-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-05-2018 a las 04:31:13
-- Versión del servidor: 5.7.14
-- Versión de PHP: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sigpad_dev`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_create` (IN `carnetsXML` VARCHAR(1000), OUT `result` INT)  BEGIN
-- -------------------------------------------------
-- -Variables
-- -------------------------------------------------
	DECLARE idgrupo INT DEFAULT 0;
	DECLARE i INT DEFAULT 1;	
	DECLARE rowcount INT;
	DECLARE carnet VARCHAR(50);
	DECLARE estID INT DEFAULT 0;
	DECLARE estadoInicial INT DEFAULT 0;
	DECLARE esLider INT DEFAULT 0;
-- -------------------------------------------------
-- -Manejador de excepciones
-- -------------------------------------------------
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		SET result = 1;
		SELECT result as 'resultado';
		ROLLBACK;
	END;

-- -------------------------------------------------	
-- -Inserción de datos en pdg_grupo
-- -------------------------------------------------
	START TRANSACTION;
	INSERT INTO `pdg_gru_grupo`(`id_cat_sta`,`anio_pdg_gru`,`ciclo_pdg_gru`)
			VALUES (1, year(now()),'I');
	SET idgrupo = last_insert_id();	

-- -------------------------------------------------
-- -Inserción de datos en pdg_grupo_estudiante
-- -------------------------------------------------
	SET estadoInicial = 6;
	SET esLider = 1;
	SET rowcount = ExtractValue(carnetsXML,'count(//carnet)') + 1;
	WHILE i < rowcount DO
		SET carnet = ExtractValue(carnetsXML,'//carnet[$i]');
		SELECT id_gen_est INTO estID FROM gen_est_estudiante WHERE carnet_gen_est = carnet;
		INSERT INTO `pdg_gru_est_grupo_estudiante` (`id_gen_est`,
			`id_pdg_gru`,`id_cat_sta`,`eslider_pdg_gru_est`)
				VALUES (estID,idgrupo,estadoInicial,esLider);
		SET carnet = 0;
		SET estID = 0;
		SET estadoInicial = 5;
		SET esLider = 0;
		SET i = i + 1;
	END WHILE;
	COMMIT;	
	SET result = 0;
	SELECT result as 'resultado';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_find_ByCarnet` (IN `carnet` VARCHAR(10), OUT `result` INT, OUT `carnetsJSON` VARCHAR(1000))  BEGIN
-- -------------------------------------------------
-- -Variables
-- -------------------------------------------------
	DECLARE idgrupo INT DEFAULT -1;
	DECLARE estID INT DEFAULT -1;
	DECLARE grupoID INT DEFAULT -1;
-- -------------------------------------------------
-- -Manejador de excepciones
-- -------------------------------------------------
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;

-- -------------------------------------------------
-- -Recuperar el id del estudiante por carnet
-- -------------------------------------------------
	SELECT id_gen_est INTO estID from gen_est_estudiante where carnet_gen_est = carnet;

-- -------------------------------------------------
-- -Recuperar el ID del grupo si lo tiene
-- -------------------------------------------------
	SELECT id_pdg_gru INTO grupoID from pdg_gru_est_grupo_estudiante where id_gen_est = estID;

-- -------------------------------------------------
-- -Armar resultados según la respuesta anterior
-- -------------------------------------------------
	IF grupoID > 0 THEN
		SELECT 
				CONCAT('[', GROUP_CONCAT(
								JSON_OBJECT('id',ge.id_gen_est,
											'nombre',ge.nombre_gen_est,
											'carnet',ge.carnet_gen_est,
											'estado',pge.id_cat_sta)
						),']')
			INTO carnetsJSON
			FROM pdg_gru_est_grupo_estudiante pge INNER JOIN gen_est_estudiante ge
			ON (pge.id_gen_est = ge.id_gen_est)
			WHERE id_pdg_gru = grupoID
			ORDER BY pge.eslider_pdg_gru_est DESC;
		SET result = 0;
	ELSE
		SET result = 1;
	END IF;
	SELECT result as 'resultado';
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_cri_eva_criterio_evaluacion`
--

