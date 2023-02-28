-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-02-2023 a las 20:28:52
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `farmacia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(64) NOT NULL,
  `apellidos` varchar(64) NOT NULL,
  `dni` int(45) DEFAULT NULL,
  `edad` date NOT NULL,
  `telefono` int(64) DEFAULT NULL,
  `correo` varchar(64) DEFAULT NULL,
  `sexo` varchar(64) NOT NULL,
  `adicional` varchar(512) DEFAULT NULL,
  `avatar` varchar(256) DEFAULT NULL,
  `estado` varchar(16) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `nombre`, `apellidos`, `dni`, `edad`, `telefono`, `correo`, `sexo`, `adicional`, `avatar`, `estado`) VALUES
(1, 'Hermenegildo', 'López Diéguez', 123456789, '1992-07-27', 26, 'prueba@correo.com', 'Hombre', 'Nada', 'default_avatar.png', 'A'),
(2, 'Penélope', 'Rodríguez Sánchez', 98765433, '2023-02-04', 17, 'prueba@correo.com', 'Mujer', 'Nada', 'default_avatar.png', 'A'),
(3, 'Aldonza', 'de la Mancha', 10, '1998-02-05', 13, 'aldonza@farmacia.com', 'Mujer', 'Nada', 'default_avatar.png', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `codigo` varchar(128) NOT NULL,
  `fecha_compra` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `total` float NOT NULL,
  `id_estado_pago` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `codigo`, `fecha_compra`, `fecha_entrega`, `total`, `id_estado_pago`, `id_proveedor`) VALUES
(5, '11', '2023-01-30', '2023-02-05', 1, 1, 2),
(6, '987', '2023-01-29', '2023-02-04', 8, 1, 3),
(7, '1', '2023-01-30', '2023-02-01', 1, 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle` int(11) NOT NULL,
  `det_cantidad` int(11) NOT NULL,
  `det_vencimiento` date NOT NULL,
  `id__det_lote` int(11) NOT NULL,
  `id__det_prod` int(11) NOT NULL,
  `lote_id_prov` int(255) NOT NULL,
  `id_det_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_detalle`, `det_cantidad`, `det_vencimiento`, `id__det_lote`, `id__det_prod`, `lote_id_prov`, `id_det_venta`) VALUES
(1, 23, '2022-11-06', 9, 10, 1, 16),
(2, 10, '2022-11-06', 9, 10, 1, 17),
(3, 15, '2022-12-08', 12, 10, 3, 18),
(4, 5, '2022-12-10', 11, 10, 2, 18),
(5, 1, '2022-12-10', 11, 10, 2, 19),
(6, 1, '2022-11-06', 7, 12, 1, 19),
(7, 7, '2023-05-13', 8, 14, 2, 20),
(8, 2, '2023-01-08', 14, 18, 2, 21),
(9, 4, '2023-02-04', 15, 15, 3, 21),
(10, 1, '2022-12-10', 11, 10, 2, 21),
(11, 1, '2022-11-06', 7, 12, 1, 21),
(12, 2, '2023-05-13', 8, 14, 2, 21),
(13, 1, '2023-01-08', 14, 18, 2, 22),
(14, 12, '2023-02-05', 3, 15, 3, 23),
(15, 11, '2023-01-30', 6, 15, 5, 24),
(16, 1, '2023-02-05', 1, 13, 2, 25),
(17, 5, '2023-03-12', 2, 10, 3, 26),
(18, 10, '2023-02-05', 1, 13, 2, 27);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pago`
--

CREATE TABLE `estado_pago` (
  `id` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estado_pago`
--

