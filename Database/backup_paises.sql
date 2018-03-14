/*
SQLyog Enterprise - MySQL GUI v8.05 
MySQL - 5.5.24-log : Database - paises
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `direcciones` */

DROP TABLE IF EXISTS `direcciones`;

CREATE TABLE `direcciones` (
  `id_dir` int(15) NOT NULL AUTO_INCREMENT,
  `direccion` varchar(255) DEFAULT NULL,
  `id_pais` int(15) DEFAULT NULL,
  `id_mun` int(15) DEFAULT NULL,
  PRIMARY KEY (`id_dir`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `direcciones` */

insert  into `direcciones`(`id_dir`,`direccion`,`id_pais`,`id_mun`) values (1,'prueba1',1,1),(2,'prueba23',1,1);

/*Table structure for table `municipio` */

DROP TABLE IF EXISTS `municipio`;

CREATE TABLE `municipio` (
  `id_mun` int(15) NOT NULL AUTO_INCREMENT,
  `nombre_mun` varchar(255) DEFAULT NULL,
  `id_pais` int(15) DEFAULT NULL,
  PRIMARY KEY (`id_mun`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `municipio` */

insert  into `municipio`(`id_mun`,`nombre_mun`,`id_pais`) values (1,'SAN SALVADOR',1),(2,'SAN MIGUEL',1),(3,'GUATEMALA CITY',2),(4,'ZACAPA',2),(5,'SAN PEDRO SULA',3);

/*Table structure for table `pais` */

DROP TABLE IF EXISTS `pais`;

CREATE TABLE `pais` (
  `id_pais` int(15) NOT NULL AUTO_INCREMENT,
  `nombre_pais` varchar(255) DEFAULT NULL,
  `comentario_pais` varchar(255) DEFAULT NULL,
  `atraccion_pais` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pais`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `pais` */

insert  into `pais`(`id_pais`,`nombre_pais`,`comentario_pais`,`atraccion_pais`) values (1,'EL SALVADOR','Pulgarcito','pupusas'),(2,'GUATEMALA','Norte','quetzal'),(3,'HONDURAS','Atlantico','roatan');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
