-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-04-2025 a las 17:28:32
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
-- Base de datos: `chismograph`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banned_posts`
--

CREATE TABLE `banned_posts` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `at_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banned_usuarios`
--

CREATE TABLE `banned_usuarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `at_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `ip` varchar(120) NOT NULL,
  `ubicacion` varchar(120) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `accion` varchar(120) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `osver` varchar(120) NOT NULL,
  `useragent` varchar(455) NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `receptor_id` int(11) NOT NULL,
  `post_original_id` int(11) NOT NULL,
  `comentario_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pay_historic`
--

CREATE TABLE `pay_historic` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pago_fecha` datetime DEFAULT current_timestamp(),
  `monto` decimal(10,2) DEFAULT 0.00,
  `metodo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_usuario`
--

CREATE TABLE `perfil_usuario` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `foto_portada` varchar(255) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `cred_index` double DEFAULT 0,
  `descripcion` text DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `gustos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gustos`)),
  `motivo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`motivo`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `padre_id` int(11) DEFAULT NULL,
  `contenido` varchar(480) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `likes` int(11) DEFAULT 0,
  `dislikes` int(11) DEFAULT 0,
  `cred_index` double DEFAULT 0,
  `isGob` tinyint(1) DEFAULT 0,
  `isCel` tinyint(1) DEFAULT 0,
  `isNews` tinyint(1) DEFAULT 0,
  `isUser` tinyint(1) DEFAULT 0,
  `isAdmin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reported_posts`
--

CREATE TABLE `reported_posts` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `motivo` varchar(480) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `at_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reported_users`
--

CREATE TABLE `reported_users` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `at_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `rol_desc` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabulador`
--

CREATE TABLE `tabulador` (
  `nivel` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tabulador`
--

INSERT INTO `tabulador` (`nivel`, `nombre`) VALUES
(0, 'MASTER'),
(1, 'BRONCE'),
(3, 'HIERRO'),
(5, 'PLATA'),
(7, 'ORO'),
(9, 'DIAMANTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL DEFAULT 1316,
  `user` varchar(180) NOT NULL,
  `pwd` varchar(180) NOT NULL,
  `birthday` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `auth_key` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `banned_posts`
--
ALTER TABLE `banned_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `banned_usuarios`
--
ALTER TABLE `banned_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receptor_id` (`receptor_id`),
  ADD KEY `post_original_id` (`post_original_id`),
  ADD KEY `comentario_id` (`comentario_id`);

--
-- Indices de la tabla `pay_historic`
--
ALTER TABLE `pay_historic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pay_historic_usuarios` (`user_id`);

--
-- Indices de la tabla `perfil_usuario`
--
ALTER TABLE `perfil_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_perfil_usuario_usuarios` (`user_id`);

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `padre_id` (`padre_id`);

--
-- Indices de la tabla `reported_posts`
--
ALTER TABLE `reported_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reported_users`
--
ALTER TABLE `reported_users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tabulador`
--
ALTER TABLE `tabulador`
  ADD PRIMARY KEY (`nivel`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `banned_posts`
--
ALTER TABLE `banned_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `banned_usuarios`
--
ALTER TABLE `banned_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pay_historic`
--
ALTER TABLE `pay_historic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfil_usuario`
--
ALTER TABLE `perfil_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reported_posts`
--
ALTER TABLE `reported_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reported_users`
--
ALTER TABLE `reported_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `notificaciones_ibfk_2` FOREIGN KEY (`post_original_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificaciones_ibfk_3` FOREIGN KEY (`comentario_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pay_historic`
--
ALTER TABLE `pay_historic`
  ADD CONSTRAINT `fk_pay_historic_usuarios` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `perfil_usuario`
--
ALTER TABLE `perfil_usuario`
  ADD CONSTRAINT `fk_perfil_usuario_usuarios` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`padre_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
