use wesley;

INSERT INTO
  moneda (id_moneda, nombre, valor, status)
VALUES
  (1, 'Dolar', 38, 1),
  (2, 'Euro', 0, 1);

INSERT INTO
  cambio (id_cambio, cambio, fecha, moneda, status)
VALUES
  (1, 38.00, '2024-06-09 14:23:28', 1, 1);

INSERT INTO
  sede (id_sede, nombre, telefono, direccion, status)
VALUES
  (
    1,
    'Sede Cabudare (Principal)',
    '04127774522',
    'Cabudare',
    1
  ),
  (2, 'Sede Coro', '04121381283', 'Coro', 1),
  (3, 'Sede Lara', '04121020312', 'La Rotaria', 1);

INSERT INTO
  clase (id_clase, nombre_c, status)
VALUES
  (1, 'Antiinflamatorio', 1),
  (2, 'Antidepresivo', 1);

INSERT INTO
  tipo_producto (id_tipoprod, nombrepro, status)
VALUES
  (1, 'Acetaminofén', 1),
  (2, 'Omeprazol', 1),
  (3, 'Ibuprofeno', 1);

INSERT INTO
  tipo (id_tipo, nombre_t, status)
VALUES
  (1, 'Pediatrico', 1),
  (2, 'Adulto', 1);

INSERT INTO
  medida (id_medida, nombre, status)
VALUES
  (1, 'mg', 1),
  (2, 'ml', 1);

INSERT INTO
  presentacion (cod_pres, cantidad, id_medida, peso, status)
VALUES
  (1, 12, 1, 20.00, 1),
  (2, 10, 1, 200.00, 1);

INSERT INTO
  laboratorio (rif_laboratorio, direccion, razon_social, status)
VALUES
  (
    'J-000876266',
    'Urbanización Industrial Lebrun Edificio Cofasa piso 3, Petare. Caracas.',
    ' Laboratorio Cofasa',
    1
  );

INSERT INTO
  producto (
    cod_producto,
    id_tipoprod,
    contraindicaciones,
    composicion,
    posologia,
    id_tipo,
    id_clase,
    cod_pres,
    status
  )
VALUES
  (
    '1',
    1,
    'Pastillo A1',
    ' C8H9NO2',
    'La dosis habitual es de 325 mg a 650 mg. Tómelo con una frecuencia de 4 a 6 horas, según sea necesario, hasta 4 veces en un período de 24 horas. La dosis máxima puede variar entre 3,000 mg y 4,000 mg',
    2,
    1,
    1,
    1
  ),
  (
    '2',
    3,
    'Pastillo B2',
    'C13H18O2',
    'En adultos y adolescentes de 14 a 18 años se toma un comprimido (600 mg) cada 6 a 8 horas, dependiendo de la intensidad del cuadro y de la respuesta al tratamiento.',
    2,
    1,
    2,
    1
  );

INSERT INTO
  producto_sede (
    id_producto_sede,
    cod_producto,
    lote,
    fecha_vencimiento,
    id_sede,
    cantidad
  )
VALUES
  (1, '2', '000001', '2026-03-25', 1, 200),
  (2, '1', '000002', '2024-05-17', 1, 300);

INSERT INTO
  proveedor (
    rif_proveedor,
    direccion,
    razon_social,
    contacto,
    status
  )
VALUES
  (
    'J-123123123',
    'Urbanización Industrial Lebrun Edificio Cofasa piso 3, Petare. Caracas.',
    'Proveedor ejemplo',
    '',
    1
  );

INSERT INTO
  contacto_prove (id_contacto_prove, telefono, rif_proveedor)
VALUES
  (1, '0412523232', 'J-123123123');

INSERT INTO
  forma_pago(id_forma_pago, tipo_pago, status)
VALUES
  (1, 'Pago movil', 1),
  (2, 'Transferencia', 1),
  (3, 'Efectivo', 1);

INSERT INTO
  rol (id_rol, nombre, status)
VALUES
  (1, 'Administrador', 1),
  (2, 'Gerente', 1),
  (3, 'Empleado', 1);

INSERT INTO
  tipo_empleado (tipo_em, nombre_e, status)
VALUES
  (1, 'Gerente', 1),
  (2, 'Doctor', 1);

INSERT INTO
  instituciones (
    rif_int,
    razon_social,
    direccion,
    contacto,
    status
  )
