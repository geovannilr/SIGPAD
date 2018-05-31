-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-05-2018 a las 04:13:05
-- Versión del servidor: 10.1.31-MariaDB
-- Versión de PHP: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sigpad`
--
CREATE DATABASE IF NOT EXISTS `sigpad` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sigpad`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `sp_create_grupoTDG`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_grupoTDG` (IN `carnetsXML` VARCHAR(1000), OUT `result` INT)  BEGIN
-- -------------------------------------------------
-- -Variables
-- -------------------------------------------------
	DECLARE idgrupo INT DEFAULT 0;
	DECLARE i INT DEFAULT 1;	
	DECLARE rowcount INT;
	DECLARE carnet VARCHAR(50);
	DECLARE estID INT DEFAULT 0;
	DECLARE SALIDA VARCHAR(80);
-- -------------------------------------------------
-- -Manejador de excepciones
-- -------------------------------------------------
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		SET result = 1;
		-- SELECT 'Llave duplicada!' AS 'ERROR';
		ROLLBACK;
	END;

-- -------------------------------------------------	
-- -Inserción de datos en pdg_grupo
-- -------------------------------------------------
	START TRANSACTION;
	INSERT INTO `sigpad`.`pdg_grupo`(`cat_estado_idcat_estado`,`anio`,`ciclo`)
			VALUES (1, '2018','I');
	SET idgrupo = last_insert_id();	

-- -------------------------------------------------
-- -Inserción de datos en pdg_grupo_estudiante
-- -------------------------------------------------
	SET rowcount = ExtractValue(carnetsXML,'count(//carnet)') + 1;
	SET SALIDA = 'INI:';
	WHILE i < rowcount DO
		SET carnet = ExtractValue(carnetsXML,'//carnet[$i]');
		SELECT idgen_estudiante INTO estID FROM gen_estudiante WHERE carnet_estudiante = carnet;
		INSERT INTO `sigpad`.`pdg_grupo_estudiante` (`gen_estudiante_idgen_estudiante`,
			`pdg_grupo_idpdg_grupo`,`estado_pdg_grupo_estudiante`)
				VALUES (estID,idgrupo,3);
		SET carnet = 0;
		SET estID = 0;
		SET i = i + 1;
	END WHILE;
	COMMIT;
	SET result = 0;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_estado`
--

