-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
<<<<<<< HEAD
-- Tiempo de generación: 15-10-2024 a las 21:18:01
=======
-- Tiempo de generación: 15-10-2024 a las 23:40:15
>>>>>>> version_1
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
-- Base de datos: `bc_vac`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nino`
--

CREATE TABLE `nino` (
  `id` bigint(20) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `fecha_nacimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nino`
--

INSERT INTO `nino` (`id`, `nombre`, `apellido`, `fecha_nacimiento`) VALUES
(1, 'Juan', 'Pérez', '2015-06-15'),
(2, 'María', 'Gómez', '2016-08-22'),
(3, 'Carlos', 'López', '2017-11-30'),
(4, 'pedro', 'mons', '2024-10-02'),
(5, 'ped', 'mons', '2024-10-03'),
(6, 'gino', 'torrico', '2024-08-08'),
(7, 'Camila ', 'Torrico', '2024-08-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunas`
--

CREATE TABLE `vacunas` (
  `id` bigint(20) NOT NULL,
  `tipo_id` bigint(20) NOT NULL,
  `dosis` text NOT NULL,
  `fecha_vacunacion` date NOT NULL,
  `nino_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacunas`
--

INSERT INTO `vacunas` (`id`, `tipo_id`, `dosis`, `fecha_vacunacion`, `nino_id`) VALUES
(1, 1, '1', '2024-10-12', 6),
(2, 2, '1', '2024-10-12', 6),
(3, 2, '2', '2024-10-16', 6),
(4, 2, '3', '2024-10-12', 6),
(5, 1, '1', '2024-10-01', 4),
(6, 1, '2', '2024-10-10', 4),
(7, 3, '1', '2024-10-17', 4),
(8, 3, '1', '2024-10-17', 6),
(9, 3, '2', '2024-10-07', 6),
(10, 2, '4', '2024-10-18', 6),
(11, 1, '1', '2024-08-02', 7),
<<<<<<< HEAD
(12, 2, '1', '2024-08-02', 7);
=======
(12, 2, '1', '2024-08-02', 7),
(13, 1, '2', '2024-10-09', 7),
(14, 3, '1', '2024-10-16', 7);
>>>>>>> version_1

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacuna_tipo`
--

CREATE TABLE `vacuna_tipo` (
  `id` bigint(20) NOT NULL,
  `tipo` text NOT NULL,
  `dosis_requeridas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacuna_tipo`
--

INSERT INTO `vacuna_tipo` (`id`, `tipo`, `dosis_requeridas`) VALUES
(1, 'a', 2),
(2, 'b', 4),
(3, 'c', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `nino`
--
ALTER TABLE `nino`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_id` (`tipo_id`),
  ADD KEY `nino_id` (`nino_id`);

--
-- Indices de la tabla `vacuna_tipo`
--
ALTER TABLE `vacuna_tipo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo` (`tipo`) USING HASH;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `nino`
--
ALTER TABLE `nino`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `vacunas`
--
ALTER TABLE `vacunas`
<<<<<<< HEAD
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
=======
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
>>>>>>> version_1

--
-- AUTO_INCREMENT de la tabla `vacuna_tipo`
--
ALTER TABLE `vacuna_tipo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD CONSTRAINT `vacunas_ibfk_1` FOREIGN KEY (`tipo_id`) REFERENCES `vacuna_tipo` (`id`),
  ADD CONSTRAINT `vacunas_ibfk_2` FOREIGN KEY (`nino_id`) REFERENCES `nino` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
