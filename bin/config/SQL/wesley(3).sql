-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-03-2024 a las 19:34:40
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
-- Estructura de tabla para la tabla `detalle_recepcion`
--

CREATE TABLE `detalle_recepcion` (
  `id_detalle` varchar(15) NOT NULL,
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

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulo`, `nombre`, `status`) VALUES
(1, 'Clientes', 1),
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
(15, 'Bancos', 1),
(16, 'Cuentas farmacia', 1),
(17, 'Roles', 1),
(18, 'Empresa de Envio', 1),
(19, 'Sedes de Envio', 1),
(20, 'Comprobar pago', 1),
(21, 'Envios', 1);

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

INSERT INTO `moneda` (`id_moneda`, `nombre`, `valor`, `status`) VALUES
(1, 'Dolar', 0, 1),
(2, 'Euro', 0, 1);

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
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `id_rol`, `id_modulo`, `nombre_accion`, `status`) VALUES
(1, 1, 1, 'Registrar', 1),
(2, 1, 1, 'Editar', 1),
(3, 1, 1, 'Eliminar', 1),
(4, 1, 1, 'Consultar', 1),
(5, 1, 2, 'Registrar', 1),
(6, 1, 2, 'Editar', 0),
(7, 1, 2, 'Eliminar', 1),
(8, 1, 2, 'Consultar', 1),
(9, 1, 3, 'Registrar', 1),
(10, 1, 3, 'Editar', 1),
(11, 1, 3, 'Eliminar', 1),
(12, 1, 3, 'Consultar', 1),
(13, 1, 4, 'Registrar', 1),
(14, 1, 4, 'Editar', 1),
(15, 1, 4, 'Eliminar', 1),
(16, 1, 4, 'Consultar', 1),
(17, 1, 5, 'Registrar', 1),
(18, 1, 5, 'Editar', 1),
(19, 1, 5, 'Eliminar', 1),
(20, 1, 5, 'Consultar', 1),
(21, 1, 6, 'Registrar', 1),
(22, 1, 6, 'Editar', 1),
(23, 1, 6, 'Eliminar', 1),
(24, 1, 6, 'Consultar', 1),
(25, 1, 7, 'Registrar', 1),
(26, 1, 7, 'Editar', 1),
(27, 1, 7, 'Eliminar', 1),
(28, 1, 7, 'Consultar', 1),
(29, 1, 8, 'Registrar', 1),
(30, 1, 8, 'Editar', 1),
(31, 1, 8, 'Eliminar', 1),
(32, 1, 8, 'Consultar', 1),
(33, 1, 9, 'Registrar', 1),
(34, 1, 9, 'Editar', 1),
(35, 1, 9, 'Eliminar', 1),
(36, 1, 9, 'Consultar', 1),
(37, 1, 10, 'Registrar', 1),
(38, 1, 10, 'Editar', 1),
(39, 1, 10, 'Eliminar', 1),
(40, 1, 10, 'Consultar', 1),
(41, 1, 11, 'Registrar', 1),
(42, 1, 11, 'Editar', 1),
(43, 1, 11, 'Eliminar', 1),
(44, 1, 11, 'Consultar', 1),
(45, 1, 12, 'Consultar', 1),
(46, 1, 12, 'Exportar reporte', 1),
(47, 1, 12, 'Exportar reporte estadistico', 1),
(48, 1, 13, 'Registrar', 1),
(49, 1, 13, 'Editar', 1),
(50, 1, 13, 'Eliminar', 1),
(51, 1, 13, 'Consultar', 1),
(52, 1, 14, 'Consultar', 1),
(53, 1, 15, 'Registrar', 1),
(54, 1, 15, 'Editar', 1),
(55, 1, 15, 'Eliminar', 1),
(56, 1, 15, 'Consultar', 1),
(57, 1, 16, 'Registrar', 1),
(58, 1, 16, 'Editar', 1),
(59, 1, 16, 'Eliminar', 1),
(60, 1, 16, 'Consultar', 1),
(61, 1, 17, 'Modificar acceso', 1),
(62, 1, 17, 'Modificar acciones', 1),
(63, 1, 17, 'Consultar', 1),
(64, 1, 18, 'Registrar', 1),
(65, 1, 18, 'Editar', 1),
(66, 1, 18, 'Eliminar', 1),
(67, 1, 18, 'Consultar', 1),
(68, 1, 19, 'Registrar', 1),
(69, 1, 19, 'Editar', 1),
(70, 1, 19, 'Eliminar', 1),
(71, 1, 19, 'Consultar', 1),
(72, 1, 20, 'Consultar', 1),
(73, 1, 21, 'Consultar', 1),
(74, 1, 21, 'Asignar estado', 1),
(75, 2, 1, 'Registrar', 1),
(76, 2, 1, 'Editar', 1),
(77, 2, 1, 'Eliminar', 1),
(78, 2, 1, 'Consultar', 1),
(79, 2, 2, 'Registrar', 1),
(80, 2, 2, 'Editar', 1),
(81, 2, 2, 'Eliminar', 1),
(82, 2, 2, 'Consultar', 1),
(83, 2, 3, 'Registrar', 1),
(84, 2, 3, 'Editar', 1),
(85, 2, 3, 'Eliminar', 1),
(86, 2, 3, 'Consultar', 1),
(87, 2, 4, 'Registrar', 1),
(88, 2, 4, 'Editar', 1),
(89, 2, 4, 'Eliminar', 1),
(90, 2, 4, 'Consultar', 1),
(91, 2, 5, 'Registrar', 1),
(92, 2, 5, 'Editar', 1),
(93, 2, 5, 'Eliminar', 1),
(94, 2, 5, 'Consultar', 1),
(95, 2, 6, 'Registrar', 1),
(96, 2, 6, 'Editar', 1),
(97, 2, 6, 'Eliminar', 1),
(98, 2, 6, 'Consultar', 1),
(99, 2, 7, 'Registrar', 1),
(100, 2, 7, 'Editar', 1),
(101, 2, 7, 'Eliminar', 1),
(102, 2, 7, 'Consultar', 1),
(103, 2, 8, 'Registrar', 1),
(104, 2, 8, 'Editar', 1),
(105, 2, 8, 'Eliminar', 1),
(106, 2, 8, 'Consultar', 1),
(107, 2, 9, 'Registrar', 1),
(108, 2, 9, 'Editar', 1),
(109, 2, 9, 'Eliminar', 1),
(110, 2, 9, 'Consultar', 1),
(111, 2, 10, 'Registrar', 1),
(112, 2, 10, 'Editar', 1),
(113, 2, 10, 'Eliminar', 1),
(114, 2, 10, 'Consultar', 1),
(115, 2, 11, 'Registrar', 1),
(116, 2, 11, 'Editar', 1),
(117, 2, 11, 'Eliminar', 1),
(118, 2, 11, 'Consultar', 1),
(119, 2, 12, 'Consultar', 1),
(120, 2, 12, 'Exportar reporte', 1),
(121, 2, 12, 'Exportar reporte estadistico', 1),
(122, 2, 13, 'Registrar', 1),
(123, 2, 13, 'Editar', 1),
(124, 2, 13, 'Eliminar', 1),
(125, 2, 13, 'Consultar', 1),
(126, 2, 14, 'Consultar', 1),
(127, 2, 15, 'Registrar', 1),
(128, 2, 15, 'Editar', 1),
(129, 2, 15, 'Eliminar', 1),
(130, 2, 15, 'Consultar', 1),
(131, 2, 16, 'Registrar', 1),
(132, 2, 16, 'Editar', 1),
(133, 2, 16, 'Eliminar', 1),
(134, 2, 16, 'Consultar', 1),
(135, 2, 17, 'Modificar acceso', 1),
(136, 2, 17, 'Modificar acciones', 1),
(137, 2, 17, 'Consultar', 1),
(138, 2, 18, 'Registrar', 1),
(139, 2, 18, 'Editar', 1),
(140, 2, 18, 'Eliminar', 1),
(141, 2, 18, 'Consultar', 1),
(142, 2, 19, 'Registrar', 1),
(143, 2, 19, 'Editar', 1),
(144, 2, 19, 'Eliminar', 1),
(145, 2, 19, 'Consultar', 1),
(146, 2, 20, 'Consultar', 1),
(147, 2, 20, 'Comprobar pago', 1),
(148, 2, 21, 'Consultar', 1),
(149, 2, 21, 'Asignar estado', 1),
(150, 3, 1, 'Registrar', 1),
(151, 3, 1, 'Editar', 1),
(152, 3, 1, 'Eliminar', 1),
(153, 3, 1, 'Consultar', 1),
(154, 3, 2, 'Registrar', 1),
(155, 3, 2, 'Editar', 1),
(156, 3, 2, 'Eliminar', 1),
(157, 3, 2, 'Consultar', 1),
(158, 3, 3, 'Registrar', 1),
(159, 3, 3, 'Editar', 1),
(160, 3, 3, 'Eliminar', 1),
(161, 3, 3, 'Consultar', 1),
(162, 3, 4, 'Registrar', 1),
(163, 3, 4, 'Editar', 1),
(164, 3, 4, 'Eliminar', 1),
(165, 3, 4, 'Consultar', 1),
(166, 3, 5, 'Registrar', 1),
(167, 3, 5, 'Editar', 1),
(168, 3, 5, 'Eliminar', 1),
(169, 3, 5, 'Consultar', 1),
(170, 3, 6, 'Registrar', 1),
(171, 3, 6, 'Editar', 1),
(172, 3, 6, 'Eliminar', 1),
(173, 3, 6, 'Consultar', 1),
(174, 3, 7, 'Registrar', 1),
(175, 3, 7, 'Editar', 1),
(176, 3, 7, 'Eliminar', 1),
(177, 3, 7, 'Consultar', 1),
(178, 3, 8, 'Registrar', 1),
(179, 3, 8, 'Editar', 1),
(180, 3, 8, 'Eliminar', 1),
(181, 3, 8, 'Consultar', 1),
(182, 3, 9, 'Registrar', 1),
(183, 3, 9, 'Editar', 1),
(184, 3, 9, 'Eliminar', 1),
(185, 3, 9, 'Consultar', 1),
(186, 3, 10, 'Registrar', 1),
(187, 3, 10, 'Editar', 1),
(188, 3, 10, 'Eliminar', 1),
(189, 3, 10, 'Consultar', 1),
(190, 3, 11, 'Registrar', 1),
(191, 3, 11, 'Editar', 1),
(192, 3, 11, 'Eliminar', 1),
(193, 3, 11, 'Consultar', 1),
(194, 3, 12, 'Consultar', 1),
(195, 3, 12, 'Exportar reporte', 1),
(196, 3, 12, 'Exportar reporte estadistico', 1),
(197, 3, 13, 'Registrar', 1),
(198, 3, 13, 'Editar', 1),
(199, 3, 13, 'Eliminar', 1),
(200, 3, 13, 'Consultar', 1),
(201, 3, 14, 'Consultar', 1),
(202, 3, 15, 'Registrar', 1),
(203, 3, 15, 'Editar', 1),
(204, 3, 15, 'Eliminar', 1),
(205, 3, 15, 'Consultar', 1),
(206, 3, 16, 'Registrar', 1),
(207, 3, 16, 'Editar', 1),
(208, 3, 16, 'Eliminar', 1),
(209, 3, 16, 'Consultar', 1),
(210, 3, 17, 'Modificar acceso', 1),
(211, 3, 17, 'Modificar acciones', 1),
(212, 3, 17, 'Consultar', 1),
(213, 3, 18, 'Registrar', 1),
(214, 3, 18, 'Editar', 1),
(215, 3, 18, 'Eliminar', 1),
(216, 3, 18, 'Consultar', 1),
(217, 3, 19, 'Registrar', 1),
(218, 3, 19, 'Editar', 1),
(219, 3, 19, 'Eliminar', 1),
(220, 3, 19, 'Consultar', 1),
(221, 3, 20, 'Consultar', 1),
(222, 3, 20, 'Comprobar pago', 1),
(223, 3, 21, 'Consultar', 1),
(224, 3, 21, 'Asignar estado', 1);

-- --------------------------------------------------------

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

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`, `status`) VALUES
(1, 'Administrador', 1),
(2, 'Gerente', 1),
(3, 'Empleado', 1),
(4, 'Cliente', 1);

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
  `cedula` varchar(30) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(70) NOT NULL,
  `rol` int(11) NOT NULL,
  `img` varchar(120) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`cedula`, `nombre`, `apellido`, `correo`, `password`, `rol`, `img`, `status`) VALUES
('O9OnH0ox4pHUYNZMrowa2Q==', 'admin', 'admin', 'mE/+zG67/LRq502a/iv4tA==', '$2y$10$bCcSDXQ65T.b9pnF1/pehOwIQ1OZcV.PeAENqRE/iWoeb6C4oaVJu', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `num_fact` varchar(15) NOT NULL,
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
-- Indices de la tabla `detalle_recepcion`
--
ALTER TABLE `detalle_recepcion`
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
-- Filtros para la tabla `detalle_recepcion`
--
ALTER TABLE `detalle_recepcion`
  ADD CONSTRAINT `detalle_recepcion_ibfk_1` FOREIGN KEY (`id_producto_sede`) REFERENCES `producto_sede` (`id_producto_sede`) ON DELETE CASCADE ON UPDATE CASCADE;

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
