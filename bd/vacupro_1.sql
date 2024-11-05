-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2024 a las 17:44:25
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
-- Base de datos: `vacupro_1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario_vacunacion`
--

CREATE TABLE `calendario_vacunacion` (
  `id` bigint(20) NOT NULL,
  `id_vacuna` bigint(20) NOT NULL,
  `numero_dosis` int(11) NOT NULL,
  `edad_recomendada_meses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calendario_vacunacion`
--

INSERT INTO `calendario_vacunacion` (`id`, `id_vacuna`, `numero_dosis`, `edad_recomendada_meses`) VALUES
(1, 1, 1, 0),
(2, 2, 1, 0),
(3, 2, 2, 2),
(4, 2, 3, 6),
(5, 3, 1, 2),
(6, 3, 2, 4),
(7, 3, 3, 6),
(8, 3, 4, 18),
(9, 4, 1, 2),
(10, 4, 2, 4),
(11, 4, 3, 6),
(12, 4, 4, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niños`
--

CREATE TABLE `niños` (
  `id` bigint(20) NOT NULL,
  `nombre` text NOT NULL,
  `apellido_paterno` text DEFAULT NULL,
  `apellido_materno` text DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `numero_acta_nacimiento` text DEFAULT NULL,
  `numero_cedula_identidad` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nombre_responsable` text DEFAULT NULL,
  `apellido_paterno_tutor` text DEFAULT NULL,
  `apellido_materno_tutor` text DEFAULT NULL,
  `numero_cedula_tutor` text DEFAULT NULL,
  `relacion` text DEFAULT NULL,
  `telefono_tutor` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niños`
--

INSERT INTO `niños` (`id`, `nombre`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`, `numero_acta_nacimiento`, `numero_cedula_identidad`, `fecha_registro`, `nombre_responsable`, `apellido_paterno_tutor`, `apellido_materno_tutor`, `numero_cedula_tutor`, `relacion`, `telefono_tutor`) VALUES
(1, 'Carlos', 'García', 'Lopez', '2023-11-05', '123456', '1234567890', '2024-11-05 13:35:34', 'Carlos', 'Martínez', 'González', '987654321', 'Padre', '555-1234'),
(2, 'María', 'Perez', 'Sanchez', '2024-04-05', '789123', '9876543211', '2024-11-05 13:35:34', 'María', 'Ramirez', 'Lopez', '876543210', 'Madre', '555-5678'),
(3, 'José', 'Rodríguez', 'Fernandez', '2024-02-05', '456789', '1230984567', '2024-11-05 13:35:34', 'José', 'Gonzalez', 'Mendez', '234567890', 'Tío', '555-6789'),
(4, 'Ana', 'Gomez', 'Jimenez', '2024-08-05', '321654', '7654321098', '2024-11-05 13:35:34', 'Ana', 'Torres', 'Perez', '345678901', 'Abuela', '555-9876'),
(5, 'jimi', 'jor', 'moon', '2024-09-05', '64238472534462453', '5515984', '2024-11-05 13:51:43', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'jimi', 'moos', 'mons', '2024-09-06', '654648746321365476857', '4654654654', '2024-11-05 13:56:14', 'james', 'monns', 'monns', '23452345', 'Padre', '76992490'),
(7, 'jael', 'mons', 'mons', '2024-08-31', '46546749687987', '45658476987', '2024-11-05 13:59:15', 'mons', 'mons', 'mars', '6546215654', 'Madre', '76992490'),
(8, 'resmo', 'more', 'aosmrfs', '2024-10-16', '2345623452345', '2342342', '2024-11-05 15:28:30', 'mosn', 'mons', 'asdobnf', '58465', 'Madre', '76992490'),
(9, 'Carlos', 'Montes', 'Mars', '2024-09-20', '84968746574687', '564654715', '2024-11-05 15:55:54', 'javier', 'mons', 'mars', '52181984', 'Madre', '76992490');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` bigint(20) NOT NULL,
  `id_nino` bigint(20) NOT NULL,
  `tipo_notificacion` text DEFAULT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id` bigint(20) NOT NULL,
  `puesto` text NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `numero_cedula_identidad` text DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id`, `puesto`, `nombre`, `apellido`, `numero_cedula_identidad`, `celular`) VALUES
(1, 'Médico', 'Carlos', 'Pérez', '12345678', NULL),
(2, 'Enfermera', 'María', 'González', '87654321', NULL),
(3, 'Administrativo', 'Juan', 'López', '11223344', NULL),
(4, 'Vacunador', 'Ana', 'Martínez', '55667788', NULL),
(5, 'Técnico de laboratorio', 'Luis', 'Ramírez', '99887766', NULL),
(6, 'Médico', 'Laura', 'Fernández', '33445566', NULL),
(7, 'Enfermera', 'Lucía', 'Torres', '77889900', NULL),
(8, 'Medico', 'Pedro', 'Méndez', '66778899', '76992490'),
(9, 'Vacunador', 'Sofía', 'Vargas', '44556677', NULL),
(10, 'Técnico de laboratorio', 'Miguel', 'Jiménez', '22334455', NULL),
(11, 'Enfermero', 'javier', 'mons mars', '8765416874', NULL),
(12, 'Asistente', 'asfa', 'asdfas asdfasdf', '234234', NULL),
(13, 'Enfermero', 'names', ' ', '7654698787', NULL),
(14, 'Medico', 'javis', 'mor', '465164', '76992490');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunaciones`
--

CREATE TABLE `vacunaciones` (
  `id` bigint(20) NOT NULL,
  `id_nino` bigint(20) NOT NULL,
  `id_vacuna` bigint(20) NOT NULL,
  `numero_dosis` int(11) NOT NULL,
  `fecha_administracion` date NOT NULL,
  `id_personal` bigint(20) NOT NULL,
  `fecha_proxima_dosis` date DEFAULT NULL,
  `estado` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacunaciones`
--

INSERT INTO `vacunaciones` (`id`, `id_nino`, `id_vacuna`, `numero_dosis`, `fecha_administracion`, `id_personal`, `fecha_proxima_dosis`, `estado`) VALUES
(2, 9, 1, 0, '2024-11-06', 14, '2024-11-22', 'Pendiente'),
(3, 9, 2, 0, '2024-11-14', 13, NULL, 'Pendiente'),
(4, 7, 1, 0, '2024-10-30', 11, NULL, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunas`
--

CREATE TABLE `vacunas` (
  `id` bigint(20) NOT NULL,
  `nombre` text NOT NULL,
  `total_dosis` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacunas`
--

INSERT INTO `vacunas` (`id`, `nombre`, `total_dosis`) VALUES
(1, 'Tuberculosis (BCG)', 1),
(2, 'Hepatitis B', 3),
(3, 'Poliovirus (Polio)', 4),
(4, 'Difteria, Tétanos y Tos Ferina (DTP o DTaP)', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calendario_vacunacion`
--
ALTER TABLE `calendario_vacunacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vacuna` (`id_vacuna`);

--
-- Indices de la tabla `niños`
--
ALTER TABLE `niños`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nino` (`id_nino`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vacunaciones`
--
ALTER TABLE `vacunaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nino` (`id_nino`),
  ADD KEY `id_vacuna` (`id_vacuna`),
  ADD KEY `id_personal` (`id_personal`);

--
-- Indices de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calendario_vacunacion`
--
ALTER TABLE `calendario_vacunacion`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `niños`
--
ALTER TABLE `niños`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `vacunaciones`
--
ALTER TABLE `vacunaciones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calendario_vacunacion`
--
ALTER TABLE `calendario_vacunacion`
  ADD CONSTRAINT `calendario_vacunacion_ibfk_1` FOREIGN KEY (`id_vacuna`) REFERENCES `vacunas` (`id`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_nino`) REFERENCES `niños` (`id`);

--
-- Filtros para la tabla `vacunaciones`
--
ALTER TABLE `vacunaciones`
  ADD CONSTRAINT `vacunaciones_ibfk_1` FOREIGN KEY (`id_nino`) REFERENCES `niños` (`id`),
  ADD CONSTRAINT `vacunaciones_ibfk_2` FOREIGN KEY (`id_vacuna`) REFERENCES `vacunas` (`id`),
  ADD CONSTRAINT `vacunaciones_ibfk_3` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
