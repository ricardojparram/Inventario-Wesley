-- VISTA PARA PRODUCTO_SEDE CON TODAS LAS RELACIONES
CREATE
OR REPLACE VIEW vw_producto_sede_detallado AS
SELECT
    ps.id_producto_sede,
    concat (
        tp.nombrepro,
        ' ',
        pres.peso,
        ' ',
        med.nombre,
        ' '
    ) AS presentacion_producto,
    tp.nombrepro as nombre_producto,
    pres.peso as presentacion_peso,
    med.nombre as medida,
    ps.lote,
    ps.fecha_vencimiento,
    ps.cantidad,
    s.nombre,
    tipo.nombre_t as tipo,
    clase.nombre_c as clase,
    s.id_sede
FROM
    producto_sede ps
    INNER JOIN producto p ON p.cod_producto = ps.cod_producto
    INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod
    INNER JOIN presentacion pres ON pres.cod_pres = p.cod_pres
    INNER JOIN medida med ON med.id_medida = pres.id_medida
    INNER JOIN sede s ON s.id_sede = ps.id_sede2
    INNER JOIN tipo ON tipo.id_tipo = p.id_tipo
    INNER JOIN clase ON clase.id_clase = p.id_clase;

-- VISTA PARA VENTAS CON TODAS LAS RELACIONES
CREATE
OR REPLACE VIEW vw_venta_detallada AS
SELECT
    p.id_pago,
    p.status as status_pago,
    v.num_fact,
    v.monto_fact,
    pe.cedula AS cedula,
    pe.nombres AS nombre,
    v.fecha,
    (
        SELECT
            CONCAT (v.monto_dolares, ' ', m.nombre)
        FROM
            moneda m
        WHERE
            UPPER(m.nombre) = 'DOLAR'
    ) AS total_divisa
FROM
    venta v
    INNER JOIN pagos_recibidos p ON p.num_fact = v.num_fact
    INNER JOIN venta_personal vpe ON vpe.num_fact = v.num_fact
    LEFT JOIN personal pe ON pe.cedula = vpe.cedula
WHERE
    v.status = 1
UNION
ALL
SELECT
    p.id_pago,
    p.status as status_pago,
    v.num_fact,
    v.monto_fact,
    pa.ced_pac AS cedula,
    pa.nombre AS nombre,
    v.fecha,
    (
        SELECT
            CONCAT (v.monto_dolares, ' ', m.nombre)
        FROM
            moneda m
        WHERE
            UPPER(m.nombre) = 'DOLAR'
    ) AS total_divisa
FROM
    venta v
    INNER JOIN pagos_recibidos p ON p.num_fact = v.num_fact
    INNER JOIN venta_pacientes vpa ON vpa.num_fact = v.num_fact
    LEFT JOIN pacientes pa ON pa.ced_pac = vpa.ced_pac
WHERE
    v.status = 1;

CREATE
OR REPLACE VIEW vw_entrada_inventario AS -- RECEPCION NACIONAL
SELECT
    entrada.presentacion_producto as presentacion_producto,
    entrada.cantidad as cantidad,
    entrada.tipo as tipo,
    entrada.fecha as fecha,
    s.nombre as nombre_sede,
    s.id_sede as id_sede
FROM
    (
        SELECT
            ps.presentacion_producto,
            SUM(drn.cantidad) AS cantidad,
            ps.id_sede,
            'Recepción nacional' AS tipo,
            rn.fecha AS fecha
        FROM
            vw_producto_sede_detallado ps
            INNER JOIN detalle_recepcion_nacional drn ON drn.id_producto_sede = ps.id_producto_sede
            INNER JOIN recepcion_nacional rn ON rn.id_rep_nacional = drn.id_rep_nacional
        GROUP BY
            ps.presentacion_producto,
            ps.id_sede,
            rn.fecha
        UNION
        ALL -- COMPRA 
        SELECT
            ps.presentacion_producto,
            SUM(cp.cantidad) AS cantidad,
            ps.id_sede,
            'Compra' AS tipo,
            c.fecha AS fecha
        FROM
            vw_producto_sede_detallado ps
            INNER JOIN compra_producto cp ON cp.id_producto_sede = ps.id_producto_sede
            INNER JOIN compra c ON c.orden_compra = cp.orden_compra
        GROUP BY
            ps.presentacion_producto,
            ps.id_sede,
            c.fecha
        UNION
        ALL -- RECEPCION
        SELECT
            ps.presentacion_producto,
            SUM(dr.cantidad) AS cantidad,
            ps.id_sede,
            'Recepción' AS tipo,
            rs.fecha AS fecha
        FROM
            vw_producto_sede_detallado ps
            INNER JOIN detalle_recepcion dr ON dr.id_producto_sede = ps.id_producto_sede
            INNER JOIN recepcion_sede rs ON rs.id_recepcion = dr.id_recepcion
        GROUP BY
            ps.presentacion_producto,
            ps.id_sede,
            rs.fecha
        UNION
        ALL -- CARGO
        SELECT
            ps.presentacion_producto,
            SUM(dc.cantidad) AS cantidad,
            ps.id_sede,
            'Cargo' AS tipo,
            c.fecha AS fecha
        FROM
            vw_producto_sede_detallado ps
            INNER JOIN detalle_cargo dc ON dc.id_producto_sede = ps.id_producto_sede
            INNER JOIN cargo c ON c.id_cargo = dc.id_cargo
        GROUP BY
            ps.presentacion_producto,
            ps.id_sede,
            c.fecha
    ) as entrada
    INNER JOIN sede s ON s.id_sede = entrada.id_sede;

