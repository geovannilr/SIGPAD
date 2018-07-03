-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-07-2018 a las 05:16:47
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `getEtapasEvaluativasByGrupo` (IN `idGrupo` INT)  BEGIN
SELECT nombre_cat_eta_eva, nombre_cat_tpo_tra_gra,ttg.tema_pdg_tra_gra,tpo.orden_eta_eva,ttg.id_pdg_gru
 FROM sigpad_dev.pdg_tra_gra_trabajo_graduacion ttg
inner join sigpad_dev.rel_tpo_tra_eta_tipo_trabajo_etapa tpo on ttg.id_cat_tpo_tra_gra=tpo.id_cat_tpo_tra_gra 
inner join sigpad_dev.cat_eta_eva_etapa_evaluativa ev on tpo.id_cat_eta_eva=ev.id_cat_eta_eva
inner join sigpad_dev.cat_tpo_tra_gra_tipo_trabajo_graduacion tt on tt.id_cat_tpo_tra_gra=ttg.id_cat_tpo_tra_gra
inner join pdg_gru_grupo gp on gp.id_pdg_gru=ttg.id_pdg_gru
where gp.anio_pdg_gru = tt.anio_cat_tpo_tra_gra and gp.id_pdg_gru = idGrupo
order by tpo.orden_eta_eva;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_enviarParaAprobacion` (IN `idEstudiante` INT, OUT `result` INT)  BEGIN

declare idGrupo int;
declare cant int;
declare max decimal;
declare min decimal;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';
	END;
    

-- recuperar el ID del grupo
select id_pdg_gru into idGrupo from pdg_gru_est_grupo_estudiante where id_gen_est=idEstudiante ;

-- recuperar cantidad del grupo
select count(id_gen_est) into cant from pdg_gru_est_grupo_estudiante where id_pdg_gru=idGrupo;

 select convert((select valor_gen_par from gen_par_parametros where id_gen_par=1),decimal) into min;
 select convert((select valor_gen_par from gen_par_parametros where id_gen_par=2),decimal) into max;

if ( cant >=min and cant <=max)
then 
	CALL `sp_pdg_gru_aprobarGrupoTGCoordinador`(idGrupo, @result);
    
else 
update pdg_gru_grupo
    set id_cat_sta=7
    where id_pdg_gru=idGrupo;
end if ;




set result=0;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_est_perteneceAGrupo` (IN `idEst` INT, OUT `result` INT)  BEGIN


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

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_aceptarRechazarGrupo` (IN `acepto` INT, IN `idEst` INT, OUT `result` INT)  BEGIN

-- -------------------------------------------------
-- -Variables
-- -------------------------------------------------
declare idGrupo int default -1;
   -- acepto=1 No acepto= 2
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
-- -Actualización
-- -------------------------------------------------
    START TRANSACTION;
    set idGrupo= (select id_pdg_gru from pdg_gru_est_grupo_estudiante where pdg_gru_est_grupo_estudiante.id_gen_est=idEst);
    if (acepto > 0)
    then -- cambia el estado del estudiante a aceptado para ese grupo
    update pdg_gru_est_grupo_estudiante 
    set id_cat_sta=6
    where pdg_gru_est_grupo_estudiante.id_gen_est=idEst; 
    
    if ((select count(id_pdg_gru_est)  from pdg_gru_est_grupo_estudiante where pdg_gru_est_grupo_estudiante.id_pdg_gru=idGrupo and pdg_gru_est_grupo_estudiante.id_cat_sta<>6)=0) -- verifica si ya no hay más estudiantes que no hayan aceptado
    then
			if (select count(id_pdg_gru_est)  from pdg_gru_est_grupo_estudiante where id_pdg_gru=idGrupo)>=3	-- falta hacerlo parametrizado select * from gen_par_parametros; 
            then
				update pdg_gru_grupo
				set id_cat_sta=2 -- debe ser parametrizable.
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_aprobarGrupoTGCoordinador` (IN `idGrupo` INT, OUT `result` INT)  BEGIN

declare correlativo int default 0;
declare numero varchar(30);
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		SET result = 1;
		SELECT result as 'resultado';
		ROLLBACK;
       /* GET DIAGNOSTICS CONDITION 1
		@p2 = MESSAGE_TEXT;
		SET result = -1;
		SELECT @p2 as 'resultado';*/
	END;
    
	START TRANSACTION;    
    
    if((select id_cat_sta from pdg_gru_grupo where id_pdg_gru =idGrupo )=7)
    
    then
			
            
				set correlativo= (SELECT max(correlativo_pdg_gru_gru)+1 
										FROM pdg_gru_grupo 
										where pdg_gru_grupo.anio_pdg_gru=(select anio_pdg_gru from pdg_gru_grupo where id_pdg_gru =idGrupo ));
									
				set numero= (select concat(lpad(correlativo,2,0),'-',(select anio_pdg_gru from pdg_gru_grupo where id_pdg_gru =idGrupo ))); -- limitado a correlativos de 2 digitos por el lpad.
				
				update pdg_gru_grupo
				set numero_pdg_gru=numero,
					correlativo_pdg_gru_gru=correlativo,
                    id_cat_sta=3
				where pdg_gru_grupo.id_pdg_gru=idGrupo;

		COMMIT;	
		SET result = 0;
		SELECT result as 'resultado';
		
    end if;
END$$

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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_grupoDetalleByID` (IN `idGrupo` INT)  BEGIN


	select es.id_gen_est as ID_Estudiante,es.carnet_gen_est as carnet, es.nombre_gen_est as Nombre, if (gs.eslider_pdg_gru_est=1,"Lider", "Miembro") as Cargo   from pdg_gru_est_grupo_estudiante as gs 
			 inner join gen_est_estudiante as es on gs.id_gen_est=es.id_gen_est
             where gs.id_pdg_gru=idGrupo;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_gru_select_gruposPendientesDeAprobacion` ()  BEGIN
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
            
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pdg_ModificarGrupo` (IN `idEst` INT, IN `idGrupo` INT, OUT `result` INT)  BEGIN -- si se recibe -1 se debe eliminar del grupo y sin asignarlo a alguno


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

 

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_cri_eva_criterio_evaluacion`
--

