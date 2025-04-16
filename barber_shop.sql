-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-04-2025 a las 18:04:30
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
-- Base de datos: `barber_shop`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha_cita` datetime NOT NULL,
  `notas` text DEFAULT NULL,
  `total` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_servicios`
--

CREATE TABLE `citas_servicios` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stockMin` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `password` varchar(60) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `salario` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `password`, `telefono`, `cargo`, `fecha_contratacion`, `salario`, `status`) VALUES
(1, 'Juan Pérez', 'pass123', '3204567890', 'Barbero', '2023-05-10', 1800000, 1),
(2, 'Carlos Rodríguez', 'clave456', '3112345678', 'Barbero', '2022-08-15', 1850000, 2),
(3, 'Andrés Gómez', 'secreto789', '3156789012', 'Recepcionista', '2021-11-20', 1600000, 1),
(4, 'Luis Torres', 'password321', '3223456789', 'Barbero', '2023-01-25', 1900000, 2),
(5, 'Santiago López', 'clave654', '3109876543', 'Administrador', '2020-06-12', 2500000, 1),
(6, 'Diego Martínez', 'pass999', '3004561237', 'Barbero', '2023-09-30', 1800000, 1),
(7, 'Oscar Ramírez', 'secreto111', '3127894561', 'Recepcionista', '2022-04-18', 1650000, 2),
(8, 'Ricardo Herrera', 'clave777', '3012349876', 'Barbero', '2023-07-05', 1850000, 1),
(9, 'Fernando Díaz', 'pass222', '3045678923', 'Barbero', '2021-12-09', 1750000, 2),
(10, 'Hugo Castro', 'clave333', '3187654321', 'Recepcionista', '2020-02-14', 1600000, 1),
(11, 'Daniel Mendoza', 'pass444', '3056789012', 'Barbero', '2023-03-22', 1800000, 1),
(12, 'Javier Salazar', 'secreto555', '3171234567', 'Barbero', '2021-07-30', 1900000, 2),
(13, 'David Ospina', 'clave888', '3194567890', 'Recepcionista', '2022-05-08', 1650000, 1),
(14, 'Camilo Guzmán', 'pass666', '3119876543', 'Administrador', '2019-10-01', 2600000, 2),
(15, 'Felipe Vargas', 'secreto999', '3145678901', 'Barbero', '2023-11-05', 1850000, 1),
(16, 'Andrés Castillo', 'clave111', '3209871234', 'Recepcionista', '2020-09-17', 1600000, 2),
(17, 'Julio Ramírez', 'pass777', '3014567892', 'Barbero', '2022-03-28', 1750000, 1),
(18, 'Sebastián Ríos', 'secreto222', '3026789013', 'Barbero', '2021-08-19', 1800000, 2),
(19, 'Mauricio Herrera', 'clave333', '3037890124', 'Administrador', '2018-07-04', 2700000, 1),
(20, 'Ángel Bermúdez', 'pass888', '3048901235', 'Recepcionista', '2023-06-11', 1650000, 1),
(21, 'Tomás Paredes', 'secreto444', '3069012346', 'Barbero', '2022-12-30', 1900000, 2),
(22, 'Jorge Castaño', 'clave555', '3070123457', 'Barbero', '2020-05-21', 1850000, 1),
(23, 'Samuel Cortés', 'pass999', '3081234568', 'Recepcionista', '2021-03-15', 1600000, 2),
(24, 'Cristian Suárez', 'secreto666', '3092345679', 'Barbero', '2023-04-20', 1750000, 1),
(25, 'Jonathan Peña', 'clave777', '3103456780', 'Administrador', '2017-12-10', 2800000, 2),
(26, 'Gabriel León', 'pass000', '3124567891', 'Barbero', '2023-09-01', 1800000, 1),
(27, 'Leonardo Zapata', 'secreto111', '3135678902', 'Recepcionista', '2022-11-07', 1650000, 2),
(28, 'Esteban Morales', 'clave222', '3156789014', 'Barbero', '2021-06-23', 1850000, 1),
(29, 'Mateo Quintero', 'pass333', '3167890125', 'Barbero', '2020-01-29', 1900000, 2),
(30, 'Kevin Velásquez', 'secreto444', '3188901236', 'Recepcionista', '2023-02-12', 1600000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL,
  `fecha_venta` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_productos`
--

CREATE TABLE `ventas_productos` (
  `id` int(11) NOT NULL,
  `ventas_id` int(11) NOT NULL,
  `productos_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `citas_servicios`
--
ALTER TABLE `citas_servicios`
  ADD PRIMARY KEY (`id`,`cita_id`,`servicio_id`),
  ADD KEY `cita_id` (`cita_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `empleado_id` (`usuarios_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `empleado_id` (`usuarios_id`);

--
-- Indices de la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  ADD PRIMARY KEY (`id`,`ventas_id`,`productos_id`),
  ADD KEY `fk_ventas_has_productos_productos1_idx` (`productos_id`),
  ADD KEY `fk_ventas_has_productos_ventas1_idx` (`ventas_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `citas_servicios`
--
ALTER TABLE `citas_servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `citas_servicios`
--
ALTER TABLE `citas_servicios`
  ADD CONSTRAINT `cita_servicios_ibfk_1` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`),
  ADD CONSTRAINT `cita_servicios_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `cita_servicios_ibfk_3` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  ADD CONSTRAINT `fk_ventas_has_productos_productos1` FOREIGN KEY (`productos_id`) REFERENCES `productos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ventas_has_productos_ventas1` FOREIGN KEY (`ventas_id`) REFERENCES `ventas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
