-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2024 a las 21:43:40
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
-- Base de datos: `vacupro_2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niños`
--

CREATE TABLE `niños` (
  `id` bigint(20) NOT NULL,
  `nombre` text DEFAULT NULL,
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
(1, 'jael', 'mons', 'mars', '2024-10-31', '234523452', '32452345', '2024-11-05 20:56:36', 'carlos', 'mons', 'mons', '6549874', 'Padre', '76992490'),
(3, 'paola andrea', 'callejas', 'caceres', '2024-08-27', '12322', '34523452345', '2024-11-05 22:22:57', 'su mama', 'su papa', 'mos', '64564654', 'Padre', '76992490');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id` bigint(20) NOT NULL,
  `puesto` text DEFAULT NULL,
  `nombre` text DEFAULT NULL,
  `apellido` text DEFAULT NULL,
  `numero_cedula_identidad` text DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id`, `puesto`, `nombre`, `apellido`, `numero_cedula_identidad`, `celular`) VALUES
(1, 'Enfermero', 'jimi', 'mons', '', '34634563456');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunaciones`
--

CREATE TABLE `vacunaciones` (
  `id` bigint(20) NOT NULL,
  `id_nino` bigint(20) NOT NULL,
  `tipo_id` bigint(20) NOT NULL,
  `numero_dosis` int(11) DEFAULT NULL,
  `fecha_administracion` date DEFAULT NULL,
  `id_personal` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacunaciones`
--

INSERT INTO `vacunaciones` (`id`, `id_nino`, `tipo_id`, `numero_dosis`, `fecha_administracion`, `id_personal`) VALUES
(1, 1, 4, 1, '2024-10-30', 1),
(2, 1, 3, 1, '2024-11-07', 1),
(3, 1, 3, 1, '2024-11-07', 1),
(4, 1, 4, 2, '2024-11-06', 1),
(12, 3, 1, 1, '2024-11-06', 1),
(13, 3, 1, 2, '2024-11-23', 1),
(14, 3, 2, 1, '2024-11-13', 1),
(15, 3, 5, 1, '2024-11-28', 1),
(17, 3, 1, 3, '2024-11-08', 1),
(18, 1, 1, 1, '2024-11-08', 1),
(19, 1, 3, 2, '2024-11-07', 1),
(20, 3, 4, 1, '2024-11-08', 1),
(21, 3, 4, 2, '2024-11-16', 1),
(25, 3, 6, 1, '2024-11-08', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacuna_tipo`
--

CREATE TABLE `vacuna_tipo` (
  `id` bigint(20) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `dosis_requeridas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacuna_tipo`
--

INSERT INTO `vacuna_tipo` (`id`, `tipo`, `dosis_requeridas`) VALUES
(1, 'Tuberculosis (BCG)', 1),
(2, 'Hepatitis B', 3),
(3, 'Poliovirus (Polio)', 3),
(4, 'Difteria', 3),
(5, 'Tétanos', 3),
(6, 'Tos Ferina', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacupro_2_usuarios`
--

CREATE TABLE `vacupro_2_usuarios` (
  `id` bigint(20) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacupro_2_usuarios`
--

INSERT INTO `vacupro_2_usuarios` (`id`, `usuario`, `contrasena`, `fecha_registro`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', '2024-11-08 20:23:34');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `niños`
--
ALTER TABLE `niños`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `tipo_id` (`tipo_id`),
  ADD KEY `id_personal` (`id_personal`);

--
-- Indices de la tabla `vacuna_tipo`
--
ALTER TABLE `vacuna_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vacupro_2_usuarios`
--
ALTER TABLE `vacupro_2_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `niños`
--
ALTER TABLE `niños`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `vacunaciones`
--
ALTER TABLE `vacunaciones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `vacuna_tipo`
--
ALTER TABLE `vacuna_tipo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `vacupro_2_usuarios`
--
ALTER TABLE `vacupro_2_usuarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `vacunaciones`
--
ALTER TABLE `vacunaciones`
  ADD CONSTRAINT `vacunaciones_ibfk_1` FOREIGN KEY (`id_nino`) REFERENCES `niños` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vacunaciones_ibfk_2` FOREIGN KEY (`tipo_id`) REFERENCES `vacuna_tipo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vacunaciones_ibfk_3` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