VALUES
  (
    'J-123456787',
    'Jose Antonio Maria Pineda',
    'Vargas , centro',
    '04142342324',
    1
  ),
  (
    'J-456789017',
    'Hospital Universitario de Barquisimeto',
    'Calle Hospital, Barquisimeto',
    '02511234568',
    1
  );

INSERT INTO
  `personal` (
    `cedula`,
    `nombres`,
    `apellidos`,
    `direccion`,
    `id_sede`,
    `edad`,
    `telefono`,
    `correo`,
    `tipo_em`,
    `status`
  )
VALUES
  (
    'V-16794406',
    'Jose',
    'Ramon',
    'Cabudare ',
    1,
    '1980-01-23',
    '042145699810',
    'Jose33@gmail.com',
    2,
    1
  ),
  (
    'V-30125380',
    'Roberto',
    'Vargas',
    'Urb Las casitas',
    2,
    '1976-04-19',
    '04120503888',
    'ricardo.prxr16@gmail.com',
    2,
    1
  ),
  (
    'V-30233547',
    'Enmanuel Josue',
    'Torres Rodriguez',
    'Tierra Negra',
    1,
    '2001-07-20',
    '04123799406',
    'enmanuel.josue.torres13@gmail.com',
    1,
    1
  );

INSERT INTO
  `pacientes` (`ced_pac`, `nombre`, `apellido`, `status`)
VALUES
  ('21727935', 'Eduardo', 'Torres', 1),
  ('28256649', 'Yelimar ', 'Hernández', 1),
  ('30125380', 'Ricardo ', 'Parra ', 1),
  ('30233547', 'Enmanuel', 'Torres', 1),
  ('30349137', 'Aparicio', 'Víctor ', 1);

INSERT INTO
  usuario (
    cedula,
    nombre,
    apellido,
    correo,
    password,
    rol,
    img,
    status
  )
VALUES
  (
    'V-123123123',
    'admin',
    'admin',
    'admin@admin.com',
    '$2y$10$w1yanGxu3hASPys0NJDa5.xH685Gd0IIjUKbFfzbcDWf1BDyWvVJe',
    1,
    NULL,
    1
  );

INSERT INTO
  modulos (id_modulo, nombre, status)
VALUES
  (1, 'Personal', 1),
  (2, 'Ventas', 1),
  (3, 'Compras', 1),
  (4, 'Metodo pago', 1),
  (5, 'Moneda', 1),
  (6, 'Producto', 1),
  (7, 'Laboratorio', 1),
  (8, 'Proveedor', 1),
  (9, 'Clase', 1),
  (10, 'Tipo', 1),
  (11, 'Presentacion', 1),
  (12, 'Reportes', 1),
  (13, 'Usuarios', 1),
  (14, 'Bitacora', 1),
  (15, 'Recepcion nacional', 1),
  (16, 'Recepcion', 1),
  (17, 'Roles', 1),
  (18, 'Transferencia', 1),
  (19, 'Sedes', 1),
  (20, 'Pagos recibidos', 1),
  (21, 'Donativos pacientes', 1),
  (22, 'Donativos personal', 1),
  (23, 'Donativos instituciones', 1),
  (24, 'Medida', 1),
  (25, 'Inventario', 1),
  (26, 'Historial de inventario', 1),
  (27, 'Producto dañado', 1),
  (28, 'Tipo empleado', 1),
  (29, 'Cargo', 1),
  (30, 'Descargo', 1),
  (31, 'Mantenimiento', 1),
  (32, 'Tipo producto', 1),
  (33, 'Instituciones', 1);

INSERT INTO
  permisos (id_rol, id_modulo, nombre_accion, status)