CREATE TABLE `cat_cri_eva_criterio_evaluacion` (
  `id_cat_cri_eva` int(11) NOT NULL,
  `id_pdg_asp` int(11) NOT NULL,
  `nombre_cat_cri_eva` varchar(45) NOT NULL,
  `ponderacion_cat_cri_eva` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_cri_eva_criterio_evaluacion`
--

INSERT INTO `cat_cri_eva_criterio_evaluacion` (`id_cat_cri_eva`, `id_pdg_asp`, `nombre_cat_cri_eva`, `ponderacion_cat_cri_eva`) VALUES
(1, 1, 'Introducción', '20.00'),
(2, 1, 'Objetivos', '20.00'),
(3, 1, 'Justificación', '40.00'),
(4, 1, 'Referencias', '20.00'),
(5, 2, 'Presentación', '50.00'),
(6, 2, 'Contenido', '50.00'),
(7, 3, 'Casos de Uso', '40.00'),
(8, 3, 'Diagrama de Clases', '40.00'),
(9, 3, 'Documento', '20.00'),
(10, 4, 'Diagrama Entidad Relación', '50.00'),
(11, 4, 'Diagrama de Arquitectura', '50.00'),
(12, 5, 'Documentación', '50.00'),
(13, 5, 'Estanddar', '50.00'),
(14, 6, 'Referencias', '50.00'),
(15, 6, 'Contenido', '20.00'),
(16, 6, 'CD\'s', '30.00'),
(17, 7, 'Tiempo ', '30.00'),
(18, 7, 'Presentación ', '30.00'),
(19, 7, 'Contenido', '40.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_ctg_tra_categoria_trabajo_graduacion`
--

CREATE TABLE `cat_ctg_tra_categoria_trabajo_graduacion` (
  `id_cat_ctg_tra` int(11) NOT NULL,
  `nombre_cat_ctg_tra` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_ctg_tra_categoria_trabajo_graduacion`
--

INSERT INTO `cat_ctg_tra_categoria_trabajo_graduacion` (`id_cat_ctg_tra`, `nombre_cat_ctg_tra`) VALUES
(1, 'Sistema de Informático Gerencial'),
(2, 'Sistema Contable'),
(3, 'Sitio Web Informativo'),
(4, 'Sistema de información geográfica'),
(5, 'Sistema de Seguimiento de Procesos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_eta_eva_etapa_evaluativa`
--

CREATE TABLE `cat_eta_eva_etapa_evaluativa` (
  `id_cat_eta_eva` int(11) NOT NULL,
  `nombre_cat_eta_eva` varchar(45) NOT NULL,
  `ponderacion_cat_eta_eva` decimal(5,2) NOT NULL,
  `tiene_defensas_cat_eta_eva` int(11) NOT NULL DEFAULT '0',
  `anio_cat_eta_eva` int(11) NOT NULL,
  `puede_observar_cat_eta_eva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_eta_eva_etapa_evaluativa`
--

INSERT INTO `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`, `nombre_cat_eta_eva`, `ponderacion_cat_eta_eva`, `tiene_defensas_cat_eta_eva`, `anio_cat_eta_eva`, `puede_observar_cat_eta_eva`) VALUES
(10, 'Anteproyecto', '15.00', 1, 2018, 1),
(11, 'Analisis y Diseño', '25.00', 1, 2018, 1),
(12, 'Codificación', '50.00', 1, 2018, 0),
(13, 'Defensa Final', '10.00', 1, 2018, 1),
(14, 'Modulo 1', '25.00', 1, 2019, 0),
(15, 'Modulo 2', '25.00', 1, 2019, 1),
(16, 'Modulo 3', '25.00', 1, 2019, 1),
(17, 'Modulo 4', '25.00', 1, 2019, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_idi_idioma`
--

CREATE TABLE `cat_idi_idioma` (
  `id_cat_idi` int(11) NOT NULL,
  `nombre_cat_idi` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_idi_idioma`
--

INSERT INTO `cat_idi_idioma` (`id_cat_idi`, `nombre_cat_idi`) VALUES
(1, 'Español'),
(2, 'Inglés'),
(3, 'Mandarin '),
(4, 'Frances');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_pro_proceso`
--

CREATE TABLE `cat_pro_proceso` (
  `id_cat_pro` int(11) NOT NULL,
  `nombre_cat_pro` varchar(45) NOT NULL,
  `descripcion_cat_pro` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_ski_skill`
--

CREATE TABLE `cat_ski_skill` (
  `id_cat_ski` int(11) NOT NULL,
  `nombre_cat_ski` varchar(45) DEFAULT NULL,
  `id_tpo_ski` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_ski_skill`
--

INSERT INTO `cat_ski_skill` (`id_cat_ski`, `nombre_cat_ski`, `id_tpo_ski`) VALUES
(1, 'MySQL', 1),
(2, 'SQL Server ', 1),
(3, 'PLSQL', 1),
(4, 'Oracle', 1),
(5, 'Java EE', 2),
(6, 'C#', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_sta_estado`
--

CREATE TABLE `cat_sta_estado` (
  `id_cat_sta` int(11) NOT NULL,
  `nombre_cat_sta` varchar(45) NOT NULL,
  `descripcion_cat_sta` varchar(250) NOT NULL,
  `id_cat_tpo_sta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_sta_estado`
--

INSERT INTO `cat_sta_estado` (`id_cat_sta`, `nombre_cat_sta`, `descripcion_cat_sta`, `id_cat_tpo_sta`) VALUES
(1, 'Creación de propuesta', 'Propuesta tentativa creada, pendiente aprobación de integrantes.', 1),
(2, 'Listo para envío', 'Listo para enviar a aprobación de docente director.', 1),
(3, 'Aprobado', 'Aprobado con asignación de código de grupo.', 1),
(4, 'Desintegrado', 'Desintegrar un grupo, liberando integrantes.', 1),
(5, 'Confirmación en espera', 'Esperando aprobación de integrante para conformar grupo.', 1),
(6, 'Aceptado', 'Integrante acepta conformación de grupo.', 1),
(7, 'Enviado para aprobación', 'Enviado a aprobación de docente director.', 1),
(8, 'Enviado', 'Preperfil: Creación y envío del Preperfil para Aprobación', 2),
(9, 'Aprobado por Asesor', 'Preperfil: Cuando el Asesor de el Visto bueno', 2),
(10, 'Aproba por Coordinador', 'Preperfil: Cuando el Coordinador Aprueba el Preperfil', 2),
(11, 'Rechazado por Asesor', 'Preperfil: Rechazado por Asesor', 2),
(12, 'Rechazado por Coordinador', 'Preperfil:Rechazado por Coordinador', 2),
(13, 'Enviado', 'Etapa enviada para Aprobación', 3),
(14, 'Aprobado', 'Etapa Aprobada', 3),
(15, 'Rechazado', 'Etapa Rechazada', 3),
(16, 'Con Observaciones', 'Etapa con Observaciones', 3),
(17, 'En Proceso', 'Trabajo de Gradución en proceso.', 6),
(18, 'Iniciado', 'Trabajo de graduación iniciado.', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tit_titulos_profesionales`
--

CREATE TABLE `cat_tit_titulos_profesionales` (
  `id_cat_tit` int(11) NOT NULL,
  `nombre_titulo_cat_tit` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_tit_titulos_profesionales`
--

INSERT INTO `cat_tit_titulos_profesionales` (`id_cat_tit`, `nombre_titulo_cat_tit`) VALUES
(1, 'Ingeniería de Sistemas Informáticos'),
(2, 'Ingeniería de Negocios'),
(3, 'Maestria en Arquitectura de Software');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tpo_doc_tipo_documento`
--

CREATE TABLE `cat_tpo_doc_tipo_documento` (
  `id_cat_tpo_doc` int(11) NOT NULL,
  `nombre_pdg_tpo_doc` varchar(45) NOT NULL,
  `descripcion_pdg_tpo_doc` varchar(100) DEFAULT NULL,
  `puede_observar_cat_pdg_tpo_doc` int(11) NOT NULL DEFAULT '1',
  `anio_cat_pdg_tpo_doc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_tpo_doc_tipo_documento`
--

INSERT INTO `cat_tpo_doc_tipo_documento` (`id_cat_tpo_doc`, `nombre_pdg_tpo_doc`, `descripcion_pdg_tpo_doc`, `puede_observar_cat_pdg_tpo_doc`, `anio_cat_pdg_tpo_doc`) VALUES
(2, 'Anteproyecto', 'Este es el Anteproyecto', 1, 2018),
(3, 'Analisis ', 'Documento de Analisis', 1, 2018),
(4, 'Diseño', 'Este es el documento del Diseño', 1, 2018),
(5, 'Tomo', 'Tomo Final', 1, 2018);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tpo_ski_tipo_skill`
--

CREATE TABLE `cat_tpo_ski_tipo_skill` (
  `id_tpo_ski` int(11) NOT NULL,
  `descripcion_tpo_ski` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_tpo_ski_tipo_skill`
--

INSERT INTO `cat_tpo_ski_tipo_skill` (`id_tpo_ski`, `descripcion_tpo_ski`) VALUES
(1, 'Base de Datos'),
(2, 'Lenguajes de Programación'),
(3, 'Bussines Intelligence'),
(4, 'Arquitectura de Software'),
(5, 'Inteligencia Artificial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tpo_sta_tipo_estado`
--

CREATE TABLE `cat_tpo_sta_tipo_estado` (
  `id_cat_tpo_sta` int(11) NOT NULL,
  `nombre_cat_tpo_sta` varchar(60) NOT NULL,
  `descripcion_cat_tpo_sta` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_tpo_sta_tipo_estado`
--

INSERT INTO `cat_tpo_sta_tipo_estado` (`id_cat_tpo_sta`, `nombre_cat_tpo_sta`, `descripcion_cat_tpo_sta`) VALUES
(1, 'Creación de Grupos', 'Estos Estados servirán para el proceso de creación y formación de Grupos'),
(2, 'Estados de Preperfil', 'Estados relacionados al proceso de Preperfil de grupos'),
(3, 'Estados de Etapas Evaluativas', 'Estados de las diferentes etapas Evaluativas'),
(6, 'Estados Trabajo de Graduación', 'Estados del seguimiento del proceso de trabajos de graduación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tpo_tra_gra_tipo_trabajo_graduacion`
--

CREATE TABLE `cat_tpo_tra_gra_tipo_trabajo_graduacion` (
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `nombre_cat_tpo_tra_gra` varchar(45) NOT NULL,
  `anio_cat_tpo_tra_gra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_tpo_tra_gra_tipo_trabajo_graduacion`
--

INSERT INTO `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`, `nombre_cat_tpo_tra_gra`, `anio_cat_tpo_tra_gra`) VALUES
(1, 'Desarrollo de Software', 2018),
(2, 'Trabajo de Investigación', 2018),
(3, 'Desarrollo Agil', 2018),
(4, 'Especialización', 2019);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dcn_cer_certificaciones`
--

CREATE TABLE `dcn_cer_certificaciones` (
  `id_dcn_cer` int(11) NOT NULL,
  `nombre_dcn_cer` varchar(45) DEFAULT NULL,
  `anio_expedicion_dcn_cer` varchar(45) DEFAULT NULL,
  `institucion_dcn_cer` varchar(45) DEFAULT NULL,
  `id_cat_idi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dcn_exp_experiencia`
--

CREATE TABLE `dcn_exp_experiencia` (
  `id_dcn_exp` int(11) NOT NULL,
  `lugar_trabajo_dcn_exp` varchar(45) DEFAULT NULL,
  `anio_inicio_dcn_exp` int(11) DEFAULT NULL,
  `anio_fin_dcn_exp` int(11) DEFAULT NULL,
  `descripcion_dcn_exp` varchar(800) DEFAULT NULL,
  `id_cat_idi` int(11) NOT NULL,
  `id_pdg_dcn` int(11) NOT NULL
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
-- Estructura de tabla para la tabla `gen_par_parametros`
--

CREATE TABLE `gen_par_parametros` (
  `id_gen_par` int(11) NOT NULL,
  `nombre_gen_par` varchar(45) DEFAULT NULL,
  `valor_gen_par` varchar(45) DEFAULT NULL,
  `id_gen_usuario` int(11) DEFAULT NULL,
  `fecha_creacion_gen_par` datetime DEFAULT NULL,
  `id_gen_tpo_par` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_tpo_par_tipo_parametro`
--

CREATE TABLE `gen_tpo_par_tipo_parametro` (
  `id_gen_tpo_par` int(11) NOT NULL,
  `tipo_gen_tpo_par` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `primer_nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `segundo_nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `segundo_apellido` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_carnet` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `gen_usuario`
--

INSERT INTO `gen_usuario` (`id`, `name`, `user`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `codigo_carnet`) VALUES
(3, 'Fernando Ernesto Cosme Morales', 'cm11005', 'cosme.92@gmail.com', '$2y$10$BjPijXdO87r91PjefxJA0ODEyOjmgyyhfdr.s0YxRMH77y9vYEzJG', 'ADjRxwoNGKcyrZSn8sjXAR3wpiHJsGB8LdPbVCS6CEP8zuplHG8Lk6DfBCAS', '2018-04-01 21:23:31', '2018-05-02 16:46:29', NULL, NULL, NULL, NULL, NULL),
(30, 'Administrador Central', 'admin', 'administrador@ues.edu.sv', '$2y$10$PdTVSePNsZMz/G/dT7uKM.nx4jwoCFRoU/qQntwPZQ9OtBjehYeiK', '5ECRfk131eANUOnPbWwfw2AmlnbQpnISrXPQCM1Uv688BiRXo6EC6ak0AYTt', '2018-05-02 12:28:30', '2018-05-02 12:28:30', NULL, NULL, NULL, NULL, NULL),
(22, 'Eduardo Rafael Serrano Barrera', 'sb12002', 'rafael31194@hotmail.com', '$2y$10$U4USomU1aYJmniMN0ghXZesIrpGV2laeOMF5A3DfAdHrkmZx17bIS', 'THtf4I44kzEQt0ZztHzwkAwpICYQAXewDAFdfTdaXfvdwZkBNCHBI20xNd9P', '2018-04-12 19:51:07', '2018-04-12 19:51:07', NULL, NULL, NULL, NULL, NULL),
(21, 'Edgardo José Ramirez García', 'rg12001', 'edgardo.ramirez94@gmail.com', '$2y$10$x76t/jfOKVu3ZrBFcNMzxee47YyYwmjBpHCCN0ed1zgOF8D0TneGC', 'XQWXV2nsqqMwTQEip8HSZdYRheN14GYvVu9EIPRaGz7HLkJonEnjHAycPipd', '2018-04-12 19:49:39', '2018-04-12 19:49:39', NULL, NULL, NULL, NULL, NULL),
(20, 'Francisco Wilfredo Polanco Portillo', 'pp10005', 'polanco260593@gmail.com', '$2y$10$Db..R7cD93r70/TgPqYzLetuYq.U.vsseOJiiq/c3rw2WnEgY47b6', NULL, '2018-04-12 19:48:34', '2018-04-12 19:48:34', NULL, NULL, NULL, NULL, NULL),
(31, 'Yesenia Vigil', 'admin_tdg', 'tdg@ues.edu.sv', '$2y$10$lscTHMSe2IuDSIfItMXMLeEgaDUkal18L1FQjPnMWX3ErzF1EdR7C', 'optHEIAttpbj8rW5nknAPcMZg4zqQZ1wj6qLmJLVMB4VqgogVnLG5QFYYANC', '2018-05-21 04:50:36', '2018-05-21 04:50:36', NULL, NULL, NULL, NULL, NULL),
(32, 'Cesar González', 'cg99005', 'cesargrodriguez@gmail.com', '$2y$10$Hq8qsxVNZ6Wh5wBcQU58Yez8o00vTg22OAylsuenZPgS48ojmOQP2', NULL, '2018-06-25 03:53:40', '2018-06-25 03:53:40', 'Cesar', 'Augusto', 'González', 'Rodriguez', 'cg99005'),
(33, 'Balmore Ortiz', 'bo96001', 'balmore.ortiz@gmail.com', '$2y$10$whBZB27BBTQD.BE3eBaxyev8FACIqK.uNy10kiHq21zpD/.F5mjpO', NULL, '2018-06-25 03:56:29', '2018-06-25 03:56:29', 'Balmore', NULL, 'Ortiz', NULL, 'bo96001');

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
-- Estructura de tabla para la tabla `pdg_apr_eta_tra_aprobador_etapa_trabajo`
--

CREATE TABLE `pdg_apr_eta_tra_aprobador_etapa_trabajo` (
  `id_pdg_apr_eta_tra` int(11) NOT NULL,
  `id_pdg_eta_eva_tra` int(11) NOT NULL,
  `id_pdg_tri_gru` int(11) NOT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  `aprobo` int(11) DEFAULT NULL,
  `fecha_aprobacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_arc_doc_archivo_documento`
--

CREATE TABLE `pdg_arc_doc_archivo_documento` (
  `id_pdg_arc_doc` int(11) NOT NULL,
  `id_pdg_doc` int(11) NOT NULL,
  `ubicacion_arc_doc` varchar(200) NOT NULL,
  `fecha_subida_arc_doc` datetime DEFAULT NULL,
  `nombre_arc_doc` varchar(45) DEFAULT NULL,
  `activo` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='InnoDB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_asp_aspectos_evaluativos`
--

CREATE TABLE `pdg_asp_aspectos_evaluativos` (
  `id_pdg_asp` int(11) NOT NULL,
  `nombre_pdg_asp` varchar(45) NOT NULL,
  `ponderacion_pdg_asp` decimal(5,2) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_asp_aspectos_evaluativos`
--

INSERT INTO `pdg_asp_aspectos_evaluativos` (`id_pdg_asp`, `nombre_pdg_asp`, `ponderacion_pdg_asp`, `id_cat_eta_eva`) VALUES
(1, 'Documento', '40.00', 10),
(2, 'Defensa', '60.00', 10),
(3, 'Analisis', '50.00', 11),
(4, 'Diseño', '50.00', 11),
(5, 'Defensa', '100.00', 12),
(6, 'Tomo', '40.00', 13),
(7, 'Defensa Final', '60.00', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_bit_tra_gra_bitacora_trabajo_graduacion`
--

CREATE TABLE `pdg_bit_tra_gra_bitacora_trabajo_graduacion` (
  `id_pdg_bit_tra_gra` int(11) NOT NULL,
  `fecha_bit_tra_gra` datetime DEFAULT NULL,
  `nombre_archivo_bit_tra_gra` varchar(100) DEFAULT NULL,
  `ubicacion_arch_bit_tra_gra` varchar(100) DEFAULT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_dcn_docente`
--

CREATE TABLE `pdg_dcn_docente` (
  `id_pdg_dcn` int(11) NOT NULL,
  `carnet_pdg_dcn` varchar(45) NOT NULL,
  `anio_titulacion_pdg_dcn` varchar(45) DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  `id_gen_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_dcn_docente`
--

INSERT INTO `pdg_dcn_docente` (`id_pdg_dcn`, `carnet_pdg_dcn`, `anio_titulacion_pdg_dcn`, `activo`, `id_gen_usuario`) VALUES
(1, 'cg99005', '1999', 1, 32),
(2, 'bo96001', '1996', 1, 33);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_def_defensa`
--

CREATE TABLE `pdg_def_defensa` (
  `idcat_pdg_def_defensa` int(11) NOT NULL,
  `nombre_defensa` varchar(100) DEFAULT NULL,
  `lugar_def` varchar(100) DEFAULT NULL,
  `fecha_hora_def` datetime DEFAULT NULL,
  `descripcion_def` varchar(300) DEFAULT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL COMMENT 'la etapa evaluativa se va agregar en base al estado del trabajo de graduación cuando se cree un objeto de tipo defensa\n'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_doc_documento`
--

CREATE TABLE `pdg_doc_documento` (
  `id_pdg_doc` int(11) NOT NULL,
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_tpo_doc` int(11) NOT NULL,
  `fecha_creacion_pdg_doc` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_eta_eva_tra_etapa_trabajo`
--

CREATE TABLE `pdg_eta_eva_tra_etapa_trabajo` (
  `id_pdg_eta_eva_tra` int(11) NOT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

--
-- Volcado de datos para la tabla `pdg_gru_est_grupo_estudiante`
--

INSERT INTO `pdg_gru_est_grupo_estudiante` (`id_pdg_gru_est`, `id_pdg_gru`, `id_gen_est`, `id_cat_sta`, `eslider_pdg_gru_est`) VALUES
(1, 1, 1, 6, 1),
(2, 1, 4, 6, 0),
(4, 1, 2, 6, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_gru_grupo`
--

CREATE TABLE `pdg_gru_grupo` (
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `numero_pdg_gru` varchar(45) DEFAULT NULL,
  `correlativo_pdg_gru_gru` int(11) NOT NULL DEFAULT '0',
  `anio_pdg_gru` int(11) NOT NULL,
  `ciclo_pdg_gru` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_gru_grupo`
--

INSERT INTO `pdg_gru_grupo` (`id_pdg_gru`, `id_cat_sta`, `numero_pdg_gru`, `correlativo_pdg_gru_gru`, `anio_pdg_gru`, `ciclo_pdg_gru`) VALUES
(1, 3, '01-2018', 1, 2018, 'I');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_not_cri_tra_nota_criterio_trabajo`
--

CREATE TABLE `pdg_not_cri_tra_nota_criterio_trabajo` (
  `id_pdg_not_cri_tra` int(11) NOT NULL,
  `nota_pdg_not_cri_tra` decimal(3,2) NOT NULL,
  `id_cat_cri_eva` int(11) NOT NULL,
  `id_pdg_tra_gra` int(11) NOT NULL,
  `id_pdg_gru_est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_obs_observacion`
--

CREATE TABLE `pdg_obs_observacion` (
  `id_pdg_obs` int(11) NOT NULL,
  `descripción_pdg_obs` varchar(45) NOT NULL,
  `fecha_pdg_obs` datetime NOT NULL,
  `ultima_modificacion_pdg_obs` datetime DEFAULT NULL,
  `id_gen_usuario` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_ppe_pre_perfil`
--

CREATE TABLE `pdg_ppe_pre_perfil` (
  `id_pdg_ppe` int(11) NOT NULL,
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `tema_pdg_ppe` varchar(200) NOT NULL,
  `nombre_archivo_pdg_ppe` varchar(200) NOT NULL,
  `ubicacion_pdg_ppe` varchar(200) DEFAULT NULL,
  `fecha_creacion_pdg_ppe` datetime DEFAULT NULL,
  `id_gen_usuario` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_ppe_pre_perfil`
--

INSERT INTO `pdg_ppe_pre_perfil` (`id_pdg_ppe`, `id_pdg_gru`, `id_cat_sta`, `id_cat_tpo_tra_gra`, `tema_pdg_ppe`, `nombre_archivo_pdg_ppe`, `ubicacion_pdg_ppe`, `fecha_creacion_pdg_ppe`, `id_gen_usuario`) VALUES
(10, 1, 3, 1, 'Desarrollo de Sistema informático para la gestión de trabajo de graduación de la Escuela de Ingeniería de Sistemas Informáticos de la Universidad de El Salvador', 'Grupo1_2018_090620APA_Samples_V4.pdf', 'C:\\wamp\\www\\SIGPAD\\public\\Uploads\\PrePerfil\\Grupo1_2018_090620APA_Samples_V4.pdf', '2018-06-24 21:06:20', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_tra_gra_trabajo_graduacion`
--

CREATE TABLE `pdg_tra_gra_trabajo_graduacion` (
  `id_pdg_tra_gra` int(11) NOT NULL,
  `id_pdg_gru` int(11) NOT NULL,
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `tema_pdg_tra_gra` varchar(200) NOT NULL,
  `id_cat_ctg_tra` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_tra_gra_trabajo_graduacion`
--

INSERT INTO `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`, `id_pdg_gru`, `id_cat_tpo_tra_gra`, `tema_pdg_tra_gra`, `id_cat_ctg_tra`, `id_cat_sta`) VALUES
(1, 1, 1, 'Desarrollo de Sistema informático para la gestión de trabajo de graduación de la Escuela de Ingeniería de Sistemas Informáticos de la Universidad de El Salvador', 5, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_tri_gru_tribunal_grupo`
--

CREATE TABLE `pdg_tri_gru_tribunal_grupo` (
  `id_pdg_tri_gru` int(11) NOT NULL,
  `id_pdg_tri_rol` int(11) NOT NULL,
  `id_pdg_dcn` int(11) NOT NULL,
  `id_pdg_gru` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_tri_gru_tribunal_grupo`
--

INSERT INTO `pdg_tri_gru_tribunal_grupo` (`id_pdg_tri_gru`, `id_pdg_tri_rol`, `id_pdg_dcn`, `id_pdg_gru`) VALUES
(1, 1, 1, 1),
(2, 3, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_tri_rol_tribunal_rol`
--

CREATE TABLE `pdg_tri_rol_tribunal_rol` (
  `id_pdg_tri_rol` int(11) NOT NULL,
  `nombre_tri_rol` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_tri_rol_tribunal_rol`
--

INSERT INTO `pdg_tri_rol_tribunal_rol` (`id_pdg_tri_rol`, `nombre_tri_rol`) VALUES
(1, 'Asesor'),
(2, 'Coordinador'),
(3, 'Jurado');

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
(1, 'Ver Roles', 'rol.index', 'Permite al usuario ver el listado de roles que posee el sistema disponibles.', '2018-04-30 10:33:59', '2018-04-30 11:02:28'),
(3, 'Ver Usuarios', 'usuario.index', 'Permite ver el listado de usuarios del sistema', '2018-05-02 10:14:09', '2018-05-02 10:14:09'),
(4, 'Crear Usuarios', 'usuario.create', 'Permite registrar nuevos usuarios en el sistema.', '2018-05-02 10:14:33', '2018-05-02 10:14:33'),
(5, 'Modificar Usuarios', 'usuario.edit', 'Permite modificar los usuarios registrados del sistema.', '2018-05-02 10:15:00', '2018-05-02 10:15:00'),
(6, 'Eliminar Usuarios', 'usuario.destroy', 'Permite eliminar usuarios registrados del sistema.', '2018-05-02 10:16:15', '2018-05-02 10:16:15'),
(7, 'Crear Roles', 'rol.create', 'Permite registrar nuevos roles en el sistema.', '2018-05-02 10:17:21', '2018-05-02 10:17:21'),
(8, 'Modificar Roles', 'rol.edit', 'Permite modificar roles registrados en el sistema.', '2018-05-02 10:17:48', '2018-05-02 10:17:48'),
(9, 'Eliminar Roles', 'rol.destroy', 'Permite eliminar roles registrados en el sistema.', '2018-05-02 10:22:52', '2018-05-02 10:22:52'),
(10, 'Crear Permisos', 'permiso.create', 'Permite al Usuario registrar nuevos permisos dentro del sistema.', '2018-05-02 10:25:06', '2018-05-02 10:25:06'),
(11, 'Modificar Permisos', 'permiso.edit', 'Permite  al usuario modificar permisos registrados en el sistema.', '2018-05-02 10:25:29', '2018-05-02 10:25:29'),
(12, 'Ver Permisos', 'permiso.index', 'Permite ver el listado de permisos registrados en el sistema.', '2018-05-02 10:26:25', '2018-05-02 10:26:25'),
(13, 'Crear grupo Trabajo de graduación', 'grupotdg.create', 'Permite a los estudiantes que empiezan su proceso de trabajo de graduación conformar grupo con los estudiantes de su afinidad.', '2018-05-02 10:45:27', '2018-05-02 10:45:27'),
(13, 'Crear grupo Trabajo de graduación', 'grupotdg.create', 'Permite a los estudiantes que empiezan su proceso de trabajo de graduación conformar grupo con los estudiantes de su afinidad.', '2018-05-02 10:45:27', '2018-05-02 10:45:27'),
(14, 'Eliminar Permisos', 'permiso.destroy', 'Permite eliminar permisos del sistema.', '2018-05-20 17:57:14', '2018-05-20 17:57:14'),
(15, 'Ver grupos de trabajo de graduación', 'grupo.index', 'Permite ver el listado de todos los grupos de trabajo de graduación que se han creado , en esa misma vista se encuentran las opciones de ver el detalle, modificar ,aprobar/denegar  y  eliminar trabajos de graduación.', '2018-05-21 04:41:01', '2018-05-21 04:41:01'),
(16, 'Ver PrePerfiles', 'prePerfil.index', 'Esta opción permite ver el listado de preperfiles dependiendo el rol que accede.', '2018-06-02 14:10:43', '2018-06-02 14:10:43'),
(17, 'Crear PrePerfiles', 'prePerfil.create', 'Permite la creación de un preperfil , subir el documento y enviar para aprobación por parte del alumno.', '2018-06-02 14:11:47', '2018-06-02 14:11:47'),
(18, 'Aprobar Pre-Perfil', 'prePerfil.aprobar', 'Permite al administrador te trabajo de graduación aprobar  pre-perfiles de los grupos de trabajo de graduación.', '2018-06-03 17:16:17', '2018-06-03 17:16:17'),
(19, 'Rechazar Pre-Perfiles', 'prePerfil.rechazar', 'Permite al coordinador de trabajos de graduación rechazar los pre-perfiles enviados para aprobación de parte de lso grupos de trabajo de graduación.', '2018-06-03 17:17:06', '2018-06-03 17:17:06'),
(20, 'Eliminar Pre-Perfiles', 'prePerfil.destroy', 'Permite eliminar los preperfiles subidos por cada grupo de trabajo de graduación', '2018-06-04 15:10:17', '2018-06-04 15:10:17'),
(21, 'Modificar Pre-Perfil', 'prePerfil.edit', 'Permite a los grupos de trabajo de graduación modificar los preperfiles enviados siempre y cuando este no haya sido aprobado o rechazado.', '2018-06-04 15:27:04', '2018-06-04 15:27:04');

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
(1, 1, 2, '2018-04-30 11:57:05', '2018-04-30 11:57:05'),
(15, 13, 3, '2018-05-02 12:44:48', '2018-05-02 12:44:48'),
(4, 1, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(5, 3, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(6, 4, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(7, 5, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(8, 6, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(9, 7, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(10, 8, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(11, 9, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(12, 10, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(13, 11, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(14, 12, 6, '2018-05-02 10:28:23', '2018-05-02 10:28:23'),
(16, 14, 6, '2018-05-20 17:58:04', '2018-05-20 17:58:04'),
(17, 15, 7, '2018-05-21 04:43:43', '2018-05-21 04:43:43'),
(0, 13, 7, '2018-06-12 10:59:51', '2018-06-12 10:59:51'),
(19, 16, 3, '2018-06-02 14:22:22', '2018-06-02 14:22:22'),
(20, 17, 3, '2018-06-02 14:22:22', '2018-06-02 14:22:22'),
(21, 16, 7, '2018-06-03 16:40:10', '2018-06-03 16:40:10'),
(22, 18, 7, '2018-06-03 17:23:06', '2018-06-03 17:23:06'),
(23, 19, 7, '2018-06-03 17:23:06', '2018-06-03 17:23:06'),
(24, 20, 3, '2018-06-04 15:11:50', '2018-06-04 15:11:50'),
(25, 21, 3, '2018-06-04 15:27:18', '2018-06-04 15:27:18');

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
-- Estructura de tabla para la tabla `rel_dcn_cer_docente_certificacion`
--

CREATE TABLE `rel_dcn_cer_docente_certificacion` (
  `id_rel_dcn_cer` int(11) NOT NULL,
  `id_dcn_cer` int(11) NOT NULL,
  `id_pdg_dcn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_est_pro_estudiante_proceso`
--

CREATE TABLE `rel_est_pro_estudiante_proceso` (
  `id_rel_est_pro` int(11) NOT NULL,
  `id_cat_pro` int(11) NOT NULL,
  `id_gen_est` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_idi_dcn_idioma_docente`
--

CREATE TABLE `rel_idi_dcn_idioma_docente` (
  `id_rel_idi_dcn` int(11) NOT NULL,
  `id_pdg_dcn` int(11) NOT NULL,
  `id_cat_idi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_obs_gru_observacion_grupo`
--

CREATE TABLE `rel_obs_gru_observacion_grupo` (
  `id_rel_obs_gru` int(11) NOT NULL,
  `id_pdg_gru` int(11) NOT NULL,
  `id_pdg_obs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_obs_ppe_observacion_preperfil`
--

CREATE TABLE `rel_obs_ppe_observacion_preperfil` (
  `id_rel_obs_ppe` int(11) NOT NULL,
  `id_pdg_obs` int(11) NOT NULL,
  `id_pdg_ppe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_pdg_obs_arc_observacion_archivo`
--

CREATE TABLE `rel_pdg_obs_arc_observacion_archivo` (
  `id_rel_pdg_obs_arc` int(11) NOT NULL,
  `id_pdg_arc_doc` int(11) NOT NULL,
  `id_pdg_obs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_rol_tri_eta_eva_tribunal_etapa`
--

CREATE TABLE `rel_rol_tri_eta_eva_tribunal_etapa` (
  `id_rel_rol_tri_eta_eva` int(11) NOT NULL,
  `id_pdg_tri_rol` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL,
  `anio_rel_rol_tri_eta_eva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rel_rol_tri_eta_eva_tribunal_etapa`
--

INSERT INTO `rel_rol_tri_eta_eva_tribunal_etapa` (`id_rel_rol_tri_eta_eva`, `id_pdg_tri_rol`, `id_cat_eta_eva`, `anio_rel_rol_tri_eta_eva`) VALUES
(1, 1, 10, 2018),
(2, 1, 11, 2018),
(3, 1, 12, 2018),
(4, 1, 13, 2018),
(5, 3, 13, 2018);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_ski_dcn_skill_docente`
--

CREATE TABLE `rel_ski_dcn_skill_docente` (
  `id_rel_ski_dcn` int(11) NOT NULL,
  `nivel_ski_dcn` int(11) DEFAULT NULL,
  `id_pdg_dcn` int(11) NOT NULL,
  `id_cat_ski` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_sta_eta_eva_estado_etapa_evaluativa`
--

CREATE TABLE `rel_sta_eta_eva_estado_etapa_evaluativa` (
  `id_rel_sta_eta_eva` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL,
  `id_cat_sta` int(11) NOT NULL,
  `anio_rel_sta_eta_eva` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rel_sta_eta_eva_estado_etapa_evaluativa`
--

INSERT INTO `rel_sta_eta_eva_estado_etapa_evaluativa` (`id_rel_sta_eta_eva`, `id_cat_eta_eva`, `id_cat_sta`, `anio_rel_sta_eta_eva`) VALUES
(1, 10, 13, 2018),
(2, 10, 14, 2018),
(3, 10, 15, 2018),
(4, 10, 16, 2018),
(5, 11, 13, 2018),
(6, 11, 14, 2018),
(7, 11, 15, 2018),
(8, 11, 16, 2018),
(9, 12, 13, 2018),
(10, 12, 14, 2018),
(11, 12, 15, 2018),
(12, 12, 16, 2018),
(13, 13, 13, 2018),
(14, 13, 14, 2018),
(15, 13, 15, 2018),
(16, 13, 16, 2018);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_tit_dcn_titulo_docente`
--

CREATE TABLE `rel_tit_dcn_titulo_docente` (
  `id_rel_tit_dcn` int(11) NOT NULL,
  `id_pdg_dcn` int(11) NOT NULL,
  `id_cat_tit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
--

CREATE TABLE `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva` (
  `id_rel_tpo_doc_eta_eva` int(11) NOT NULL,
  `id_cat_tpo_doc` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
--

INSERT INTO `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva` (`id_rel_tpo_doc_eta_eva`, `id_cat_tpo_doc`, `id_cat_eta_eva`) VALUES
(1, 2, 10),
(2, 3, 11),
(3, 4, 11),
(4, 5, 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rel_tpo_tra_eta_tipo_trabajo_etapa`
--

CREATE TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa` (
  `id_rel_tpo_tra_eta` int(11) NOT NULL,
  `id_cat_tpo_tra_gra` int(11) NOT NULL,
  `id_cat_eta_eva` int(11) NOT NULL,
  `orden_eta_eva` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rel_tpo_tra_eta_tipo_trabajo_etapa`
--

INSERT INTO `rel_tpo_tra_eta_tipo_trabajo_etapa` (`id_rel_tpo_tra_eta`, `id_cat_tpo_tra_gra`, `id_cat_eta_eva`, `orden_eta_eva`) VALUES
(1, 1, 10, 1),
(2, 1, 11, 2),
(3, 1, 12, 3),
(4, 1, 13, 4),
(5, 4, 14, 1),
(6, 4, 15, 2),
(7, 4, 16, 3),
(8, 4, 17, 4);

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
(3, 'Estudiante', 'estudiante', 'Estudiante de la Escuela de ingeniería de Sistemas Informáticos', '2018-04-12 19:47:32', '2018-04-12 19:47:32', NULL),
(2, 'Docente', 'docente', 'Docente de la escuela de ingeniería de Sistemas', '2018-04-02 15:12:14', '2018-04-02 15:12:14', NULL),
(6, 'Administrador', 'administrador', 'Rol administrador del sistema, tiene los permisos para la gestion de usuarios, roles y permisos.', '2018-05-02 10:28:23', '2018-05-02 10:28:23', NULL),
(7, 'Coordinador Trabajos de  Graduación', 'administrador_tdg', 'Es el encargado de la coordinación de todos los grupos de trabajo de graduación y la toma de decisiones sobre las solicitudes y organización de los grupos.', '2018-05-21 04:43:43', '2018-05-21 04:43:55', NULL);

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
(1, 3, 20, '2018-04-12 19:48:34', '2018-04-12 19:48:34'),
(2, 3, 21, '2018-04-12 19:49:39', '2018-04-12 19:49:39'),
(3, 3, 22, '2018-04-12 19:51:07', '2018-04-12 19:51:07'),
(6, 3, 25, '2018-04-23 12:10:50', '2018-04-23 12:10:50'),
(7, 2, 25, '2018-04-23 12:10:50', '2018-04-23 12:10:50'),
(20, 6, 30, '2018-05-02 12:28:30', '2018-05-02 12:28:30'),
(16, 3, 3, '2018-04-26 22:07:18', '2018-04-26 22:07:18'),
(0, 7, 31, '2018-06-12 10:07:09', '2018-06-12 10:07:09'),
(0, 2, 0, '2018-06-25 03:53:40', '2018-06-25 03:53:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cat_cri_eva_criterio_evaluacion`
--
ALTER TABLE `cat_cri_eva_criterio_evaluacion`
  ADD PRIMARY KEY (`id_cat_cri_eva`),
  ADD KEY `fk_cat_cri_eva_criterio_evaluacion_pdg_asp_aspectos_evaluat_idx` (`id_pdg_asp`);

--
-- Indices de la tabla `cat_ctg_tra_categoria_trabajo_graduacion`
--
ALTER TABLE `cat_ctg_tra_categoria_trabajo_graduacion`
  ADD PRIMARY KEY (`id_cat_ctg_tra`);

--
-- Indices de la tabla `cat_eta_eva_etapa_evaluativa`
--
ALTER TABLE `cat_eta_eva_etapa_evaluativa`
  ADD PRIMARY KEY (`id_cat_eta_eva`);

--
-- Indices de la tabla `cat_idi_idioma`
--
ALTER TABLE `cat_idi_idioma`
  ADD PRIMARY KEY (`id_cat_idi`);

--
-- Indices de la tabla `cat_pro_proceso`
--
ALTER TABLE `cat_pro_proceso`
  ADD PRIMARY KEY (`id_cat_pro`);

--
-- Indices de la tabla `cat_ski_skill`
--
ALTER TABLE `cat_ski_skill`
  ADD PRIMARY KEY (`id_cat_ski`),
  ADD KEY `fk_cat_ski_skill_cat_tpo_ski_tipo_skill1_idx` (`id_tpo_ski`);

--
-- Indices de la tabla `cat_sta_estado`
--
ALTER TABLE `cat_sta_estado`
  ADD PRIMARY KEY (`id_cat_sta`),
  ADD KEY `fk_cat_sta_estado_cat_tpo_sta_tipo_estado1_idx` (`id_cat_tpo_sta`);

--
-- Indices de la tabla `cat_tit_titulos_profesionales`
--
ALTER TABLE `cat_tit_titulos_profesionales`
  ADD PRIMARY KEY (`id_cat_tit`);

--
-- Indices de la tabla `cat_tpo_doc_tipo_documento`
--
ALTER TABLE `cat_tpo_doc_tipo_documento`
  ADD PRIMARY KEY (`id_cat_tpo_doc`);

--
-- Indices de la tabla `cat_tpo_ski_tipo_skill`
--
ALTER TABLE `cat_tpo_ski_tipo_skill`
  ADD PRIMARY KEY (`id_tpo_ski`);

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
-- Indices de la tabla `dcn_cer_certificaciones`
--
ALTER TABLE `dcn_cer_certificaciones`
  ADD PRIMARY KEY (`id_dcn_cer`),
  ADD KEY `fk_dcn_cer_certificaciones_cat_idi_idioma1_idx` (`id_cat_idi`);

--
-- Indices de la tabla `dcn_exp_experiencia`
--
ALTER TABLE `dcn_exp_experiencia`
  ADD PRIMARY KEY (`id_dcn_exp`),
  ADD KEY `fk_dcn_exp_experiencia_cat_idi_idioma1_idx` (`id_cat_idi`),
  ADD KEY `fk_dcn_exp_experiencia_pdg_dcn_docente1_idx` (`id_pdg_dcn`);

--
-- Indices de la tabla `gen_est_estudiante`
--
ALTER TABLE `gen_est_estudiante`
  ADD PRIMARY KEY (`id_gen_est`);

--
-- Indices de la tabla `gen_par_parametros`
--
ALTER TABLE `gen_par_parametros`
  ADD PRIMARY KEY (`id_gen_par`),
  ADD KEY `fk_gen_par_parametros_gen_tpo_par_tipo_parametro1_idx` (`id_gen_tpo_par`);

--
-- Indices de la tabla `gen_tpo_par_tipo_parametro`
--
ALTER TABLE `gen_tpo_par_tipo_parametro`
  ADD PRIMARY KEY (`id_gen_tpo_par`);

--
-- Indices de la tabla `gen_usuario`
--
ALTER TABLE `gen_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pdg_apr_eta_tra_aprobador_etapa_trabajo`
--
ALTER TABLE `pdg_apr_eta_tra_aprobador_etapa_trabajo`
  ADD PRIMARY KEY (`id_pdg_apr_eta_tra`),
  ADD KEY `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_tri_gru_trib_idx` (`id_pdg_tri_gru`),
  ADD KEY `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_tra_gra_trab_idx` (`id_pdg_tra_gra`),
  ADD KEY `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_eta_eva_tra__idx` (`id_pdg_eta_eva_tra`);

--
-- Indices de la tabla `pdg_arc_doc_archivo_documento`
--
ALTER TABLE `pdg_arc_doc_archivo_documento`
  ADD PRIMARY KEY (`id_pdg_arc_doc`),
  ADD KEY `fk_pdg_arc_doc_archivo_documento_pdg_doc_documento1_idx` (`id_pdg_doc`);

--
-- Indices de la tabla `pdg_asp_aspectos_evaluativos`
--
ALTER TABLE `pdg_asp_aspectos_evaluativos`
  ADD PRIMARY KEY (`id_pdg_asp`),
  ADD KEY `fk_pdg_asp_aspectos_evaluativos_cat_eta_eva_etapa_evaluativ_idx` (`id_cat_eta_eva`);

--
-- Indices de la tabla `pdg_bit_tra_gra_bitacora_trabajo_graduacion`
--
ALTER TABLE `pdg_bit_tra_gra_bitacora_trabajo_graduacion`
  ADD PRIMARY KEY (`id_pdg_bit_tra_gra`),
  ADD KEY `fk_pdg_bit_tra_gra_bitacora_trabajo_graduacion_pdg_tra_gra__idx` (`id_pdg_tra_gra`);

--
-- Indices de la tabla `pdg_dcn_docente`
--
ALTER TABLE `pdg_dcn_docente`
  ADD PRIMARY KEY (`id_pdg_dcn`);

--
-- Indices de la tabla `pdg_def_defensa`
--
ALTER TABLE `pdg_def_defensa`
  ADD PRIMARY KEY (`idcat_pdg_def_defensa`),
  ADD KEY `fk_pdg_def_defensa_pdg_tra_gra_trabajo_graduacion1_idx` (`id_pdg_tra_gra`),
  ADD KEY `fk_pdg_def_defensa_cat_eta_eva_etapa_evaluativa1_idx` (`id_cat_eta_eva`);

--
-- Indices de la tabla `pdg_doc_documento`
--
ALTER TABLE `pdg_doc_documento`
  ADD PRIMARY KEY (`id_pdg_doc`),
  ADD KEY `fk_pdg_doc_documento_pdg_gru_grupo1_idx` (`id_pdg_gru`),
  ADD KEY `fk_pdg_doc_documento_cat_pdg_tpo_doc_tipo_documento1_idx` (`id_cat_tpo_doc`);

--
-- Indices de la tabla `pdg_eta_eva_tra_etapa_trabajo`
--
ALTER TABLE `pdg_eta_eva_tra_etapa_trabajo`
  ADD PRIMARY KEY (`id_pdg_eta_eva_tra`),
  ADD KEY `fk_pdg_eta_eva_tra_etapa_trabajo_pdg_tra_gra_trabajo_gradua_idx` (`id_pdg_tra_gra`),
  ADD KEY `fk_pdg_eta_eva_tra_etapa_trabajo_cat_eta_eva_etapa_evaluati_idx` (`id_cat_eta_eva`);

--
-- Indices de la tabla `pdg_gru_est_grupo_estudiante`
--
ALTER TABLE `pdg_gru_est_grupo_estudiante`
  ADD PRIMARY KEY (`id_pdg_gru_est`),
  ADD KEY `fk_pdg_gru_est_grupo_estudiante_pdg_gru_grupo_idx` (`id_pdg_gru`),
  ADD KEY `fk_pdg_gru_est_grupo_estudiante_gen_est_estudiante1_idx` (`id_gen_est`),
  ADD KEY `fk_pdg_gru_est_grupo_estudiante_cat_sta_estado1_idx` (`id_cat_sta`);

--
-- Indices de la tabla `pdg_gru_grupo`
--
ALTER TABLE `pdg_gru_grupo`
  ADD PRIMARY KEY (`id_pdg_gru`),
  ADD KEY `fk_pdg_gru_grupo_cat_sta_estado1_idx` (`id_cat_sta`);

--
-- Indices de la tabla `pdg_not_cri_tra_nota_criterio_trabajo`
--
ALTER TABLE `pdg_not_cri_tra_nota_criterio_trabajo`
  ADD PRIMARY KEY (`id_pdg_not_cri_tra`),
  ADD KEY `fk_pdg_not_cri_tra_nota_criterio_trabajo_cat_cri_eva_criter_idx` (`id_cat_cri_eva`),
  ADD KEY `fk_pdg_not_cri_tra_nota_criterio_trabajo_pdg_gru_est_grupo__idx` (`id_pdg_gru_est`);

--
-- Indices de la tabla `pdg_obs_observacion`
--
ALTER TABLE `pdg_obs_observacion`
  ADD PRIMARY KEY (`id_pdg_obs`),
  ADD KEY `fk_pdg_obs_observacion_gen_usuario1_idx` (`id_gen_usuario`);

--
-- Indices de la tabla `pdg_ppe_pre_perfil`
--
ALTER TABLE `pdg_ppe_pre_perfil`
  ADD PRIMARY KEY (`id_pdg_ppe`),
  ADD KEY `fk_pdg_ppe_pre_perfil_gen_usuario1_idx` (`id_gen_usuario`),
  ADD KEY `fk_pdg_ppe_pre_perfil_pdg_gru_grupo1_idx` (`id_pdg_gru`),
  ADD KEY `fk_pdg_ppe_pre_perfil_cat_sta_estado1_idx` (`id_cat_sta`),
  ADD KEY `fk_pdg_ppe_pre_perfil_cat_tpo_tra_gra_tipo_trabajo_graduaci_idx` (`id_cat_tpo_tra_gra`);

--
-- Indices de la tabla `pdg_tra_gra_trabajo_graduacion`
--
ALTER TABLE `pdg_tra_gra_trabajo_graduacion`
  ADD PRIMARY KEY (`id_pdg_tra_gra`),
  ADD KEY `fk_pdg_tra_gra_trabajo_graduacion_pdg_gru_est_grupo_estudia_idx` (`id_pdg_gru`),
  ADD KEY `fk_pdg_tra_gra_trabajo_graduacion_cat_tpo_tra_gra_tipo_trab_idx` (`id_cat_tpo_tra_gra`),
  ADD KEY `fk_pdg_tra_gra_trabajo_graduacion_cat_ctg_tra_categoria_traba1` (`id_cat_ctg_tra`),
  ADD KEY `fk_pdg_tra_gra_trabajo_graduacion_cat_sta_estado1_idx` (`id_cat_sta`);

--
-- Indices de la tabla `pdg_tri_gru_tribunal_grupo`
--
ALTER TABLE `pdg_tri_gru_tribunal_grupo`
  ADD PRIMARY KEY (`id_pdg_tri_gru`),
  ADD KEY `fk_pdg_tri_gru_tribunal_grupo_pdg_tri_rol_tribunal_rol1_idx` (`id_pdg_tri_rol`),
  ADD KEY `fk_pdg_tri_gru_tribunal_grupo_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  ADD KEY `fk_pdg_tri_gru_tribunal_grupo_pdg_gru_grupo1_idx` (`id_pdg_gru`);

--
-- Indices de la tabla `pdg_tri_rol_tribunal_rol`
--
ALTER TABLE `pdg_tri_rol_tribunal_rol`
  ADD PRIMARY KEY (`id_pdg_tri_rol`);

--
-- Indices de la tabla `rel_dcn_cer_docente_certificacion`
--
ALTER TABLE `rel_dcn_cer_docente_certificacion`
  ADD PRIMARY KEY (`id_rel_dcn_cer`),
  ADD KEY `fk_rel_dcn_cer_docente_certificacion_dcn_cer_certificacione_idx` (`id_dcn_cer`),
  ADD KEY `fk_rel_dcn_cer_docente_certificacion_pdg_dcn_docente1_idx` (`id_pdg_dcn`);

--
-- Indices de la tabla `rel_est_pro_estudiante_proceso`
--
ALTER TABLE `rel_est_pro_estudiante_proceso`
  ADD PRIMARY KEY (`id_rel_est_pro`),
  ADD KEY `fk_rel_est_pro_estudiante_proceso_cat_pro_proceso1_idx` (`id_cat_pro`),
  ADD KEY `fk_rel_est_pro_estudiante_proceso_cat_sta_estado1_idx` (`id_cat_sta`);

--
-- Indices de la tabla `rel_idi_dcn_idioma_docente`
--
ALTER TABLE `rel_idi_dcn_idioma_docente`
  ADD PRIMARY KEY (`id_rel_idi_dcn`),
  ADD KEY `fk_rel_idi_dcn_idioma_docente_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  ADD KEY `fk_rel_idi_dcn_idioma_docente_cat_idi_idioma1_idx` (`id_cat_idi`);

--
-- Indices de la tabla `rel_obs_gru_observacion_grupo`
--
ALTER TABLE `rel_obs_gru_observacion_grupo`
  ADD PRIMARY KEY (`id_rel_obs_gru`),
  ADD KEY `fk_rel_obs_gru_observacion_grupo_pdg_gru_grupo1_idx` (`id_pdg_gru`),
  ADD KEY `fk_rel_obs_gru_observacion_grupo_pdg_obs_observacion1_idx` (`id_pdg_obs`);

--
-- Indices de la tabla `rel_obs_ppe_observacion_preperfil`
--
ALTER TABLE `rel_obs_ppe_observacion_preperfil`
  ADD PRIMARY KEY (`id_rel_obs_ppe`),
  ADD KEY `fk_rel_obs_ppe_observacion_preperfil_pdg_obs_observacion1_idx` (`id_pdg_obs`),
  ADD KEY `fk_rel_obs_ppe_observacion_preperfil_pdg_ppe_pre_perfil1_idx` (`id_pdg_ppe`);

--
-- Indices de la tabla `rel_pdg_obs_arc_observacion_archivo`
--
ALTER TABLE `rel_pdg_obs_arc_observacion_archivo`
  ADD PRIMARY KEY (`id_rel_pdg_obs_arc`),
  ADD KEY `fk_rel_pdg_obs_arc_observacion_archivo_pdg_arc_doc_archivo__idx` (`id_pdg_arc_doc`),
  ADD KEY `fk_rel_pdg_obs_arc_observacion_archivo_pdg_obs_observacion1_idx` (`id_pdg_obs`);

--
-- Indices de la tabla `rel_rol_tri_eta_eva_tribunal_etapa`
--
ALTER TABLE `rel_rol_tri_eta_eva_tribunal_etapa`
  ADD PRIMARY KEY (`id_rel_rol_tri_eta_eva`),
  ADD KEY `fk_rel_rol_tri_eta_eva_tribunal_etapa_pdg_tri_rol_tribunal__idx` (`id_pdg_tri_rol`),
  ADD KEY `fk_rel_rol_tri_eta_eva_tribunal_etapa_cat_eta_eva_etapa_eva_idx` (`id_cat_eta_eva`);

--
-- Indices de la tabla `rel_ski_dcn_skill_docente`
--
ALTER TABLE `rel_ski_dcn_skill_docente`
  ADD PRIMARY KEY (`id_rel_ski_dcn`),
  ADD KEY `fk_rel_ski_dcn_skill_docente_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  ADD KEY `fk_rel_ski_dcn_skill_docente_cat_ski_skill1_idx` (`id_cat_ski`);

--
-- Indices de la tabla `rel_sta_eta_eva_estado_etapa_evaluativa`
--
ALTER TABLE `rel_sta_eta_eva_estado_etapa_evaluativa`
  ADD PRIMARY KEY (`id_rel_sta_eta_eva`),
  ADD KEY `fk_rel_sta_eta_eva_estado_etapa_evaluativa_cat_eta_eva_etap_idx` (`id_cat_eta_eva`),
  ADD KEY `fk_rel_sta_eta_eva_estado_etapa_evaluativa_cat_sta_estado1_idx` (`id_cat_sta`);

--
-- Indices de la tabla `rel_tit_dcn_titulo_docente`
--
ALTER TABLE `rel_tit_dcn_titulo_docente`
  ADD PRIMARY KEY (`id_rel_tit_dcn`),
  ADD KEY `fk_rel_tit_dcn_titulo_docente_pdg_dcn_docente1_idx` (`id_pdg_dcn`),
  ADD KEY `fk_rel_tit_dcn_titulo_docente_cat_tit_titulos_profesionales_idx` (`id_cat_tit`);

--
-- Indices de la tabla `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
--
ALTER TABLE `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
  ADD PRIMARY KEY (`id_rel_tpo_doc_eta_eva`),
  ADD KEY `fk_rel_tpo_doc_eta_eva_tipo_documento_etapa_eva_cat_pdg_tpo_idx` (`id_cat_tpo_doc`),
  ADD KEY `fk_rel_tpo_doc_eta_eva_tipo_documento_etapa_eva_cat_eta_eva_idx` (`id_cat_eta_eva`);

--
-- Indices de la tabla `rel_tpo_tra_eta_tipo_trabajo_etapa`
--
ALTER TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa`
  ADD PRIMARY KEY (`id_rel_tpo_tra_eta`),
  ADD KEY `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_tpo_tra_gra_tipo__idx` (`id_cat_tpo_tra_gra`),
  ADD KEY `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_eta_eva_etapa_eva_idx` (`id_cat_eta_eva`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cat_cri_eva_criterio_evaluacion`
--
ALTER TABLE `cat_cri_eva_criterio_evaluacion`
  MODIFY `id_cat_cri_eva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `cat_ctg_tra_categoria_trabajo_graduacion`
--
ALTER TABLE `cat_ctg_tra_categoria_trabajo_graduacion`
  MODIFY `id_cat_ctg_tra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `cat_eta_eva_etapa_evaluativa`
--
ALTER TABLE `cat_eta_eva_etapa_evaluativa`
  MODIFY `id_cat_eta_eva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `cat_idi_idioma`
--
ALTER TABLE `cat_idi_idioma`
  MODIFY `id_cat_idi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `cat_pro_proceso`
--
ALTER TABLE `cat_pro_proceso`
  MODIFY `id_cat_pro` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cat_ski_skill`
--
ALTER TABLE `cat_ski_skill`
  MODIFY `id_cat_ski` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `cat_sta_estado`
--
ALTER TABLE `cat_sta_estado`
  MODIFY `id_cat_sta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `cat_tit_titulos_profesionales`
--
ALTER TABLE `cat_tit_titulos_profesionales`
  MODIFY `id_cat_tit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `cat_tpo_doc_tipo_documento`
--
ALTER TABLE `cat_tpo_doc_tipo_documento`
  MODIFY `id_cat_tpo_doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `cat_tpo_ski_tipo_skill`
--
ALTER TABLE `cat_tpo_ski_tipo_skill`
  MODIFY `id_tpo_ski` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `cat_tpo_sta_tipo_estado`
--
ALTER TABLE `cat_tpo_sta_tipo_estado`
  MODIFY `id_cat_tpo_sta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `cat_tpo_tra_gra_tipo_trabajo_graduacion`
--
ALTER TABLE `cat_tpo_tra_gra_tipo_trabajo_graduacion`
  MODIFY `id_cat_tpo_tra_gra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `dcn_cer_certificaciones`
--
ALTER TABLE `dcn_cer_certificaciones`
  MODIFY `id_dcn_cer` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `dcn_exp_experiencia`
--
ALTER TABLE `dcn_exp_experiencia`
  MODIFY `id_dcn_exp` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `gen_est_estudiante`
--
ALTER TABLE `gen_est_estudiante`
  MODIFY `id_gen_est` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `gen_par_parametros`
--
ALTER TABLE `gen_par_parametros`
  MODIFY `id_gen_par` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `gen_tpo_par_tipo_parametro`
--
ALTER TABLE `gen_tpo_par_tipo_parametro`
  MODIFY `id_gen_tpo_par` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_apr_eta_tra_aprobador_etapa_trabajo`
--
ALTER TABLE `pdg_apr_eta_tra_aprobador_etapa_trabajo`
  MODIFY `id_pdg_apr_eta_tra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_arc_doc_archivo_documento`
--
ALTER TABLE `pdg_arc_doc_archivo_documento`
  MODIFY `id_pdg_arc_doc` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_asp_aspectos_evaluativos`
--
ALTER TABLE `pdg_asp_aspectos_evaluativos`
  MODIFY `id_pdg_asp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `pdg_bit_tra_gra_bitacora_trabajo_graduacion`
--
ALTER TABLE `pdg_bit_tra_gra_bitacora_trabajo_graduacion`
  MODIFY `id_pdg_bit_tra_gra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_dcn_docente`
--
ALTER TABLE `pdg_dcn_docente`
  MODIFY `id_pdg_dcn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `pdg_def_defensa`
--
ALTER TABLE `pdg_def_defensa`
  MODIFY `idcat_pdg_def_defensa` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_doc_documento`
--
ALTER TABLE `pdg_doc_documento`
  MODIFY `id_pdg_doc` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_eta_eva_tra_etapa_trabajo`
--
ALTER TABLE `pdg_eta_eva_tra_etapa_trabajo`
  MODIFY `id_pdg_eta_eva_tra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_gru_grupo`
--
ALTER TABLE `pdg_gru_grupo`
  MODIFY `id_pdg_gru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `pdg_not_cri_tra_nota_criterio_trabajo`
--
ALTER TABLE `pdg_not_cri_tra_nota_criterio_trabajo`
  MODIFY `id_pdg_not_cri_tra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_obs_observacion`
--
ALTER TABLE `pdg_obs_observacion`
  MODIFY `id_pdg_obs` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pdg_ppe_pre_perfil`
--
ALTER TABLE `pdg_ppe_pre_perfil`
  MODIFY `id_pdg_ppe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `pdg_tra_gra_trabajo_graduacion`
--
ALTER TABLE `pdg_tra_gra_trabajo_graduacion`
  MODIFY `id_pdg_tra_gra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `pdg_tri_gru_tribunal_grupo`
--
ALTER TABLE `pdg_tri_gru_tribunal_grupo`
  MODIFY `id_pdg_tri_gru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `pdg_tri_rol_tribunal_rol`
--
ALTER TABLE `pdg_tri_rol_tribunal_rol`
  MODIFY `id_pdg_tri_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `rel_dcn_cer_docente_certificacion`
--
ALTER TABLE `rel_dcn_cer_docente_certificacion`
  MODIFY `id_rel_dcn_cer` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_est_pro_estudiante_proceso`
--
ALTER TABLE `rel_est_pro_estudiante_proceso`
  MODIFY `id_rel_est_pro` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_idi_dcn_idioma_docente`
--
ALTER TABLE `rel_idi_dcn_idioma_docente`
  MODIFY `id_rel_idi_dcn` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_obs_gru_observacion_grupo`
--
ALTER TABLE `rel_obs_gru_observacion_grupo`
  MODIFY `id_rel_obs_gru` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_obs_ppe_observacion_preperfil`
--
ALTER TABLE `rel_obs_ppe_observacion_preperfil`
  MODIFY `id_rel_obs_ppe` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_pdg_obs_arc_observacion_archivo`
--
ALTER TABLE `rel_pdg_obs_arc_observacion_archivo`
  MODIFY `id_rel_pdg_obs_arc` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_rol_tri_eta_eva_tribunal_etapa`
--
ALTER TABLE `rel_rol_tri_eta_eva_tribunal_etapa`
  MODIFY `id_rel_rol_tri_eta_eva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `rel_ski_dcn_skill_docente`
--
ALTER TABLE `rel_ski_dcn_skill_docente`
  MODIFY `id_rel_ski_dcn` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_sta_eta_eva_estado_etapa_evaluativa`
--
ALTER TABLE `rel_sta_eta_eva_estado_etapa_evaluativa`
  MODIFY `id_rel_sta_eta_eva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `rel_tit_dcn_titulo_docente`
--
ALTER TABLE `rel_tit_dcn_titulo_docente`
  MODIFY `id_rel_tit_dcn` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
--
ALTER TABLE `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
  MODIFY `id_rel_tpo_doc_eta_eva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `rel_tpo_tra_eta_tipo_trabajo_etapa`
--
ALTER TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa`
  MODIFY `id_rel_tpo_tra_eta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cat_cri_eva_criterio_evaluacion`
--
ALTER TABLE `cat_cri_eva_criterio_evaluacion`
  ADD CONSTRAINT `fk_cat_cri_eva_criterio_evaluacion_pdg_asp_aspectos_evaluativ1` FOREIGN KEY (`id_pdg_asp`) REFERENCES `pdg_asp_aspectos_evaluativos` (`id_pdg_asp`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cat_ski_skill`
--
ALTER TABLE `cat_ski_skill`
  ADD CONSTRAINT `fk_cat_ski_skill_cat_tpo_ski_tipo_skill1` FOREIGN KEY (`id_tpo_ski`) REFERENCES `cat_tpo_ski_tipo_skill` (`id_tpo_ski`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cat_sta_estado`
--
ALTER TABLE `cat_sta_estado`
  ADD CONSTRAINT `fk_cat_sta_estado_cat_tpo_sta_tipo_estado1` FOREIGN KEY (`id_cat_tpo_sta`) REFERENCES `cat_tpo_sta_tipo_estado` (`id_cat_tpo_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dcn_cer_certificaciones`
--
ALTER TABLE `dcn_cer_certificaciones`
  ADD CONSTRAINT `fk_dcn_cer_certificaciones_cat_idi_idioma1` FOREIGN KEY (`id_cat_idi`) REFERENCES `cat_idi_idioma` (`id_cat_idi`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dcn_exp_experiencia`
--
ALTER TABLE `dcn_exp_experiencia`
  ADD CONSTRAINT `fk_dcn_exp_experiencia_cat_idi_idioma1` FOREIGN KEY (`id_cat_idi`) REFERENCES `cat_idi_idioma` (`id_cat_idi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_dcn_exp_experiencia_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `gen_par_parametros`
--
ALTER TABLE `gen_par_parametros`
  ADD CONSTRAINT `fk_gen_par_parametros_gen_tpo_par_tipo_parametro1` FOREIGN KEY (`id_gen_tpo_par`) REFERENCES `gen_tpo_par_tipo_parametro` (`id_gen_tpo_par`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_apr_eta_tra_aprobador_etapa_trabajo`
--
ALTER TABLE `pdg_apr_eta_tra_aprobador_etapa_trabajo`
  ADD CONSTRAINT `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_eta_eva_tra_et1` FOREIGN KEY (`id_pdg_eta_eva_tra`) REFERENCES `pdg_eta_eva_tra_etapa_trabajo` (`id_pdg_eta_eva_tra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_tra_gra_trabaj1` FOREIGN KEY (`id_pdg_tra_gra`) REFERENCES `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_apr_eta_tra_aprobador_etapa_trabajo_pdg_tri_gru_tribun1` FOREIGN KEY (`id_pdg_tri_gru`) REFERENCES `pdg_tri_gru_tribunal_grupo` (`id_pdg_tri_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_arc_doc_archivo_documento`
--
ALTER TABLE `pdg_arc_doc_archivo_documento`
  ADD CONSTRAINT `fk_pdg_arc_doc_archivo_documento_pdg_doc_documento1` FOREIGN KEY (`id_pdg_doc`) REFERENCES `pdg_doc_documento` (`id_pdg_doc`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_asp_aspectos_evaluativos`
--
ALTER TABLE `pdg_asp_aspectos_evaluativos`
  ADD CONSTRAINT `fk_pdg_asp_aspectos_evaluativos_cat_eta_eva_etapa_evaluativa1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_bit_tra_gra_bitacora_trabajo_graduacion`
--
ALTER TABLE `pdg_bit_tra_gra_bitacora_trabajo_graduacion`
  ADD CONSTRAINT `fk_pdg_bit_tra_gra_bitacora_trabajo_graduacion_pdg_tra_gra_tr1` FOREIGN KEY (`id_pdg_tra_gra`) REFERENCES `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_def_defensa`
--
ALTER TABLE `pdg_def_defensa`
  ADD CONSTRAINT `fk_pdg_def_defensa_cat_eta_eva_etapa_evaluativa1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_def_defensa_pdg_tra_gra_trabajo_graduacion1` FOREIGN KEY (`id_pdg_tra_gra`) REFERENCES `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_doc_documento`
--
ALTER TABLE `pdg_doc_documento`
  ADD CONSTRAINT `fk_pdg_doc_documento_cat_pdg_tpo_doc_tipo_documento1` FOREIGN KEY (`id_cat_tpo_doc`) REFERENCES `cat_tpo_doc_tipo_documento` (`id_cat_tpo_doc`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_doc_documento_pdg_gru_grupo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_eta_eva_tra_etapa_trabajo`
--
ALTER TABLE `pdg_eta_eva_tra_etapa_trabajo`
  ADD CONSTRAINT `fk_pdg_eta_eva_tra_etapa_trabajo_cat_eta_eva_etapa_evaluativa1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_eta_eva_tra_etapa_trabajo_pdg_tra_gra_trabajo_graduaci1` FOREIGN KEY (`id_pdg_tra_gra`) REFERENCES `pdg_tra_gra_trabajo_graduacion` (`id_pdg_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_gru_est_grupo_estudiante`
--
ALTER TABLE `pdg_gru_est_grupo_estudiante`
  ADD CONSTRAINT `fk_pdg_gru_est_grupo_estudiante_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_gru_est_grupo_estudiante_gen_est_estudiante1` FOREIGN KEY (`id_gen_est`) REFERENCES `gen_est_estudiante` (`id_gen_est`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_gru_est_grupo_estudiante_pdg_gru_grupo` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_gru_grupo`
--
ALTER TABLE `pdg_gru_grupo`
  ADD CONSTRAINT `fk_pdg_gru_grupo_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_not_cri_tra_nota_criterio_trabajo`
--
ALTER TABLE `pdg_not_cri_tra_nota_criterio_trabajo`
  ADD CONSTRAINT `fk_pdg_not_cri_tra_nota_criterio_trabajo_cat_cri_eva_criterio1` FOREIGN KEY (`id_cat_cri_eva`) REFERENCES `cat_cri_eva_criterio_evaluacion` (`id_cat_cri_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_not_cri_tra_nota_criterio_trabajo_pdg_gru_est_grupo_es1` FOREIGN KEY (`id_pdg_gru_est`) REFERENCES `pdg_gru_est_grupo_estudiante` (`id_pdg_gru_est`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_ppe_pre_perfil`
--
ALTER TABLE `pdg_ppe_pre_perfil`
  ADD CONSTRAINT `fk_pdg_ppe_pre_perfil_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_ppe_pre_perfil_cat_tpo_tra_gra_tipo_trabajo_graduacion1` FOREIGN KEY (`id_cat_tpo_tra_gra`) REFERENCES `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_ppe_pre_perfil_pdg_gru_grupo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_tra_gra_trabajo_graduacion`
--
ALTER TABLE `pdg_tra_gra_trabajo_graduacion`
  ADD CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_cat_ctg_tra_categoria_traba1` FOREIGN KEY (`id_cat_ctg_tra`) REFERENCES `cat_ctg_tra_categoria_trabajo_graduacion` (`id_cat_ctg_tra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_cat_tpo_tra_gra_tipo_trabaj1` FOREIGN KEY (`id_cat_tpo_tra_gra`) REFERENCES `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_tra_gra_trabajo_graduacion_pdg_gru_est_grupo_estudiante1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_est_grupo_estudiante` (`id_pdg_gru_est`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pdg_tri_gru_tribunal_grupo`
--
ALTER TABLE `pdg_tri_gru_tribunal_grupo`
  ADD CONSTRAINT `fk_pdg_tri_gru_tribunal_grupo_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_tri_gru_tribunal_grupo_pdg_gru_grupo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pdg_tri_gru_tribunal_grupo_pdg_tri_rol_tribunal_rol1` FOREIGN KEY (`id_pdg_tri_rol`) REFERENCES `pdg_tri_rol_tribunal_rol` (`id_pdg_tri_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_dcn_cer_docente_certificacion`
--
ALTER TABLE `rel_dcn_cer_docente_certificacion`
  ADD CONSTRAINT `fk_rel_dcn_cer_docente_certificacion_dcn_cer_certificaciones1` FOREIGN KEY (`id_dcn_cer`) REFERENCES `dcn_cer_certificaciones` (`id_dcn_cer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_dcn_cer_docente_certificacion_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_est_pro_estudiante_proceso`
--
ALTER TABLE `rel_est_pro_estudiante_proceso`
  ADD CONSTRAINT `fk_rel_est_pro_estudiante_proceso_cat_pro_proceso1` FOREIGN KEY (`id_cat_pro`) REFERENCES `cat_pro_proceso` (`id_cat_pro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_est_pro_estudiante_proceso_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_idi_dcn_idioma_docente`
--
ALTER TABLE `rel_idi_dcn_idioma_docente`
  ADD CONSTRAINT `fk_rel_idi_dcn_idioma_docente_cat_idi_idioma1` FOREIGN KEY (`id_cat_idi`) REFERENCES `cat_idi_idioma` (`id_cat_idi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_idi_dcn_idioma_docente_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_obs_gru_observacion_grupo`
--
ALTER TABLE `rel_obs_gru_observacion_grupo`
  ADD CONSTRAINT `fk_rel_obs_gru_observacion_grupo_pdg_gru_grupo1` FOREIGN KEY (`id_pdg_gru`) REFERENCES `pdg_gru_grupo` (`id_pdg_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_obs_gru_observacion_grupo_pdg_obs_observacion1` FOREIGN KEY (`id_pdg_obs`) REFERENCES `pdg_obs_observacion` (`id_pdg_obs`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_obs_ppe_observacion_preperfil`
--
ALTER TABLE `rel_obs_ppe_observacion_preperfil`
  ADD CONSTRAINT `fk_rel_obs_ppe_observacion_preperfil_pdg_obs_observacion1` FOREIGN KEY (`id_pdg_obs`) REFERENCES `pdg_obs_observacion` (`id_pdg_obs`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_obs_ppe_observacion_preperfil_pdg_ppe_pre_perfil1` FOREIGN KEY (`id_pdg_ppe`) REFERENCES `pdg_ppe_pre_perfil` (`id_pdg_ppe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_pdg_obs_arc_observacion_archivo`
--
ALTER TABLE `rel_pdg_obs_arc_observacion_archivo`
  ADD CONSTRAINT `fk_rel_pdg_obs_arc_observacion_archivo_pdg_arc_doc_archivo_do1` FOREIGN KEY (`id_pdg_arc_doc`) REFERENCES `pdg_arc_doc_archivo_documento` (`id_pdg_arc_doc`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_pdg_obs_arc_observacion_archivo_pdg_obs_observacion1` FOREIGN KEY (`id_pdg_obs`) REFERENCES `pdg_obs_observacion` (`id_pdg_obs`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_rol_tri_eta_eva_tribunal_etapa`
--
ALTER TABLE `rel_rol_tri_eta_eva_tribunal_etapa`
  ADD CONSTRAINT `fk_rel_rol_tri_eta_eva_tribunal_etapa_cat_eta_eva_etapa_evalu1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_rol_tri_eta_eva_tribunal_etapa_pdg_tri_rol_tribunal_rol1` FOREIGN KEY (`id_pdg_tri_rol`) REFERENCES `pdg_tri_rol_tribunal_rol` (`id_pdg_tri_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_ski_dcn_skill_docente`
--
ALTER TABLE `rel_ski_dcn_skill_docente`
  ADD CONSTRAINT `fk_rel_ski_dcn_skill_docente_cat_ski_skill1` FOREIGN KEY (`id_cat_ski`) REFERENCES `cat_ski_skill` (`id_cat_ski`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_ski_dcn_skill_docente_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_sta_eta_eva_estado_etapa_evaluativa`
--
ALTER TABLE `rel_sta_eta_eva_estado_etapa_evaluativa`
  ADD CONSTRAINT `fk_rel_sta_eta_eva_estado_etapa_evaluativa_cat_eta_eva_etapa_1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_sta_eta_eva_estado_etapa_evaluativa_cat_sta_estado1` FOREIGN KEY (`id_cat_sta`) REFERENCES `cat_sta_estado` (`id_cat_sta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_tit_dcn_titulo_docente`
--
ALTER TABLE `rel_tit_dcn_titulo_docente`
  ADD CONSTRAINT `fk_rel_tit_dcn_titulo_docente_cat_tit_titulos_profesionales1` FOREIGN KEY (`id_cat_tit`) REFERENCES `cat_tit_titulos_profesionales` (`id_cat_tit`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_tit_dcn_titulo_docente_pdg_dcn_docente1` FOREIGN KEY (`id_pdg_dcn`) REFERENCES `pdg_dcn_docente` (`id_pdg_dcn`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
--
ALTER TABLE `rel_tpo_doc_eta_eva_tipo_documento_etapa_eva`
  ADD CONSTRAINT `fk_rel_tpo_doc_eta_eva_tipo_documento_etapa_eva_cat_eta_eva_e1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_tpo_doc_eta_eva_tipo_documento_etapa_eva_cat_pdg_tpo_d1` FOREIGN KEY (`id_cat_tpo_doc`) REFERENCES `cat_tpo_doc_tipo_documento` (`id_cat_tpo_doc`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rel_tpo_tra_eta_tipo_trabajo_etapa`
--
ALTER TABLE `rel_tpo_tra_eta_tipo_trabajo_etapa`
  ADD CONSTRAINT `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_eta_eva_etapa_evalu1` FOREIGN KEY (`id_cat_eta_eva`) REFERENCES `cat_eta_eva_etapa_evaluativa` (`id_cat_eta_eva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rel_tpo_tra_eta_tipo_trabajo_etapa_cat_tpo_tra_gra_tipo_tr1` FOREIGN KEY (`id_cat_tpo_tra_gra`) REFERENCES `cat_tpo_tra_gra_tipo_trabajo_graduacion` (`id_cat_tpo_tra_gra`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
