-- MySQL dump 10.13  Distrib 8.0.13, for Linux (x86_64)
--
-- Host: localhost    Database: sigpad_dev
-- ------------------------------------------------------
-- Server version	8.0.13

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `sigpad_dev`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sigpad_dev` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `sigpad_dev`;

--
-- Table structure for table `cat_car_cargo_eisi`
--

DROP TABLE IF EXISTS `cat_car_cargo_eisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_car_cargo_eisi` (
  `id_cat_car` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cargo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cat_car`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_car_cargo_eisi`
--

LOCK TABLES `cat_car_cargo_eisi` WRITE;
/*!40000 ALTER TABLE `cat_car_cargo_eisi` DISABLE KEYS */;
INSERT INTO `cat_car_cargo_eisi` VALUES (1,'Docente'),(2,'Coordinador de Cátedra'),(3,'Coordinador de Laboratorios'),(4,'Instructor'),(5,'Coordinador de Auxiliares de Cátedra'),(6,'Gerente de Informática'),(7,'Coordinador del CIDES'),(8,'Secretario de la EISI'),(9,'Director de la EISI');
/*!40000 ALTER TABLE `cat_car_cargo_eisi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_cri_eva_criterio_evaluacion`
--

DROP TABLE IF EXISTS `cat_cri_eva_criterio_evaluacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_cri_eva_criterio_evaluacion` (
  `id_cat_cri_eva` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_asp` int(11) NOT NULL,
  `nombre_cat_cri_eva` varchar(45) NOT NULL,
  `ponderacion_cat_cri_eva` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id_cat_cri_eva`),
  KEY `fk_cat_cri_eva_criterio_evaluacion_pdg_asp_aspectos_evaluat_idx` (`id_pdg_asp`),
  CONSTRAINT `fk_cat_cri_eva_criterio_evaluacion_pdg_asp_aspectos_evaluativ1` FOREIGN KEY (`id_pdg_asp`) REFERENCES `pdg_asp_aspectos_evaluativos` (`id_pdg_asp`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_cri_eva_criterio_evaluacion`
--

