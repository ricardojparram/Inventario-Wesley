
--  BASE DE DATOS PARA EL SISTEMA DE LA FARMACIA MEDISALUD C.A

DROP DATABASE IF EXISTS medisalud;

CREATE DATABASE  medisalud CHARACTER SET utf8mb4;
USE medisalud;

-- TABLA PARA CLIENTES 
CREATE TABLE cliente(
    cedula varchar(30) COLLATE utf8_spanish2_ci PRIMARY KEY,
    nombre varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    apellido varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    direccion varchar(180) COLLATE utf8_spanish2_ci NOT NULL,
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA LABORATORIOS 
CREATE TABLE laboratorio(
    cod_lab varchar(10) PRIMARY KEY,
    rif varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    direccion varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
    razon_social varchar(200) COLLATE utf8_spanish2_ci,
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA PROVEEDOR 
CREATE TABLE proveedor(
    cod_prove int AUTO_INCREMENT PRIMARY KEY,
    rif varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    direccion varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
    razon_social varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA NIVEL DE USUARIO 
CREATE TABLE rol(
    id_rol int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA USUARIOS 
CREATE TABLE usuario(
    cedula varchar(30) PRIMARY KEY,
    nombre varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    apellido varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    correo varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
    password varchar(70) COLLATE utf8_spanish2_ci NOT NULL,
    rol int NOT NULL,
    img varchar(120) COLLATE utf8_spanish2_ci,
    status int NOT NULL,
    FOREIGN KEY (rol) REFERENCES rol(id_rol) ON DELETE CASCADE ON UPDATE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA MODULOS DE USUARIO

CREATE TABLE modulos (
    id_modulo int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(30) NOT NULL,
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE permisos(
    id_permiso int AUTO_INCREMENT PRIMARY KEY,
    id_rol int NOT NULL,
    id_modulo int NOT NULL,
    nombre_accion varchar(40) NOT NULL,
    status int NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES rol(id_rol) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_modulo) REFERENCES modulos(id_modulo) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA BITACORA 
CREATE TABLE bitacora (
  id int AUTO_INCREMENT PRIMARY KEY,
  modulo varchar(20) NOT NULL,
  usuario varchar(30) NOT NULL,
  descripcion varchar(50) NOT NULL,
  fecha datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  status int(11) NOT NULL,
  FOREIGN KEY (usuario) REFERENCES usuario (cedula) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA NOTIFICACIONES 
CREATE TABLE notificaciones (
  id int AUTO_INCREMENT PRIMARY KEY,
  tipo_notificacion varchar(30) NOT NULL,
  mensaje varchar(100) NOT NULL,
  fecha date NOT NULL,
  stock int,
  dia_de_inventario decimal(10,2),
  status int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA EL CONTACTO DE LOS CLIENTES 
CREATE TABLE contacto_cliente(
    id_contacto int AUTO_INCREMENT PRIMARY KEY,
    celular varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
    correo varchar(60) COLLATE utf8_spanish2_ci,
    cedula varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
    FOREIGN KEY (cedula) REFERENCES cliente(cedula) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci; 

-- TABLA PARA EL CONTACTO DE LOS LABORATORIOS */
CREATE TABLE contacto_lab(
    id_contacto_lab int AUTO_INCREMENT PRIMARY KEY,
    telefono varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    contacto varchar(20) COLLATE utf8_spanish2_ci ,
    cod_lab varchar(11) NOT NULL,
    FOREIGN KEY (cod_lab) REFERENCES laboratorio(cod_lab) ON DELETE CASCADE ON UPDATE CASCADE  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA EL CONTACTO DE LAS Proveedor 
CREATE TABLE contacto_prove(
    id_contacto_prove int AUTO_INCREMENT PRIMARY KEY,
    telefono varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    contacto varchar(200) COLLATE utf8_spanish2_ci ,
    cod_prove int NOT NULL,
    FOREIGN KEY (cod_prove) REFERENCES proveedor(cod_prove) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA LOS TIPOS DE PRODUCTOS 
CREATE TABLE tipo(
    cod_tipo int AUTO_INCREMENT PRIMARY KEY,
    des_tipo varchar(40) COLLATE utf8_spanish2_ci,
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA LAS CLASES DE PRODUCTOS 
CREATE TABLE clase (
  cod_clase int AUTO_INCREMENT PRIMARY KEY,
  des_clase varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  status int(11) NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
 
-- TABLA PARA LA PRESENTACIÓN DE LOS PRODUCTOS 
CREATE TABLE presentacion(
    cod_pres int AUTO_INCREMENT PRIMARY KEY,
    cantidad varchar(12) COLLATE utf8_spanish2_ci NOT NULL,
    medida varchar(15) COLLATE utf8_spanish2_ci NOT NULL,
    peso decimal(10,2) NOT NULL,
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA PRODUCTO 
CREATE TABLE producto( 
    cod_producto int AUTO_INCREMENT PRIMARY KEY,
    codigo varchar(50) NOT NULL,
    descripcion varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
    ubicacion varchar(100)COLLATE utf8_spanish2_ci NOT NULL,
    composicion varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
    contraindicaciones varchar(400) COLLATE utf8_spanish2_ci NOT NULL,
    posologia varchar(400) COLLATE utf8_spanish2_ci NOT NULL,
    vencimiento date NOT NULL,
    p_venta decimal (10,2) COLLATE utf8_spanish2_ci NOT NULL,
    stock varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
    img varchar(100),
    cod_lab varchar(10),
    cod_tipo int NOT NULL,
    cod_clase int NOT NULL,
    cod_pres int NOT NULL,
    status int NOT NULL,
    FOREIGN KEY (cod_lab) REFERENCES laboratorio(cod_lab) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cod_tipo) REFERENCES tipo(cod_tipo) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cod_clase) REFERENCES clase(cod_clase) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cod_pres) REFERENCES presentacion(cod_pres) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA LOS TIPOS PAGOS 
CREATE TABLE tipo_pago(
    id_tipo_pago int AUTO_INCREMENT PRIMARY KEY,
    des_tipo_pago varchar(40) COLLATE utf8_spanish2_ci,
    online int NOT NULL,
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


-- TABLA PARA LA MONEDA 
CREATE TABLE moneda(
    id_moneda int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA LA CAMBIO 
CREATE TABLE cambio(
    id_cambio int AUTO_INCREMENT PRIMARY KEY,
    cambio varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    moneda int NOT NULL,
    status int NOT NULL,
    FOREIGN KEY(moneda) REFERENCES moneda(id_moneda) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA CARRITO 

CREATE TABLE carrito(
    cedula varchar(30) NOT NULL,
    cod_producto int NOT NULL,
    cantidad varchar(10) NOT NULL,
    FOREIGN KEY (cedula) REFERENCES usuario (cedula) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cod_producto) REFERENCES producto (cod_producto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA EMPRESA ENVIO
 
CREATE TABLE empresa_envio(
    id_empresa int AUTO_INCREMENT PRIMARY KEY,
    rif varchar(15) NOT NULL,
    nombre varchar(15) NOT NULL,
    contacto varchar(15) ,
    status int NOT NULL    
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA ESTADOS DE VENEZUELA

CREATE TABLE estados_venezuela(
    id_estado int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT NULl
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


-- TABLA PARA SEDE EMVIO
   
CREATE TABLE sede_envio(
    id_sede varchar(10) PRIMARY KEY,
    nombre varchar(50) NOT NULL,
    ubicacion varchar(100) NOT NULL,
    id_estado int NOT NULL,
    id_empresa int NOT NULL,
    status int NOT NULL,
    FOREIGN KEY (id_estado) REFERENCES estados_venezuela (id_estado) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_empresa) REFERENCES empresa_envio (id_empresa) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA ENVIOS
CREATE TABLE envio(
    id_envio int AUTO_INCREMENT PRIMARY KEY,
    id_sede varchar(10) NOT NULL,
    fecha_envio datetime,
    fecha_entrega datetime,
    monto_envio decimal(10,2),
    status int NOT NULL,
    FOREIGN KEY (id_sede) REFERENCES sede_envio (id_sede) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA LAS VENTAS 
CREATE TABLE venta(
    num_fact int AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cedula_cliente varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
    direccion varchar(60),
    id_envio int,
    online int,
    status int NOT NULL,
    FOREIGN KEY (id_envio) REFERENCES envio(id_envio) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA LA RELACIÓN VENTA - PRODUCTO 
CREATE TABLE venta_producto(
    num_fact int NOT NULL,
    cod_producto int NOT NULL,
    cantidad int NOT NULL,
    precio_actual decimal (10,2)  NOT NULL,
    FOREIGN KEY (num_fact) REFERENCES venta(num_fact) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cod_producto) REFERENCES producto(cod_producto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA BANCOS
CREATE TABLE banco(
    id_banco int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(60) NOT NULL,
    codigo varchar(5),
    status int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA DATOS DEL BANCO DE LA FARMACIA
CREATE TABLE datos_cobro_farmacia(
    id_datos_cobro int AUTO_INCREMENT PRIMARY KEY,
    num_cuenta varchar(25),
    rif_cedula varchar(25),
    telefono varchar(15),
    id_banco int NOT NULL,
    status int NOT NULL,
    FOREIGN KEY (id_banco) REFERENCES banco(id_banco) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA PAGOS
CREATE TABLE pago(
    id_pago int AUTO_INCREMENT PRIMARY KEY,
    monto_total decimal (10,2) NOT NULL,
    num_fact int NOT NULL,
    status int NOT NULL,
    FOREIGN KEY (num_fact) REFERENCES venta(num_fact) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA DETALLE DE PAGO
CREATE TABLE detalle_pago(
    id_detalle_pago int AUTO_INCREMENT PRIMARY KEY,
    id_pago int NOT NULL,
    id_tipo_pago int NOT NULL,
    id_datos_cobro int,
    id_banco_cliente int,
    referencia varchar(50),
    monto_pago decimal (10,2) NOT NULL,
    id_cambio int NOT NULL,
    FOREIGN KEY (id_banco_cliente) REFERENCES banco(id_banco) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_tipo_pago) REFERENCES tipo_pago(id_tipo_pago) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_datos_cobro) REFERENCES datos_cobro_farmacia(id_datos_cobro) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_pago) REFERENCES pago(id_pago) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_cambio) REFERENCES cambio(id_cambio) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA LAS COMPRA
CREATE TABLE compra(
    cod_compra int AUTO_INCREMENT PRIMARY KEY,
    orden_compra varchar(12) NOT NULL, 
    fecha date NOT NULL,
    monto_total varchar(20) NOT NULL,
    cod_prove int NOT NULL,
    id_cambio int NOT NULL,
    status int NOT NULL,
    FOREIGN KEY (cod_prove) REFERENCES proveedor(cod_prove) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_cambio) REFERENCES cambio(id_cambio) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- TABLA PARA LAS COMPRA POR PRODUCTO 
CREATE TABLE compra_producto(
    cod_compra int NOT NULL,
    cod_producto int NOT NULL,
    cantidad int(12) NOT NULL,
    precio_compra varchar(15) NOT NULL,
    FOREIGN KEY (cod_compra) REFERENCES compra(cod_compra) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cod_producto) REFERENCES producto(cod_producto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
