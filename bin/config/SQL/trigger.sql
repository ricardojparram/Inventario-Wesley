-- --------------------------------------------------------
--
-- TRIGGER detectar_lotes_terminar, Listo
--
DELIMITER //

CREATE TRIGGER detectar_lotes_terminar
AFTER UPDATE ON producto_sede
FOR EACH ROW
BEGIN
    DECLARE cantidad_restante INT;
    DECLARE mensaje VARCHAR(255);
    DECLARE producto_nombre VARCHAR(255);
    DECLARE sede_nombre VARCHAR(255);
    
    SET cantidad_restante = NEW.cantidad;
    
    SELECT CONCAT(tp.nombrepro, ' ', pr.peso, ' ', m.nombre), s.nombre INTO producto_nombre, sede_nombre
    FROM producto_sede ps
    INNER JOIN producto p ON p.cod_producto = NEW.cod_producto
    INNER JOIN tipo_producto tp ON tp.id_tipoprod = p.id_tipoprod
    INNER JOIN sede s ON s.id_sede = ps.id_sede
    INNER JOIN presentacion pr ON pr.cod_pres = p.cod_pres
    INNER JOIN medida m ON m.id_medida = pr.id_medida
    WHERE ps.id_producto_sede = NEW.id_producto_sede;
    
    IF cantidad_restante <= 10 AND cantidad_restante >= 0 THEN
        SET mensaje = CONCAT('El lote ', producto_nombre, ' esta cerca de terminar en la sede ', sede_nombre, '. Quedan ', cantidad_restante, ' unidades.');
        
        INSERT INTO notificaciones (titulo, mensaje, fecha, status)
        VALUES (CONCAT('Lote ', producto_nombre, ' cerca de terminar'), mensaje, NOW(), 1); 
    END IF;
END;
//

DELIMITER ;


-- --------------------------------------------------------
--
-- TRIGGER guardar_productos_vencidos, Listo
--

DELIMITER //
CREATE TRIGGER `insert_pago_recibido_status_0` AFTER INSERT ON `pagos_recibidos`
FOR EACH ROW
BEGIN
    DECLARE cedula VARCHAR(50);
    DECLARE nombre VARCHAR(50);
    DECLARE fecha DATE;
    DECLARE total_divisa VARCHAR(50);
    DECLARE mensaje TEXT;

    IF NEW.status = 0 THEN
        IF EXISTS (SELECT 1 FROM venta v
                   INNER JOIN venta_personal vpe ON vpe.num_fact = v.num_fact
                   WHERE v.num_fact = NEW.num_fact AND v.status = 1) THEN
            SELECT vpe.cedula, pe.nombres, v.fecha, CONCAT(v.monto_dolares, ' ', m.nombre) INTO cedula, nombre, fecha, total_divisa
            FROM venta v
            INNER JOIN venta_personal vpe ON vpe.num_fact = v.num_fact
            LEFT JOIN personal pe ON pe.cedula = vpe.cedula
            INNER JOIN moneda m ON m.nombre = 'DOLAR'
            WHERE v.num_fact = NEW.num_fact
            LIMIT 1;

            SET mensaje = CONCAT('Pago recibido de ', nombre, ' (cedula: ', cedula, '). Fecha: ', fecha, '. Total en ', total_divisa);
        ELSEIF EXISTS (SELECT 1 FROM venta v
                       INNER JOIN venta_pacientes vpa ON vpa.num_fact = v.num_fact
                       WHERE v.num_fact = NEW.num_fact AND v.status = 1) THEN
            SELECT vpa.ced_pac, pa.nombre, v.fecha, CONCAT(v.monto_dolares, ' ', m.nombre) INTO cedula, nombre, fecha, total_divisa
            FROM venta v
            INNER JOIN venta_pacientes vpa ON vpa.num_fact = v.num_fact
            LEFT JOIN pacientes pa ON pa.ced_pac = vpa.ced_pac
            INNER JOIN moneda m ON m.nombre = 'DOLAR'
            WHERE v.num_fact = NEW.num_fact
            LIMIT 1;

            SET mensaje = CONCAT('Pago recibido de ', nombre, ' (cedula: ', cedula, '). Fecha: ', fecha, '. Total en ', total_divisa);
        END IF;

        INSERT INTO notificaciones (titulo, mensaje, fecha, status)
        VALUES (CONCAT('Pago recibido de ', nombre), mensaje, NOW(), 1);
    END IF;
END;
//
DELIMITER ;
