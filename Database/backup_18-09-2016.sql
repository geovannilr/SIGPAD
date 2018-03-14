/*
SQLyog Enterprise - MySQL GUI v8.05 
MySQL - 5.5.50-0ubuntu0.14.04.1 : Database - sql9132828
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`sql9132828` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `sql9132828`;

/*Table structure for table `Privilegios` */

DROP TABLE IF EXISTS `Privilegios`;

CREATE TABLE `Privilegios` (
  `id_privilegio` int(255) NOT NULL AUTO_INCREMENT,
  `nombre_privilegio` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_privilegio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `Privilegios` */

insert  into `Privilegios`(`id_privilegio`,`nombre_privilegio`) values (1,'Usuario');

/*Table structure for table `Registro_Privilegios` */

DROP TABLE IF EXISTS `Registro_Privilegios`;

CREATE TABLE `Registro_Privilegios` (
  `id_registro_privilegios` int(255) NOT NULL AUTO_INCREMENT,
  `privilegio` int(255) DEFAULT NULL,
  `rol` int(255) DEFAULT NULL,
  `valido_hasta` date DEFAULT NULL,
  PRIMARY KEY (`id_registro_privilegios`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `Registro_Privilegios` */

insert  into `Registro_Privilegios`(`id_registro_privilegios`,`privilegio`,`rol`,`valido_hasta`) values (1,1,6,'2100-01-01');

/*Table structure for table `Registro_Roles` */

DROP TABLE IF EXISTS `Registro_Roles`;

CREATE TABLE `Registro_Roles` (
  `id_registro_roles` int(255) NOT NULL AUTO_INCREMENT,
  `usuario` int(255) DEFAULT NULL,
  `rol` int(255) DEFAULT NULL,
  `valido_hasta` date DEFAULT NULL,
  PRIMARY KEY (`id_registro_roles`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `Registro_Roles` */

insert  into `Registro_Roles`(`id_registro_roles`,`usuario`,`rol`,`valido_hasta`) values (1,6,1,'2100-01-01'),(2,32,6,'2100-01-01');

/*Table structure for table `Rol` */

DROP TABLE IF EXISTS `Rol`;

CREATE TABLE `Rol` (
  `id_rol` int(255) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `Rol` */

insert  into `Rol`(`id_rol`,`nombre_rol`) values (1,'Alumno'),(2,'Docente'),(3,'Coordinacion Trabajo de Graduación'),(4,'Coordinación de Horas Sociales'),(5,'Coordinación de PERA'),(6,'Administrador');

/*Table structure for table `asignado` */

DROP TABLE IF EXISTS `asignado`;

CREATE TABLE `asignado` (
  `id_due` char(7) NOT NULL,
  `id_docente` char(7) NOT NULL,
  `id_proceso` char(7) NOT NULL,
  `id_cargo` char(7) NOT NULL,
  `correlativo_tutor_ss` int(11) NOT NULL,
  PRIMARY KEY (`id_due`,`id_docente`,`id_proceso`,`id_cargo`,`correlativo_tutor_ss`),
  KEY `fk_asignado2` (`id_docente`),
  CONSTRAINT `fk_asignado2` FOREIGN KEY (`id_docente`) REFERENCES `gen_docente` (`id_docente`),
  CONSTRAINT `fk_asignado` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `asignado` */

/*Table structure for table `cat_estado` */

DROP TABLE IF EXISTS `cat_estado`;

CREATE TABLE `cat_estado` (
  `id_estado` char(7) NOT NULL,
  `id_proceso` char(7) NOT NULL,
  `estado` char(2) DEFAULT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_estado`),
  KEY `fk_corresponde` (`id_proceso`),
  CONSTRAINT `fk_corresponde` FOREIGN KEY (`id_proceso`) REFERENCES `cat_proceso` (`id_proceso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cat_estado` */

/*Table structure for table `cat_parametro_general` */

DROP TABLE IF EXISTS `cat_parametro_general`;

CREATE TABLE `cat_parametro_general` (
  `id_parametro` char(7) NOT NULL,
  `parametro` varchar(20) DEFAULT NULL,
  `descripcion` longtext,
  `valor` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id_parametro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cat_parametro_general` */

/*Table structure for table `cat_proceso` */

DROP TABLE IF EXISTS `cat_proceso`;

CREATE TABLE `cat_proceso` (
  `id_proceso` char(7) NOT NULL,
  `proceso` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_proceso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cat_proceso` */

/*Table structure for table `conforma` */

DROP TABLE IF EXISTS `conforma`;

CREATE TABLE `conforma` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tg` int(11) NOT NULL,
  `id_due` char(7) NOT NULL,
  PRIMARY KEY (`id_equipo_tg`,`anio_tg`,`id_due`),
  KEY `fk_conforma2` (`id_due`),
  CONSTRAINT `fk_conforma2` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`),
  CONSTRAINT `fk_conforma` FOREIGN KEY (`id_equipo_tg`, `anio_tg`) REFERENCES `pdg_equipo_tg` (`id_equipo_tg`, `anio_tg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `conforma` */

/*Table structure for table `corresponde_menu_usuario` */

DROP TABLE IF EXISTS `corresponde_menu_usuario`;

CREATE TABLE `corresponde_menu_usuario` (
  `id_menu` char(7) NOT NULL,
  `id_usuario` char(7) NOT NULL,
  `padre_menu` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_menu`,`id_usuario`),
  KEY `fk_corresponde2` (`id_usuario`),
  CONSTRAINT `fk_corresponde3` FOREIGN KEY (`id_menu`) REFERENCES `gen_menu` (`id_menu`),
  CONSTRAINT `fk_corresponde2` FOREIGN KEY (`id_usuario`) REFERENCES `gen_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `corresponde_menu_usuario` */

/*Table structure for table `cuenta_con_menu_perfil` */

DROP TABLE IF EXISTS `cuenta_con_menu_perfil`;

CREATE TABLE `cuenta_con_menu_perfil` (
  `id_menu` char(7) NOT NULL,
  `id_perfil_usuario` char(7) NOT NULL,
  PRIMARY KEY (`id_menu`,`id_perfil_usuario`),
  KEY `fk_cuenta_con2` (`id_perfil_usuario`),
  CONSTRAINT `fk_cuenta_con2` FOREIGN KEY (`id_perfil_usuario`) REFERENCES `gen_perfil` (`id_perfil_usuario`),
  CONSTRAINT `fk_cuenta_con` FOREIGN KEY (`id_menu`) REFERENCES `gen_menu` (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cuenta_con_menu_perfil` */

/*Table structure for table `es` */

DROP TABLE IF EXISTS `es`;

CREATE TABLE `es` (
  `id_tipo_estudiante` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  PRIMARY KEY (`id_tipo_estudiante`,`id_due`),
  KEY `fk_es2` (`id_due`),
  CONSTRAINT `fk_es2` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`),
  CONSTRAINT `fk_es` FOREIGN KEY (`id_tipo_estudiante`) REFERENCES `gen_tipo_estudiante` (`id_tipo_estudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `es` */

/*Table structure for table `gen_cargo` */

DROP TABLE IF EXISTS `gen_cargo`;

CREATE TABLE `gen_cargo` (
  `id_cargo` char(7) NOT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_cargo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_cargo` */

/*Table structure for table `gen_cargo_administrativo` */

DROP TABLE IF EXISTS `gen_cargo_administrativo`;

CREATE TABLE `gen_cargo_administrativo` (
  `id_cargo_administrativo` char(7) NOT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_cargo_administrativo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_cargo_administrativo` */

/*Table structure for table `gen_departamento` */

DROP TABLE IF EXISTS `gen_departamento`;

CREATE TABLE `gen_departamento` (
  `id_departamento` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_departamento` */

/*Table structure for table `gen_docente` */

DROP TABLE IF EXISTS `gen_docente`;

CREATE TABLE `gen_docente` (
  `id_docente` char(7) NOT NULL,
  `id_cargo` char(7) NOT NULL,
  `id_departamento` char(7) NOT NULL,
  `id_cargo_administrativo` char(7) DEFAULT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` decimal(8,0) DEFAULT NULL,
  `celular` decimal(8,0) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_docente`),
  KEY `fk_desempenia` (`id_cargo`),
  KEY `fk_desempenia_2` (`id_cargo_administrativo`),
  KEY `fk_pertenece_5` (`id_departamento`),
  CONSTRAINT `fk_pertenece_5` FOREIGN KEY (`id_departamento`) REFERENCES `gen_departamento` (`id_departamento`),
  CONSTRAINT `fk_desempenia` FOREIGN KEY (`id_cargo`) REFERENCES `gen_cargo` (`id_cargo`),
  CONSTRAINT `fk_desempenia_2` FOREIGN KEY (`id_cargo_administrativo`) REFERENCES `gen_cargo_administrativo` (`id_cargo_administrativo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_docente` */

/*Table structure for table `gen_estudiante` */

DROP TABLE IF EXISTS `gen_estudiante`;

CREATE TABLE `gen_estudiante` (
  `id_due` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `dui` char(9) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` decimal(8,0) DEFAULT NULL,
  `celular` decimal(8,0) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `correlativo_equipo` int(11) DEFAULT NULL,
  `apertura_expediente` date DEFAULT NULL,
  `remision` tinyint(1) DEFAULT NULL,
  `fecha_remision` date DEFAULT NULL,
  PRIMARY KEY (`id_due`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_estudiante` */

/*Table structure for table `gen_materia` */

DROP TABLE IF EXISTS `gen_materia`;

CREATE TABLE `gen_materia` (
  `id_materia` char(7) NOT NULL,
  `id_docente` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_materia`),
  KEY `fk_coordina` (`id_docente`),
  CONSTRAINT `fk_coordina` FOREIGN KEY (`id_docente`) REFERENCES `gen_docente` (`id_docente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_materia` */

/*Table structure for table `gen_menu` */

DROP TABLE IF EXISTS `gen_menu`;

CREATE TABLE `gen_menu` (
  `id_menu` char(7) NOT NULL,
  `nombre_menu` varchar(100) DEFAULT NULL,
  `descrpcion_menu` varchar(250) DEFAULT NULL,
  `url_menu` varchar(500) DEFAULT NULL,
  `nivel_menu` int(11) DEFAULT NULL,
  `tipo_menu` varchar(50) DEFAULT NULL,
  `padre_menu` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_menu` */

/*Table structure for table `gen_perfil` */

DROP TABLE IF EXISTS `gen_perfil`;

CREATE TABLE `gen_perfil` (
  `id_perfil_usuario` char(7) NOT NULL,
  `nombre_perfil` varchar(100) DEFAULT NULL,
  `descripcion_perfil` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_perfil_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_perfil` */

/*Table structure for table `gen_tipo_estudiante` */

DROP TABLE IF EXISTS `gen_tipo_estudiante`;

CREATE TABLE `gen_tipo_estudiante` (
  `id_tipo_estudiante` char(7) NOT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_tipo_estudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_tipo_estudiante` */

/*Table structure for table `gen_tipo_usuario` */

DROP TABLE IF EXISTS `gen_tipo_usuario`;

CREATE TABLE `gen_tipo_usuario` (
  `id_tipo_usuario` char(7) NOT NULL,
  `nombre_tipo_usuario` varchar(50) DEFAULT NULL,
  `descripcion_tipo_usuario` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_tipo_usuario` */

/*Table structure for table `gen_usuario` */

DROP TABLE IF EXISTS `gen_usuario`;

CREATE TABLE `gen_usuario` (
  `id_usuario` char(7) NOT NULL,
  `id_tipo_usuario` char(7) NOT NULL,
  `id_perfil_usuario` char(7) NOT NULL,
  `nombre_usuario` char(30) DEFAULT NULL,
  `contrasenia` varchar(30) DEFAULT NULL,
  `contrasenia_temporal` varchar(30) DEFAULT NULL,
  `fecha_creacion_usuario` datetime DEFAULT NULL,
  `correo_usuario` varchar(50) DEFAULT NULL,
  `estado_usuario` char(2) DEFAULT NULL,
  `ultima_conexion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_concierne` (`id_perfil_usuario`),
  KEY `fk_se_asocia` (`id_tipo_usuario`),
  CONSTRAINT `fk_se_asocia` FOREIGN KEY (`id_tipo_usuario`) REFERENCES `gen_tipo_usuario` (`id_tipo_usuario`),
  CONSTRAINT `fk_concierne` FOREIGN KEY (`id_perfil_usuario`) REFERENCES `gen_perfil` (`id_perfil_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_usuario` */

/*Table structure for table `pdg_bitacora_control` */

DROP TABLE IF EXISTS `pdg_bitacora_control`;

CREATE TABLE `pdg_bitacora_control` (
  `id_bitacora` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `tematica_tratar` longtext,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `hora_inicio_docente` time DEFAULT NULL,
  `hora_fin_docente` time DEFAULT NULL,
  `hora_inicio_alumno_1` time DEFAULT NULL,
  `hora_fin_alumno_1` time DEFAULT NULL,
  `hora_inicio_alumno_2` time DEFAULT NULL,
  `hora_fin_alumno_2` time DEFAULT NULL,
  `hora_inicio_alumno_3` time DEFAULT NULL,
  `hora_fin_alumno_3` time DEFAULT NULL,
  `hora_inicio_alumno_4` time DEFAULT NULL,
  `hora_fin_alumno_4` time DEFAULT NULL,
  `hora_inicio_alumno_5` time DEFAULT NULL,
  `hora_fin_alumno_5` time DEFAULT NULL,
  PRIMARY KEY (`id_bitacora`),
  KEY `fk_llena` (`id_detalle_pdg`),
  CONSTRAINT `fk_llena` FOREIGN KEY (`id_detalle_pdg`) REFERENCES `pdg_detalle` (`id_detalle_pdg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_bitacora_control` */

/*Table structure for table `pdg_criterio` */

DROP TABLE IF EXISTS `pdg_criterio`;

CREATE TABLE `pdg_criterio` (
  `id_criterio` char(7) NOT NULL,
  `id_deta_nota` char(7) NOT NULL,
  `id_porcen_consolidado` char(7) NOT NULL,
  `criterio` varchar(100) DEFAULT NULL,
  `porcen_individual` decimal(3,2) DEFAULT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_criterio`),
  KEY `fk_aplica` (`id_deta_nota`),
  KEY `fk_pertenece_3` (`id_porcen_consolidado`),
  CONSTRAINT `fk_pertenece_3` FOREIGN KEY (`id_porcen_consolidado`) REFERENCES `pdg_porcentaje_consolidado` (`id_porcen_consolidado`),
  CONSTRAINT `fk_aplica` FOREIGN KEY (`id_deta_nota`) REFERENCES `pdg_deta_nota` (`id_deta_nota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_criterio` */

/*Table structure for table `pdg_deta_nota` */

DROP TABLE IF EXISTS `pdg_deta_nota`;

CREATE TABLE `pdg_deta_nota` (
  `id_deta_nota` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `nota` decimal(4,2) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  PRIMARY KEY (`id_deta_nota`),
  KEY `fk_tiene_2` (`id_due`),
  CONSTRAINT `fk_tiene_2` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_deta_nota` */

/*Table structure for table `pdg_detalle` */

DROP TABLE IF EXISTS `pdg_detalle`;

CREATE TABLE `pdg_detalle` (
  `id_detalle_pdg` char(7) NOT NULL,
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tg` int(11) NOT NULL,
  `anio` int(11) DEFAULT NULL,
  `vb_perfil_asesor` tinyint(1) DEFAULT NULL,
  `vb_perfil_junta` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproyecto` tinyint(1) DEFAULT NULL,
  `fecha_eva_anteproyecto` date DEFAULT NULL,
  `fecha_eva_etapa1` date DEFAULT NULL,
  `fecha_eva_etapa2` date DEFAULT NULL,
  `fecha_eva_publica` date DEFAULT NULL,
  `recolector_nota` tinyint(1) DEFAULT NULL,
  `remision_ejemplar` tinyint(1) DEFAULT NULL,
  `ratificacion_nota` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_pdg`),
  KEY `fk_posee_4` (`id_equipo_tg`,`anio_tg`),
  CONSTRAINT `fk_posee_4` FOREIGN KEY (`id_equipo_tg`, `anio_tg`) REFERENCES `pdg_equipo_tg` (`id_equipo_tg`, `anio_tg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_detalle` */

/*Table structure for table `pdg_documento` */

DROP TABLE IF EXISTS `pdg_documento`;

CREATE TABLE `pdg_documento` (
  `id_documento_pdg` char(7) NOT NULL,
  `id_tipo_documento_pdg` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `ruta` longtext,
  `extension` char(4) DEFAULT NULL,
  `obser_asesor` longtext,
  `obser_tribu_1` longtext,
  `obser_tribu_2` longtext,
  PRIMARY KEY (`id_documento_pdg`),
  KEY `fk_anexa_2` (`id_detalle_pdg`),
  KEY `fk_pertenece_4` (`id_tipo_documento_pdg`),
  CONSTRAINT `fk_pertenece_4` FOREIGN KEY (`id_tipo_documento_pdg`) REFERENCES `pdg_tipo_documento` (`id_tipo_documento_pdg`),
  CONSTRAINT `fk_anexa_2` FOREIGN KEY (`id_detalle_pdg`) REFERENCES `pdg_detalle` (`id_detalle_pdg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_documento` */

/*Table structure for table `pdg_equipo_tg` */

DROP TABLE IF EXISTS `pdg_equipo_tg`;

CREATE TABLE `pdg_equipo_tg` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tg` int(11) NOT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `sigla` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_equipo_tg`,`anio_tg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_equipo_tg` */

insert  into `pdg_equipo_tg`(`id_equipo_tg`,`anio_tg`,`tema`,`sigla`) values ('1',2016,'tema1','t1'),('2',2016,'tema2','t2');

/*Table structure for table `pdg_etapa` */

DROP TABLE IF EXISTS `pdg_etapa`;

CREATE TABLE `pdg_etapa` (
  `id_etapa` char(7) NOT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_etapa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_etapa` */

/*Table structure for table `pdg_perfil` */

DROP TABLE IF EXISTS `pdg_perfil`;

CREATE TABLE `pdg_perfil` (
  `id_perfil` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `objetivo_general` longtext,
  `objetivo_especifico` longtext,
  `descripcion` longtext,
  PRIMARY KEY (`id_perfil`),
  KEY `fk_se_ingresa` (`id_detalle_pdg`),
  CONSTRAINT `fk_se_ingresa` FOREIGN KEY (`id_detalle_pdg`) REFERENCES `pdg_detalle` (`id_detalle_pdg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_perfil` */

/*Table structure for table `pdg_porcentaje_consolidado` */

DROP TABLE IF EXISTS `pdg_porcentaje_consolidado`;

CREATE TABLE `pdg_porcentaje_consolidado` (
  `id_porcen_consolidado` char(7) NOT NULL,
  `id_etapa` char(7) NOT NULL,
  `porcentaje_consolidado` decimal(3,2) DEFAULT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_porcen_consolidado`),
  KEY `fk_esta_definido` (`id_etapa`),
  CONSTRAINT `fk_esta_definido` FOREIGN KEY (`id_etapa`) REFERENCES `pdg_etapa` (`id_etapa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_porcentaje_consolidado` */

/*Table structure for table `pdg_solicitud_academica` */

DROP TABLE IF EXISTS `pdg_solicitud_academica`;

CREATE TABLE `pdg_solicitud_academica` (
  `id_solicitud_academica` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `tipo_solicitud` varchar(25) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `fecha_solicitud` date DEFAULT NULL,
  `justificacion` longtext,
  `acuerdo_junta` int(11) DEFAULT NULL,
  `nombre_actual` longtext,
  `caso_especial` varchar(100) DEFAULT NULL,
  `fecha_ini_prorroga` date DEFAULT NULL,
  `fecha_fin_prorroga` date DEFAULT NULL,
  `nombre_propuesto` longtext,
  `eva_actual` longtext,
  `eva_antes_prorroga` longtext,
  `cantidad_evaluacion_actual` int(11) DEFAULT NULL,
  `duracion` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id_solicitud_academica`),
  KEY `fk_solicita` (`id_detalle_pdg`),
  CONSTRAINT `fk_solicita` FOREIGN KEY (`id_detalle_pdg`) REFERENCES `pdg_detalle` (`id_detalle_pdg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_solicitud_academica` */

/*Table structure for table `pdg_tipo_documento` */

DROP TABLE IF EXISTS `pdg_tipo_documento`;

CREATE TABLE `pdg_tipo_documento` (
  `id_tipo_documento_pdg` char(7) NOT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_tipo_documento_pdg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pdg_tipo_documento` */

/*Table structure for table `per_detalle` */

DROP TABLE IF EXISTS `per_detalle`;

CREATE TABLE `per_detalle` (
  `id_detalle_pera` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `area_deficitaria` varchar(50) DEFAULT NULL,
  `uv` int(11) DEFAULT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_pera`),
  KEY `fk_posee` (`id_due`),
  CONSTRAINT `fk_posee` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `per_detalle` */

/*Table structure for table `per_evaluacion` */

DROP TABLE IF EXISTS `per_evaluacion`;

CREATE TABLE `per_evaluacion` (
  `id_evaluacion` char(7) NOT NULL,
  `id_tipo_pera` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `descripcion` longtext,
  `porcentaje` decimal(3,2) DEFAULT NULL,
  `nota` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`id_evaluacion`),
  KEY `fk_tiene` (`id_tipo_pera`),
  CONSTRAINT `fk_tiene` FOREIGN KEY (`id_tipo_pera`) REFERENCES `per_tipo` (`id_tipo_pera`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `per_evaluacion` */

/*Table structure for table `per_tipo` */

DROP TABLE IF EXISTS `per_tipo`;

CREATE TABLE `per_tipo` (
  `id_tipo_pera` char(7) NOT NULL,
  `id_detalle_pera` char(7) NOT NULL,
  `tipo` varchar(25) DEFAULT NULL,
  `uv` int(11) DEFAULT NULL,
  `duracion` decimal(5,2) DEFAULT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_tipo_pera`),
  KEY `fk_contiene` (`id_detalle_pera`),
  CONSTRAINT `fk_contiene` FOREIGN KEY (`id_detalle_pera`) REFERENCES `per_detalle` (`id_detalle_pera`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `per_tipo` */

/*Table structure for table `pss_contacto` */

DROP TABLE IF EXISTS `pss_contacto`;

CREATE TABLE `pss_contacto` (
  `id_contacto` char(7) NOT NULL,
  `id_institucion` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `descripcion_cargo` varchar(100) DEFAULT NULL,
  `celular` decimal(8,0) DEFAULT NULL,
  `telefono` decimal(8,0) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_contacto`),
  KEY `fk_representa` (`id_institucion`),
  CONSTRAINT `fk_representa` FOREIGN KEY (`id_institucion`) REFERENCES `pss_institucion` (`id_institucion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_contacto` */

/*Table structure for table `pss_detalle_expediente` */

DROP TABLE IF EXISTS `pss_detalle_expediente`;

CREATE TABLE `pss_detalle_expediente` (
  `id_detalle_expediente` char(7) NOT NULL,
  `id_servicio_social` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `estado` char(2) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `oficializacion` tinyint(1) DEFAULT NULL,
  `carta_finalizacion` tinyint(1) DEFAULT NULL,
  `hora_asignada` int(11) DEFAULT NULL,
  `costo_hora` float DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `cierre_modalidad` tinyint(1) DEFAULT NULL,
  `observacion` longtext,
  PRIMARY KEY (`id_detalle_expediente`),
  KEY `fk_contiene_2` (`id_servicio_social`),
  KEY `fk_posee_3` (`id_due`),
  CONSTRAINT `fk_posee_3` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`),
  CONSTRAINT `fk_contiene_2` FOREIGN KEY (`id_servicio_social`) REFERENCES `pss_servicio_social` (`id_servicio_social`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_detalle_expediente` */

/*Table structure for table `pss_documento` */

DROP TABLE IF EXISTS `pss_documento`;

CREATE TABLE `pss_documento` (
  `id_documento_pss` char(7) NOT NULL,
  `id_detalle_expediente` char(7) NOT NULL,
  `id_tipo_documento_pss` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `ruta` longtext,
  `extension` char(4) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `obser_asesor` longtext,
  PRIMARY KEY (`id_documento_pss`),
  KEY `fk_anexa` (`id_detalle_expediente`),
  KEY `fk_pertenece_2` (`id_tipo_documento_pss`),
  CONSTRAINT `fk_pertenece_2` FOREIGN KEY (`id_tipo_documento_pss`) REFERENCES `pss_tipo_documento` (`id_tipo_documento_pss`),
  CONSTRAINT `fk_anexa` FOREIGN KEY (`id_detalle_expediente`) REFERENCES `pss_detalle_expediente` (`id_detalle_expediente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_documento` */

/*Table structure for table `pss_institucion` */

DROP TABLE IF EXISTS `pss_institucion`;

CREATE TABLE `pss_institucion` (
  `id_institucion` char(7) NOT NULL,
  `id_rubro` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `tipo` varchar(25) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_institucion`),
  KEY `fk_pertenece` (`id_rubro`),
  CONSTRAINT `fk_pertenece` FOREIGN KEY (`id_rubro`) REFERENCES `pss_rubro` (`id_rubro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_institucion` */

/*Table structure for table `pss_modalidad` */

DROP TABLE IF EXISTS `pss_modalidad`;

CREATE TABLE `pss_modalidad` (
  `id_modalidad` char(7) NOT NULL,
  `modalidad` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_modalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_modalidad` */

/*Table structure for table `pss_rubro` */

DROP TABLE IF EXISTS `pss_rubro`;

CREATE TABLE `pss_rubro` (
  `id_rubro` char(7) NOT NULL,
  `rubro` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_rubro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_rubro` */

/*Table structure for table `pss_servicio_social` */

DROP TABLE IF EXISTS `pss_servicio_social`;

CREATE TABLE `pss_servicio_social` (
  `id_servicio_social` char(7) NOT NULL,
  `id_contacto` char(7) NOT NULL,
  `id_modalidad` char(7) NOT NULL,
  `nombre_servicio_social` varchar(500) DEFAULT NULL,
  `cantidad_estudiante` int(11) DEFAULT NULL,
  `objetivo` varchar(500) DEFAULT NULL,
  `importancia` varchar(500) DEFAULT NULL,
  `presupuesto` decimal(8,2) DEFAULT NULL,
  `logro` varchar(500) DEFAULT NULL,
  `localidad_proyecto` varchar(100) DEFAULT NULL,
  `beneficiario_directo` int(11) DEFAULT NULL,
  `beneficiario_indirecto` int(11) DEFAULT NULL,
  `descripcion` longtext,
  `nombre_contacto_ss` varchar(100) DEFAULT NULL,
  `email_contacto_ss` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_servicio_social`),
  KEY `fk_alimenta` (`id_contacto`),
  KEY `fk_posee_2` (`id_modalidad`),
  CONSTRAINT `fk_posee_2` FOREIGN KEY (`id_modalidad`) REFERENCES `pss_modalidad` (`id_modalidad`),
  CONSTRAINT `fk_alimenta` FOREIGN KEY (`id_contacto`) REFERENCES `pss_contacto` (`id_contacto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_servicio_social` */

/*Table structure for table `pss_tipo_documento` */

DROP TABLE IF EXISTS `pss_tipo_documento`;

CREATE TABLE `pss_tipo_documento` (
  `id_tipo_documento_pss` char(7) NOT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_tipo_documento_pss`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_tipo_documento` */

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `uid` int(255) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

/*Data for the table `usuario` */

insert  into `usuario`(`uid`,`password`,`login`,`activo`) values (3,'123','EDU',1),(4,'2255','edu',1),(5,'202cb962ac59075b964b07152d234b70','eduardo',1),(6,'e10adc3949ba59abbe56e057f20f883e','eduardof',1),(7,'4c56ff4ce4aaf9573aa5dff913df997a','jose',1),(8,'1500f56fa1ed99f398aea8989ad46850','meni',1),(9,'099b3b060154898840f0ebdfb46ec78f','eeeee',1),(10,'698d51a19d8a121ce581499d7b701668','eeeee1',1),(11,'989b43a957e69a818ad20d25b9f60e2e','1111111',1),(12,'aced32c0e8d01d370c89fabc02186126','1111111e',1),(13,'81dc9bdb52d04dc20036dbd8313ed055','1234',1),(14,'d25f5c06d44b9b5d0aee477ebeaa9c08','12345',1),(15,'4297f44b13955235245b2497399d7a93','3333',1),(16,'7f6ffaa6bb0b408017b62254211691b5','morfeo',1),(17,'d41d8cd98f00b204e9800998ecf8427e','',1),(18,'5b17a56f1832aa6257115fb93a9e9ef9','rmoran',1),(19,'10b76bb3a35138e092f8804560b6b33c','prueba2',1),(20,'b4be1c568a6dc02dcaf2849852bdb13e','prueba3',1),(21,'4b71fb775271b77b861f06df858c6c64','jeduardofg',1),(22,'dccac6c2a05156cf3279028f88fafe74','jedu',1),(23,'e3bdecb759e05bfc23a166edec72f0f1','rhenriquez',1),(24,'d2f2297d6e829cd3493aa7de4416a18f','eee',1),(25,'d2f2297d6e829cd3493aa7de4416a18f','eee',1),(26,'fa246d0262c3925617b0c72bb20eeb1d','EDU',1),(27,'fff98a98eefc51d32d100307bfce83b2','nuevoRmoran',1),(28,'202cb962ac59075b964b07152d234b70','nuevo2',1),(29,'d41d8cd98f00b204e9800998ecf8427e','nuevo',1),(30,'202cb962ac59075b964b07152d234b70','nuevo3',1),(31,'81dc9bdb52d04dc20036dbd8313ed055','itsmenia',1),(32,'2662080fb289bcaafd930f1112df9529','edward',1);

/* Procedure structure for procedure `Consultar_Login_Usuario` */

/*!50003 DROP PROCEDURE IF EXISTS  `Consultar_Login_Usuario` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `Consultar_Login_Usuario`(usuario NVARCHAR(255))
BEGIN
select * from usuario where (login = usuario or usuario = '0');
end */$$
DELIMITER ;

/* Procedure structure for procedure `Consultar_Privilegio` */

/*!50003 DROP PROCEDURE IF EXISTS  `Consultar_Privilegio` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `Consultar_Privilegio`(usuario NVARCHAR(255), privilegio NVARCHAR(255))
BEGIN
	SELECT 
	p.nombre_privilegio,u.login 
FROM 
	Privilegios p
	JOIN
		Registro_Privilegios rp
	ON
		rp.privilegio = p.id_privilegio	
		AND rp.valido_hasta >= '2100-01-01'
	JOIN
		Rol r
	ON
		r.id_rol = rp.rol	
	JOIN
		Registro_Roles rr
	ON
		rr.rol = r.id_rol	
		AND rr.valido_hasta >= '2100-01-01'
	JOIN
		usuario u
	ON
		u.uid = rr.usuario
		AND u.activo = 1
WHERE u.login = usuario and p.nombre_privilegio = privilegio;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `Crear_Usuario` */

/*!50003 DROP PROCEDURE IF EXISTS  `Crear_Usuario` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `Crear_Usuario`(usuario nvarchar(255), clave nvarchar(255))
BEGIN
  insert into usuario(login,password,activo)
  values (usuario,clave,1);
END */$$
DELIMITER ;

/* Procedure structure for procedure `Login` */

/*!50003 DROP PROCEDURE IF EXISTS  `Login` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `Login`(usuario NVARCHAR(255), clave NVARCHAR(255))
BEGIN
	select login, activo from usuario where (login = usuario and password = clave and activo = 1);
    END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
