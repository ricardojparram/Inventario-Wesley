-- VISTA PARA PRODUCTO_SEDE CON TODAS LAS RELACIONES
CREATE
OR REPLACE VIEW vw_producto_sede_detallado AS
SELECT
    ps.id_producto_sede,
    concat(
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
    INNER JOIN sede s ON s.id_sede = ps.id_sede
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
            CONCAT(v.monto_dolares, ' ', m.nombre)
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
            CONCAT(v.monto_dolares, ' ', m.nombre)
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
    v.status = 1