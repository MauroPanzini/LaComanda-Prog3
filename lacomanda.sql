-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-07-2024 a las 04:32:27
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lacomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `codigoMesa` varchar(5) NOT NULL,
  `codigoPedido` varchar(5) NOT NULL,
  `puntajeMozo` int(11) NOT NULL,
  `puntajeMesa` int(11) NOT NULL,
  `puntajeRestaurante` int(11) NOT NULL,
  `puntajeCocinero` int(11) NOT NULL,
  `promedio` float NOT NULL,
  `descripcion` varchar(66) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `codigoMesa`, `codigoPedido`, `puntajeMozo`, `puntajeMesa`, `puntajeRestaurante`, `puntajeCocinero`, `promedio`, `descripcion`) VALUES
(1, 'jAs9y', 'A1b2C', 7, 6, 8, 9, 7.5, 'Muy bueno'),
(2, 'K8JoI', 'D3e4F', 4, 5, 3, 6, 4.5, 'Podría mejorar'),
(3, 'm2hDa', 'G5h6I', 10, 10, 10, 10, 10, 'Excelente'),
(4, 'jAs9y', 'J7k8L', 3, 2, 4, 5, 3.5, 'Regular'),
(5, 'K8JoI', 'M9n0O', 9, 8, 7, 9, 8.25, 'Muy buena experiencia'),
(6, 'm2hDa', 'P1q2R', 6, 5, 6, 7, 6, 'Satisfactorio'),
(7, 'jAs9y', 'S3t4U', 2, 3, 1, 4, 2.5, 'No fue bueno'),
(8, 'K8JoI', 'V5w6X', 5, 6, 5, 6, 5.5, 'Aceptable'),
(9, 'm2hDa', 'Y7z8A', 8, 7, 8, 8, 7.75, 'Muy buen servicio'),
(10, 'jAs9y', 'B9c0D', 4, 3, 2, 5, 3.5, 'Podría ser mejor'),
(11, 'K8JoI', 'E1f2G', 7, 8, 6, 7, 7, 'Buen lugar'),
(12, 'm2hDa', 'H3i4J', 9, 9, 9, 10, 9.25, 'Fantástico'),
(13, 'jAs9y', 'K5l6M', 1, 2, 3, 2, 2, 'Malo'),
(14, 'K8JoI', 'N7o8P', 6, 5, 7, 6, 6, 'Buen promedio'),
(15, 'm2hDa', 'Q9r0S', 3, 4, 5, 4, 4, 'Normal'),
(16, 'jAs9y', 'T1u2V', 10, 9, 8, 9, 9, 'Genial'),
(17, 'K8JoI', 'W3x4Y', 5, 6, 5, 7, 5.75, 'Bien, pero puede mejorar'),
(18, 'm2hDa', 'Z5a6B', 7, 8, 7, 8, 7.5, 'Muy bueno'),
(19, 'jAs9y', 'C7d8E', 6, 6, 6, 7, 6.25, 'Aceptable'),
(20, 'K8JoI', 'F9g0H', 3, 3, 4, 3, 3.25, 'Podría mejorar un poco');


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `estado` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `estado`) VALUES
(1, 'jAs9y', 'cerrada'),
(2, 'K8JoI', 'cerrada'),
(3, 'm2hDa', 'cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigoMesa` varchar(5) NOT NULL,
  `codigoPedido` varchar(5) NOT NULL,
  `nombreCliente` varchar(25) NOT NULL,
  `precioFinal` int(11) NOT NULL,
  `rutaFoto` varchar(60) DEFAULT NULL,
  `tiempoEstimadoPedido` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigoMesa`, `codigoPedido`, `nombreCliente`, `precioFinal`, `rutaFoto`, `tiempoEstimadoPedido`) VALUES
(1, 'jAs9y', 'A1b2C', 'Carlos', 25000, 'jAs9y.jpg', 0),
(2, 'K8JoI', 'D3e4F', 'María', 30000, 'K8JoI.jpg', 0),
(3, 'm2hDa', 'G5h6I', 'Juan', 35000, 'm2hDa.jpg', 0),
(4, 'jAs9y', 'J7k8L', 'Ana', 22000, 'jAs9y.jpg', 0),
(5, 'K8JoI', 'M9n0O', 'Luis', 28000, 'K8JoI.jpg', 0),
(6, 'm2hDa', 'P1q2R', 'Laura', 32000, 'm2hDa.jpg', 0),
(7, 'jAs9y', 'S3t4U', 'Pedro', 27000, 'jAs9y.jpg', 0),
(8, 'K8JoI', 'V5w6X', 'Sofía', 34000, 'K8JoI.jpg', 0),
(9, 'm2hDa', 'Y7z8A', 'Daniel', 36000, 'm2hDa.jpg', 0),
(10, 'jAs9y', 'B9c0D', 'Lucía', 21000, 'jAs9y.jpg', 0),
(11, 'K8JoI', 'E1f2G', 'Diego', 33000, 'K8JoI.jpg', 0),
(12, 'm2hDa', 'H3i4J', 'Elena', 31000, 'm2hDa.jpg', 0),
(13, 'jAs9y', 'K5l6M', 'Gabriel', 29000, 'jAs9y.jpg', 0),
(14, 'K8JoI', 'N7o8P', 'Natalia', 26000, 'K8JoI.jpg', 0),
(15, 'm2hDa', 'Q9r0S', 'Fernando', 38000, 'm2hDa.jpg', 0),
(16, 'jAs9y', 'T1u2V', 'Marta', 37000, 'jAs9y.jpg', 0),
(17, 'K8JoI', 'W3x4Y', 'Ricardo', 24000, 'K8JoI.jpg', 0),
(18, 'm2hDa', 'Z5a6B', 'Valeria', 39000, 'm2hDa.jpg', 0),
(19, 'jAs9y', 'C7d8E', 'Álvaro', 35000, 'jAs9y.jpg', 0),
(20, 'K8JoI', 'F9g0H', 'Patricia', 28000, 'K8JoI.jpg', 0);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_productos`
--

CREATE TABLE `pedidos_productos` (
  `id` int(11) NOT NULL,
  `codigoMesa` varchar(5) NOT NULL,
  `codigoPedido` varchar(5) NOT NULL,
  `nombreProducto` varchar(25) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioTotal` int(11) NOT NULL,
  `encargado` varchar(20) NOT NULL,
  `estado` varchar(25) NOT NULL,
  `tiempoPreparacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_productos`
--

INSERT INTO `pedidos_productos` (`id`, `codigoMesa`, `codigoPedido`, `nombreProducto`, `cantidad`, `precioTotal`, `encargado`, `estado`, `tiempoPreparacion`) VALUES
(55, 'jAs9y', 'A1b2C', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado con demora', 0),
(56, 'jAs9y', 'A1b2C', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado con demora', 0),
(57, 'jAs9y', 'A1b2C', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado con demora', 0),
(58, 'jAs9y', 'A1b2C', 'Daikiri', 1, 4000, 'bartender', 'entregado con demora', 0),
(59, 'K8JoI', 'D3e4F', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(60, 'K8JoI', 'D3e4F', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(61, 'K8JoI', 'D3e4F', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(62, 'K8JoI', 'D3e4F', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(63, 'm2hDa', 'G5h6I', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(64, 'm2hDa', 'G5h6I', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(65, 'm2hDa', 'G5h6I', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(66, 'm2hDa', 'G5h6I', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(67, 'jAs9y', 'J7k8L', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado con demora', 0),
(68, 'jAs9y', 'J7k8L', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado con demora', 0),
(69, 'jAs9y', 'J7k8L', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado con demora', 0),
(70, 'jAs9y', 'J7k8L', 'Daikiri', 1, 4000, 'bartender', 'entregado con demora', 0),
(71, 'K8JoI', 'M9n0O', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(72, 'K8JoI', 'M9n0O', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(73, 'K8JoI', 'M9n0O', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(74, 'K8JoI', 'M9n0O', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(75, 'm2hDa', 'P1q2R', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(76, 'm2hDa', 'P1q2R', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(77, 'm2hDa', 'P1q2R', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(78, 'm2hDa', 'P1q2R', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(79, 'jAs9y', 'S3t4U', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(80, 'jAs9y', 'S3t4U', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(81, 'jAs9y', 'S3t4U', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(82, 'jAs9y', 'S3t4U', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(83, 'K8JoI', 'V5w6X', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(84, 'K8JoI', 'V5w6X', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(85, 'K8JoI', 'V5w6X', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(86, 'K8JoI', 'V5w6X', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(87, 'm2hDa', 'Y7z8A', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(88, 'm2hDa', 'Y7z8A', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(89, 'm2hDa', 'Y7z8A', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(90, 'm2hDa', 'Y7z8A', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(91, 'jAs9y', 'B9c0D', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(92, 'jAs9y', 'B9c0D', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(93, 'jAs9y', 'B9c0D', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(94, 'jAs9y', 'B9c0D', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(95, 'K8JoI', 'E1f2G', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(96, 'K8JoI', 'E1f2G', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(97, 'K8JoI', 'E1f2G', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(98, 'K8JoI', 'E1f2G', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(99, 'm2hDa', 'H3i4J', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(100, 'm2hDa', 'H3i4J', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(101, 'm2hDa', 'H3i4J', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(102, 'm2hDa', 'H3i4J', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(103, 'jAs9y', 'K5l6M', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(104, 'jAs9y', 'K5l6M', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(105, 'jAs9y', 'K5l6M', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(106, 'jAs9y', 'K5l6M', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(107, 'K8JoI', 'N7o8P', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(108, 'K8JoI', 'N7o8P', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(109, 'K8JoI', 'N7o8P', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(110, 'K8JoI', 'N7o8P', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(111, 'm2hDa', 'Q9r0S', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(112, 'm2hDa', 'Q9r0S', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(113, 'm2hDa', 'Q9r0S', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(114, 'm2hDa', 'Q9r0S', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(115, 'jAs9y', 'T1u2V', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(116, 'jAs9y', 'T1u2V', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(117, 'jAs9y', 'T1u2V', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(118, 'jAs9y', 'T1u2V', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(119, 'K8JoI', 'W3x4Y', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(120, 'K8JoI', 'W3x4Y', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(121, 'K8JoI', 'W3x4Y', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(122, 'K8JoI', 'W3x4Y', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(123, 'm2hDa', 'Z5a6B', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(124, 'm2hDa', 'Z5a6B', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(125, 'm2hDa', 'Z5a6B', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(126, 'm2hDa', 'Z5a6B', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(127, 'jAs9y', 'C7d8E', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(128, 'jAs9y', 'C7d8E', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(129, 'jAs9y', 'C7d8E', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(130, 'jAs9y', 'C7d8E', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0),
(131, 'K8JoI', 'F9g0H', 'Milanesa a caballo', 1, 8800, 'cocinero', 'entregado', 0),
(132, 'K8JoI', 'F9g0H', 'Hamburguesa de garbanzos', 2, 15200, 'cocinero', 'entregado', 0),
(133, 'K8JoI', 'F9g0H', 'Cerveza Corona', 1, 3500, 'cervecero', 'entregado', 0),
(134, 'K8JoI', 'F9g0H', 'Daikiri', 1, 4000, 'bartender', 'entregado', 0);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `precio` int(11) NOT NULL,
  `tiempoEstimado` int(11) NOT NULL,
  `encargado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `tiempoEstimado`, `encargado`) VALUES
(1, 'Milanesa a caballo', 6000, 20, 'cocinero'),
(2, 'Hamburguesa de garbanzos', 4500, 15, 'cocinero'),
(3, 'Cerveza Corona', 3000, 5, 'cervecero'),
(4, 'Daikiri', 3500, 10, 'bartender');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `sector` varchar(20) NOT NULL,
  `fechaIngreso` date DEFAULT NULL,
  `fechaBaja` date DEFAULT NULL,
  `nombre` varchar(20) NOT NULL,
  `clave` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `sector`, `fechaIngreso`, `fechaBaja`, `nombre`, `clave`) VALUES
(1, 'admin', '2022-06-20', NULL, 'Mau', '1234'),
(2, 'mozo', '2024-06-18', NULL, 'Mozo1', '1234'),
(3, 'cocinero', '2023-05-20', NULL, 'Cocinero1', '1234'),
(4, 'bartender', '2023-08-10', NULL, 'Bartender1', '1234'),
(6, 'cervecero', '2024-07-07', NULL, 'Cervecero1', '1234');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
