-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2023 a las 22:21:46
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comanda`
--

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `pedidoId` int(11) NOT NULL,
  `puntMesa` int(11) NOT NULL,
  `puntRestaurant` int(11) NOT NULL,
  `puntMozo` int(11) NOT NULL,
  `puntCocinero` int(11) NOT NULL,
  `detalle` varchar(66) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `id` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `usuarioId` int(11) NOT NULL,
  `cliente` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `codPedido` varchar(5) NOT NULL,
  `mesaId` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `horaOrden` time,
  `horaInicio` time DEFAULT NULL,
  `horaFin` time DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL,
  `pedidoId` int(11) NOT NULL,
  `productoId` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `horaInicio` time,
  `tiempoEstimado` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `precio` float DEFAULT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `producto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `clave` varchar(20) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `sector` varchar(20) DEFAULT NULL,
  `fechaRegistro` date NOT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `email`, `clave`, `role`, `sector`, `fechaRegistro`, `fechaBaja`) VALUES
(1, 'Salvador', 'Pedrozo', 'Salva.7693@gmail.com', '1234', 'Admin', NULL, '2022-02-26', NULL),
(2, 'Esteban', 'Pedrozo', 'esteban@gmail.com', '1234', 'Empleado', 'Mozo', '2023-03-15', NULL),
(3, 'Makarena', 'Jara', 'maka@gmail.com', '1234', 'Empleado', 'Barra', '2022-09-26', NULL),
(4, 'Yesica', 'Contreras', 'yesi@gmail.com', '1234', 'Empleado', 'Cocina', '2022-11-20', NULL),
(5, 'Ale', 'Florentin', 'ale@gmail.com', '1234', 'Empleado', 'Cervecería', '2023-02-22', NULL),
(6, 'Esteban', 'Pedrozo', 'esteban@gmail.com', '1234', 'Empleado', 'Mozo', '2022-06-26', NULL);


INSERT INTO `producto` (`id`, `nombre`, `sector`, `precio`, `stock`) VALUES
(1, 'Pizza - Muzza', 'Cocina', 1000, 10),
(2, 'Pizza - Especial', 'Cocina', 1200, 10),
(3, 'Pizza - Peperoni', 'Cocina', 1500, 10),
(4, 'Cerveza - Quilmes', 'Cervecería', 600, 80),
(5, 'Cerveza - Brama', 'Cervecería', 600, 80),
(6, 'Cerveza - Artesanal', 'Cervecería', 600, 80),
(7, 'Vino Blanco', 'Barra', 900, 80),
(8, 'Vino Tinto', 'Barra', 900, 80),
(9, 'Flan', 'Cocina', 500, 80);


--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`id`, `estado`) VALUES
(1, 'Cerrada'),
(2, 'Cerrada'),
(3, 'Cerrada'),
(4, 'Cerrada'),
(5, 'Cerrada'),
(6, 'Cerrada');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
