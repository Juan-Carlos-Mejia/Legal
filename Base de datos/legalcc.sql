-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-10-2024 a las 09:17:04
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
-- Base de datos: `legalcc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audiencias`
--

CREATE TABLE `audiencias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `caso` varchar(50) NOT NULL,
  `modalidad` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `imputado` varchar(255) NOT NULL,
  `victima` varchar(255) NOT NULL,
  `delito` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `juzgado` varchar(50) NOT NULL,
  `abogado` varchar(50) NOT NULL,
  `fiscal` varchar(50) NOT NULL,
  `sala` varchar(50) NOT NULL,
  `juez_suplente` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `audiencias`
--

INSERT INTO `audiencias` (`id`, `titulo`, `caso`, `modalidad`, `fecha`, `hora`, `imputado`, `victima`, `delito`, `descripcion`, `juzgado`, `abogado`, `fiscal`, `sala`, `juez_suplente`) VALUES
(8, 'Primera 1', '2024-08-25-08-46-38-1598', 'Virtual', '2024-10-25', '20:16:00', 'Maria', 'Salvador', 'Hurto', 'Se necesita mas incformacion', 'Juzgado 1', 'Abogado 1', 'Fiscal 1', 'Sala 2', 'Suplente 3'),
(9, 'Primera', '2024-08-23-08-25-14-7711', 'Presencial', '2024-09-02', '21:20:00', 'Maria', 'Andrade', 'Violación', 'sdasdasd', 'Juzgado 1', 'Abogado 1', 'Fiscal 1', 'Sala 1', 'Suplente 1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casos`
--