VALUES
  (1, 1, 'Registrar', 1),
  (1, 1, 'Editar', 1),
  (1, 1, 'Eliminar', 1),
  (1, 1, 'Consultar', 1),
  (1, 2, 'Registrar', 1),
  (1, 2, 'Eliminar', 1),
  (1, 2, 'Consultar', 1),
  (1, 3, 'Registrar', 1),
  (1, 3, 'Eliminar', 1),
  (1, 3, 'Consultar', 1),
  (1, 4, 'Registrar', 1),
  (1, 4, 'Editar', 1),
  (1, 4, 'Eliminar', 1),
  (1, 4, 'Consultar', 1),
  (1, 5, 'Registrar', 1),
  (1, 5, 'Editar', 1),
  (1, 5, 'Eliminar', 1),
  (1, 5, 'Consultar', 1),
  (1, 6, 'Registrar', 1),
  (1, 6, 'Editar', 1),
  (1, 6, 'Eliminar', 1),
  (1, 6, 'Consultar', 1),
  (1, 7, 'Registrar', 1),
  (1, 7, 'Editar', 1),
  (1, 7, 'Eliminar', 1),
  (1, 7, 'Consultar', 1),
  (1, 8, 'Registrar', 1),
  (1, 8, 'Editar', 1),
  (1, 8, 'Eliminar', 1),
  (1, 8, 'Consultar', 1),
  (1, 9, 'Registrar', 1),
  (1, 9, 'Editar', 1),
  (1, 9, 'Eliminar', 1),
  (1, 9, 'Consultar', 1),
  (1, 10, 'Registrar', 1),
  (1, 10, 'Editar', 1),
  (1, 10, 'Eliminar', 1),
  (1, 10, 'Consultar', 1),
  (1, 11, 'Registrar', 1),
  (1, 11, 'Editar', 1),
  (1, 11, 'Eliminar', 1),
  (1, 11, 'Consultar', 1),
  (1, 12, 'Consultar', 1),
  (1, 12, 'Exportar reporte', 1),
  (1, 13, 'Registrar', 1),
  (1, 13, 'Editar', 1),
  (1, 13, 'Eliminar', 1),
  (1, 13, 'Consultar', 1),
  (1, 14, 'Consultar', 1),
  (1, 15, 'Registrar', 1),
  (1, 15, 'Eliminar', 1),
  (1, 15, 'Consultar', 1),
  (1, 16, 'Registrar', 1),
  (1, 16, 'Eliminar', 1),
  (1, 16, 'Consultar', 1),
  (1, 17, 'Modificar acciones', 1),
  (1, 17, 'Registrar', 1),
  (1, 17, 'Editar', 1),
  (1, 17, 'Eliminar', 1),
  (1, 17, 'Consultar', 1),
  (1, 18, 'Registrar', 1),
  (1, 18, 'Eliminar', 1),
  (1, 18, 'Consultar', 1),
  (1, 19, 'Registrar', 1),
  (1, 19, 'Editar', 1),
  (1, 19, 'Eliminar', 1),
  (1, 19, 'Consultar', 1),
  (1, 20, 'Consultar', 1),
  (1, 20, 'Comprobar pago', 1),
  (1, 21, 'Registrar', 1),
  (1, 21, 'Eliminar', 1),
  (1, 21, 'Consultar', 1),
  (1, 22, 'Registrar', 1),
  (1, 22, 'Eliminar', 1),
  (1, 22, 'Consultar', 1),
  (1, 23, 'Registrar', 1),
  (1, 23, 'Eliminar', 1),
  (1, 23, 'Consultar', 1),
  (1, 24, 'Registrar', 1),
  (1, 24, 'Editar', 1),
  (1, 24, 'Eliminar', 1),
  (1, 24, 'Consultar', 1),
  (1, 25, 'Consultar', 1),
  (1, 26, 'Consultar', 1),
  (1, 27, 'Consultar', 1),
  (1, 28, 'Registrar', 1),
  (1, 28, 'Editar', 1),
  (1, 28, 'Eliminar', 1),
  (1, 28, 'Consultar', 1),
  (1, 29, 'Registrar', 1),
  (1, 29, 'Eliminar', 1),
  (1, 29, 'Consultar', 1),
  (1, 30, 'Registrar', 1),
  (1, 30, 'Eliminar', 1),
  (1, 30, 'Consultar', 1),
  (1, 31, 'Backup', 1),
  (1, 31, 'Consultar', 1),
  (1, 32, 'Registrar', 1),
  (1, 32, 'Editar', 1),
  (1, 32, 'Eliminar', 1),
  (1, 32, 'Consultar', 1),
  (1, 33, 'Registrar', 1),
  (1, 33, 'Editar', 1),
  (1, 33, 'Eliminar', 1),
  (1, 33, 'Consultar', 1),
  -- siguiente rol
  (2, 1, 'Registrar', 1),
  (2, 1, 'Editar', 1),
  (2, 1, 'Eliminar', 1),
  (2, 1, 'Consultar', 1),
  (2, 2, 'Registrar', 1),
  (2, 2, 'Eliminar', 1),
  (2, 2, 'Consultar', 1),
  (2, 3, 'Registrar', 1),
  (2, 3, 'Eliminar', 1),
  (2, 3, 'Consultar', 1),
  (2, 4, 'Registrar', 1),
  (2, 4, 'Editar', 1),
  (2, 4, 'Eliminar', 1),
  (2, 4, 'Consultar', 1),
  (2, 5, 'Registrar', 1),
  (2, 5, 'Editar', 1),
  (2, 5, 'Eliminar', 1),
  (2, 5, 'Consultar', 1),
  (2, 6, 'Registrar', 1),
  (2, 6, 'Editar', 1),
  (2, 6, 'Eliminar', 1),
  (2, 6, 'Consultar', 1),
  (2, 7, 'Registrar', 1),
  (2, 7, 'Editar', 1),
  (2, 7, 'Eliminar', 1),
  (2, 7, 'Consultar', 1),
  (2, 8, 'Registrar', 1),
  (2, 8, 'Editar', 1),
  (2, 8, 'Eliminar', 1),
  (2, 8, 'Consultar', 1),
  (2, 9, 'Registrar', 1),
  (2, 9, 'Editar', 1),
  (2, 9, 'Eliminar', 1),
  (2, 9, 'Consultar', 1),
  (2, 10, 'Registrar', 1),
  (2, 10, 'Editar', 1),
  (2, 10, 'Eliminar', 1),
  (2, 10, 'Consultar', 1),
  (2, 11, 'Registrar', 1),
  (2, 11, 'Editar', 1),
  (2, 11, 'Eliminar', 1),
  (2, 11, 'Consultar', 1),
  (2, 12, 'Consultar', 1),
  (2, 12, 'Exportar reporte', 1),
  (2, 13, 'Registrar', 1),
  (2, 13, 'Editar', 1),
  (2, 13, 'Eliminar', 1),
  (2, 13, 'Consultar', 1),
  (2, 14, 'Consultar', 1),
  (2, 15, 'Registrar', 1),
  (2, 15, 'Eliminar', 1),
  (2, 15, 'Consultar', 1),
  (2, 16, 'Registrar', 1),
  (2, 16, 'Eliminar', 1),
  (2, 16, 'Consultar', 1),
  (2, 17, 'Modificar acciones', 1),
  (2, 17, 'Registrar', 1),
  (2, 17, 'Editar', 1),
  (2, 17, 'Eliminar', 1),
  (2, 17, 'Consultar', 1),
  (2, 18, 'Registrar', 1),
  (2, 18, 'Eliminar', 1),
  (2, 18, 'Consultar', 1),
  (2, 19, 'Registrar', 1),
  (2, 19, 'Editar', 1),
  (2, 19, 'Eliminar', 1),
  (2, 19, 'Consultar', 1),
  (2, 20, 'Consultar', 1),
  (2, 20, 'Comprobar pago', 1),
  (2, 21, 'Registrar', 1),
  (2, 21, 'Eliminar', 1),
  (2, 21, 'Consultar', 1),
  (2, 22, 'Registrar', 1),
  (2, 22, 'Eliminar', 1),
  (2, 22, 'Consultar', 1),
  (2, 23, 'Registrar', 1),
  (2, 23, 'Eliminar', 1),
  (2, 23, 'Consultar', 1),
  (2, 24, 'Registrar', 1),
  (2, 24, 'Editar', 1),
  (2, 24, 'Eliminar', 1),
  (2, 24, 'Consultar', 1),
  (2, 25, 'Consultar', 1),
  (2, 26, 'Consultar', 1),
  (2, 27, 'Consultar', 1),
  (2, 28, 'Registrar', 1),
  (2, 28, 'Editar', 1),
  (2, 28, 'Eliminar', 1),
  (2, 28, 'Consultar', 1),
  (2, 29, 'Registrar', 1),
  (2, 29, 'Eliminar', 1),
  (2, 29, 'Consultar', 1),
  (2, 30, 'Registrar', 1),
  (2, 30, 'Eliminar', 1),
  (2, 30, 'Consultar', 1),
  (2, 31, 'Backup', 1),
  (2, 31, 'Consultar', 1),
  (2, 32, 'Registrar', 1),
  (2, 32, 'Editar', 1),
  (2, 32, 'Eliminar', 1),
  (2, 32, 'Consultar', 1),
  (2, 33, 'Registrar', 1),
  (2, 33, 'Editar', 1),
  (2, 33, 'Eliminar', 1),
  (2, 33, 'Consultar', 1),
  -- siguiente rol
  (3, 1, 'Registrar', 1),
  (3, 1, 'Editar', 1),
  (3, 1, 'Eliminar', 1),
  (3, 1, 'Consultar', 1),
  (3, 2, 'Registrar', 1),
  (3, 2, 'Eliminar', 1),
  (3, 2, 'Consultar', 1),
  (3, 3, 'Registrar', 1),
  (3, 3, 'Eliminar', 1),
  (3, 3, 'Consultar', 1),
  (3, 4, 'Registrar', 1),
  (3, 4, 'Editar', 1),
  (3, 4, 'Eliminar', 1),
  (3, 4, 'Consultar', 1),
  (3, 5, 'Registrar', 1),
  (3, 5, 'Editar', 1),
  (3, 5, 'Eliminar', 1),
  (3, 5, 'Consultar', 1),
  (3, 6, 'Registrar', 1),
  (3, 6, 'Editar', 1),
  (3, 6, 'Eliminar', 1),
  (3, 6, 'Consultar', 1),
  (3, 7, 'Registrar', 1),
  (3, 7, 'Editar', 1),
  (3, 7, 'Eliminar', 1),
  (3, 7, 'Consultar', 1),
  (3, 8, 'Registrar', 1),
  (3, 8, 'Editar', 1),
  (3, 8, 'Eliminar', 1),
  (3, 8, 'Consultar', 1),
  (3, 9, 'Registrar', 1),
  (3, 9, 'Editar', 1),
  (3, 9, 'Eliminar', 1),
  (3, 9, 'Consultar', 1),
  (3, 10, 'Registrar', 1),
  (3, 10, 'Editar', 1),
  (3, 10, 'Eliminar', 1),
  (3, 10, 'Consultar', 1),
  (3, 11, 'Registrar', 1),
  (3, 11, 'Editar', 1),
  (3, 11, 'Eliminar', 1),
  (3, 11, 'Consultar', 1),
  (3, 12, 'Consultar', 1),
  (3, 12, 'Exportar reporte', 1),
  (3, 13, 'Registrar', 1),
  (3, 13, 'Editar', 1),
  (3, 13, 'Eliminar', 1),
  (3, 13, 'Consultar', 1),
  (3, 14, 'Consultar', 1),
  (3, 15, 'Registrar', 1),
  (3, 15, 'Eliminar', 1),
  (3, 15, 'Consultar', 1),
  (3, 16, 'Registrar', 1),
  (3, 16, 'Eliminar', 1),
  (3, 16, 'Consultar', 1),
  (3, 17, 'Modificar acciones', 1),
  (3, 17, 'Registrar', 1),
  (3, 17, 'Editar', 1),
  (3, 17, 'Eliminar', 1),
  (3, 17, 'Consultar', 1),
  (3, 18, 'Registrar', 1),
  (3, 18, 'Eliminar', 1),
  (3, 18, 'Consultar', 1),
  (3, 19, 'Registrar', 1),
  (3, 19, 'Editar', 1),
  (3, 19, 'Eliminar', 1),
  (3, 19, 'Consultar', 1),
  (3, 20, 'Consultar', 1),
  (3, 20, 'Comprobar pago', 1),
  (3, 21, 'Registrar', 1),
  (3, 21, 'Eliminar', 1),
  (3, 21, 'Consultar', 1),
  (3, 22, 'Registrar', 1),
  (3, 22, 'Eliminar', 1),
  (3, 22, 'Consultar', 1),
  (3, 23, 'Registrar', 1),
  (3, 23, 'Eliminar', 1),
  (3, 23, 'Consultar', 1),
  (3, 24, 'Registrar', 1),
  (3, 24, 'Editar', 1),
  (3, 24, 'Eliminar', 1),
  (3, 24, 'Consultar', 1),
  (3, 25, 'Consultar', 1),
  (3, 26, 'Consultar', 1),
  (3, 27, 'Consultar', 1),
  (3, 28, 'Registrar', 1),
  (3, 28, 'Editar', 1),
  (3, 28, 'Eliminar', 1),
  (3, 28, 'Consultar', 1),
  (3, 29, 'Registrar', 1),
  (3, 29, 'Eliminar', 1),
  (3, 29, 'Consultar', 1),
  (3, 30, 'Registrar', 1),
  (3, 30, 'Eliminar', 1),
  (3, 30, 'Consultar', 1),
  (3, 31, 'Backup', 1),
  (3, 31, 'Consultar', 1),
  (3, 32, 'Registrar', 1),
  (3, 32, 'Editar', 1),
  (3, 32, 'Eliminar', 1),
  (3, 32, 'Consultar', 1),
  (3, 33, 'Registrar', 1),
  (3, 33, 'Editar', 1),
  (3, 33, 'Eliminar', 1),
  (3, 33, 'Consultar', 1);