CREATE
OR REPLACE VIEW vw_salida_inventario AS
SELECT
    salida.presentacion_producto as presentacion_producto,
    salida.cantidad as cantidad,
    salida.tipo as tipo,
    salida.fecha as fecha,
    salida.origen as origen,
    s.nombre as nombre_sede,
    s.id_sede as id_sede
FROM
    (
        SELECT
            ps.presentacion_producto,
            SUM(dt.cantidad) as cantidad,
            ps.id_sede,
            'Donaciones' as tipo,
            d.fecha as fecha,
            CASE
                WHEN dp.id_donaciones IS NOT NULL THEN 'Personal'
                WHEN dpac.id_donaciones IS NOT NULL THEN 'Paciente'
                WHEN di.id_donaciones IS NOT NULL THEN 'Institución'
                ELSE 'No especificado'
            END as origen
        FROM
            vw_producto_sede_detallado ps
            INNER JOIN det_donacion dt ON dt.id_producto_sede = ps.id_producto_sede
            INNER JOIN donaciones d ON d.id_donaciones = dt.id_donaciones
            LEFT JOIN donativo_per dp ON dp.id_donaciones = d.id_donaciones
            LEFT JOIN donativo_pac dpac ON dpac.id_donaciones = d.id_donaciones
            LEFT JOIN donativo_int di ON di.id_donaciones = d.id_donaciones
        GROUP BY
            ps.presentacion_producto,
            ps.id_sede,
            d.fecha,
            origen
        UNION
        ALL -- TRANSFERENCIA
        SELECT
            ps.presentacion_producto,
            SUM(dt.cantidad) as cantidad,
            ps.id_sede,
            'Transferencia' as tipo,
            t.fecha as fecha,
            '' as origen
        FROM
            vw_producto_sede_detallado ps
            INNER JOIN detalle_transferencia dt ON dt.id_producto_sede = ps.id_producto_sede
            INNER JOIN transferencia t ON t.id_transferencia = dt.id_transferencia
        GROUP BY
            ps.presentacion_producto,
            ps.id_sede,
            t.fecha
        UNION
        ALL -- VENTAS
        SELECT
            ps.presentacion_producto,
            SUM(vprod.cantidad) as cantidad,
            ps.id_sede,
            'Ventas' as tipo,
            v.fecha as fecha,
            CASE
                WHEN vper.id_venta IS NOT NULL THEN 'Personal'
                WHEN vpac.id_venta IS NOT NULL THEN 'Paciente'
                ELSE 'No especificado'
            END as origen
        FROM
            vw_producto_sede_detallado ps
            INNER JOIN venta_producto vprod ON vprod.id_producto_sede = ps.id_producto_sede
            INNER JOIN venta v ON v.num_fact = vprod.num_fact
            LEFT JOIN venta_pacientes vpac ON vpac.num_fact = v.num_fact
            LEFT JOIN venta_personal vper ON vper.num_fact = v.num_fact
        GROUP BY
            ps.presentacion_producto,
            ps.id_sede,
            v.fecha,
            origen
        UNION
        ALL -- DESCARGO
        SELECT
            ps.presentacion_producto,
            SUM(dd.cantidad) as cantidad,
            ps.id_sede,
            'Descargo' as tipo,
            d.fecha as fecha,
            '' as origen
        FROM
            vw_producto_sede_detallado ps
            INNER JOIN detalle_descargo dd ON dd.id_producto_sede = ps.id_producto_sede
            INNER JOIN descargo d ON d.id_descargo = dd.id_descargo
        GROUP BY
            ps.presentacion_producto,
            ps.id_sede,
            d.fecha
    ) as salida
    INNER JOIN sede s ON s.id_sede = salida.id_sede;

CREATE
OR REPLACE VIEW vw_donaciones_por_tipo AS
SELECT
    q.id_donaciones,
    q.fecha,
    q.tipo_donacion,
    q.id,
    q.nombre,
    ps.id_sede
FROM
    (
        SELECT
            d.id_donaciones,
            d.fecha as fecha,
            'Institución' as tipo_donacion,
            CAST(di.rif_int AS CHAR) as id,
            i.razon_social as nombre
        FROM
            donaciones d
            INNER JOIN donativo_int di ON di.id_donaciones = d.id_donaciones
            INNER JOIN instituciones i ON i.rif_int = di.rif_int
        UNION
        SELECT
            d.id_donaciones,
            d.fecha as fecha,
            'Paciente' as tipo_donacion,
            CAST(dp.ced_pac AS CHAR) as id,
            CONCAT(p.nombre, ' ', p.apellido) as nombre
        FROM
            donaciones d
            INNER JOIN donativo_pac dp ON dp.id_donaciones = d.id_donaciones
            INNER JOIN pacientes p ON p.ced_pac = dp.ced_pac
        UNION
        SELECT
            d.id_donaciones,
            d.fecha as fecha,
            'Personal' as tipo_donacion,
            CAST(dper.cedula AS CHAR) as id,
            CONCAT(per.nombres, ' ', per.apellidos) as nombre
        FROM
            donaciones d
            INNER JOIN donativo_per dper ON dper.id_donaciones = d.id_donaciones
            INNER JOIN personal per ON dper.cedula = per.cedula
    ) as q
    INNER JOIN det_donacion dd ON dd.id_donaciones = q.id_donaciones
    INNER JOIN producto_sede ps ON ps.id_producto_sede = dd.id_producto_sede
GROUP BY
    q.id_donaciones;