CREATE TABLE `casos` (
  `id` int(11) NOT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `victima` varchar(255) DEFAULT NULL,
  `imputado` varchar(255) DEFAULT NULL,
  `tipo_delito` varchar(255) DEFAULT NULL,
  `documento` varchar(255) DEFAULT NULL,
  `fecha_creacion` date NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(10) DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casos`
--

INSERT INTO `casos` (`id`, `referencia`, `victima`, `imputado`, `tipo_delito`, `documento`, `fecha_creacion`, `estado`) VALUES
(136, '2024-08-25-08-46-38-1598', 'Andrade', 'Maria', 'fraude', NULL, '2021-08-25', 'activo'),
(137, '2024-09-25-02-44-22-5223', 'Juan', 'Maria', 'asalto', NULL, '2024-09-24', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casos_archivados`
--

CREATE TABLE `casos_archivados` (
  `id` int(11) NOT NULL,
  `referencia` varchar(255) NOT NULL,
  `victima` varchar(255) NOT NULL,
  `imputado` varchar(255) NOT NULL,
  `tipo_delito` varchar(255) NOT NULL,
  `archivos_documento` text DEFAULT NULL,
  `fecha_creacion` date NOT NULL,
  `fecha_expiracion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casos_archivados`
--

INSERT INTO `casos_archivados` (`id`, `referencia`, `victima`, `imputado`, `tipo_delito`, `archivos_documento`, `fecha_creacion`, `fecha_expiracion`) VALUES
(81, '2024-08-23-16-57-04-8065', 'Maria', 'Salvador', 'fraude', NULL, '2024-08-23', '2025-08-23'),
(87, '2024-08-23-08-25-14-7711', 'Salvador', 'Juan', 'homicidio', NULL, '2024-08-23', '2025-08-23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `declaraciones`
--

CREATE TABLE `declaraciones` (
  `id` int(11) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `tipo_declaracion` enum('documento','audio','video','texto') NOT NULL,
  `documento` text DEFAULT NULL,
  `audio` text DEFAULT NULL,
  `video` text DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `declaraciones`
--

INSERT INTO `declaraciones` (`id`, `apellido`, `nombre`, `caso_id`, `tipo_declaracion`, `documento`, `audio`, `video`, `texto`, `fecha`) VALUES
(2, 'On2PLGkPjw==', 'JH2RImk=', 137, 'texto', NULL, NULL, NULL, 'IXOPKigalfmPVxPF', '2024-10-17 06:10:48'),
(3, 'On2PLGkPjw==', 'JH2RImk=', 136, 'video', NULL, NULL, '[\"JmqGOWQKme+PDlLsgN5gPJR2nnwk\"]', NULL, '2024-10-17 06:14:10'),
(4, 'On2PLGkPjw==', 'JH2RImk=', 137, 'documento', '[\"OW6CKHwCg\\/3cAzbM0ux2No4qmmhx3ggQwHrr\"]', NULL, NULL, NULL, '2024-10-17 06:15:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delete_requests`
--

CREATE TABLE `delete_requests` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `razon` text NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `delete_requests`
--

INSERT INTO `delete_requests` (`id`, `nombre_usuario`, `razon`, `fecha_hora`, `fecha_solicitud`) VALUES
(5, 'Salvador Salgado', 'assdaasd', '2024-08-28 00:21:00', '2024-08-28 06:18:27'),
(6, 'Salvador Salgado', 'sdasdsad', '2024-08-28 02:19:00', '2024-08-28 06:19:06'),
(7, 'Salvador Salgado', 'Necesito mas tiempo', '2024-08-29 00:21:00', '2024-08-28 06:21:38'),
(8, 'Salvador Salgado', 'sdasd', '2024-08-29 00:22:00', '2024-08-28 06:22:20'),
(9, 'Salvador Salgado', 'asdsd', '2024-08-28 00:30:00', '2024-08-28 06:30:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `caso_referencia` varchar(255) DEFAULT NULL,
  `nombre_archivo` varchar(255) DEFAULT NULL,
  `tipo_archivo` varchar(255) DEFAULT NULL,
  `ubicacion_archivo` varchar(255) DEFAULT NULL,
  `archivado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id`, `caso_referencia`, `nombre_archivo`, `tipo_archivo`, `ubicacion_archivo`, `archivado`) VALUES
(40, '2024-08-25-08-46-38-1598', 'Entregable planificación del sprint (1) (1) (1).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'documentos/Entregable planificación del sprint (1) (1) (1).docx', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_archivados`
--

CREATE TABLE `documentos_archivados` (
  `id` int(11) NOT NULL,
  `caso_referencia` varchar(255) DEFAULT NULL,
  `nombre_archivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencias`
--

CREATE TABLE `evidencias` (
  `id` int(11) NOT NULL,
  `caso_referencia` varchar(255) DEFAULT NULL,
  `nombre_archivo` varchar(255) DEFAULT NULL,
  `tipo_archivo` varchar(255) DEFAULT NULL,
  `ubicacion_archivo` varchar(255) DEFAULT NULL,
  `tipo_evidencia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evidencias`
--

INSERT INTO `evidencias` (`id`, `caso_referencia`, `nombre_archivo`, `tipo_archivo`, `ubicacion_archivo`, `tipo_evidencia`) VALUES
(72, '2024-08-25-08-46-38-1598', 'H3CK0 X SR. TORTUGA X BAD BUNNY X ELADIO CARRION (IA) - Minnie (POLICIA) [MEME GTA ROLEPLAY NEXXUZ].mp3', NULL, 'uploads/H3CK0 X SR. TORTUGA X BAD BUNNY X ELADIO CARRION (IA) - Minnie (POLICIA) [MEME GTA ROLEPLAY NEXXUZ].mp3', NULL),
(73, '2024-08-25-08-46-38-1598', 'Mi diosa del amor (Venus) -Sr Tortuga (GTA Roleplay) (IA).mp3', NULL, 'uploads/Mi diosa del amor (Venus) -Sr Tortuga (GTA Roleplay) (IA).mp3', NULL),
(74, '2024-09-25-02-44-22-5223', '', '', 'uploads/', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencias_archivadas`
--

CREATE TABLE `evidencias_archivadas` (
  `id` int(11) NOT NULL,
  `caso_referencia` varchar(255) DEFAULT NULL,
  `tipo_evidencia` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_sesiones`
--

CREATE TABLE `historial_sesiones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `ip_usuario` varchar(50) DEFAULT NULL,
  `exito` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_sesiones`
--

INSERT INTO `historial_sesiones` (`id`, `usuario_id`, `correo`, `fecha_hora`, `ip_usuario`, `exito`) VALUES
(1, 7, 'parcial111@gmail.com', '2024-10-25 00:32:44', '::1', 1),
(2, NULL, 'maria222@gmail.com', '2024-10-25 01:04:02', '::1', 0),
(3, 15, 'maria222@gmail.com', '2024-10-25 01:04:09', '::1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imputados`
--

CREATE TABLE `imputados` (
  `id` int(6) UNSIGNED NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `dui` varchar(10) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `distrito` varchar(50) NOT NULL,
  `direccion` text NOT NULL,
  `madre` varchar(100) NOT NULL,
  `padre` varchar(100) NOT NULL,
  `pandilla` varchar(50) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `sexo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imputados`
--

INSERT INTO `imputados` (`id`, `codigo`, `apellido`, `nombre`, `fecha_nacimiento`, `dui`, `departamento`, `distrito`, `direccion`, `madre`, `padre`, `pandilla`, `alias`, `sexo`) VALUES
(9, 'IMP 288-4603-956', 'KHKHOWkPhQ==', 'On2PPWkPj+4=', '2002-11-14', 'UCTUfTxY0g', 'PG+WJ30fgfI=', 'PG+WJ30fgfKPZgHdlw==', '', '', '', 'BC0=', 'BSQ=', 'JH2QKH0HifLA'),
(10, 'IMP 660-2999-792', 'OnOQKg==', 'I2mPInsYgQ==', '2002-02-10', 'UCTUfTxY0g', 'On2NP2lLofLO', 'LXWQP3oClPOPEQ==', 'Cn2PJ21LjunKVROJk9MzNZ40mn551Q==', 'KHKELmQK', 'IG+KL3oE', 'Dn2XJHtLjvnIUR3a', 'BX3DLW0HifLO', 'L3mOLmYCjvM='),
(12, 'IMP 808-8105-594', 'DXqULQ==', 'DW+FOGw=', '0000-00-00', '', 'On2Na1sKjOrORx3b', 'On2Na1sKjOrORx3b0vF8I489', '', 'GniFOGw=', 'D3iQLQ==', 'GniFOA==', 'D2+HLQ==', 'L3mOLmYCjvM=');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imputados_archivados`
--

CREATE TABLE `imputados_archivados` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `apellido` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `dui` varchar(10) NOT NULL,
  `departamento` varchar(255) NOT NULL,
  `distrito` varchar(255) NOT NULL,
  `direccion` text NOT NULL,
  `madre` varchar(255) DEFAULT NULL,
  `padre` varchar(255) DEFAULT NULL,
  `pandilla` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `sexo` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imputados_archivados`
--

INSERT INTO `imputados_archivados` (`id`, `codigo`, `apellido`, `nombre`, `fecha_nacimiento`, `dui`, `departamento`, `distrito`, `direccion`, `madre`, `padre`, `pandilla`, `alias`, `sexo`) VALUES
(2, 'IMP 148-5874-102', 'KHKHOWkPhQ==', 'On2PPWkPj+4=', '0000-00-00', 'UCTUfTxY0g', 'On2Na1sKjOrORx3b', 'LXWQP3oClPOPEQ==', '', '', '', 'JE/SeA==', 'Ok8=', 'K'),
(3, 'IMP 679-0171-519', 'KE+nClsv', 'KE+nDkk4pNk=', '0000-00-00', '', '', '', '', '', '', 'LU+iCkw=', 'KE+nClsvtw==', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `remitente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `remitente_id`, `destinatario_id`, `mensaje`, `fecha_envio`) VALUES
(9, 15, 7, 'Hola', '2024-10-25 07:04:25'),
(10, 7, 15, 'Como estamos', '2024-10-25 07:04:43'),
(11, 15, 7, 'Muy bien y tu', '2024-10-25 07:07:22'),
(18, 7, 15, 'Hola maria', '2024-10-25 07:11:46'),
(19, 15, 7, 'Hola', '2024-10-25 07:14:19'),
(20, 7, 15, 'Hola', '2024-10-25 07:14:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `juez` varchar(100) NOT NULL,
  `razon` text NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `juez_id` int(11) NOT NULL,
  `razon` text NOT NULL,
  `fecha_sugerida` date NOT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `caso_id` int(11) DEFAULT NULL,
  `respuesta` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id`, `usuario_id`, `juez_id`, `razon`, `fecha_sugerida`, `estado`, `fecha_creacion`, `caso_id`, `respuesta`) VALUES
(10, 7, 9, 'Necesito mas tiempo para recolectar mas información', '2024-09-11', 'Rechazada', '2024-09-03 15:55:24', 8, 'Sin Respuesta'),
(13, 7, 9, 'Tremendo', '2024-09-04', 'Pendiente', '2024-09-04 06:45:04', 8, 'Sin Respuesta'),
(14, 7, 9, 'sdasdas', '2024-09-04', 'Pendiente', '2024-09-05 00:29:56', 8, 'Sin Respuesta'),
(15, 7, 9, 'asjhdkjdhasddsa', '2024-09-09', 'Pendiente', '2024-09-09 06:29:34', 8, 'Sin Respuesta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `telefono`, `tipo`, `correo`, `contrasena`) VALUES
(7, 'Salvador', 'Salgado', '1111-1111', 'abogado', 'parcial111@gmail.com', '$2y$10$xAaoM6TMmGcIb1YsIiJ9CumCMm9QAZ/DqOaMjpBEImwa.iXHh9pti'),
(9, 'Maria', 'Salgado', '2222-2222', 'juez', 'salva@gmail.com', '$2y$10$COTDr3mZ32Pm3w10uOIhYuoM4WqNNLUeVMXWV9DL03ub/yvN7LZwW'),
(14, 'Salvador', 'Salgado Osorio', '1111-1111', 'juez', 'parcial123@gmail.com', '$2y$10$AsbhgtZt.YtmJ7EDZrQnF.B7D/vujqC31DHnydk5UM3XuRcpI3I1W'),
(15, 'Maria', 'Andrade', '76284263', 'juez', 'maria222@gmail.com', '$2y$10$2oi/86TeFl6SciTXUPCogOUQSV/X.OaQKEacWbhAHvCq/FR750Xnm');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `victimas`
--

CREATE TABLE `victimas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `apellido` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `dui` varchar(20) NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `departamento` varchar(255) NOT NULL,
  `distrito` varchar(255) NOT NULL,
  `direccion` text NOT NULL,
  `madre` varchar(255) DEFAULT NULL,
  `padre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `victimas`
--

INSERT INTO `victimas` (`id`, `codigo`, `apellido`, `nombre`, `fecha_nacimiento`, `dui`, `sexo`, `departamento`, `distrito`, `direccion`, `madre`, `padre`) VALUES
(3, 'VIC 953-6560-972', 'On2PLGkPjw==', 'JH2RImk=', '0000-00-00', '', 'BH2QKH0HifLA', 'PG+WJ30fgfI=', 'PG+WJ30fgfKPZgHdlw==', 'WG6Ga0sEjPPBShOJsc1mKw==', 'JH2RImlLr+/AURvG', 'P3WAP2cZwM/OTxXIltA='),
(4, 'VIC 451-0824-324', 'On2PLGkPjw==', 'JH2RImk=', '2000-05-15', 'WC7Qfz1d2KuCEw==', 'BH2QKH0HifLA', 'OE2y', 'OE2y', 'OE2yGg==', 'OE2y', 'OE2y');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `victimas_archivados`
--

CREATE TABLE `victimas_archivados` (
  `id` int(11) NOT NULL,
  `codigo` varchar(225) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `dui` varchar(10) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `distrito` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `madre` varchar(100) DEFAULT NULL,
  `padre` varchar(100) DEFAULT NULL,
  `sexo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `victimas_archivados`
--

INSERT INTO `victimas_archivados` (`id`, `codigo`, `apellido`, `nombre`, `fecha_nacimiento`, `dui`, `departamento`, `distrito`, `direccion`, `madre`, `padre`, `sexo`) VALUES
(1, 'VIC 914-7407-792', 'On2PLGkPjw==', 'On2PPWkPj+4=', '2002-11-14', 'UCTUfTxY0q', 'On2Na1sKjOrORx3b', 'PG+WJ30fgfKPZgHdlw==', 'WG6Ga0sEjPPBShOJsc1mKw==', 'LX2KOHFLufPDQhzNk59DNDjpkg==', 'P3WAP2cZwM/OTxXIltA=', 'K3WNKnoCjw=='),
(2, 'VIC 001-1868-566', 'KHKHOWkPhQ==', 'On2PPWkPj+4=', '2010-11-11', '', 'PG+WJ30fgfI=', 'PG+WJ30fgfI=', 'WG6Ga0sEjPPBShOJsc1mKw==', 'LX2KOHFLufPDQhzNk59DNDjpkg==', 'Jm+AKnpLpe7BRgHdnZ9SP58qkmh1', 'OX2NOG0Tlf3D');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audiencias`
--
ALTER TABLE `audiencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `casos`
--
ALTER TABLE `casos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referencia` (`referencia`);

--
-- Indices de la tabla `casos_archivados`
--
ALTER TABLE `casos_archivados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referencia` (`referencia`);

--
-- Indices de la tabla `declaraciones`
--
ALTER TABLE `declaraciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `delete_requests`
--
ALTER TABLE `delete_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caso_referencia` (`caso_referencia`);

--
-- Indices de la tabla `documentos_archivados`
--
ALTER TABLE `documentos_archivados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caso_referencia` (`caso_referencia`);

--
-- Indices de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caso_referencia` (`caso_referencia`);

--
-- Indices de la tabla `evidencias_archivadas`
--
ALTER TABLE `evidencias_archivadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caso_referencia` (`caso_referencia`);

--
-- Indices de la tabla `historial_sesiones`
--
ALTER TABLE `historial_sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `imputados`
--
ALTER TABLE `imputados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `imputados_archivados`
--
ALTER TABLE `imputados_archivados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dui` (`dui`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `remitente_id` (`remitente_id`),
  ADD KEY `destinatario_id` (`destinatario_id`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `juez_id` (`juez_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `victimas`
--
ALTER TABLE `victimas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `victimas_archivados`
--
ALTER TABLE `victimas_archivados`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `audiencias`
--
ALTER TABLE `audiencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `casos`
--
ALTER TABLE `casos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT de la tabla `casos_archivados`
--
ALTER TABLE `casos_archivados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `declaraciones`
--
ALTER TABLE `declaraciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `delete_requests`
--
ALTER TABLE `delete_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `documentos_archivados`
--
ALTER TABLE `documentos_archivados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `evidencias_archivadas`
--
ALTER TABLE `evidencias_archivadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `historial_sesiones`
--
ALTER TABLE `historial_sesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `imputados`
--
ALTER TABLE `imputados`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `imputados_archivados`
--
ALTER TABLE `imputados_archivados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `victimas`
--
ALTER TABLE `victimas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `victimas_archivados`
--
ALTER TABLE `victimas_archivados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`caso_referencia`) REFERENCES `casos` (`referencia`);

--
-- Filtros para la tabla `documentos_archivados`
--
ALTER TABLE `documentos_archivados`
  ADD CONSTRAINT `documentos_archivados_ibfk_1` FOREIGN KEY (`caso_referencia`) REFERENCES `casos_archivados` (`referencia`);

--
-- Filtros para la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD CONSTRAINT `evidencias_ibfk_1` FOREIGN KEY (`caso_referencia`) REFERENCES `casos` (`referencia`);

--
-- Filtros para la tabla `evidencias_archivadas`
--
ALTER TABLE `evidencias_archivadas`
  ADD CONSTRAINT `evidencias_archivadas_ibfk_1` FOREIGN KEY (`caso_referencia`) REFERENCES `casos_archivados` (`referencia`);

--
-- Filtros para la tabla `historial_sesiones`
--
ALTER TABLE `historial_sesiones`
  ADD CONSTRAINT `historial_sesiones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`remitente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`destinatario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `solicitudes_ibfk_2` FOREIGN KEY (`juez_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