LOCK TABLES `cat_cri_eva_criterio_evaluacion` WRITE;
/*!40000 ALTER TABLE `cat_cri_eva_criterio_evaluacion` DISABLE KEYS */;
INSERT INTO `cat_cri_eva_criterio_evaluacion` VALUES (1,1,'Introducción',20.00),(2,1,'Objetivos',20.00),(3,1,'Justificación',40.00),(4,1,'Referencias',20.00),(5,2,'Presentación',50.00),(6,2,'Contenido',50.00),(7,3,'Casos de Uso',40.00),(8,3,'Diagrama de Clases',40.00),(9,3,'Documento',20.00),(10,4,'Diagrama Entidad Relación',50.00),(11,4,'Diagrama de Arquitectura',50.00),(12,5,'Documentación',50.00),(13,5,'Estanddar',50.00),(14,6,'Referencias',50.00),(15,6,'Contenido',20.00),(16,6,'CD\'s',30.00),(17,7,'Tiempo ',30.00),(18,7,'Presentación ',30.00),(19,7,'Contenido',40.00),(20,8,'Criterio Anteproyecto',100.00),(21,9,'Criterio Etapa I',100.00),(22,10,'Criterio Etapa II',100.00),(23,11,'Criterio Defensa Final',100.00);
/*!40000 ALTER TABLE `cat_cri_eva_criterio_evaluacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_ctg_tra_categoria_trabajo_graduacion`
--

DROP TABLE IF EXISTS `cat_ctg_tra_categoria_trabajo_graduacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_ctg_tra_categoria_trabajo_graduacion` (
  `id_cat_ctg_tra` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_ctg_tra` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cat_ctg_tra`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_ctg_tra_categoria_trabajo_graduacion`
--

LOCK TABLES `cat_ctg_tra_categoria_trabajo_graduacion` WRITE;
/*!40000 ALTER TABLE `cat_ctg_tra_categoria_trabajo_graduacion` DISABLE KEYS */;
INSERT INTO `cat_ctg_tra_categoria_trabajo_graduacion` VALUES (1,'Sistema de Informático Gerencial'),(2,'Sistema Contable'),(3,'Sitio Web Informativo'),(4,'Sistema de información geográfica'),(5,'Sistema de Seguimiento de Procesos');
/*!40000 ALTER TABLE `cat_ctg_tra_categoria_trabajo_graduacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_eta_eva_etapa_evaluativa`
--

DROP TABLE IF EXISTS `cat_eta_eva_etapa_evaluativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_eta_eva_etapa_evaluativa` (
  `id_cat_eta_eva` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_eta_eva` varchar(45) NOT NULL,
  `ponderacion_cat_eta_eva` decimal(5,2) NOT NULL,
  `tiene_defensas_cat_eta_eva` int(11) NOT NULL DEFAULT '0',
  `anio_cat_eta_eva` int(11) NOT NULL,
  `puede_observar_cat_eta_eva` int(11) NOT NULL,
  PRIMARY KEY (`id_cat_eta_eva`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_eta_eva_etapa_evaluativa`
--

LOCK TABLES `cat_eta_eva_etapa_evaluativa` WRITE;
/*!40000 ALTER TABLE `cat_eta_eva_etapa_evaluativa` DISABLE KEYS */;
INSERT INTO `cat_eta_eva_etapa_evaluativa` VALUES (10,'Anteproyecto',15.00,1,2018,1),(11,'Analisis y Diseño',25.00,1,2018,1),(12,'Codificación',50.00,1,2018,0),(13,'Defensa Final',10.00,1,2018,1),(14,'Modulo 1',25.00,1,2019,0),(15,'Modulo 2',25.00,1,2019,1),(16,'Modulo 3',25.00,1,2019,1),(17,'Modulo 4',25.00,1,2019,0),(18,'Anteproyecto',15.00,1,2018,0),(19,'Etapa I',25.00,1,2018,0),(20,'Etapa 2',35.00,1,2018,0),(21,'Defensa Final',25.00,1,2018,0);
/*!40000 ALTER TABLE `cat_eta_eva_etapa_evaluativa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_idi_idioma`
--

DROP TABLE IF EXISTS `cat_idi_idioma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_idi_idioma` (
  `id_cat_idi` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_idi` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cat_idi`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_idi_idioma`
--

LOCK TABLES `cat_idi_idioma` WRITE;
/*!40000 ALTER TABLE `cat_idi_idioma` DISABLE KEYS */;
INSERT INTO `cat_idi_idioma` VALUES (1,'Español'),(2,'Inglés'),(3,'Mandarin '),(4,'Frances');
/*!40000 ALTER TABLE `cat_idi_idioma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_mat_materia`
--

DROP TABLE IF EXISTS `cat_mat_materia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_mat_materia` (
  `id_cat_mat` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_mat` varchar(45) NOT NULL,
  `nombre_mat` varchar(100) NOT NULL,
  `es_electiva` int(11) DEFAULT NULL,
  `anio_pensum` int(11) DEFAULT NULL,
  `orden_mat` int(11) DEFAULT NULL,
  `ciclo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cat_mat`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_mat_materia`
--

LOCK TABLES `cat_mat_materia` WRITE;
/*!40000 ALTER TABLE `cat_mat_materia` DISABLE KEYS */;
INSERT INTO `cat_mat_materia` VALUES (1,'MTE115','Métodos experimentales',0,1998,NULL,'I'),(2,'FIR115','Física I',0,1998,NULL,'II'),(3,'FIR215','Física II',0,1998,NULL,'I'),(4,'FIR315','Física III',0,1998,NULL,'II'),(5,'SDU115','Sistemas Digitales',0,1998,NULL,'I'),(7,'MIP115','Microprogramación',0,1998,NULL,'I'),(8,'COS115','Comunicaciones I',0,1998,NULL,'II'),(9,'LPR115','Legislación Profesional',0,1998,NULL,'I'),(10,'CPR115','Consultoría Profesional',0,1998,NULL,'II'),(11,'MAT115','Matematicas I',0,1998,NULL,'I'),(12,'MAT215','Matematicas II',0,1998,NULL,'II'),(13,'MAT315','Matematicas III',0,1998,NULL,'I'),(14,'MAT415','Matematicas IV',0,1998,NULL,'II'),(15,'ANS115','Análisis Numérico',0,1998,NULL,'I'),(16,'ARC115','Arquitectura de Computadoras',0,1998,NULL,'II'),(46,'PDD115','Programación 3D',1,1998,NULL,NULL),(18,'SIO115','Sistemas Operativos',0,1998,NULL,'II'),(19,'RHU115','Recursos Humanos',0,1998,NULL,'I'),(20,'ACC115','Administración de Centros de Cómputo',0,1998,NULL,'II'),(21,'IAI115','Introducción a la Informática',0,1998,NULL,'I'),(22,'PRN115','Programación I',0,1998,NULL,'II'),(23,'PRN215','Programación II',0,1998,NULL,'I'),(24,'ESD115','Estructuras de Datos',0,1998,NULL,'II'),(25,'HDP115','Herramientas de Productividad',0,1998,NULL,'I'),(26,'SIC115','Sistemas Contables',0,1998,NULL,'II'),(27,'ANF115','Teoría Administrativa',0,1998,NULL,'I'),(28,'ANF115','Análisis Financiero',0,1998,NULL,'II'),(29,'BAD115','Bases de Datos',0,1998,NULL,'I'),(30,'ANF115','Administración de Proyectos Informáticos',0,1998,NULL,'II'),(31,'PSI115','Psicologia Social',0,1998,NULL,'I'),(32,'MSM115','Manejo de Software para Microcomputadoras',0,1998,NULL,'II'),(33,'PYE115','Probabilidad y Estadística',0,1998,NULL,'I'),(34,'PRN315','Programación III',0,1998,NULL,'II'),(35,'SYP115','Sistemas y Procedimientos',0,1998,NULL,'I'),(36,'IEC115','Ingenieria Económica',0,1998,NULL,'II'),(37,'DSI115','Diseño de Sistemas I',0,1998,NULL,'I'),(38,'DSI115','Diseño de Sistemas II',0,1998,NULL,'II'),(39,'SGI115','Sistemas de Información Gerencial',0,1998,NULL,'I'),(47,'POO115','Programación Orientada a Objetos',1,1998,NULL,NULL),(41,'MSM115','Historia Social y Económica de El Salvador y C.A.',0,1998,NULL,'I'),(42,'FDE115','Fundamentos de Economía',0,1998,NULL,'II'),(43,'MEP115','Métodos Probabilísticos',0,1998,NULL,'I'),(44,'MOP115','Métodos de Optimización',0,1998,NULL,'II'),(45,'TSI115','Teoría de sistemas',0,1998,NULL,'I'),(48,'ADC115','Análisis de Costos Informáticos',1,1998,NULL,NULL),(49,'AGR115','Algoritmos Gráficos',1,1998,NULL,NULL),(50,'IIN115','Introducción a la Infografía',1,1998,NULL,NULL),(51,'TPI115','Técnicas de Programación para Internet',1,1998,NULL,NULL),(52,'TDS115','Técnicas de Simulación',1,1998,NULL,NULL),(53,'TOO15','Tecnología Orientada a Objetos',1,1998,NULL,NULL),(54,'COS215','Comunicaciones II',1,1998,NULL,NULL),(55,'SIF115','Seguridad Informática',1,1998,NULL,NULL),(56,'CET115','Comercio Electrónico',1,1998,NULL,NULL),(57,'IGF115','Ingeniería de Software',1,1998,NULL,NULL),(58,'SGG115','Sistemas de Información Geográficos',1,1998,NULL,NULL),(59,'AEC115','Análisis Estadístico por Computadora',1,1998,NULL,NULL),(60,'PDM115','Programación para Dispositivos Móviles',1,1998,NULL,NULL),(61,'IBD15','Implementación de Bases de Datos',1,1998,NULL,NULL),(62,'AUS115','Auditoría de Sistemas',1,1998,NULL,NULL),(63,'PDC115','Protocólos de Comunicación',1,1998,NULL,NULL),(64,'PAM115','Introducción a la programación de Aplicaciones Móviles',1,1998,NULL,NULL),(68,'MEC115','Modelos Económicos',1,1998,NULL,NULL),(69,'IFI115','Ingenieria Financiera',1,1998,NULL,NULL),(70,'TDS115','Trabajo de Graduacion',1,1998,NULL,NULL),(71,'PRS115','Pera 1',1,1998,NULL,NULL),(72,'PRS215','Pera 2',1,1998,NULL,NULL);
/*!40000 ALTER TABLE `cat_mat_materia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_pro_proceso`
--

DROP TABLE IF EXISTS `cat_pro_proceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_pro_proceso` (
  `id_cat_pro` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_pro` varchar(45) NOT NULL,
  `descripcion_cat_pro` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cat_pro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_pro_proceso`
--

LOCK TABLES `cat_pro_proceso` WRITE;
/*!40000 ALTER TABLE `cat_pro_proceso` DISABLE KEYS */;
/*!40000 ALTER TABLE `cat_pro_proceso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_ski_skill`
--

DROP TABLE IF EXISTS `cat_ski_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_ski_skill` (
  `id_cat_ski` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_ski` varchar(45) DEFAULT NULL,
  `id_tpo_ski` int(11) NOT NULL,
  PRIMARY KEY (`id_cat_ski`),
  KEY `fk_cat_ski_skill_cat_tpo_ski_tipo_skill1_idx` (`id_tpo_ski`),
  CONSTRAINT `fk_cat_ski_skill_cat_tpo_ski_tipo_skill1` FOREIGN KEY (`id_tpo_ski`) REFERENCES `cat_tpo_ski_tipo_skill` (`id_tpo_ski`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_ski_skill`
--

LOCK TABLES `cat_ski_skill` WRITE;
/*!40000 ALTER TABLE `cat_ski_skill` DISABLE KEYS */;
INSERT INTO `cat_ski_skill` VALUES (1,'MySQL',1),(2,'SQL Server ',1),(3,'PLSQL',1),(4,'Oracle',1),(5,'Java EE',2),(6,'C#',2),(7,'Chatbot',5),(8,'IoT',3),(9,'Datawarehouse',3),(10,'Power BI',3),(11,'Tableu',3),(12,'Data Factory',3),(13,'ETL',3),(14,'Microservicios',4),(15,'Domine Driven Design',4),(16,'MVC',4),(17,'API ',4),(18,'Scrum',6),(19,'Kanbam',6),(20,'XP',6),(21,'Agile',6),(22,'RUP',6);
/*!40000 ALTER TABLE `cat_ski_skill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_sta_estado`
--

DROP TABLE IF EXISTS `cat_sta_estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_sta_estado` (
  `id_cat_sta` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_sta` varchar(45) NOT NULL,
  `descripcion_cat_sta` varchar(250) NOT NULL,
  `id_cat_tpo_sta` int(11) NOT NULL,
  PRIMARY KEY (`id_cat_sta`),
  KEY `fk_cat_sta_estado_cat_tpo_sta_tipo_estado1_idx` (`id_cat_tpo_sta`),
  CONSTRAINT `fk_cat_sta_estado_cat_tpo_sta_tipo_estado1` FOREIGN KEY (`id_cat_tpo_sta`) REFERENCES `cat_tpo_sta_tipo_estado` (`id_cat_tpo_sta`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_sta_estado`
--

LOCK TABLES `cat_sta_estado` WRITE;
/*!40000 ALTER TABLE `cat_sta_estado` DISABLE KEYS */;
INSERT INTO `cat_sta_estado` VALUES (1,'Creación de propuesta','Propuesta tentativa creada, pendiente aprobación de integrantes.',1),(2,'Listo para envío','Listo para enviar a aprobación de docente director.',1),(3,'Aprobado','Aprobado con asignación de código de grupo.',1),(4,'Desintegrado','Desintegrar un grupo, liberando integrantes.',1),(5,'Confirmación en espera','Esperando aprobación de integrante para conformar grupo.',1),(6,'Aceptado','Integrante acepta conformación de grupo.',1),(7,'Enviado para aprobación','Enviado a aprobación de docente director.',1),(8,'Enviado','Preperfil: Creación y envío del Preperfil para Aprobación',2),(9,'Aprobado por Asesor','Preperfil: Cuando el Asesor de el Visto bueno',2),(10,'Aproba por Coordinador','Preperfil: Cuando el Coordinador Aprueba el Preperfil',2),(11,'Rechazado por Asesor','Preperfil: Rechazado por Asesor',2),(12,'Rechazado por Coordinador','Preperfil:Rechazado por Coordinador',2),(13,'Enviado','Etapa enviada para Aprobación',3),(14,'Aprobado','Etapa Aprobada',3),(15,'Rechazado','Etapa Rechazada',3),(16,'Con Observaciones','Etapa con Observaciones',3),(17,'En Proceso','Trabajo de Gradución en proceso.',6),(18,'Iniciado','Trabajo de graduación iniciado.',6);
/*!40000 ALTER TABLE `cat_sta_estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_tit_titulos_profesionales`
--

DROP TABLE IF EXISTS `cat_tit_titulos_profesionales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_tit_titulos_profesionales` (
  `id_cat_tit` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_titulo_cat_tit` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cat_tit`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_tit_titulos_profesionales`
--

LOCK TABLES `cat_tit_titulos_profesionales` WRITE;
/*!40000 ALTER TABLE `cat_tit_titulos_profesionales` DISABLE KEYS */;
INSERT INTO `cat_tit_titulos_profesionales` VALUES (1,'Ingeniería de Sistemas Informáticos'),(2,'Ingeniería de Negocios'),(3,'Maestria en Arquitectura de Software'),(4,'Maestría en Seguridad Informática'),(5,'Maestría en Administración de Proyectos'),(6,'Maestría en Gestión de Riesgos'),(7,'Maestría en Administración de Negocios'),(8,'Maestría en Análisis Financiero'),(9,'Licenciatura en Ciencias de la computación'),(10,'Ingeniería Civil'),(11,'Ingeniería Química'),(12,'Ingeniería Agronomica');
/*!40000 ALTER TABLE `cat_tit_titulos_profesionales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_tpo_col_pub_tipo_colaborador`
--

DROP TABLE IF EXISTS `cat_tpo_col_pub_tipo_colaborador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_tpo_col_pub_tipo_colaborador` (
  `id_cat_tpo_col_pub` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_tpo_col_pub` varchar(45) NOT NULL,
  `descripcion_cat_tpo_col_pub` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cat_tpo_col_pub`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_tpo_col_pub_tipo_colaborador`
--

LOCK TABLES `cat_tpo_col_pub_tipo_colaborador` WRITE;
/*!40000 ALTER TABLE `cat_tpo_col_pub_tipo_colaborador` DISABLE KEYS */;
INSERT INTO `cat_tpo_col_pub_tipo_colaborador` VALUES (1,'Coordinador','Docente coordinador de trabajo de graduación'),(2,'Asesor','Docente asesor de trabajo de graduación'),(3,'Director','Docente director de trabajo de graduación'),(4,'Observador','Docente observador de trabajo de graduación');
/*!40000 ALTER TABLE `cat_tpo_col_pub_tipo_colaborador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_tpo_doc_tipo_documento`
--

DROP TABLE IF EXISTS `cat_tpo_doc_tipo_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_tpo_doc_tipo_documento` (
  `id_cat_tpo_doc` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_pdg_tpo_doc` varchar(45) NOT NULL,
  `descripcion_pdg_tpo_doc` varchar(100) DEFAULT NULL,
  `puede_observar_cat_pdg_tpo_doc` int(11) NOT NULL DEFAULT '1',
  `anio_cat_pdg_tpo_doc` int(11) NOT NULL,
  PRIMARY KEY (`id_cat_tpo_doc`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_tpo_doc_tipo_documento`
--

LOCK TABLES `cat_tpo_doc_tipo_documento` WRITE;
/*!40000 ALTER TABLE `cat_tpo_doc_tipo_documento` DISABLE KEYS */;
INSERT INTO `cat_tpo_doc_tipo_documento` VALUES (2,'Anteproyecto','Este es el Anteproyecto',1,2018),(3,'Analisis ','Documento de Analisis',1,2018),(4,'Diseño','Este es el documento del Diseño',1,2018),(5,'Tomo','Tomo Final',1,2018),(6,'Perfil','Documento del Perfil del Proyecto',1,2018),(7,'Resumen de Perfil','Resumen de una pagina del perfil del proyecto',1,2018),(8,'Entregable 1','Primer Entregable de una etapa evaluativa',1,2018),(17,'Entregable 2',' Segundo Entregable de una etapa evaluativa',1,2018),(18,'Entregable 3','Tercer Entregable de una etapa evaluativa',1,2018),(19,'Entregable 4',' Cuarto Entregable de una etapa evaluativa',1,2018),(20,'Entregable 5',' Quinto Entregable de una etapa evaluativa',1,2018),(21,'Entregable 6','Sexto Entregable de una etapa evaluativa',1,2018),(22,'Entregable 7',' Septimo Entregable de una etapa evaluativa',1,2018),(23,'Entregable 8','Octavo Entregable de una etapa evaluativa',1,2018),(24,'Entregable 9','Noveno Entregable de una etapa evaluativa',1,2018),(25,'Entregable 10','Decimo  Entregable de una etapa evaluativa',1,2018);
/*!40000 ALTER TABLE `cat_tpo_doc_tipo_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_tpo_pub_tipo_publicacion`
--

DROP TABLE IF EXISTS `cat_tpo_pub_tipo_publicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_tpo_pub_tipo_publicacion` (
  `id_cat_tpo_pub` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_tpo_pub` varchar(45) NOT NULL,
  `descripcion_cat_tpo_pub` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cat_tpo_pub`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_tpo_pub_tipo_publicacion`
--

LOCK TABLES `cat_tpo_pub_tipo_publicacion` WRITE;
/*!40000 ALTER TABLE `cat_tpo_pub_tipo_publicacion` DISABLE KEYS */;
INSERT INTO `cat_tpo_pub_tipo_publicacion` VALUES (1,'TG','Trabajo de Graduación'),(2,'AA','Artículo Académico'),(3,'EL','Escrito Libre'),(4,'AC','Artículo Científico');
/*!40000 ALTER TABLE `cat_tpo_pub_tipo_publicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_tpo_ski_tipo_skill`
--

DROP TABLE IF EXISTS `cat_tpo_ski_tipo_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_tpo_ski_tipo_skill` (
  `id_tpo_ski` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_tpo_ski` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_tpo_ski`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_tpo_ski_tipo_skill`
--

LOCK TABLES `cat_tpo_ski_tipo_skill` WRITE;
/*!40000 ALTER TABLE `cat_tpo_ski_tipo_skill` DISABLE KEYS */;
INSERT INTO `cat_tpo_ski_tipo_skill` VALUES (1,'Base de Datos'),(2,'Lenguajes de Programación'),(3,'Bussines Intelligence'),(4,'Arquitectura de Software'),(5,'Inteligencia Artificial'),(6,'Metodologías Ágiles');
/*!40000 ALTER TABLE `cat_tpo_ski_tipo_skill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_tpo_sta_tipo_estado`
--

DROP TABLE IF EXISTS `cat_tpo_sta_tipo_estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_tpo_sta_tipo_estado` (
  `id_cat_tpo_sta` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_tpo_sta` varchar(60) NOT NULL,
  `descripcion_cat_tpo_sta` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cat_tpo_sta`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_tpo_sta_tipo_estado`
--

LOCK TABLES `cat_tpo_sta_tipo_estado` WRITE;
/*!40000 ALTER TABLE `cat_tpo_sta_tipo_estado` DISABLE KEYS */;
INSERT INTO `cat_tpo_sta_tipo_estado` VALUES (1,'Creación de Grupos','Estos Estados servirán para el proceso de creación y formación de Grupos'),(2,'Estados de Preperfil','Estados relacionados al proceso de Preperfil de grupos'),(3,'Estados de Etapas Evaluativas','Estados de las diferentes etapas Evaluativas'),(6,'Estados Trabajo de Graduación','Estados del seguimiento del proceso de trabajos de graduación');
/*!40000 ALTER TABLE `cat_tpo_sta_tipo_estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_tpo_tra_gra_tipo_trabajo_graduacion`
--

DROP TABLE IF EXISTS `cat_tpo_tra_gra_tipo_trabajo_graduacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cat_tpo_tra_gra_tipo_trabajo_graduacion` (
  `id_cat_tpo_tra_gra` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cat_tpo_tra_gra` varchar(45) NOT NULL,
  `anio_cat_tpo_tra_gra` int(11) NOT NULL,
  PRIMARY KEY (`id_cat_tpo_tra_gra`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_tpo_tra_gra_tipo_trabajo_graduacion`
--

LOCK TABLES `cat_tpo_tra_gra_tipo_trabajo_graduacion` WRITE;
/*!40000 ALTER TABLE `cat_tpo_tra_gra_tipo_trabajo_graduacion` DISABLE KEYS */;
INSERT INTO `cat_tpo_tra_gra_tipo_trabajo_graduacion` VALUES (1,'Trabajo de Graduación Tradicional',2018),(2,'Desarrollo de Software Agil',2018),(3,'Desarrollo Agil',2018),(4,'Especialización',2019);
/*!40000 ALTER TABLE `cat_tpo_tra_gra_tipo_trabajo_graduacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcn_cer_certificaciones`
--

DROP TABLE IF EXISTS `dcn_cer_certificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dcn_cer_certificaciones` (
  `id_dcn_cer` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_dcn_cer` varchar(45) DEFAULT NULL,
  `anio_expedicion_dcn_cer` varchar(45) DEFAULT NULL,
  `institucion_dcn_cer` varchar(45) DEFAULT NULL,
  `id_cat_idi` int(11) NOT NULL,
  PRIMARY KEY (`id_dcn_cer`),
  KEY `fk_dcn_cer_certificaciones_cat_idi_idioma1_idx` (`id_cat_idi`),
  CONSTRAINT `fk_dcn_cer_certificaciones_cat_idi_idioma1` FOREIGN KEY (`id_cat_idi`) REFERENCES `cat_idi_idioma` (`id_cat_idi`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcn_cer_certificaciones`
--

LOCK TABLES `dcn_cer_certificaciones` WRITE;
/*!40000 ALTER TABLE `dcn_cer_certificaciones` DISABLE KEYS */;
INSERT INTO `dcn_cer_certificaciones` VALUES (1,'CCNA - Cisco Network Security','2010','Cisco Institute',2),(2,'.NET fundamentals','2014','CECTIC',2),(3,'C# Advance','2013','Universidad Don Bosco',1),(4,'SCRUM Master','2012','ASIS',1),(5,'Fundamentos de Scrum','2014','AgilStudy',1),(6,'DDD Development','2012','DevCamp',1),(7,'English Advanced','2009','Universidad Don Bosco',2),(8,'XP Certification','2009','Universidad Don Bosco',2);
/*!40000 ALTER TABLE `dcn_cer_certificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcn_exp_experiencia`
--

DROP TABLE IF EXISTS `dcn_exp_experiencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dcn_exp_experiencia` (
  `id_dcn_exp` int(11) NOT NULL AUTO_INCREMENT,
  `lugar_trabajo_dcn_exp` varchar(45) DEFAULT NULL,
  `anio_inicio_dcn_exp` int(11) DEFAULT NULL,
  `anio_fin_dcn_exp` int(11) DEFAULT NULL,
  `descripcion_dcn_exp` varchar(800) DEFAULT NULL,
  `id_cat_idi` int(11) NOT NULL,
  `id_pdg_dcn` int(11) NOT NULL,
  PRIMARY KEY (`id_dcn_exp`),
  KEY `fk_dcn_exp_experiencia_cat_idi_idioma1_idx` (`id_cat_idi`),
  KEY `fk_dcn_exp_experiencia_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  CONSTRAINT `fk_dcn_exp_experiencia_cat_idi_idioma1` FOREIGN KEY (`id_cat_idi`) REFERENCES `cat_idi_idioma` (`id_cat_idi`),
  CONSTRAINT `fk_dcn_exp_experiencia_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcn_exp_experiencia`
--

LOCK TABLES `dcn_exp_experiencia` WRITE;
/*!40000 ALTER TABLE `dcn_exp_experiencia` DISABLE KEYS */;
INSERT INTO `dcn_exp_experiencia` VALUES (1,'Universidad de El Salvador',2006,NULL,'Docente universitario de tiempo completo, encargado de la cátedra de Programación de Dispositivos móviles, Programación I,II y III, Introducción a la Informática, entre otros.',1,1),(2,'Consultora de Software',1998,2005,'Desarrollador de Software encargado de diseño e implementación de nuevos sistemas contables y financieros',1,1),(3,'Ministerio de Transporte',2000,2001,'Consultoría para el desarollo de plataforma web de gestión de accidentes de tránsito',1,1),(4,'Consultores Independiente',1999,2004,'Consultorías especializadas en sistemas financieros y contables, encargado de implementaciónes de nuevos procesos en sistemas automátizados',1,2),(5,'Universidad de El Salvador',2004,NULL,'Docente universitario de la materia de Programación Web y Consultoría profesional, encargado de actualizaciones en los planes de estudio y actividades didacticas',1,2),(6,'CREA',2012,2014,'Analista programador en tecnologías .NET y PHP',2,3),(7,'CECTIC',2014,2015,'Formador de Curso SQL Server Fundamentals',2,3),(8,'Universidad de El Salvador',2012,NULL,'Docente Universitario especializado en Desarrollo de sistemas',1,4);
/*!40000 ALTER TABLE `dcn_exp_experiencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcn_exp_ues_experiencia_ues`
--

DROP TABLE IF EXISTS `dcn_exp_ues_experiencia_ues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dcn_exp_ues_experiencia_ues` (
  `id_dcn_exp_ues` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat_car` int(11) NOT NULL,
  `id_pdg_dcn` int(11) NOT NULL,
  `descripcion_exp_ues` varchar(800) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  PRIMARY KEY (`id_dcn_exp_ues`),
  KEY `fk_reference_cargo_exp_ues_idx` (`id_cat_car`),
  KEY `fk_reference_docente_experiencia_idx` (`id_pdg_dcn`),
  CONSTRAINT `fk_reference_docente_experiencia` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`),
  CONSTRAINT `fk_reference_experiencia_cargo_docente` FOREIGN KEY (`id_cat_car`) REFERENCES `cat_car_cargo_eisi` (`id_cat_car`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcn_exp_ues_experiencia_ues`
--

LOCK TABLES `dcn_exp_ues_experiencia_ues` WRITE;
/*!40000 ALTER TABLE `dcn_exp_ues_experiencia_ues` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcn_exp_ues_experiencia_ues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcn_his_historial_academico`
--

DROP TABLE IF EXISTS `dcn_his_historial_academico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `dcn_his_historial_academico` (
  `id_dcn_his` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_dcn` int(11) NOT NULL,
  `id_cat_mat` int(11) NOT NULL,
  `id_cat_car` int(11) NOT NULL,
  `anio` int(11) DEFAULT NULL,
  `descripcion_adicional` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_dcn_his`),
  KEY `reference_cargo_eisi_idx` (`id_cat_car`),
  KEY `fk_reference_docente_idx` (`id_pdg_dcn`),
  KEY `fk_reference_materia_idx` (`id_cat_mat`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcn_his_historial_academico`
--

LOCK TABLES `dcn_his_historial_academico` WRITE;
/*!40000 ALTER TABLE `dcn_his_historial_academico` DISABLE KEYS */;
INSERT INTO `dcn_his_historial_academico` VALUES (1,1,20,1,2000,NULL),(2,1,22,2,2001,NULL),(3,1,23,1,2001,NULL),(4,1,24,1,2001,NULL),(5,1,25,1,2004,NULL),(6,1,26,2,2009,NULL),(7,1,29,2,2013,NULL),(8,1,29,2,2014,NULL),(9,1,29,2,2015,NULL),(10,1,30,1,2015,NULL),(11,2,1,1,2016,NULL);
/*!40000 ALTER TABLE `dcn_his_historial_academico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gen_est_estudiante`
--

DROP TABLE IF EXISTS `gen_est_estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `gen_est_estudiante` (
  `id_gen_est` int(11) NOT NULL AUTO_INCREMENT,
  `id_gen_usr` int(11) NOT NULL,
  `carnet_gen_est` varchar(10) NOT NULL,
  `nombre_gen_est` varchar(45) NOT NULL,
  PRIMARY KEY (`id_gen_est`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gen_est_estudiante`
--

LOCK TABLES `gen_est_estudiante` WRITE;
/*!40000 ALTER TABLE `gen_est_estudiante` DISABLE KEYS */;
INSERT INTO `gen_est_estudiante` VALUES (1,3,'cm11005','Fernando Ernesto Cosme Morales'),(2,22,'sb12002','Eduardo Rafael Serrano Barrera'),(3,21,'rg12001','Edgardo José Ramírez García'),(4,20,'pp10005','Francisco Wilfredo Polanco Portillo'),(5,34,'sb12003','Yennifer Santofimio Rincón'),(6,35,'sb12004','Maria Alejandra Jaramillo Rivera'),(7,36,'sb12005','Maikil Steven Durango Calvo'),(8,37,'sb12006','Luisa Manuela Betancur Vargas'),(9,38,'sb12007','Lizeth Gallego Quiroz'),(10,39,'sb12008','Laura Marecla Osorio Serna'),(11,40,'sb12009','Juan Pablo Quintero Tamayo'),(12,41,'sb12010','Gysela Andrea Jordan Aristizabal'),(13,42,'sb12011','Gloria Estefany Velez Serna'),(14,43,'sb12012','Estefany Ochoa Cardona'),(20,53,'rg12001','Edgardo José Ramírez García');
/*!40000 ALTER TABLE `gen_est_estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gen_int_integracion`
--

DROP TABLE IF EXISTS `gen_int_integracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `gen_int_integracion` (
  `id_gen_int` int(11) NOT NULL AUTO_INCREMENT,
  `id_gen_tpo_int` int(11) NOT NULL,
  `llave_gen_int` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_gen_int`),
  KEY `fk_gen_int_integracion_gen_tpo_int_tipo_integracion1_idx` (`id_gen_tpo_int`),
  CONSTRAINT `fk_gen_int_integracion_gen_tpo_int_tipo_integracion1` FOREIGN KEY (`id_gen_tpo_int`) REFERENCES `gen_tpo_int_tipo_integracion` (`id_gen_tpo_int`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gen_int_integracion`
--

LOCK TABLES `gen_int_integracion` WRITE;
/*!40000 ALTER TABLE `gen_int_integracion` DISABLE KEYS */;
/*!40000 ALTER TABLE `gen_int_integracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gen_par_parametros`
--

DROP TABLE IF EXISTS `gen_par_parametros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `gen_par_parametros` (
  `id_gen_par` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_gen_par` varchar(45) DEFAULT NULL,
  `valor_gen_par` varchar(45) DEFAULT NULL,
  `id_gen_usuario` int(11) DEFAULT NULL,
  `fecha_creacion_gen_par` datetime DEFAULT NULL,
  `id_gen_tpo_par` int(11) NOT NULL,
  PRIMARY KEY (`id_gen_par`),
  KEY `fk_gen_par_parametros_gen_tpo_par_tipo_parametro1_idx` (`id_gen_tpo_par`),
  CONSTRAINT `fk_gen_par_parametros_gen_tpo_par_tipo_parametro1` FOREIGN KEY (`id_gen_tpo_par`) REFERENCES `gen_tpo_par_tipo_parametro` (`id_gen_tpo_par`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gen_par_parametros`
--

LOCK TABLES `gen_par_parametros` WRITE;
/*!40000 ALTER TABLE `gen_par_parametros` DISABLE KEYS */;
INSERT INTO `gen_par_parametros` VALUES (1,'Cant. Max. Grupo','5',33,'2018-08-05 23:25:18',1),(2,'Cant. Min. Grupo ','3',33,'2018-08-05 23:25:18',1),(3,'CANTMAXASESOR','1',NULL,NULL,1),(4,'CANTMINASESOR','1',NULL,NULL,1),(5,'CANTMINJURADO','1',NULL,NULL,1),(6,'CANTMAXJURADO','2',NULL,NULL,1);
/*!40000 ALTER TABLE `gen_par_parametros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gen_tpo_int_tipo_integracion`
--

DROP TABLE IF EXISTS `gen_tpo_int_tipo_integracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `gen_tpo_int_tipo_integracion` (
  `id_gen_tpo_int` int(11) NOT NULL AUTO_INCREMENT,
  `tabla_gen_tpo_int` varchar(100) NOT NULL,
  `descripcion_gen_tpo_int` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_gen_tpo_int`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gen_tpo_int_tipo_integracion`
--

LOCK TABLES `gen_tpo_int_tipo_integracion` WRITE;
/*!40000 ALTER TABLE `gen_tpo_int_tipo_integracion` DISABLE KEYS */;
INSERT INTO `gen_tpo_int_tipo_integracion` VALUES (1,'pdg_gru_grupo','Información de Grupos de Trabajo de Graduación'),(2,'pdg_dcn_docente','Información de Docentes del Sistema'),(3,'gen_est_estudiante','Información de Estudiantes del Sistema');
/*!40000 ALTER TABLE `gen_tpo_int_tipo_integracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gen_tpo_par_tipo_parametro`
--

DROP TABLE IF EXISTS `gen_tpo_par_tipo_parametro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `gen_tpo_par_tipo_parametro` (
  `id_gen_tpo_par` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_gen_tpo_par` varchar(45) NOT NULL,
  PRIMARY KEY (`id_gen_tpo_par`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gen_tpo_par_tipo_parametro`
--

LOCK TABLES `gen_tpo_par_tipo_parametro` WRITE;
/*!40000 ALTER TABLE `gen_tpo_par_tipo_parametro` DISABLE KEYS */;
INSERT INTO `gen_tpo_par_tipo_parametro` VALUES (1,'INT'),(2,'DATETIME'),(3,'DECIMAL(5,2)');
/*!40000 ALTER TABLE `gen_tpo_par_tipo_parametro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gen_usuario`
--

DROP TABLE IF EXISTS `gen_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `gen_usuario` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `primer_nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `segundo_nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `segundo_apellido` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_carnet` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gen_usuario`
--

LOCK TABLES `gen_usuario` WRITE;
/*!40000 ALTER TABLE `gen_usuario` DISABLE KEYS */;
INSERT INTO `gen_usuario` VALUES (0,'Administrador de Publicaciones','admin_publicaciones','publicacione_tdg@eisi.edu.sv','$2y$10$HhbTQX12FiVs3aHGMeNy0eqfLVdrVdLKUh2SWT.13Dd3N6G4uq0E6','L9h81de71JYfEmfk2x0zqPUPlzpQreXqH6rffqZRmR8rFO2h3h2CZgtwtq6h','2018-07-20 00:39:08','2018-10-29 10:41:49',NULL,NULL,NULL,NULL,NULL),(3,'Fernando Ernesto Cosme Morales','cm11005','cosme.92@gmail.com','$2y$10$KPuVK9/TNGs3yMAHHfU5i.pCIsgGYoHdRXW0gg1aoGkt2oMltMZ5K','mJOX301Qv1SPAPQEQVQj9nbxk6GP1yR9WlfMq3vaQrYUv1nw00rXNEB4CwuM','2018-04-01 21:23:31','2018-10-15 20:55:58',NULL,NULL,NULL,NULL,NULL),(20,'Francisco Wilfredo Polanco Portillo','pp10005','polanco260593@gmail.com','$2y$10$Db..R7cD93r70/TgPqYzLetuYq.U.vsseOJiiq/c3rw2WnEgY47b6',NULL,'2018-04-12 19:48:34','2018-04-12 19:48:34',NULL,NULL,NULL,NULL,NULL),(21,'Edgardo José Ramirez García','rg12002','edgardo.ramirez94@gmail.com','$2y$10$x76t/jfOKVu3ZrBFcNMzxee47YyYwmjBpHCCN0ed1zgOF8D0TneGC','XQWXV2nsqqMwTQEip8HSZdYRheN14GYvVu9EIPRaGz7HLkJonEnjHAycPipd','2018-04-12 19:49:39','2018-04-12 19:49:39',NULL,NULL,NULL,NULL,NULL),(22,'Eduardo Rafael Serrano Barrera','sb12002','rafael31194@hotmail.com','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS','0S1OvxFtln6ZKJMyXWl2hiahULHaElTAYUoeiypGk6PC8vIqoQvlcdlCsBV7','2018-04-12 19:51:07','2018-04-12 19:51:07',NULL,NULL,NULL,NULL,NULL),(30,'Administrador Central','admin','administrador@ues.edu.sv','$2y$10$PdTVSePNsZMz/G/dT7uKM.nx4jwoCFRoU/qQntwPZQ9OtBjehYeiK','tHkAVlnzZq6ejOggsWTRyhzFKP9TjDCTCaHQSIYiZsKljdr0gCZFeiLotWhq','2018-05-02 12:28:30','2018-05-02 12:28:30',NULL,NULL,NULL,NULL,NULL),(31,'Yesenia Vigil','admin_tdg','tdg@ues.edu.sv','$2y$10$JGpT8Qd7FCjWq2j4ovjjv.z4YYJlxNx6916jeinPUAnmXx/eQtgpa','f3BnGYsQ5QwpXqvBLqhbuhgc54NfzWxReV7nLfM2wsYF1GWXApITv2zHGgsz','2018-05-21 04:50:36','2018-11-01 22:48:08',NULL,NULL,NULL,NULL,NULL),(32,'Cesar González','cg99005','cesargrodriguez@gmail.com','$2y$10$Hq8qsxVNZ6Wh5wBcQU58Yez8o00vTg22OAylsuenZPgS48ojmOQP2','uP4cZaFsuiPSd3ZUrQXPYsgmYAwXT3y1YNaB1aIzxQikE7tIfaYWfh3i3O03','2018-06-25 03:53:40','2018-06-25 03:53:40','Cesar','Augusto','González','Rodriguez','cg99005'),(33,'Balmore Ortiz','bo96001','balmore.ortiz@gmail.com','$2y$10$whBZB27BBTQD.BE3eBaxyev8FACIqK.uNy10kiHq21zpD/.F5mjpO',NULL,'2018-06-25 03:56:29','2018-06-25 03:56:29','Balmore',NULL,'Ortiz',NULL,'bo96001'),(34,'Yennifer Santofimio Rincón','sb12003','sb12003@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS','c6e8mGIWVJ2ZoYnhnWW4C4ZhCRpAo5WdbsQ2I4GRZDv09zOVqUwdCX71iYs7','2018-08-06 03:56:29',NULL,NULL,NULL,NULL,NULL,NULL),(35,'Maria Alejandra Jaramillo Rivera','sb12004','sb12004@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS','wm9mOOoyJcl0ZsC09MKtiWi3Dq7dwJSxYHRjF8Uvnc0dLQypMOGUmAT55TSK','2018-08-06 03:56:29',NULL,NULL,NULL,NULL,NULL,NULL),(36,'Maikil Steven Durango Calvo','sb12005','sb12005@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS','OPz02gMF9nAK70c1ND0KHxZ6svGScaxPGefR9LfKSaq11woz6egbARIIvll3','2018-08-06 03:56:29',NULL,NULL,NULL,NULL,NULL,NULL),(37,'Luisa Manuela Betancur Vargas','sb12006','sb12005@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS','Dz7CgtsxX8oBFDOqSFVr0MD8BgvVK9Fp4FzEEzNgPOAjaNFETzbtv4JfbdKk','2018-08-06 03:56:29',NULL,NULL,NULL,NULL,NULL,'sb12006'),(38,'Lizeth Gallego Quiroz','sb12007','sb12006@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS','16AndHIHGXqSr5ogKDrUs9nN0S10A3FSV3vOM7X3Rp38XVf4yweXIO94vhuT','2018-08-06 03:56:29',NULL,'Lizeth',NULL,' Gallego ','Quiroz','sb12007'),(39,'Laura Marecla Osorio Serna','sb12008','sb12007@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS','OBB1HtusDq49qxuamymtvzltjepwhjfKCLvAcwLo22yrAHsDI1o1jjFCOCLJ','2018-08-06 03:56:29',NULL,'Laura ','Marecla ','Osorio ','Serna','sb12008'),(40,'Juan Pablo Quintero Tamayo','sb12009','sb12008@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS','zdPn89QXLEhCAbjX9nOZseZTErlJduYd8BS98u8FTaPABa6OWajNcbV5MMsv','2018-08-06 03:56:29',NULL,'Juan ','Pablo ','Quintero ','Tamayo','sb12009'),(41,'Gysela Andrea Jordan Aristizabal','sb12010','sb12009@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS','g1iVst1OgHuVrEqBaPkgEvhrEnuR1JDn0LPoYEeUmIF7nNR2VHlevUGFA1mJ','2018-08-06 03:56:29',NULL,'Gysela ','Andrea ','Jordan ','Aristizabal',NULL),(42,'Gloria Estefany Velez Serna','sb12011','sb12010@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS',NULL,'2018-08-06 03:56:29',NULL,'Gloria ','Estefany ','Velez ','Serna',NULL),(43,'Estefany Ochoa Cardona','sb12012','sb12011@ues.edu.sv','$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS',NULL,'2018-08-06 03:56:29',NULL,'Estefany',NULL,' Ochoa ','Cardona',NULL),(53,'Edgardo José Ramírez García','rg12001','rg12001@ues.edu.sv','$2y$10$Yo0Uu0CRFcdNCJzRyo7Lw.ucW.JphJhenkjn9NY7XUwdNSsHkX6gy','gD2TPvoua4xKx6IMR5WiYuCSeFCfdlGgsKhnfiPPbDA799Vex2K1SvrsjfEz','2018-10-12 23:14:57','2018-10-26 21:31:06','Edgardo','José','Ramírez','García','rg12001'),(54,'Administrador de Documentos de Publicaciones','doc_publicaciones','admin.docs@ues.ued.sv','$2y$10$/7I6537LHGIDwIQP3ewLUu6.P18PUiDj.vyTHxDvcm.neJRcalNc2','K5u2L6foii9yIqhgDnppQ3vihI5LuyudalYVJsud6fRds16Nkaqnck90guL2','2018-10-29 10:46:01','2018-10-29 10:50:53',NULL,NULL,NULL,NULL,NULL),(55,'Ivan','ro10003','ro10003@ues.edu.sv','$2y$10$jxV3XxHD5msOmx9yad3KYe5CKH3zdN6jNupbmOcsNHmjs/Gon6jmK','ZPapyRSZ5vVbW3t5idMhsjm057t2CQA8hyAhy8zeKguKVPJtFQFZlD6u7J5C','2018-11-03 11:13:37','2018-11-03 11:13:56',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `gen_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_apr_eta_tra_aprobador_etapa_trabajo`
--

DROP TABLE IF EXISTS `pdg_apr_eta_tra_aprobador_etapa_trabajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_apr_eta_tra_aprobador_etapa_trabajo` (
  `id_pdg_apr_eta_tra` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_eta_eva_tra` int(11) NOT NULL,
  `id_pdg_tri_gru` int(11) NOT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  `aprobo` int(11) DEFAULT NULL,
  `fecha_aprobacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pdg_apr_eta_tra`),
  KEY `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_tri_gru_trib_idx` (`id_pdg_tri_gru`),
  KEY `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_tra_gra_trab_idx` (`id_pdg_tra_gra`),
  KEY `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_eta_eva_tra__idx` (`id_pdg_eta_eva_tra`),
  CONSTRAINT `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_eta_eva_tra_et1` FOREIGN KEY (`id_pdg_eta_eva_tra`) REFERENCES `pdg_eta_eva_tra_etapa_trabajo` (`id_pdg_eta_eva_tra`),
  CONSTRAINT `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_tra_gra_trabaj1` FOREIGN KEY (`id_pdg_tra_gra`) REFERENCES `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`),
  CONSTRAINT `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_tri_gru_tribun1` FOREIGN KEY (`id_pdg_tri_gru`) REFERENCES `pdg_tri_gru_tribunal_grupo` (`id_pdg_tri_gru`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_apr_eta_tra_aprobador_etapa_trabajo`
--

LOCK TABLES `pdg_apr_eta_tra_aprobador_etapa_trabajo` WRITE;
/*!40000 ALTER TABLE `pdg_apr_eta_tra_aprobador_etapa_trabajo` DISABLE KEYS */;
/*!40000 ALTER TABLE `pdg_apr_eta_tra_aprobador_etapa_trabajo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_arc_doc_archivo_documento`
--

DROP TABLE IF EXISTS `pdg_arc_doc_archivo_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_arc_doc_archivo_documento` (
  `id_pdg_arc_doc` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_doc` int(11) NOT NULL,
  `ubicacion_arc_doc` varchar(200) NOT NULL,
  `fecha_subida_arc_doc` datetime DEFAULT NULL,
  `nombre_arc_doc` varchar(45) DEFAULT NULL,
  `activo` int(11) DEFAULT '1',
  PRIMARY KEY (`id_pdg_arc_doc`),
  KEY `fk_pdg_arc_doc_archivo_documento_pdg_doc_documento1_idx` (`id_pdg_doc`),
  CONSTRAINT `fk_pdg_arc_doc_archivo_documento_pdg_doc_documento1` FOREIGN KEY (`id_pdg_doc`) REFERENCES `pdg_doc_documento` (`id_pdg_doc`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='InnoDB';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_arc_doc_archivo_documento`
--

LOCK TABLES `pdg_arc_doc_archivo_documento` WRITE;
/*!40000 ALTER TABLE `pdg_arc_doc_archivo_documento` DISABLE KEYS */;
INSERT INTO `pdg_arc_doc_archivo_documento` VALUES (1,1,'C:\\wamp\\www\\SIGPAD\\public\\Uploads\\2018\\Grupo1\\Etapas\\ Grupo1_2018_080704Cupon Escuela Génesis.pdf','2018-07-22 20:07:04','Cupon Escuela Génesis.pdf',1);
/*!40000 ALTER TABLE `pdg_arc_doc_archivo_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_asp_aspectos_evaluativos`
--

DROP TABLE IF EXISTS `pdg_asp_aspectos_evaluativos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_asp_aspectos_evaluativos` (
  `id_pdg_asp` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_pdg_asp` varchar(45) NOT NULL,
  `ponderacion_pdg_asp` decimal(5,2) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL,
  PRIMARY KEY (`id_pdg_asp`),
  KEY `fk_pdg_asp_aspectos_evaluativos_cat_eta_eva_etapa_evaluativ_idx` (`id_cat_eta_eva`),
  CONSTRAINT `fk_pdg_asp_aspectos_evaluativos_cat_eta_eva_etapa_evaluativa1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_asp_aspectos_evaluativos`
--

LOCK TABLES `pdg_asp_aspectos_evaluativos` WRITE;
/*!40000 ALTER TABLE `pdg_asp_aspectos_evaluativos` DISABLE KEYS */;
INSERT INTO `pdg_asp_aspectos_evaluativos` VALUES (1,'Documento',40.00,10),(2,'Defensa',60.00,10),(3,'Analisis',50.00,11),(4,'Diseño',50.00,11),(5,'Defensa',100.00,12),(6,'Tomo',40.00,13),(7,'Defensa Final',60.00,13),(8,'Nota Anteproyecto',100.00,18),(9,'Nota Etapa I',100.00,19),(10,'Nota Etapa II',100.00,20),(11,'Nota Defensa Final',100.00,21);
/*!40000 ALTER TABLE `pdg_asp_aspectos_evaluativos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_bit_tra_gra_bitacora_trabajo_graduacion`
--

DROP TABLE IF EXISTS `pdg_bit_tra_gra_bitacora_trabajo_graduacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_bit_tra_gra_bitacora_trabajo_graduacion` (
  `id_pdg_bit_tra_gra` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_bit_tra_gra` datetime DEFAULT NULL,
  `nombre_archivo_bit_tra_gra` varchar(100) DEFAULT NULL,
  `ubicacion_arch_bit_tra_gra` varchar(100) DEFAULT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  PRIMARY KEY (`id_pdg_bit_tra_gra`),
  KEY `fk_pdg_bit_tra_gra_bitacora_trabajo_graduacion_pdg_tra_gra__idx` (`id_pdg_tra_gra`),
  CONSTRAINT `fk_pdg_bit_tra_gra_bitacora_trabajo_graduacion_pdg_tra_gra_tr1` FOREIGN KEY (`id_pdg_tra_gra`) REFERENCES `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_bit_tra_gra_bitacora_trabajo_graduacion`
--

LOCK TABLES `pdg_bit_tra_gra_bitacora_trabajo_graduacion` WRITE;
/*!40000 ALTER TABLE `pdg_bit_tra_gra_bitacora_trabajo_graduacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `pdg_bit_tra_gra_bitacora_trabajo_graduacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_dcn_docente`
--

DROP TABLE IF EXISTS `pdg_dcn_docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_dcn_docente` (
  `id_pdg_dcn` int(11) NOT NULL AUTO_INCREMENT,
  `carnet_pdg_dcn` varchar(45) NOT NULL,
  `anio_titulacion_pdg_dcn` varchar(45) DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  `id_gen_usuario` int(11) NOT NULL,
  `dcn_profileFoto` varchar(50000) DEFAULT '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 ',
  `tipoJornada` int(11) DEFAULT NULL COMMENT '1-Completo, 2-Parcial',
  `descripcionDocente` varchar(800) DEFAULT NULL,
  `id_cargo_actual` int(11) DEFAULT NULL,
  `link_fb` varchar(500) DEFAULT NULL,
  `link_tw` varchar(500) DEFAULT NULL,
  `link_linke` varchar(500) DEFAULT NULL,
  `link_git` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_pdg_dcn`),
  KEY `fk_references_cat_cargo_idx` (`id_cargo_actual`),
  CONSTRAINT `fk_references_cat_cargo` FOREIGN KEY (`id_cargo_actual`) REFERENCES `cat_car_cargo_eisi` (`id_cat_car`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_dcn_docente`
--

LOCK TABLES `pdg_dcn_docente` WRITE;
/*!40000 ALTER TABLE `pdg_dcn_docente` DISABLE KEYS */;
INSERT INTO `pdg_dcn_docente` VALUES (1,'cg99005','1999',1,32,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTUBhKHrVXeyHaH_0BmxSnRm2oDQLzcytCb66tmQC9etQ9Ooi7',1,'es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de L orem Ipsum.',1,'https://www.facebook.com/','https://twitter.com/','https://www.linkedin.com','https://github.com/'),(2,'bo96001','1996',1,33,'https://assets4.domestika.org/project-items/001/228/844/sesion-estudio-barcelona-10-big.jpg?1425034585',1,NULL,2,NULL,NULL,NULL,NULL),(3,'sb12010','1996',1,41,'https://blog.faculdademachadodeassis.edu.br/wp-content/uploads/2014/10/2.jpg',2,NULL,3,NULL,NULL,NULL,NULL),(4,'sb12011','1996',1,42,'https://www.colegioperfil.com.br/wp-content/uploads/2018/10/aluna02-1.png',1,NULL,4,NULL,NULL,NULL,NULL),(5,'sb12012','1996',1,43,'https://destinonegocio.com/wp-content/uploads/2015/10/ico-destinonegocio-profesional-de-recursos-humanos-istock-getty-images.jpg',2,NULL,5,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `pdg_dcn_docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_def_defensa`
--

DROP TABLE IF EXISTS `pdg_def_defensa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_def_defensa` (
  `idcat_pdg_def_defensa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_defensa` varchar(100) DEFAULT NULL,
  `lugar_def` varchar(100) DEFAULT NULL,
  `fecha_hora_def` datetime DEFAULT NULL,
  `descripcion_def` varchar(300) DEFAULT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL COMMENT 'la etapa evaluativa se va agregar en base al estado del trabajo de graduación cuando se cree un objeto de tipo defensa\n',
  PRIMARY KEY (`idcat_pdg_def_defensa`),
  KEY `fk_pdg_def_defensa_pdg_tra_gra_trabajo_graduacion1_idx` (`id_pdg_tra_gra`),
  KEY `fk_pdg_def_defensa_cat_eta_eva_etapa_evaluativa1_idx` (`id_cat_eta_eva`),
  CONSTRAINT `fk_pdg_def_defensa_cat_eta_eva_etapa_evaluativa1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`),
  CONSTRAINT `fk_pdg_def_defensa_pdg_tra_gra_trabajo_graduacion1` FOREIGN KEY (`id_pdg_tra_gra`) REFERENCES `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_def_defensa`
--

LOCK TABLES `pdg_def_defensa` WRITE;
/*!40000 ALTER TABLE `pdg_def_defensa` DISABLE KEYS */;
/*!40000 ALTER TABLE `pdg_def_defensa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_doc_documento`
--

DROP TABLE IF EXISTS `pdg_doc_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_doc_documento` (
  `id_pdg_doc` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_tpo_doc` int(11) NOT NULL,
  `fecha_creacion_pdg_doc` datetime NOT NULL,
  PRIMARY KEY (`id_pdg_doc`),
  KEY `fk_pdg_doc_documento_pdg_gru_grupo1_idx` (`id_pdg_gru`),
  KEY `fk_pdg_doc_documento_cat_pdg_tpo_doc_tipo_documento1_idx` (`id_cat_tpo_doc`),
  CONSTRAINT `fk_pdg_doc_documento_cat_pdg_tpo_doc_tipo_documento1` FOREIGN KEY (`id_cat_tpo_doc`) REFERENCES `cat_tpo_doc_tipo_documento` (`id_cat_tpo_doc`),
  CONSTRAINT `fk_pdg_doc_documento_pdg_gru_grupo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_doc_documento`
--

LOCK TABLES `pdg_doc_documento` WRITE;
/*!40000 ALTER TABLE `pdg_doc_documento` DISABLE KEYS */;
INSERT INTO `pdg_doc_documento` VALUES (1,1,5,'2018-07-22 20:07:55');
/*!40000 ALTER TABLE `pdg_doc_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_eta_eva_tra_etapa_trabajo`
--

DROP TABLE IF EXISTS `pdg_eta_eva_tra_etapa_trabajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_eta_eva_tra_etapa_trabajo` (
  `id_pdg_eta_eva_tra` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` datetime DEFAULT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL,
  `id_tpo_doc` int(11) NOT NULL,
  `orden_eta_eva` int(11) DEFAULT '1',
  PRIMARY KEY (`id_pdg_eta_eva_tra`),
  KEY `fk_pdg_eta_eva_tra_etapa_trabajo_pdg_tra_gra_trabajo_gradua_idx` (`id_pdg_tra_gra`),
  KEY `fk_pdg_eta_eva_tra_etapa_trabajo_cat_eta_eva_etapa_evaluati_idx` (`id_cat_eta_eva`),
  CONSTRAINT `fk_pdg_eta_eva_tra_etapa_trabajo_cat_eta_eva_etapa_evaluativa1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`),
  CONSTRAINT `fk_pdg_eta_eva_tra_etapa_trabajo_pdg_tra_gra_trabajo_graduaci1` FOREIGN KEY (`id_pdg_tra_gra`) REFERENCES `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_eta_eva_tra_etapa_trabajo`
--

LOCK TABLES `pdg_eta_eva_tra_etapa_trabajo` WRITE;
/*!40000 ALTER TABLE `pdg_eta_eva_tra_etapa_trabajo` DISABLE KEYS */;
INSERT INTO `pdg_eta_eva_tra_etapa_trabajo` VALUES (1,NULL,1,10,2,1),(2,NULL,1,11,3,2),(3,NULL,1,11,4,2),(4,NULL,1,13,5,1),(10,NULL,1,13,8,1),(11,NULL,1,13,17,2),(12,NULL,1,12,8,1),(13,NULL,1,12,17,2),(14,NULL,1,12,18,3),(15,NULL,1,12,19,4);
/*!40000 ALTER TABLE `pdg_eta_eva_tra_etapa_trabajo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_gru_est_grupo_estudiante`
--

DROP TABLE IF EXISTS `pdg_gru_est_grupo_estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_gru_est_grupo_estudiante` (
  `id_pdg_gru_est` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_gru` int(11) NOT NULL,
  `id_gen_est` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `eslider_pdg_gru_est` int(11) NOT NULL,
  PRIMARY KEY (`id_pdg_gru_est`),
  KEY `fk_pdg_gru_est_grupo_estudiante_pdg_gru_grupo_idx` (`id_pdg_gru`),
  KEY `fk_pdg_gru_est_grupo_estudiante_gen_est_estudiante1_idx` (`id_gen_est`),
  KEY `fk_pdg_gru_est_grupo_estudiante_cat_sta_estado1_idx` (`id_cat_sta`),
  CONSTRAINT `fk_pdg_gru_est_grupo_estudiante_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`),
  CONSTRAINT `fk_pdg_gru_est_grupo_estudiante_gen_est_estudiante1` FOREIGN KEY (`id_gen_est`) REFERENCES `gen_est_estudiante` (`id_gen_est`),
  CONSTRAINT `fk_pdg_gru_est_grupo_estudiante_pdg_gru_grupo` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_gru_est_grupo_estudiante`
--

LOCK TABLES `pdg_gru_est_grupo_estudiante` WRITE;
/*!40000 ALTER TABLE `pdg_gru_est_grupo_estudiante` DISABLE KEYS */;
INSERT INTO `pdg_gru_est_grupo_estudiante` VALUES (1,1,1,6,1),(2,1,4,6,0),(4,1,2,6,0),(15,4,5,6,1),(16,5,6,6,1),(17,5,7,6,0),(18,5,8,6,0),(19,5,9,6,0),(20,4,3,6,0);
/*!40000 ALTER TABLE `pdg_gru_est_grupo_estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_gru_grupo`
--

DROP TABLE IF EXISTS `pdg_gru_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_gru_grupo` (
  `id_pdg_gru` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat_sta` int(11) NOT NULL,
  `numero_pdg_gru` varchar(45) DEFAULT NULL,
  `correlativo_pdg_gru_gru` int(11) NOT NULL DEFAULT '0',
  `anio_pdg_gru` int(11) NOT NULL,
  `ciclo_pdg_gru` varchar(45) NOT NULL,
  PRIMARY KEY (`id_pdg_gru`),
  KEY `fk_pdg_gru_grupo_cat_sta_estado1_idx` (`id_cat_sta`),
  CONSTRAINT `fk_pdg_gru_grupo_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_gru_grupo`
--

LOCK TABLES `pdg_gru_grupo` WRITE;
/*!40000 ALTER TABLE `pdg_gru_grupo` DISABLE KEYS */;
INSERT INTO `pdg_gru_grupo` VALUES (1,3,'01-2018',1,2018,'I'),(4,7,NULL,0,2018,'I'),(5,3,'02-2018',2,2018,'I');
/*!40000 ALTER TABLE `pdg_gru_grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_not_cri_tra_nota_criterio_trabajo`
--

DROP TABLE IF EXISTS `pdg_not_cri_tra_nota_criterio_trabajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_not_cri_tra_nota_criterio_trabajo` (
  `id_pdg_not_cri_tra` int(11) NOT NULL AUTO_INCREMENT,
  `nota_pdg_not_cri_tra` decimal(3,2) NOT NULL,
  `id_cat_cri_eva` int(11) NOT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  `id_pdg_gru_est` int(11) NOT NULL,
  PRIMARY KEY (`id_pdg_not_cri_tra`),
  KEY `fk_pdg_not_cri_tra_nota_criterio_trabajo_cat_cri_eva_criter_idx` (`id_cat_cri_eva`),
  KEY `fk_pdg_not_cri_tra_nota_criterio_trabajo_pdg_gru_est_grupo__idx` (`id_pdg_gru_est`),
  CONSTRAINT `fk_pdg_not_cri_tra_nota_criterio_trabajo_cat_cri_eva_criterio1` FOREIGN KEY (`id_cat_cri_eva`) REFERENCES `cat_cri_eva_criterio_evaluacion` (`id_cat_cri_eva`),
  CONSTRAINT `fk_pdg_not_cri_tra_nota_criterio_trabajo_pdg_gru_est_grupo_es1` FOREIGN KEY (`id_pdg_gru_est`) REFERENCES `pdg_gru_est_grupo_estudiante` (`id_pdg_gru_est`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_not_cri_tra_nota_criterio_trabajo`
--

LOCK TABLES `pdg_not_cri_tra_nota_criterio_trabajo` WRITE;
/*!40000 ALTER TABLE `pdg_not_cri_tra_nota_criterio_trabajo` DISABLE KEYS */;
/*!40000 ALTER TABLE `pdg_not_cri_tra_nota_criterio_trabajo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_obs_observacion`
--

DROP TABLE IF EXISTS `pdg_obs_observacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_obs_observacion` (
  `id_pdg_obs` int(11) NOT NULL AUTO_INCREMENT,
  `descripción_pdg_obs` varchar(45) NOT NULL,
  `fecha_pdg_obs` datetime NOT NULL,
  `ultima_modificacion_pdg_obs` datetime DEFAULT NULL,
  `id_gen_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_pdg_obs`),
  KEY `fk_pdg_obs_observacion_gen_usuario1_idx` (`id_gen_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_obs_observacion`
--

LOCK TABLES `pdg_obs_observacion` WRITE;
/*!40000 ALTER TABLE `pdg_obs_observacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `pdg_obs_observacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_per_perfil`
--

DROP TABLE IF EXISTS `pdg_per_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_per_perfil` (
  `id_pdg_per` int(11) NOT NULL AUTO_INCREMENT,
  `tema_pdg_per` varchar(500) NOT NULL,
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `id_cat_ctg_tra` int(11) NOT NULL,
  `fecha_creacion_per` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pdg_per`),
  KEY `fk_pdg_per_perfil_reference_pdg_gru_grupo_idx` (`id_pdg_gru`),
  KEY `fk_pdg_per_perfil_reference_cat_ctg_categoria_tra_gra_idx` (`id_cat_ctg_tra`),
  KEY `fk_pdg_per_perfil_reference_cat_sta_estado_idx` (`id_cat_sta`),
  KEY `fk_pdg_per_perfil_reference_cat_tpo_tra_gra_tipo_trabajo_gr_idx` (`id_cat_tpo_tra_gra`),
  CONSTRAINT `fk_pdg_per_perfil_reference_cat_ctg_categoria_tra_gra` FOREIGN KEY (`id_cat_ctg_tra`) REFERENCES `cat_ctg_tra_categoria_trabajo_graduacion` (`id_cat_ctg_tra`),
  CONSTRAINT `fk_pdg_per_perfil_reference_cat_sta_estado` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`),
  CONSTRAINT `fk_pdg_per_perfil_reference_cat_tpo_tra_gra_tipo_trabajo_gra` FOREIGN KEY (`id_cat_tpo_tra_gra`) REFERENCES `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`),
  CONSTRAINT `fk_pdg_per_perfil_reference_pdg_gru_grupo` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_per_perfil`
--

LOCK TABLES `pdg_per_perfil` WRITE;
/*!40000 ALTER TABLE `pdg_per_perfil` DISABLE KEYS */;
/*!40000 ALTER TABLE `pdg_per_perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_ppe_pre_perfil`
--

DROP TABLE IF EXISTS `pdg_ppe_pre_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_ppe_pre_perfil` (
  `id_pdg_ppe` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `tema_pdg_ppe` varchar(200) NOT NULL,
  `nombre_archivo_pdg_ppe` varchar(200) NOT NULL,
  `ubicacion_pdg_ppe` varchar(200) DEFAULT NULL,
  `fecha_creacion_pdg_ppe` datetime DEFAULT NULL,
  `id_gen_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_pdg_ppe`),
  KEY `fk_pdg_ppe_pre_perfil_gen_usuario1_idx` (`id_gen_usuario`),
  KEY `fk_pdg_ppe_pre_perfil_pdg_gru_grupo1_idx` (`id_pdg_gru`),
  KEY `fk_pdg_ppe_pre_perfil_cat_sta_estado1_idx` (`id_cat_sta`),
  KEY `fk_pdg_ppe_pre_perfil_cat_tpo_tra_gra_tipo_trabajo_graduaci_idx` (`id_cat_tpo_tra_gra`),
  CONSTRAINT `fk_pdg_ppe_pre_perfil_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`),
  CONSTRAINT `fk_pdg_ppe_pre_perfil_cat_tpo_tra_gra_tipo_trabajo_graduacion1` FOREIGN KEY (`id_cat_tpo_tra_gra`) REFERENCES `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`),
  CONSTRAINT `fk_pdg_ppe_pre_perfil_pdg_gru_grupo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_ppe_pre_perfil`
--

LOCK TABLES `pdg_ppe_pre_perfil` WRITE;
/*!40000 ALTER TABLE `pdg_ppe_pre_perfil` DISABLE KEYS */;
INSERT INTO `pdg_ppe_pre_perfil` VALUES (10,1,3,1,'Desarrollo de Sistema informático para la gestión de trabajo de graduación de la Escuela de Ingeniería de Sistemas Informáticos de la Universidad de El Salvador','Grupo1_2018_090620APA_Samples_V4.pdf','C:\\wamp\\www\\SIGPAD\\public\\Uploads\\PrePerfil\\Grupo1_2018_090620APA_Samples_V4.pdf','2018-06-24 21:06:20',3),(11,5,10,1,'Tema de preperfil','Anteproyecto.docx','Grupo2_2018_061117Anteproyecto.docx','2018-11-02 18:11:17',35),(12,5,12,2,'tema 1 de pre perfil','Defensa AP_ultima.pdf','Grupo2_2018_061143Defensa AP_ultima.pdf','2018-11-02 18:11:43',35);
/*!40000 ALTER TABLE `pdg_ppe_pre_perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_tra_gra_trabajo_graduacion`
--

DROP TABLE IF EXISTS `pdg_tra_gra_trabajo_graduacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_tra_gra_trabajo_graduacion` (
  `id_pdg_tra_gra` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `tema_pdg_tra_gra` varchar(200) NOT NULL,
  `id_cat_ctg_tra` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  PRIMARY KEY (`id_pdg_tra_gra`),
  KEY `fk_pdg_tra_gra_trabajo_graduacion_cat_tpo_tra_gra_tipo_trab_idx` (`id_cat_tpo_tra_gra`),
  KEY `fk_pdg_tra_gra_trabajo_graduacion_cat_ctg_tra_categoria_traba1` (`id_cat_ctg_tra`),
  KEY `fk_pdg_tra_gra_trabajo_graduacion_cat_sta_estado1_idx` (`id_cat_sta`),
  KEY `fk_pdg_tra_gra_trabajo_graduacion_gru_grupo_idx` (`id_pdg_gru`),
  CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_cat_ctg_tra_categoria_traba1` FOREIGN KEY (`id_cat_ctg_tra`) REFERENCES `cat_ctg_tra_categoria_trabajo_graduacion` (`id_cat_ctg_tra`),
  CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`),
  CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_cat_tpo_tra_gra_tipo_trabaj1` FOREIGN KEY (`id_cat_tpo_tra_gra`) REFERENCES `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`),
  CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_gru_grupo` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_tra_gra_trabajo_graduacion`
--

LOCK TABLES `pdg_tra_gra_trabajo_graduacion` WRITE;
/*!40000 ALTER TABLE `pdg_tra_gra_trabajo_graduacion` DISABLE KEYS */;
INSERT INTO `pdg_tra_gra_trabajo_graduacion` VALUES (1,1,1,'Desarrollo de Sistema informático para la gestión de trabajo de graduación de la Escuela de Ingeniería de Sistemas Informáticos de la Universidad de El Salvador',5,18);
/*!40000 ALTER TABLE `pdg_tra_gra_trabajo_graduacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_tri_gru_tribunal_grupo`
--

DROP TABLE IF EXISTS `pdg_tri_gru_tribunal_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_tri_gru_tribunal_grupo` (
  `id_pdg_tri_gru` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_tri_rol` int(11) NOT NULL,
  `id_pdg_dcn` int(11) NOT NULL,
  `id_pdg_gru` int(11) NOT NULL,
  PRIMARY KEY (`id_pdg_tri_gru`),
  KEY `fk_pdg_tri_gru_tribunal_grupo_pdg_tri_rol_tribunal_rol1_idx` (`id_pdg_tri_rol`),
  KEY `fk_pdg_tri_gru_tribunal_grupo_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  KEY `fk_pdg_tri_gru_tribunal_grupo_pdg_gru_grupo1_idx` (`id_pdg_gru`),
  CONSTRAINT `fk_pdg_tri_gru_tribunal_grupo_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`),
  CONSTRAINT `fk_pdg_tri_gru_tribunal_grupo_pdg_gru_grupo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`),
  CONSTRAINT `fk_pdg_tri_gru_tribunal_grupo_pdg_tri_rol_tribunal_rol1` FOREIGN KEY (`id_pdg_tri_rol`) REFERENCES `pdg_tri_rol_tribunal_rol` (`id_pdg_tri_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_tri_gru_tribunal_grupo`
--

LOCK TABLES `pdg_tri_gru_tribunal_grupo` WRITE;
/*!40000 ALTER TABLE `pdg_tri_gru_tribunal_grupo` DISABLE KEYS */;
INSERT INTO `pdg_tri_gru_tribunal_grupo` VALUES (2,3,2,1),(13,1,1,1);
/*!40000 ALTER TABLE `pdg_tri_gru_tribunal_grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdg_tri_rol_tribunal_rol`
--

DROP TABLE IF EXISTS `pdg_tri_rol_tribunal_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pdg_tri_rol_tribunal_rol` (
  `id_pdg_tri_rol` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_tri_rol` varchar(45) NOT NULL,
  PRIMARY KEY (`id_pdg_tri_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdg_tri_rol_tribunal_rol`
--

LOCK TABLES `pdg_tri_rol_tribunal_rol` WRITE;
/*!40000 ALTER TABLE `pdg_tri_rol_tribunal_rol` DISABLE KEYS */;
INSERT INTO `pdg_tri_rol_tribunal_rol` VALUES (1,'Asesor'),(2,'Coordinador'),(3,'Jurado');
/*!40000 ALTER TABLE `pdg_tri_rol_tribunal_rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `permission_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (0,13,7,'2018-06-12 10:59:51','2018-06-12 10:59:51'),(1,1,2,'2018-04-30 11:57:05','2018-04-30 11:57:05'),(4,1,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(5,3,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(6,4,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(7,5,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(8,6,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(9,7,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(10,8,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(11,9,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(12,10,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(13,11,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(14,12,6,'2018-05-02 10:28:23','2018-05-02 10:28:23'),(15,13,3,'2018-05-02 12:44:48','2018-05-02 12:44:48'),(16,14,6,'2018-05-20 17:58:04','2018-05-20 17:58:04'),(17,15,7,'2018-05-21 04:43:43','2018-05-21 04:43:43'),(19,16,3,'2018-06-02 14:22:22','2018-06-02 14:22:22'),(20,17,3,'2018-06-02 14:22:22','2018-06-02 14:22:22'),(21,16,7,'2018-06-03 16:40:10','2018-06-03 16:40:10'),(22,18,7,'2018-06-03 17:23:06','2018-06-03 17:23:06'),(23,19,7,'2018-06-03 17:23:06','2018-06-03 17:23:06'),(24,20,3,'2018-06-04 15:11:50','2018-06-04 15:11:50'),(25,21,3,'2018-06-04 15:27:18','2018-06-04 15:27:18'),(26,0,0,'2018-07-20 00:36:39','2018-07-20 00:36:39'),(27,23,8,'2018-08-03 21:08:10','2018-08-03 21:08:10'),(28,24,8,'2018-08-03 21:08:10','2018-08-03 21:08:10'),(29,25,8,'2018-08-03 21:08:10','2018-08-03 21:08:10'),(30,26,8,'2018-08-03 21:08:53','2018-08-03 21:08:53'),(31,27,8,'2018-08-03 21:08:53','2018-08-03 21:08:53'),(32,28,8,'2018-08-03 22:30:17','2018-08-03 22:30:17'),(33,29,8,'2018-08-04 00:25:08','2018-08-04 00:25:08'),(34,30,8,'2018-08-04 00:26:47','2018-08-04 00:26:47'),(35,22,3,'2018-08-07 01:32:09','2018-08-07 01:32:09'),(36,15,3,'2018-08-07 01:34:46','2018-08-07 01:34:46'),(37,23,3,'2018-08-07 01:36:32','2018-08-07 01:36:32'),(38,27,3,'2018-08-07 01:36:33','2018-08-07 01:36:33'),(39,23,9,'2018-10-29 10:48:06','2018-10-29 10:48:06'),(40,27,9,'2018-10-29 10:48:06','2018-10-29 10:48:06'),(41,28,9,'2018-10-29 10:48:06','2018-10-29 10:48:06'),(42,29,9,'2018-10-29 10:48:06','2018-10-29 10:48:06'),(43,30,9,'2018-10-29 10:48:06','2018-10-29 10:48:06'),(44,22,7,'2018-11-01 23:38:49','2018-11-01 23:38:49'),(47,31,7,'2018-11-02 00:55:55','2018-11-02 00:55:55'),(48,32,7,'2018-11-02 00:55:55','2018-11-02 00:55:55'),(49,41,7,'2018-11-02 21:57:13','2018-11-02 21:57:13'),(50,42,7,'2018-11-02 21:57:13','2018-11-02 21:57:13'),(51,42,2,'2018-11-02 21:57:26','2018-11-02 21:57:26'),(52,15,10,'2018-11-04 20:12:28','2018-11-04 20:12:28'),(53,35,10,'2018-11-04 20:12:28','2018-11-04 20:12:28'),(54,39,10,'2018-11-04 20:12:28','2018-11-04 20:12:28'),(55,40,10,'2018-11-04 20:12:28','2018-11-04 20:12:28'),(56,16,10,'2018-11-04 20:21:49','2018-11-04 20:21:49'),(57,20,7,'2018-11-04 20:26:35','2018-11-04 20:26:35'),(58,21,7,'2018-11-04 20:26:35','2018-11-04 20:26:35');
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_user`
--

DROP TABLE IF EXISTS `permission_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `permission_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_user`
--

LOCK TABLES `permission_user` WRITE;
/*!40000 ALTER TABLE `permission_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Ver Roles','rol.index','Permite al usuario ver el listado de roles que posee el sistema disponibles.','2018-04-30 16:33:59','2018-04-30 17:02:28'),(3,'Ver Usuarios','usuario.index','Permite ver el listado de usuarios del sistema','2018-05-02 16:14:09','2018-05-02 16:14:09'),(4,'Crear Usuarios','usuario.create','Permite registrar nuevos usuarios en el sistema.','2018-05-02 16:14:33','2018-05-02 16:14:33'),(5,'Modificar Usuarios','usuario.edit','Permite modificar los usuarios registrados del sistema.','2018-05-02 16:15:00','2018-05-02 16:15:00'),(6,'Eliminar Usuarios','usuario.destroy','Permite eliminar usuarios registrados del sistema.','2018-05-02 16:16:15','2018-05-02 16:16:15'),(7,'Crear Roles','rol.create','Permite registrar nuevos roles en el sistema.','2018-05-02 16:17:21','2018-05-02 16:17:21'),(8,'Modificar Roles','rol.edit','Permite modificar roles registrados en el sistema.','2018-05-02 16:17:48','2018-05-02 16:17:48'),(9,'Eliminar Roles','rol.destroy','Permite eliminar roles registrados en el sistema.','2018-05-02 16:22:52','2018-05-02 16:22:52'),(10,'Crear Permisos','permiso.create','Permite al Usuario registrar nuevos permisos dentro del sistema.','2018-05-02 16:25:06','2018-05-02 16:25:06'),(11,'Modificar Permisos','permiso.edit','Permite  al usuario modificar permisos registrados en el sistema.','2018-05-02 16:25:29','2018-05-02 16:25:29'),(12,'Ver Permisos','permiso.index','Permite ver el listado de permisos registrados en el sistema.','2018-05-02 16:26:25','2018-05-02 16:26:25'),(13,'Crear grupo Trabajo de graduación','grupotdg.create','Permite a los estudiantes que empiezan su proceso de trabajo de graduación conformar grupo con los estudiantes de su afinidad.','2018-05-02 16:45:27','2018-05-02 16:45:27'),(14,'Eliminar Permisos','permiso.destroy','Permite eliminar permisos del sistema.','2018-05-20 23:57:14','2018-05-20 23:57:14'),(15,'Ver grupos de trabajo de graduación','grupo.index','Permite ver el listado de todos los grupos de trabajo de graduación que se han creado , en esa misma vista se encuentran las opciones de ver el detalle, modificar ,aprobar/denegar  y  eliminar trabajos de graduación.','2018-05-21 10:41:01','2018-11-04 20:39:47'),(16,'Ver PrePerfiles','prePerfil.index','Esta opción permite ver el listado de preperfiles dependiendo el rol que accede.','2018-06-02 20:10:43','2018-06-02 20:10:43'),(17,'Crear PrePerfiles','prePerfil.create','Permite la creación de un preperfil , subir el documento y enviar para aprobación por parte del alumno.','2018-06-02 20:11:47','2018-06-02 20:11:47'),(18,'Aprobar Pre-Perfil','prePerfil.aprobar','Permite al administrador te trabajo de graduación aprobar  pre-perfiles de los grupos de trabajo de graduación.','2018-06-03 23:16:17','2018-06-03 23:16:17'),(19,'Rechazar Pre-Perfiles','prePerfil.rechazar','Permite al coordinador de trabajos de graduación rechazar los pre-perfiles enviados para aprobación de parte de lso grupos de trabajo de graduación.','2018-06-03 23:17:06','2018-06-03 23:17:06'),(20,'Eliminar Pre-Perfiles','prePerfil.destroy','Permite eliminar los preperfiles subidos por cada grupo de trabajo de graduación','2018-06-04 21:10:17','2018-06-04 21:10:17'),(21,'Modificar Pre-Perfil','prePerfil.edit','Permite a los grupos de trabajo de graduación modificar los preperfiles enviados siempre y cuando este no haya sido aprobado o rechazado.','2018-06-04 21:27:04','2018-06-04 21:27:04'),(22,'Crear grupo Trabajo de graduación','grupotdg.create','Permite a los estudiantes que empiezan su proceso de trabajo de graduación conformar grupo con los estudiantes de su afinidad.','2018-05-02 16:45:27','2018-05-02 16:45:27'),(23,'Ver Publicaciones de Trabajo de Graduación','publicacion.index','Permite ver el listado de todas las publicaciones de trabajo de graduación registradas en el sistema.','2018-07-20 06:26:52','2018-07-20 06:26:52'),(24,'Modificar Publicaciones','publicacion.edit','Permite actualizar los registros de publicaciones de trabajos de graduación','2018-07-20 06:32:40','2018-07-20 06:32:40'),(25,'Eliminar Publicaciones','publicacion.destroy','Permite eliminar publicaciones de trabajo de graduación.','2018-07-20 06:33:07','2018-07-20 06:33:07'),(26,'Crear Publicación de trabajo de graduación','publicacion.create','Permite la creación de nuevas publicaciones de trabajos de graduación','2018-07-20 06:34:42','2018-07-20 06:34:42'),(27,'Ver detalle de publicaciones','publicacion.show','Permite ver todos los documentos que froman parte de esa publicación de trabajo de graduación ademas de los colaboradores y el autor de la misma.','2018-07-21 08:28:56','2018-07-21 08:28:56'),(28,'Crear Documentos de Publicacion','documentoPublicacion.create','Permite crear documentos nuevos para una publicación de trabajo de graduación.','2018-07-21 09:31:51','2018-07-21 09:31:51'),(29,'Eliminar Documentos de una Publicacion','documentoPublicacion.destroy','Poder eliminar archivos y/o documentos de una publicacion','2018-08-04 00:24:47','2018-08-04 00:24:47'),(30,'Modificar Documentos de una Publicacion','documentoPublicacion.edit','Poder Modificar archivos de una publicación','2018-08-04 00:26:29','2018-08-04 00:26:29'),(31,'Modificar Grupo de Trabajo de Graduación','grupo.edit','Que el coordinador de trabajo de graduación pueda modificar los alumnos de los grupos de trabajo','2018-11-01 23:37:42','2018-11-01 23:37:42'),(32,'Eliminar Grupos de Trabajo','grupo.destroy','que el Coordinador de trabajo de Graduación pueda Eliminar los grupos de Trabajo.','2018-11-01 23:38:30','2018-11-01 23:38:30'),(33,'Ver Dashboard','dashboard.index','Ver Dashboard de Etapas Evaluativas','2018-11-02 21:44:58','2018-11-02 21:44:58'),(35,'Aprobar Perfil','perfil.aprobar','Permite aprobar perfiles de grupos de trabajos de graduación.','2018-11-02 21:47:23','2018-11-02 21:47:23'),(36,'Crear Perfiles','perfil.create','Permite crear un nuevo perfil','2018-11-02 21:48:00','2018-11-02 21:48:00'),(37,'Eliminar Perfiles','perfil.destroy','Permite eliminar perfiles','2018-11-02 21:48:48','2018-11-02 21:48:48'),(38,'Modificar Perfil','perfil.edit','Permite modificar perfiles','2018-11-02 21:49:25','2018-11-02 21:49:25'),(39,'Ver Perfiles','perfil.index','Permite ver los perfiles de los grupos de trabajo de graduación','2018-11-02 21:50:03','2018-11-02 21:50:03'),(40,'Rechazar Perfiles','perfil.rechazar','Permite rechazar perfiles de trabajos de graduación.','2018-11-02 21:50:42','2018-11-02 21:50:42'),(41,'Modificar Tribunal','tribunal.edit','Permite modificar los miembros del tribunal evaluador.','2018-11-02 21:51:52','2018-11-02 21:51:52'),(42,'Ver Tribunal','tribunal.index','Ver tribunal evaluador.','2018-11-02 21:52:19','2018-11-02 21:52:19');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pub_arc_publicacion_archivo`
--

DROP TABLE IF EXISTS `pub_arc_publicacion_archivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pub_arc_publicacion_archivo` (
  `id_pub_arc` int(11) NOT NULL AUTO_INCREMENT,
  `id_pub` int(11) NOT NULL,
  `ubicacion_pub_arc` varchar(200) NOT NULL,
  `fecha_subida_pub_arc` datetime DEFAULT NULL,
  `nombre_pub_arc` varchar(45) DEFAULT NULL,
  `descripcion_pub_arc` varchar(400) DEFAULT NULL,
  `ubicacion_fisica_pub_arc` varchar(100) DEFAULT NULL,
  `activo_pub_arc` int(11) DEFAULT '1',
  PRIMARY KEY (`id_pub_arc`),
  KEY `fk_pub_arc_publicacion_archivo_pub_publicacion1_idx` (`id_pub`),
  CONSTRAINT `fk_pub_arc_publicacion_archivo_pub_publicacion1` FOREIGN KEY (`id_pub`) REFERENCES `pub_publicacion` (`id_pub`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pub_arc_publicacion_archivo`
--

LOCK TABLES `pub_arc_publicacion_archivo` WRITE;
/*!40000 ALTER TABLE `pub_arc_publicacion_archivo` DISABLE KEYS */;
INSERT INTO `pub_arc_publicacion_archivo` VALUES (1,47,'C:\\wamp\\www\\SIGPAD\\public\\Uploads\\Publicaciones\\Codigo200407060731Portal Utec.pdf','2018-07-23 18:07:31','Portal Utec.pdf','test1','PENDIENTE',1);
/*!40000 ALTER TABLE `pub_arc_publicacion_archivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pub_aut_publicacion_autor`
--

DROP TABLE IF EXISTS `pub_aut_publicacion_autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pub_aut_publicacion_autor` (
  `id_pub_aut` int(11) NOT NULL AUTO_INCREMENT,
  `id_pub` int(11) NOT NULL,
  `id_gen_int` int(11) DEFAULT NULL,
  `nombres_pub_aut` varchar(45) DEFAULT NULL,
  `apellidos_pub_aut` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_pub_aut`),
  KEY `fk_pub_aut_publicacion_autor_pub_publicacion1_idx` (`id_pub`),
  KEY `fk_pub_aut_publicacion_autor_gen_int_integracion1_idx` (`id_gen_int`),
  CONSTRAINT `fk_pub_aut_publicacion_autor_gen_int_integracion1` FOREIGN KEY (`id_gen_int`) REFERENCES `gen_int_integracion` (`id_gen_int`),
  CONSTRAINT `fk_pub_aut_publicacion_autor_pub_publicacion1` FOREIGN KEY (`id_pub`) REFERENCES `pub_publicacion` (`id_pub`)
) ENGINE=InnoDB AUTO_INCREMENT=935 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pub_aut_publicacion_autor`
--

LOCK TABLES `pub_aut_publicacion_autor` WRITE;
/*!40000 ALTER TABLE `pub_aut_publicacion_autor` DISABLE KEYS */;
INSERT INTO `pub_aut_publicacion_autor` VALUES (1,1,NULL,'Mercedes Guadalupe','Alfaro Rodríguez'),(2,1,NULL,'Edgardo Enrique','Colon Sorto'),(3,1,NULL,'Ernesto Armando','Zavaleta Angel'),(4,2,NULL,'Silvia Esperanza','Montano Guandique'),(5,2,NULL,'Yesenia Marisol','Vigil Merino'),(6,3,NULL,'Lissette Carolina','Ayala'),(7,3,NULL,'Celia','Molina Guardado'),(8,3,NULL,'Elvira Virginia','Portillo Jovel'),(9,4,NULL,'Jhony Francy','Crúz Ventura'),(10,4,NULL,'Raúl Ernesto','Muñoz Arévalo'),(11,4,NULL,'Nestor Omar','Pérez Crúz'),(12,5,NULL,'Bladimir','Díaz Campos'),(13,5,NULL,'Sandra Guadalupe','Romero Hernández'),(14,5,NULL,'Rodrigo Ernesto','Vásquez Escalante'),(15,5,NULL,'Virginia Antonia','Velásquez Monjarás'),(16,6,NULL,'Arnoldo Inocencio','Rivas Molina'),(17,7,NULL,'Jose Roberto','Argueta Crúz'),(18,7,NULL,'Elmer Arturo','Carballo Ruiz'),(19,7,NULL,'Jose Alberto','Martínez Campos'),(20,7,NULL,'Mirna Angelica','Portillo Molina'),(21,8,NULL,'Edwar Leonel','Guardado Martínez'),(22,8,NULL,'Blanca Graciela','Mira Guevara'),(23,8,NULL,'Franklin Americo','Rivera Santos'),(24,8,NULL,'Cristian Mauricio','Rodríguez'),(25,9,NULL,'Marta Claudia','Castro Trigueros'),(26,9,NULL,'Oscar Alberto','Díaz Pineda'),(27,9,NULL,'Marvin Del Rosario','Ortiz Morales'),(28,9,NULL,'Lorena Abigail','Vanegas Ochoa'),(29,10,NULL,'Amada Vilma','Fuentes Garcia'),(30,10,NULL,'Maria Ivette','Ramos Santos'),(31,10,NULL,'Edwin David','Ruiz Choto'),(32,11,NULL,'Elsy Yanira','Bonilla Quezada'),(33,11,NULL,'Xenia Maria','Menjivar Alvarado'),(34,11,NULL,'Karla Regina','Pérez Hernández'),(35,11,NULL,'Eliza Eleonora','Quiñónez Cacao'),(36,12,NULL,'Ramón Antonio','López Leiva'),(37,12,NULL,'Evelin Carolina','Magaña Leon'),(38,12,NULL,'Cleotilde Del Rosario','Valencia'),(39,13,NULL,'Jenny Estebadita','Artiga Rivera'),(40,13,NULL,'Claudia Elizabeth','Campos Hernández'),(41,13,NULL,'Celina Margarita','Gutiérrez Flores'),(42,13,NULL,'Julie Aurora','Lazo Gutiérrez'),(43,14,NULL,'Alexander Ernesto','Ernesto Aguilera'),(44,14,NULL,'José Simeón','Pocasangre Zepeda´'),(45,14,NULL,'Giovanna Geraldine','Ulloa Carranza'),(46,14,NULL,'Ricardo Ivan','Valiente Dueñas'),(47,15,NULL,'Ricardo Antonio','Jiménez Rivas'),(48,15,NULL,'Sandra Jeannette','Vega Perez'),(49,15,NULL,'Delmira Eleticia','Vega Ruiz'),(50,16,NULL,'Carlos Ernesto','Avalos Panameño'),(51,16,NULL,'Edgar William','Castellanos Sánchez'),(52,16,NULL,'Katia Eliana','Crúz Martínez'),(53,16,NULL,'Haydee De La Paz','Santos Vásquez'),(54,17,NULL,'Alam Balmore','Aviles Muñoz'),(55,17,NULL,'Luis Antonio','Meléndez Mozo'),(56,17,NULL,'Ricardo Antonio','Quinteros Alemán'),(57,17,NULL,'Juan Alberto','Sánchez'),(58,18,NULL,'Gloria Lissette','Castillo Sánchez'),(59,18,NULL,'Maria Mercedes','Lara Mendez'),(60,18,NULL,'Juan Carlos','Miranda Guerra'),(61,18,NULL,'Carlos','Balmore Ortiz'),(62,19,NULL,'Cristian Rafael','Fuentes Serrano'),(63,19,NULL,'Melvin Leonardo','Landaverde Contreras'),(64,19,NULL,'Mauricio Ernesto','Morales Menjivar'),(65,19,NULL,'Joaquín Galileo','Soto Campos'),(66,20,NULL,'Claudia Isabel','Lainez Alvarez'),(67,20,NULL,'Amarildo Odir','Renderos'),(68,20,NULL,'Reyna Jeannette','Vides León'),(69,21,NULL,'Cesar Isaac','Chavez Figueroa'),(70,21,NULL,'Marlon Omar','Garcia Perlera'),(71,21,NULL,'Gerónimo Maximiliano','Rivera Martínez'),(72,22,NULL,'Edgar Manuel','Contreras Rosas'),(73,22,NULL,'Hernan Alexandr','Martínez Bonilla'),(74,22,NULL,'Estela Del Carmen','Mendez Blanco'),(75,22,NULL,'Julio Alberto','Rivas Cuellar'),(76,23,NULL,'Juan Ernesto','Asunción Umaña'),(77,23,NULL,'Raymundo Antonio','Chavez Alas'),(78,23,NULL,'Sonia Elizabeth','Gonzalez Chavarria'),(79,23,NULL,'Manuel Arturo','López Rivas'),(80,24,NULL,'Dinorah Beatriz','Barraza Otero'),(81,24,NULL,'Claudia Elizabeth','Lara Leiva'),(82,24,NULL,'Henry Ernesto','Mercado Flores'),(83,24,NULL,'Ivette Carolina','Lara Leiva'),(84,25,NULL,'Jose Felix','Mira Hernández'),(85,25,NULL,'Jaime Alfredo','Quintanilla Membreño'),(86,25,NULL,'Maritza Migdalia','Salinas Escobar'),(87,26,NULL,'Oscar Adonai','Alfaro Rodríguez'),(88,26,NULL,'Ricardo Ernesto','Angel Pérez'),(89,26,NULL,'William Edgardo','Gàmez Martínez'),(90,26,NULL,'Linda Jeannette','Ibarra Argueta'),(91,27,NULL,'Nestor Elias','Alberto Rodas'),(92,27,NULL,'Jacqueline Elizabeth','Saravia Machuca'),(93,27,NULL,'Ricardo Mauricio','Soto Aguilar'),(94,28,NULL,'Jaime Omar','Crúz Perez'),(95,28,NULL,'Nelly Lissette','Henriquez Flamenco'),(96,28,NULL,'Boris Alexander','Montano Navarrete'),(97,28,NULL,'Jaime Alexander','Rodas Castro'),(98,29,NULL,'Kelly Xiomara','Aguilar Flores'),(99,29,NULL,'Milton Edwin','Ayala Arevalo'),(100,29,NULL,'Juan Carlos','Gómez Martínez'),(101,29,NULL,'Ovidio','Villalobo Avalos'),(102,30,NULL,'Marlon Yury','Gonzalez Mancía'),(103,30,NULL,'Cesar Augusto','Gonzalez Rodríguez'),(104,30,NULL,'Marvin Ernesto','Granados Murcia'),(105,30,NULL,'Julian Federico','Muñoz Inestroza'),(106,31,NULL,'Silvia Elizabeth','Peraza Menendez'),(107,31,NULL,'Carla Maria','Rivas Díaz'),(108,31,NULL,'Erick Barnett','Rivera Carbajal'),(109,32,NULL,'Julio Enrique','Aguilar Vidal'),(110,32,NULL,'Lidia Xiomara','García Ramos'),(111,32,NULL,'Elyzadeth Ixchell','Iraheta Agreda'),(112,32,NULL,'Alejandra Mónica','Quintanilla Orellana'),(113,33,NULL,'Ana Cristela','Gutierrez Mendoza'),(114,33,NULL,'Carlos Roberto','Hernández Salomón'),(115,33,NULL,'Karl Hugo Edwin','Moran Guevara'),(116,34,NULL,'Julio Edgardo','Guzmán Flores'),(117,34,NULL,'Ana Mercedes','Mendoza Hernández'),(118,34,NULL,'Douglas Jose Gilberto','Sánchez Alas'),(119,35,NULL,'Marta Gladys','Roque Rivera'),(120,35,NULL,'Miriam Cidalia','Medrano López'),(121,35,NULL,'Mayra Alicia','González Quezada'),(122,36,NULL,'Oscar Audelino','Aguilar Santos'),(123,36,NULL,'Tania Beatriz','Funes Moreno'),(124,36,NULL,'Carlos Antonio','López Cortez'),(125,37,NULL,'Jaime Arturo','Carias Garcia'),(126,37,NULL,'Ricardo William','Garcia Carpio'),(127,37,NULL,'Sandra Patricia','Marroquin'),(128,38,NULL,'Franklin Francisco','Barahona Rosales'),(129,38,NULL,'Manuel Antonio','Ortiz Crúz'),(130,38,NULL,'Virna Yasminia','Urquilla Cuellar'),(131,39,NULL,'Julio Cesar','Portillo Jovel'),(132,39,NULL,'Juan Jose','Ramos Aguillon'),(133,39,NULL,'Ivania Lissette','Torres Climaco'),(134,39,NULL,'Jorge Marcelo','Torres Lipe'),(135,40,NULL,'Roberto Leonel','Gracias Ramos'),(136,40,NULL,'Fatima Lizeth','Rodríguez Erazo'),(137,40,NULL,'Maria Dolores','Serrano Pocasangre'),(138,41,NULL,'Norma Maria','Minero Castro'),(139,41,NULL,'Osmin Ernesto','Palacios Molina'),(140,42,NULL,'Eugenia Guadalupe','Aguiñada Crúz'),(141,42,NULL,'Juan Francisco','Cabrera Herrera'),(142,42,NULL,'Milagro De Maria','Salazar Iraheta'),(143,43,NULL,'Katy','Chavarria Menjivar'),(144,43,NULL,'Diana Janina','Cortez Martínez'),(145,43,NULL,'Dionisio Alexander','Gómez Peña'),(146,43,NULL,'Claudia Marta','Iraheta'),(147,44,NULL,'Aida Estela','Crúz Cardoza'),(148,44,NULL,'Tico Giovanni','Delcid Muñoz'),(149,44,NULL,'Jacqueline Ivette','Sánchez Reyes'),(150,45,NULL,'Rodrigo Armando','Alfaro Henriquez'),(151,45,NULL,'Luis Alberto','Ardon'),(152,45,NULL,'Elmer Ernesto','Cornejo Cañas'),(153,45,NULL,'Carlos Daniel','Ramirez Crúz'),(154,46,NULL,'Helga Elena','Alas Galdamez'),(155,46,NULL,'Marco Antonio','Duran'),(156,46,NULL,'Victor Manuel','Rodríguez Ortega'),(157,46,NULL,'José Ernesto','Solórzano López'),(158,47,NULL,'Yesenia Marisol','Vigil Merino'),(159,48,NULL,'Rudy Wilfredo','Chicas Villegas'),(160,48,NULL,'Hugo Ernesto','Contreras Ayala'),(161,48,NULL,'Ruth Patricia','Cortez Recinos'),(162,48,NULL,'Danny William','Gutierrez Recinos'),(163,49,NULL,'Bladimir','Díaz Campos'),(164,50,NULL,'Gil Ubaldo','Crúz Ramírez'),(165,50,NULL,'Ronal Waldemar','Lopez Rodríguez'),(166,50,NULL,'Luis Alejandro','Martínez Campos'),(167,50,NULL,'Raúl Ernesto','Montano Arias'),(168,51,NULL,'Adilia Margarita','Alarcón Perez'),(169,51,NULL,'Gabriela Iveth','Moran Hidalgo'),(170,52,NULL,'Joaquín Ernesto','Aguilar Landaverde'),(171,52,NULL,'Norman Giovanni','Gómez Rivas'),(172,52,NULL,'Jimmy Alexander','Ortiz Carpio'),(173,52,NULL,'Juan Francisco','Ramirez Rochac'),(174,53,NULL,'Luis Alberto','Crúz Orellana'),(175,53,NULL,'Marlon Giovanni','Martínez Perez'),(176,53,NULL,'Gloria Lissette','Rodríguez Marin'),(177,53,NULL,'Cosme Atenogenes','Roque Salmeron'),(178,54,NULL,'José Raul','Hernández Amaya'),(179,54,NULL,'Virginia De Los Ángeles','Salvador'),(180,55,NULL,'Amilcar Ernesto','Barrientos Aguilar'),(181,55,NULL,'Maria Del Carmen','Delgado Amaya'),(182,55,NULL,'Gloria Eugenia','Renderos Alvarado'),(183,55,NULL,'Daniel Ernesto','Vigil Sandoval'),(184,56,NULL,'Juan Carlos','Campos Rivera'),(185,56,NULL,'Walter Nelson','Escalante Erazo'),(186,56,NULL,'Magali Evelyn','López Perez'),(187,56,NULL,'Paula Agripina','López Fabian'),(188,57,NULL,'Carlos Humberto','Caceres Gutierrez'),(189,57,NULL,'Benjamín','Carpio Andrade'),(190,57,NULL,'Leidyn Jeannette','Cerén Castillo'),(191,57,NULL,'Carmen Helena','Gallardo Menjivar'),(192,58,NULL,'Nubia Glendaly','Orellana Ramírez'),(193,58,NULL,'Roxana Heide','Pérez Martínez'),(194,58,NULL,'Maria Magdalena','Vásquez Elías'),(195,59,NULL,'Rosa Maria','Monge Alvarenga'),(196,59,NULL,'Karla Patricia','Payes Aguilar'),(197,59,NULL,'Iliana Guadalupe','Sánchez Tobar'),(198,59,NULL,'Geraldina Elizabeth','Vásquez Ramirez'),(199,60,NULL,'Oscar Mauricio','Castillo Recinos'),(200,60,NULL,'Mirna Elizabeth','Perdomo Ramirez'),(201,60,NULL,'Sigfrido Alexander','Villeda Majano'),(202,61,NULL,'Herson Douglas','Crúz Peña'),(203,61,NULL,'Jessica Marily','Portillo Baires'),(204,61,NULL,'Jaime Roberto','Valdez Carranza'),(205,62,NULL,'Lissette Aracely','Martínez Chávez'),(206,62,NULL,'Patricia Guadalupe','Rodríguez Zepeda'),(207,62,NULL,'Zoila Elizabeth','Rubio Molina'),(208,62,NULL,'Idalia Yasmin','Sarmento Granados'),(209,63,NULL,'Alfredo Alcides','Aquino Aguilar'),(210,63,NULL,'Mario Antonio','Martínez Ramírez'),(211,63,NULL,'Julio Ernesto','Pérez Ramírez'),(212,64,NULL,'Alfredo Alcides','Aquino Aguilar'),(213,64,NULL,'Mario Antonio','Martínez Ramírez'),(214,64,NULL,'Julio Ernesto','Pérez Ramírez'),(215,65,NULL,'Karla Verónica','Amaya Urbina'),(216,65,NULL,'Jose Leopoldo','Madrid Turcios'),(217,65,NULL,'Francisco Javier','Nuñez Mejia'),(218,65,NULL,'Henry Membreño','Sandoval Sol'),(219,66,NULL,'Carlos Arnoldo','Pacheco Alas'),(220,66,NULL,'Ernesto Antonio','Perez Vides'),(221,66,NULL,'German Bladimir','Sánchez Chicas'),(222,67,NULL,'Marvin Rafael','Ancheta Cuellar'),(223,67,NULL,'Boris Benjamín','Avalos Avalos'),(224,67,NULL,'Nelson Vladimir De La Cruz','Ruiz'),(225,67,NULL,'Juan','Zaldivar Maradiaga'),(226,68,NULL,'Norma Vilma','García Pérez'),(227,68,NULL,'Claudia Evelyn','Hernández Alvarenga'),(228,68,NULL,'Carlos José','Hernández Hernández'),(229,68,NULL,'José Noe','Quintanilla Ruiz'),(230,69,NULL,'Mayra Lelys','Heríquez Serrano'),(231,69,NULL,'Magdalena','Quintanilla Velásquez'),(232,69,NULL,'Ada Violeta Beatriz','Ramires Molina'),(233,69,NULL,'Claudia Rissel','Ramírez Molina'),(234,70,NULL,'Xiomara Ivette','Aldana Pleitez'),(235,70,NULL,'Kathleen Ludmila','Gomez Gonzalez'),(236,70,NULL,'Rosaura Estela','Vásquez Pineda'),(237,71,NULL,'Iris Ivette','Cantor Pérez'),(238,71,NULL,'Silvia Yesenia','Cardoza Arriaga'),(239,71,NULL,'Jorge Luis','Crúz Ochoa'),(240,71,NULL,'Napoleon Ernesto','Lopez Espinoza'),(241,72,NULL,'Nelson Eduardo','Hernández German'),(242,73,NULL,'Carlos Arturo','Espinoza Ayala'),(243,73,NULL,'Christian Denis','Menendez Orantes'),(244,73,NULL,'David Ernesto','Quintanilla Ingles'),(245,73,NULL,'Rudvi Hermelinda','Ramírez Rivas'),(246,74,NULL,'Hilda Guadalupe','Carranza Rodríguez'),(247,74,NULL,'Karen Araceli','Juarez Alarcón'),(248,74,NULL,'Ricardo Alexander','Perez Mendez'),(249,74,NULL,'Rene Eduardo','Trejo Corado'),(250,75,NULL,'Cesar Emilio','Amaya Rauda'),(251,75,NULL,'Madhra Krisna','Aguilar Saravia'),(252,75,NULL,'Isis Estephany','Gonzalez Quintanilla'),(253,75,NULL,'Teresa Elizabeth','Lazo Herrera'),(254,76,NULL,'Jazmin Elizabeth','Anaya Morales'),(255,76,NULL,'Karina Lourdes','García Romero'),(256,76,NULL,'Juan Antonio','Lara Salazar'),(257,76,NULL,'Roxana Carolina','López'),(258,77,NULL,'Lissette Carolina','Acevedo Chicas'),(259,77,NULL,'William Medardo','Rodrigues Rosales'),(260,77,NULL,'Cecilia Guadalupe','Serrano Acosta'),(261,78,NULL,'Verónica Elizabeth','Mendizábal Henriquez'),(262,78,NULL,'Raul Antonio','Molina Alvarenga'),(263,78,NULL,'Elba','Monterrosa Cuellar'),(264,78,NULL,'Blanca Cecilia','Salmeron Cornejo'),(265,79,NULL,'Marvin Ronaldi','Ardon Quezada'),(266,79,NULL,'Jhony Mikel','Escobar Galdamez'),(267,79,NULL,'Ramon Reyes','Leiva Díaz'),(268,80,NULL,'Hilda Guadalupe','Cardoza Hernández'),(269,80,NULL,'Manlikova Ivanova','Meléndez Montoya'),(270,80,NULL,'Maria Idalia','Reyes Portillo'),(271,80,NULL,'Jorge Alberto','Romero Galindo'),(272,81,NULL,'Héctor Geovanny','Aguilar Merino'),(273,81,NULL,'Cesar Francisco','Claros Baires'),(274,81,NULL,'Griselda María Teresa','Duran Rivas'),(275,81,NULL,'Victor De Jesús','Ostorga Cartagena'),(276,82,NULL,'Whitman Alberto','Anaya Crúz'),(277,82,NULL,'Jorge Adalberto','Cortez Sánchez'),(278,82,NULL,'Juan Jose','Deras Ventur'),(279,82,NULL,'Carlos Arnoldo','Vásquez Hernández'),(280,83,NULL,'Juan Pablo','Campos Molina'),(281,83,NULL,'Roxana Ivette','Fernandez Alvarez'),(282,83,NULL,'Mario Aaron','Lopez Payes'),(283,83,NULL,'Wendy Jasmin','Villalobos Parada'),(284,84,NULL,'Cecilia Ivette','Ayala Gomez'),(285,84,NULL,'Cesar Rodrigo','Duran Garcia'),(286,84,NULL,'Rigoberto Edison','Palacios Martel'),(287,84,NULL,'Erika Del Carmen','Rosales Meardi'),(288,85,NULL,'Luis Fernando','Estrada Guillen'),(289,85,NULL,'Claudia Beatriz','Mejia Iraheta'),(290,85,NULL,'Ena Isabel','Mejia Reyes'),(291,85,NULL,'Jose Antonio','Serrano Pocasangre'),(292,86,NULL,'Maria Victoria','Lopez Zetino'),(293,86,NULL,'Rigoberto Antonio','Reyes Alvarenga'),(294,86,NULL,'Alma Jeannette','Sánchez Ramos'),(295,86,NULL,'Nelly Margarita','Santana Flores'),(296,87,NULL,'Melvin Adalberto','Crúz Crúz'),(297,87,NULL,'Milton David','Crúz Crúz'),(298,87,NULL,'Mario Roberto','Najarro Parada'),(299,87,NULL,'Danny Rolando','Villalta Palacios'),(300,88,NULL,'Luis Salvador','Barrera Mancia'),(301,88,NULL,'Ericka Fabiola','Henríquez Campos'),(302,88,NULL,'Daniel Ernesto','Tutila Hernández'),(303,89,NULL,'Alex Vladimir','Alvarado Arevalo'),(304,89,NULL,'Guillermo Oswaldo','Castillo Pineda'),(305,89,NULL,'Erick Giovanni','Perez Barahona'),(306,89,NULL,'Jose Alfredo','Vigil Hernández'),(307,90,NULL,'Ana Beatriz','Canjura Astorga'),(308,90,NULL,'Dina Jazmin','Chavarria Jiménez'),(309,90,NULL,'Ana Paulina','Montano Acevedo'),(310,91,NULL,'Felipe Ernesto','Aguilara Sánchez'),(311,91,NULL,'Sandra Cecilia','Alvarez Carabantes'),(312,91,NULL,'Brenda Guadalupe','Mancia Henriquez'),(313,91,NULL,'Sara Yamileth','Santamaría Serrano'),(314,92,NULL,'Luis Arnoldo','Carranza'),(315,92,NULL,'Gustavo Rodolfo Alexander','Rauda Perez'),(316,92,NULL,'Julio Cesar','Rico Alfaro'),(317,93,NULL,'Ramiro Alexander','Belloso Urvina'),(318,93,NULL,'Mirna Noemi','Mancía Rivera'),(319,93,NULL,'Oscar José','Morán Bautista'),(320,93,NULL,'Guadalupe Beatriz','Olmedo Portillo'),(321,94,NULL,'Carolina Sugey','Alfaro Chamorro'),(322,94,NULL,'Edwin Adonai','Monterrosa Portillo'),(323,94,NULL,'Claudia Lysette','Rivera Alvarado'),(324,94,NULL,'Marvin Fredy','Villalobos Martínez'),(325,95,NULL,'Kelly Guadalupe','Flores'),(326,95,NULL,'Karla Elizabeth Ivonne','Fuentes Guevara'),(327,95,NULL,'Lesbia María','Mancía Sandoval'),(328,95,NULL,'Ronald Oswaldo','Vásquez Rivera'),(329,96,NULL,'Jose Rafael','Burgos Batres'),(330,96,NULL,'Jose Raul','Lopez Madrid'),(331,96,NULL,'Mauricio Antonio','Perez Pineda'),(332,96,NULL,'Claudia Carolina','Platero Zepeda'),(333,97,NULL,'Dora Cristina','Gonzalez Hernández'),(334,97,NULL,'Sandra Patricia Elena','Ramos Rodríguez'),(335,97,NULL,'Judth Marisela','Reyes Hernández'),(336,97,NULL,'Carlos Alberto','Reyes'),(337,98,NULL,'Jorge Ernesto','Guevara Domínguez'),(338,98,NULL,'Cesar Armando','Menjivar Escobar'),(339,98,NULL,'Glenda Damaris','Orellana Arias'),(340,98,NULL,'Francisco Rafael','Rodríguez Escobar'),(341,99,NULL,'Ivan Santiago','Ramírez Gómez'),(342,100,NULL,'Sonia Elizabeth','Bonilla Rubio'),(343,100,NULL,'Concepción De','María Cardoza'),(344,100,NULL,'Cristi Dalila','Hernández Sánchez'),(345,100,NULL,'Carlos Alfredo','Monterrosa Surio'),(346,101,NULL,'Jorge Luis','Chacón Asencio'),(347,101,NULL,'Manuel De Jesús','Flores Villatoro'),(348,101,NULL,'Claudia Patricia','González Quintanilla'),(349,101,NULL,'Jorge Luis','Carlos Jiménez'),(350,102,NULL,'Emely Maricrúz','Chavez Vásquez'),(351,102,NULL,'Oscar Ricardo','Cuellar Rodríguez'),(352,102,NULL,'Jhonathan Alexis','Hernández Orellana'),(353,102,NULL,'Erick Ivan','López Rodríguez'),(354,103,NULL,'Silvia Susana','Hernández Osorio'),(355,103,NULL,'Jose Roberto','Guerrero Rivera'),(356,104,NULL,'Xochil','Aleman Pineda'),(357,104,NULL,'Alcides Antonio','Henriquez Contreras'),(358,104,NULL,'Elvis Moises','Martínez Perez'),(359,104,NULL,'Luis Alonso','Ventura Beltran'),(360,105,NULL,'Sonia Magali','Flores Martínez'),(361,105,NULL,'Josue Bani','Perez Figueroa'),(362,105,NULL,'Juan Jose','Pineda Galdamez'),(363,106,NULL,'Daniel','Henriquez Barahona'),(364,106,NULL,'David Israel','Hernández Martínez'),(365,106,NULL,'Roberto Carlos','Martínez'),(366,106,NULL,'Elias Santiago','Peralta Ortiz'),(367,107,NULL,'Rosa Yaneth','Deras Ventura'),(368,107,NULL,'Henry Rolando','Monterrosa Hernández'),(369,107,NULL,'Claudia Nohemi','Sosa Mejía'),(370,108,NULL,'Victor Hugo','Benitez Aleman'),(371,108,NULL,'Jorge Alexander','Cordero Mancia'),(372,108,NULL,'Farid Antonio','Perez Aldana'),(373,108,NULL,'Carlos Enrique','Vásquez Rodríguez'),(374,109,NULL,'Ezno Danilo','Flores Lagos'),(375,109,NULL,'Barbara Patricia','Fuentes Serrano'),(376,109,NULL,'Elmer Osvaldo','Reyes Hernández'),(377,109,NULL,'Katya Karolyna','Rodríguez Rivas'),(378,110,NULL,'Adonay Edgardo','Escalante Santos'),(379,110,NULL,'Juan Antonio','Escobar Vidad'),(380,110,NULL,'Monica Alejandra','Linares Rodríguez'),(381,110,NULL,'Leslie Carol','Rivas Hernández'),(382,111,NULL,'Ingrid Jeannette','Lara Amaya'),(383,111,NULL,'Norma Estela','López Ventura'),(384,111,NULL,'Jaime Ernesto','Ramirez Flores'),(385,112,NULL,'Carlos Alberto','Aragón Durán'),(386,112,NULL,'Dennis Remberto','Penado Mozo'),(387,112,NULL,'Mario Rene','Rivas Dmoniguez'),(388,113,NULL,'Daniel Stanley','Carranza Miguel'),(389,113,NULL,'Nilson Giovanni','Nuñez Nuñez'),(390,114,NULL,'Carmen Elena','López Ramos'),(391,114,NULL,'Cindy Ivette','Martínez Díaz'),(392,114,NULL,'Jose Manuel','Martínez Arevalo'),(393,115,NULL,'Santiago Alejandro','Campos Amaya'),(394,115,NULL,'Cesar Ernesto','Castro Flores'),(395,115,NULL,'Alejandro Enrique','Escalante Mancia'),(396,116,NULL,'Reynaldo','Cerón Sosa'),(397,116,NULL,'Ivan Wilfredo','García Martínez'),(398,117,NULL,'Julio Alejandro','González Fernández'),(399,118,NULL,'Hector Giovanni','Salazar Garcia'),(400,118,NULL,'Sandra Lorena','Tobar Ramos'),(401,118,NULL,'Salvador Alfonso','Zepeda Blanco'),(402,119,NULL,'Darvin Rufino','Avelar Otero'),(403,119,NULL,'Carlos Alberto','Osorio Contreras'),(404,119,NULL,'Julio Nelson','Ortiz Hernández'),(405,119,NULL,'Douglas Fernando','Sandoval Funes'),(406,120,NULL,'Iliana Isabel','Ponce Hernández'),(407,120,NULL,'Edwin Salvador','Ramirez Rivera'),(408,121,NULL,'Mario Hernerth','Morazán Bonilla'),(409,121,NULL,'Carlos Alberto','Rivas Escobar'),(410,121,NULL,'Heydi Vanessa','Torres Escalante'),(411,121,NULL,'Mario Edgardo','Valdez Ortiz'),(412,122,NULL,'Alex Ernesto','Castillo Godoy'),(413,122,NULL,'Mario Alberto','Castro Machado'),(414,122,NULL,'Roberto Neftali','Panameño Hernández'),(415,122,NULL,'Willir Antony','Vásquez'),(416,123,NULL,'Xiomara Elizabeth','López Garciaguirre'),(417,123,NULL,'Gustavo Alfonso','Urrutia Rodríguez'),(418,124,NULL,'Patricia Yanette','Flamenco Urrutia'),(419,124,NULL,'Arturo Noel','Larios Rauda'),(420,124,NULL,'Mirna Del Carmen','Pleites Guidos'),(421,124,NULL,'Leslie Mariela','Zelayandia Guzman'),(422,125,NULL,'Eduardo Neftalí','Barrera Alvarez'),(423,125,NULL,'Jaime Alfredo','Beltrán Ramirez'),(424,125,NULL,'Mainor Ludobico','Cornejo Hasbun'),(425,125,NULL,'Roberto Antonio','Cienfuegos García'),(426,126,NULL,'Edgar Alexander','Cornejo Gómez'),(427,126,NULL,'Marcia Guadalupe','Lemus Majano'),(428,126,NULL,'Noel','Maradiaga Fernández'),(429,126,NULL,'Salvador Eloy','Monge Alvarenga'),(430,127,NULL,'Jose María','Garcia Oliva'),(431,127,NULL,'Billy Ronaldo','Pérez Rivera'),(432,127,NULL,'Carlos Francisco','Recinos Pérez'),(433,127,NULL,'Carlos Otoniel','Valdes Flores'),(434,128,NULL,'Edgardo Ulises','Díaz Tejada'),(435,128,NULL,'Karen Patricia','Jaime Herrera'),(436,128,NULL,'Moris Atilio','Mendez Mejia'),(437,128,NULL,'Salvador','Peña Mejia'),(438,129,NULL,'Daniel Alejandro','Coello Yanés'),(439,129,NULL,'Nely Mercedes','Pocasangre Gomez'),(440,129,NULL,'Sara Maria','Tadeo Morales'),(441,129,NULL,'Jasseline Danara','Torres Funes'),(442,130,NULL,'Jose Luis','Hernández Ayala'),(443,130,NULL,'Yeny Arely','Posada Ayala'),(444,130,NULL,'Vilma Flor','Torres Portillo'),(445,131,NULL,'Reina Roxana','Aragón Duran'),(446,131,NULL,'José Alberto','Argueta Girón'),(447,131,NULL,'Gerardo Adonay','Serrano Serrano'),(448,131,NULL,'Carlos Manuel','Vidal Figueroa'),(449,132,NULL,'Milton Eduardo','Carranza Valdes'),(450,132,NULL,'Roger Julian','Orellano Muñoz'),(451,132,NULL,'Roberto Ernique','Reyes Ramires'),(452,132,NULL,'Mirna Elizabeth','Rivas Ramos'),(453,133,NULL,'José David','Calderón Serrano'),(454,133,NULL,'René De Jesús','Montano Hernández'),(455,133,NULL,'José Adán','Nuñez Abarca'),(456,133,NULL,'Marco Antonio','Oviedo Martínez'),(457,134,NULL,'José Edgardo','Arteaga Valencia'),(458,134,NULL,'Oscar José Arnulfo','López Mendoza'),(459,134,NULL,'Issela Guadalupe','Mejía Beltran'),(460,134,NULL,'Guillermo Francisco','Tobar Alemán'),(461,135,NULL,'Denys Francisco','Calzada Peña'),(462,135,NULL,'Walter Edgar','Campos Mejia'),(463,135,NULL,'Roberto Carlos Serpas','Ortiz Lagos'),(464,136,NULL,'Regina Isabel','Bonilla Reyes'),(465,136,NULL,'Moises Ernesto','Godoy Sánchez'),(466,136,NULL,'Irma Elizabeth','Vides Rosales'),(467,137,NULL,'Juan Carlos','Jimenez Ventura'),(468,137,NULL,'Alma Dinora','Miranda Navarrete'),(469,137,NULL,'Veronica Isabel','Rosales Beltran'),(470,138,NULL,'Tomas Edgardo','Gomez Vásquez'),(471,138,NULL,'Roberto Antonio','Martínez Valles'),(472,138,NULL,'Paulo Cesar','Nuñez Soriano'),(473,138,NULL,'Jose Nelson','Zepeda Doño'),(474,139,NULL,'Ada Patricia','Lovo Zelaya'),(475,139,NULL,'Kevin Jaime','Rivera Flores'),(476,139,NULL,'Adán Mauricio','Romero López'),(477,140,NULL,'Rafael Enrique','Azucena'),(478,140,NULL,'Salvador Ernesto','Cabrera Molina'),(479,140,NULL,'Jose Fernando','Contreras Orellana'),(480,140,NULL,'Jose Edenilson','Ruiz Ramirez'),(481,141,NULL,'Melvin Ernesto','Alvarado Rivas'),(482,141,NULL,'Lisandro Miguel','Cerritos Alvarado'),(483,141,NULL,'Carlos Eduardo','Fuentes Romero'),(484,141,NULL,'Liliana Raquel','Guzman Rivera'),(485,142,NULL,'Santos Gilberto','Mancia Lopez'),(486,142,NULL,'Maria Ines','Medrano Lopez'),(487,142,NULL,'Julio David','Sura Martínez'),(488,142,NULL,'Luis Afredo','Vazquez Alvarado'),(489,143,NULL,'Delmy Elizabeth','Escobar Gonzalez'),(490,143,NULL,'Teresa De Los Angeles','Jimenez Granados'),(491,143,NULL,'Ever Israel','Menjivar Aviles'),(492,143,NULL,'Luis Ernesto','Serrano Guzman'),(493,144,NULL,'Benjamin Ernesto','Cardona Rodríguez'),(494,144,NULL,'Juan Carlos','Crúz Salazar'),(495,144,NULL,'Roldan Antonio','Gonzalez Quezada'),(496,144,NULL,'Roberto Antonio','Guillen Cortez'),(497,145,NULL,'Jeannette Mellany','Aguilar Zepeda'),(498,145,NULL,'Christian Alexander','Martínez Arteaga'),(499,145,NULL,'Elvis Mauricio','Ramirez'),(500,145,NULL,'Miguel Isaac','Sánchez Ramos'),(501,146,NULL,'Karla Vanessa','Campos Guadron'),(502,146,NULL,'Liliana Esmeralda','Gonzalez Romero'),(503,146,NULL,'Karina Lisseth','Ramirez Reyes'),(504,146,NULL,'Karla Mercedes','Villalta Paz'),(505,147,NULL,'Jose Benjamin','Aguilar Ascencio'),(506,147,NULL,'Victor Alonso','Deras Rosa'),(507,147,NULL,'Moises Enrique','Reyes Romero'),(508,148,NULL,'Ronald Fernando','Chanchan Rivas'),(509,148,NULL,'Nelson Eduardo','Najarro Alvarez'),(510,148,NULL,'Christian Alexander','Rodríguez Membreño'),(511,148,NULL,'Jose Orlando','Torres Aguiluz'),(512,149,NULL,'Christian Vladimir','Navarro Galindo'),(513,149,NULL,'Ulises Esau','Ramirez Castro'),(514,149,NULL,'Jose Antonio','Rivera Rodríguez'),(515,149,NULL,'Rene Mauricio','Ventura Villacorta'),(516,150,NULL,'Johanna Yamileth','Bnilla Guevara'),(517,150,NULL,'Wilmer Alfredo','Lopez Zepeda'),(518,150,NULL,'Patricia Carolina','Mejia Contreras'),(519,150,NULL,'Vladimir Alberto','Urrutia Hernández'),(520,151,NULL,'Gerson Levi','Molina Ceren'),(521,151,NULL,'Jaime Oscar','Portal Guillen'),(522,151,NULL,'Veronica Raquel','Rojas Alfaro'),(523,151,NULL,'Pedro Alberto','Vazquez Orellana'),(524,152,NULL,'Claudia Maribel','Orellana Rodríguez'),(525,153,NULL,'Melissa Guadalupe','Huezo Ventura'),(526,153,NULL,'Francisco Amadeo','Medina Gamez'),(527,153,NULL,'Lilian Guadalupe','Quintanilla Ortega'),(528,153,NULL,'Guadalupe De Jesus','Romero Martínez'),(529,154,NULL,'Rosa Haydee','Bonilla Quijano'),(530,154,NULL,'Ivania Margarita','Calderon Aviles'),(531,154,NULL,'Mario Alfonso','Crúz Vásquez'),(532,154,NULL,'Christian Lazaro','Palacios Galdamez'),(533,155,NULL,'Rene Rolando','Cuchillas Cañas'),(534,155,NULL,'Oscar Atilio','Hernández Peña'),(535,155,NULL,'Yuri Hildebrando','Mejia Merlos'),(536,155,NULL,'Hector Atilio','Silva Garcia'),(537,156,NULL,'Roberto José','Beltranán Mena'),(538,156,NULL,'Wilson Alberto','Flores Morales'),(539,156,NULL,'Marlon Alexis','Manzano Reyes'),(540,156,NULL,'William Stanley','Quijada Sandoval'),(541,157,NULL,'Alvaro Marin','Ayala Lopez'),(542,157,NULL,'Nelson Humberto','Gonzalez Arevalo'),(543,157,NULL,'Mayr Cristina','Lovato Urquilla'),(544,157,NULL,'Jose Antonio','Rivera Molina'),(545,158,NULL,'Beatriz Guadalupe','Alas'),(546,158,NULL,'Julio Henry','Martínez Cañenguez'),(547,158,NULL,'Gladys Angelica','Pineda Rodríguez'),(548,158,NULL,'Silvia Patricia','Rodríguez López'),(549,159,NULL,'Juan Carlos','Castro González'),(550,159,NULL,'Diego Ernesto','Goncález Marroquin'),(551,159,NULL,'Norman Alexis','Salinas Pineda'),(552,159,NULL,'Claudia Ivette','Sánchez Delgado'),(553,160,NULL,'Kerin Ivan','Luna Zelaya'),(554,160,NULL,'Susana Maryori','Rivera Portillo'),(555,160,NULL,'Robinson Vladimir','Ruiz Ramirez'),(556,160,NULL,'Axa Raquel','Torres Araujo'),(557,161,NULL,'Hector Eduardo','Chávez Monterrosa'),(558,161,NULL,'Carmen María','Gutierrez Campos'),(559,161,NULL,'Marcela Ariana','Iraheta Castaneda'),(560,161,NULL,'Javier Alfredo','Segura Valenzuela'),(561,162,NULL,'Ricardo Ernesto','Ayala Vásquez'),(562,162,NULL,'Emerson Alfredo','Cortez Argueta'),(563,162,NULL,'Juan Carlos','Guidos Juarez'),(564,162,NULL,'Cristiane Daniel','Ruiz Sanchéz'),(565,163,NULL,'Jairo Mercedes','Maldonado Martínez'),(566,163,NULL,'Luis Humberto','Pérez Flores'),(567,163,NULL,'Oliver José','Tobias Hernández'),(568,164,NULL,'Jose Antonio','Barrera Castro'),(569,164,NULL,'Rocio Belliny','Melgar Flores'),(570,164,NULL,'Nancy Marisol','Tutila Argueta'),(571,164,NULL,'Xenia Raquel','Vásquez Orellana'),(572,165,NULL,'Yancy Hazel','Chacon Orellana'),(573,165,NULL,'Karla Veronica','Mercado Araujo'),(574,165,NULL,'Hector Mauricio','Rivas Berrios'),(575,166,NULL,'Luis De Jesus','Rivera Vásquez'),(576,166,NULL,'Hector Vladimir','Rodríguez Meléndez'),(577,166,NULL,'Diana Carolina','Sánchez Garay'),(578,166,NULL,'Fatima Patricia','Sánchez Montoya'),(579,167,NULL,'Yaxché Elizabeth','Morales Palacios'),(580,167,NULL,'Johanna Margarita','Ramirez Nery'),(581,167,NULL,'Elisa Karelia','Rivera Hernández'),(582,167,NULL,'Astrid Aracely María De Los Ángeles','Salazar Herrera'),(583,168,NULL,'Karen Yaneth','Alegria Osegueda'),(584,168,NULL,'Sandra Isabel','Lopez Aria'),(585,168,NULL,'Julia Xiomara','Mira'),(586,168,NULL,'Oscar Armando','Pinto Molina'),(587,169,NULL,'Cesar Alejandro','Canton Garcia'),(588,169,NULL,'Marlon Marcelo','Martínez Matus'),(589,169,NULL,'Karla Patricia','Rodríguez Majano'),(590,169,NULL,'Douglas Alexander','Ruiz Abrego'),(591,170,NULL,'Cesar Mauricio','Lopez Portillo'),(592,170,NULL,'Irma Lissette','Mendoza Rodríguez'),(593,170,NULL,'Carlos Dennys','Osorio Guevara'),(594,170,NULL,'Victor Othsmaro','Salas Reyes'),(595,171,NULL,'Jose Roberto','Bonilla Chavez'),(596,171,NULL,'David Antonio','Ceren Rosales'),(597,171,NULL,'Marlyn Edenilson','Lara Orellana'),(598,171,NULL,'Elmer Rosemberg','Monzon Peña'),(599,172,NULL,'Karla Jeannette','Flamenco Barrios'),(600,172,NULL,'Juan Edgardo','Garcia Ramos'),(601,172,NULL,'Williams Neftali','Ramos Osorios'),(602,172,NULL,'Erick Salomon','Ticas Alfaro'),(603,173,NULL,'Jose Victor','Guardado Menjivar'),(604,173,NULL,'Edwin Alejandro','Martínez Linares'),(605,173,NULL,'Mauricio Alexander','Morales Quintanilla'),(606,174,NULL,'Marlon Guillermo','Andrade Ruiz'),(607,174,NULL,'Gerardo Heriberto','Aquino Pineda'),(608,174,NULL,'Flor De Maria','Meztizo Aguilar'),(609,174,NULL,'Luis Enrique','Monge'),(610,175,NULL,'Fernando David','Hernández Gómez'),(611,175,NULL,'Sandra Lissette','Ramirez Aragon'),(612,175,NULL,'Marina Griselda','Rivas Cortez'),(613,175,NULL,'Gloribel Marleny','Vigil Cabrera'),(614,176,NULL,'Victor Manuel','Guardado Sandoval'),(615,176,NULL,'Frank Stid','Peña Jorge'),(616,176,NULL,'Reinaldo Antonio','Pineda Crespín'),(617,176,NULL,'Rolando Josafat','Rodríguez Peñalva'),(618,177,NULL,'Leni Ricardo Della','Valle Bermudez'),(619,177,NULL,'Ligia Lorena','López Vargas'),(620,177,NULL,'Alberto Elenilson','Osorio Crúz'),(621,177,NULL,'Eduardo Rafael','Peña Ochoa'),(622,178,NULL,'Violeta Elizabeth','Flores Áleman'),(623,178,NULL,'Flor De Fatima','Guzmán Gómez'),(624,178,NULL,'Jessica Margarita','Jimenez'),(625,178,NULL,'Edgardo Abinadi','Mendoza Nuñez'),(626,179,NULL,'Ignacio','Alejo Siguenza'),(627,179,NULL,'Orlando Oswaldo','López Cortes'),(628,179,NULL,'Jaime Eduardo','Martínez Salmerón'),(629,179,NULL,'Nestor Wilfredo Mayen De','La Crúz'),(630,180,NULL,'Francis Lourdes','Menéndez Cornejo'),(631,180,NULL,'Jasmín Celina','Menjivar Escobar'),(632,180,NULL,'Mario Eduardo','Reyes Crespo'),(633,180,NULL,'Rolando Efraín','Tejada Molina'),(634,181,NULL,'Moises Elias','Crúz López'),(635,181,NULL,'Jennie Xiomara','Granados Guevara'),(636,181,NULL,'Ángel José','Lizama Molina'),(637,181,NULL,'Ledwin Baudilio','Rivas Sorto'),(638,182,NULL,'Edward Enrique','Domínguez Guardado'),(639,182,NULL,'Juan Pablo','Escalante Membreño'),(640,182,NULL,'Ingrid Lisbeth','Moreno Mejía'),(641,183,NULL,'Wilmer Vladimir','Aguilar Alas'),(642,183,NULL,'José Alberto','Erazo Rivas'),(643,183,NULL,'Javier Armando','Marroquín Díaz'),(644,183,NULL,'Roxana Carolina','Martínez Alvarado'),(645,184,NULL,'Gloria Elizabeth','Ayala Ayala'),(646,184,NULL,'Mayela Beatriz','Campos Lopez'),(647,184,NULL,'Cristina Ivette','Cuellar Batres'),(648,184,NULL,'Liliana Maria','Flores Crúz'),(649,185,NULL,'José Humberto','Chávez Portillo'),(650,185,NULL,'Juan José','Molina Guadique'),(651,185,NULL,'Ruanda Maritza','Quinteros Romeros'),(652,185,NULL,'Melvín Oswaldo','Reyes Pineda'),(653,186,NULL,'Jonny Alexander','Bonilla Rojas'),(654,186,NULL,'Nancy Beatriz','Franco Bazán'),(655,186,NULL,'David Orlando','Menjivar Quijada'),(656,186,NULL,'Manuel Antonio','Ortez Canales'),(657,187,NULL,'Carlos Alberto','Alvarenga Molina'),(658,187,NULL,'Hermes Egokibelier','Basagoita Izaquirre'),(659,187,NULL,'Evelyn Aracely','Carranza Martínez'),(660,187,NULL,'Winston Stanley','Crúz Perla'),(661,188,NULL,'Rafael Edgardo','Castro Fuentes'),(662,188,NULL,'Ludwin Leonel','Mejia Climaco'),(663,188,NULL,'Francisco Norberto','Ramos Marquez'),(664,188,NULL,'Manuel Alberto','Zepeda Castaneda'),(665,189,NULL,'David Gerardo','Cartagena Quentanilla'),(666,189,NULL,'Jesús Amílcar','Chavarría Alfaro'),(667,189,NULL,'Hilsia Ivette','Hernández Reyes'),(668,189,NULL,'Oscar Mauricio','Rivera Machado'),(669,190,NULL,'Manuel Alejandro','Cea'),(670,190,NULL,'Roberto Alejandro','Mena Rivas'),(671,190,NULL,'Victor Manuel','Rivas Merlos'),(672,190,NULL,'Edgar Oswaldo','Sigüenza Alarcon'),(673,191,NULL,'Adan Enrique','Rodríguez Rivas'),(674,192,NULL,'Geraldine Elizabeth','Gonzales Alfaro'),(675,192,NULL,'Ronald Steeven','Pineda Portal'),(676,192,NULL,'Luis Ernesto','Valle Escobar'),(677,192,NULL,'Alfredo Steve','Vásquez Villalta'),(678,193,NULL,'Walter Arturo','Hidalgo Celarie'),(679,193,NULL,'Alfonso Lopez','Santa Maria'),(680,193,NULL,'Jose Ricardo','Martínez Ruano'),(681,193,NULL,'Jose Andre','Palacios Rivas'),(682,194,NULL,'Oscar Fabricio','Clavel Quijada'),(683,194,NULL,'Marvin Jose','Melara Zepeda'),(684,194,NULL,'Yansi Lisseth','Nuila Martínez'),(685,195,NULL,'Carlos Alonso','Caña Díaz'),(686,195,NULL,'Maximo Alexander','Funez Mejia'),(687,195,NULL,'Jorge Alberto Lopez','Santa Maria'),(688,195,NULL,'Raul Ernesto','Solorzano Castro'),(689,196,NULL,'Pablo Cesar','Aguilar Martínez'),(690,196,NULL,'Jose Luis','Colocho Romero'),(691,196,NULL,'Jose Mauricio','Lopez Zepeda'),(692,196,NULL,'William Ernesto','Vides Ortez'),(693,197,NULL,'Ricardo Amed','Guardado Hernández'),(694,197,NULL,'Mario Ernesto','Hercules Rubio'),(695,197,NULL,'Juan Carlos','Menjivasr Miranda'),(696,197,NULL,'Juan Carlos','Rodríguez Argueta'),(697,198,NULL,'Giovanny Francisco','Fuentes Fuentes'),(698,198,NULL,'Eduardo Ernesto','Lopez Leon'),(699,198,NULL,'Angela Vanessa','Recinos Landaverde'),(700,198,NULL,'Cindy Yamileth','Rivera Santos'),(701,199,NULL,'Pedro Jose','Flores Melara'),(702,199,NULL,'Consuelo Aminta','Orrego Gonzalez'),(703,199,NULL,'Efrain Alexander','Renderos Alfaro'),(704,199,NULL,'Daniel Edgardo','Rodríguez Rivera'),(705,200,NULL,'Jose Santos','Mejia Angel'),(706,200,NULL,'Fernando Emerson','Ortiz Baran'),(707,201,NULL,'Denis Aristides','Campos Escalante'),(708,201,NULL,'Esther Abigail','Flores Escobar'),(709,201,NULL,'Kenny Johamy','Garcia Huezo'),(710,201,NULL,'Victor Wilfredo','Garcia Torres'),(711,202,NULL,'Ernesto Alberto','Martínez Marin'),(712,202,NULL,'Mauricio Enrique','Martínez Ventura'),(713,202,NULL,'Javier Orlando','Miranda Menendez'),(714,202,NULL,'Douglas Orlando','Navarrete Reyes'),(715,203,NULL,'Alfredo Rigoberto','Estrada Romero'),(716,203,NULL,'Carlos Antonio','Goody Zepeda'),(717,203,NULL,'Diego Ignacio','Herrera Aguilar'),(718,203,NULL,'Oscar Armando','Mendoza Colocho'),(719,204,NULL,'Nelson Jonathan','Gomez Prudencio'),(720,204,NULL,'Victor Alfonso','Morales Gutierrez'),(721,204,NULL,'Blanca Maricela','Torres Pineda'),(722,205,NULL,'Adriana Arely','Aguilar Barrera'),(723,205,NULL,'Edward Ernesto','Mejia Hernández'),(724,205,NULL,'Claudia Esmeralda','Rodríguez Henriquez'),(725,205,NULL,'Emilio Jose','Velasquez Pacheco'),(726,206,NULL,'Criseida Mabel','Beltran Barraza'),(727,206,NULL,'Londiz Beatriz','Ramirez Arevalo'),(728,207,NULL,'Elio Enrique','Castellon Torres'),(729,208,NULL,'Escobar Monterrosa','Ricardo Enrique'),(730,208,NULL,'Guzman Osorio','Kary Yohani'),(731,208,NULL,'Ramos Andrade','Jose Francisco'),(732,208,NULL,'Urbina Pineda','Edwin Manuel'),(733,209,NULL,'Natalia Carolina','Aguirre Monge'),(734,209,NULL,'Sonia Guadalupe','Garcia Cabrera'),(735,210,NULL,'Emerson Enrique','Galvez Menjivar'),(736,210,NULL,'Jose Noe','Martínez Chaveez'),(737,210,NULL,'Raul Oswaldo','Martínez Chavez'),(738,210,NULL,'Dina Lily','Merino Ruiz'),(739,211,NULL,'Yeni Bedilia','Bonilla Angel'),(740,211,NULL,'Bruno Alberto','Gonzalez Crespin'),(741,211,NULL,'Karen Elvira','Peñare Aviles'),(742,211,NULL,'Edwin Ernesto','Rodríguez Bonilla'),(743,212,NULL,'Johanna Elizabeth','Cerritos Pacheco'),(744,212,NULL,'Tony Kevin','Guzman Castro'),(745,212,NULL,'Rosa Jazmin','Hilario Orellana'),(746,212,NULL,'Melvin Ramón','Morales'),(747,213,NULL,'Anabel Elizabeth','Amaya Maravilla'),(748,213,NULL,'Carlos Oswaldo','Martínez Henriquez'),(749,213,NULL,'Claudia Evelin','Ortega Barahona'),(750,214,NULL,'Juan Hector','Larios Alvarenga'),(751,214,NULL,'Vanessa','Meléndez Ascencio'),(752,214,NULL,'Ingris Grissel','Romero Alas'),(753,214,NULL,'Erick Rolando','Ventura Crúz'),(754,215,NULL,'Ernesto Josue','Carrillo Rivas'),(755,215,NULL,'Elmer Edgardo','Escobar Trinidad'),(756,215,NULL,'Josue Saul','Galdamez Díaz'),(757,215,NULL,'David Moises','Menjivar Duran'),(758,216,NULL,'Sandra Elizabeth','Orellanda Tobar'),(759,216,NULL,'Cesar Alexander','Palacios Reyes'),(760,216,NULL,'Erick Douglas','Tobar Lopez'),(761,216,NULL,'Oscar Orlando','Vásquez Matinez'),(762,217,NULL,'Erika Yamileth','Echeverria Gutierrez'),(763,217,NULL,'Mauricio Jose','Gamez Brito'),(764,217,NULL,'Ronald Maycon','Perez Miron'),(765,217,NULL,'Marcela Esmeralda','Perez Miron'),(766,218,NULL,'Rene Antonio','Flores Crúz'),(767,218,NULL,'Henry Alexander','Molina Martínez'),(768,218,NULL,'Jose Alejandro','Reyes Gomez'),(769,218,NULL,'Claudia Marlene','Sorto Uceda'),(770,219,NULL,'Roger Miguel','España Alfaro'),(771,219,NULL,'Eder Vladimir','Hernández Corpeño'),(772,219,NULL,'Katya Melissa','Ortiz Mejia'),(773,219,NULL,'Jorge Alberto','Villanueva Meléndez'),(774,220,NULL,'Maria Esperanza','Hernández Vargas'),(775,220,NULL,'Marta Yosely Orellana','De Zelaya'),(776,220,NULL,'Sandra Veronica','Rodríguez Domínguez'),(777,221,NULL,'Iris Ivette','Henriquez Alvarado'),(778,221,NULL,'Carlos Antonio','Meléndez Lopez'),(779,221,NULL,'David Aristides','Ochoa Ayala'),(780,221,NULL,'Lisandro Antonio','Rafaelano Colocho'),(781,222,NULL,'Emmanuel Antonio','Avelar Martínez'),(782,222,NULL,'Jose Ricardo','Perez Pozos'),(783,222,NULL,'William Enrique','Rivera Valle'),(784,222,NULL,'Samuel','Rodríguez Meléndez'),(785,223,NULL,'Rodolfo Alexander','Angel Contreras'),(786,223,NULL,'Liliana Lisbeth','Cabrera Gonzalez'),(787,223,NULL,'Oswaldo Edenilson','Callejas Ceron'),(788,223,NULL,'Jimmy Anthony','Ramirez Reyes'),(789,224,NULL,'Claudia Veronica','Perez Velasquez'),(790,224,NULL,'Mauro Rene','Merino Nolasco'),(791,224,NULL,'Noe Amilcar','Orellana Henriquez'),(792,225,NULL,'Hector','Peña Valencia'),(793,225,NULL,'Xiomara Guadalupe','Rodríguez Portillo'),(794,225,NULL,'Lilian Aracely','Santos Aquino'),(795,226,NULL,'Oscar Alcides','Castillo Villacorta'),(796,226,NULL,'Nelson Guillermo','Chicas Garcia'),(797,226,NULL,'Ricardo Antonio','Escobar Orellana'),(798,226,NULL,'Karen Beatriz','Medrano Calderon'),(799,227,NULL,'Giovanni Alexander','Alegria Peña'),(800,227,NULL,'Lilian Mercedes','Joaquín Hernández'),(801,227,NULL,'Kristian Rolando','Sánchez Martínez'),(802,228,NULL,'Ileana Marianela','Castro Rosales'),(803,228,NULL,'Claudia Elizabeth','Guzman Salgado'),(804,229,NULL,'Ricardo Antonio','Aguilar Rivera'),(805,229,NULL,'Denis Jonathan','Mendoza Mencos'),(806,229,NULL,'Miguel Josue','Tobias Rivas'),(807,230,NULL,'Salvador Alexander','Martínez Ramirez'),(808,230,NULL,'Edwin Romero','Rivas Díaz'),(809,230,NULL,'Rodrigo Alexander','Sandoval'),(810,231,NULL,'Orlando Augusto Martínez De','La Crúz'),(811,231,NULL,'Ivonne Lissette','Meléndez Landaverde'),(812,231,NULL,'Astrid Rigel','Merlos Anaya'),(813,231,NULL,'Carlos Humberto','Vásquez Lozano'),(814,232,NULL,'Emerson Joaquín','Minero Sánchez'),(815,232,NULL,'Caleb Rubén','Rodríguez Orozco'),(816,232,NULL,'Carlos Aarón','Romero Delgado'),(817,232,NULL,'Alber José','Romero Salgado'),(818,233,NULL,'Henry Alberto','Domínguez Vásquez'),(819,233,NULL,'Jenifer Eunice','Fernandez Peña'),(820,233,NULL,'Marvin Omar','Flores Peñate'),(821,233,NULL,'Roxana Nohemy','Meléndez Rivera'),(822,234,NULL,'Melissa Raquel','Aguilar Montes'),(823,234,NULL,'Manuel Ernesto','Reymundo Serrano'),(824,234,NULL,'Silvia Patricia','Rivera Fuentes'),(825,234,NULL,'Jairo Alexander','Urbina Valencia'),(826,235,NULL,'Magaly Elizabeth','Castillo Escobar'),(827,235,NULL,'David Isaac','Martínez Hernández'),(828,235,NULL,'Marvin Daniel','Rivera Alvarenga'),(829,235,NULL,'Jessica Carolina','Rivera Serrano'),(830,236,NULL,'Karina Lissette','Espinola Hernández'),(831,236,NULL,'Raul Antonio','Hernández Mojica'),(832,236,NULL,'Carlos Jose','Oviedo Hernández'),(833,236,NULL,'Josue Francisco','Ponce Perez'),(834,237,NULL,'Oscar Alejandro','Duarte Bonilla'),(835,237,NULL,'Elim','Guardado Cardoza'),(836,237,NULL,'Luis Guillermo','Paulino Panameño'),(837,237,NULL,'Erick Edenilson','Roque Díaz'),(838,238,NULL,'Carlos Alberto','Cañenguez Castro'),(839,238,NULL,'Mario Ernesto','Cañenguez Castro'),(840,238,NULL,'Billy Leopoldo','Hernández Balette'),(841,238,NULL,'Keny Lisseth','Hernández Ortez'),(842,239,NULL,'Carmen Alicia','Garcia Altuve'),(843,239,NULL,'Rene Alfredo','Garcia Monjaras'),(844,239,NULL,'Mario Alexis','Muños Martínez'),(845,239,NULL,'Jose Abel','Perez Lainez'),(846,240,NULL,'Abio Francisco','Mena Guillen'),(847,240,NULL,'Eily Emperatriz','Osorio Roscala'),(848,240,NULL,'Edwin Moises','Rivera Rivera'),(849,240,NULL,'Victor Manuel','Salmeron Ochoa'),(850,241,NULL,'Gloria Claribel','Aquino Flores'),(851,241,NULL,'Lisseth Veraliz','Deras Alvarado'),(852,241,NULL,'Daniel Ernesto','Lopez Garcia'),(853,241,NULL,'Orlando Ademir','Marciales Benitez'),(854,242,NULL,'Jose Edwin','Flores Lopez'),(855,242,NULL,'Salvador Mauricio','Gomez'),(856,242,NULL,'Jorge Ernesto','Lopez Alvarenga'),(857,242,NULL,'Christian Marvin','Martínez Perez'),(858,243,NULL,'Jesus Antonio','Bermudez Rivera'),(859,243,NULL,'Juan Emanuel','Perez Zuniga'),(860,244,NULL,'Rodrigo Antonio','Bazan Molina'),(861,244,NULL,'Jenny Ariela','Reyes Cartagena'),(862,244,NULL,'Afolfo Jose','Rivas Escobar'),(863,244,NULL,'Silvia Iveth','Vásquez Molina'),(864,245,NULL,'Cristian Oswaldo','Fuentes'),(865,245,NULL,'Josue Rogelio','Henriquez Benitez'),(866,245,NULL,'Angel Antonio','Montenegro Landaverde'),(867,246,NULL,'Carlos Fernando','Flores Arevalo'),(868,246,NULL,'Mario Ivan','Garza Salinas'),(869,246,NULL,'Nancy Elizabeth','Hernández'),(870,246,NULL,'Maria Isabel','Landaverde Pérez'),(871,247,NULL,'Sofia Yamileth','Castillo Medina'),(872,247,NULL,'Juan Carlos','García Alfaro'),(873,247,NULL,'Nelson Osvaldo','Guardado Peraza'),(874,247,NULL,'Juan Miguel','Pérez Pineda'),(875,248,NULL,'Nestor Geovanni','Cabrera Alvarenga'),(876,248,NULL,'Herbert Mauricio','Flores Guevara'),(877,248,NULL,'Reina Iveth','Osegueda Alegría'),(878,248,NULL,'Alejandra Ivonne','Rivera Tamayo'),(879,249,NULL,'Crúz Arely','Cortez López'),(880,249,NULL,'Jeysson Ricardo','López Sarmiento'),(881,249,NULL,'Diana Carolina','Martínez Orellana'),(882,249,NULL,'Jonathan Esaú','Torres Araujo'),(883,250,NULL,'Milton Wilfredo','Alfaro Pérez'),(884,250,NULL,'Martín Alexander','Espinal Fernández'),(885,250,NULL,'Josue David','Herrera'),(886,250,NULL,'Bladimir','Ramirez Ramirez'),(887,251,NULL,'Daniel Farid','Hernáandez Cortez'),(888,251,NULL,'Samuel Alexander','Pérez'),(889,251,NULL,'Emerson Enrique','Ventura Huezo'),(890,252,NULL,'Tanya Verónica','Cerón Díaz'),(891,252,NULL,'Nancy Del Carmen','Mejía Cordova'),(892,252,NULL,'Mayra Carolina','Mercado Laínez'),(893,252,NULL,'Abel Christian Alcides','Morales Báchez'),(894,253,NULL,'Amanda Elisa','Escobar Orellana'),(895,253,NULL,'Ligia Elena','Girón'),(896,253,NULL,'Tito Javier Francisco','Miguel Gladaméz'),(897,253,NULL,'Susana Carolina','Zaldívar Marroquín'),(898,254,NULL,'Orlando Miguel','Astorga'),(899,254,NULL,'Evelyn Roxana','Cruz Gavidia'),(900,254,NULL,'Jacqueline Lisbeth','Orantes Pérez'),(901,254,NULL,'Walter Vladimir','Orellana Aguilar'),(902,255,NULL,'Ricardo Alejandro','Batres Alfaro'),(903,255,NULL,'Carlos Roberto','Clavel Quijada'),(904,255,NULL,'Manuel De Jesus','Perez Hernadez'),(905,256,NULL,'Emersson Ernesto','Aparicio Garay'),(906,256,NULL,'Marvin Omar','Flores Alvarado'),(907,256,NULL,'Oscar Adolfo','Menjivar Peraza'),(908,256,NULL,'Oscar Elias','Penut Mejia'),(909,257,NULL,'Elman Bladimir','Ortiz Santamaría'),(910,257,NULL,'Juan Carlos','Portillo Beltran'),(911,257,NULL,'Geovany De Jesus','Quintanilla Martinez'),(912,257,NULL,'Ricardo Romeo','Ramos Recinos'),(913,258,NULL,'Karen Cecilia','Cornejo Rivas'),(914,258,NULL,'Anthony José','Huezo Delgado'),(915,258,NULL,'Carlos Emilio','Lainez Montoya'),(916,258,NULL,'Axell José','Tejada Calderon'),(917,259,NULL,'Ingrid Roxana','Alvarez Feusier'),(918,259,NULL,'Ever Alexander','Flamenco Leon'),(919,259,NULL,'Ever Vlardimir','Sanchez Gomez'),(920,260,NULL,'Marcela Jasmiz','Aldana Palacion'),(921,260,NULL,'Nestor William','Herrara Orellana'),(922,260,NULL,'Omar Alfredo','Ostorga Cartagena'),(923,260,NULL,'Juan Carlos','Serrano Mendoza'),(924,261,NULL,'José Eduardo','Andasol Reyes'),(925,261,NULL,'Ever Orlando','Lemus Lemus'),(926,261,NULL,'Walter Geovanni','Lopez Rivera'),(927,262,NULL,'Iris Lizeth','Chacon Escobar'),(928,262,NULL,'Juan Francisco','Flores Figueroa'),(929,262,NULL,'Jorge Esau','Mendez Campos'),(930,262,NULL,'David Alejandro','Perez'),(931,263,NULL,'Nadia Abigail','Cordero Ramirez'),(932,263,NULL,'Oscar Alexander','Garcia Reyes'),(933,263,NULL,'Sofia Ester','Jimenez Molina'),(934,263,NULL,'Erick Alexander','Ventura Chavez');
/*!40000 ALTER TABLE `pub_aut_publicacion_autor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pub_col_colaborador`
--

DROP TABLE IF EXISTS `pub_col_colaborador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pub_col_colaborador` (
  `id_pub_col` int(11) NOT NULL AUTO_INCREMENT,
  `id_gen_int` int(11) DEFAULT NULL,
  `nombres_pub_col` varchar(45) DEFAULT NULL,
  `apellidos_pub_col` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_pub_col`),
  KEY `fk_pub_col_colaborador_gen_int_integracion1_idx` (`id_gen_int`),
  CONSTRAINT `fk_pub_col_colaborador_gen_int_integracion1` FOREIGN KEY (`id_gen_int`) REFERENCES `gen_int_integracion` (`id_gen_int`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pub_col_colaborador`
--

LOCK TABLES `pub_col_colaborador` WRITE;
/*!40000 ALTER TABLE `pub_col_colaborador` DISABLE KEYS */;
INSERT INTO `pub_col_colaborador` VALUES (1,NULL,'Ulises','Agüero Arroyo'),(2,NULL,'Carlos','Alegria Alegria'),(3,NULL,'Lisette Carolina','Ayala De Hernández'),(4,NULL,'Mauricio Enrique','Ayala Joya'),(5,NULL,'Carlos Balmore','Ortiz'),(6,NULL,'Mario Alfredo','Cabrera'),(7,NULL,'Claudia Elizabeth','Campos Hernández'),(8,NULL,'Elmer Arturo','Carballo Ruiz'),(9,NULL,'Edgar William','Castellanos Sánchez'),(10,NULL,'Rudy Wilfredo','Chicas Villegas'),(11,NULL,'Jorge Mauricio','Coto'),(12,NULL,'Jhony Francy','Crúz'),(13,NULL,'Bladimir','Díaz Campos'),(14,NULL,'Oscar Alberto','Díaz Pineda'),(15,NULL,'Patricia Haydee','Estrada Carranza De López'),(16,NULL,'Rigoberto Antonio','Flores'),(17,NULL,'Carlos Ernesto','Garcia Garcia'),(18,NULL,'Carlos','Garcia Paredes'),(19,NULL,'Lizeth Carmeline','Gochez Sandoval De Peñate'),(20,NULL,'Cesar Augusto','Gonzalez Rodríguez'),(21,NULL,'Rene Américo','Hernández'),(22,NULL,'Boris','Hernández Buendía'),(23,NULL,'Jorge Enrique','Iraheta Tobias'),(24,NULL,'Jose Alberto','Martínez Campos'),(25,NULL,'Luis Alonso','Martínez Perdomo'),(26,NULL,'Guillermo','Mejía Díaz'),(27,NULL,'Roberto','Mendez'),(28,NULL,'Jose Roberto','Mendez Carranza'),(29,NULL,'Silvia Esperanza','Montano Guandique'),(30,NULL,'Boris Alexander','Montano Navarrete'),(31,NULL,'Angelica Maria','Nuila Novoa De Sánchez'),(32,NULL,'Ruddy','Orellana'),(33,NULL,'Marvin Del Rosario','Ortiz De Diaz'),(34,NULL,'Pedro Eliseo','Peñate Hernández'),(35,NULL,'Miguel Angel','Perez Ramos'),(36,NULL,'Eduardo Alonso','Pleitez Castro'),(37,NULL,'Julio Alberto','Portillo'),(38,NULL,'Arnoldo Inocencio','Rivas Molina'),(39,NULL,'Tania Torres','Rivera'),(40,NULL,'Oscar Alonso','Rodríguez Linares'),(41,NULL,'José María','Sánchez Cornejo'),(42,NULL,'Mario Luis','Sánchez Gonzales'),(43,NULL,'Volker','Turau'),(44,NULL,'Yesenia Marisol','Vigil Merino'),(45,NULL,'Albert','');
/*!40000 ALTER TABLE `pub_col_colaborador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pub_publicacion`
--

DROP TABLE IF EXISTS `pub_publicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `pub_publicacion` (
  `id_pub` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat_tpo_pub` int(11) NOT NULL,
  `id_gen_int` int(11) DEFAULT NULL,
  `titulo_pub` varchar(300) NOT NULL,
  `anio_pub` int(11) DEFAULT NULL,
  `correlativo_pub` int(11) DEFAULT NULL,
  `codigo_pub` varchar(45) DEFAULT NULL,
  `resumen_pub` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id_pub`),
  KEY `fk_gen_pub_publicacion_cat_tpo_pub_tipo_publicacion_idx` (`id_cat_tpo_pub`),
  KEY `fk_pub_publicacion_gen_int_integracion1_idx` (`id_gen_int`),
  CONSTRAINT `fk_gen_pub_publicacion_cat_tpo_pub_tipo_publicacion` FOREIGN KEY (`id_cat_tpo_pub`) REFERENCES `cat_tpo_pub_tipo_publicacion` (`id_cat_tpo_pub`),
  CONSTRAINT `fk_pub_publicacion_gen_int_integracion1` FOREIGN KEY (`id_gen_int`) REFERENCES `gen_int_integracion` (`id_gen_int`)
) ENGINE=InnoDB AUTO_INCREMENT=264 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pub_publicacion`
--

LOCK TABLES `pub_publicacion` WRITE;
/*!40000 ALTER TABLE `pub_publicacion` DISABLE KEYS */;
INSERT INTO `pub_publicacion` VALUES (1,1,NULL,'Diseño e implantación de un sistema traductor español-nahuat',1998,1,'199801',NULL),(2,1,NULL,'Mecanización de una metodología de priorización y mejora de procesos',1998,2,'199802',NULL),(3,1,NULL,'Diseño de software de soporte pedagógico para la carrera de Arquitectura',1999,1,'199901',NULL),(4,1,NULL,'Diseño e Implementación de un sistema de control administrativo para Secretaria de Bienestar Universitario',1999,2,'199902',NULL),(5,1,NULL,'Diseño de un sistema de información del fondo solidario para la familia microempresaria',2000,1,'200001',NULL),(6,1,NULL,'Software de soporte pedagógico para el desarrollo de los contenidos programáticos de educación básica',2000,2,'200002',NULL),(7,1,NULL,'Análisis_ Diseño y desarrollo de un sistema de infomación mecanisado para el registro y manejo de los expedientes clinicos de los pacientes del Hospital Rosales_ como una herramienta de apoyo para las investigaciones patologicas',2000,3,'200003',NULL),(8,1,NULL,'Diseño e implimentación de un sistema de control de pacientes y soporte estadistico para el Hospital Nacional de Maternidad',2000,4,'200004',NULL),(9,1,NULL,'Sistema de información para la administración de cementerio de la Alcaldia Municipal de San Salvador',2000,5,'200005',NULL),(10,1,NULL,'Desarrollo de un sistema informático para el control de programas de medicina preventiva del ministerio de salud y asistencia social enfocados a niños_ mujeres embarazadas y en edad fértil de las unidades de salud',2001,1,'200101',NULL),(11,1,NULL,'Desarrollo de un sistema informatico para el registro y control de tramites académicos de la Facultad de Ingenieria y Arquitecura de la UES a través de Internet',2001,2,'200102',NULL),(12,1,NULL,'Desarrollo de un sistema informático materno infantil para la fundación Seraphin',2001,3,'200103',NULL),(13,1,NULL,'Desarrollo de un sistema para el control penitenciario para uso de la dirección General la de centros penales de El Salvador',2001,4,'200104',NULL),(14,1,NULL,'Desarrollo de un sistema de información automatizado para areas administrativas y de gestión de proyectos de la asociación para el desarrollo integral de Tejutepeque (ADIT)',2001,5,'200105',NULL),(15,1,NULL,'Sistema de Información para la Administración y control de los centros de formación profesional de Fe y Alegría de El Salvador',2001,6,'200106',NULL),(16,1,NULL,'Desarrollo de un sistema para el área de Catastro que controle inmuebles y establecimientos para la alcaldía municipal de Mejicanos',2001,7,'200107',NULL),(17,1,NULL,'Desarrollo de un sistema de información de competencias y rendimiento deportivo para el instituto nacional de los Deportes de El Salvador',2001,8,'200108',NULL),(18,1,NULL,'Desarrollo de un modelo de aula virtual en la Facultad de Ingeniería y Arquitectura de la Universidad de El Salvador orientado a la formación tecnológica de profesionales del la micro y pequeña empresa salvadoreña basado en la plataforma de Internet',2001,9,'200109',NULL),(19,1,NULL,'Sistema de formación para el apoyo a la administración de material bibliográfico del sistema bibliotecario de la Universidad de El Salvador',2002,1,'200201',NULL),(20,1,NULL,'Sistema de información para la administración de competencias de disciplinas deportivas para personas con discapacidad mental_ desarrollado para el comité de Olimpiadas Especiales de El Salvador',2002,2,'200202',NULL),(21,1,NULL,'Desarrollo de un software para la administración remota de computadoras intercomunicadas con el protocolo TCP/IP',2002,3,'200203',NULL),(22,1,NULL,'Sistema Informático para la gestión de denuncias ambientales del Ministerio del Medio Ambiente y Recursos Naturales',2002,4,'200204',NULL),(23,1,NULL,'Modelo de comercio electrónico sobre una arquitectura cliente servido en Internet para pequeña mediana y gran empresa del sector formal de nuestro país',2002,5,'200205',NULL),(24,1,NULL,'Desarrollo de un sistema informático para la administración de publicaciones periódicas y usuarios para la red de Hemerotecas',2002,6,'200206',NULL),(25,1,NULL,'Desarrollo de un sistema informático para la administración de los expedientes y fechas clínicas de pacientes adultos que son atendidos en las clínicas – escuelas de la Facultad de Odontología de la Universidad de El Salvador',2002,7,'200207',NULL),(26,1,NULL,'Software de planificación nutricional y dietético como una herramienta que de soporte a los profesionales del área de nutrición',2002,8,'200208',NULL),(27,1,NULL,'Diagnostico del mercado laboral de El Salvador para los profesionales en informatica',2002,9,'200209',NULL),(28,1,NULL,'Sistema de información Georeferenciado sobre necesidades de Inversión en proyectos de desarrollo social para la fundacion salvadoreña de desarrollo y vivienda mínima (FUNDASAL)',2003,1,'200301',NULL),(29,1,NULL,'Creación de una bolsa de trabajo electrónica para la Universidad de El Salvador',2003,2,'200302',NULL),(30,1,NULL,'Sistema de información de activos fijos del Ministerio de Salud y Asistencia Social',2003,3,'200303',NULL),(31,1,NULL,'Sistema de información para el registro_ control y distribución de fondos propios ingresados a través de colectarías para el Hospital Rosales',2003,4,'200304',NULL),(32,1,NULL,'Desarrollo de un sistema de información para el area administrativa y de saludo como una herramienta de soporte para la investigación cientifica del centro de paralisis cerebral',2003,5,'200305',NULL),(33,1,NULL,'Desarrollo de un sistema de información para el sector pesquero artesanal de El Salvador',2003,6,'200306',NULL),(34,1,NULL,'Sistema de información para el registro de áreas criticas urbanas integrado a un sistema de información geográfico para las alcaldía de El Salvador',2003,7,'200307',NULL),(35,1,NULL,'Desarrollo de software de gestión medica de pediatría general',2003,8,'200308',NULL),(36,1,NULL,'Sistema de Información para la gestion administrativa de la Unidad de Emergencia del Hospital Nacional Benjamín Bloom',2003,9,'200309',NULL),(37,1,NULL,'Sistema de información para la elaboración de planes de entrenamiento para deportistas con retardo mental para las disciplinas deportivas de Olimpiadas Especiales El Salvador',2003,10,'200310',NULL),(38,1,NULL,'Desarrollo de un sistema informático de control en expedientes e inventario de medicamentos para la unidad departamental de salud de San Vicente',2003,11,'200311',NULL),(39,1,NULL,'Sistema de información para el apoyo a la administración de los proyectos y programas sociales impulsados por la secretaria Nacional de la Familia',2003,12,'200312',NULL),(40,1,NULL,'Software de apoyo a la enseñanza del lenguaje signado y la lectoescritura del idioma español para personas sordo mudas',2003,13,'200313',NULL),(41,1,NULL,'Sistema de Información geográfica de la Ciudad de San Vicente y sus alrededores',2004,1,'200401',NULL),(42,1,NULL,'Sistema informatico para el abastecimiento_ despacho y control de medicamentos e insumos del Hospital de niños Benjamín Bloom.',2004,2,'200402',NULL),(43,1,NULL,'Sistema informático para el registro de colección de fauna del parque zoológico nacional de El Salvador',2004,3,'200403',NULL),(44,1,NULL,'Sistema informatico de antecedentes penales y procesales para la direccion de centros penales del Ministerio de Gobernación',2004,4,'200404',NULL),(45,1,NULL,'Sistema informatico para el registro y hospitalizacion de pacientes para el Hospital Nacional de niños Benjamín Bloom',2004,5,'200405',NULL),(46,1,NULL,'Sistema informático para la gestión del desarrollo profesional de los docentes del ministerio de educación',2004,6,'200406',NULL),(47,1,NULL,'Web Service Interface and Architecture for accessing field bus system',2004,7,'200407',NULL),(48,1,NULL,'Investigación aplicada al area de inteligencia artificial y desarrollo de un sistema experto',2004,8,'200408',NULL),(49,1,NULL,'Metodo colaborativo de planificación estrategica en tecnologias de información y comunicaciones',2004,9,'200409',NULL),(50,1,NULL,'Desarrollo de un sistema informático para la administración del escalafón magisterial del Ministerio de Educación',2005,1,'200501',NULL),(51,1,NULL,'Sistema informático de apoyo a los programas de protección del Instituto Salvadoreño para el desarrollo integral de la niñez y la adolescencia',2005,2,'200502',NULL),(52,1,NULL,'Creación de un almacén de datos para la explotación y descubrimiento de patrones de enfermedades cancerigenas del Instituto del Cáncer de El Salvador',2005,3,'200503',NULL),(53,1,NULL,'Campus virtual de enseñanza para el sistema de post-grado de la Universidad de El Salvador',2005,4,'200504',NULL),(54,1,NULL,'Sistema informatico para la administración y control del hogar de niñas Natalia de Siman',2005,5,'200505',NULL),(55,1,NULL,'Sistema Integral de Administración Financiera para la alcaldía de Santa Tecla',2005,6,'200506',NULL),(56,1,NULL,'Sistema informatico mecanizado para el registro_ control y seguimiento de los proceso de la Unidad de adquisiciones y contrataciones institucionles (UACI) de la alcaldía municipal de ciudad Colon',2005,7,'200507',NULL),(57,1,NULL,'Sistema de información general para el costeo de derecho-habientes ingresados en el Hospital Militar',2005,8,'200508',NULL),(58,1,NULL,'Software aplicado a la educación basica de primer y segundo ciclo basada en el plan curricular del MINED',2005,9,'200509',NULL),(59,1,NULL,'Sistema de información como apoyo a la gestion ambiental realizada por la unidad de medio ambiente de la alcaldia de colon',2005,10,'200510',NULL),(60,1,NULL,'Diseño del centro de desarrollo e investigación en tecnologías de información y comunicaciones de la Facultad de Ingeniería y Arquitectura de la Universidad de El Salvador',2005,11,'200511',NULL),(61,1,NULL,'Desarrollo de un almacén de datos para integración y análisis de las diferentes fuentes de información sobre la atención a pacientes del hospital Nacional Rosales',2006,1,'200601',NULL),(62,1,NULL,'Sistema Informático de la Gestión de Compras para la Unidad de Adquisición y contratación Institucional del Ministerio de Medio Ambiente y Recursos Naturales',2006,2,'200602',NULL),(63,1,NULL,'Desarrollo de un portal web y una aplicación de Escritorio para la promoción del Turismo en El Salvador',2006,3,'200603',NULL),(64,1,NULL,'Sistema de información para la administración de estadísticas del subsector hidrocarburos de El Salvador',2006,4,'20064',NULL),(65,1,NULL,'Sistema de información para la administración de proyectos e integración de herramientas de comunicaciones para el consejo de investigaciones científicas de la Universidad de El Salvador',2006,5,'200605',NULL),(66,1,NULL,'Software educativo de apoyo a la enseñanza-aprendizaje de las matemáticas de tercer ciclo de Educación Básica de El Salvador',2006,6,'200606',NULL),(67,1,NULL,'Sistema de inventario_ mantenimiento de equipos y soporte técnico de la Universidad de El Salvador',2006,7,'200607',NULL),(68,1,NULL,'Sistema Informático para el control de mantenimiento preventivo programado y correctivo de maquinaria y equipo del hospital de niños Benjamín Bloom',2006,8,'200608',NULL),(69,1,NULL,'Sistema integrado de control de los activos informáticos para soporte técnico del Ministerio de Obras Publicas (SICAMOP)',2006,9,'200609',NULL),(70,1,NULL,'Software para el registro y control de la exportación de especies marinas para el centro de desarrollo de la Pesca y la Agricultura (CENDEPESCA)',2006,10,'200610',NULL),(71,1,NULL,'Software de control para niños con desnutrición moderada para la Organización vinculo de Amor',2006,11,'200611',NULL),(72,1,NULL,'Programación de Aplicaciones con dispositivos moviles',2006,12,'200612',NULL),(73,1,NULL,'Sistema para la administración de las adquisiciones a través de Ferias de Centros Escolares del Ministerio de Educación',2007,1,'200701',NULL),(74,1,NULL,'Desarrollo de un sistema de información integrado para la gestion de las adquisiciones y contrataciones de la Federación salvadoreña de futbol_ haciendo uso de las tecnologías orientadas a servicio',2007,2,'200702',NULL),(75,1,NULL,'Sistema para la gestion de donativos y brigadas de ayuda para la Universidad de El Salvador en situaciones de emergencia nacional',2007,3,'200703',NULL),(76,1,NULL,'Sistema de información para el control de inventarios y administración de procesos de compras de libre gestión del Hospital Nacional Zacamil',2007,4,'200704',NULL),(77,1,NULL,'Sistema de información geografico de costos de producción agrícola de El Salvador para el Ministerio de Agricultura y Ganaderia',2007,5,'200705',NULL),(78,1,NULL,'Sistema de información para la administración y control de archivo central de la Universidad de El Salvador',2007,6,'200706',NULL),(79,1,NULL,'Diseño de una metodología que permita implementar el protocolo de Internet versión 6 en empresas o instituciones con aplicaciones basadas en el protocolo TCP/IP Version 4',2007,7,'200707',NULL),(80,1,NULL,'Sistema informatico para la administración de terapias medicas en el instuto salvadoreño de rehabilitación de invalidos (ISRI)',2007,8,'200708',NULL),(81,1,NULL,'Sistema Informático de análisis territorial de riesgos en El Salvador para el servicio nacional de estudios territoriales (SNET)',2007,9,'200709',NULL),(82,1,NULL,'Sistema de Gestión de proceso de ejecución de la encuesta de Hogares de propositos múltiples',2007,10,'200710',NULL),(83,1,NULL,'Sistema de información geográfico orientado al apoyo de la inversión forestal para El Salvador',2007,11,'200711',NULL),(84,1,NULL,'Software para la gestión de conciliaciones y depuraciones bancarias institucional de la Universidad de El Salvador',2007,12,'200712',NULL),(85,1,NULL,'Sistema de administración de inventario para la dirección departamental del Ministerio de Educación de San Salvador',2007,13,'200713',NULL),(86,1,NULL,'Sistema Informático para la gestión de las adquisiciones y contrataciones de obras bienes y servicios de universidad de El Salvador',2007,14,'200714',NULL),(87,1,NULL,'Sistema Informático de apoyo a la evaluación institucional de la Universidad de El Salvador',2007,15,'200715',NULL),(88,1,NULL,'Investigación y diseño de una metodología que permite aplicar el ruteo de paquetes de datos en un prototipo que implemente la suite de protocolos IPV6',2007,16,'200716',NULL),(89,1,NULL,'Sistema de información para el control georeferencial a traves de la WEB de los proyectos desarrollados por caritas El Salvador',2008,1,'200801',NULL),(90,1,NULL,'Sistema informatico para la administración academica de la casa del joven de la alcaldía de Nejapa',2008,2,'200802',NULL),(91,1,NULL,'Sistema de información geografico para la gestion de proyecto de agua potable desarrollados por la ONGD Ingenieria sin fronteras',2008,3,'200803',NULL),(92,1,NULL,'Diseño de un sistema de información que brinde soporte a los procesos de Análisis de Indicadores Socioeconómicos de El Salvador que realiza el ministerio de economía (MINEC)',2008,4,'200804',NULL),(93,1,NULL,'Estudio y análisis sobre la informático forense en El Salvador',2008,5,'200805',NULL),(94,1,NULL,'Sistema de Información para el apoyo de la verificación y control de las obligaciones tributarias de las empresas del Municipio de Ilopango',2008,6,'200806',NULL),(95,1,NULL,'Sistema de Gestión de Estado Familiar para la Alcaldía Municipal de Santa Tecla',2008,7,'200807',NULL),(96,1,NULL,'Aplicación web de apoyo a la Gestión administrativa de la Unidad de Recursos Informáticos de la Facultad de Odontología de la Universidad de El Salvador',2008,8,'200808',NULL),(97,1,NULL,'Desarrollo del Sistema de Información para administración del Centro de Salud Dental de la Fundación para el Desarrollo de la Mujer (FUDEM)',2008,9,'200809',NULL),(98,1,NULL,'Sistema Informatico para el registro_ control y seguimiento de los proyectos realizados por la asociación comunitaria unida por el agua y la agricultura (ACUA)',2008,10,'200810',NULL),(99,1,NULL,'Sistema de información y Gestion de Denuncias a Miembros de la Policia en la Inspectoría General de la PNC',2008,11,'200811',NULL),(100,1,NULL,'Sistema Informático de apoyo a la Gestión administrativa de las Donaciones para las aldeas infantiles SOS (SISOS)',2008,12,'200812',NULL),(101,1,NULL,'Sistema Informático de Gestión de Incidentes de apelación para el Tribunal de Apelaciones de los Impuestos Internos y de Aduanas',2008,13,'200813',NULL),(102,1,NULL,'Sistema Informático para el apoyo a los procesos de compra_ administración y despacho de medicamentos e insumos médicos del Hospital Nacional Rosales de El Salvador',2008,14,'200814',NULL),(103,1,NULL,'Sistema de información mecanizado de apoyo a los gestiones de la Unidad Administrativa y Financiera del fondo universitario de protección de la Universidad de El Salvador',2008,15,'200815',NULL),(104,1,NULL,'Sistema de Información para la atención integral de pacientes con tuberculosis del Ministerio de Salud',2008,16,'200816',NULL),(105,1,NULL,'Software para gestión del recurso Humano en el hogar del paralisis cerebral Roberto Callejas Montalvo',2008,17,'200817',NULL),(106,1,NULL,'Sistema Informático para la Administración de los servicios de extención_ asistencia técnica especializada y capacitaciones en el area de transferencia de tecnologia del CENTA',2008,18,'200818',NULL),(107,1,NULL,'Sistema Informático de gestión hospitalaria para el Hospital Nacional de Maternidad \"Doctor Raul Arguello Escolán\"',2008,19,'200819',NULL),(108,1,NULL,'Software Pedagógico para la enseñanza del idioma ingles a estudiantes no videntes del Universidad de El Salvador',2008,20,'200820',NULL),(109,1,NULL,'Integración de la Universidad de El Salvador a la red academica Mundial de Usuarios Moviles Eduroam',2008,21,'200821',NULL),(110,1,NULL,'Sistema de información aplicado a travez de la Web para el monitoreo y asignación de actividades del personal de la Policia Nacional Civil de El Salvador',2008,22,'200822',NULL),(111,1,NULL,'Sistema de Información para la administración de las clinicas de la Asociación Nacional de El Salvador orden de Malta',2008,23,'200823',NULL),(112,1,NULL,'Mecanización de la administración de proyectos sociale para la ONG iniciatica social para la democración (ISD)',2008,24,'200824',NULL),(113,1,NULL,'Desarrollo de un portal Web para dar soporte organizacionl al programa olimpiadas especiales El Salvador',2008,25,'200825',NULL),(114,1,NULL,'Sistema Informaticos de soporte a la vigilancia epidemilogica de los pacientes pedriaticos con VIH/SIDA del centro de excelencia para niños con inmunodeficiencia (CENID)',2008,26,'200826',NULL),(115,1,NULL,'Sistema Informático para el manejo de inventario y activo fijo del Ministerio de Trabajo y Previsión Social',2008,27,'200827',NULL),(116,1,NULL,'Sistema de Información contable para la Unidad del fondo Universitario de protección de la Universidad de El Salvador (FUP-UES)',2009,1,'200901',NULL),(117,1,NULL,'Sistema Informático orientado a la Web para la Gestión Administrativa de la ONG Médicos por el Derecho a la Salud',2009,2,'200902',NULL),(118,1,NULL,'Sistema Informático para la enseñanza-aprendizaje de conceptos prematematicos para niños sordos de El Salvador',2009,3,'200903',NULL),(119,1,NULL,'Sistema de Administración de los servicios especiales de la dirección general de Correos con apoyo de dispositivos moviles',2009,4,'200904',NULL),(120,1,NULL,'Sistema Informático para la gestión de Inventario y Depreciación de Activo Fijo de Las Bodegas del ISTU',2009,5,'200905',NULL),(121,1,NULL,'Investigación Sistemática sobre la Implementación de Información de Datos y Sincronización de Información en los procesos de la cadena de abasto de la industria Salvadoreña',2009,6,'200906',NULL),(122,1,NULL,'Sistema de Información para el control académico y la gestión de donativos para la asociación proyecto Jesús.',2009,7,'200907',NULL),(123,1,NULL,'Sistema Informático para el control de las funciones que desarrolla el cuerpo de agentes metropolitanos de la alcaldía municipal de San Salvador',2009,8,'200908',NULL),(124,1,NULL,'Software de administración y seguimiento de la información del programa de desarrollo infantil de las aldeas infantiles S.O.S.',2009,9,'200909',NULL),(125,1,NULL,'Sistema Informático para la evaluación del desarrollo físico_ psicológico y social de los niños y del desempeño de las madres y tias de las aldeas infantiles SOS de Santa Tecla',2009,10,'200910',NULL),(126,1,NULL,'Sistema de Información colaborativo para la defensa del consumidor',2009,11,'200911',NULL),(127,1,NULL,'Sistema de Apoyo a las operaciones del departamento de Dosimetria del centro de investigaciones nucleares (CIAN)',2009,12,'200912',NULL),(128,1,NULL,'Sistema de Informacion para la gestion administrtiva de la seccion de probidad de la Corte Suprema de Justicia',2009,13,'200913',NULL),(129,1,NULL,'Sistema de Apoyo al proceso de enseñanza/aprendizaje de anatomía humana en la Facultad de Medicina de la Universidad de El Salvador',2009,14,'200914',NULL),(130,1,NULL,'Sistema Informático de Auditoria para el Instituto Salvadoreño de Fomento Cooperativo',2009,15,'200915',NULL),(131,1,NULL,'Sistema Infrmático para control de asignación y escrituración de tierra del istituto salvadoreño de transformación agraria',2009,16,'200916',NULL),(132,1,NULL,'Investigación de software libre y de código abierto para el desarrollo tecnológico en las PYMES de El Salvador',2009,17,'200917',NULL),(133,1,NULL,'Sistema Informático de apoyo para la planificación didactica para los docentes de eduación básica',2009,18,'200918',NULL),(134,1,NULL,'Sistema de Información de apoyo al proceso de la acuicultura para la asociación salvadoreña de desarrollo campesino (ASDEC)',2009,19,'200919',NULL),(135,1,NULL,'Sistema de soporte y asistencia técnica a usuarios y administración de los recursos tecnológicos del banco de Fomento Agropecuario',2009,20,'200920',NULL),(136,1,NULL,'Campus Virtual de la Universidad de El Salvador',2009,21,'200921',NULL),(137,1,NULL,'Sistema de Control Administrativo y Financiero para Centros de Desarrollo Integral (CDI\'S) Asociados a Compasión Internacional',2009,22,'200922',NULL),(138,1,NULL,'Diseño de una Metodología Aplicada que Permita el Uso de Redes Avanzadas en Investigación Científica y Tecnologica en la FIA',2009,23,'200923',NULL),(139,1,NULL,'Diseño de una Metodología de Administración de Riesgos de Tecnologías de Información y Comunicaciones para el Ministerio de Educación',2009,24,'200924',NULL),(140,1,NULL,'Sistema Informático como Herramienta de Apoyo a la Administración para el Manejo Integral de la Información del Recurso Humano en la Fundación para la Cooperación y el Desarrollo Comunal de El Salvador (CORDES)',2009,25,'200925',NULL),(141,1,NULL,'Sistema Informatico para la gestion de bodega y transporte de la oficina general de administracion del Ministerio de Agricultura y Ganaderia',2010,1,'201001',NULL),(142,1,NULL,'Sistema de Informacion estadistico para la mejora de procesos de atencion a pacientes del ISRI',2010,2,'201002',NULL),(143,1,NULL,'Aplicación Web para la gestion de clientes_ ventas y control de existencias de productos y servicios de la Unidad de Comercializacion del Centro Nacional de Tecnologia Agropecuaria y Forestal',2010,3,'201003',NULL),(144,1,NULL,'Aplicación Informatica para el apoyo a los procesos de registro y analisis de indicadores socioeconomicos de personas con discpacidad en el salvador',2010,4,'201004',NULL),(145,1,NULL,'Herramienta para el desarrollo asistido de diagramas de flujo',2010,5,'201005',NULL),(146,1,NULL,'Propuesta de una Metodología para certificación de Calidad tomando como base el \"CMMI\" para el desarrollo de software en El Salvador',2010,6,'201006',NULL),(147,1,NULL,'Sistema de información para el apoyo de las actividades referentes a la evaluación de daños y analisis de necesidades realizadas por la Crúz roja salvadoreña',2010,7,'201007',NULL),(148,1,NULL,'Diseño de una red convergente utilizando software de libre distribución y el protocolo SIP para la Facultad de Ingenieria y Arquitectura de la Universidad de El Salvador',2010,8,'201008',NULL),(149,1,NULL,'Sistema Informático de registro y seguimiento academico para educación básica del Centro Escolar Colonia San Antonio',2010,9,'201009',NULL),(150,1,NULL,'Sistema informatico para la gestion_ control y seguimiento de donaciones de la asociacion emiliani de El Salvador',2010,10,'201010',NULL),(151,1,NULL,'Sistema Informatico como herramienta de apoyo en los procesos de emision de solvencias estadisticas empresariales para la Digestic',2010,11,'201011',NULL),(152,1,NULL,'Monitor Personalizado de noticias y medios en la web 2.0',2010,12,'201012',NULL),(153,1,NULL,'Sistema Informatico de apoyo a la gestion de las capacitaciones del Ministerio de Hacienda',2010,13,'201013',NULL),(154,1,NULL,'Sistema de registro y control del historial medico de pacientes de la unidades de emergencias de los hospitales del sector publico de El Salvador',2010,14,'201014',NULL),(155,1,NULL,'Desarrollo de un Data Warehouse para el proceso de denuncias de la Defensoria del consumidor',2010,15,'201015',NULL),(156,1,NULL,'Sistema Informático para la correlación CITO-COLPO-HISTOLÓGICA del Problema Nacional de Prevención de Cancer Cérvico-Uterino',2010,16,'201016',NULL),(157,1,NULL,'Sistema Informático para la Administración y facturación de tasas e impuestos municipales de la alcaldía municipal de Cuscatancingo (AMC)',2010,17,'201017',NULL),(158,1,NULL,'Software de orientación vocacional para aspirantes de nuevo ingreso de la Universidad de El Salvador',2010,18,'201018',NULL),(159,1,NULL,'Sistema Informático de Apoyo a la gestión del talento del recurso humano de la cooperativa americana de remesas al exterior en centro america',2010,19,'201019',NULL),(160,1,NULL,'Mapa de tecnologías educativas de los centros educactivos públicos de El Salvador',2010,20,'201020',NULL),(161,1,NULL,'Estudio y análisis del uso de clusters tecnológicos como herramientas para potenciar la exportación de software en El Salvador',2010,21,'201021',NULL),(162,1,NULL,'Investigación de las herramientas de software utilizadas en la informática forense en El Salvador',2010,22,'201022',NULL),(163,1,NULL,'Sistema Informático de Administración y Prestación de Servicios Médicos en la Secretaria de Bienestar Universitario',2011,1,'201101',NULL),(164,1,NULL,'Sistema Informático para la gestion de camas hospitalarias en el area de cirugia del Hospital Nacional Rosales',2011,2,'201102',NULL),(165,1,NULL,'Sistema Informático para la administracion de recursos humanos de la facultad de odontologia de la Universidad de El Salvador',2011,3,'201103',NULL),(166,1,NULL,'Sistema Informatico para la administracion academica en el centro de enseñanza de idiomas extranjeros de la universidad de El Salvador',2011,4,'201104',NULL),(167,1,NULL,'Sistema Informatico para la administracion de recursos de apoyo del complejo educativo Walter A. Soundy',2011,5,'201105',NULL),(168,1,NULL,'Sistema Informatico de Gestion para capacitaciones del servicio social pasionista',2011,6,'201106',NULL),(169,1,NULL,'Sistema de sondeo de precios apoyado por tecnologias moviles para la defensoria del consumidor',2011,7,'201107',NULL),(170,1,NULL,'Sistema Informatico para la administracion y reabastecimiento de los inventarios de consumo y prestamos de la Alcaldia Municipal de Ayutuxpeteque',2011,8,'201108',NULL),(171,1,NULL,'Sistema Informatico de gestion de proyectos para la fundacion promotora de la competitividad de la micro y pequeña empresa',2011,9,'201109',NULL),(172,1,NULL,'Sistema Informatico para el registro y seguimiento de los servicios de asistencia legal y psicosocial para la unidad de familia_ niñez y adolecencia de la Procuraduria General de la Republica',2011,10,'201110',NULL),(173,1,NULL,'Sistema informatico para el monitoreo de programas y administracion de proyectos de la fundacion salvadoreña para la promocion social y desarrollo economico',2011,11,'201111',NULL),(174,1,NULL,'Sistema Informatico para la administracion del expediente academico en linea para la Facultad de Ingenieria y Arquitectura de la Universidad de El Salvador',2011,12,'201112',NULL),(175,1,NULL,'Sistema Informático de Gestión de Recursos Hifrogeológicos para la Unidad de Investigación e Hidrogeología de ANDA',2011,13,'201113',NULL),(176,1,NULL,'Sistema Informático para la administración de los becarios del proyecto residencia Universitaria de la Asociación Jovesolides del El Salvador',2011,14,'201114',NULL),(177,1,NULL,'Sistema Informático de apoyo a la formulación de propuestas de proyectos para la fundación circulo solidario de El Salvador',2011,15,'201115',NULL),(178,1,NULL,'Sistema de Información para el departamento de administración y finanzas del centro de desarrollo para la pesca y acuicultura',2011,16,'201116',NULL),(179,1,NULL,'Sistema Informático para el manejo de expedientes clinicos y citas de los pacientes del Instituto Salvadoreño de Rehabilitación de Inválidos',2011,17,'201117',NULL),(180,1,NULL,'Sistema Informático para la gestión de procesos de la unidad de transporte y combustible del ministerio de gobernación',2011,18,'201118',NULL),(181,1,NULL,'Sistema Informático para la administración y control de espedientes del centro de rehabilitación integral para la niñez y la adolescencia',2011,19,'201119',NULL),(182,1,NULL,'Sistema Informático de control presupuestario para la universidad de El Salvador',2011,20,'201120',NULL),(183,1,NULL,'Sistema Informático para la unidad de Adquisiciones y contrataciones institucional en la Procuraduría para la defensa de los derechos humanos',2011,21,'201121',NULL),(184,1,NULL,'Sistema Informático de Monitoreo y Control de los Proyectos en la Fundación para la Cooperación y Desarrollo Comunal en El Salvador',2011,22,'201122',NULL),(185,1,NULL,'Sistema de Información sobre expedientes de edificaciones culturales para la coordinación de inspecciones y licencias de obra de la Secretaría de la Cultura',2011,23,'201123',NULL),(186,1,NULL,'Sistema de Información para el control de solicitudes de trabajo en la Dirección Jurídica del Ministerio de Justicia y Seguridad Pública',2011,24,'201124',NULL),(187,1,NULL,'Sistema de Información para el área de hospitalización en el hospital nacional rosales',2011,25,'201125',NULL),(188,1,NULL,'Sistema Informatico para la Gestion del Expediente Clinico de los atletas del Instituto Nacional de los Deportes',2011,26,'201126',NULL),(189,1,NULL,'Sistema Informático para el control de servicios y procesos administrativos de las direcciones departamentales del ministerio de Educación',2012,1,'201201',NULL),(190,1,NULL,'Sistema Informático de Administracion de pares evaluados para la direccion nacional de educacion superior del ministerio de educacion',2012,2,'201202',NULL),(191,1,NULL,'Desarrollo de un prototipo de comunicación basado en tecnologia de voz ip para eol estado mayor conjunto de la Fuerza Armada de El Salvador.',2012,3,'201203',NULL),(192,1,NULL,'Sistema Informatico para el registro y control de expedientes de penas sustitutivas a carcel para la corte suprema de justicia.',2012,4,'201204',NULL),(193,1,NULL,'Sistema de Gestion documental de acuerdos de junta directiva para secretaria de facultad de medicina de la Universidad de El Salvador.',2012,5,'201205',NULL),(194,1,NULL,'Sistema Informatico para la Administracion de Censos Escolares desde el nivel de educacion de parvularia hasta educacion media para el ministerio de educacion.',2012,6,'201206',NULL),(195,1,NULL,'Desarrollo del sistema informatico de gestion y control de catastro para la alcaldia municipal de San Jose Villanueva.',2012,7,'201207',NULL),(196,1,NULL,'Sistema de administracion de activo fijo para la facultad de ingenieria y arquitectura de la Universidad de El Salvador',2012,8,'201208',NULL),(197,1,NULL,'Automatizacion del sistema de Registro y administracion de cooperativas para el instituto salvadoreño de fomento cooperativo.',2012,9,'201209',NULL),(198,1,NULL,'Sistema Informatico de apoyo a la produccion_ acondicionamiento y almacenamiento de semilla para la Unidad de Tecnologia de semilla del centro nacional de Tecnologia agropecuaria y Forestal.',2012,10,'201210',NULL),(199,1,NULL,'Sistema Informatico para una bolsa de trabajo para la facultad de Ingenieria y Arquitectura de la Universidad de El Salvador',2012,11,'201211',NULL),(200,1,NULL,'Sistema Informatico para la planificacion y gestion administrativa para el departamento de laboratorio clinico del hosital nacional Santa Teresa.',2012,12,'201212',NULL),(201,1,NULL,'Sistema Informatico de apoyo a los procesos del servicio de integracion laboral de la red iberoamericana de entidad de personas con discapacidad fisica.',2012,13,'201213',NULL),(202,1,NULL,'Sistema Informatico para el control de recursos investigativos de la division central de investigaciones de la pnc.',2012,14,'201214',NULL),(203,1,NULL,'Sistema Informatico de Produccion de la direccion de publicaciones e impresiones de la Secretaria de la Cultura.',2012,15,'201215',NULL),(204,1,NULL,'Sistema Informatico de soporte a la gestion administrativa para la secretaria de la Facultad de Ingenieria y Arquitectura de la Universidad de El Salvador.',2012,16,'201216',NULL),(205,1,NULL,'Sistema de Flujo de trabajo para el manejo de tramites de desarrollo urbano de la OPAMSS',2012,17,'201217',NULL),(206,1,NULL,'Sistema de Informacion de Distribucion y Administracion logistica de transporte para el Ministerio de Educacion.',2012,18,'201218',NULL),(207,1,NULL,'Sistema Informatico para la administracion de las asignaturas del Registro Academico de la Facultad de Ingenieria y Arquitectura de la Universidad de El Salvador.',2012,19,'201219',NULL),(208,1,NULL,'Sistema de Administracion bibliografico para la biblioteca naconal de El Salvador Francisco Gavidia',2012,20,'201220',NULL),(209,1,NULL,'Sistema Informatico de Registro Academico y entrega de Paquetes Escolares para el Centro Escolar Catolico Monseñor Esteban Alliet',2012,21,'201221',NULL),(210,1,NULL,'Sistema Informatico para la gestion de expedientes academico_ medico y militar de cadetes de la escuela militar Capitan general Gerardo Barrios.',2012,22,'201222',NULL),(211,1,NULL,'Sistema Informatico para la direccion de Planificacion del Ministerio de Educacion',2012,23,'201223',NULL),(212,1,NULL,'Sistema informatico para el control y mantenimiento de activos fijos de Care El Salvador',2013,1,'201301',NULL),(213,1,NULL,'Sistema Informatico de soporte a la gestion administrativa de la unidad de secretaria municipal de zacatecoluca',2013,2,'201302',NULL),(214,1,NULL,'Sistema Informatico para la gestion de eventos de los teatros nacionales de El Salvador para la secretaria de cultura de la presidencia.',2013,3,'201303',NULL),(215,1,NULL,'Sistema Informatico de apoyo a la gestion de los servicios de la direccion general del registro de asociaciones y fundaciones sin fines de lucro del ministerio de gobernacion.',2013,4,'201304',NULL),(216,1,NULL,'Sistema Informatico para el control y seguimiento de proyectos de la direccion general de ordenamiento forestal_ cuencas y riego.',2013,5,'201305',NULL),(217,1,NULL,'Desarrollo de un sistema informatico que apoye el control del plan del plan anual operativo de la administracion nacional de acueductos y alcantarillados',2013,6,'201306',NULL),(218,1,NULL,'Sistema informatico para el manejo de servicio de emergencia de la direccion general de proteccion civil',2013,7,'201307',NULL),(219,1,NULL,'Sistema de la administracion de insumos alimenticios para el area de alimentacion y dietas del hospital nacional Rosales',2013,8,'201308',NULL),(220,1,NULL,'Sistema informatico para la gestion de asesorias e inscripcion de cooperativas en el instituto salvadoreño de fomento cooperativo',2013,9,'201309',NULL),(221,1,NULL,'Sistema de registro y archivo de la documentacion de la facultad de ingenieria y arquitectura de la universidad de El Salvador',2013,10,'201310',NULL),(222,1,NULL,'Sistema informatico de recepcion y seguimiento de casos de atencion en el ministerio de salud de El Salvador',2013,11,'201311',NULL),(223,1,NULL,'Sistema informatico para el control de proyectos impulsados por la gerencia de educacion en ciencia y tecnologia del ministerio de educacion',2013,12,'201312',NULL),(224,1,NULL,'Sistema informatico de apoyo a la toma de decisiones para la administracion de la clinica asistencial corazon de Maria',2013,13,'201313',NULL),(225,1,NULL,'Sistema informatico para la administracion de expedientes deportivos y seguimiento de planes de entrenamiento del instituto nacional de los deportes',2013,14,'201314',NULL),(226,1,NULL,'Sistema informatico para la clasificacion de la zoologia de El Salvador para el museo de historia natural Saburo Hirao',2013,15,'201315',NULL),(227,1,NULL,'Sistema informatico para la planeacion_ organizacion y control de eventos del instituto nacional de los deportes de El Salvador',2013,16,'201316',NULL),(228,1,NULL,'Sistemas informaticos para la gestion de institutos nacionales',2013,17,'201317',NULL),(229,1,NULL,'Sistemas informaticos para la administracion de servicios medicos en la unidad de hemato-oncologia del hospital nacional Rosales',2013,18,'201318',NULL),(230,1,NULL,'Sistema informatico para la gestion de solicitudes tramitadas por los ciudadanos en la oficina de informacion y respuesta del ministerio de educacion',2013,19,'201319',NULL),(231,1,NULL,'Sistema informático para la gestión de conciliación y depuración bancarias de la unidad financiera de oficinas centrales de la Universidad de El Salvador (SICOBI)',2014,1,'201401',NULL),(232,1,NULL,'Sistema informatico para la planeacion y gestion de los procesos de servicio de mantenimiento del area de mantenimiento general_ nivel regional y local del Ministerio de salud(SIM)',2014,2,'201402',NULL),(233,1,NULL,'Sistema informatico de gestion y control de banco de leche humana para la red nacional hospitalaria_ centralizado en el hospital nacional especializado de maternidad',2014,3,'201403',NULL),(234,1,NULL,'Sistema informatico de registro academico para el instituto de ciencia y tecnologia aplicada de la universidad de El Salvador sede Chalatengango',2014,4,'201404',NULL),(235,1,NULL,'Sistema informatico para ayudar en los analisis de amenazas_ vulnerabilidad y escenarios de riesgos a la direccion general de proteccion civil dependencia del ministerio de gobernacion',2014,5,'201405',NULL),(236,1,NULL,'Sistema informatico de administracion de banco de sangre para el hospital nacional Rosales',2014,6,'201406',NULL),(237,1,NULL,'Sistema informatico para la gestion del recurso humano del instituto salvadoreño de rehabilitacion integral (ISRI)',2014,7,'201407',NULL),(238,1,NULL,'Sistema informatico de registro y consulta de imagenes medicas para el departamento de radiologia del hospital Nacional Rosales',2014,8,'201408',NULL),(239,1,NULL,'Sistema informatico para la planificacion_ gestion y control celular de mision cristiana ELIM de El Salvador',2014,9,'201409',NULL),(240,1,NULL,'Sistema informatico de control de bienes de larga duracion usando dispositivos moviles para el instituto nacional de pensiones de los empleados publicos (INPEP)',2014,10,'201410',NULL),(241,1,NULL,'Sistema informatico para la gestion del mejoramiento de la atencion a los pacientes de la especialidad de cardiologia del hospital Nacional Rosales (SICARDIOHNR)',2014,11,'201411',NULL),(242,1,NULL,'Desarrollo de un sistema informatico para la gestion administrativa de la unidad de personal en la direccion general de centros penales',2014,12,'201412',NULL),(243,1,NULL,'Sistema de monitoreo y evaluacion de actividades hospitalarias del ministerio de salud',2014,13,'201413',NULL),(244,1,NULL,'Sistema informatico para la gestion y presentacion interna de los procesos universitarios e instalaciones de interes de la UES',2014,14,'201414',NULL),(245,1,NULL,'Sistema informatico para la administracion de creditos de la Asociacion Cooperativa de Ahorro y Credito de la Federacion de Ingenieros y Arquitectos de El Salvador',2014,15,'201415',NULL),(246,1,NULL,'Sistema informático para la gestión de archivos e información de la Facultad de Ingeniería y arquitectura.',2015,1,'201501',NULL),(247,1,NULL,'Sistema Informático de apoyo a la gestión del diagnostico y planificación del Sistema de Gestión de Calidad de la academia Cristiana Nacional (SICACI)',2015,2,'201502',NULL),(248,1,NULL,'Sistema Informático para la administración de inventario de insumos del Instituto Salvadoreño de Turismo',2015,3,'201503',NULL),(249,1,NULL,'Sistema informático para la gestión del historial clínico perinatal para el Ministerio de Salud de El Salvador (SHCP)',2015,4,'201504',NULL),(250,1,NULL,'Sistema informático para la gestión y respaldo de documentos digitales de asociados en ACOFINGES de R.L.',2015,5,'201505',NULL),(251,1,NULL,'Sistema informático de gestión de imagenología digital del Ministerio de Salud',2015,6,'201506',NULL),(252,1,NULL,'Sistema informático para la gestión y control de donaciones de la red nacional de bancos de sangre.',2015,7,'201507',NULL),(253,1,NULL,'Sistema informático para apoyar el control y administración de capacitaciones impartidas por el área de formación virtual de la gerencia de Tecnologías Educativas del Ministerio de Educación.',2015,8,'201508',NULL),(254,1,NULL,'Sistema informatico de orientacion vocacional para los alumnos del noveno grado y tercer año de bachillerato del colegio salesiano Santa Cecila (SIOV)',2015,9,'201509',NULL),(255,1,NULL,'Sistema informatico para la administracion y el control de contratos publicos con proveedores del instituto salvadoreño de transformacion agraria (SINCOISTA)',2015,10,'201510',NULL),(256,1,NULL,'Sistema informatico para el monitoreo y evaluacion de Estandares de calidad en los establecimientos de salud del primer nivel de atencion del ministerio de salud de El Salvador',2015,11,'201511',NULL),(257,1,NULL,'Sistema Informatico para la gestion del historial medico del area de salud sexual y reproductiva y ginecologia',2015,12,'201512',NULL),(258,1,NULL,'Sistema Informatico de apoyo a la gestion de flujos de trabajo de las dependencias Administrativas del ministerio de salud',2015,13,'201513',NULL),(259,1,NULL,'Sistema informatico para la dispensarizacion de los pacientes de los equipos comunitarios de salud familiar del ministerio de Salud.',2015,14,'201514',NULL),(260,1,NULL,'Sistema Informatico de apoyo a la gestion de apoyo y monitoreo de servicios integrales de salud que proporcionan personal de enfermeria para contribuir a mejorar la calidad de atencion a pacientes de la red nacional de establecimientos de salud.',2015,15,'201515',NULL),(261,1,NULL,'Sistema Informatico para la emision de pasaportes provisionales de salvadoreños en el exterior.',2015,16,'201516',NULL),(262,1,NULL,'Sistema informatico para la gestion clinico pediatrico .',2015,17,'201517',NULL),(263,1,NULL,'Sistema Informatico para el control de consultas odontologicas para clinicas de primer y segundo nivel del ministerio de salud (SICMIS)',2015,18,'201518',NULL);
/*!40000 ALTER TABLE `pub_publicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_col_pub_colaborador_publicacion`
--

DROP TABLE IF EXISTS `rel_col_pub_colaborador_publicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_col_pub_colaborador_publicacion` (
  `id_rel_col_pub` int(11) NOT NULL AUTO_INCREMENT,
  `id_pub` int(11) NOT NULL,
  `id_pub_col` int(11) NOT NULL,
  `id_cat_tpo_col_pub` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_col_pub`),
  KEY `fk_rel_col_pub_colaborador_publicacion_pub_publicacion1_idx` (`id_pub`),
  KEY `fk_rel_col_pub_colaborador_publicacion_pub_col_colaborador1_idx` (`id_pub_col`),
  KEY `fk_rel_col_pub_colaborador_publicacion_cat_tpo_col_pub_tipo_idx` (`id_cat_tpo_col_pub`),
  CONSTRAINT `fk_rel_col_pub_colaborador_publicacion_cat_tpo_col_pub_tipo_c1` FOREIGN KEY (`id_cat_tpo_col_pub`) REFERENCES `cat_tpo_col_pub_tipo_colaborador` (`id_cat_tpo_col_pub`),
  CONSTRAINT `fk_rel_col_pub_colaborador_publicacion_pub_col_colaborador1` FOREIGN KEY (`id_pub_col`) REFERENCES `pub_col_colaborador` (`id_pub_col`),
  CONSTRAINT `fk_rel_col_pub_colaborador_publicacion_pub_publicacion1` FOREIGN KEY (`id_pub`) REFERENCES `pub_publicacion` (`id_pub`)
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_col_pub_colaborador_publicacion`
--

LOCK TABLES `rel_col_pub_colaborador_publicacion` WRITE;
/*!40000 ALTER TABLE `rel_col_pub_colaborador_publicacion` DISABLE KEYS */;
INSERT INTO `rel_col_pub_colaborador_publicacion` VALUES (1,1,23,2),(2,1,41,1),(3,2,2,2),(4,2,37,1),(5,3,35,2),(6,3,15,1),(7,4,18,1),(8,4,21,2),(9,5,19,2),(10,6,15,2),(11,6,42,1),(12,7,4,2),(13,7,31,1),(14,8,37,1),(15,9,37,1),(16,9,34,2),(17,10,22,1),(18,10,44,2),(19,11,3,2),(20,11,6,1),(21,12,37,1),(22,12,37,2),(23,13,25,1),(24,13,44,2),(25,14,16,1),(26,14,16,2),(27,15,27,2),(28,15,32,1),(29,16,37,1),(30,16,37,2),(31,17,22,1),(32,17,3,2),(33,18,21,1),(34,18,21,2),(35,19,29,2),(36,19,11,1),(37,20,37,1),(38,20,33,2),(39,21,17,1),(40,21,29,2),(41,22,19,2),(42,22,31,1),(43,23,19,2),(44,23,41,1),(45,24,28,1),(46,24,34,2),(47,25,23,1),(48,25,3,2),(49,26,33,2),(50,26,15,1),(51,27,3,2),(52,27,16,1),(53,28,23,1),(54,28,33,2),(55,29,24,2),(56,29,14,1),(57,30,28,2),(58,30,11,1),(59,31,23,1),(60,31,3,2),(61,32,34,2),(62,32,16,1),(63,33,14,1),(64,34,36,1),(65,34,3,2),(66,35,15,1),(67,35,29,2),(68,36,45,2),(69,36,41,1),(70,37,29,1),(71,37,31,2),(72,38,12,2),(73,38,37,1),(74,39,38,2),(75,39,17,1),(76,41,23,1),(77,42,41,1),(78,42,14,2),(79,43,36,1),(80,43,31,2),(81,44,38,2),(82,44,29,1),(83,45,15,1),(84,45,11,2),(85,46,37,1),(86,47,43,1),(87,48,17,1),(88,48,24,2),(89,49,1,1),(90,50,37,3),(91,51,33,3),(92,52,13,3),(93,53,38,3),(94,54,34,3),(95,55,17,3),(96,56,41,3),(97,57,15,3),(98,58,24,3),(99,59,14,3),(100,60,13,3),(101,61,24,3),(102,62,14,3),(103,63,33,3),(104,65,15,3),(105,66,37,3),(106,67,15,3),(107,68,26,3),(108,69,14,3),(109,70,34,3),(110,71,41,3),(111,72,37,3),(112,64,23,3),(113,73,26,3),(114,74,13,3),(115,75,16,3),(116,76,24,3),(117,76,33,4),(118,77,13,3),(119,78,37,3),(120,79,34,3),(121,80,37,3),(122,81,38,3),(123,82,44,3),(124,83,13,3),(125,84,26,3),(126,85,20,3),(127,86,37,3),(128,87,15,3),(129,88,34,3),(130,89,30,3),(131,90,15,3),(132,91,14,3),(133,92,13,3),(134,93,37,3),(135,94,13,3),(136,95,41,3),(137,96,33,3),(138,97,11,3),(139,98,29,3),(140,99,20,3),(141,100,11,3),(142,101,24,3),(143,102,8,3),(144,103,41,3),(145,104,16,3),(146,105,29,3),(147,106,44,3),(148,107,14,3),(149,108,37,3),(150,109,26,3),(151,110,23,3),(152,111,38,3),(153,112,29,3),(154,113,8,3),(155,114,41,3),(156,115,31,3),(157,116,7,3),(158,117,17,3),(159,118,7,3),(160,119,26,3),(161,120,28,3),(162,121,38,3),(163,122,20,3),(164,123,9,3),(165,124,34,3),(166,125,29,3),(167,126,37,3),(168,127,14,3),(169,128,11,3),(170,129,5,3),(171,130,15,3),(172,131,23,3),(173,132,41,3),(174,133,10,3),(175,134,33,3),(176,135,41,3),(177,136,13,3),(178,137,26,3),(179,138,34,3),(180,139,34,3),(181,140,7,3),(182,232,44,3),(183,244,20,3),(184,245,37,3),(185,246,41,3),(186,247,13,3),(187,248,41,3),(188,249,23,3),(189,250,13,3),(190,251,33,3),(191,252,13,3),(192,253,13,3),(193,254,5,3),(194,255,13,3),(195,256,44,3),(196,257,38,3),(197,258,41,3),(198,259,37,3),(199,260,14,3),(200,261,8,3),(201,262,13,3),(202,263,3,3);
/*!40000 ALTER TABLE `rel_col_pub_colaborador_publicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_dcn_cer_docente_certificacion`
--

DROP TABLE IF EXISTS `rel_dcn_cer_docente_certificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_dcn_cer_docente_certificacion` (
  `id_rel_dcn_cer` int(11) NOT NULL AUTO_INCREMENT,
  `id_dcn_cer` int(11) NOT NULL,
  `id_pdg_dcn` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_dcn_cer`),
  KEY `fk_rel_dcn_cer_docente_certificacion_dcn_cer_certificacione_idx` (`id_dcn_cer`),
  KEY `fk_rel_dcn_cer_docente_certificacion_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  CONSTRAINT `fk_rel_dcn_cer_docente_certificacion_dcn_cer_certificaciones1` FOREIGN KEY (`id_dcn_cer`) REFERENCES `dcn_cer_certificaciones` (`id_dcn_cer`),
  CONSTRAINT `fk_rel_dcn_cer_docente_certificacion_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_dcn_cer_docente_certificacion`
--

LOCK TABLES `rel_dcn_cer_docente_certificacion` WRITE;
/*!40000 ALTER TABLE `rel_dcn_cer_docente_certificacion` DISABLE KEYS */;
INSERT INTO `rel_dcn_cer_docente_certificacion` VALUES (1,2,1),(2,3,1),(3,4,1),(4,6,2),(5,1,2),(6,7,2),(7,5,3),(8,8,4);
/*!40000 ALTER TABLE `rel_dcn_cer_docente_certificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_est_pro_estudiante_proceso`
--

DROP TABLE IF EXISTS `rel_est_pro_estudiante_proceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_est_pro_estudiante_proceso` (
  `id_rel_est_pro` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat_pro` int(11) NOT NULL,
  `id_gen_est` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_est_pro`),
  KEY `fk_rel_est_pro_estudiante_proceso_cat_pro_proceso1_idx` (`id_cat_pro`),
  KEY `fk_rel_est_pro_estudiante_proceso_cat_sta_estado1_idx` (`id_cat_sta`),
  CONSTRAINT `fk_rel_est_pro_estudiante_proceso_cat_pro_proceso1` FOREIGN KEY (`id_cat_pro`) REFERENCES `cat_pro_proceso` (`id_cat_pro`),
  CONSTRAINT `fk_rel_est_pro_estudiante_proceso_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_est_pro_estudiante_proceso`
--

LOCK TABLES `rel_est_pro_estudiante_proceso` WRITE;
/*!40000 ALTER TABLE `rel_est_pro_estudiante_proceso` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_est_pro_estudiante_proceso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_idi_dcn_idioma_docente`
--

DROP TABLE IF EXISTS `rel_idi_dcn_idioma_docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_idi_dcn_idioma_docente` (
  `id_rel_idi_dcn` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_dcn` int(11) NOT NULL,
  `id_cat_idi` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_idi_dcn`),
  KEY `fk_rel_idi_dcn_idioma_docente_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  KEY `fk_rel_idi_dcn_idioma_docente_cat_idi_idioma1_idx` (`id_cat_idi`),
  CONSTRAINT `fk_rel_idi_dcn_idioma_docente_cat_idi_idioma1` FOREIGN KEY (`id_cat_idi`) REFERENCES `cat_idi_idioma` (`id_cat_idi`),
  CONSTRAINT `fk_rel_idi_dcn_idioma_docente_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_idi_dcn_idioma_docente`
--

LOCK TABLES `rel_idi_dcn_idioma_docente` WRITE;
/*!40000 ALTER TABLE `rel_idi_dcn_idioma_docente` DISABLE KEYS */;
INSERT INTO `rel_idi_dcn_idioma_docente` VALUES (1,1,1),(2,1,2),(3,2,2),(4,2,1),(5,3,1),(6,3,3),(7,4,4),(8,4,1),(9,5,1);
/*!40000 ALTER TABLE `rel_idi_dcn_idioma_docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_obs_gru_observacion_grupo`
--

DROP TABLE IF EXISTS `rel_obs_gru_observacion_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_obs_gru_observacion_grupo` (
  `id_rel_obs_gru` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_gru` int(11) NOT NULL,
  `id_pdg_obs` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_obs_gru`),
  KEY `fk_rel_obs_gru_observacion_grupo_pdg_gru_grupo1_idx` (`id_pdg_gru`),
  KEY `fk_rel_obs_gru_observacion_grupo_pdg_obs_observacion1_idx` (`id_pdg_obs`),
  CONSTRAINT `fk_rel_obs_gru_observacion_grupo_pdg_gru_grupo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`),
  CONSTRAINT `fk_rel_obs_gru_observacion_grupo_pdg_obs_observacion1` FOREIGN KEY (`id_pdg_obs`) REFERENCES `pdg_obs_observacion` (`id_pdg_obs`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_obs_gru_observacion_grupo`
--

LOCK TABLES `rel_obs_gru_observacion_grupo` WRITE;
/*!40000 ALTER TABLE `rel_obs_gru_observacion_grupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_obs_gru_observacion_grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_obs_ppe_observacion_preperfil`
--

DROP TABLE IF EXISTS `rel_obs_ppe_observacion_preperfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_obs_ppe_observacion_preperfil` (
  `id_rel_obs_ppe` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_obs` int(11) NOT NULL,
  `id_pdg_ppe` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_obs_ppe`),
  KEY `fk_rel_obs_ppe_observacion_preperfil_pdg_obs_observacion1_idx` (`id_pdg_obs`),
  KEY `fk_rel_obs_ppe_observacion_preperfil_pdg_ppe_pre_perfil1_idx` (`id_pdg_ppe`),
  CONSTRAINT `fk_rel_obs_ppe_observacion_preperfil_pdg_obs_observacion1` FOREIGN KEY (`id_pdg_obs`) REFERENCES `pdg_obs_observacion` (`id_pdg_obs`),
  CONSTRAINT `fk_rel_obs_ppe_observacion_preperfil_pdg_ppe_pre_perfil1` FOREIGN KEY (`id_pdg_ppe`) REFERENCES `pdg_ppe_pre_perfil` (`id_pdg_ppe`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_obs_ppe_observacion_preperfil`
--

LOCK TABLES `rel_obs_ppe_observacion_preperfil` WRITE;
/*!40000 ALTER TABLE `rel_obs_ppe_observacion_preperfil` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_obs_ppe_observacion_preperfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_pdg_obs_arc_observacion_archivo`
--

DROP TABLE IF EXISTS `rel_pdg_obs_arc_observacion_archivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_pdg_obs_arc_observacion_archivo` (
  `id_rel_pdg_obs_arc` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_arc_doc` int(11) NOT NULL,
  `id_pdg_obs` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_pdg_obs_arc`),
  KEY `fk_rel_pdg_obs_arc_observacion_archivo_pdg_arc_doc_archivo__idx` (`id_pdg_arc_doc`),
  KEY `fk_rel_pdg_obs_arc_observacion_archivo_pdg_obs_observacion1_idx` (`id_pdg_obs`),
  CONSTRAINT `fk_rel_pdg_obs_arc_observacion_archivo_pdg_arc_doc_archivo_do1` FOREIGN KEY (`id_pdg_arc_doc`) REFERENCES `pdg_arc_doc_archivo_documento` (`id_pdg_arc_doc`),
  CONSTRAINT `fk_rel_pdg_obs_arc_observacion_archivo_pdg_obs_observacion1` FOREIGN KEY (`id_pdg_obs`) REFERENCES `pdg_obs_observacion` (`id_pdg_obs`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_pdg_obs_arc_observacion_archivo`
--

LOCK TABLES `rel_pdg_obs_arc_observacion_archivo` WRITE;
/*!40000 ALTER TABLE `rel_pdg_obs_arc_observacion_archivo` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_pdg_obs_arc_observacion_archivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_rol_tri_eta_eva_tribunal_etapa`
--

DROP TABLE IF EXISTS `rel_rol_tri_eta_eva_tribunal_etapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_rol_tri_eta_eva_tribunal_etapa` (
  `id_rel_rol_tri_eta_eva` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_tri_rol` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL,
  `anio_rel_rol_tri_eta_eva` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_rol_tri_eta_eva`),
  KEY `fk_rel_rol_tri_eta_eva_tribunal_etapa_pdg_tri_rol_tribunal__idx` (`id_pdg_tri_rol`),
  KEY `fk_rel_rol_tri_eta_eva_tribunal_etapa_cat_eta_eva_etapa_eva_idx` (`id_cat_eta_eva`),
  CONSTRAINT `fk_rel_rol_tri_eta_eva_tribunal_etapa_cat_eta_eva_etapa_evalu1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`),
  CONSTRAINT `fk_rel_rol_tri_eta_eva_tribunal_etapa_pdg_tri_rol_tribunal_rol1` FOREIGN KEY (`id_pdg_tri_rol`) REFERENCES `pdg_tri_rol_tribunal_rol` (`id_pdg_tri_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_rol_tri_eta_eva_tribunal_etapa`
--

LOCK TABLES `rel_rol_tri_eta_eva_tribunal_etapa` WRITE;
/*!40000 ALTER TABLE `rel_rol_tri_eta_eva_tribunal_etapa` DISABLE KEYS */;
INSERT INTO `rel_rol_tri_eta_eva_tribunal_etapa` VALUES (1,1,10,2018),(2,1,11,2018),(3,1,12,2018),(4,1,13,2018),(5,3,13,2018);
/*!40000 ALTER TABLE `rel_rol_tri_eta_eva_tribunal_etapa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_ski_dcn_skill_docente`
--

DROP TABLE IF EXISTS `rel_ski_dcn_skill_docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_ski_dcn_skill_docente` (
  `id_rel_ski_dcn` int(11) NOT NULL AUTO_INCREMENT,
  `nivel_ski_dcn` int(11) DEFAULT NULL,
  `id_pdg_dcn` int(11) NOT NULL,
  `id_cat_ski` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_ski_dcn`),
  KEY `fk_rel_ski_dcn_skill_docente_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  KEY `fk_rel_ski_dcn_skill_docente_cat_ski_skill1_idx` (`id_cat_ski`),
  CONSTRAINT `fk_rel_ski_dcn_skill_docente_cat_ski_skill1` FOREIGN KEY (`id_cat_ski`) REFERENCES `cat_ski_skill` (`id_cat_ski`),
  CONSTRAINT `fk_rel_ski_dcn_skill_docente_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_ski_dcn_skill_docente`
--

LOCK TABLES `rel_ski_dcn_skill_docente` WRITE;
/*!40000 ALTER TABLE `rel_ski_dcn_skill_docente` DISABLE KEYS */;
INSERT INTO `rel_ski_dcn_skill_docente` VALUES (1,3,1,1),(2,3,1,2),(3,3,1,3),(4,3,1,4),(5,2,1,9),(6,2,1,10),(7,2,1,13),(8,2,1,18),(9,1,2,18),(10,1,2,16),(11,1,2,17),(12,2,2,4),(13,3,2,1),(14,2,3,12),(15,1,3,15),(16,3,3,18),(17,2,3,1),(18,3,3,6),(19,2,4,5),(20,2,4,6),(21,2,4,7),(22,3,4,8),(23,1,5,3),(24,1,5,12);
/*!40000 ALTER TABLE `rel_ski_dcn_skill_docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_sta_eta_eva_estado_etapa_evaluativa`
--

DROP TABLE IF EXISTS `rel_sta_eta_eva_estado_etapa_evaluativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_sta_eta_eva_estado_etapa_evaluativa` (
  `id_rel_sta_eta_eva` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat_eta_eva` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `anio_rel_sta_eta_eva` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_rel_sta_eta_eva`),
  KEY `fk_rel_sta_eta_eva_estado_etapa_evaluativa_cat_eta_eva_etap_idx` (`id_cat_eta_eva`),
  KEY `fk_rel_sta_eta_eva_estado_etapa_evaluativa_cat_sta_estado1_idx` (`id_cat_sta`),
  CONSTRAINT `fk_rel_sta_eta_eva_estado_etapa_evaluativa_cat_eta_eva_etapa_1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`),
  CONSTRAINT `fk_rel_sta_eta_eva_estado_etapa_evaluativa_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_sta_eta_eva_estado_etapa_evaluativa`
--

LOCK TABLES `rel_sta_eta_eva_estado_etapa_evaluativa` WRITE;
/*!40000 ALTER TABLE `rel_sta_eta_eva_estado_etapa_evaluativa` DISABLE KEYS */;
INSERT INTO `rel_sta_eta_eva_estado_etapa_evaluativa` VALUES (1,10,13,2018),(2,10,14,2018),(3,10,15,2018),(4,10,16,2018),(5,11,13,2018),(6,11,14,2018),(7,11,15,2018),(8,11,16,2018),(9,12,13,2018),(10,12,14,2018),(11,12,15,2018),(12,12,16,2018),(13,13,13,2018),(14,13,14,2018),(15,13,15,2018),(16,13,16,2018);
/*!40000 ALTER TABLE `rel_sta_eta_eva_estado_etapa_evaluativa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_tit_dcn_titulo_docente`
--

DROP TABLE IF EXISTS `rel_tit_dcn_titulo_docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_tit_dcn_titulo_docente` (
  `id_rel_tit_dcn` int(11) NOT NULL AUTO_INCREMENT,
  `id_pdg_dcn` int(11) NOT NULL,
  `id_cat_tit` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_tit_dcn`),
  KEY `fk_rel_tit_dcn_titulo_docente_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  KEY `fk_rel_tit_dcn_titulo_docente_cat_tit_titulos_profesionales_idx` (`id_cat_tit`),
  CONSTRAINT `fk_rel_tit_dcn_titulo_docente_cat_tit_titulos_profesionales1` FOREIGN KEY (`id_cat_tit`) REFERENCES `cat_tit_titulos_profesionales` (`id_cat_tit`),
  CONSTRAINT `fk_rel_tit_dcn_titulo_docente_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_tit_dcn_titulo_docente`
--

LOCK TABLES `rel_tit_dcn_titulo_docente` WRITE;
/*!40000 ALTER TABLE `rel_tit_dcn_titulo_docente` DISABLE KEYS */;
INSERT INTO `rel_tit_dcn_titulo_docente` VALUES (1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,1,2),(7,1,5),(8,2,7),(9,2,8),(10,3,9),(11,4,3),(12,4,4);
/*!40000 ALTER TABLE `rel_tit_dcn_titulo_docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
--

DROP TABLE IF EXISTS `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva` (
  `id_rel_tpo_doc_eta_eva` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat_tpo_doc` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_tpo_doc_eta_eva`),
  KEY `fk_rel_tpo_doc_eta_eva_tipo_documento_etapa_eva_cat_pdg_tpo_idx` (`id_cat_tpo_doc`),
  KEY `fk_rel_tpo_doc_eta_eva_tipo_documento_etapa_eva_cat_eta_eva_idx` (`id_cat_eta_eva`),
  CONSTRAINT `fk_rel_tpo_doc_eta_eva_tipo_documento_etapa_eva_cat_eta_eva_e1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`),
  CONSTRAINT `fk_rel_tpo_doc_eta_eva_tipo_documento_etapa_eva_cat_pdg_tpo_d1` FOREIGN KEY (`id_cat_tpo_doc`) REFERENCES `cat_tpo_doc_tipo_documento` (`id_cat_tpo_doc`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
--

LOCK TABLES `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva` WRITE;
/*!40000 ALTER TABLE `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva` DISABLE KEYS */;
INSERT INTO `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva` VALUES (1,2,10),(2,3,11),(3,4,11),(4,5,13);
/*!40000 ALTER TABLE `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_tpo_tra_eta_tipo_trabajo_etapa`
--

DROP TABLE IF EXISTS `rel_tpo_tra_eta_tipo_trabajo_etapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa` (
  `id_rel_tpo_tra_eta` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL,
  `orden_eta_eva` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_rel_tpo_tra_eta`),
  KEY `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_tpo_tra_gra_tipo__idx` (`id_cat_tpo_tra_gra`),
  KEY `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_eta_eva_etapa_eva_idx` (`id_cat_eta_eva`),
  CONSTRAINT `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_eta_eva_etapa_evalu1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`),
  CONSTRAINT `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_tpo_tra_gra_tipo_tr1` FOREIGN KEY (`id_cat_tpo_tra_gra`) REFERENCES `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_tpo_tra_eta_tipo_trabajo_etapa`
--

LOCK TABLES `rel_tpo_tra_eta_tipo_trabajo_etapa` WRITE;
/*!40000 ALTER TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa` DISABLE KEYS */;
INSERT INTO `rel_tpo_tra_eta_tipo_trabajo_etapa` VALUES (1,1,10,1),(2,1,11,2),(3,1,12,3),(4,1,13,4),(5,4,14,1),(6,4,15,2),(7,4,16,3),(8,4,17,4),(9,2,18,1),(10,2,19,2),(11,2,20,3),(12,2,21,4);
/*!40000 ALTER TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (1,3,20,'2018-04-12 19:48:34','2018-04-12 19:48:34'),(2,3,21,'2018-04-12 19:49:39','2018-04-12 19:49:39'),(3,3,22,'2018-04-12 19:51:07','2018-04-12 19:51:07'),(6,3,25,'2018-04-23 12:10:50','2018-04-23 12:10:50'),(7,2,25,'2018-04-23 12:10:50','2018-04-23 12:10:50'),(16,3,3,'2018-04-26 22:07:18','2018-04-26 22:07:18'),(20,6,30,'2018-05-02 12:28:30','2018-05-02 12:28:30'),(21,7,31,'2018-06-12 10:07:09','2018-06-12 10:07:09'),(32,6,22,'2018-08-03 21:05:16','2018-08-03 21:05:16'),(34,8,22,'2018-08-03 21:05:16','2018-08-03 21:05:16'),(35,2,33,'2018-08-05 07:07:53','2018-08-05 07:07:53'),(37,6,32,'2018-08-05 07:08:18','2018-08-05 07:08:18'),(38,8,32,'2018-08-05 07:08:19','2018-08-05 07:08:19'),(39,3,34,'2018-08-06 05:11:41',NULL),(40,3,35,'2018-08-06 05:11:41',NULL),(41,3,36,'2018-08-06 05:11:41',NULL),(42,3,37,'2018-08-06 05:11:41',NULL),(43,3,38,'2018-08-06 05:11:41',NULL),(44,3,39,'2018-08-06 05:11:41',NULL),(45,3,40,'2018-08-06 05:11:41',NULL),(46,3,41,'2018-08-06 05:11:41',NULL),(47,3,42,'2018-08-06 05:11:41',NULL),(48,3,43,'2018-08-06 05:11:41',NULL),(49,3,53,'2018-10-12 23:14:57','2018-10-12 23:14:57'),(50,8,0,'2018-10-29 10:40:33','2018-10-29 10:40:33'),(52,9,54,'2018-10-29 10:48:28','2018-10-29 10:48:28'),(53,3,55,'2018-11-03 11:13:37','2018-11-03 11:13:37'),(54,10,32,'2018-11-04 20:13:11','2018-11-04 20:13:11');
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `special` enum('all-access','no-access') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (2,'Docente','docente','Docente de la escuela de ingeniería de Sistemas','2018-04-02 15:12:14','2018-04-02 15:12:14',NULL),(3,'Estudiante','estudiante','Estudiante de la Escuela de ingeniería de Sistemas Informáticos','2018-04-12 19:47:32','2018-04-12 19:47:32',NULL),(6,'Administrador','administrador','Rol administrador del sistema, tiene los permisos para la gestion de usuarios, roles y permisos.','2018-05-02 10:28:23','2018-05-02 10:28:23',NULL),(7,'Coordinador Trabajos de  Graduación','administrador_tdg','Es el encargado de la coordinación de todos los grupos de trabajo de graduación y la toma de decisiones sobre las solicitudes y organización de los grupos.','2018-05-21 04:43:43','2018-05-21 04:43:55',NULL),(8,'Admin. de Publicaciones de Trabajos de Graduación','admin_publicaciones','Rol dedicado a la administración del histórico de todos los trabajos de graduación de la Escuela de Ingeniería de Sistemas informáticos.','2018-07-20 00:36:39','2018-07-20 00:37:46',NULL),(9,'Gestor de Documentos Publicaciones','gestor_de_documentos_publicaciones','Permite al usuario poder subir, modificar y eliminar los documentos correspondientes a las publicaciones de trabajo de graduacion','2018-10-29 10:48:06','2018-10-29 10:48:06',NULL),(10,'Docente Asesor','docente_asesor','Rol para docentes asesores de trabajo de graduación.','2018-11-04 20:12:28','2018-11-04 20:12:28',NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `view_dcn_perfildocente`
--

DROP TABLE IF EXISTS `view_dcn_perfildocente`;
/*!50001 DROP VIEW IF EXISTS `view_dcn_perfildocente`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_dcn_perfildocente` AS SELECT 
 1 AS `id_pdg_dcn`,
 1 AS `carnet_pdg_dcn`,
 1 AS `anio_titulacion_pdg_dcn`,
 1 AS `activo`,
 1 AS `id_gen_usuario`,
 1 AS `dcn_profileFoto`,
 1 AS `id_cat_tit`,
 1 AS `id_rel_tit_dcn`,
 1 AS `nombre_titulo_cat_tit`,
 1 AS `id_dcn_exp`,
 1 AS `lugar_trabajo_dcn_exp`,
 1 AS `anio_inicio_dcn_exp`,
 1 AS `anio_fin_dcn_exp`,
 1 AS `idIdiomaExper`,
 1 AS `idiomaExper`,
 1 AS `descripcionExperiencia`,
 1 AS `id_dcn_cer`,
 1 AS `id_rel_dcn_cer`,
 1 AS `nombre_dcn_cer`,
 1 AS `anio_expedicion_dcn_cer`,
 1 AS `institucion_dcn_cer`,
 1 AS `idIdiomaCert`,
 1 AS `idiomaCert`,
 1 AS `id_rel_ski_dcn`,
 1 AS `nivel_ski_dcn`,
 1 AS `Nivel`,
 1 AS `id_cat_ski`,
 1 AS `nombre_cat_ski`,
 1 AS `id_tpo_ski`,
 1 AS `id_rel_idi_dcn`,
 1 AS `idIdioma`,
 1 AS `nombre_cat_idi`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'sigpad_dev'
--
/*!50003 DROP PROCEDURE IF EXISTS `sp_dcn_get_historial_academicoByDocente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_dcn_get_historial_academicoByDocente`(in idDocente int)
BEGIN

SELECT usr.name as Docente, 
car.nombre_cargo as Cargo, 
mat.codigo_mat as Codigo,
mat.nombre_mat as Materia, 
mat.ciclo as Ciclo,
his.anio


FROM sigpad_dev.dcn_his_historial_academico his
		inner join cat_mat_materia mat on mat.id_cat_mat=his.id_cat_mat
        inner join pdg_dcn_docente doc on doc.id_pdg_dcn=his.id_pdg_dcn
        inner join cat_car_cargo_eisi car on car.id_cat_car = his.id_cat_car
        inner join gen_usuario usr on usr.id=doc.id_gen_usuario
        
        
        where doc.id_pdg_dcn=idDocente or -1=idDocente;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_dcn_get_PerfilDocente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_dcn_get_PerfilDocente`()
BEGIN


select * from pdg_dcn_docente dcn 
		left join rel_tit_dcn_titulo_docente rtit on rtit.id_pdg_dcn=dcn.id_pdg_dcn
        left join cat_tit_titulos_profesionales tit on tit.id_cat_tit=rtit.id_cat_tit
        left join ( select ex.*, id.nombre_cat_idi idiomaExper from dcn_exp_experiencia ex inner join cat_idi_idioma id on id.id_cat_idi=ex.id_cat_idi)exp on exp.id_pdg_dcn=dcn.id_pdg_dcn
        left join rel_dcn_cer_docente_certificacion cerd on cerd.id_pdg_dcn=dcn.id_pdg_dcn 
        left join (select ex.*, id.nombre_cat_idi idiomaCert from dcn_cer_certificaciones ex inner join cat_idi_idioma id on id.id_cat_idi=ex.id_cat_idi  )cer on cer.id_dcn_cer=cerd.id_rel_dcn_cer
        left join ( select skd.*, case when (skd.nivel_ski_dcn ) =1 then 'Basico' when (skd.nivel_ski_dcn ) =2 then 'Intermedio' else 'Avanzado' end as Nivel from rel_ski_dcn_skill_docente skd )rski on rski.id_pdg_dcn=dcn.id_pdg_dcn 
        left join cat_ski_skill ski on ski.id_cat_ski=rski.id_rel_ski_dcn
        left join rel_idi_dcn_idioma_docente ridi on ridi.id_pdg_dcn=dcn.id_pdg_dcn 
        left join cat_idi_idioma idi on idi.id_cat_idi=ridi.id_cat_idi;
        
	

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_alter_tpo_doc_x_Etapas_byGrypo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_alter_tpo_doc_x_Etapas_byGrypo`(in cant int,out result int)
BEGIN


DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;
    
    
    

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_crearAprobadoresPorEtapa` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_crearAprobadoresPorEtapa`(in idGrupo int, in idTrabajoGra int, out result int )
BEGIN


	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;
    
    
    
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_enviarParaAprobacion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_enviarParaAprobacion`(IN `idGrupo` INT, OUT `result` INT)
BEGIN


declare cant int;
declare max_ int;
declare min_ int;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;
    





select count(id_gen_est) into cant from pdg_gru_est_grupo_estudiante where id_pdg_gru=idGrupo;

 select convert((select valor_gen_par from gen_par_parametros where id_gen_par=1),UNSIGNED) into max_;
 select convert((select valor_gen_par from gen_par_parametros where id_gen_par=2),UNSIGNED) into min_;

if ( cant >=min_ and cant <=max_)
then 
	CALL `sp_pdg_gru_aprobarGrupoTGCoordinador`(idGrupo, @resultado);
    select @reslutado;
else 
update pdg_gru_grupo
    set id_cat_sta=7
    where id_pdg_gru=idGrupo;
end if ;




set result=0;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_est_perteneceAGrupo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_est_perteneceAGrupo`(IN `idEst` INT, OUT `result` INT)
BEGIN


DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;

SELECT id_pdg_gru, sta.id_cat_sta,sta.nombre_cat_sta 
			FROM pdg_gru_est_grupo_estudiante gst 
			inner join cat_sta_estado sta on gst.id_cat_sta=sta.id_cat_sta
            
            where gst.id_gen_est=idEst;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_eta_eva_alter_EntregablesxEtapas_byGrypo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_eta_eva_alter_EntregablesxEtapas_byGrypo`(in cant int, in idTrabajoGra int, in idEtapa int,out result int)
BEGIN

	DECLARE i INT DEFAULT 1;
	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			GET DIAGNOSTICS CONDITION 1
			@p2 = MESSAGE_TEXT;
			SET result = -1;
			SELECT @p2 as 'resultado';
		END;
        
	START TRANSACTION;
    
	WHILE i < (cant) DO
	
    insert into pdg_eta_eva_tra_etapa_trabajo (id_pdg_tra_gra,id_cat_eta_eva,id_tpo_doc,orden_eta_eva)
	values(idTrabajoGra,idEtapa,(select id_cat_tpo_doc from cat_tpo_doc_tipo_documento where nombre_pdg_tpo_doc like (select concat('Entregable ',i))),	i);
    set i=i+1;    
    
    END WHILE;
commit;    
    
    

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_eta_eva_set_EntregablesxEtapas_byGrypo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_eta_eva_set_EntregablesxEtapas_byGrypo`( in idTrabajoGra int, out result int)
BEGIN


DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;
    
    START TRANSACTION;  
     
if((select count(*) from pdg_eta_eva_tra_etapa_trabajo etapas where etapas.id_pdg_tra_gra =idTrabajoGra)=0)
then
insert into pdg_eta_eva_tra_etapa_trabajo (id_pdg_tra_gra,id_cat_eta_eva,id_tpo_doc,orden_eta_eva)
 select idTrabajoGra,eta.id_cat_eta_eva, 
case when tpd.id_cat_tpo_doc is null then 8 else tpd.id_cat_tpo_doc end as id_tpo_doc,
 (select count(rel2.id_cat_eta_eva) from rel_tpo_doc_eta_eva_tipo_documento_etapa_eva rel2 where rel.id_cat_eta_eva=rel2.id_cat_eta_eva) as orden_eta_eva 
from cat_eta_eva_etapa_evaluativa eta
left join rel_tpo_doc_eta_eva_tipo_documento_etapa_eva rel on rel.id_cat_eta_eva=eta.id_cat_eta_eva
left join cat_tpo_doc_tipo_documento tpd on rel.id_cat_tpo_doc=tpd.id_cat_tpo_doc
inner join rel_tpo_tra_eta_tipo_trabajo_etapa rel_ttg on rel_ttg.id_cat_eta_eva=eta.id_cat_eta_eva

where rel_ttg.id_cat_tpo_tra_gra=idTrabajoGra;
commit;
end if;



    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_getDocumentosArchivosEtapasByIdEtapaGrupo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_getDocumentosArchivosEtapasByIdEtapaGrupo`(IN `idEtapa` INT, IN `idGrupo` INT)
BEGIN
SELECT 
docs.id_pdg_doc,
 docs.id_pdg_gru,
 docs.id_cat_tpo_doc,
 docs.fecha_creacion_pdg_doc as fechaCreacionDocumento, 
 tpd.nombre_pdg_tpo_doc as NombreTipoDocumento,
 tpd.puede_observar_cat_pdg_tpo_doc as SePuedeObservarDocu,
 arc.id_pdg_arc_doc,
 arc.nombre_arc_doc as nombreArchivo,
 arc.ubicacion_arc_doc as UbicacionArchivo,
 arc.fecha_subida_arc_doc fechaSubidaArchivo,
 arc.activo as esArchivoActico
 ,eta.nombre_cat_eta_eva as nombreEtapaEva
 ,eta.ponderacion_cat_eta_eva as ponderacionEtapa
 ,eta.tiene_defensas_cat_eta_eva as tieneDefensasLaEtapa
 ,eta.puede_observar_cat_eta_eva as sePuedeObservarLaEtapa

FROM pdg_doc_documento docs 
inner join cat_tpo_doc_tipo_documento tpd on docs.id_cat_tpo_doc=tpd.id_cat_tpo_doc
inner join pdg_eta_eva_tra_etapa_trabajo rel on docs.id_cat_tpo_doc=rel.id_tpo_doc
inner join cat_eta_eva_etapa_evaluativa eta on rel.id_cat_eta_eva=eta.id_cat_eta_eva
inner join pdg_arc_doc_archivo_documento arc on arc.id_pdg_doc=docs.id_pdg_doc


where rel.id_cat_eta_eva=idEtapa and docs.id_pdg_gru=idGrupo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_getDocumentosByEtapa` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_getDocumentosByEtapa`(IN `idEtapa` INT, IN `idTraGra` INT)
BEGIN

SELECT rel.id_cat_eta_eva,nombre_cat_eta_eva,ponderacion_cat_eta_eva, rel.id_tpo_doc as id_cat_tpo_doc,nombre_pdg_tpo_doc

 FROM pdg_eta_eva_tra_etapa_trabajo rel
inner join cat_eta_eva_etapa_evaluativa eta on rel.id_cat_eta_eva=eta.id_cat_eta_eva
inner join cat_tpo_doc_tipo_documento tpd on rel.id_tpo_doc=tpd.id_cat_tpo_doc

where rel.id_cat_eta_eva=idEtapa AND  rel.id_pdg_tra_gra = idTraGra;
 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_getEtapasEvaluativasByGrupo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_getEtapasEvaluativasByGrupo`(IN `idGrupo` INT)
BEGIN
SELECT ev.id_cat_eta_eva,nombre_cat_eta_eva, nombre_cat_tpo_tra_gra,ttg.tema_pdg_tra_gra,tpo.orden_eta_eva,ttg.id_pdg_gru
 FROM sigpad_dev.pdg_tra_gra_trabajo_graduacion ttg
inner join sigpad_dev.rel_tpo_tra_eta_tipo_trabajo_etapa tpo on ttg.id_cat_tpo_tra_gra=tpo.id_cat_tpo_tra_gra 
inner join sigpad_dev.cat_eta_eva_etapa_evaluativa ev on tpo.id_cat_eta_eva=ev.id_cat_eta_eva
inner join sigpad_dev.cat_tpo_tra_gra_tipo_trabajo_graduacion tt on tt.id_cat_tpo_tra_gra=ttg.id_cat_tpo_tra_gra
inner join pdg_gru_grupo gp on gp.id_pdg_gru=ttg.id_pdg_gru
where gp.anio_pdg_gru = tt.anio_cat_tpo_tra_gra and gp.id_pdg_gru = idGrupo
order by tpo.orden_eta_eva;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_get_tribunalPorGrupos_byAnio` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_get_tribunalPorGrupos_byAnio`(in anio int)
BEGIN

select gru.numero_pdg_gru as 'Numero Grupo', 
		est.carnet_gen_est as Carnet,
        est.nombre_gen_est as Lider, 
        triR.nombre_tri_rol as TribunalRol,
        usr.name  as Docente
        
        from pdg_tri_gru_tribunal_grupo triG
		 join pdg_gru_est_grupo_estudiante gruE on triG.id_pdg_gru=gruE.id_pdg_gru and gruE.eslider_pdg_gru_est=1
             join pdg_tri_rol_tribunal_rol triR on triR.id_pdg_tri_rol=triG.id_pdg_tri_rol
             join gen_est_estudiante est on est.id_gen_est=gruE.id_gen_est
             left join pdg_dcn_docente dcn on dcn.id_pdg_dcn=triG.id_pdg_dcn
             left join gen_usuario usr on usr.id=dcn.id_gen_usuario
            left join pdg_gru_grupo gru on gru.id_pdg_gru=gruE.id_pdg_gru
            
            where gru.anio_pdg_gru=anio
            order by gru.numero_pdg_gru asc;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_gru_aceptarRechazarGrupo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_aceptarRechazarGrupo`(IN `acepto` INT, IN `idEst` INT, OUT `result` INT)
BEGIN




declare idGrupo int default -1;
   
    


    	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
	GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;
    
    



    START TRANSACTION;
    set idGrupo= (select id_pdg_gru from pdg_gru_est_grupo_estudiante where pdg_gru_est_grupo_estudiante.id_gen_est=idEst);
    if (acepto > 0)
    then 
    update pdg_gru_est_grupo_estudiante 
    set id_cat_sta=6
    where pdg_gru_est_grupo_estudiante.id_gen_est=idEst; 
    
    if ((select count(id_pdg_gru_est)  from pdg_gru_est_grupo_estudiante where pdg_gru_est_grupo_estudiante.id_pdg_gru=idGrupo and pdg_gru_est_grupo_estudiante.id_cat_sta<>6)=0) 
    then
			if (select count(id_pdg_gru_est)  from pdg_gru_est_grupo_estudiante where id_pdg_gru=idGrupo)>=3	
            then
				update pdg_gru_grupo
				set id_cat_sta=2 
                where id_pdg_gru=idGrupo;
                
            end if;
    end if;

	SET result = 0;
	ELSE
    delete from pdg_gru_est_grupo_estudiante
    where id_gen_est=idEst and id_pdg_gru=idGrupo;
		
		SET result = 0;
	END IF;
	SELECT result as 'resultado';
    	COMMIT;	
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_gru_aprobarGrupoTGCoordinador` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_aprobarGrupoTGCoordinador`(IN `idGrupo` INT, OUT `result` INT)
BEGIN

declare correlativo int default 0;
declare numero varchar(30);
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		SET result = 1;
		SELECT result as 'resultado';
		ROLLBACK;
       
	END;
    
	START TRANSACTION;    
    
    if((select id_cat_sta from pdg_gru_grupo where id_pdg_gru =idGrupo )=2)
    
    then
			
            
				set correlativo= (SELECT max(correlativo_pdg_gru_gru)+1 
										FROM pdg_gru_grupo 
										where pdg_gru_grupo.anio_pdg_gru=(select anio_pdg_gru from pdg_gru_grupo where id_pdg_gru =idGrupo ));
									
				set numero= (select concat(lpad(correlativo,2,0),'-',(select anio_pdg_gru from pdg_gru_grupo where id_pdg_gru =idGrupo ))); 
				
				update pdg_gru_grupo
				set numero_pdg_gru=numero,
					correlativo_pdg_gru_gru=correlativo,
                    id_cat_sta=3
				where pdg_gru_grupo.id_pdg_gru=idGrupo;

		COMMIT;	
		SET result = 0;
		SELECT result as 'resultado';
		
    end if;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_gru_create` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_create`(IN `carnetsXML` VARCHAR(1000), OUT `result` INT)
BEGIN



	DECLARE idgrupo INT DEFAULT 0;
	DECLARE i INT DEFAULT 1;	
	DECLARE rowcount INT;
	DECLARE carnet VARCHAR(50);
	DECLARE estID INT DEFAULT 0;
	DECLARE estadoInicial INT DEFAULT 0;
	DECLARE esLider INT DEFAULT 0;



	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		SET result = 1;
		SELECT result as 'resultado';
		ROLLBACK;
	END;




	START TRANSACTION;
	INSERT INTO `pdg_gru_grupo`(`id_cat_sta`,`anio_pdg_gru`,`ciclo_pdg_gru`)
			VALUES (1, year(now()),'I');
	SET idgrupo = last_insert_id();	




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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_gru_find_ByCarnet` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_find_ByCarnet`(IN `carnet` VARCHAR(10), OUT `result` INT, OUT `carnetsJSON` VARCHAR(1000))
BEGIN



	DECLARE idgrupo INT DEFAULT -1;
	DECLARE estID INT DEFAULT -1;
	DECLARE grupoID INT DEFAULT -1;



	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;




	SELECT id_gen_est INTO estID from gen_est_estudiante where carnet_gen_est = carnet;




	SELECT id_pdg_gru INTO grupoID from pdg_gru_est_grupo_estudiante where id_gen_est = estID;




	IF grupoID > 0 THEN
		SELECT 
				CONCAT('[', GROUP_CONCAT(
								JSON_OBJECT('id',ge.id_gen_est,
											'nombre',ge.nombre_gen_est,
											'carnet',ge.carnet_gen_est,
											'estado',pge.id_cat_sta,
                                            'lider',pge.eslider_pdg_gru_est,
                                            'idGrupo',grupoID
                                            )
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_gru_grupoDetalleByID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_grupoDetalleByID`(IN `idGrupo` INT)
BEGIN


	select es.id_gen_est as ID_Estudiante,es.carnet_gen_est as carnet, es.nombre_gen_est as Nombre, if (gs.eslider_pdg_gru_est=1,"Lider", "Miembro") as Cargo   from pdg_gru_est_grupo_estudiante as gs 
			 inner join gen_est_estudiante as es on gs.id_gen_est=es.id_gen_est
             where gs.id_pdg_gru=idGrupo;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_gru_select_gruposByDocente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_select_gruposByDocente`(in idDocente int)
BEGIN

select 
    gru.id_pdg_gru as ID,
    gru.numero_pdg_gru numeroGrupo,
    est.nombre_gen_est as Lider,
    (select count(id_pdg_gru_est) from pdg_gru_est_grupo_estudiante ge where ge.id_pdg_gru=gru.id_pdg_gru ) as Cant
    ,case when (select count(*) from pdg_tra_gra_trabajo_graduacion as tdg where tdg.id_pdg_gru = gru.id_pdg_gru)>=1 then 1 else 0 end as estado
    
    from pdg_gru_grupo as gru 
			inner join pdg_gru_est_grupo_estudiante gruEst on gru.id_pdg_gru=gruEst.id_pdg_gru
            inner join gen_est_estudiante est on est.id_gen_est=gruEst.id_gen_est
            inner join cat_sta_estado st on st.id_cat_sta=gru.id_cat_sta

 where gruEst.eslider_pdg_gru_est=1 and gru.id_pdg_gru in  (select id_pdg_gru from pdg_tri_gru_tribunal_grupo where id_pdg_dcn=idDocente);
            

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_gru_select_gruposPendientesDeAprobacion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_select_gruposPendientesDeAprobacion`()
BEGIN
select 
    gru.id_pdg_gru as ID,
    est.nombre_gen_est as Lider,
    st.nombre_cat_sta as Estado,
    st.id_cat_sta as idEstado,
    (select count(id_pdg_gru_est) from pdg_gru_est_grupo_estudiante ge where ge.id_pdg_gru=gru.id_pdg_gru ) as Cant
    
    
    from pdg_gru_grupo as gru 
			inner join pdg_gru_est_grupo_estudiante gruEst on gru.id_pdg_gru=gruEst.id_pdg_gru
            inner join gen_est_estudiante est on est.id_gen_est=gruEst.id_gen_est
            inner join cat_sta_estado st on st.id_cat_sta=gru.id_cat_sta
	where gruEst.eslider_pdg_gru_est=1;
            
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_pdg_ModificarGrupo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_ModificarGrupo`(IN `idEst` INT, IN `idGrupo` INT, OUT `result` INT)
BEGIN 


DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;
 


update pdg_gru_est_grupo_estudiante
set id_pdg_gru=idGrupo,
 id_cat_sta=6
where id_gen_est=idEst;

 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `´sp_pdg_get_etapasAspectosCriteriosByTipoTrabajo´` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `´sp_pdg_get_etapasAspectosCriteriosByTipoTrabajo´`(in idTipoTrabajo int)
BEGIN

SELECT tra.nombre_cat_tpo_tra_gra,
 eta.nombre_cat_eta_eva as etapa,
 asp.nombre_pdg_asp aspecto, 
 asp.ponderacion_pdg_asp porcentajeAspecto,
 cri.nombre_cat_cri_eva criterio ,
 cri.ponderacion_cat_cri_eva porcentajeCriterio
		FROM sigpad_dev.cat_eta_eva_etapa_evaluativa eta 
		inner join pdg_asp_aspectos_evaluativos asp on eta.id_cat_eta_eva=asp.id_cat_eta_eva
        inner join cat_cri_eva_criterio_evaluacion cri on cri.id_pdg_asp=asp.id_pdg_asp 
        inner join rel_tpo_tra_eta_tipo_trabajo_etapa rel on rel.id_cat_eta_eva=eta.id_cat_eta_eva
        inner join cat_tpo_tra_gra_tipo_trabajo_graduacion tra on tra.id_cat_tpo_tra_gra=rel.id_cat_tpo_tra_gra
        where tra.id_cat_tpo_tra_gra=idTipoTrabajo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Current Database: `sigpad_dev`
--

USE `sigpad_dev`;

--
-- Final view structure for view `view_dcn_perfildocente`
--

/*!50001 DROP VIEW IF EXISTS `view_dcn_perfildocente`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_dcn_perfildocente` AS select `dcn`.`id_pdg_dcn` AS `id_pdg_dcn`,`dcn`.`carnet_pdg_dcn` AS `carnet_pdg_dcn`,`dcn`.`anio_titulacion_pdg_dcn` AS `anio_titulacion_pdg_dcn`,`dcn`.`activo` AS `activo`,`dcn`.`id_gen_usuario` AS `id_gen_usuario`,`dcn`.`dcn_profileFoto` AS `dcn_profileFoto`,`rtit`.`id_cat_tit` AS `id_cat_tit`,`rtit`.`id_rel_tit_dcn` AS `id_rel_tit_dcn`,`tit`.`nombre_titulo_cat_tit` AS `nombre_titulo_cat_tit`,`exp`.`id_dcn_exp` AS `id_dcn_exp`,`exp`.`lugar_trabajo_dcn_exp` AS `lugar_trabajo_dcn_exp`,`exp`.`anio_inicio_dcn_exp` AS `anio_inicio_dcn_exp`,ifnull(`exp`.`anio_fin_dcn_exp`,'') AS `anio_fin_dcn_exp`,`exp`.`id_cat_idi` AS `idIdiomaExper`,`exp`.`idiomaExper` AS `idiomaExper`,`exp`.`descripcion_dcn_exp` AS `descripcionExperiencia`,`cerd`.`id_dcn_cer` AS `id_dcn_cer`,`cerd`.`id_rel_dcn_cer` AS `id_rel_dcn_cer`,`cer`.`nombre_dcn_cer` AS `nombre_dcn_cer`,`cer`.`anio_expedicion_dcn_cer` AS `anio_expedicion_dcn_cer`,`cer`.`institucion_dcn_cer` AS `institucion_dcn_cer`,`cer`.`id_cat_idi` AS `idIdiomaCert`,`cer`.`idiomaCert` AS `idiomaCert`,`rski`.`id_rel_ski_dcn` AS `id_rel_ski_dcn`,`rski`.`nivel_ski_dcn` AS `nivel_ski_dcn`,`rski`.`Nivel` AS `Nivel`,`ski`.`id_cat_ski` AS `id_cat_ski`,`ski`.`nombre_cat_ski` AS `nombre_cat_ski`,`ski`.`id_tpo_ski` AS `id_tpo_ski`,`ridi`.`id_rel_idi_dcn` AS `id_rel_idi_dcn`,`idi`.`id_cat_idi` AS `idIdioma`,`idi`.`nombre_cat_idi` AS `nombre_cat_idi` from (((((((((`pdg_dcn_docente` `dcn` left join `rel_tit_dcn_titulo_docente` `rtit` on((`rtit`.`id_pdg_dcn` = `dcn`.`id_pdg_dcn`))) left join `cat_tit_titulos_profesionales` `tit` on((`tit`.`id_cat_tit` = `rtit`.`id_cat_tit`))) left join (select `ex`.`id_dcn_exp` AS `id_dcn_exp`,`ex`.`lugar_trabajo_dcn_exp` AS `lugar_trabajo_dcn_exp`,`ex`.`anio_inicio_dcn_exp` AS `anio_inicio_dcn_exp`,`ex`.`anio_fin_dcn_exp` AS `anio_fin_dcn_exp`,`ex`.`descripcion_dcn_exp` AS `descripcion_dcn_exp`,`ex`.`id_cat_idi` AS `id_cat_idi`,`ex`.`id_pdg_dcn` AS `id_pdg_dcn`,`id`.`nombre_cat_idi` AS `idiomaExper` from (`dcn_exp_experiencia` `ex` join `cat_idi_idioma` `id` on((`id`.`id_cat_idi` = `ex`.`id_cat_idi`)))) `exp` on((`exp`.`id_pdg_dcn` = `dcn`.`id_pdg_dcn`))) left join `rel_dcn_cer_docente_certificacion` `cerd` on((`cerd`.`id_pdg_dcn` = `dcn`.`id_pdg_dcn`))) left join (select `certi`.`id_dcn_cer` AS `id_dcn_cer`,`certi`.`nombre_dcn_cer` AS `nombre_dcn_cer`,`certi`.`anio_expedicion_dcn_cer` AS `anio_expedicion_dcn_cer`,`certi`.`institucion_dcn_cer` AS `institucion_dcn_cer`,`certi`.`id_cat_idi` AS `id_cat_idi`,`id`.`nombre_cat_idi` AS `idiomaCert` from (`dcn_cer_certificaciones` `certi` join `cat_idi_idioma` `id` on((`id`.`id_cat_idi` = `certi`.`id_cat_idi`)))) `cer` on((`cer`.`id_dcn_cer` = `cerd`.`id_rel_dcn_cer`))) left join (select `skd`.`id_rel_ski_dcn` AS `id_rel_ski_dcn`,`skd`.`nivel_ski_dcn` AS `nivel_ski_dcn`,`skd`.`id_pdg_dcn` AS `id_pdg_dcn`,`skd`.`id_cat_ski` AS `id_cat_ski`,(case when (`skd`.`nivel_ski_dcn` = 1) then 'Basico' when (`skd`.`nivel_ski_dcn` = 2) then 'Intermedio' else 'Avanzado' end) AS `Nivel` from `rel_ski_dcn_skill_docente` `skd`) `rski` on((`rski`.`id_pdg_dcn` = `dcn`.`id_pdg_dcn`))) left join `cat_ski_skill` `ski` on((`ski`.`id_cat_ski` = `rski`.`id_rel_ski_dcn`))) left join `rel_idi_dcn_idioma_docente` `ridi` on((`ridi`.`id_pdg_dcn` = `dcn`.`id_pdg_dcn`))) left join `cat_idi_idioma` `idi` on((`idi`.`id_cat_idi` = `ridi`.`id_cat_idi`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-05  4:01:13