INSERT INTO `estado_pago` (`id`, `nombre`) VALUES
(1, 'Cancelado'),
(2, 'No cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id_laboratorio` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `laboratorio`
--

INSERT INTO `laboratorio` (`id_laboratorio`, `nombre`, `avatar`, `estado`) VALUES
(2, 'Madrid', 'lab_default.png', 'A'),
(3, 'Córdoba', 'lab_default.png', 'A'),
(4, 'Eclipse', 'lab_default.png', 'A'),
(5, 'Forja de Estrellas', 'lab_default.png', 'A'),
(6, 'Portugal', 'lab_default.png', 'A'),
(10, '3M Peru', 'lab_default.png', 'A'),
(11, 'Bois', 'lab_default.png', 'A'),
(12, 'A & C MARVEL', 'lab_default.png', 'A'),
(13, 'Vitaline', 'lab_default.png', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `id` int(11) NOT NULL,
  `codigo` varchar(128) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `cantidad_lote` int(11) NOT NULL,
  `vencimiento` date NOT NULL,
  `precio_compra` float NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `estado` varchar(16) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `lote`
--

INSERT INTO `lote` (`id`, `codigo`, `cantidad`, `cantidad_lote`, `vencimiento`, `precio_compra`, `id_compra`, `id_producto`, `estado`) VALUES
(1, '123', 1, 0, '2023-02-05', 1, 5, 13, 'I'),
(2, '12345', 11, 6, '2023-03-12', 19, 6, 10, 'A'),
(3, '5555', 13, 1, '2023-02-05', 14, 6, 15, 'A'),
(4, '1111', 20, 20, '2023-03-12', 7, 7, 21, 'A'),
(5, '22', 17, 17, '2023-02-05', 2, 7, 14, 'A'),
(6, '321', 11, 0, '2023-01-30', 11, 7, 15, 'I');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `id_presentacion` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `presentacion`
--

INSERT INTO `presentacion` (`id_presentacion`, `nombre`, `estado`) VALUES
(2, 'Ampollas', 'A'),
(5, 'Comprimido', 'A'),
(6, 'Tableta', 'A'),
(7, 'Aerosol', 'A'),
(8, 'Inyectable', 'A'),
(9, 'Crema', 'A'),
(10, 'Suspensión', 'A'),
(11, 'Suspensión nasal', 'A'),
(12, 'Suspensión oftálmica', 'A'),
(13, 'Enema', 'A'),
(14, 'Emulsión', 'A'),
(15, 'Cápsula', 'A'),
(16, 'Gel', 'A'),
(17, 'Espuma', 'A'),
(18, 'Implante', 'A'),
(19, 'Loción', 'A'),
(20, 'Parche', 'A'),
(21, 'Jalea', 'A'),
(22, 'Jabón', 'A'),
(23, 'Gránulos', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `concentracion` varchar(255) DEFAULT NULL,
  `adicional` varchar(255) DEFAULT NULL,
  `precio` float NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'A',
  `prod_lab` int(11) NOT NULL,
  `prod_tip_prod` int(11) NOT NULL,
  `prod_present` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `concentracion`, `adicional`, `precio`, `avatar`, `estado`, `prod_lab`, `prod_tip_prod`, `prod_present`) VALUES
(10, 'Paracetamol', '100 mg', 'Caja envase bilateral', 1, 'prod_default.png', 'A', 2, 1, 5),
(11, 'Alercet', '10 mg/ml', 'Frascos a 15 ml', 2, 'prod_default.png', 'A', 2, 2, 2),
(12, 'Alercet', '1 mg/ml', 'Frascos a 60 ml', 1, 'prod_default.png', 'A', 2, 2, 2),
(13, 'A FOLIC', '6.5 mg', '', 1.5, 'prod_default.png', 'A', 5, 2, 6),
(14, 'Ramipril Normon', '2.5 mg', '', 1, 'prod_default.png', 'A', 6, 2, 5),
(15, 'AB AMBROMOX', '600 mg', 'Caja vial', 1, 'prod_default.png', 'A', 3, 2, 7),
(18, 'AB AMBROMOX', '300 mg', 'Caja vial', 2.65, 'prod_default.png', 'A', 5, 1, 7),
(19, 'ALBENDAZOL', '100 mg/5ml', 'Frascos a 20 ml', 3.3, 'prod_default.png', 'A', 12, 2, 2),
(20, 'AMIKACINA', '100 mg/2ml', 'Ampollas a 2ml', 1.5, 'prod_default.png', 'A', 11, 1, 7),
(21, 'ACICLOVIR', '200 mg', 'Caja envase blíster tabletas', 1, 'prod_default.png', 'A', 12, 1, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `telefono` int(11) NOT NULL,
  `correo` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `nombre`, `telefono`, `correo`, `direccion`, `avatar`, `estado`) VALUES
(1, 'Penélope', 123456789, '', 'Desengaño', 'prov_default.png', 'A'),
(2, 'Aldonza', 987654321, '', 'Desengaño', 'prov_default.png', 'A'),
(3, 'Distribuidora y droguería San Carlos', 222555888, 'carlos@carlos.com', 'Avda San Carlos', 'prov_default.png', 'A'),
(5, 'Envíos Sánchez', 4321, 'aaaa@correo.es', 'Plaza Malmuerta', 'prov_default.png', 'A'),
(6, 'prueba', 957437705, 'prueba@correo.com', 'Plaza Malmuerta', 'prov_default.png', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `id_tip_prod` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`id_tip_prod`, `nombre`, `estado`) VALUES
(1, 'Genérico', 'A'),
(2, 'Comercial', 'A'),
(3, 'Pandemia', 'A'),
(7, 'Regalos', 'A'),
(8, 'Joyería', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_us`
--

CREATE TABLE `tipo_us` (
  `id_tipo_us` int(11) NOT NULL,
  `nombre_tipo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_us`
--

INSERT INTO `tipo_us` (`id_tipo_us`, `nombre_tipo`) VALUES
(1, 'Administrador'),
(2, 'Tecnico'),
(3, 'Root');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_us` varchar(45) NOT NULL,
  `apellidos_us` varchar(45) NOT NULL,
  `edad` date NOT NULL,
  `dni_us` varchar(45) NOT NULL,
  `contrasena_us` varchar(256) NOT NULL,
  `telefono_us` int(11) NOT NULL DEFAULT 0,
  `residencia_us` varchar(45) NOT NULL,
  `correo_us` varchar(25) NOT NULL,
  `sexo_us` varchar(25) NOT NULL,
  `adicional_us` varchar(500) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `us_tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_us`, `apellidos_us`, `edad`, `dni_us`, `contrasena_us`, `telefono_us`, `residencia_us`, `correo_us`, `sexo_us`, `adicional_us`, `avatar`, `us_tipo`) VALUES
(1, 'Alejandro', 'Veranez', '1992-07-27', '1234567', '$2y$10$B3LI7o2/D54eUldEH3Gtmehb5o4QSDWq/ziu19FPLJyik2xYhhmfe', 987654321, 'Bastele', 'stc.fcojsr@gmail.com', 'Hombre', 'Supercalifragilisticoespialidoso', 'default.jpg', 3),
(2, 'Imilce', 'Olmeil', '0000-00-00', '7654321', '$2y$10$1rPvjMykdpgvfb0aSXODb.xvh03ocrBdh1U2dFtQ26fId4F5ysOiy', 0, '', 'imilce@gmail.com', 'Mujer', '', 'default.jpg', 1),
(4, 'Miriam', 'Belmonte', '2003-06-27', '1234569', '$2y$10$KXs7AFxaFuBuxWRw6DsS3ODPuW.Q9iF7mZeYR3GUSyu0gtc2Nr4Tm', 123456789, '', 'miriam@farmacia.com', 'Mujer', '', 'default.jpg', 1),
(6, 'Penélope', 'de las Altas Torres', '1999-02-11', '98765432', 's3rmg1nzlz', 0, '', 'penelope@farmacia.com', '', '', 'default.jpg', 2),
(7, 'Aldonza', 'de la Mancha', '1993-02-01', '98765433', '12345', 0, '', '', '', '', 'default.jpg', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id_venta` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `cliente` varchar(45) DEFAULT NULL,
  `dni` int(11) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `vendedor` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id_venta`, `fecha`, `cliente`, `dni`, `total`, `vendedor`, `id_cliente`) VALUES
(9, '2022-11-05 20:52:20', 'Claudia', 15, 5, 1, NULL),
(10, '2022-11-05 20:56:10', 'Estuarda', 10, 5, 1, NULL),
(11, '2022-11-05 20:57:13', 'm', 20, 7, 1, NULL),
(12, '2022-11-05 23:01:49', 'Vericueto', 123, 4, 1, NULL),
(13, '2022-11-05 23:04:48', 'asd', 111, 6, 1, NULL),
(14, '2022-11-05 23:08:08', 'Vericueto', 123, 6, 1, NULL),
(15, '2022-11-05 23:08:44', 'Vericueto', 123, 6, 1, NULL),
(16, '2022-11-25 17:45:34', 'pecosete', 123, 23, 1, NULL),
(17, '2022-11-25 17:47:52', 'pepeaca', 123, 10, 1, NULL),
(18, '2022-11-25 17:53:30', 'popo', 321, 20, 1, NULL),
(19, '2022-12-15 17:18:21', 'Pecosete', 1228, 2, 1, NULL),
(20, '2022-12-15 17:18:52', 'Pecoseta', 3322, 7, 1, NULL),
(21, '2023-01-09 23:21:03', 'Aldonza', 222213, 13.3, 1, NULL),
(22, '2023-01-13 20:49:52', NULL, NULL, 2.65, 1, 3),
(23, '2023-01-30 19:09:50', NULL, NULL, 12, 1, 3),
(24, '2023-01-30 19:18:03', NULL, NULL, 11, 1, 3),
(25, '2023-01-30 19:25:20', NULL, NULL, 1.5, 1, 3),
(26, '2023-01-30 19:43:16', NULL, NULL, 5, 1, 3),
(27, '2023-01-30 19:45:23', NULL, NULL, 15, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_producto`
--

CREATE TABLE `venta_producto` (
  `id_ventaproducto` int(11) NOT NULL,
  `precio` float NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` float NOT NULL,
  `producto_id_producto` int(11) NOT NULL,
  `venta_id_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta_producto`
--

INSERT INTO `venta_producto` (`id_ventaproducto`, `precio`, `cantidad`, `subtotal`, `producto_id_producto`, `venta_id_venta`) VALUES
(1, 0, 5, 5, 12, 9),
(2, 0, 5, 5, 12, 10),
(3, 0, 7, 7, 12, 11),
(4, 0, 2, 4, 11, 12),
(5, 0, 3, 6, 11, 13),
(6, 0, 3, 6, 11, 14),
(7, 0, 3, 6, 11, 15),
(8, 0, 23, 23, 10, 16),
(9, 0, 10, 10, 10, 17),
(10, 0, 20, 20, 10, 18),
(11, 0, 1, 1, 10, 19),
(12, 0, 1, 1, 12, 19),
(13, 0, 7, 7, 14, 20),
(14, 2.65, 2, 5.3, 18, 21),
(15, 1, 4, 4, 15, 21),
(16, 1, 1, 1, 10, 21),
(17, 1, 1, 1, 12, 21),
(18, 1, 2, 2, 14, 21),
(19, 2.65, 1, 2.65, 18, 22),
(20, 1, 12, 12, 15, 23),
(21, 1, 11, 11, 15, 24),
(22, 1.5, 1, 1.5, 13, 25),
(23, 1, 5, 5, 10, 26),
(24, 1.5, 10, 15, 13, 27);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estado_pago` (`id_estado_pago`,`id_proveedor`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_det_venta_idx` (`id_det_venta`);

--
-- Indices de la tabla `estado_pago`
--
ALTER TABLE `estado_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`id_laboratorio`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_compra` (`id_compra`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`id_presentacion`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `prod_lab_idx` (`prod_lab`),
  ADD KEY `prod_tip_prod_idx` (`prod_tip_prod`),
  ADD KEY `prod_present_idx` (`prod_present`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD PRIMARY KEY (`id_tip_prod`);

--
-- Indices de la tabla `tipo_us`
--
ALTER TABLE `tipo_us`
  ADD PRIMARY KEY (`id_tipo_us`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `us_tipo_idx` (`us_tipo`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `vendedor` (`vendedor`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD PRIMARY KEY (`id_ventaproducto`),
  ADD KEY `fk_venta_has_producto_producto1_idx` (`producto_id_producto`),
  ADD KEY `fk_venta_has_producto_venta1_idx` (`venta_id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `estado_pago`
--
ALTER TABLE `estado_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id_laboratorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  MODIFY `id_presentacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  MODIFY `id_tip_prod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tipo_us`
--
ALTER TABLE `tipo_us`
  MODIFY `id_tipo_us` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  MODIFY `id_ventaproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_estado_pago`) REFERENCES `estado_pago` (`id`),
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `id_det_venta` FOREIGN KEY (`id_det_venta`) REFERENCES `venta` (`id_venta`);

--
-- Filtros para la tabla `lote`
--
ALTER TABLE `lote`
  ADD CONSTRAINT `lote_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id`),
  ADD CONSTRAINT `lote_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `prod_lab` FOREIGN KEY (`prod_lab`) REFERENCES `laboratorio` (`id_laboratorio`),
  ADD CONSTRAINT `prod_present` FOREIGN KEY (`prod_present`) REFERENCES `presentacion` (`id_presentacion`),
  ADD CONSTRAINT `prod_tip_prod` FOREIGN KEY (`prod_tip_prod`) REFERENCES `tipo_producto` (`id_tip_prod`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `us_tipo` FOREIGN KEY (`us_tipo`) REFERENCES `tipo_us` (`id_tipo_us`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`);

--
-- Filtros para la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD CONSTRAINT `fk_venta_has_producto_producto1` FOREIGN KEY (`producto_id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `fk_venta_has_producto_venta1` FOREIGN KEY (`venta_id_venta`) REFERENCES `venta` (`id_venta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
