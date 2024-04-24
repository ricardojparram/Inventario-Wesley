--  BASE DE DATOS PARA LA FUNDACION CENTRO MEDICO WESLEY
DROP DATABASE IF EXISTS wesley;

CREATE DATABASE wesley CHARACTER SET utf8mb4;

USE wesley;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla rol
--
CREATE TABLE rol (
  id_rol int(11) AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(50) NOT NULL,
  status tinyint(1) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla modulos
--
CREATE TABLE modulos (
  id_modulo int(11) AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(50) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla usuario
--
CREATE TABLE usuario (
  cedula varchar(15) PRIMARY KEY,
  nombre varchar(50) NOT NULL,
  apellido varchar(50) NOT NULL,
  correo varchar(320) NOT NULL,
  password varchar(60) NOT NULL,
  rol int(11) NOT NULL,
  img varchar(300) DEFAULT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (rol) REFERENCES rol (id_rol) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla bitacora
--
CREATE TABLE bitacora (
  id_bitacora int(11) AUTO_INCREMENT PRIMARY KEY,
  cedula varchar(15) NOT NULL,
  descripcion varchar(50) NOT NULL,
  fecha datetime NOT NULL DEFAULT current_timestamp(),
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (cedula) REFERENCES usuario (cedula) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla permisos
--
CREATE TABLE permisos (
  id_permiso int(11) AUTO_INCREMENT PRIMARY KEY,
  id_rol int(11) NOT NULL,
  id_modulo int(11) NOT NULL,
  nombre_accion varchar(40) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (id_rol) REFERENCES rol (id_rol) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_modulo) REFERENCES modulos (id_modulo) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla moneda
--
CREATE TABLE moneda (
  id_moneda int(11) AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(50) NOT NULL,
  valor decimal(10, 0) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla cambio
--
CREATE TABLE cambio (
  id_cambio int(11) AUTO_INCREMENT PRIMARY KEY,
  cambio decimal(10, 2) NOT NULL,
  fecha datetime NOT NULL DEFAULT current_timestamp(),
  moneda int(11) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (moneda) REFERENCES moneda (id_moneda) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla tipo_producto
--
CREATE TABLE tipo_producto (
  id_tipoprod int(11) AUTO_INCREMENT PRIMARY KEY,
  nombrepro varchar(50) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla tipo
--
CREATE TABLE tipo (
  id_tipo int(11) AUTO_INCREMENT PRIMARY KEY,
  nombre_t varchar(50) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla clase
--
CREATE TABLE clase (
  id_clase int(11) AUTO_INCREMENT PRIMARY KEY,
  nombre_c varchar(50) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla medida
--
CREATE TABLE medida (
  id_medida int(15) AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(50) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla presentacion
--
CREATE TABLE presentacion (
  cod_pres int(11) AUTO_INCREMENT PRIMARY KEY,
  cantidad smallint(10) UNSIGNED NOT NULL,
  id_medida int(15) NOT NULL,
  peso decimal(10, 2) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (id_medida) REFERENCES medida (id_medida) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla proveedor
--
CREATE TABLE proveedor (
  rif_proveedor varchar(20) PRIMARY KEY,
  direccion varchar(200) NOT NULL,
  razon_social varchar(200) NOT NULL,
  contacto varchar(50) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla contacto_prove
--
CREATE TABLE contacto_prove (
  id_contacto_prove int(11) AUTO_INCREMENT PRIMARY KEY,
  telefono varchar(20) NOT NULL,
  rif_proveedor varchar(20) NOT NULL,
  FOREIGN KEY (rif_proveedor) REFERENCES proveedor (rif_proveedor) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla laboratorio
--
CREATE TABLE laboratorio (
  rif_laboratorio varchar(20) PRIMARY KEY,
  direccion varchar(200) NOT NULL,
  razon_social varchar(200) DEFAULT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla producto
--
CREATE TABLE producto (
  cod_producto varchar(15) PRIMARY KEY,
  id_tipoprod int(11) DEFAULT NULL,
  composicion varchar(60) NOT NULL,
  posologia varchar(200) NOT NULL,
  contraindicaciones varchar(250) NOT NULL,
  rif_laboratorio varchar(20) DEFAULT NULL,
  id_tipo int(11) DEFAULT NULL,
  id_clase int(11) DEFAULT NULL,
  cod_pres int(11) DEFAULT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (cod_pres) REFERENCES presentacion (cod_pres) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_clase) REFERENCES clase (id_clase) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (rif_laboratorio) REFERENCES laboratorio (rif_laboratorio),
  FOREIGN KEY (id_tipoprod) REFERENCES tipo_producto (id_tipoprod) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_tipo) REFERENCES tipo (id_tipo) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla img_producto
--
CREATE TABLE img_producto (
  cod_producto varchar(15) NOT NULL,
  img varchar(300) CHARACTER SET utf16 COLLATE utf16_spanish2_ci NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (cod_producto) REFERENCES producto (cod_producto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla sede
--
CREATE TABLE sede (
  id_sede int(11) AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(50) NOT NULL,
  telefono varchar(15) NOT NULL,
  direccion varchar(200) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla producto_sede
--
CREATE TABLE producto_sede (
  id_producto_sede int(50) AUTO_INCREMENT PRIMARY KEY,
  cod_producto varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  lote varchar(15) NOT NULL,
  fecha_vencimiento date NOT NULL,
  id_sede int(11) NOT NULL,
  cantidad int(11) NOT NULL,
  FOREIGN KEY (id_sede) REFERENCES sede (id_sede) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (cod_producto) REFERENCES producto (cod_producto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla descargo
--
CREATE TABLE descargo (
  id_descargo int(11) AUTO_INCREMENT PRIMARY KEY,
  fecha datetime NOT NULL DEFAULT current_timestamp(),
  num_descargo int(11) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla producto_dañado
--
-- CREATE TABLE producto_dañado (
--   id_pro_dañado int(11) AUTO_INCREMENT PRIMARY KEY,
--   cantidad smallint(10) UNSIGNED NOT NULL,
--   id_descargo int(11) NOT NULL,
--   FOREIGN KEY (id_descargo) REFERENCES descargo (id_descargo) ON DELETE CASCADE ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla detalle_descargo
--
CREATE TABLE detalle_descargo (
  id_detalle int(11) AUTO_INCREMENT PRIMARY KEY,
  id_descargo int(11) NOT NULL,
  id_producto_sede int(50) NOT NULL,
  cantidad int(11) NOT NULL,
  FOREIGN KEY (id_producto_sede) REFERENCES producto_sede (id_producto_sede) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_descargo) REFERENCES descargo (id_descargo) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla compra
--
CREATE TABLE compra (
  orden_compra varchar(12) PRIMARY KEY,
  fecha date NOT NULL,
  monto_total decimal(10, 2) NOT NULL,
  ced_prove varchar(20) NOT NULL,
  status tinyint(11) UNSIGNED NOT NULL,
  FOREIGN KEY (ced_prove) REFERENCES proveedor (rif_proveedor) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla compra_producto
--
CREATE TABLE compra_producto (
  id_detalle int(11) AUTO_INCREMENT PRIMARY KEY,
  id_producto_sede int(50) NOT NULL,
  orden_compra varchar(15) NOT NULL,
  cantidad int(12) NOT NULL,
  precio_compra decimal(10, 2) NOT NULL,
  FOREIGN KEY (id_producto_sede) REFERENCES producto_sede (id_producto_sede) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (orden_compra) REFERENCES compra (orden_compra) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla donaciones
--
CREATE TABLE donaciones (
  id_donaciones int(11) AUTO_INCREMENT PRIMARY KEY,
  fecha datetime NOT NULL DEFAULT current_timestamp(),
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla det_donacion
--
CREATE TABLE det_donacion (
  id_detalle int(11) AUTO_INCREMENT PRIMARY KEY,
  id_producto_sede int(50) NOT NULL,
  cantidad int(12) NOT NULL,
  id_donaciones int(11) NOT NULL,
  FOREIGN KEY (id_donaciones) REFERENCES donaciones (id_donaciones) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_producto_sede) REFERENCES producto_sede (id_producto_sede) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla donativo_pac
--
CREATE TABLE donativo_pac (
  id_donativopac int(11) AUTO_INCREMENT PRIMARY KEY,
  ced_pac varchar(15) NOT NULL,
  id_donaciones int(11) NOT NULL,
  FOREIGN KEY (id_donaciones) REFERENCES donaciones (id_donaciones) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla donativo_per
--
CREATE TABLE donativo_per (
  id_donativo int(11) AUTO_INCREMENT PRIMARY KEY,
  cedula varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  id_donaciones int(11) NOT NULL,
  FOREIGN KEY (id_donaciones) REFERENCES donaciones (id_donaciones) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla instituciones
--
CREATE TABLE instituciones (
  rif_int varchar(20) PRIMARY KEY,
  razon_social varchar(200) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  direccion varchar(200) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  contacto varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla donativo_int
--
CREATE TABLE donativo_int (
  id_donativo_int int(11) AUTO_INCREMENT PRIMARY KEY,
  rif_int varchar(20) NOT NULL,
  id_donaciones int(11) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (id_donaciones) REFERENCES donaciones (id_donaciones) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (rif_int) REFERENCES instituciones (rif_int) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla tipo_empleado
--
CREATE TABLE tipo_empleado (
  tipo_em int(11) AUTO_INCREMENT PRIMARY KEY,
  nombre_e varchar(50) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla personal
--
CREATE TABLE personal (
  cedula varchar(15) PRIMARY KEY,
  nombres varchar(50) NOT NULL,
  apellidos varchar(50) NOT NULL,
  direccion varchar(200) NOT NULL,
  id_sede int(11) NOT NULL,
  edad date NOT NULL,
  -- fecha_nacimiento date NOT NULL,
  telefono varchar(15) NOT NULL,
  correo varchar(320) NOT NULL,
  tipo_em int(11) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (tipo_em) REFERENCES tipo_empleado (tipo_em) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_sede) REFERENCES sede (id_sede) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla pacientes
--
CREATE TABLE pacientes (
  ced_pac varchar(15) PRIMARY KEY,
  nombre varchar(50) NOT NULL,
  apellido varchar(50) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla venta
--
CREATE TABLE venta (
  num_fact varchar(15) PRIMARY KEY,
  monto_fact decimal(10, 2) NOT NULL,
  monto_dolares decimal(10, 2) NOT NULL,
  fecha datetime NOT NULL DEFAULT current_timestamp(),
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla venta_producto
--
CREATE TABLE venta_producto (
  id_venta_p int(11) AUTO_INCREMENT PRIMARY KEY,
  num_fact varchar(15) NOT NULL,
  id_producto_sede int(50) NOT NULL,
  cantidad smallint(5) UNSIGNED NOT NULL,
  precio_actual decimal(10, 2) NOT NULL,
  FOREIGN KEY (id_producto_sede) REFERENCES producto_sede (id_producto_sede) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (num_fact) REFERENCES venta (num_fact) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla venta_pacientes
--
CREATE TABLE venta_pacientes (
  id_venta int(11) AUTO_INCREMENT PRIMARY KEY,
  num_fact varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  ced_pac varchar(15) NOT NULL,
  FOREIGN KEY (num_fact) REFERENCES venta (num_fact) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (ced_pac) REFERENCES pacientes (ced_pac) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla venta_personal
--
CREATE TABLE venta_personal (
  id_venta int(11) AUTO_INCREMENT PRIMARY KEY,
  cedula varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  num_fact varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  FOREIGN KEY (cedula) REFERENCES personal (cedula) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (num_fact) REFERENCES venta (num_fact) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla pagos_recibidos
--
CREATE TABLE pagos_recibidos (
  id_pago int(11) AUTO_INCREMENT PRIMARY KEY,
  num_fact varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (num_fact) REFERENCES venta (num_fact) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla forma_pago
--
CREATE TABLE forma_pago (
  id_forma_pago int(11) AUTO_INCREMENT PRIMARY KEY,
  tipo_pago varchar(15) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla detalle_pago
--
CREATE TABLE detalle_pago (
  id_det_pago int(11) AUTO_INCREMENT PRIMARY KEY,
  id_pago int(11) NOT NULL,
  id_forma_pago int(11) NOT NULL,
  referencia varchar(25) NOT NULL,
  monto_pago decimal(10, 2) NOT NULL,
  FOREIGN KEY (id_pago) REFERENCES pagos_recibidos (id_pago) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_forma_pago) REFERENCES forma_pago (id_forma_pago) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla recepcion_sede
--
CREATE TABLE recepcion_sede (
  id_recepcion int(11) AUTO_INCREMENT PRIMARY KEY,
  id_transferencia int(11) NOT NULL,
  fecha datetime NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla detalle_recepcion
--
CREATE TABLE detalle_recepcion (
  id_recepcion int(11) NOT NULL,
  cantidad int(11) NOT NULL,
  id_producto_sede int(50) NOT NULL,
  FOREIGN KEY (id_producto_sede) REFERENCES producto_sede (id_producto_sede) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_recepcion) REFERENCES recepcion_sede (id_recepcion) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla transferencia
--
CREATE TABLE transferencia (
  id_transferencia int(11) AUTO_INCREMENT PRIMARY KEY,
  id_sede int(11) NOT NULL,
  fecha datetime NOT NULL DEFAULT current_timestamp(),
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish2_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla detalle_transferencia
--
CREATE TABLE detalle_transferencia (
  id_transferencia int(11) AUTO_INCREMENT PRIMARY KEY,
  id_producto_sede int(50) NOT NULL,
  cantidad int(11) NOT NULL,
  FOREIGN KEY (id_transferencia) REFERENCES transferencia (id_transferencia) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_producto_sede) REFERENCES producto_sede (id_producto_sede) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla cargo
--
CREATE TABLE cargo (
  id_cargo int(11) AUTO_INCREMENT PRIMARY KEY,
  fecha datetime NOT NULL DEFAULT current_timestamp(),
  num_cargo int(50) NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla detalle_cargo
--
CREATE TABLE detalle_cargo (
  id_cargo int(11) NOT NULL,
  id_producto_sede int(50) NOT NULL,
  cantidad int(11) NOT NULL,
  FOREIGN KEY (id_cargo) REFERENCES cargo (id_cargo) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_producto_sede) REFERENCES producto_sede (id_producto_sede) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla recepcion_nacional
--
CREATE TABLE recepcion_nacional (
  id_rep_nacional int(11) AUTO_INCREMENT PRIMARY KEY,
  id_proveedor varchar(20) NOT NULL,
  fecha date NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla detalle_recepcion_nacional
--
CREATE TABLE detalle_recepcion_nacional (
  id_rep_nacional int(11) NOT NULL,
  cantidad int(11) NOT NULL,
  id_producto_sede int(50) NOT NULL,
  FOREIGN KEY (id_rep_nacional) REFERENCES recepcion_nacional (id_rep_nacional) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_producto_sede) REFERENCES producto_sede (id_producto_sede) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla historial
--
CREATE TABLE historial (
  id_historial int(11) AUTO_INCREMENT PRIMARY KEY,
  fecha datetime NOT NULL DEFAULT current_timestamp(),
  tipo_movimiento varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  entrada varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  salida varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  id_sede int(11) NOT NULL,
  id_lote varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  id_producto_sede int(50) NOT NULL,
  cantidad int(11) NOT NULL,
  descripcion varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  id_usuario varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  status tinyint(1) UNSIGNED NOT NULL,
  FOREIGN KEY (id_producto_sede) REFERENCES producto_sede (id_producto_sede) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuario (cedula) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