CREATE TABLE `cat_cri_eva_criterio_evaluacion` (
  `id_cat_cri_eva` int(11) NOT NULL,
  `nombre_cat_cri_eva` varchar(45) NOT NULL,
  `ponderacion_cat_cri_eva` decimal(3,2) NOT NULL,
  `cat_eta_eva_etapa_evaluativa_id_cat_eta_eva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_eta_eva_etapa_evaluativa`
--

CREATE TABLE `cat_eta_eva_etapa_evaluativa` (
  `id_cat_eta_eva` int(11) NOT NULL,
  `nombre_cat_eta_eva` varchar(45) NOT NULL,
  `ponderacion_cat_eta_eva` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_pro_proceso`
--

CREATE TABLE `cat_pro_proceso` (
  `id_cat_pro` int(11) NOT NULL,
  `nombre_cat_pro` varchar(45) NOT NULL,
  `descripcion_cat_pro` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_sta_estado`
--

CREATE TABLE `cat_sta_estado` (
  `id_cat_sta` int(11) NOT NULL,
  `id_cat_tpo_sta` int(11) NOT NULL,
  `nombre_cat_sta` varchar(45) NOT NULL,
  `descripcion_cat_sta` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_sta_estado`
--

INSERT INTO `cat_sta_estado` (`id_cat_sta`, `id_cat_tpo_sta`, `nombre_cat_sta`, `descripcion_cat_sta`) VALUES
(1, 1, 'Creación de propuesta', 'Propuesta tentativa creada, pendiente aprobación de integrantes.'),
(2, 2, 'Listo para envío', 'Listo para enviar a aprobación de docente director.'),
(3, 3, 'Aprobado', 'Aprobado con asignación de código de grupo.'),
(4, 4, 'Desintegrado', 'Desintegrar un grupo, liberando integrantes.'),
(5, 2, 'Confirmación en espera', 'Esperando aprobación de integrante para conformar grupo.'),
(6, 3, 'Aceptado', 'Integrante acepta conformación de grupo.'),
(7, 2, 'Enviado para aprobación', 'Enviado a aprobación de docente director.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tpo_sta_tipo_estado`
--

CREATE TABLE `cat_tpo_sta_tipo_estado` (
  `id_cat_tpo_sta` int(11) NOT NULL,
  `nombre_cat_tpo_sta` varchar(45) NOT NULL,
  `descripcion_cat_tpo_sta` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_tpo_sta_tipo_estado`
--

INSERT INTO `cat_tpo_sta_tipo_estado` (`id_cat_tpo_sta`, `nombre_cat_tpo_sta`, `descripcion_cat_tpo_sta`) VALUES
(1, 'Inicial', 'Estado inicial.'),
(2, 'Pendiente/Espera', 'Previo a siguiente estado.'),
(3, 'Aprobado', 'Confirmado para continuar al siguiente estado'),
(4, 'Negado', 'Negado, devuelta a estado anterior.'),
(5, 'Cancelado', 'De vuelta a estado inicial.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tpo_tra_gra_tipo_trabajo_graduacion`
--

CREATE TABLE `cat_tpo_tra_gra_tipo_trabajo_graduacion` (
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `nombre_cat_tpo_tra_gra` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_est_estudiante`
--

CREATE TABLE `gen_est_estudiante` (
  `id_gen_est` int(11) NOT NULL,
  `id_gen_usr` int(11) NOT NULL,
  `carnet_gen_est` varchar(10) NOT NULL,
  `nombre_gen_est` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_est_estudiante`
--

INSERT INTO `gen_est_estudiante` (`id_gen_est`, `id_gen_usr`, `carnet_gen_est`, `nombre_gen_est`) VALUES
(1, 3, 'cm11005', 'Fernando Ernesto Cosme Morales'),
(2, 22, 'sb12002', 'Eduardo Rafael Serrano Barrera'),
(3, 21, 'rg12001', 'Edgardo José Ramírez García'),
(4, 20, 'pp10005', 'Francisco Wilfredo Polanco Portillo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_usuario`
--

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
(3, 'Fernando Ernesto Cosme Morales', 'cm11005', 'cosme.92@gmail.com', '$2y$10$BjPijXdO87r91PjefxJA0ODEyOjmgyyhfdr.s0YxRMH77y9vYEzJG', 'nbowshVGl6H3h2anWkcRprn3B6HWUbB3TKSycq3AFu7Hw0LmUcXNUHTBUQET', '2018-04-01 09:23:31', '2018-05-02 04:46:29'),
(30, 'Administrador Central', 'admin', 'administrador@ues.edu.sv', '$2y$10$PdTVSePNsZMz/G/dT7uKM.nx4jwoCFRoU/qQntwPZQ9OtBjehYeiK', '8xZ4bXMx4GBhV51dfUCAOshC550geTfGxQYKPsA0EaZmfhjqkYW09KCRxAG1', '2018-05-02 00:28:30', '2018-05-02 00:28:30'),
(22, 'Eduardo Rafael Serrano Barrera', 'sb12002', 'rafael31194@hotmail.com', '$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS', 'vIBhD1oxEPF3hJwN2JgqWNvy9XK4Qg4L2J9AohIhEMfaMCt2YsrYIleY71rh', '2018-04-12 07:51:07', '2018-04-12 07:51:07'),
(21, 'Edgardo José Ramirez García', 'rg12001', 'edgardo.ramirez94@gmail.com', '$2y$10$x76t/jfOKVu3ZrBFcNMzxee47YyYwmjBpHCCN0ed1zgOF8D0TneGC', '5Qs15kJ7reIIqV6P0nP1X9dOPrB8hzGffHRgN88YAWKlTkHUXs0YgY9v1BoK', '2018-04-12 07:49:39', '2018-04-12 07:49:39'),
(20, 'Francisco Wilfredo Polanco Portillo', 'pp10005', 'polanco260593@gmail.com', '$2y$10$Db..R7cD93r70/TgPqYzLetuYq.U.vsseOJiiq/c3rw2WnEgY47b6', NULL, '2018-04-12 07:48:34', '2018-04-12 07:48:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_gru_est_grupo_estudiante`
--

CREATE TABLE `pdg_gru_est_grupo_estudiante` (
  `id_pdg_gru_est` int(11) NOT NULL,
  `id_pdg_gru` int(11) NOT NULL,
  `id_gen_est` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `eslider_pdg_gru_est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_gru_grupo`
--

CREATE TABLE `pdg_gru_grupo` (
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `numero_pdg_gru` varchar(45) DEFAULT NULL,
  `correlativo_pdg_gru_gru` int(11) NOT NULL DEFAULT '0',
  `anio_pdg_gru` varchar(45) NOT NULL,
  `ciclo_pdg_gru` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_not_cri_tra_nota_criterio_trabajo`
--

CREATE TABLE `pdg_not_cri_tra_nota_criterio_trabajo` (
  `id_pdg_not_cri_tra` int(11) NOT NULL,
  `id_cat_cri_eva` int(11) NOT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  `nota_pdg_not_cri_tra` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_tra_gra_trabajo_graduacion`
--

CREATE TABLE `pdg_tra_gra_trabajo_graduacion` (
  `id_pdg_tra_gra` int(11) NOT NULL,
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `tema_pdg_tra_gra` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

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
(1, 'Ver Roles', 'rol.index', 'Permite al usuario ver el listado de roles que posee el sistema disponibles.', '2018-04-29 22:33:59', '2018-04-29 23:02:28'),
(3, 'Ver Usuarios', 'usuario.index', 'Permite ver el listado de usuarios del sistema', '2018-05-01 22:14:09', '2018-05-01 22:14:09'),
(4, 'Crear Usuarios', 'usuario.create', 'Permite registrar nuevos usuarios en el sistema.', '2018-05-01 22:14:33', '2018-05-01 22:14:33'),
(5, 'Modificar Usuarios', 'usuario.edit', 'Permite modificar los usuarios registrados del sistema.', '2018-05-01 22:15:00', '2018-05-01 22:15:00'),
(6, 'Eliminar Usuarios', 'usuario.destroy', 'Permite eliminar usuarios registrados del sistema.', '2018-05-01 22:16:15', '2018-05-01 22:16:15'),
(7, 'Crear Roles', 'rol.create', 'Permite registrar nuevos roles en el sistema.', '2018-05-01 22:17:21', '2018-05-01 22:17:21'),
(8, 'Modificar Roles', 'rol.edit', 'Permite modificar roles registrados en el sistema.', '2018-05-01 22:17:48', '2018-05-01 22:17:48'),
(9, 'Eliminar Roles', 'rol.destroy', 'Permite eliminar roles registrados en el sistema.', '2018-05-01 22:22:52', '2018-05-01 22:22:52'),
(10, 'Crear Permisos', 'permiso.create', 'Permite al Usuario registrar nuevos permisos dentro del sistema.', '2018-05-01 22:25:06', '2018-05-01 22:25:06'),
(11, 'Modificar Permisos', 'permiso.edit', 'Permite  al usuario modificar permisos registrados en el sistema.', '2018-05-01 22:25:29', '2018-05-01 22:25:29'),
(12, 'Ver Permisos', 'permiso.index', 'Permite ver el listado de permisos registrados en el sistema.', '2018-05-01 22:26:25', '2018-05-01 22:26:25'),
(13, 'Crear grupo Trabajo de graduación', 'grupotdg.create', 'Permite a los estudiantes que empiezan su proceso de trabajo de graduación conformar grupo con los estudiantes de su afinidad.', '2018-05-01 22:45:27', '2018-05-01 22:45:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_role`
--

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
(1, 1, 2, '2018-04-29 23:57:05', '2018-04-29 23:57:05'),
(15, 13, 3, '2018-05-02 00:44:48', '2018-05-02 00:44:48'),
(4, 1, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(5, 3, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(6, 4, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(7, 5, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(8, 6, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(9, 7, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(10, 8, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(11, 9, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(12, 10, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(13, 11, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23'),
(14, 12, 6, '2018-05-01 22:28:23', '2018-05-01 22:28:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_user`
--

CREATE TABLE `permission_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_est_pro_estudiante_proceso`
--

CREATE TABLE `rel_est_pro_estudiante_proceso` (
  `id_rel_est_pro` int(11) NOT NULL,
  `id_cat_pro` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `id_gen_est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_tpo_tra_eta_tipo_trabajo_etapa`
--

CREATE TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa` (
  `id_rel_tpo_tra_eta` int(11) NOT NULL,
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

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
(3, 'Estudiante', 'estudiante', 'Estudiante de la Escuela de ingeniería de Sistemas Informáticos', '2018-04-12 07:47:32', '2018-04-12 07:47:32', NULL),
(2, 'Docente', 'docente', 'Docente de la escuela de ingeniería de Sistemas', '2018-04-02 03:12:14', '2018-04-02 03:12:14', NULL),
(6, 'Administrador', 'administrador', 'Rol administrador del sistema, tiene los permisos para la gestion de usuarios, roles y permisos.', '2018-05-01 22:28:23', '2018-05-01 22:28:23', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_user`
--

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
(1, 3, 20, '2018-04-12 07:48:34', '2018-04-12 07:48:34'),
(2, 3, 21, '2018-04-12 07:49:39', '2018-04-12 07:49:39'),
(3, 3, 22, '2018-04-12 07:51:07', '2018-04-12 07:51:07'),
(6, 3, 25, '2018-04-23 00:10:50', '2018-04-23 00:10:50'),
(7, 2, 25, '2018-04-23 00:10:50', '2018-04-23 00:10:50'),
(20, 6, 30, '2018-05-02 00:28:30', '2018-05-02 00:28:30'),
(16, 3, 3, '2018-04-26 10:07:18', '2018-04-26 10:07:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cat_cri_eva_criterio_evaluacion`
--
ALTER TABLE `cat_cri_eva_criterio_evaluacion`
  ADD PRIMARY KEY (`id_cat_cri_eva`),
  ADD KEY `fk_cat_cri_eva_criterio_evaluacion_cat_eta_eva_etapa_evalua_idx` (`cat_eta_eva_etapa_evaluativa_id_cat_eta_eva`);

--
-- Indices de la tabla `cat_eta_eva_etapa_evaluativa`
--
ALTER TABLE `cat_eta_eva_etapa_evaluativa`
  ADD PRIMARY KEY (`id_cat_eta_eva`);

--
-- Indices de la tabla `cat_pro_proceso`
--
ALTER TABLE `cat_pro_proceso`
  ADD PRIMARY KEY (`id_cat_pro`);

--
-- Indices de la tabla `cat_sta_estado`
--
ALTER TABLE `cat_sta_estado`
  ADD PRIMARY KEY (`id_cat_sta`),
  ADD KEY `fk_cat_sta_estado_cat_tpo_sta_tipo_estado1_idx` (`id_cat_tpo_sta`);

--
-- Indices de la tabla `cat_tpo_sta_tipo_estado`
--
ALTER TABLE `cat_tpo_sta_tipo_estado`
  ADD PRIMARY KEY (`id_cat_tpo_sta`);

--
-- Indices de la tabla `cat_tpo_tra_gra_tipo_trabajo_graduacion`
--
ALTER TABLE `cat_tpo_tra_gra_tipo_trabajo_graduacion`
  ADD PRIMARY KEY (`id_cat_tpo_tra_gra`);

--
-- Indices de la tabla `gen_est_estudiante`
--
ALTER TABLE `gen_est_estudiante`
  ADD PRIMARY KEY (`id_gen_est`),
  ADD UNIQUE KEY `carnet_estudiante_UNIQUE` (`carnet_gen_est`);

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
-- Indices de la tabla `pdg_gru_est_grupo_estudiante`
--
ALTER TABLE `pdg_gru_est_grupo_estudiante`
  ADD PRIMARY KEY (`id_pdg_gru_est`),
  ADD KEY `fk_pdg_equipo_estudiante_pdg_equipo1_idx` (`id_pdg_gru`),
  ADD KEY `fk_pdg_gru_est_grupo_estudiante_cat_estado1_idx` (`id_cat_sta`),
  ADD KEY `fk_pdg_gru_est_grupo_estudiante_gen_est_estudiante1_idx` (`id_gen_est`);

--
-- Indices de la tabla `pdg_gru_grupo`
--
ALTER TABLE `pdg_gru_grupo`
  ADD PRIMARY KEY (`id_pdg_gru`),
  ADD KEY `fk_pdg_equipo_gen_estado1_idx` (`id_cat_sta`);

--
-- Indices de la tabla `pdg_not_cri_tra_nota_criterio_trabajo`
--
ALTER TABLE `pdg_not_cri_tra_nota_criterio_trabajo`
  ADD PRIMARY KEY (`id_pdg_not_cri_tra`,`id_cat_cri_eva`),
  ADD KEY `fk_pdg_not_cri_tra_nota_criterio_trabajo_cat_cri_eva_criter_idx` (`id_cat_cri_eva`),
  ADD KEY `fk_pdg_not_cri_tra_nota_criterio_trabajo_pdg_tra_gra_trabaj_idx` (`id_pdg_tra_gra`);

--
-- Indices de la tabla `pdg_tra_gra_trabajo_graduacion`
--
ALTER TABLE `pdg_tra_gra_trabajo_graduacion`
  ADD PRIMARY KEY (`id_pdg_tra_gra`),
  ADD KEY `fk_pdg_tra_gra_trabajo_graduacion_pdg_gru_grupo1_idx` (`id_pdg_gru`),
  ADD KEY `fk_pdg_tra_gra_trabajo_graduacion_cat_tpo_tra_gra_tipo_trab_idx` (`id_cat_tpo_tra_gra`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_UNIQUE` (`slug`);

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
-- Indices de la tabla `rel_est_pro_estudiante_proceso`
--
ALTER TABLE `rel_est_pro_estudiante_proceso`
  ADD PRIMARY KEY (`id_rel_est_pro`),
  ADD KEY `fk_rel_est_pro_estudiante_proceso_cat_pro_proceso1_idx` (`id_cat_pro`),
  ADD KEY `fk_rel_est_pro_estudiante_proceso_cat_sta_estado1_idx` (`id_cat_sta`),
  ADD KEY `fk_rel_est_pro_estudiante_proceso_gen_est_estudiante1_idx` (`id_gen_est`);

--
-- Indices de la tabla `rel_tpo_tra_eta_tipo_trabajo_etapa`
--
ALTER TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa`
  ADD PRIMARY KEY (`id_rel_tpo_tra_eta`,`id_cat_tpo_tra_gra`,`id_cat_eta_eva`),
  ADD KEY `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_tpo_tra_gra_tipo__idx` (`id_cat_tpo_tra_gra`),
  ADD KEY `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_eta_eva_etapa_eva_idx` (`id_cat_eta_eva`);

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
-- AUTO_INCREMENT de la tabla `cat_cri_eva_criterio_evaluacion`
--
ALTER TABLE `cat_cri_eva_criterio_evaluacion`
  MODIFY `id_cat_cri_eva` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cat_eta_eva_etapa_evaluativa`
--
ALTER TABLE `cat_eta_eva_etapa_evaluativa`
  MODIFY `id_cat_eta_eva` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cat_sta_estado`
--
ALTER TABLE `cat_sta_estado`
  MODIFY `id_cat_sta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `cat_tpo_sta_tipo_estado`
--
ALTER TABLE `cat_tpo_sta_tipo_estado`
  MODIFY `id_cat_tpo_sta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `cat_tpo_tra_gra_tipo_trabajo_graduacion`
--
ALTER TABLE `cat_tpo_tra_gra_tipo_trabajo_graduacion`
  MODIFY `id_cat_tpo_tra_gra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `gen_est_estudiante`
--
ALTER TABLE `gen_est_estudiante`
  MODIFY `id_gen_est` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
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
-- AUTO_INCREMENT de la tabla `pdg_gru_est_grupo_estudiante`
--
ALTER TABLE `pdg_gru_est_grupo_estudiante`
  MODIFY `id_pdg_gru_est` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `pdg_gru_grupo`
--
ALTER TABLE `pdg_gru_grupo`
  MODIFY `id_pdg_gru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `pdg_not_cri_tra_nota_criterio_trabajo`
--
ALTER TABLE `pdg_not_cri_tra_nota_criterio_trabajo`
  MODIFY `id_pdg_not_cri_tra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_tra_gra_trabajo_graduacion`
--
ALTER TABLE `pdg_tra_gra_trabajo_graduacion`
  MODIFY `id_pdg_tra_gra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_est_pro_estudiante_proceso`
--
ALTER TABLE `rel_est_pro_estudiante_proceso`
  MODIFY `id_rel_est_pro` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cat_cri_eva_criterio_evaluacion`
--
ALTER TABLE `cat_cri_eva_criterio_evaluacion`
  ADD CONSTRAINT `fk_cat_cri_eva_criterio_evaluacion_cat_eta_eva_etapa_evaluati1` FOREIGN KEY (`cat_eta_eva_etapa_evaluativa_id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cat_sta_estado`
--
ALTER TABLE `cat_sta_estado`
  ADD CONSTRAINT `fk_cat_sta_estado_cat_tpo_sta_tipo_estado1` FOREIGN KEY (`id_cat_tpo_sta`) REFERENCES `cat_tpo_sta_tipo_estado` (`id_cat_tpo_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_gru_est_grupo_estudiante`
--
ALTER TABLE `pdg_gru_est_grupo_estudiante`
  ADD CONSTRAINT `fk_pdg_equipo_estudiante_pdg_equipo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_gru_est_grupo_estudiante_cat_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_gru_est_grupo_estudiante_gen_est_estudiante1` FOREIGN KEY (`id_gen_est`) REFERENCES `gen_est_estudiante` (`id_gen_est`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_gru_grupo`
--
ALTER TABLE `pdg_gru_grupo`
  ADD CONSTRAINT `fk_pdg_equipo_gen_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_not_cri_tra_nota_criterio_trabajo`
--
ALTER TABLE `pdg_not_cri_tra_nota_criterio_trabajo`
  ADD CONSTRAINT `fk_pdg_not_cri_tra_nota_criterio_trabajo_cat_cri_eva_criterio1` FOREIGN KEY (`id_cat_cri_eva`) REFERENCES `cat_cri_eva_criterio_evaluacion` (`id_cat_cri_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_not_cri_tra_nota_criterio_trabajo_pdg_tra_gra_trabajo_1` FOREIGN KEY (`id_pdg_tra_gra`) REFERENCES `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_tra_gra_trabajo_graduacion`
--
ALTER TABLE `pdg_tra_gra_trabajo_graduacion`
  ADD CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_cat_tpo_tra_gra_tipo_trabaj1` FOREIGN KEY (`id_cat_tpo_tra_gra`) REFERENCES `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_pdg_gru_grupo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_est_pro_estudiante_proceso`
--
ALTER TABLE `rel_est_pro_estudiante_proceso`
  ADD CONSTRAINT `fk_rel_est_pro_estudiante_proceso_cat_pro_proceso1` FOREIGN KEY (`id_cat_pro`) REFERENCES `cat_pro_proceso` (`id_cat_pro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_est_pro_estudiante_proceso_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_est_pro_estudiante_proceso_gen_est_estudiante1` FOREIGN KEY (`id_gen_est`) REFERENCES `gen_est_estudiante` (`id_gen_est`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_tpo_tra_eta_tipo_trabajo_etapa`
--
ALTER TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa`
  ADD CONSTRAINT `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_eta_eva_etapa_evalu1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_tpo_tra_gra_tipo_tr1` FOREIGN KEY (`id_cat_tpo_tra_gra`) REFERENCES `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
