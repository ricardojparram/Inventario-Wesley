-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-03-2024 a las 22:34:46
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `wesley`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_Bitacora` int(11) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cambio`
--

CREATE TABLE `cambio` (
  `id_cambio` int(11) NOT NULL,
  `cambio` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `moneda` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id_cargo` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `num_cargo` int(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clase`
--

CREATE TABLE `clase` (
  `id_clase` int(11) NOT NULL,
  `nombre_c` varchar(40) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `orden_compra` varchar(12) NOT NULL,
  `fecha` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `ced_prove` varchar(20) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_producto`
--

CREATE TABLE `compra_producto` (
  `id_detalle` int(11) NOT NULL,
  `cod_producto` varchar(15) NOT NULL,
  `orden_compra` varchar(15) NOT NULL,
  `cantidad` int(12) NOT NULL,
  `precio_compra` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto_prove`
--

CREATE TABLE `contacto_prove` (
  `id_contacto_prove` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `rif_proveedor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descargo`
--

CREATE TABLE `descargo` (
  `id_descargo` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `num_descargo` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cargo`
--

CREATE TABLE `detalle_cargo` (
  `id_detalle` int(11) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `num_cargo` int(11) NOT NULL,
  `id_producto_sede` int(50) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_descargo`
--

CREATE TABLE `detalle_descargo` (
  `id_detalle` int(11) NOT NULL,
  `id_descargo` int(11) NOT NULL,
  `id_pro_dañado` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pago`
--

CREATE TABLE `detalle_pago` (
  `id_det_pago` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL,
  `id_forma_pago` int(11) NOT NULL,
  `referencia` varchar(25) NOT NULL,
  `monto_pago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_recepcion`
--

CREATE TABLE `detalle_recepcion` (
  `id_detalle` varchar(15) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_producto_sede` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_recepcion_nacional`
--

CREATE TABLE `detalle_recepcion_nacional` (
  `id_detalle` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_producto_sede` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_transferencia`
--

CREATE TABLE `detalle_transferencia` (
  `id_detalle` int(11) NOT NULL,
  `id_lote` int(50) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_donacion`
--

CREATE TABLE `det_donacion` (
  `id_detalle` int(11) NOT NULL,
  `cod_producto` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `cantidad` int(12) NOT NULL,
  `id_donaciones` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donaciones`
--

CREATE TABLE `donaciones` (
  `id_donaciones` int(11) NOT NULL,
  `beneficiario` varchar(11) NOT NULL,
  `fecha` date NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donativo_int`
--

CREATE TABLE `donativo_int` (
  `id_donativo_int` int(11) NOT NULL,
  `rif_int` varchar(20) NOT NULL,
  `id_donaciones` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donativo_pac`
--

CREATE TABLE `donativo_pac` (
  `id_donativopac` int(11) NOT NULL,
  `ced_pac` varchar(15) NOT NULL,
  `id_donaciones` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donativo_per`
--

CREATE TABLE `donativo_per` (
  `id_donativo` int(11) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `id_donaciones` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forma_pago`
--

CREATE TABLE `forma_pago` (
  `id_forma_pago` int(11) NOT NULL,
  `tipo_pago` varchar(15) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `id_historial` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo_movimiento` varchar(20) NOT NULL,
  `entrada` varchar(20) NOT NULL,
  `salida` varchar(15) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `id_lote` varchar(15) NOT NULL,
  `id_producto_sede` int(50) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `id_usuario` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `img_producto`
--

CREATE TABLE `img_producto` (
  `cod_producto` varchar(15) NOT NULL,
  `img` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instituciones`
--

CREATE TABLE `instituciones` (
  `rif_int` varchar(20) NOT NULL,
  `razon_social` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `contacto` varchar(15) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorio`
--

CREATE TABLE `laboratorio` (
  `rif_laboratorio` varchar(20) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `razon_social` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medida`
--

CREATE TABLE `medida` (
  `id_medida` int(15) NOT NULL,
  `nombre` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulo` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moneda`
--

CREATE TABLE `moneda` (
  `id_moneda` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `valor` decimal(10,0) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `moneda`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `ced_pac` varchar(15) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `apellido` varchar(15) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_recibidos`
--

CREATE TABLE `pagos_recibidos` (
  `id_pago` int(11) NOT NULL,
  `num_fact` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `nombre_accion` varchar(40) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `cedula` varchar(15) NOT NULL,
  `nombres` varchar(20) NOT NULL,
  `apellidos` varchar(20) NOT NULL,
  `direccion` varchar(180) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `edad` int(180) NOT NULL,
  `telefono` int(20) NOT NULL,
  `correo` varchar(180) NOT NULL,
  `tipo_em` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `cod_pres` int(11) NOT NULL,
  `cantidad` varchar(12) NOT NULL,
  `id_medida` int(15) NOT NULL,
  `peso` decimal(10,2) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `cod_producto` varchar(15) NOT NULL,
  `id_tipoprod` int(11) NOT NULL,
  `ubicacion` varchar(15) NOT NULL,
  `composicion` varchar(80) NOT NULL,
  `posologia` varchar(40) NOT NULL,
  `rif_laboratorio` varchar(20) DEFAULT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_clase` int(11) NOT NULL,
  `cod_pres` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_dañado`
--

CREATE TABLE `producto_dañado` (
  `id_pro_dañado` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_descargo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_sede`
--

CREATE TABLE `producto_sede` (
  `id_producto_sede` int(50) NOT NULL,
  `cod_producto` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `lote` varchar(15) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `id_sede` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `rif_proveedor` varchar(20) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `razon_social` varchar(50) NOT NULL,
  `contacto` varchar(15) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepcion_nacional`
--

CREATE TABLE `recepcion_nacional` (
  `id_rep_nacional` int(11) NOT NULL,
  `id_proveedor` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` date NOT NULL,
  `estado_producto` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepcion_sede`
--

CREATE TABLE `recepcion_sede` (
  `id_recepcion` varchar(15) NOT NULL,
  `id_transferencia` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sede`
--

CREATE TABLE `sede` (
  `id_sede` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `telefono` int(20) NOT NULL,
  `direccion` varchar(20) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo`
--

CREATE TABLE `tipo` (
  `id_tipo` int(11) NOT NULL,
  `nombre_t` varchar(30) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_empleado`
--

CREATE TABLE `tipo_empleado` (
  `tipo_em` int(11) NOT NULL,
  `nombre_e` varchar(30) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `id_tipoprod` int(11) NOT NULL,
  `nombrepro` varchar(30) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencia`
--

CREATE TABLE `transferencia` (
  `id_transferencia` int(11) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `id_lote` varchar(15) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `cedula` varchar(15) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `password` varchar(70) NOT NULL,
  `rol` int(11) NOT NULL,
  `img` varchar(120) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `num_fact` varchar(15) NOT NULL,
  `monto_fact` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_pacientes`
--

CREATE TABLE `venta_pacientes` (
  `id_venta` int(11) NOT NULL,
  `num_fact` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `ced_pac` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_personal`
--

CREATE TABLE `venta_personal` (
  `id_venta` int(11) NOT NULL,
  `cedula` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `num_fact` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_producto`
--

CREATE TABLE `venta_producto` (
  `id_venta_p` int(11) NOT NULL,
  `num_fact` varchar(15) NOT NULL,
  `cod_producto` varchar(15) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_actual` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_Bitacora`),
  ADD KEY `cedula` (`cedula`);

--
-- Indices de la tabla `cambio`
--
ALTER TABLE `cambio`
  ADD PRIMARY KEY (`id_cambio`),
  ADD KEY `moneda` (`moneda`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `clase`
--
ALTER TABLE `clase`
  ADD PRIMARY KEY (`id_clase`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`orden_compra`),
  ADD KEY `ced_prove` (`ced_prove`);

--
-- Indices de la tabla `compra_producto`
--
ALTER TABLE `compra_producto`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `cod_producto` (`cod_producto`),
  ADD KEY `orden_compra` (`orden_compra`);

--
-- Indices de la tabla `contacto_prove`
--
ALTER TABLE `contacto_prove`
  ADD PRIMARY KEY (`id_contacto_prove`),
  ADD KEY `cod_prove` (`rif_proveedor`);

--
-- Indices de la tabla `descargo`
--
ALTER TABLE `descargo`
  ADD PRIMARY KEY (`id_descargo`);

--
-- Indices de la tabla `detalle_cargo`
--
ALTER TABLE `detalle_cargo`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_cargo` (`id_cargo`),
  ADD KEY `id_producto_sede` (`id_producto_sede`);

--
-- Indices de la tabla `detalle_descargo`
--
ALTER TABLE `detalle_descargo`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pro_dañado` (`id_pro_dañado`),
  ADD KEY `id_descargo` (`id_descargo`);

--
-- Indices de la tabla `detalle_pago`
--
ALTER TABLE `detalle_pago`
  ADD PRIMARY KEY (`id_det_pago`),
  ADD KEY `id_pago` (`id_pago`),
  ADD KEY `id_forma_pago` (`id_forma_pago`);

--
-- Indices de la tabla `detalle_recepcion`
--
ALTER TABLE `detalle_recepcion`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_producto_sede` (`id_producto_sede`);

--
-- Indices de la tabla `detalle_recepcion_nacional`
--
ALTER TABLE `detalle_recepcion_nacional`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_producto_sede` (`id_producto_sede`);

--
-- Indices de la tabla `detalle_transferencia`
--
ALTER TABLE `detalle_transferencia`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_lote` (`id_lote`);

--
-- Indices de la tabla `det_donacion`
--
ALTER TABLE `det_donacion`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_donaciones` (`id_donaciones`),
  ADD KEY `cod_producto` (`cod_producto`);

--
-- Indices de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  ADD PRIMARY KEY (`id_donaciones`);

--
-- Indices de la tabla `donativo_int`
--
ALTER TABLE `donativo_int`
  ADD PRIMARY KEY (`id_donativo_int`),
  ADD KEY `id_donaciones` (`id_donaciones`),
  ADD KEY `rif_int` (`rif_int`);

--
-- Indices de la tabla `donativo_pac`
--
ALTER TABLE `donativo_pac`
  ADD PRIMARY KEY (`id_donativopac`),
  ADD KEY `id_donaciones` (`id_donaciones`);

--
-- Indices de la tabla `donativo_per`
--
ALTER TABLE `donativo_per`
  ADD PRIMARY KEY (`id_donativo`),
  ADD KEY `id_donciones` (`id_donaciones`);

--
-- Indices de la tabla `forma_pago`
--
ALTER TABLE `forma_pago`
  ADD PRIMARY KEY (`id_forma_pago`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `cod_producto` (`id_producto_sede`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `img_producto`
--
ALTER TABLE `img_producto`
  ADD KEY `codigo` (`cod_producto`);

--
-- Indices de la tabla `instituciones`
--
ALTER TABLE `instituciones`
  ADD PRIMARY KEY (`rif_int`);

--
-- Indices de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`rif_laboratorio`);

--
-- Indices de la tabla `medida`
--
ALTER TABLE `medida`
  ADD PRIMARY KEY (`id_medida`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `moneda`
--
ALTER TABLE `moneda`
  ADD PRIMARY KEY (`id_moneda`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`ced_pac`);

--
-- Indices de la tabla `pagos_recibidos`
--
ALTER TABLE `pagos_recibidos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `num_fact` (`num_fact`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`cedula`),
  ADD KEY `tipo_em` (`tipo_em`),
  ADD KEY `id_sede` (`id_sede`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`cod_pres`),
  ADD KEY `id_medida` (`id_medida`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`cod_producto`),
  ADD KEY `cod_pres` (`cod_pres`),
  ADD KEY `id_clase` (`id_clase`),
  ADD KEY `rif_laboratorio` (`rif_laboratorio`),
  ADD KEY `id_tipoprod` (`id_tipoprod`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Indices de la tabla `producto_dañado`
--
ALTER TABLE `producto_dañado`
  ADD PRIMARY KEY (`id_pro_dañado`),
  ADD KEY `id_descargo` (`id_descargo`);

--
-- Indices de la tabla `producto_sede`
--
ALTER TABLE `producto_sede`
  ADD PRIMARY KEY (`id_producto_sede`),
  ADD KEY `id_sede` (`id_sede`),
  ADD KEY `cod_producto` (`cod_producto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`rif_proveedor`);

--
-- Indices de la tabla `recepcion_nacional`
--
ALTER TABLE `recepcion_nacional`
  ADD PRIMARY KEY (`id_rep_nacional`);

--
-- Indices de la tabla `recepcion_sede`
--
ALTER TABLE `recepcion_sede`
  ADD PRIMARY KEY (`id_recepcion`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sede`
--
ALTER TABLE `sede`
  ADD PRIMARY KEY (`id_sede`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tipo_empleado`
--
ALTER TABLE `tipo_empleado`
  ADD PRIMARY KEY (`tipo_em`);

--
-- Indices de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD PRIMARY KEY (`id_tipoprod`),
  ADD UNIQUE KEY `nombrePro` (`nombrepro`),
  ADD UNIQUE KEY `nombrepro_2` (`nombrepro`);

--
-- Indices de la tabla `transferencia`
--
ALTER TABLE `transferencia`
  ADD PRIMARY KEY (`id_transferencia`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`cedula`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`num_fact`);

--
-- Indices de la tabla `venta_pacientes`
--
ALTER TABLE `venta_pacientes`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `ced_pac` (`ced_pac`),
  ADD KEY `venta_factura` (`num_fact`);

--
-- Indices de la tabla `venta_personal`
--
ALTER TABLE `venta_personal`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `cedula` (`cedula`),
  ADD KEY `num_fact` (`num_fact`);

--
-- Indices de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD PRIMARY KEY (`id_venta_p`),
  ADD KEY `codigo` (`cod_producto`),
  ADD KEY `num_fact` (`num_fact`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT;


--
-- AUTO_INCREMENT de la tabla `compra_producto`
--
ALTER TABLE `compra_producto`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contacto_prove`
--
ALTER TABLE `contacto_prove`
  MODIFY `id_contacto_prove` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medida`
--
ALTER TABLE `medida`
  MODIFY `id_medida` int(15) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuario` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cambio`
--
ALTER TABLE `cambio`
  ADD CONSTRAINT `cambio_ibfk_1` FOREIGN KEY (`moneda`) REFERENCES `moneda` (`id_moneda`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`ced_prove`) REFERENCES `proveedor` (`rif_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compra_producto`
--
ALTER TABLE `compra_producto`
  ADD CONSTRAINT `compra_producto_ibfk_3` FOREIGN KEY (`cod_producto`) REFERENCES `producto` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_producto_ibfk_4` FOREIGN KEY (`orden_compra`) REFERENCES `compra` (`orden_compra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `contacto_prove`
--
ALTER TABLE `contacto_prove`
  ADD CONSTRAINT `contacto_prove_ibfk_1` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedor` (`rif_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_cargo`
--
ALTER TABLE `detalle_cargo`
  ADD CONSTRAINT `detalle_cargo_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_cargo_ibfk_2` FOREIGN KEY (`id_producto_sede`) REFERENCES `producto_sede` (`id_producto_sede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_descargo`
--
ALTER TABLE `detalle_descargo`
  ADD CONSTRAINT `detalle_descargo_ibfk_1` FOREIGN KEY (`id_pro_dañado`) REFERENCES `producto_dañado` (`id_pro_dañado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_descargo_ibfk_2` FOREIGN KEY (`id_descargo`) REFERENCES `descargo` (`id_descargo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_pago`
--
ALTER TABLE `detalle_pago`
  ADD CONSTRAINT `detalle_pago_ibfk_1` FOREIGN KEY (`id_pago`) REFERENCES `pagos_recibidos` (`id_pago`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pago_ibfk_2` FOREIGN KEY (`id_forma_pago`) REFERENCES `forma_pago` (`id_forma_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_recepcion`
--
ALTER TABLE `detalle_recepcion`
  ADD CONSTRAINT `detalle_recepcion_ibfk_1` FOREIGN KEY (`id_producto_sede`) REFERENCES `producto_sede` (`id_producto_sede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_recepcion_nacional`
--
ALTER TABLE `detalle_recepcion_nacional`
  ADD CONSTRAINT `detalle_recepcion_nacional_ibfk_1` FOREIGN KEY (`id_detalle`) REFERENCES `recepcion_nacional` (`id_rep_nacional`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_recepcion_nacional_ibfk_2` FOREIGN KEY (`id_producto_sede`) REFERENCES `producto_sede` (`id_producto_sede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_transferencia`
--
ALTER TABLE `detalle_transferencia`
  ADD CONSTRAINT `detalle_transferencia_ibfk_1` FOREIGN KEY (`id_detalle`) REFERENCES `transferencia` (`id_transferencia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_transferencia_ibfk_2` FOREIGN KEY (`id_lote`) REFERENCES `producto_sede` (`id_producto_sede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `det_donacion`
--
ALTER TABLE `det_donacion`
  ADD CONSTRAINT `det_donacion_ibfk_1` FOREIGN KEY (`id_donaciones`) REFERENCES `donaciones` (`id_donaciones`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `det_donacion_ibfk_2` FOREIGN KEY (`cod_producto`) REFERENCES `producto` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `donativo_int`
--
ALTER TABLE `donativo_int`
  ADD CONSTRAINT `donativo_int_ibfk_1` FOREIGN KEY (`id_donaciones`) REFERENCES `donaciones` (`id_donaciones`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `donativo_int_ibfk_2` FOREIGN KEY (`rif_int`) REFERENCES `instituciones` (`rif_int`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `donativo_pac`
--
ALTER TABLE `donativo_pac`
  ADD CONSTRAINT `donativo_pac_ibfk_1` FOREIGN KEY (`id_donaciones`) REFERENCES `donaciones` (`id_donaciones`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `donativo_per`
--
ALTER TABLE `donativo_per`
  ADD CONSTRAINT `donativo_per_ibfk_1` FOREIGN KEY (`id_donaciones`) REFERENCES `donaciones` (`id_donaciones`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `historial_ibfk_2` FOREIGN KEY (`id_producto_sede`) REFERENCES `producto_sede` (`id_producto_sede`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `historial_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `img_producto`
--
ALTER TABLE `img_producto`
  ADD CONSTRAINT `img_producto_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `producto` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_recibidos`
--
ALTER TABLE `pagos_recibidos`
  ADD CONSTRAINT `pagos_recibidos_ibfk_1` FOREIGN KEY (`num_fact`) REFERENCES `venta` (`num_fact`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `personal_ibfk_1` FOREIGN KEY (`tipo_em`) REFERENCES `tipo_empleado` (`tipo_Em`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personal_ibfk_2` FOREIGN KEY (`id_sede`) REFERENCES `sede` (`id_sede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD CONSTRAINT `presentacion_ibfk_1` FOREIGN KEY (`id_medida`) REFERENCES `medida` (`id_medida`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`cod_pres`) REFERENCES `presentacion` (`cod_pres`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id_clase`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_4` FOREIGN KEY (`rif_laboratorio`) REFERENCES `laboratorio` (`rif_laboratorio`),
  ADD CONSTRAINT `producto_ibfk_5` FOREIGN KEY (`id_tipoprod`) REFERENCES `tipo_producto` (`id_tipoprod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_6` FOREIGN KEY (`id_tipo`) REFERENCES `tipo` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_dañado`
--
ALTER TABLE `producto_dañado`
  ADD CONSTRAINT `producto_dañado_ibfk_1` FOREIGN KEY (`id_descargo`) REFERENCES `descargo` (`id_descargo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_sede`
--
ALTER TABLE `producto_sede`
  ADD CONSTRAINT `producto_sede_ibfk_2` FOREIGN KEY (`id_sede`) REFERENCES `sede` (`id_sede`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_sede_ibfk_3` FOREIGN KEY (`cod_producto`) REFERENCES `producto` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recepcion_sede`
--
ALTER TABLE `recepcion_sede`
  ADD CONSTRAINT `recepcion_sede_ibfk_1` FOREIGN KEY (`id_recepcion`) REFERENCES `detalle_recepcion` (`id_detalle`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipo_empleado`
--
ALTER TABLE `tipo_empleado`
  ADD CONSTRAINT `tipo_empleado_ibfk_1` FOREIGN KEY (`tipo_em`) REFERENCES `personal` (`tipo_em`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta_pacientes`
--
ALTER TABLE `venta_pacientes`
  ADD CONSTRAINT `venta_factura` FOREIGN KEY (`num_fact`) REFERENCES `venta` (`num_fact`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_pacientes_ibfk_1` FOREIGN KEY (`ced_pac`) REFERENCES `pacientes` (`ced_pac`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_pacientes_ibfk_2` FOREIGN KEY (`num_fact`) REFERENCES `producto` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta_personal`
--
ALTER TABLE `venta_personal`
  ADD CONSTRAINT `venta_personal_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `personal` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_personal_ibfk_2` FOREIGN KEY (`num_fact`) REFERENCES `venta` (`num_fact`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `producto` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`num_fact`) REFERENCES `venta` (`num_fact`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
