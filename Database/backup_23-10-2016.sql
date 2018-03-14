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
  `url_privilegio` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_privilegio`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `Privilegios` */

insert  into `Privilegios`(`id_privilegio`,`nombre_privilegio`,`url_privilegio`) values (1,'Usuario','Administrador/Usuario'),(3,'Home','Login');

/*Table structure for table `Registro_Privilegios` */

DROP TABLE IF EXISTS `Registro_Privilegios`;

CREATE TABLE `Registro_Privilegios` (
  `id_registro_privilegios` int(255) NOT NULL AUTO_INCREMENT,
  `privilegio` int(255) DEFAULT NULL,
  `rol` int(255) DEFAULT NULL,
  `valido_hasta` date DEFAULT NULL,
  PRIMARY KEY (`id_registro_privilegios`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `Registro_Privilegios` */

insert  into `Registro_Privilegios`(`id_registro_privilegios`,`privilegio`,`rol`,`valido_hasta`) values (1,1,6,'2100-01-01'),(2,3,6,'2100-01-01');

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

insert  into `asignado`(`id_due`,`id_docente`,`id_proceso`,`id_cargo`,`correlativo_tutor_ss`) values ('1','1','PER','3',0),('1','dd12345','PER','3',0),('1234567','dd12345','PER','3',0),('2','dd12345','PER','3',0),('3','dd12345','PER','3',0),('aa12345','dd12345','PER','3',0),('3','dd54321','PER','3',0);

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

insert  into `cat_proceso`(`id_proceso`,`proceso`) values ('PDG','Proceso de Graduación'),('PER',NULL),('PSS','Proceso de Servicio Social');

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

insert  into `gen_cargo`(`id_cargo`,`descripcion`) values ('1',NULL);

/*Table structure for table `gen_cargo_administrativo` */

DROP TABLE IF EXISTS `gen_cargo_administrativo`;

CREATE TABLE `gen_cargo_administrativo` (
  `id_cargo_administrativo` char(7) NOT NULL,
  `descripcion` longtext,
  PRIMARY KEY (`id_cargo_administrativo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_cargo_administrativo` */

insert  into `gen_cargo_administrativo`(`id_cargo_administrativo`,`descripcion`) values ('1',NULL);

/*Table structure for table `gen_departamento` */

DROP TABLE IF EXISTS `gen_departamento`;

CREATE TABLE `gen_departamento` (
  `id_departamento` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `gen_departamento` */

insert  into `gen_departamento`(`id_departamento`,`nombre`) values ('1',NULL);

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

insert  into `gen_docente`(`id_docente`,`id_cargo`,`id_departamento`,`id_cargo_administrativo`,`nombre`,`apellido`,`direccion`,`telefono`,`celular`,`email`) values ('1','1','1','1',NULL,NULL,NULL,NULL,NULL,NULL),('dd12345','1','1','1',NULL,NULL,NULL,NULL,NULL,NULL),('dd54321','1','1','1',NULL,NULL,NULL,NULL,NULL,NULL);

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

insert  into `gen_estudiante`(`id_due`,`nombre`,`apellido`,`dui`,`direccion`,`telefono`,`celular`,`email`,`fecha_nac`,`correlativo_equipo`,`apertura_expediente`,`remision`,`fecha_remision`) values ('1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),('1234567',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),('2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),('3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),('7654321',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),('aa12345','Juan','Perez',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),('pp10251',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

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

insert  into `pdg_detalle`(`id_detalle_pdg`,`id_equipo_tg`,`anio_tg`,`anio`,`vb_perfil_asesor`,`vb_perfil_junta`,`entrega_copia_anteproyecto`,`fecha_eva_anteproyecto`,`fecha_eva_etapa1`,`fecha_eva_etapa2`,`fecha_eva_publica`,`recolector_nota`,`remision_ejemplar`,`ratificacion_nota`) values ('1','1',2016,2016,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

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

insert  into `pdg_perfil`(`id_perfil`,`id_detalle_pdg`,`ciclo`,`anio`,`objetivo_general`,`objetivo_especifico`,`descripcion`) values ('1','1',2,2015,'prueba octubre','prueba octubre','prueba octubre'),('11','1',1,2016,'nnnnnnnn','nnnnnnnnn','nnn'),('12','1',1,2016,'DIOS es AMOR','DIOS es AMOR','DIOS es AMOR'),('13','1',1,2016,'ñññññññ','ñññññ','ñññññ'),('14','1',2,2015,'gh','gh','gh'),('15','1',1,2016,'yu','yu','yu'),('16','1',1,2016,'asas','ass','asas'),('17','1',1,2016,'sdfsdsd','sdfsdf','fsdfse'),('18','1',1,2015,'dfds','sdfsd','sdfds'),('19','1',1,2015,'hola','hola','hola'),('2','1',2,2015,'prueba octubre','prueba octubre','prueba octubre'),('20','1',1,2015,'xc','xc','xc'),('21','1',1,2016,'12','12','12'),('23','1',1,2016,'23','23','23'),('24','1',1,2016,'qwe','qwe','qwe'),('25','1',1,2016,'ty','ty','ty'),('27','1',1,2016,'vb','vb','vb'),('28','1',1,2016,'324342','324342','3232432'),('29','1',1,2016,'1221','1221','1122'),('3','1',1,2016,'pruebaCOT','pruebaCOT','pruebaCOT'),('30','1',1,2016,'gh','gh','gh'),('31','1',1,2016,'fv','fv','fv'),('32','1',1,2016,'pñ','pñ','pñ'),('33','1',1,2016,'gh','gh','gh'),('34','1',1,2016,'llñlñlñ','lñlñlñ','lññllñ'),('35','1',1,2016,'bn','bn','bn'),('37','1',1,2015,'as','asas','asas'),('38','1',1,2016,'daf','asd','asd'),('39','1',1,2016,'sadsad','sadasdasd','asdasd'),('4','1',1,2016,'pruebaCOT','pruebaCOT','pruebaCOT'),('40','1',1,2016,'546','456','554'),('41','1',1,2015,'asd','sadsad','asdsad'),('42','1',1,2016,'vvbvb','vvbvb','fsdgdsg'),('43','1',2,2015,'PRUEBA BUENA','PRUEBA BUENA','PRUEBA BUENA'),('5','1',2,2016,'alfaromeo','alfaromeo','alfaromeo'),('6','1',1,2016,'november','november','november'),('7','1',2,2015,'asas','as','ass'),('8','1',1,2016,'sssssssssssssssssssss','ssssssssssssssss','ssssssssssssssss'),('9','1',1,2016,'sssssssssssssssssssss','ssssssssssssssss','ssssssssssssssss');

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

insert  into `per_detalle`(`id_detalle_pera`,`id_due`,`area_deficitaria`,`uv`,`ciclo`,`anio`) values ('1','1','Comunicaciones',NULL,NULL,NULL),('2','2','Programación',NULL,NULL,NULL),('3','3','Administracion',NULL,NULL,NULL),('4','aa12345','Arquitectura de Computadoras',NULL,NULL,NULL),('5','1234567','Desarrollo de Sistemas',NULL,NULL,NULL),('6','pp10251','Analisis de Sistemas',NULL,NULL,NULL);

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
  `descripcion` longtext,
  `inicio` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  PRIMARY KEY (`id_tipo_pera`),
  KEY `fk_contiene` (`id_detalle_pera`),
  CONSTRAINT `fk_contiene` FOREIGN KEY (`id_detalle_pera`) REFERENCES `per_detalle` (`id_detalle_pera`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `per_tipo` */

/*Table structure for table `pss_contacto` */

DROP TABLE IF EXISTS `pss_contacto`;

CREATE TABLE `pss_contacto` (
  `id_contacto` char(10) NOT NULL,
  `id_institucion` char(17) NOT NULL,
  `nombre` varchar(25) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `descripcion_cargo` varchar(100) DEFAULT NULL,
  `celular` varchar(9) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_contacto`),
  KEY `fk_representa` (`id_institucion`),
  CONSTRAINT `fk_representa` FOREIGN KEY (`id_institucion`) REFERENCES `pss_institucion` (`id_institucion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_contacto` */

insert  into `pss_contacto`(`id_contacto`,`id_institucion`,`nombre`,`apellido`,`descripcion_cargo`,`celular`,`telefono`,`email`) values ('01117152-','0614-190584-107-5','Emerson','Domínguez ','Programador','7165-9231','71659231','emersondominguez@outlook.com');

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
  `id_institucion` varchar(17) NOT NULL,
  `id_rubro` varchar(3) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `tipo` varchar(3) DEFAULT NULL,
  `estado` char(3) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_institucion`),
  KEY `fk_pertenece` (`id_rubro`),
  CONSTRAINT `fk_pertenece` FOREIGN KEY (`id_rubro`) REFERENCES `pss_rubro` (`id_rubro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_institucion` */

insert  into `pss_institucion`(`id_institucion`,`id_rubro`,`nombre`,`tipo`,`estado`,`direccion`,`telefono`,`email`) values ('0614-190584-107-1','FIN','PRUEBA2016','PUB','APR','Colonia Roma #345, San Salvador','26665555','hhh@outlook.com'),('0614-190584-107-2','EXT','PRUEBA','PUB','APR','Colonia Roma #345, San Salvador','26665555','hhh@outlook.com'),('0614-190584-107-3','EXT','PRUEBA','PUB','APR','Colonia Roma #345, San Salvador','26665555','hhh@outlook.com'),('0614-190584-107-5','COM','COMPUTECNIC','SI',NULL,'Colonia Roma #345, San Salvador','2255-9633','computecnic@compu.com.sv'),('0614-190584-107-6','SER','TECNOSERVICE','SI',NULL,'Colonia Roma #345, San Salvador','2251-6532','rhh@tecnoservice.com.sv'),('0614-190584-107-7','COM','ttt','PUB','APR','Reparto los santos #','71659231','emersondominguez@outlook.com'),('0614-190584-107-8','EXT','AGROSERVICE','PUB','APR','Reparto los santos #2','71659231','agroservice@outlook.com'),('0614-190584-107-9','COM','DARK','PUB','APR','SONSONATE','6522222','dark@outlook.com');

/*Table structure for table `pss_modalidad` */

DROP TABLE IF EXISTS `pss_modalidad`;

CREATE TABLE `pss_modalidad` (
  `id_modalidad` char(7) NOT NULL,
  `modalidad` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_modalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_modalidad` */

insert  into `pss_modalidad`(`id_modalidad`,`modalidad`) values ('AYU01','AYUDANTIA'),('CPR01','CURSO PROPEDEUTICO'),('PAS01','PASANTIA'),('PRO01','PROYECTO');

/*Table structure for table `pss_rubro` */

DROP TABLE IF EXISTS `pss_rubro`;

CREATE TABLE `pss_rubro` (
  `id_rubro` char(3) NOT NULL,
  `rubro` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_rubro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pss_rubro` */

insert  into `pss_rubro`(`id_rubro`,`rubro`) values ('COM','COMERCIALES'),('EXT','EXTRACCION'),('FIN','FINANCIERAS'),('IND','INDUSTRIALES'),('SER','SERVICIOS');

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
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

/*Data for the table `usuario` */

insert  into `usuario`(`uid`,`password`,`login`,`activo`) values (3,'123','EDU',1),(4,'2255','edu',1),(5,'202cb962ac59075b964b07152d234b70','eduardo',1),(6,'e10adc3949ba59abbe56e057f20f883e','eduardof',1),(7,'4c56ff4ce4aaf9573aa5dff913df997a','jose',1),(8,'1500f56fa1ed99f398aea8989ad46850','meni',1),(9,'099b3b060154898840f0ebdfb46ec78f','eeeee',1),(10,'698d51a19d8a121ce581499d7b701668','eeeee1',1),(11,'989b43a957e69a818ad20d25b9f60e2e','1111111',1),(12,'aced32c0e8d01d370c89fabc02186126','1111111e',1),(13,'81dc9bdb52d04dc20036dbd8313ed055','1234',1),(14,'d25f5c06d44b9b5d0aee477ebeaa9c08','12345',1),(15,'4297f44b13955235245b2497399d7a93','3333',1),(16,'7f6ffaa6bb0b408017b62254211691b5','morfeo',1),(17,'d41d8cd98f00b204e9800998ecf8427e','',1),(18,'5b17a56f1832aa6257115fb93a9e9ef9','rmoran',1),(19,'10b76bb3a35138e092f8804560b6b33c','prueba2',1),(20,'b4be1c568a6dc02dcaf2849852bdb13e','prueba3',1),(21,'4b71fb775271b77b861f06df858c6c64','jeduardofg',1),(22,'dccac6c2a05156cf3279028f88fafe74','jedu',1),(23,'e3bdecb759e05bfc23a166edec72f0f1','rhenriquez',1),(24,'d2f2297d6e829cd3493aa7de4416a18f','eee',1),(25,'d2f2297d6e829cd3493aa7de4416a18f','eee',1),(26,'fa246d0262c3925617b0c72bb20eeb1d','EDU',1),(27,'fff98a98eefc51d32d100307bfce83b2','nuevoRmoran',1),(28,'202cb962ac59075b964b07152d234b70','nuevo2',1),(29,'d41d8cd98f00b204e9800998ecf8427e','nuevo',1),(30,'202cb962ac59075b964b07152d234b70','nuevo3',1),(31,'81dc9bdb52d04dc20036dbd8313ed055','itsmenia',1),(32,'2662080fb289bcaafd930f1112df9529','edward',1),(33,'2662080fb289bcaafd930f1112df9529','eduardofg',1),(34,'1c1d5e86e3024e82ba1568aa37c5dfeb','teclado',1),(35,'7a2c8a31c1ee7868a14f435fefcb3381','raton',1),(36,'08b5411f848a2581a41672a759c87380','monitor',1),(37,'4d186321c1a7f0f354b297e8914ab240','hola',1),(38,'98ce3010caf21876324addaf1a0f4aa2','magia',1),(39,'42bc352460e9ad5c8badc00730a73bec','cel',1),(40,'b06393e3807f6e8c547e7153966ad247','venti',1),(41,'cc26afdaee695fa32bebd743894a8bd4','lapiz',1),(42,'288404204e3d452229308317344a285d','pl',1),(43,'c03b337a13ca6d63f34eb5f5b15f1734','cualquier_cosa',1),(44,'c20ad4d76fe97759aa27a0c99bff6710','12',1),(45,'ff1ccf57e98c817df1efcd9fe44a8aeb','we',1),(46,'818f9c45cfa30eeff277ef38bcbe9910','er',1),(47,'385d04e7683a033fcc6c6654529eb7e9','yu',1);

/* Procedure structure for procedure `Consultar_Login_Usuario` */

/*!50003 DROP PROCEDURE IF EXISTS  `Consultar_Login_Usuario` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `Consultar_Login_Usuario`(usuario NVARCHAR(255))
BEGIN
select * from usuario where (login = usuario or usuario = '0');
end */$$
DELIMITER ;

/* Procedure structure for procedure `consultar_privilegio` */

/*!50003 DROP PROCEDURE IF EXISTS  `consultar_privilegio` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `consultar_privilegio`(usuario NVARCHAR(255))
BEGIN
	SELECT 
	p.nombre_privilegio,p.url_privilegio
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
WHERE u.login = 'edward' ;
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

/* Procedure structure for procedure `per_proc_asi_doc` */

/*!50003 DROP PROCEDURE IF EXISTS  `per_proc_asi_doc` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `per_proc_asi_doc`(id_due char(7), id_docente char(7))
BEGIN
	INSERT INTO asignado (id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss) 
		VALUES (id_due,id_docente,'PER',3,0);
    END */$$
DELIMITER ;

/* Procedure structure for procedure `per_proc_consultar_area_deficitaria` */

/*!50003 DROP PROCEDURE IF EXISTS  `per_proc_consultar_area_deficitaria` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `per_proc_consultar_area_deficitaria`(due char(7))
BEGIN
	SELECT area_deficitaria FROM per_detalle 
                    WHERE id_due = due;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `per_proc_insertar_def_tip` */

/*!50003 DROP PROCEDURE IF EXISTS  `per_proc_insertar_def_tip` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `per_proc_insertar_def_tip`(id_tipo_pera chAR(7), id_detalle CHAR(7), tipo VARCHAR(25),uv INT(11), descripcion LONGTEXT, inicio date, fin date)
BEGIN
    
    declare detalle_pera char(7);
    set detalle_pera = (SELECT id_detalle_pera FROM per_detalle
				WHERE id_due = id_detalle);
				
    INSERT INTO per_tipo (id_tipo_pera,id_detalle_pera,tipo,uv,descripcion,inicio,fin)
	vALUES (id_tipo_pera,detalle_pera,tipo,uv,descripcion,inicio,fin);
    END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_pss_insertar_contacto` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_pss_insertar_contacto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `sp_pss_insertar_contacto`(nit varchar(17),dui varchar(10),nombre varchar(25),apellido varchar(25),cargo varchar(100),telefono varchar(9),movil varchar(9),email varchar(50))
BEGIN
		
		INSERT INTO pss_contacto(id_contacto,id_institucion,nombre,apellido,descripcion_cargo,celular,telefono,email) VALUES(dui,nit,nombre,apellido,cargo,telefono,movil,email);
	
    END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_pss_insertar_institucion` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_pss_insertar_institucion` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`sql9132828`@`%` PROCEDURE `sp_pss_insertar_institucion`(nit VARCHAR(17),rubro VARCHAR(3),nombre VARCHAR(30),tipo VARCHAR(3),estado VARCHAR(3),direccion VARCHAR(150),telefono VARCHAR(9),email VARCHAR(50))
BEGIN
	INSERT INTO pss_institucion(id_institucion,id_rubro,nombre,tipo,estado,direccion,telefono,email) values(nit,rubro,nombre,tipo,estado,direccion,telefono,email);
    END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