DROP TABLE IF EXISTS `cat_estado`;
CREATE TABLE `cat_estado` (
  `idcat_estado` int(11) NOT NULL,
  `nombre_estado` varchar(45) NOT NULL,
  `cat_tipo_estado_idcat_tipo_estado` int(11) NOT NULL,
  `descripcion_estado` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_estado`
--

INSERT INTO `cat_estado` (`idcat_estado`, `nombre_estado`, `cat_tipo_estado_idcat_tipo_estado`, `descripcion_estado`) VALUES
(1, 'Creación de propuesta', 1, 'Propuesta tentativa creada, pendiente aprobación de integrantes.'),
(2, 'Listo para envío', 2, 'Listo para enviar a aprobación de docente director.'),
(3, 'Aprobado', 3, 'Aprobado con asignación de código de grupo.'),
(4, 'Desintegrado', 4, 'Desintegrar un grupo, liberando integrantes.'),
(5, 'Confirmación en espera', 2, 'Esperando aprobación de integrante para conformar grupo.'),
(6, 'Aceptado', 3, 'Integrante acepta conformación de grupo.'),
(7, 'Enviado para aprobación', 2, 'Enviado a aprobación de docente director.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tipo_estado`
--

DROP TABLE IF EXISTS `cat_tipo_estado`;
CREATE TABLE `cat_tipo_estado` (
  `idcat_tipo_estado` int(11) NOT NULL,
  `nombre_tipo_estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_tipo_estado`
--

INSERT INTO `cat_tipo_estado` (`idcat_tipo_estado`, `nombre_tipo_estado`) VALUES
(1, 'Inicial'),
(2, 'Pendiente/Espera'),
(3, 'Aprobado'),
(4, 'Negado'),
(5, 'Cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_estudiante`
--

DROP TABLE IF EXISTS `gen_estudiante`;
CREATE TABLE `gen_estudiante` (
  `idgen_estudiante` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `carnet_estudiante` varchar(10) NOT NULL,
  `nombre_estudiante` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_estudiante`
--

INSERT INTO `gen_estudiante` (`idgen_estudiante`, `id`, `carnet_estudiante`, `nombre_estudiante`) VALUES
(1, 3, 'cm11005', 'Fernando Ernesto Cosme Morales'),
(2, 22, 'sb12002', 'Eduardo Rafael Serrano Barrera'),
(3, 21, 'rg12001', 'Edgardo José Ramírez García'),
(4, 20, 'pp10005', 'Francisco Wilfredo Polanco Portillo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_usuario`
--

DROP TABLE IF EXISTS `gen_usuario`;
CREATE TABLE `gen_usuario` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `gen_usuario`
--

INSERT INTO `gen_usuario` (`id`, `name`, `user`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Fernando Ernesto Cosme Morales', 'cm11005', 'cosme.92@gmail.com', '$2y$10$BjPijXdO87r91PjefxJA0ODEyOjmgyyhfdr.s0YxRMH77y9vYEzJG', 'fP9Te110V4Sd9mRFOKxm2lmhqswxUyTEZLSy0KlhyJiGeexlbOaBkEY7vPfC', '2018-04-01 03:23:31', '2018-05-01 22:46:29'),
(30, 'Administrador Central', 'admin', 'administrador@ues.edu.sv', '$2y$10$PdTVSePNsZMz/G/dT7uKM.nx4jwoCFRoU/qQntwPZQ9OtBjehYeiK', 'cs8VEjgblyBVMddeJQD94Zt3RjzLqgpNZvxNZfR4PJDF5g3m5gCQLVjNm70y', '2018-05-01 18:28:30', '2018-05-01 18:28:30'),
(22, 'Eduardo Rafael Serrano Barrera', 'sb12002', 'rafael31194@hotmail.com', '$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS', NULL, '2018-04-12 01:51:07', '2018-04-12 01:51:07'),
(21, 'Edgardo José Ramirez García', 'rg12001', 'edgardo.ramirez94@gmail.com', '$2y$10$x76t/jfOKVu3ZrBFcNMzxee47YyYwmjBpHCCN0ed1zgOF8D0TneGC', NULL, '2018-04-12 01:49:39', '2018-04-12 01:49:39'),
(20, 'Francisco Wilfredo Polanco Portillo', 'pp10005', 'polanco260593@gmail.com', '$2y$10$Db..R7cD93r70/TgPqYzLetuYq.U.vsseOJiiq/c3rw2WnEgY47b6', NULL, '2018-04-12 01:48:34', '2018-04-12 01:48:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2015_01_20_084450_create_roles_table', 1),
(4, '2015_01_20_084525_create_role_user_table', 1),
(5, '2015_01_24_080208_create_permissions_table', 1),
(6, '2015_01_24_080433_create_permission_role_table', 1),
(7, '2015_12_04_003040_add_special_role_column', 1),
(8, '2017_10_17_170735_create_permission_user_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_grupo`
--

DROP TABLE IF EXISTS `pdg_grupo`;
CREATE TABLE `pdg_grupo` (
  `idpdg_grupo` int(11) NOT NULL,
  `codigo_grupo` varchar(50) DEFAULT NULL,
  `nombre_grupo` varchar(45) DEFAULT NULL,
  `cat_estado_idcat_estado` int(11) NOT NULL,
  `anio` varchar(45) NOT NULL,
  `ciclo` varchar(45) NOT NULL,
  `cantidad` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_grupo`
--

INSERT INTO `pdg_grupo` (`idpdg_grupo`, `codigo_grupo`, `nombre_grupo`, `cat_estado_idcat_estado`, `anio`, `ciclo`, `cantidad`) VALUES
(15, NULL, NULL, 1, '2018', 'I', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_grupo_estudiante`
--

DROP TABLE IF EXISTS `pdg_grupo_estudiante`;
CREATE TABLE `pdg_grupo_estudiante` (
  `idpdg_grupo_estudiante` int(11) NOT NULL,
  `gen_estudiante_idgen_estudiante` int(11) NOT NULL,
  `pdg_grupo_idpdg_grupo` int(11) NOT NULL,
  `estado_pdg_grupo_estudiante` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Ver Roles', 'rol.index', 'Permite al usuario ver el listado de roles que posee el sistema disponibles.', '2018-04-29 16:33:59', '2018-04-29 17:02:28'),
(3, 'Ver Usuarios', 'usuario.index', 'Permite ver el listado de usuarios del sistema', '2018-05-01 16:14:09', '2018-05-01 16:14:09'),
(4, 'Crear Usuarios', 'usuario.create', 'Permite registrar nuevos usuarios en el sistema.', '2018-05-01 16:14:33', '2018-05-01 16:14:33'),
(5, 'Modificar Usuarios', 'usuario.edit', 'Permite modificar los usuarios registrados del sistema.', '2018-05-01 16:15:00', '2018-05-01 16:15:00'),
(6, 'Eliminar Usuarios', 'usuario.destroy', 'Permite eliminar usuarios registrados del sistema.', '2018-05-01 16:16:15', '2018-05-01 16:16:15'),
(7, 'Crear Roles', 'rol.create', 'Permite registrar nuevos roles en el sistema.', '2018-05-01 16:17:21', '2018-05-01 16:17:21'),
(8, 'Modificar Roles', 'rol.edit', 'Permite modificar roles registrados en el sistema.', '2018-05-01 16:17:48', '2018-05-01 16:17:48'),
(9, 'Eliminar Roles', 'rol.destroy', 'Permite eliminar roles registrados en el sistema.', '2018-05-01 16:22:52', '2018-05-01 16:22:52'),
(10, 'Crear Permisos', 'permiso.create', 'Permite al Usuario registrar nuevos permisos dentro del sistema.', '2018-05-01 16:25:06', '2018-05-01 16:25:06'),
(11, 'Modificar Permisos', 'permiso.edit', 'Permite  al usuario modificar permisos registrados en el sistema.', '2018-05-01 16:25:29', '2018-05-01 16:25:29'),
(12, 'Ver Permisos', 'permiso.index', 'Permite ver el listado de permisos registrados en el sistema.', '2018-05-01 16:26:25', '2018-05-01 16:26:25'),
(13, 'Crear grupo Trabajo de graduación', 'grupotdg.create', 'Permite a los estudiantes que empiezan su proceso de trabajo de graduación conformar grupo con los estudiantes de su afinidad.', '2018-05-01 16:45:27', '2018-05-01 16:45:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permission_role`
--

INSERT INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2018-04-29 17:57:05', '2018-04-29 17:57:05'),
(15, 13, 3, '2018-05-01 18:44:48', '2018-05-01 18:44:48'),
(4, 1, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(5, 3, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(6, 4, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(7, 5, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(8, 6, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(9, 7, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(10, 8, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(11, 9, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(12, 10, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(13, 11, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23'),
(14, 12, 6, '2018-05-01 16:28:23', '2018-05-01 16:28:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_user`
--

DROP TABLE IF EXISTS `permission_user`;
CREATE TABLE `permission_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `special` enum('all-access','no-access') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`, `special`) VALUES
(3, 'Estudiante', 'estudiante', 'Estudiante de la Escuela de ingeniería de Sistemas Informáticos', '2018-04-12 01:47:32', '2018-04-12 01:47:32', NULL),
(2, 'Docente', 'docente', 'Docente de la escuela de ingeniería de Sistemas', '2018-04-01 21:12:14', '2018-04-01 21:12:14', NULL),
(6, 'Administrador', 'administrador', 'Rol administrador del sistema, tiene los permisos para la gestion de usuarios, roles y permisos.', '2018-05-01 16:28:23', '2018-05-01 16:28:23', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, 20, '2018-04-12 01:48:34', '2018-04-12 01:48:34'),
(2, 3, 21, '2018-04-12 01:49:39', '2018-04-12 01:49:39'),
(3, 3, 22, '2018-04-12 01:51:07', '2018-04-12 01:51:07'),
(6, 3, 25, '2018-04-22 18:10:50', '2018-04-22 18:10:50'),
(7, 2, 25, '2018-04-22 18:10:50', '2018-04-22 18:10:50'),
(20, 6, 30, '2018-05-01 18:28:30', '2018-05-01 18:28:30'),
(16, 3, 3, '2018-04-26 04:07:18', '2018-04-26 04:07:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cat_estado`
--
ALTER TABLE `cat_estado`
  ADD PRIMARY KEY (`idcat_estado`),
  ADD KEY `fk_gen_estado_gen_tipo_estado1` (`cat_tipo_estado_idcat_tipo_estado`);

--
-- Indices de la tabla `cat_tipo_estado`
--
ALTER TABLE `cat_tipo_estado`
  ADD PRIMARY KEY (`idcat_tipo_estado`);

--
-- Indices de la tabla `gen_estudiante`
--
ALTER TABLE `gen_estudiante`
  ADD PRIMARY KEY (`idgen_estudiante`,`id`),
  ADD UNIQUE KEY `carnet_estudiante_UNIQUE` (`carnet_estudiante`);

--
-- Indices de la tabla `gen_usuario`
--
ALTER TABLE `gen_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gen_usuario_email_unique` (`email`),
  ADD UNIQUE KEY `carnet` (`user`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pdg_grupo`
--
ALTER TABLE `pdg_grupo`
  ADD PRIMARY KEY (`idpdg_grupo`),
  ADD KEY `fk_pdg_equipo_gen_estado1_idx` (`cat_estado_idcat_estado`);

--
-- Indices de la tabla `pdg_grupo_estudiante`
--
ALTER TABLE `pdg_grupo_estudiante`
  ADD PRIMARY KEY (`idpdg_grupo_estudiante`,`gen_estudiante_idgen_estudiante`,`pdg_grupo_idpdg_grupo`),
  ADD KEY `fk_pdg_equipo_estudiante_gen_estudiante_idx` (`gen_estudiante_idgen_estudiante`),
  ADD KEY `fk_pdg_equipo_estudiante_pdg_grupo_idx` (`pdg_grupo_idpdg_grupo`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indices de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Indices de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_user_permission_id_index` (`permission_id`),
  ADD KEY `permission_user_user_id_index` (`user_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indices de la tabla `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_role_id_index` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cat_estado`
--
ALTER TABLE `cat_estado`
  MODIFY `idcat_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cat_tipo_estado`
--
ALTER TABLE `cat_tipo_estado`
  MODIFY `idcat_tipo_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `gen_estudiante`
--
ALTER TABLE `gen_estudiante`
  MODIFY `idgen_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `gen_usuario`
--
ALTER TABLE `gen_usuario`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pdg_grupo`
--
ALTER TABLE `pdg_grupo`
  MODIFY `idpdg_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pdg_grupo_estudiante`
--
ALTER TABLE `pdg_grupo_estudiante`
  MODIFY `idpdg_grupo_estudiante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cat_estado`
--
ALTER TABLE `cat_estado`
  ADD CONSTRAINT `fk_gen_estado_gen_tipo_estado1` FOREIGN KEY (`cat_tipo_estado_idcat_tipo_estado`) REFERENCES `cat_tipo_estado` (`idcat_tipo_estado`);

--
-- Filtros para la tabla `pdg_grupo`
--
ALTER TABLE `pdg_grupo`
  ADD CONSTRAINT `fk_pdg_equipo_gen_estado1` FOREIGN KEY (`cat_estado_idcat_estado`) REFERENCES `cat_estado` (`idcat_estado`);

--
-- Filtros para la tabla `pdg_grupo_estudiante`
--
ALTER TABLE `pdg_grupo_estudiante`
  ADD CONSTRAINT `fk_pdg_equipo_estudiante_gen_estudiante` FOREIGN KEY (`gen_estudiante_idgen_estudiante`) REFERENCES `gen_estudiante` (`idgen_estudiante`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_equipo_estudiante_pdg_grupo` FOREIGN KEY (`pdg_grupo_idpdg_grupo`) REFERENCES `pdg_grupo` (`idpdg_grupo`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
