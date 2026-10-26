-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2026 a las 14:32:46
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
-- Base de datos: `equipos_vc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `mac_address` varchar(17) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `nombre`, `ip`, `mac_address`, `tipo`, `fecha_registro`) VALUES
(1, 'TV Sala de Pruebas', '192.168.1.50', 'A1:B2:C3:D4:E5:F6', 'Smart TV Android', '2026-10-12 17:52:37'),
(2, 'Samgung ', '192.168.2.8', 'D8:45:67:56:D1:F6', 'Smart TV', '2026-10-18 07:17:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_escaneos`
--

CREATE TABLE `historial_escaneos` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `aplicaciones` text DEFAULT NULL,
  `version_os` varchar(50) DEFAULT NULL,
  `fecha_escaneo` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_escaneos`
--

INSERT INTO `historial_escaneos` (`id`, `equipo_id`, `estado`, `aplicaciones`, `version_os`, `fecha_escaneo`) VALUES
(1, 1, 'Apagado', '', 'N/A', '2026-10-12 18:52:33');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip` (`ip`),
  ADD UNIQUE KEY `mac_address` (`mac_address`);

--
-- Indices de la tabla `historial_escaneos`
--
ALTER TABLE `historial_escaneos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_id` (`equipo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `historial_escaneos`
--
ALTER TABLE `historial_escaneos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_escaneos`
--
ALTER TABLE `historial_escaneos`
  ADD CONSTRAINT `historial_escaneos_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
