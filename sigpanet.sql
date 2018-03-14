-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-03-2018 a las 21:17:49
-- Versión del servidor: 5.7.14
-- Versión de PHP: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sigpanet`
--
CREATE DATABASE IF NOT EXISTS `sigpanet` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sigpanet`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `Activar_Usuario`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Activar_Usuario` (`id_usuario_arg` VARCHAR(7), `id_confirmacion_arg` VARCHAR(255))  BEGIN
	UPDATE	
		gen_usuario SET activo = 1 
	WHERE id_usuario = id_usuario_arg and id_confirmacion = id_confirmacion_arg;
	
    END$$

DROP PROCEDURE IF EXISTS `Actualizar_Usuario`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Actualizar_Usuario` (`id_usuario_arg` CHAR(7), `nombre_usuario_arg` CHAR(30), `correo_usuario_arg` VARCHAR(50), `id_tipo_usuario_arg` CHAR(7), `id_perfil_usuario_arg` CHAR(7), `contrasenia_arg` VARCHAR(50), `confirme_contrasenia_arg` VARCHAR(50))  BEGIN
	IF (contrasenia_arg = confirme_contrasenia_arg) THEN
		select count(*) into @filas from gen_usuario where nombre_usuario = nombre_usuario_arg and id_usuario <>id_usuario_arg;
		if(@filas=0) then
			if(contrasenia_arg = '') then
				UPDATE gen_usuario 
				SET 
					nombre_usuario = nombre_usuario_arg, 
					correo_usuario = correo_usuario_arg,
					id_tipo_usuario = id_tipo_usuario_arg, 
					id_perfil_usuario = id_perfil_usuario_arg 
				WHERE 
					id_usuario = id_usuario_arg;
			else
				UPDATE gen_usuario 
				SET 
					contrasenia = md5(contrasenia_arg),
					nombre_usuario = nombre_usuario_arg, 
					correo_usuario = correo_usuario_arg,
					id_tipo_usuario = id_tipo_usuario_arg, 
					id_perfil_usuario = id_perfil_usuario_arg 
				WHERE 
					id_usuario = id_usuario_arg;
			end if;
			
			SET @retorna = 1;
		else
			SET @retorna = 2;
		end if;
	ELSE
		set @retorna = 0;
	END IF;
	SELECT @retorna AS RETORNA;
    END$$

DROP PROCEDURE IF EXISTS `Cambiar_Datos_Usuario`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Cambiar_Datos_Usuario` (`arg_nombre` NVARCHAR(255), `arg_apellidos` NVARCHAR(255), `arg_dui` NVARCHAR(255), `arg_fecha_nac` NVARCHAR(255), `arg_id_login` INT)  BEGIN
UPDATE persona p
	JOIN
		login l
	ON
		l.id_persona	= p.id_persona	 SET p.nombre = arg_nombre, p.apellidos = arg_apellidos, p.DUI = arg_dui, p.fecha_nac = STR_TO_DATE(arg_fecha_nac,'%d-%c-%Y')
WHERE l.id_login = arg_id_login;
    END$$

DROP PROCEDURE IF EXISTS `Cambiar_Password_Usuario`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Cambiar_Password_Usuario` (`actual_arg` NVARCHAR(255), `nuevo_arg` NVARCHAR(255), `confirm_arg` NVARCHAR(255), `id_usuario_arg` CHAR(7))  BEGIN
	declare filas int;
	IF (nuevo_arg = confirm_arg) THEN
		update gen_usuario SET contrasenia = nuevo_arg where contrasenia = actual_arg and id_usuario = id_usuario_arg;
		set filas = row_count();
		if (filas = 0) then
			select 0 as resultado;
		else
			select 1 as resultado;
		end if;
		
	else
	select 2 as resultado;
	end if;
    END$$

DROP PROCEDURE IF EXISTS `Consultar_Login_Usuario`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Consultar_Login_Usuario` (`usuario` NVARCHAR(255))  BEGIN
select * from usuario where (login = usuario or usuario = '0');
end$$

DROP PROCEDURE IF EXISTS `consultar_privilegio`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `consultar_privilegio` (`usuario` NVARCHAR(255))  BEGIN
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
    END$$

DROP PROCEDURE IF EXISTS `Crear_Contacto`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Crear_Contacto` (IN `dui_arg` CHAR(9), IN `id_institucion_arg` CHAR(17), IN `nombre_arg` VARCHAR(25), IN `apellido_arg` VARCHAR(25), IN `nombre_usuario_arg` CHAR(30), IN `password_arg` CHAR(50), IN `confirme_password_arg` CHAR(50), IN `descripcion_cargo_arg` VARCHAR(100), IN `telefono_arg` VARCHAR(9), IN `celular_arg` VARCHAR(9), IN `email_arg` VARCHAR(50))  BEGIN
	IF (password_arg = confirme_password_arg) THEN
		
		SELECT COUNT(*) INTO @cuenta_usuario FROM gen_usuario WHERE nombre_usuario = nombre_usuario_arg;
		IF (@cuenta_usuario = 0) THEN
			
			SELECT CASE WHEN MAX(CAST(id_usuario AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_usuario AS UNSIGNED))+1 END INTO @max_id_usuario FROM gen_usuario;
			SELECT CASE WHEN MAX(CAST(id_contacto AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_contacto AS UNSIGNED))+1 END INTO @max_id_contacto FROM pss_contacto;
			INSERT INTO 
				gen_usuario(
					id_usuario,
					id_tipo_usuario,
					id_perfil_usuario,
					nombre_usuario,
					contrasenia,
					contrasenia_temporal,
					fecha_creacion_usuario,
					correo_usuario,
					estado_usuario,
					ultima_conexion,
					activo,
					id_confirmacion
				) 
				VALUES(
					@max_id_usuario,
					1,
					1,
					nombre_usuario_arg,
					MD5(password_arg),
					'',
					NOW(),
					email_arg,
					'',
					'',
					0,
					''
				);
			INSERT INTO pss_contacto(
				id_contacto,
				id_institucion,
				nombre,
				apellido,
				descripcion_cargo,
				celular,
				telefono,
				email,
				id_login,
				dui
				)
				VALUES (
				@max_id_contacto,
				id_institucion_arg,
				nombre_arg,
				apellido_arg,
				descripcion_cargo_arg,
				celular_arg, 
				telefono_arg, 
				email_arg,
				@max_id_usuario,
				dui_arg
				);
			SET @retorna = 1;
		ELSE
			SET @retorna = 2;
		END IF;
			
	ELSE
		SET @retorna = 0;
	END IF;
	SELECT @retorna AS RETORNA;
    END$$

DROP PROCEDURE IF EXISTS `Crear_Docente`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Crear_Docente` (IN `carnet_arg` CHAR(7), IN `id_cargo_arg` CHAR(7), IN `id_cargo_administrativo_arg` CHAR(7), IN `id_departamento_arg` CHAR(7), IN `nombre_usuario_arg` CHAR(30), IN `password_arg` CHAR(50), IN `confirme_password_arg` CHAR(50), IN `nombre_arg` VARCHAR(30), IN `apellido_arg` VARCHAR(25), IN `direccion_arg` VARCHAR(150), IN `telefono_arg` DECIMAL(8,0), IN `celular_arg` DECIMAL(8,0), IN `email_arg` VARCHAR(50))  BEGIN
	IF (password_arg = confirme_password_arg) THEN
		SELECT COUNT(*) INTO @cuenta_usuario FROM gen_usuario WHERE nombre_usuario = nombre_usuario_arg;
		IF (@cuenta_usuario < 1) THEN
			SELECT CASE WHEN MAX(CAST(id_usuario AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_usuario AS UNSIGNED))+1 END INTO @max_id_usuario FROM gen_usuario;
			SELECT CASE WHEN MAX(CAST(id_docente AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_docente AS UNSIGNED))+1 END INTO @max_id_docente FROM gen_docente;
			INSERT INTO 
				gen_usuario(
					id_usuario,
					id_tipo_usuario,
					id_perfil_usuario,
					nombre_usuario,
					contrasenia,
					contrasenia_temporal,
					fecha_creacion_usuario,
					correo_usuario,
					estado_usuario,
					ultima_conexion,
					activo,
					id_confirmacion
				) 
				VALUES(
					@max_id_usuario,
					1,
					1,
					nombre_usuario_arg,
					MD5(password_arg),
					'',
					NOW(),
					email_arg,
					'',
					'',
					0,
					''
				);
			INSERT INTO gen_docente 
				(id_docente, 
				id_cargo, 
				id_departamento, 
				id_cargo_administrativo, 
				nombre, 
				apellido, 
				direccion, 
				telefono, 
				celular, 
				email, 
				id_login, 
				carnet
				)
				VALUES
				(@max_id_docente, 
				id_cargo_arg, 
				id_departamento_arg, 
				id_cargo_administrativo_arg, 
				nombre_arg, 
				apellido_arg, 
				direccion_arg, 
				telefono_arg, 
				celular_arg, 
				email_arg, 
				@max_id_usuario, 
				carnet_arg
				);
			SET @retorna = 1;
		ELSE
			SET @retorna = 2;
		END IF;
			
	ELSE
		SET @retorna = 0;
	END IF;
	SELECT @retorna AS RETORNA;
    END$$

DROP PROCEDURE IF EXISTS `Crear_Estudiante_PDG`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Crear_Estudiante_PDG` (`due_arg` CHAR(7), `nombre_arg` VARCHAR(30), `apellido_arg` VARCHAR(25), `email_arg` VARCHAR(50))  BEGIN
SET @retorna = 0;
SELECT CASE WHEN MAX(CAST(id_usuario AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_usuario AS UNSIGNED))+1 END INTO @max_id_usuario FROM gen_usuario;
SELECT COUNT(*) INTO @cuenta_estudiante FROM gen_estudiante WHERE id_due = due_arg;
IF (@cuenta_estudiante = 0) THEN
   SET @retorna = @retorna + 1;
   INSERT INTO
   gen_usuario(
       id_usuario,
       id_tipo_usuario,
       id_perfil_usuario,
       nombre_usuario,
       contrasenia,
       contrasenia_temporal,
       fecha_creacion_usuario,
       correo_usuario,
       estado_usuario,
       ultima_conexion,
       activo,
       id_confirmacion
   )
   VALUES(
       @max_id_usuario,
       1,
       1,
       due_arg,
       MD5(due_arg),
       '',
       NOW(),
       email_arg,
       '',
       '',
       0,
       ''
   );
INSERT INTO gen_estudiante (
   id_due,
   nombre,
   apellido,
   dui,
   direccion,
   telefono,
   celular,
   email,
   fecha_nac,
   correlativo_equipo,
   apertura_expediente,
   remision,
   fecha_remision,
   id_login,
   estado_tesis
   )
VALUES(
   due_arg,
   nombre_arg,
   apellido_arg,
   NULL,
   NULL,
   NULL,
   NULL,
   email_arg,
   NULL,
   NULL,
   NULL,
   NULL,
   NULL,
   @max_id_usuario,
   NULL
   );
END IF;
SELECT COUNT(*) INTO @cuenta_es FROM es WHERE id_tipo_estudiante = 'PDG' AND id_due = due_arg;
IF(@cuenta_es = 0) THEN
SET @retorna = @retorna + 2;
INSERT INTO es(id_tipo_estudiante,id_due)
VALUES
(
   'PDG',
   due_arg
);
END IF;
SELECT @retorna AS RETORNA;
END$$

DROP PROCEDURE IF EXISTS `Crear_Estudiante_PSS`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Crear_Estudiante_PSS` (`due_arg` CHAR(7), `nombre_arg` VARCHAR(30), `apellido_arg` VARCHAR(25), `email_arg` VARCHAR(50))  BEGIN
set @retorna = 0;
SELECT CASE WHEN MAX(CAST(id_usuario AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_usuario AS UNSIGNED))+1 END INTO @max_id_usuario FROM gen_usuario;
SELECT COUNT(*) INTO @cuenta_estudiante FROM gen_estudiante WHERE id_due = due_arg;
IF (@cuenta_estudiante = 0) THEN
   set @retorna = @retorna + 1;
   INSERT INTO
   gen_usuario(
       id_usuario,
       id_tipo_usuario,
       id_perfil_usuario,
       nombre_usuario,
       contrasenia,
       contrasenia_temporal,
       fecha_creacion_usuario,
       correo_usuario,
       estado_usuario,
       ultima_conexion,
       activo,
       id_confirmacion
   )
   VALUES(
       @max_id_usuario,
       1,
       1,
       due_arg,
       MD5(due_arg),
       '',
       NOW(),
       email_arg,
       '',
       '',
       0,
       ''
   );
INSERT INTO gen_estudiante (
   id_due,
   nombre,
   apellido,
   dui,
   direccion,
   telefono,
   celular,
   email,
   fecha_nac,
   correlativo_equipo,
   apertura_expediente,
   remision,
   fecha_remision,
   id_login,
   estado_tesis
   )
VALUES(
   due_arg,
   nombre_arg,
   apellido_arg,
   NULL,
   NULL,
   NULL,
   NULL,
   email_arg,
   NULL,
   NULL,
   NULL,
   NULL,
   NULL,
   @max_id_usuario,
   NULL
   );
end if;
SELECT COUNT(*) INTO @cuenta_es FROM es WHERE id_tipo_estudiante = 'PSS' AND id_due = due_arg;
IF(@cuenta_es = 0) THEN
set @retorna = @retorna + 2;
INSERT INTO es(id_tipo_estudiante,id_due)
VALUES
(
   'PSS',
   due_arg
);
END IF;
select @retorna as RETORNA;
END$$

DROP PROCEDURE IF EXISTS `Crear_Id_Confirmacion`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Crear_Id_Confirmacion` (IN `id_usuario_arg` CHAR(7), IN `id_confirmacion_arg` VARCHAR(255))  BEGIN
	update	
		gen_usuario set id_confirmacion = id_confirmacion_arg 
	where id_usuario = id_usuario_arg;	
    END$$

DROP PROCEDURE IF EXISTS `Crear_Linea_PERA`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Crear_Linea_PERA` (`due_arg` CHAR(7), `nombre_arg` VARCHAR(30), `apellido_arg` VARCHAR(25), `cum_arg` DECIMAL(4,2), `materia_arg` CHAR(7), `nota_arg` DECIMAL(3,1), `email_arg` VARCHAR(50))  BEGIN
SET @retorna = 0;
SELECT CASE WHEN MAX(CAST(id_usuario AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_usuario AS UNSIGNED))+1 END INTO @max_id_usuario FROM gen_usuario;
SELECT COUNT(*) INTO @cuenta_estudiante FROM gen_estudiante WHERE id_due = due_arg;
IF (@cuenta_estudiante = 0) THEN
   SET @retorna = @retorna + 1;
   INSERT INTO
       gen_usuario(
           id_usuario,
           id_tipo_usuario,
           id_perfil_usuario,
           nombre_usuario,
           contrasenia,
           contrasenia_temporal,
           fecha_creacion_usuario,
           correo_usuario,
           estado_usuario,
           ultima_conexion,
           activo,
           id_confirmacion
       )
   VALUES(
       @max_id_usuario,
       1,
       1,
       due_arg,
       MD5(due_arg),
       '',
       NOW(),
       email_arg,
       '',
       '',
       0,
       ''
   );
   INSERT INTO gen_estudiante (
       id_due,
       nombre,
       apellido,
       dui,
       direccion,
       telefono,
       celular,
       email,
       fecha_nac,
       correlativo_equipo,
       apertura_expediente,
       remision,
       fecha_remision,
       id_login,
       estado_tesis
       )
   VALUES(
       due_arg,
       nombre_arg,
       apellido_arg,
       NULL,
       NULL,
       NULL,
       NULL,
       email_arg,
       NULL,
       NULL,
       NULL,
       NULL,
       NULL,
       @max_id_usuario,
       NULL
       );
END IF;
SELECT COUNT(*) INTO @cuenta_es FROM es WHERE id_tipo_estudiante = 'PERA' AND id_due = due_arg;
IF(@cuenta_es = 0) THEN
   SET @retorna = @retorna + 2;
   INSERT INTO es(id_tipo_estudiante,id_due)
   VALUES
   (
       'PERA',
       due_arg
   );
END IF;
SELECT COUNT(*) INTO @cuenta_per_detalle FROM per_detalle WHERE id_due = due_arg;
IF(@cuenta_per_detalle = 0) THEN
   SET @retorna = @retorna+4;
   SELECT MONTH(NOW()) INTO @mes ;
   SELECT YEAR(NOW()) INTO @anio;
   IF(@mes >= 1 AND @mes <= 6) THEN
       SET @ciclo = 1;
   ELSE
       SET @ciclo = 2;
   END IF;
   
   IF (cum_arg >= 6.0 AND cum_arg <= 6.33) THEN
       SET @unidades_valorativas = 16;
   ELSEIF (cum_arg > 6.33 AND cum_arg <= 6.66) THEN
       SET @unidades_valorativas = 12;
   ELSEIF (cum_arg > 6.66 AND cum_arg <= 6.99) THEN
       SET @unidades_valorativas = 8;
   END IF;
   INSERT INTO per_detalle(
       id_due,
       cum,
       uv,
       ciclo,
       anio,
       estado
   )
   VALUES(
       due_arg,
       cum_arg,
       @unidades_valorativas,
       @ciclo,
       @anio,
       'i'
   );
   SET @ultimo_id_detalle_pera = LAST_INSERT_ID();
ELSE
   SELECT id_detalle_pera INTO @ultimo_id_detalle_pera FROM per_detalle WHERE id_due = due_arg;
END IF;
SELECT COUNT(*) INTO @cuenta_per_area_deficitaria FROM per_area_deficitaria WHERE id_detalle_pera = @ultimo_id_detalle_pera AND id_materia = materia_arg;
IF (@cuenta_per_area_deficitaria = 0) THEN
   SET @retorna = @retorna+8;
   INSERT INTO per_area_deficitaria(
       id_detalle_pera,
       id_materia,
       nota
   )
   VALUES(
       @ultimo_id_detalle_pera,
       materia_arg,
       nota_arg
   );
   
END IF;
SELECT @retorna AS RETORNA;
END$$

DROP PROCEDURE IF EXISTS `Crear_Usuario`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Crear_Usuario` (`arg_proc_admin` INT, `arg_tipo_usuario` INT, `arg_email` NVARCHAR(255), `arg_nombre` NVARCHAR(255), `arg_apellidos` NVARCHAR(255), `arg_dui` NVARCHAR(255), `arg_fecha_nac` NVARCHAR(255), `arg_id_confirmacion` NVARCHAR(255))  BEGIN
	
	SELECT COUNT(*) INTO @cuenta_email from login where email = arg_email;
	if (@cuenta_email < 1) then
		INSERT INTO persona SET nombre = arg_nombre, apellidos = arg_apellidos, fecha_nac = STR_TO_DATE(arg_fecha_nac,'%d-%c-%Y'), dui = arg_dui;
		SET @ultimo_id_persona = LAST_INSERT_ID();
		insert into login set password = md5('Clave123'), email = arg_email, id_confirmacion = arg_id_confirmacion, activo = 0, id_persona = @ultimo_id_persona;
		SET @ultimo_id_login = LAST_INSERT_ID();
		set @retorna = 1;
	else
		select id_login, id_persona INTO @ultimo_id_login,@ultimo_id_persona from login where email = arg_email;
		set @retorna = 2;
	end if;
	SELECT count(*) INTO @cuenta_usuario from usuario where id_login = @ultimo_id_login and id_tipo_usuario = arg_tipo_usuario and id_proc_admin = arg_proc_admin and id_persona = @ultimo_id_persona;
	if(@cuenta_usuario < 1) then
		INSERT INTO usuario SET activo = 0, id_login = @ultimo_id_login, id_tipo_usuario = arg_tipo_usuario, id_proc_admin = arg_proc_admin, id_persona = @ultimo_id_persona, habilitado = 1;
		
	else
		set @retorna = 0;
		
	end if;
	select @retorna AS RETORNA;
	
END$$

DROP PROCEDURE IF EXISTS `Eliminar_Estudiante`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Eliminar_Estudiante` (`due_arg` CHAR(7))  BEGIN
SET @comprobacion1 = 0;
SET @comprobacion2 = 0;
SET @comprobacion3 = 0;
SET @comprobacion4 = 0;
SET @comprobaciontotal = 0;
SET @espera = 0;
set @retorna = 0;
SET @existe = 0;
SELECT COUNT(*) INTO @existe FROM gen_estudiante WHERE id_due = due_arg;

SELECT COUNT(*) INTO @comprobacion1 FROM asignado WHERE id_due = due_arg; SELECT COUNT(*) INTO @comprobacion2 FROM conforma WHERE id_due = due_arg; SELECT COUNT(*) INTO @comprobacion3 FROM gen_estudiante WHERE id_due = due_arg AND (apertura_expediente_pss IS NOT NULL OR apertura_expediente IS NOT NULL
OR estado_tesis IS NOT NULL); SELECT COUNT(*) INTO @comprobacion4 FROM per_detalle WHERE id_due = due_arg AND estado <> 'i'; SELECT @comprobacion1+@comprobacion2+@comprobacion3+@comprobacion4 INTO @comprobaciontotal;
if(@comprobaciontotal = 0 and @existe = 1) then
	SELECT COUNT(*) INTO @espera FROM es WHERE id_due = due_arg AND id_tipo_estudiante = 'PERA';
		IF(@espera >= 1) THEN
		DELETE b FROM per_detalle a JOIN per_area_deficitaria b ON a.id_detalle_pera = b.id_detalle_pera
		WHERE a.id_due = due_arg;
		DELETE FROM per_detalle WHERE ID_DUE = due_arg;
	END IF;
	DELETE FROM es WHERE id_due = due_arg;
	DELETE b FROM gen_estudiante a JOIN gen_usuario b ON a.id_login = b.id_usuario WHERE a.id_due = due_arg;
	delete from gen_estudiante where id_due = due_arg;
	set @retorna = 1;
end if;
	select @retorna as RETORNA;
    END$$

DROP PROCEDURE IF EXISTS `Leer_Datos_Usuario`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Leer_Datos_Usuario` (`email` NVARCHAR(255))  BEGIN
	SELECT 
	p.nombre, p.apellidos, p.fecha_nac	, p.DUI	, l.password,l.id_login	
	FROM
	usuario u
	JOIN
		login l
	ON
		l.id_login	= u.id_login	
	JOIN
		tipo_usuario tu
	ON
		tu.id_tipo_usuario	= u.id_tipo_usuario		
	JOIN
		persona p
	ON
		p.id_persona	 = u.id_persona	
	LEFT JOIN
		proc_admin	pa
	ON
		pa.id_proc_admin	= u.id_proc_admin
	
	WHERE (l.email = email)
	LIMIT 1;
    END$$

DROP PROCEDURE IF EXISTS `Login`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Login` (`usuario` NVARCHAR(255), `clave` NVARCHAR(255))  BEGIN
	select login, activo from usuario where (login = usuario and password = clave and activo = 1);
    END$$

DROP PROCEDURE IF EXISTS `per_proc_asi_doc`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_doc` (`id_due` CHAR(7), `uv_asignadas` TINYINT, `docente_asignado` CHAR(7), `comentario` VARCHAR(1000), `docente_general` CHAR(7))  BEGIN
declare _id_detalle_pera int(11) UNSIGNED;
declare _id_tipo_pera int(11) UNSIGNED;
select p.id_detalle_pera into _id_detalle_pera
from per_detalle p
where p.id_due= id_due and p.estado= 'd';
INSERT INTO
   per_tipo (id_detalle_pera,uv,docente_general,comentario)
VALUES (_id_detalle_pera,uv_asignadas,docente_general,comentario);
select max(id_tipo_pera) into _id_tipo_pera
from per_tipo;
INSERT INTO asignado (id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss)
       VALUES (id_due,docente_asignado,'PER',10,_id_tipo_pera);
end$$

DROP PROCEDURE IF EXISTS `per_proc_asi_doc_consultar`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_doc_consultar` (`id_due` CHAR(7))  BEGIN
   SELECT    p.uv as uv_pera,
       concat_ws(' - ',`p`.`ciclo`,`p`.`anio`) AS `ciclo`,
       p.observaciones FROM per_detalle p
                   WHERE p.id_due = id_due;
END$$

DROP PROCEDURE IF EXISTS `per_proc_asi_doc_delete`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_doc_delete` (`id_tipo_pera` INT(11) UNSIGNED, `docente_general` CHAR(7))  BEGIN
   declare _docente_general char(7);
   select t.docente_general into _docente_general
   from per_tipo t
       where t.id_tipo_pera = id_tipo_pera ;
   if _docente_general =  docente_general then
       DELETE a FROM asignado a
           where a.id_proceso='PER' and a.id_cargo=10 and a.correlativo_tutor_ss=id_tipo_pera;
       DELETE t from per_tipo t
           where t.id_tipo_pera = id_tipo_pera;
   END if;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_asi_doc_update`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_doc_update` (`id_tipo_pera` INT(11) UNSIGNED, `id_due` CHAR(7), `uv_asignadas` TINYINT, `docente_asignado` CHAR(7), `comentario` VARCHAR(1000), `docente_general` CHAR(7))  BEGIN
   declare _id_detalle_pera int(11) UNSIGNED;
   declare _id_tipo_pera int(11) UNSIGNED;
   if uv_asignadas!= '' then
       UPDATE per_tipo t
           set t.uv=uv_asignadas
           where t.id_tipo_pera=id_tipo_pera and t.docente_general=docente_general;
   END if;
   UPDATE per_tipo t
       set t.comentario=comentario
       where t.id_tipo_pera=id_tipo_pera and t.docente_general=docente_general;
   if docente_asignado!='' then
       UPDATE asignado a
           set a.id_docente = docente_asignado
           where a.id_due=id_due and a.id_proceso='PER' and a.id_cargo=10 and a.correlativo_tutor_ss=id_tipo_pera;
   end if;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_asi_doc_uv_asignables`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_doc_uv_asignables` (`id_due` CHAR(7))  BEGIN
SELECT t.id_detalle_pera, d.uv uv_pera, sum(t.uv) uv_total_asignadas
    from per_detalle d join per_tipo t
        on d.id_detalle_pera = t.id_detalle_pera
        	 where d.id_due = id_due and d.estado = 'd'
		GROUP by t.id_detalle_pera;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_asi_gen`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_gen` (IN `id_due` CHAR(7), IN `id_docente` CHAR(7), IN `id_detalle_pera` INT(11), IN `observaciones` VARCHAR(1000))  BEGIN
       INSERT INTO asignado (id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss)
           VALUES (id_due,id_docente,'PER',3,id_detalle_pera);
           
             UPDATE per_detalle as d
           set d.estado = 'd'            
           where d.id_detalle_pera=id_detalle_pera;    
if observaciones != "" then
       UPDATE per_detalle as d
           set d.observaciones = observaciones            
           where d.id_detalle_pera=id_detalle_pera;
end if;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_asi_gen_areas_deficitarias`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_gen_areas_deficitarias` (`id_detalle_pera` INT(11) UNSIGNED)  BEGIN
SELECT a.id_detalle_pera, a.id_materia, m.nombre, nota, m.id_departamento, d.nombre as Departamento, ROUND(promedio_departamento.promedio,2) nota_departamento
from per_area_deficitaria a join gen_materia m
       on a.id_materia=m.id_materia
           join gen_departamento d
               on m.id_departamento = d.id_departamento
               right join (SELECT d.nombre, avg(nota) as promedio
                                   from per_area_deficitaria a join gen_materia m
                                       on a.id_materia=m.id_materia
                                           join gen_departamento d
                                               on m.id_departamento = d.id_departamento
                                       where a.id_detalle_pera=id_detalle_pera
                                       GROUP by d.nombre) as promedio_departamento
               on d.nombre = promedio_departamento.nombre
       where a.id_detalle_pera=id_detalle_pera;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_asi_gen_consultar`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_gen_consultar` (`id_due` CHAR(7))  BEGIN
   SELECT id_detalle_pera, cum,uv,ciclo,anio FROM per_detalle d
                   WHERE d.id_due = id_due and d.estado='i';
    END$$

DROP PROCEDURE IF EXISTS `per_proc_asi_gen_delete`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_gen_delete` (IN `id_detalle_pera` INT(11))  BEGIN
   
   DELETE FROM asignado
    WHERE correlativo_tutor_ss=id_detalle_pera and id_proceso='PER' AND id_cargo=3;
   UPDATE per_detalle as d
    set d.estado = 'i'            
    where d.id_detalle_pera=id_detalle_pera;    
   UPDATE per_detalle d
   SET observaciones = ""
   WHERE d.id_detalle_pera= id_detalle_pera;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_asi_gen_update`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_asi_gen_update` (IN `id_due` CHAR(7), IN `id_docente` CHAR(7), IN `id_detalle_pera` INT(11), IN `observaciones` VARCHAR(1000))  BEGIN  
   DECLARE fila int;    
   SELECT COUNT(*) INTO fila
   FROM asignado a
       where a.id_due =id_due and a.id_proceso='PER' and a.id_cargo= 3 and a.correlativo_tutor_ss =id_detalle_pera;
   if fila = 0 then
       INSERT INTO asignado    (id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss)
           VALUES (id_due,id_docente,'PER',3,id_detalle_pera);
       UPDATE per_detalle as d
           set d.estado = 'd'            
           where d.id_detalle_pera=id_detalle_pera;
   end if;
   if fila=1 then
       if id_docente != "" then    
           UPDATE asignado a
           set a.id_docente=id_docente
           where a.correlativo_tutor_ss = id_detalle_pera and a.id_due = id_due and id_proceso='PER' and id_cargo=3;          
       END if;    
   end if;
   if observaciones != "" then
       UPDATE per_detalle as d
           set d.observaciones = observaciones            
           where d.id_detalle_pera=id_detalle_pera;
   end if;    
    END$$

DROP PROCEDURE IF EXISTS `per_proc_cie_per_update`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_cie_per_update` (`_id_detalle_pera` INT(11) UNSIGNED, `_estado_registro` CHAR(1))  BEGIN

	declare _estado char(1);



	select rg.estado into _estado from per_registro_nota rg

		where rg.id_detalle_pera = _id_detalle_pera;

		

	update per_registro_nota rg

		set estado_registro = _estado_registro

		where rg.id_detalle_pera = _id_detalle_pera;



	if(_estado_registro = 'a') then

		update per_detalle d

			set d.estado = _estado

			where d.id_detalle_pera = _id_detalle_pera;

	elseif(_estado_registro = 'c') then

		update per_detalle d

			set d.estado = _estado_registro

			where d.id_detalle_pera = _id_detalle_pera;

	END if;

END$$

DROP PROCEDURE IF EXISTS `per_proc_consultar_area_deficitaria`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_consultar_area_deficitaria` (`due` CHAR(7))  BEGIN
   SELECT area_deficitaria,uv,ciclo,anio FROM per_detalle
                   WHERE id_due = due;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_def_tip_delete`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_def_tip_delete` (`id_tipo_pera` INT(11) UNSIGNED)  BEGIN
UPDATE per_tipo t
   set t.tipo='', t.descripcion='', t.inicio='', t.fin=''
       where t.id_tipo_pera=id_tipo_pera;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_def_tip_update`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_def_tip_update` (`id_tipo_pera` INT(11) UNSIGNED, `tipo` VARCHAR(25), `descripcion` VARCHAR(1000), `inicio` DATE, `fin` DATE)  BEGIN
   
   UPDATE per_tipo t
       set t.tipo=tipo, t.descripcion=descripcion, t.inicio=inicio, t.fin=fin
       where t.id_tipo_pera=id_tipo_pera;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_est_eva`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_est_eva` (`id_tipo_pera` INT(11), `nombre` VARCHAR(30), `fecha` DATE, `descripcion` LONGTEXT, `porcentaje` DECIMAL(3,2), `nota` DECIMAL(4,2))  BEGIN



	INSERT INTO `per_evaluacion` (`id_tipo_pera`, `nombre`, `fecha`, `descripcion`, `porcentaje`, `nota`)

		VALUES (id_tipo_pera, nombre, fecha, descripcion, porcentaje, nota);

END$$

DROP PROCEDURE IF EXISTS `per_proc_est_eva_consultar`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_est_eva_consultar` (`id_tipo_pera` INT(11) UNSIGNED)  BEGIN
select 
	v.estudiante,v.ciclo,v.descripcion as descripcion_pera
from per_view_def_tip v
	where v.id_tipo_pera=id_tipo_pera;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_est_eva_delete`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_est_eva_delete` (IN `id_evaluacion` INT(11) UNSIGNED)  BEGIN
   
   DELETE e FROM per_evaluacion e
   WHERE e.id_evaluacion=id_evaluacion;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_est_eva_porcentaje_total`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_est_eva_porcentaje_total` (`id_tipo_pera` INT(11) UNSIGNED)  BEGIN



  SELECT sum(porcentaje) as porcentaje_total from per_evaluacion p

    WHERE p.id_tipo_pera = id_tipo_pera;

END$$

DROP PROCEDURE IF EXISTS `per_proc_est_eva_update`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_est_eva_update` (`id_evaluacion` INT(11), `nombre` VARCHAR(30), `fecha` DATE, `descripcion` LONGTEXT, `porcentaje` DECIMAL(3,2), `nota` DECIMAL(4,2))  BEGIN
UPDATE `per_evaluacion` e
   SET e.`nombre` = nombre, e.`fecha` = fecha, e.`descripcion` =descripcion, e.`porcentaje` = porcentaje, e.`nota` = nota
   WHERE e.`id_evaluacion` = id_evaluacion ;
    END$$

DROP PROCEDURE IF EXISTS `per_proc_insertar_def_tip`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_insertar_def_tip` (`id_tipo_pera` CHAR(7), `id_detalle` CHAR(7), `tipo` VARCHAR(25), `uv` INT(11), `descripcion` LONGTEXT, `inicio` DATE, `fin` DATE)  BEGIN
   
   declare detalle_pera char(7);
   set detalle_pera = (SELECT id_detalle_pera FROM per_detalle
               WHERE id_due = id_detalle);
               
   INSERT INTO per_tipo (id_tipo_pera,id_detalle_pera,tipo,uv,descripcion,inicio,fin)
   vALUES (id_tipo_pera,detalle_pera,tipo,uv,descripcion,inicio,fin);
    END$$

DROP PROCEDURE IF EXISTS `per_proc_reg_not`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_reg_not` (`_id_detalle_pera` INT(11) UNSIGNED, `_ciclo` TINYINT, `_anio` YEAR(4), `_docente_mentor` VARCHAR(100), `_fecha_finalizacion` DATE, `_descripcion` VARCHAR(1000), `_n1` DECIMAL(4,2), `_n2` DECIMAL(4,2), `_n3` DECIMAL(4,2), `_n4` DECIMAL(4,2), `_n5` DECIMAL(4,2), `_p1` DECIMAL(2,2), `_p2` DECIMAL(2,2), `_p3` DECIMAL(2,2), `_p4` DECIMAL(2,2), `_p5` DECIMAL(2,2), `_promedio` DECIMAL(3,1), `_estado` CHAR(1))  BEGIN



	INSERT INTO per_registro_nota(id_detalle_pera,estado,ciclo,anio,docente_mentor,fecha_finalizacion,descripcion,n1,n2,n3,n4,n5,p1,p2,p3,p4,p5,promedio) 

		VALUES(_id_detalle_pera,_estado,_ciclo,_anio,_docente_mentor,_fecha_finalizacion,_descripcion,_n1,_n2,_n3,_n4,_n5,_p1,_p2,_p3,_p4,_p5,_promedio);



	update per_detalle d

		set d.estado = _estado

		where d.id_detalle_pera = _id_detalle_pera;



END$$

DROP PROCEDURE IF EXISTS `per_proc_reg_not_delete`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_reg_not_delete` (`_id_registro_nota` INT(11) UNSIGNED)  BEGIN

	declare _id_detalle_pera int(11) unsigned;



	select rg.id_detalle_pera into _id_detalle_pera from per_registro_nota rg

		where rg.id_registro_nota = _id_registro_nota;



	delete rg from per_registro_nota rg

		where rg.id_registro_nota = _id_registro_nota;



	update per_detalle d

		set d.estado = 'd'

		where id_detalle_pera = _id_detalle_pera;

END$$

DROP PROCEDURE IF EXISTS `per_proc_reg_not_final`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_reg_not_final` (`_id_detalle_pera` INT(11))  BEGIN



	select 

		v.id_detalle_pera,

		ROUND(sum(nota_tipo_uv)/uv_detalle,1) promedio

	from per_view_reg_not_final v

	where v.id_detalle_pera = _id_detalle_pera

	group by v.id_detalle_pera;



END$$

DROP PROCEDURE IF EXISTS `per_proc_reg_not_update`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `per_proc_reg_not_update` (`_id_registro_nota` INT(11) UNSIGNED, `_fecha_finalizacion` DATE, `_descripcion` VARCHAR(1000), `_estado` CHAR(1))  BEGIN

	declare _id_detalle_pera int(11) unsigned;



	select rg.id_detalle_pera into _id_detalle_pera from per_registro_nota rg

		where rg.id_registro_nota = _id_registro_nota;

		

	update per_registro_nota rg

		set rg.fecha_finalizacion = _fecha_finalizacion, rg.descripcion = _descripcion, rg.estado = _estado

		where rg.id_registro_nota = _id_registro_nota;



	update per_detalle d

		set d.estado = _estado

		where d.id_detalle_pera = _id_detalle_pera;

END$$

DROP PROCEDURE IF EXISTS `sp_pss_actualizar_contacto`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `sp_pss_actualizar_contacto` (`id_contac` VARCHAR(10), `id_institucion` VARCHAR(17), `nombre` VARCHAR(25), `apellido` VARCHAR(25), `descripcion_cargo` VARCHAR(100), `celular` VARCHAR(9), `telefono` VARCHAR(9), `email` VARCHAR(50))  BEGIN
UPDATE pss_contacto SET id_institucion=id_institucion,nombre=nombre,apellido=apellido,descripcion_cargo=descripcion_cargo,celular=celular,telefono=telefono,email=email WHERE id_contacto=id_contac;
END$$

DROP PROCEDURE IF EXISTS `sp_pss_insertar_contacto`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `sp_pss_insertar_contacto` (`id_contacto` VARCHAR(10), `id_institucion` VARCHAR(17), `nombre` VARCHAR(25), `apellido` VARCHAR(25), `descripcion_cargo` VARCHAR(100), `celular` VARCHAR(9), `telefono` VARCHAR(9), `email` VARCHAR(50))  BEGIN
		
		INSERT INTO pss_contacto(id_contacto,id_institucion,nombre,apellido,descripcion_cargo,celular,telefono,email) VALUES(id_contacto,id_institucion,nombre,apellido,descripcion_cargo,celular,telefono,email);
	
    END$$

DROP PROCEDURE IF EXISTS `Verificar_Usuario`$$
CREATE DEFINER=`sigpanet`@`%` PROCEDURE `Verificar_Usuario` (`nombre_usuario_arg` NVARCHAR(255), `contrasenia_arg` NVARCHAR(255))  BEGIN
	SELECT 
	gus.id_usuario id_login,
	gus.activo, 
	gus.correo_usuario email,
	doc_est.nombre,
	doc_est.apellido,
	doc_est.tipo,
	doc_est.id_doc_est,
	doc_est.id_cargo_administrativo,
	doc_est.id_cargo,
	doc_est.tipo_estudiante
	FROM
		gen_usuario gus
	JOIN
		(
			SELECT	
				gd1.id_cargo,
				gd1.id_cargo_administrativo,
				gd1.id_docente id_doc_est,
				gd1.id_login,
				gd1.nombre,
				gd1.apellido,
				'Docente' tipo,
				0 tipo_estudiante
			FROM
				gen_docente gd1
			UNION ALL
			SELECT 	
				-1,
				-1,
				ges1.id_due id,
				ges1.id_login,
				ges1.nombre,
				ges1.apellido,
				'Estudiante' tipo,
				vte.tipo tipo_estudiante
			FROM
				gen_estudiante ges1
			JOIN
				view_tipo_estudiante vte
			ON
				vte.id_due = ges1.id_due
			UNION ALL
			SELECT 	
				-2,
				-2,
				pct1.id_contacto,
				pct1.id_login,
				pct1.nombre,
				pct1.apellido,
				'Contacto' tipo,
				0 tipo_estudiante
			FROM
				pss_contacto pct1
			
		) doc_est
	ON
		doc_est.id_login = gus.id_usuario	
	WHERE (gus.nombre_usuario = nombre_usuario_arg AND gus.contrasenia = contrasenia_arg)
	LIMIT 1;
    END$$

--
-- Funciones
--
DROP FUNCTION IF EXISTS `func_inc_var_session`$$
CREATE DEFINER=`sigpanet`@`%` FUNCTION `func_inc_var_session` () RETURNS INT(11) NO SQL
begin
      SET @var := IFNULL(@var,0) + 1;
      return @var;
     end$$

DROP FUNCTION IF EXISTS `func_inc_var_session_PERA`$$
CREATE DEFINER=`sigpanet`@`%` FUNCTION `func_inc_var_session_PERA` () RETURNS INT(11) NO SQL
begin
      SET @PERA_var := IFNULL(@PERA_var,0) + 1;
      return @PERA_var;
     end$$

DROP FUNCTION IF EXISTS `func_reset_inc_var_session`$$
CREATE DEFINER=`sigpanet`@`%` FUNCTION `func_reset_inc_var_session` () RETURNS INT(11) NO SQL
BEGIN
      SET @var := 0;
      RETURN @var;
     END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignado`
--

DROP TABLE IF EXISTS `asignado`;
CREATE TABLE `asignado` (
  `id_due` char(7) NOT NULL,
  `id_docente` char(7) NOT NULL,
  `id_proceso` char(7) NOT NULL,
  `id_cargo` char(7) NOT NULL,
  `correlativo_tutor_ss` int(11) UNSIGNED NOT NULL,
  `es_docente_director_pdg` tinyint(1) DEFAULT NULL,
  `es_docente_tribu_principal` tinyint(1) DEFAULT NULL,
  `valido` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Validez de la asignacion (1: Valida, 0: Invalida)',
  `es_docente_pss_principal` tinyint(1) DEFAULT NULL,
  `id_detalle_expediente` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asig_docente_aux_pdg`
--

DROP TABLE IF EXISTS `asig_docente_aux_pdg`;
CREATE TABLE `asig_docente_aux_pdg` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `id_docente` char(7) NOT NULL,
  `NombreApellidoDocente` varchar(56) DEFAULT NULL,
  `correo_docente` varchar(50) DEFAULT NULL,
  `es_docente_director_pdg` tinyint(1) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asig_docente_aux_pss`
--

DROP TABLE IF EXISTS `asig_docente_aux_pss`;
CREATE TABLE `asig_docente_aux_pss` (
  `id_det_expediente` char(7) NOT NULL,
  `id_servicio_social` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `id_docente` char(7) NOT NULL,
  `NombreApellidoDocente` varchar(56) DEFAULT NULL,
  `correo_docente` varchar(50) DEFAULT NULL,
  `es_docente_pss_principal` tinyint(1) DEFAULT NULL,
  `correlativo_tutor_ss` int(11) UNSIGNED NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asig_tribunal_aux_pdg`
--

DROP TABLE IF EXISTS `asig_tribunal_aux_pdg`;
CREATE TABLE `asig_tribunal_aux_pdg` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `id_docente` char(7) NOT NULL,
  `NombreApellidoDocente` varchar(56) DEFAULT NULL,
  `correo_docente` varchar(50) DEFAULT NULL,
  `id_cargo` char(7) NOT NULL,
  `descripcion` longtext,
  `es_docente_tribu_principal` tinyint(1) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aux1_contacto_x_institucion_pss_tmp`
--

DROP TABLE IF EXISTS `aux1_contacto_x_institucion_pss_tmp`;
CREATE TABLE `aux1_contacto_x_institucion_pss_tmp` (
  `nombre_intitucion` varchar(30) NOT NULL,
  `id_institucion` varchar(7) NOT NULL,
  `nombre_apellido_contacto` varchar(52) DEFAULT NULL,
  `nombre_apellido_contacto_e_institucion` varchar(83) DEFAULT NULL,
  `id_contacto` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aux1_docente_pdg_tmp`
--

DROP TABLE IF EXISTS `aux1_docente_pdg_tmp`;
CREATE TABLE `aux1_docente_pdg_tmp` (
  `id_docente` char(7) NOT NULL,
  `carnet` char(7) DEFAULT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aux1_docente_pss_tmp`
--

DROP TABLE IF EXISTS `aux1_docente_pss_tmp`;
CREATE TABLE `aux1_docente_pss_tmp` (
  `id_docente` char(7) NOT NULL,
  `carnet` char(7) DEFAULT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aux2_docente_pdg_tmp`
--

DROP TABLE IF EXISTS `aux2_docente_pdg_tmp`;
CREATE TABLE `aux2_docente_pdg_tmp` (
  `id_docente` char(7) NOT NULL,
  `carnet` char(7) DEFAULT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aux_candidatos_para_aperturar_tmp_pss`
--

DROP TABLE IF EXISTS `aux_candidatos_para_aperturar_tmp_pss`;
CREATE TABLE `aux_candidatos_para_aperturar_tmp_pss` (
  `id_due` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `dui` char(9) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `apertura_expediente_pss` date DEFAULT NULL,
  `carta_aptitud_pss` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aux_estudiantes_con_exp_con_tmp_pss`
--

DROP TABLE IF EXISTS `aux_estudiantes_con_exp_con_tmp_pss`;
CREATE TABLE `aux_estudiantes_con_exp_con_tmp_pss` (
  `id_due` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `dui` char(9) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `apertura_expediente_pss` date DEFAULT NULL,
  `fecha_remision` date DEFAULT NULL,
  `carta_aptitud_pss` varchar(200) DEFAULT NULL,
  `id_detalle_expediente` char(7) NOT NULL,
  `id_servicio_social` char(7) NOT NULL,
  `id_modalidad` char(7) NOT NULL,
  `oficializacion` tinyint(1) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `horas_prestadas` int(11) DEFAULT NULL,
  `cierre_modalidad` tinyint(1) DEFAULT NULL,
  `observacion` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aux_estudiantes_con_exp_sin_tmp_pss`
--

DROP TABLE IF EXISTS `aux_estudiantes_con_exp_sin_tmp_pss`;
CREATE TABLE `aux_estudiantes_con_exp_sin_tmp_pss` (
  `id_due` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `dui` char(9) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `apertura_expediente_pss` date DEFAULT NULL,
  `carta_aptitud_pss` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aux_servicio_social_pss_tmp`
--

DROP TABLE IF EXISTS `aux_servicio_social_pss_tmp`;
CREATE TABLE `aux_servicio_social_pss_tmp` (
  `id_servicio_social` char(7) NOT NULL,
  `id_contacto` char(10) NOT NULL,
  `id_modalidad` char(7) NOT NULL,
  `nombre_servicio_social` varchar(500) DEFAULT NULL,
  `estado_aprobacion` varchar(2) DEFAULT NULL,
  `cantidad_estudiante` int(11) DEFAULT NULL,
  `disponibilidad` int(11) DEFAULT NULL,
  `objetivo` varchar(500) DEFAULT NULL,
  `importancia` varchar(500) DEFAULT NULL,
  `presupuesto` double DEFAULT NULL,
  `logro` varchar(500) DEFAULT NULL,
  `localidad_proyecto` varchar(100) DEFAULT NULL,
  `beneficiario_directo` int(11) DEFAULT NULL,
  `beneficiario_indirecto` int(11) DEFAULT NULL,
  `descripcion` longtext,
  `nombre_contacto_ss` varchar(100) DEFAULT NULL,
  `email_contacto_ss` varchar(50) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cartas_renuncia_tmp_pss`
--

DROP TABLE IF EXISTS `cartas_renuncia_tmp_pss`;
CREATE TABLE `cartas_renuncia_tmp_pss` (
  `id_detalle_expediente` char(7) NOT NULL,
  `id_servicio_social` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `estado` char(2) DEFAULT NULL,
  `encabezado_carta_renuncia` longtext,
  `motivos_renuncia` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_parametro_general`
--

DROP TABLE IF EXISTS `cat_parametro_general`;
CREATE TABLE `cat_parametro_general` (
  `parametro` varchar(20) NOT NULL,
  `descripcion` longtext,
  `valor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_parametro_general`
--

INSERT INTO `cat_parametro_general` (`parametro`, `descripcion`, `valor`) VALUES
('NAADACAD', 'Nombre Administrador Academico de la FIA', 'José Francisco Monroy'),
('NCPDG', 'Nombre Coordinador Proceso de Graduación', 'Jose Simeon Cañas'),
('NCPERA', 'Nombre Coordinador PERA', 'Abimael Cañas de Sousa'),
('NCPSS', 'Nombre Coordinador Proceso Servicio Social', 'Angelica Nuila de Sanchez'),
('NDEISI', 'Nombre Director de Escuela', 'José María Sanchez Cornejo'),
('NDGPDG', 'Nombre Director General de Procesos de Graduación', 'Tania Torres Rivera'),
('NSEISI', 'Nombre Secretario de la EISI', 'Rodrigo Ernesto Vasquez Escalante'),
('PVHSS', 'Precio de la hora de servicio social ($)', '51.13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_proceso`
--

DROP TABLE IF EXISTS `cat_proceso`;
CREATE TABLE `cat_proceso` (
  `id_proceso` char(7) NOT NULL,
  `proceso` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_proceso`
--

INSERT INTO `cat_proceso` (`id_proceso`, `proceso`) VALUES
('PDG', 'Proceso de Graduación'),
('PER', 'Programa Especial de Refuerzo Académico'),
('PSS', 'Proceso de Servicio Social');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_expediente_tmp_pss`
--

DROP TABLE IF EXISTS `cierre_expediente_tmp_pss`;
CREATE TABLE `cierre_expediente_tmp_pss` (
  `id_due` char(7) NOT NULL,
  `remision` tinyint(1) DEFAULT NULL,
  `fecha_remision` date DEFAULT NULL,
  `total_horas` decimal(32,0) DEFAULT NULL,
  `observaciones_exp_pss` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_notas_pdg`
--

DROP TABLE IF EXISTS `cierre_notas_pdg`;
CREATE TABLE `cierre_notas_pdg` (
  `id_equipo_tg` char(7) DEFAULT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `anio_tg` int(11) DEFAULT NULL,
  `ciclo_tg` int(11) DEFAULT NULL,
  `etapa_evaluativa` varchar(17) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `estado_nota` char(2) DEFAULT NULL,
  `id` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_servicios_tmp_pss`
--

DROP TABLE IF EXISTS `cierre_servicios_tmp_pss`;
CREATE TABLE `cierre_servicios_tmp_pss` (
  `id_detalle_expediente` char(7) NOT NULL,
  `id_servicio_social` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `estado` char(2) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `oficializacion` tinyint(1) DEFAULT NULL,
  `carta_finalizacion_horas_sociales` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conforma`
--

DROP TABLE IF EXISTS `conforma`;
CREATE TABLE `conforma` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tg` int(11) NOT NULL,
  `id_due` char(7) NOT NULL,
  `ciclo_tg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es`
--

DROP TABLE IF EXISTS `es`;
CREATE TABLE `es` (
  `id_tipo_estudiante` char(7) NOT NULL,
  `id_due` char(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expedientes_remitidos_tmp_pss`
--

DROP TABLE IF EXISTS `expedientes_remitidos_tmp_pss`;
CREATE TABLE `expedientes_remitidos_tmp_pss` (
  `id_due` char(7) NOT NULL,
  `remision` tinyint(1) DEFAULT NULL,
  `fecha_remision` date DEFAULT NULL,
  `total_horas` decimal(32,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_cargo`
--

DROP TABLE IF EXISTS `gen_cargo`;
CREATE TABLE `gen_cargo` (
  `id_cargo` char(7) NOT NULL,
  `descripcion` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_cargo`
--

INSERT INTO `gen_cargo` (`id_cargo`, `descripcion`) VALUES
('1', 'Docentes'),
('10', 'Docente Asesor de Tipo de PERA'),
('2', 'Docente Asesor de Proceso de Trabajo de Graduación\r\n'),
('3', 'Docente Asesor de Proceso de PERA\r\n'),
('4', 'Docente Tutor de Servicio Social\r\n'),
('5', 'Docente 1 del Tribunal Evaluador\r\n'),
('6', 'Docente 2 del Tribunal Evaluador'),
('7', 'Docente Coordinador de Materia\r\n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_cargo_administrativo`
--

DROP TABLE IF EXISTS `gen_cargo_administrativo`;
CREATE TABLE `gen_cargo_administrativo` (
  `id_cargo_administrativo` char(7) NOT NULL,
  `descripcion` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_cargo_administrativo`
--

INSERT INTO `gen_cargo_administrativo` (`id_cargo_administrativo`, `descripcion`) VALUES
('1', 'Director de Escuela\r\n'),
('2', 'Coordinador de PERA'),
('3', 'Coordinador de Servicio Social'),
('4', 'Coordinador de Proceso de Trabajo de Graduación'),
('5', 'Secretario'),
('6', 'Docente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_departamento`
--

DROP TABLE IF EXISTS `gen_departamento`;
CREATE TABLE `gen_departamento` (
  `id_departamento` char(7) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_departamento`
--

INSERT INTO `gen_departamento` (`id_departamento`, `nombre`) VALUES
('0', 'PERA'),
('1', 'Programación y manejo de datos'),
('2', 'Comunicaciones y Ciencias de Computación'),
('3', 'Desarrollo de sistemas'),
('4', 'Administración');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_docente`
--

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
  `id_login` char(7) DEFAULT NULL,
  `carnet` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_docente`
--

INSERT INTO `gen_docente` (`id_docente`, `id_cargo`, `id_departamento`, `id_cargo_administrativo`, `nombre`, `apellido`, `direccion`, `telefono`, `celular`, `email`, `id_login`, `carnet`) VALUES
('1', '1', '1', '1', 'Coordinador de Escuela', 'Ing. Sistema Informaticos', NULL, NULL, NULL, NULL, '1', 'aa00000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_estudiante`
--

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
  `id_login` char(7) DEFAULT NULL,
  `estado_tesis` char(1) DEFAULT NULL,
  `carta_aptitud_pss` varchar(200) DEFAULT NULL,
  `apertura_expediente_pss` date DEFAULT NULL,
  `lugar_trabajo` varchar(200) DEFAULT NULL,
  `telefono_trabajo` decimal(8,0) DEFAULT NULL,
  `observaciones_exp_pss` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_materia`
--

DROP TABLE IF EXISTS `gen_materia`;
CREATE TABLE `gen_materia` (
  `id_materia` char(7) NOT NULL,
  `id_departamento` char(7) NOT NULL,
  `id_docente` char(7) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `uv` tinyint(1) NOT NULL DEFAULT '4'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_materia`
--

INSERT INTO `gen_materia` (`id_materia`, `id_departamento`, `id_docente`, `nombre`, `uv`) VALUES
('1', '0', NULL, 'Proyecto', 2),
('2', '0', NULL, 'Proyecto', 4),
('3', '0', NULL, 'Proyecto', 8),
('4', '0', NULL, 'Proyecto', 12),
('5', '0', NULL, 'Proyecto', 16),
('ACC115', '4', NULL, 'Administración de Centros de Computo', 4),
('COS115', '2', NULL, 'Comunicaciones I', 4),
('COS215', '2', NULL, 'Comunicaciones II', 4),
('CPR115', '4', NULL, 'Consultoría Profesional', 4),
('DSI115', '3', NULL, 'Diseño de Sistemas I', 4),
('DSI215', '3', NULL, 'Diseño de Sistemas II', 4),
('PRN115', '1', NULL, 'Programación I', 4),
('PRN215', '1', NULL, 'Programación II', 4),
('PRN315', '1', NULL, 'Programación III', 4),
('RHU115', '4', NULL, 'Recursos Humanos', 4),
('SIO115', '2', NULL, 'Sistemas Operativos', 4),
('TAD115', '4', NULL, 'Teoría Administrativa', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_oficializacion_tmp_pss`
--

DROP TABLE IF EXISTS `gen_oficializacion_tmp_pss`;
CREATE TABLE `gen_oficializacion_tmp_pss` (
  `id_detalle_expediente` char(7) NOT NULL,
  `id_servicio_social` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `estado` char(2) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `oficializacion` tinyint(1) DEFAULT NULL,
  `encabezado_oficializacion` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_perfil`
--

DROP TABLE IF EXISTS `gen_perfil`;
CREATE TABLE `gen_perfil` (
  `id_perfil_usuario` char(7) NOT NULL,
  `nombre_perfil` varchar(100) DEFAULT NULL,
  `descripcion_perfil` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_perfil`
--

INSERT INTO `gen_perfil` (`id_perfil_usuario`, `nombre_perfil`, `descripcion_perfil`) VALUES
('1', 'Perfil1', 'Desc Perfil1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_tipo_estudiante`
--

DROP TABLE IF EXISTS `gen_tipo_estudiante`;
CREATE TABLE `gen_tipo_estudiante` (
  `id_tipo_estudiante` char(7) NOT NULL,
  `descripcion` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_tipo_estudiante`
--

INSERT INTO `gen_tipo_estudiante` (`id_tipo_estudiante`, `descripcion`) VALUES
('PDG', 'Proceso de Graduación'),
('PERA', 'Proceso Especial de Refuerzo Academico'),
('PSS', 'Proceso de Servicio Social');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_tipo_usuario`
--

DROP TABLE IF EXISTS `gen_tipo_usuario`;
CREATE TABLE `gen_tipo_usuario` (
  `id_tipo_usuario` char(7) NOT NULL,
  `nombre_tipo_usuario` varchar(50) DEFAULT NULL,
  `descripcion_tipo_usuario` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_tipo_usuario`
--

INSERT INTO `gen_tipo_usuario` (`id_tipo_usuario`, `nombre_tipo_usuario`, `descripcion_tipo_usuario`) VALUES
('1', 'Tipo1', 'Descripcion Tipo 1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gen_usuario`
--

DROP TABLE IF EXISTS `gen_usuario`;
CREATE TABLE `gen_usuario` (
  `id_usuario` char(7) NOT NULL,
  `id_tipo_usuario` char(7) NOT NULL,
  `id_perfil_usuario` char(7) NOT NULL,
  `nombre_usuario` char(30) DEFAULT NULL,
  `contrasenia` varchar(50) DEFAULT NULL,
  `contrasenia_temporal` varchar(50) DEFAULT NULL,
  `fecha_creacion_usuario` datetime DEFAULT NULL,
  `correo_usuario` varchar(50) DEFAULT NULL,
  `estado_usuario` char(2) DEFAULT NULL,
  `ultima_conexion` datetime DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  `id_confirmacion` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gen_usuario`
--

INSERT INTO `gen_usuario` (`id_usuario`, `id_tipo_usuario`, `id_perfil_usuario`, `nombre_usuario`, `contrasenia`, `contrasenia_temporal`, `fecha_creacion_usuario`, `correo_usuario`, `estado_usuario`, `ultima_conexion`, `activo`, `id_confirmacion`) VALUES
('1', '1', '1', 'Coordinador Escuela', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', '', '2017-01-26 00:04:58', 'sigpa.fia.ues@gmail.com', '', '0000-00-00 00:00:00', 1, '5889841f75539');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_documentacion_pss_tmp`
--

DROP TABLE IF EXISTS `ingreso_documentacion_pss_tmp`;
CREATE TABLE `ingreso_documentacion_pss_tmp` (
  `id_detalle_expediente` char(7) NOT NULL,
  `id_servicio_social` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `horas_prestadas` int(11) DEFAULT NULL,
  `perfil_proyecto` varchar(200) DEFAULT NULL,
  `plan_trabajo` varchar(200) DEFAULT NULL,
  `informe_parcial` varchar(200) DEFAULT NULL,
  `informe_final` varchar(200) DEFAULT NULL,
  `memoria` varchar(200) DEFAULT NULL,
  `control_actividades` varchar(200) DEFAULT NULL,
  `carta_finalizacion_horas_sociales` varchar(200) DEFAULT NULL,
  `lugar_trabajo` varchar(200) DEFAULT NULL,
  `telefono_trabajo` decimal(8,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_apro_dene_anteproy_temp`
--

DROP TABLE IF EXISTS `pdg_apro_dene_anteproy_temp`;
CREATE TABLE `pdg_apro_dene_anteproy_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `estado_anteproyecto` char(2) DEFAULT NULL,
  `anteproy_ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproy_doc_ase` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproy_tribu_eva1` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproy_tribu_eva2` tinyint(1) DEFAULT NULL,
  `ruta` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_apro_dene_cambio_nombre_temp`
--

DROP TABLE IF EXISTS `pdg_apro_dene_cambio_nombre_temp`;
CREATE TABLE `pdg_apro_dene_cambio_nombre_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `id_solicitud_academica` char(7) NOT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `generado_x_coordinador_pdg` tinyint(1) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `ruta` longtext,
  `siglas` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_apro_dene_perfil_temp`
--

DROP TABLE IF EXISTS `pdg_apro_dene_perfil_temp`;
CREATE TABLE `pdg_apro_dene_perfil_temp` (
  `id` int(11) NOT NULL,
  `id_equipo_tg` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `id_perfil` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `perfil_ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `entrega_copia_perfil` tinyint(1) DEFAULT NULL,
  `estado_perfil` char(2) DEFAULT NULL,
  `observaciones_perfil` text,
  `numero_acta_perfil` char(10) DEFAULT NULL,
  `punto_perfil` char(10) DEFAULT NULL,
  `acuerdo_perfil` char(10) DEFAULT NULL,
  `fecha_aprobacion_perfil` date DEFAULT NULL,
  `ruta` longtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_apro_dene_prorro_temp`
--

DROP TABLE IF EXISTS `pdg_apro_dene_prorro_temp`;
CREATE TABLE `pdg_apro_dene_prorro_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `id_solicitud_academica` char(7) NOT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `generado_x_coordinador_pdg` tinyint(1) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `ruta` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_bitacora_control`
--

DROP TABLE IF EXISTS `pdg_bitacora_control`;
CREATE TABLE `pdg_bitacora_control` (
  `id_bitacora` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `tematica_tratar` longtext,
  `hora_inicio` datetime DEFAULT NULL,
  `hora_fin` datetime DEFAULT NULL,
  `hora_inicio_docente` datetime DEFAULT NULL,
  `hora_fin_docente` datetime DEFAULT NULL,
  `hora_inicio_alumno_1` datetime DEFAULT NULL,
  `hora_fin_alumno_1` datetime DEFAULT NULL,
  `hora_inicio_alumno_2` datetime DEFAULT NULL,
  `hora_fin_alumno_2` datetime DEFAULT NULL,
  `hora_inicio_alumno_3` datetime DEFAULT NULL,
  `hora_fin_alumno_3` datetime DEFAULT NULL,
  `hora_inicio_alumno_4` datetime DEFAULT NULL,
  `hora_fin_alumno_4` datetime DEFAULT NULL,
  `hora_inicio_alumno_5` datetime DEFAULT NULL,
  `hora_fin_alumno_5` datetime DEFAULT NULL,
  `id_due1` char(7) DEFAULT NULL,
  `id_due2` char(7) DEFAULT NULL,
  `id_due3` char(7) DEFAULT NULL,
  `id_due4` char(7) DEFAULT NULL,
  `lugar` longtext,
  `observaciones` longtext,
  `id_docente` char(7) DEFAULT NULL,
  `id_due5` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_consolidado_notas`
--

DROP TABLE IF EXISTS `pdg_consolidado_notas`;
CREATE TABLE `pdg_consolidado_notas` (
  `id_consolidado_notas` char(7) NOT NULL,
  `id_equipo_tg` char(7) DEFAULT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `anio_tg` int(11) DEFAULT NULL,
  `id_due` char(7) DEFAULT NULL,
  `nota_anteproyecto` double DEFAULT NULL,
  `nota_etapa1` double DEFAULT NULL,
  `nota_etapa2` double DEFAULT NULL,
  `nota_defensa_publica` double DEFAULT NULL,
  `ciclo_tg` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_criterio`
--

DROP TABLE IF EXISTS `pdg_criterio`;
CREATE TABLE `pdg_criterio` (
  `id_criterio` char(7) NOT NULL,
  `criterio` varchar(100) DEFAULT NULL,
  `descripcion` longtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_criterio`
--

INSERT INTO `pdg_criterio` (`id_criterio`, `criterio`, `descripcion`) VALUES
('1', 'Presentación del documento ', NULL),
('10', 'Conclusiones y Recomendaciones ', NULL),
('11', 'Exposición', NULL),
('12', 'Conocimiento del Tema ', NULL),
('2', 'Formulación del problemas ', NULL),
('3', 'Objetivos del Proyecto (Grales. y específicos) ', NULL),
('4', 'Importancia, Alcances y Limitaciones', NULL),
('5', 'Justificación del estudio ', NULL),
('6', 'Resultados Esperados ', NULL),
('7', 'Descripción del Sistema ', NULL),
('8', 'Metodología para resolver el Problema ', NULL),
('9', 'Planificación del Proyecto ', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_detalle`
--

DROP TABLE IF EXISTS `pdg_detalle`;
CREATE TABLE `pdg_detalle` (
  `id_detalle_pdg` char(7) NOT NULL,
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tg` int(11) NOT NULL,
  `anio` int(11) DEFAULT NULL,
  `estado_perfil` char(2) DEFAULT NULL,
  `observaciones_perfil` text,
  `entrega_copia_anteproyecto` tinyint(1) DEFAULT NULL,
  `fecha_eva_anteproyecto` date DEFAULT NULL,
  `fecha_eva_etapa1` date DEFAULT NULL,
  `fecha_eva_etapa2` date DEFAULT NULL,
  `fecha_eva_publica` date DEFAULT NULL,
  `recolector_nota` tinyint(1) DEFAULT NULL,
  `remision_ejemplar` tinyint(1) DEFAULT NULL,
  `ratificacion_nota` tinyint(1) DEFAULT NULL,
  `perfil_ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `entrega_copia_perfil` tinyint(1) DEFAULT NULL,
  `anteproy_ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproy_doc_ase` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproy_tribu_eva1` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproy_tribu_eva2` tinyint(1) DEFAULT NULL,
  `estado_anteproyecto` char(2) DEFAULT NULL,
  `ciclo_tg` int(11) NOT NULL,
  `numero_acta_perfil` char(10) DEFAULT NULL,
  `punto_perfil` char(10) DEFAULT NULL,
  `acuerdo_perfil` char(10) DEFAULT NULL,
  `fecha_aprobacion_perfil` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_deta_nota`
--

DROP TABLE IF EXISTS `pdg_deta_nota`;
CREATE TABLE `pdg_deta_nota` (
  `id_deta_nota` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `nota` decimal(4,2) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_documento`
--

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
  `obser_tribu_2` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_equipo_tg`
--

DROP TABLE IF EXISTS `pdg_equipo_tg`;
CREATE TABLE `pdg_equipo_tg` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tg` int(11) NOT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `sigla` varchar(20) DEFAULT NULL,
  `ciclo_tg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_ingre_tema_temp`
--

DROP TABLE IF EXISTS `pdg_ingre_tema_temp`;
CREATE TABLE `pdg_ingre_tema_temp` (
  `id` int(11) NOT NULL,
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `sigla` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_nota_anteproyecto`
--

DROP TABLE IF EXISTS `pdg_nota_anteproyecto`;
CREATE TABLE `pdg_nota_anteproyecto` (
  `id_nota_anteproyecto` char(7) NOT NULL,
  `id_equipo_tg` char(7) DEFAULT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `anio_tg` int(11) DEFAULT NULL,
  `estado_nota` char(2) DEFAULT NULL,
  `cod_criterio1` char(7) DEFAULT NULL,
  `nota_criterio1` double DEFAULT NULL,
  `cod_criterio2` char(7) DEFAULT NULL,
  `nota_criterio2` double DEFAULT NULL,
  `cod_criterio3` char(7) DEFAULT NULL,
  `nota_criterio3` double DEFAULT NULL,
  `cod_criterio4` char(7) DEFAULT NULL,
  `nota_criterio4` double DEFAULT NULL,
  `cod_criterio5` char(7) DEFAULT NULL,
  `nota_criterio5` double DEFAULT NULL,
  `cod_criterio6` char(7) DEFAULT NULL,
  `nota_criterio6` double DEFAULT NULL,
  `cod_criterio7` char(7) DEFAULT NULL,
  `nota_criterio7` double DEFAULT NULL,
  `cod_criterio8` char(7) DEFAULT NULL,
  `nota_criterio8` double DEFAULT NULL,
  `cod_criterio9` char(7) DEFAULT NULL,
  `nota_criterio9` double DEFAULT NULL,
  `cod_criterio10` char(7) DEFAULT NULL,
  `nota_criterio10` double DEFAULT NULL,
  `nota_documento` double DEFAULT NULL,
  `cod_criterio11` char(7) DEFAULT NULL,
  `nota_criterio11` double DEFAULT NULL,
  `cod_criterio12` char(7) DEFAULT NULL,
  `nota_criterio12` double DEFAULT NULL,
  `nota_exposicion` double DEFAULT NULL,
  `nota_anteproyecto` double DEFAULT NULL,
  `ciclo_tg` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_nota_defensa_publica`
--

DROP TABLE IF EXISTS `pdg_nota_defensa_publica`;
CREATE TABLE `pdg_nota_defensa_publica` (
  `id_nota_defensa_publica` char(7) NOT NULL,
  `id_equipo_tg` char(7) DEFAULT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `anio_tg` int(11) DEFAULT NULL,
  `id_due` char(7) DEFAULT NULL,
  `estado_nota` char(2) DEFAULT NULL,
  `id_docente` char(7) DEFAULT NULL,
  `id_cargo` char(7) DEFAULT NULL,
  `nota_defensa_publica` double DEFAULT NULL,
  `ciclo_tg` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_nota_etapa1`
--

DROP TABLE IF EXISTS `pdg_nota_etapa1`;
CREATE TABLE `pdg_nota_etapa1` (
  `id_nota_etapa1` char(7) NOT NULL,
  `id_equipo_tg` char(7) DEFAULT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `anio_tg` int(11) DEFAULT NULL,
  `id_due` char(7) DEFAULT NULL,
  `estado_nota` char(2) DEFAULT NULL,
  `nota_documento` double DEFAULT NULL,
  `cod_criterio1` char(7) DEFAULT NULL,
  `nota_criterio1` double DEFAULT NULL,
  `cod_criterio2` char(7) DEFAULT NULL,
  `nota_criterio2` double DEFAULT NULL,
  `cod_criterio3` char(7) DEFAULT NULL,
  `nota_criterio3` double DEFAULT NULL,
  `cod_criterio4` char(7) DEFAULT NULL,
  `nota_criterio4` double DEFAULT NULL,
  `cod_criterio5` char(7) DEFAULT NULL,
  `nota_criterio5` double DEFAULT NULL,
  `cod_criterio6` char(7) DEFAULT NULL,
  `nota_criterio6` double DEFAULT NULL,
  `nota_exposicion` double(10,0) DEFAULT NULL,
  `nota_etapa1` double DEFAULT NULL,
  `ciclo_tg` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_nota_etapa2`
--

DROP TABLE IF EXISTS `pdg_nota_etapa2`;
CREATE TABLE `pdg_nota_etapa2` (
  `id_nota_etapa2` char(7) NOT NULL,
  `id_equipo_tg` char(7) DEFAULT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `anio_tg` int(11) DEFAULT NULL,
  `id_due` char(7) DEFAULT NULL,
  `estado_nota` char(2) DEFAULT NULL,
  `nota_documento` double DEFAULT NULL,
  `cod_criterio1` char(7) DEFAULT NULL,
  `nota_criterio1` double DEFAULT NULL,
  `cod_criterio2` char(7) DEFAULT NULL,
  `nota_criterio2` double DEFAULT NULL,
  `cod_criterio3` char(7) DEFAULT NULL,
  `nota_criterio3` double DEFAULT NULL,
  `cod_criterio4` char(7) DEFAULT NULL,
  `nota_criterio4` double DEFAULT NULL,
  `cod_criterio5` char(7) DEFAULT NULL,
  `nota_criterio5` double DEFAULT NULL,
  `cod_criterio6` char(7) DEFAULT NULL,
  `nota_criterio6` double DEFAULT NULL,
  `nota_exposicion` double DEFAULT NULL,
  `nota_etapa2` double DEFAULT NULL,
  `ciclo_tg` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_perfil`
--

DROP TABLE IF EXISTS `pdg_perfil`;
CREATE TABLE `pdg_perfil` (
  `id_perfil` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `objetivo_general` longtext,
  `objetivo_especifico` longtext,
  `descripcion` longtext,
  `id_documento_pdg` char(7) DEFAULT NULL,
  `area_tematica_tg` longtext,
  `resultados_esperados_tg` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_perfil_temp`
--

DROP TABLE IF EXISTS `pdg_perfil_temp`;
CREATE TABLE `pdg_perfil_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `id_perfil` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `ciclo_perfil` smallint(6) DEFAULT NULL,
  `anio_perfil` int(11) DEFAULT NULL,
  `observaciones_perfil` text,
  `objetivo_general` longtext,
  `objetivo_especifico` longtext,
  `descripcion` longtext,
  `area_tematica_tg` longtext,
  `resultados_esperados_tg` longtext,
  `ruta` longtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_ratificacion_notas_temp`
--

DROP TABLE IF EXISTS `pdg_ratificacion_notas_temp`;
CREATE TABLE `pdg_ratificacion_notas_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tg` int(11) NOT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `sigla` varchar(20) DEFAULT NULL,
  `ciclo_tg` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_recolector_notas_temp`
--

DROP TABLE IF EXISTS `pdg_recolector_notas_temp`;
CREATE TABLE `pdg_recolector_notas_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tg` int(11) NOT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `sigla` varchar(20) DEFAULT NULL,
  `ciclo_tg` int(11) NOT NULL,
  `id` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_regis_ateproy_temp`
--

DROP TABLE IF EXISTS `pdg_regis_ateproy_temp`;
CREATE TABLE `pdg_regis_ateproy_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `fecha_eva_anteproyecto` date DEFAULT NULL,
  `fecha_eva_etapa1` date DEFAULT NULL,
  `fecha_eva_etapa2` date DEFAULT NULL,
  `fecha_eva_publica` date DEFAULT NULL,
  `ruta` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_remision_notas_temp`
--

DROP TABLE IF EXISTS `pdg_remision_notas_temp`;
CREATE TABLE `pdg_remision_notas_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tg` int(11) NOT NULL,
  `tema` varchar(500) DEFAULT NULL,
  `sigla` varchar(20) DEFAULT NULL,
  `ciclo_tg` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_resumen_apro_dene_prorro_temp`
--

DROP TABLE IF EXISTS `pdg_resumen_apro_dene_prorro_temp`;
CREATE TABLE `pdg_resumen_apro_dene_prorro_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `id_solicitud_academica` char(7) NOT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `generado_x_coordinador_pdg` tinyint(1) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `ruta` longtext,
  `siglas` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_resumen_pro_dene_anteproy_temp`
--

DROP TABLE IF EXISTS `pdg_resumen_pro_dene_anteproy_temp`;
CREATE TABLE `pdg_resumen_pro_dene_anteproy_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `estado_anteproyecto` char(2) DEFAULT NULL,
  `anteproy_ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproy_doc_ase` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproy_tribu_eva1` tinyint(1) DEFAULT NULL,
  `entrega_copia_anteproy_tribu_eva2` tinyint(1) DEFAULT NULL,
  `ruta` longtext,
  `siglas` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_resum_apro_dene_cambio_nombre_temp`
--

DROP TABLE IF EXISTS `pdg_resum_apro_dene_cambio_nombre_temp`;
CREATE TABLE `pdg_resum_apro_dene_cambio_nombre_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `id_solicitud_academica` char(7) NOT NULL,
  `ciclo` smallint(6) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `generado_x_coordinador_pdg` tinyint(1) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `ruta` longtext,
  `siglas` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_resum_subir_doc_temp`
--

DROP TABLE IF EXISTS `pdg_resum_subir_doc_temp`;
CREATE TABLE `pdg_resum_subir_doc_temp` (
  `id_equipo_tg` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `id_tipo_documento_pdg` char(7) NOT NULL,
  `descripcion` longtext,
  `ruta` longtext,
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_solicitud_academica`
--

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
  `caso_especial` tinyint(1) DEFAULT NULL,
  `fecha_ini_prorroga` date DEFAULT NULL,
  `fecha_fin_prorroga` date DEFAULT NULL,
  `nombre_propuesto` longtext,
  `eva_actual` longtext,
  `eva_antes_prorroga` longtext,
  `cantidad_evaluacion_actual` int(11) DEFAULT NULL,
  `duracion` decimal(5,2) DEFAULT NULL,
  `ingresado_x_equipo` tinyint(1) DEFAULT NULL,
  `generado_x_coordinador_pdg` tinyint(1) DEFAULT NULL,
  `id_documento_pdg` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_subir_doc_temp`
--

DROP TABLE IF EXISTS `pdg_subir_doc_temp`;
CREATE TABLE `pdg_subir_doc_temp` (
  `id` int(11) NOT NULL,
  `id_equipo_tg` char(7) NOT NULL,
  `id_detalle_pdg` char(7) NOT NULL,
  `anio_tesis` int(11) NOT NULL,
  `ciclo_tesis` int(11) NOT NULL,
  `tema_tesis` varchar(500) DEFAULT NULL,
  `id_tipo_documento_pdg` char(7) NOT NULL,
  `descripcion` longtext,
  `ruta` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pdg_tipo_documento`
--

DROP TABLE IF EXISTS `pdg_tipo_documento`;
CREATE TABLE `pdg_tipo_documento` (
  `id_tipo_documento_pdg` char(7) NOT NULL,
  `descripcion` longtext,
  `siglas` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pdg_tipo_documento`
--

INSERT INTO `pdg_tipo_documento` (`id_tipo_documento_pdg`, `descripcion`, `siglas`) VALUES
('1', 'Perfil', 'P'),
('10', 'Memorandum de Aprobacion de Cambio de Nombre', 'MACN'),
('11', 'Memorandum de Denegación de Cambio de Nombre', 'MDCN'),
('12', 'Memorandum de Aprobación de Prorroga', 'MAPR'),
('13', 'Memorandum de Denegación de Prorroga', 'MDPR'),
('14', 'Aprobacion/denegacion Pendiente Cambio Nombre de Trabajo de Graduación', 'ADPCN'),
('15', 'Aprobacion/denegacion Pendiente Prorroga Trabajo de Graduación', 'ADPPR'),
('16', 'Aprobacion/denegacion Pendiente Perfil', 'ADPPP'),
('17', 'Aprobacion/denegacion Pendiente Anteproyecto', 'ADPAA'),
('2', 'Anteproyecto', 'A'),
('3', 'Etapa 1', 'E1'),
('4', 'Etapa 2', 'E2'),
('5', 'Defensa Pública', 'DP'),
('6', 'Memorandum de Aprobacion de Perfil', 'MAP'),
('7', 'Memorandum de Denegación de Perfil', 'MDP'),
('8', 'Memorandum de Aprobacion de Anteproyecto', 'MAA'),
('9', 'Memorandum de Denegación de Anteproyecto', 'MDA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `per_area_deficitaria`
--

DROP TABLE IF EXISTS `per_area_deficitaria`;
CREATE TABLE `per_area_deficitaria` (
  `id_detalle_pera` int(11) UNSIGNED NOT NULL,
  `id_materia` char(7) NOT NULL,
  `nota` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `per_detalle`
--

DROP TABLE IF EXISTS `per_detalle`;
CREATE TABLE `per_detalle` (
  `id_detalle_pera` int(11) UNSIGNED NOT NULL,
  `id_due` char(7) NOT NULL,
  `cum` decimal(4,2) NOT NULL,
  `uv` tinyint(4) DEFAULT NULL,
  `ciclo` tinyint(4) DEFAULT NULL,
  `anio` smallint(6) DEFAULT NULL,
  `observaciones` varchar(1000) DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT 'i' COMMENT 'i: inicial, d: desarrollo, f: finalizado, a: abandonado, c: cerrado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Disparadores `per_detalle`
--
DROP TRIGGER IF EXISTS `per_trg_detalle_uv`;
DELIMITER $$
CREATE TRIGGER `per_trg_detalle_uv` BEFORE INSERT ON `per_detalle` FOR EACH ROW BEGIN
		if (NEW.cum >= 6.00 AND NEW.cum <= 6.33) then
			set NEW.uv = 16;    
		END IF;
		if (NEW.cum >= 6.34 AND NEW.cum <= 6.66) then
			SET NEW.uv = 12;             
		END IF;
		if (NEW.cum >= 6.67 AND NEW.cum <= 6.99) then
		    SET NEW.uv = 8;             
		END if;
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `per_evaluacion`
--

DROP TABLE IF EXISTS `per_evaluacion`;
CREATE TABLE `per_evaluacion` (
  `id_evaluacion` int(11) UNSIGNED NOT NULL,
  `id_tipo_pera` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `fecha` date NOT NULL,
  `descripcion` longtext,
  `porcentaje` decimal(3,2) DEFAULT NULL,
  `nota` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `per_registro_nota`
--

DROP TABLE IF EXISTS `per_registro_nota`;
CREATE TABLE `per_registro_nota` (
  `id_registro_nota` int(11) UNSIGNED NOT NULL,
  `estado_registro` char(1) NOT NULL DEFAULT 'a' COMMENT 'a: abierto, c: cerrado',
  `id_detalle_pera` int(11) UNSIGNED NOT NULL,
  `estado` char(1) NOT NULL COMMENT 'finalizado, abandonado',
  `ciclo` tinyint(4) NOT NULL,
  `anio` year(4) NOT NULL,
  `docente_mentor` varchar(100) NOT NULL,
  `fecha_finalizacion` date NOT NULL,
  `descripcion` varchar(1000) NOT NULL,
  `n1` decimal(4,2) NOT NULL,
  `p1` decimal(2,2) NOT NULL,
  `n2` decimal(4,2) NOT NULL,
  `p2` decimal(2,2) NOT NULL,
  `n3` decimal(4,2) NOT NULL,
  `p3` decimal(2,2) NOT NULL,
  `n4` decimal(4,2) NOT NULL,
  `p4` decimal(2,2) NOT NULL,
  `n5` decimal(4,2) NOT NULL,
  `p5` decimal(2,2) NOT NULL,
  `promedio` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `per_tipo`
--

DROP TABLE IF EXISTS `per_tipo`;
CREATE TABLE `per_tipo` (
  `id_tipo_pera` int(11) UNSIGNED NOT NULL,
  `id_detalle_pera` int(11) UNSIGNED NOT NULL,
  `tipo` varchar(25) DEFAULT NULL,
  `uv` tinyint(4) DEFAULT NULL,
  `descripcion` longtext,
  `inicio` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  `docente_general` char(7) NOT NULL COMMENT 'Docente Mentor que asigno',
  `comentario` varchar(1000) DEFAULT NULL COMMENT 'Por cada asignacion de doncente'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_asi_doc`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_asi_doc`;
CREATE TABLE `per_view_asi_doc` (
`id_tipo_pera` int(11) unsigned
,`estudiante` varchar(66)
,`uv_pera` tinyint(4)
,`uv_asignadas` tinyint(4)
,`ciclo` varchar(13)
,`id_docente` char(7)
,`observaciones` varchar(1000)
,`comentario` varchar(1000)
,`due_sin_docente` char(7)
,`docente_general` char(7)
,`id_detalle_pera` int(11) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_asi_doc_estudiante`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_asi_doc_estudiante`;
CREATE TABLE `per_view_asi_doc_estudiante` (
`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`id_docente` char(7)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_asi_gen`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_asi_gen`;
CREATE TABLE `per_view_asi_gen` (
`id_detalle_pera` int(11) unsigned
,`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`cum` decimal(4,2)
,`uv` tinyint(4)
,`ciclo` tinyint(4)
,`anio` smallint(6)
,`id_docente` char(7)
,`observaciones` varchar(1000)
,`due_sin_docente` char(7)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_asi_gen_estudiante`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_asi_gen_estudiante`;
CREATE TABLE `per_view_asi_gen_estudiante` (
`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_cie_per`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_cie_per`;
CREATE TABLE `per_view_cie_per` (
`estado_registro` char(1)
,`estado` char(1)
,`estado_2` char(1)
,`id_detalle_pera` int(11) unsigned
,`ciclo` varchar(13)
,`id_due` char(7)
,`estudiante` varchar(56)
,`docente_mentor` varchar(56)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_def_tip`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_def_tip`;
CREATE TABLE `per_view_def_tip` (
`id_tipo_pera` int(11) unsigned
,`estudiante` varchar(66)
,`uv_asignadas` tinyint(4)
,`ciclo` varchar(13)
,`tipo` varchar(25)
,`descripcion` longtext
,`inicio` date
,`fin` date
,`id_docente` char(7)
,`comentario` varchar(1000)
,`id_due` char(7)
,`docente_general` char(7)
,`id_detalle_pera` int(11) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_def_tip_estudiantes`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_def_tip_estudiantes`;
CREATE TABLE `per_view_def_tip_estudiantes` (
`id_detalle_pera` int(11) unsigned
,`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_est_eva`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_est_eva`;
CREATE TABLE `per_view_est_eva` (
`id_evaluacion` int(11) unsigned
,`estudiante` varchar(66)
,`ciclo` varchar(13)
,`id_tipo_pera` int(11) unsigned
,`tipo` varchar(140)
,`descripcion_pera` longtext
,`nombre` varchar(50)
,`fecha` date
,`descripcion_evaluacion` longtext
,`porcentaje` decimal(3,2)
,`nota` decimal(4,2)
,`id_docente` char(7)
,`id_due` char(7)
,`docente_general` char(7)
,`id_detalle_pera` int(11) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_est_eva_1`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_est_eva_1`;
CREATE TABLE `per_view_est_eva_1` (
`id_evaluacion` int(11) unsigned
,`Estudiante` varchar(64)
,`id_tipo_pera` int(11) unsigned
,`nombre` varchar(50)
,`fecha` date
,`descripcion` longtext
,`porcentaje` decimal(3,2)
,`nota` decimal(4,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_est_eva_estudiante`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_est_eva_estudiante`;
CREATE TABLE `per_view_est_eva_estudiante` (
`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_est_eva_estudiantes`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_est_eva_estudiantes`;
CREATE TABLE `per_view_est_eva_estudiantes` (
`id_tipo_pera` int(11) unsigned
,`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_est_eva_id_tipo_pera`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_est_eva_id_tipo_pera`;
CREATE TABLE `per_view_est_eva_id_tipo_pera` (
`id_tipo_pera` int(11) unsigned
,`tipo` varchar(25)
,`descripcion_pera` longtext
,`nombre` varchar(100)
,`uv` tinyint(1)
,`id_docente` char(7)
,`id_due` char(7)
,`docente_general` char(7)
,`id_detalle_pera` int(11) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_gen_docente`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_gen_docente`;
CREATE TABLE `per_view_gen_docente` (
`docente` char(7)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_reg_eva`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_reg_eva`;
CREATE TABLE `per_view_reg_eva` (
`id_evaluacion` int(11) unsigned
,`id_due` char(7)
,`id_tipo_pera` int(11) unsigned
,`nombre` varchar(50)
,`fecha` date
,`descripcion` longtext
,`porcentaje` decimal(3,2)
,`nota` decimal(4,2)
,`estudiante_pera` varchar(64)
,`tipo_pera` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_reg_eva_estudiantes`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_reg_eva_estudiantes`;
CREATE TABLE `per_view_reg_eva_estudiantes` (
`id_tipo_pera` int(11) unsigned
,`id_due` char(7)
,`estudiante_pera` varchar(64)
,`tipo_pera` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_reg_not`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_reg_not`;
CREATE TABLE `per_view_reg_not` (
`id_registro_nota` int(11) unsigned
,`id_detalle_pera` int(11) unsigned
,`estado` char(1)
,`ciclo` tinyint(4)
,`anio` year(4)
,`docente_mentor` varchar(100)
,`fecha_finalizacion` date
,`descripcion` varchar(1000)
,`n1` decimal(4,2)
,`p1` decimal(2,2)
,`n2` decimal(4,2)
,`p2` decimal(2,2)
,`n3` decimal(4,2)
,`p3` decimal(2,2)
,`n4` decimal(4,2)
,`p4` decimal(2,2)
,`n5` decimal(4,2)
,`p5` decimal(2,2)
,`promedio` decimal(3,1)
,`due` char(7)
,`docente` char(7)
,`estudiante` varchar(56)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_reg_not_estudiante`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_reg_not_estudiante`;
CREATE TABLE `per_view_reg_not_estudiante` (
`id_detalle_pera` int(11) unsigned
,`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`id_docente` char(7)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_reg_not_final`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_reg_not_final`;
CREATE TABLE `per_view_reg_not_final` (
`id_evaluacion` int(11) unsigned
,`porcentaje` decimal(3,2)
,`nota` decimal(4,2)
,`id_tipo_pera` int(11) unsigned
,`uv_tipo` tinyint(4)
,`id_detalle_pera` int(11) unsigned
,`uv_detalle` tinyint(4)
,`estado` char(1)
,`nota_tipo` decimal(29,4)
,`nota_tipo_uv` decimal(32,4)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `per_view_seleccionar_estudiantes`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `per_view_seleccionar_estudiantes`;
CREATE TABLE `per_view_seleccionar_estudiantes` (
`id_due` char(7)
,`due_sin_docente` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pss_contacto`
--

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
  `id_login` char(7) DEFAULT NULL,
  `dui` char(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pss_detalle_expediente`
--

DROP TABLE IF EXISTS `pss_detalle_expediente`;
CREATE TABLE `pss_detalle_expediente` (
  `id_detalle_expediente` char(7) NOT NULL,
  `id_servicio_social` char(7) NOT NULL,
  `id_due` char(7) NOT NULL,
  `estado` char(2) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `oficializacion` tinyint(1) DEFAULT NULL,
  `hora_asignada` int(11) DEFAULT NULL,
  `costo_hora` float DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `cierre_modalidad` tinyint(1) DEFAULT NULL,
  `observacion` longtext,
  `perfil_proyecto` varchar(200) DEFAULT NULL,
  `plan_trabajo` varchar(200) DEFAULT NULL,
  `informe_parcial` varchar(200) DEFAULT NULL,
  `informe_final` varchar(200) DEFAULT NULL,
  `carta_finalizacion_horas_sociales` varchar(200) DEFAULT NULL,
  `memoria` varchar(200) DEFAULT NULL,
  `control_actividades` varchar(200) DEFAULT NULL,
  `encabezado_oficializacion` longtext,
  `horas_prestadas` int(11) DEFAULT NULL,
  `encabezado_carta_renuncia` longtext,
  `motivos_renuncia` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pss_documento`
--

DROP TABLE IF EXISTS `pss_documento`;
CREATE TABLE `pss_documento` (
  `id_documento_pss` char(7) NOT NULL,
  `id_detalle_expediente` char(7) NOT NULL,
  `id_tipo_documento_pss` char(7) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `ruta` longtext,
  `extension` char(4) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `obser_asesor` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pss_institucion`
--

DROP TABLE IF EXISTS `pss_institucion`;
CREATE TABLE `pss_institucion` (
  `id_institucion` varchar(7) NOT NULL,
  `id_rubro` varchar(3) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `tipo` varchar(3) DEFAULT NULL,
  `estado` char(3) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `nit` varchar(17) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pss_modalidad`
--

DROP TABLE IF EXISTS `pss_modalidad`;
CREATE TABLE `pss_modalidad` (
  `id_modalidad` char(7) NOT NULL,
  `modalidad` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pss_modalidad`
--

INSERT INTO `pss_modalidad` (`id_modalidad`, `modalidad`) VALUES
('1', 'Ayudantia'),
('2', 'Curso Propedeutico'),
('3', 'Pasantia'),
('4', 'Proyecto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pss_reprotes_temporal`
--

DROP TABLE IF EXISTS `pss_reprotes_temporal`;
CREATE TABLE `pss_reprotes_temporal` (
  `id` int(11) DEFAULT NULL,
  `descripcion` longtext,
  `motivo_renuncia` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pss_rubro`
--

DROP TABLE IF EXISTS `pss_rubro`;
CREATE TABLE `pss_rubro` (
  `id_rubro` char(3) NOT NULL,
  `rubro` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pss_rubro`
--

INSERT INTO `pss_rubro` (`id_rubro`, `rubro`) VALUES
('1', 'Comercial'),
('2', 'Extraccion'),
('3', 'Financiera'),
('4', 'Salud'),
('5', 'Minero'),
('6', 'Financiero'),
('7', 'Petrolero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pss_servicio_social`
--

DROP TABLE IF EXISTS `pss_servicio_social`;
CREATE TABLE `pss_servicio_social` (
  `id_servicio_social` char(7) NOT NULL,
  `id_contacto` char(10) NOT NULL,
  `id_modalidad` char(7) NOT NULL,
  `nombre_servicio_social` varchar(500) DEFAULT NULL,
  `cantidad_estudiante` int(11) DEFAULT NULL,
  `objetivo` varchar(500) DEFAULT NULL,
  `importancia` varchar(500) DEFAULT NULL,
  `presupuesto` double DEFAULT NULL,
  `logro` varchar(500) DEFAULT NULL,
  `localidad_proyecto` varchar(100) DEFAULT NULL,
  `beneficiario_directo` int(11) DEFAULT NULL,
  `beneficiario_indirecto` int(11) DEFAULT NULL,
  `descripcion` longtext,
  `nombre_contacto_ss` varchar(100) DEFAULT NULL,
  `email_contacto_ss` varchar(50) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `estado_aprobacion` varchar(2) DEFAULT NULL,
  `disponibilidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pss_tipo_documento`
--

DROP TABLE IF EXISTS `pss_tipo_documento`;
CREATE TABLE `pss_tipo_documento` (
  `id_tipo_documento_pss` char(7) NOT NULL,
  `descripcion` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pss_tipo_documento`
--

INSERT INTO `pss_tipo_documento` (`id_tipo_documento_pss`, `descripcion`) VALUES
('1', 'Carta de Finzalizcion de Servicio Social'),
('2', 'Informe Parcial'),
('3', 'Informe Total'),
('4', 'Plan de Trabajo'),
('5', 'Hoja de Control diario de Actividades'),
('6', 'Memorandum de Asignacion de Docente Tutor'),
('7', 'Memorandum de Aprobación');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `rep_listado_docen_ase_tribu_eva_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `rep_listado_docen_ase_tribu_eva_pdg`;
CREATE TABLE `rep_listado_docen_ase_tribu_eva_pdg` (
`id_equipo_tg` char(7)
,`anio_tg` int(11)
,`NombreApellidoAlumno` varchar(57)
,`id_due` char(7)
,`tema_tesis` varchar(500)
,`NombreApellidoDocente` varchar(61)
,`NombreApellidoTribuEva` varchar(56)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabla`
--

DROP TABLE IF EXISTS `tabla`;
CREATE TABLE `tabla` (
  `valor` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_apro_dene_anteproy_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_apro_dene_anteproy_pdg`;
CREATE TABLE `view_apro_dene_anteproy_pdg` (
`id_equipo_tg` char(7)
,`id_detalle_pdg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`estado_anteproyecto` char(2)
,`anteproy_ingresado_x_equipo` tinyint(1)
,`entrega_copia_anteproy_doc_ase` tinyint(1)
,`entrega_copia_anteproy_tribu_eva1` tinyint(1)
,`entrega_copia_anteproy_tribu_eva2` tinyint(1)
,`ruta` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_apro_dene_cambio_nombre_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_apro_dene_cambio_nombre_pdg`;
CREATE TABLE `view_apro_dene_cambio_nombre_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_solicitud_academica` char(7)
,`ciclo` smallint(6)
,`anio` int(11)
,`ingresado_x_equipo` tinyint(1)
,`generado_x_coordinador_pdg` tinyint(1)
,`estado` char(2)
,`ruta` longtext
,`siglas` char(5)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_apro_dene_perfil_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_apro_dene_perfil_pdg`;
CREATE TABLE `view_apro_dene_perfil_pdg` (
`id_equipo_tg` char(7)
,`id_detalle_pdg` char(7)
,`id_perfil` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`perfil_ingresado_x_equipo` tinyint(1)
,`entrega_copia_perfil` tinyint(1)
,`estado_perfil` char(2)
,`observaciones_perfil` text
,`numero_acta_perfil` char(10)
,`punto_perfil` char(10)
,`acuerdo_perfil` char(10)
,`fecha_aprobacion_perfil` date
,`ruta` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_apro_dene_prorro_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_apro_dene_prorro_pdg`;
CREATE TABLE `view_apro_dene_prorro_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_solicitud_academica` char(7)
,`ciclo` smallint(6)
,`anio` int(11)
,`ingresado_x_equipo` tinyint(1)
,`generado_x_coordinador_pdg` tinyint(1)
,`estado` char(2)
,`ruta` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_asig_docente_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_asig_docente_pdg`;
CREATE TABLE `view_asig_docente_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_docente` char(7)
,`NombreApellidoDocente` varchar(56)
,`correo_docente` varchar(50)
,`es_docente_director_pdg` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_asig_docente_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_asig_docente_pss`;
CREATE TABLE `view_asig_docente_pss` (
`id_det_expediente` char(7)
,`id_servicio_social` char(7)
,`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`id_docente` char(7)
,`NombreApellidoDocente` varchar(56)
,`correo_docente` varchar(50)
,`es_docente_pss_principal` tinyint(1)
,`correlativo_tutor_ss` int(11) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_asig_tribunal_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_asig_tribunal_pdg`;
CREATE TABLE `view_asig_tribunal_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`tema_tesis` varchar(500)
,`ciclo_tesis` int(11)
,`id_docente` char(7)
,`NombreApellidoDocente` varchar(56)
,`correo_docente` varchar(50)
,`id_cargo` char(7)
,`descripcion` longtext
,`es_docente_tribu_principal` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_aux1_docente_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_aux1_docente_pdg`;
CREATE TABLE `view_aux1_docente_pdg` (
`id_docente` char(7)
,`carnet` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`email` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_aux1_docente_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_aux1_docente_pss`;
CREATE TABLE `view_aux1_docente_pss` (
`id_docente` char(7)
,`carnet` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`email` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_aux2_docente_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_aux2_docente_pdg`;
CREATE TABLE `view_aux2_docente_pdg` (
`id_docente` char(7)
,`carnet` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`email` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_candidatos_para_aperturar_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_candidatos_para_aperturar_pss`;
CREATE TABLE `view_candidatos_para_aperturar_pss` (
`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`dui` char(9)
,`email` varchar(50)
,`apertura_expediente_pss` date
,`carta_aptitud_pss` varchar(200)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_cartas_renuncia_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_cartas_renuncia_pss`;
CREATE TABLE `view_cartas_renuncia_pss` (
`id_detalle_expediente` char(7)
,`id_servicio_social` char(7)
,`id_due` char(7)
,`estado` char(2)
,`encabezado_carta_renuncia` longtext
,`motivos_renuncia` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_cierre_expediente_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_cierre_expediente_pss`;
CREATE TABLE `view_cierre_expediente_pss` (
`id_due` varchar(7)
,`remision` tinyint(1)
,`fecha_remision` date
,`total_horas` decimal(32,0)
,`observaciones_exp_pss` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_cierre_notas_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_cierre_notas_pdg`;
CREATE TABLE `view_cierre_notas_pdg` (
`id_equipo_tg` char(7)
,`tema` varchar(500)
,`anio_tg` int(11)
,`ciclo_tg` int(11)
,`etapa_evaluativa` varchar(17)
,`estado_nota` char(2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_cierre_servicios_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_cierre_servicios_pss`;
CREATE TABLE `view_cierre_servicios_pss` (
`id_detalle_expediente` char(7)
,`id_servicio_social` char(7)
,`id_due` char(7)
,`estado` char(2)
,`fecha_inicio` date
,`fecha_fin` date
,`oficializacion` tinyint(1)
,`carta_finalizacion_horas_sociales` varchar(200)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_consolidado_notas_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_consolidado_notas_pdg`;
CREATE TABLE `view_consolidado_notas_pdg` (
`id_consolidado_notas` char(7)
,`id_equipo_tg` char(7)
,`tema` varchar(500)
,`anio_tg` int(11)
,`ciclo_tg` int(11)
,`id_due` char(7)
,`nota_anteproyecto` double
,`nota_etapa1` double
,`nota_etapa2` double
,`nota_defensa_publica` double
,`nota_final` double
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_contacto_x_intitucion_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_contacto_x_intitucion_pss`;
CREATE TABLE `view_contacto_x_intitucion_pss` (
`nombre_intitucion` varchar(30)
,`id_institucion` varchar(7)
,`nombre_apellido_contacto` varchar(52)
,`nombre_apellido_contacto_e_institucion` varchar(83)
,`id_contacto` char(10)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_control_aseso_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_control_aseso_pdg`;
CREATE TABLE `view_control_aseso_pdg` (
`id_bitacora` char(7)
,`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`ciclo_asesoria` smallint(6)
,`anio_asesoria` int(11)
,`fecha` date
,`tema_asesoria` varchar(500)
,`tematica_tratar` longtext
,`hora_inicio` datetime
,`hora_fin` datetime
,`lugar` longtext
,`observaciones` longtext
,`id_due1` char(7)
,`hora_inicio_alumno_1` datetime
,`hora_fin_alumno_1` datetime
,`id_due2` char(7)
,`hora_inicio_alumno_2` datetime
,`hora_fin_alumno_2` datetime
,`id_due3` char(7)
,`hora_inicio_alumno_3` datetime
,`hora_fin_alumno_3` datetime
,`id_due4` char(7)
,`hora_inicio_alumno_4` datetime
,`hora_fin_alumno_4` datetime
,`id_due5` char(7)
,`hora_inicio_alumno_5` datetime
,`hora_fin_alumno_5` datetime
,`id_docente` char(7)
,`hora_inicio_docente` datetime
,`hora_fin_docente` datetime
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_estudiantes_con_exp_con_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_estudiantes_con_exp_con_pss`;
CREATE TABLE `view_estudiantes_con_exp_con_pss` (
`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`dui` char(9)
,`email` varchar(50)
,`apertura_expediente_pss` date
,`fecha_remision` date
,`carta_aptitud_pss` varchar(200)
,`id_detalle_expediente` char(7)
,`id_servicio_social` char(7)
,`id_modalidad` char(7)
,`oficializacion` tinyint(1)
,`estado` char(2)
,`fecha_inicio` date
,`fecha_fin` date
,`horas_prestadas` int(11)
,`cierre_modalidad` tinyint(1)
,`observacion` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_estudiantes_con_exp_sin_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_estudiantes_con_exp_sin_pss`;
CREATE TABLE `view_estudiantes_con_exp_sin_pss` (
`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`dui` char(9)
,`email` varchar(50)
,`apertura_expediente_pss` date
,`carta_aptitud_pss` varchar(200)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_estudiantes_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_estudiantes_pdg`;
CREATE TABLE `view_estudiantes_pdg` (
`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`dui` char(9)
,`direccion` varchar(150)
,`telefono` decimal(8,0)
,`celular` decimal(8,0)
,`email` varchar(50)
,`fecha_nac` date
,`correlativo_equipo` int(11)
,`apertura_expediente` date
,`remision` tinyint(1)
,`fecha_remision` date
,`id_login` char(7)
,`estado_tesis` char(1)
,`carta_aptitud_pss` varchar(200)
,`apertura_expediente_pss` date
,`lugar_trabajo` varchar(200)
,`telefono_trabajo` decimal(8,0)
,`observaciones_exp_pss` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_estudiantes_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_estudiantes_pss`;
CREATE TABLE `view_estudiantes_pss` (
`id_due` char(7)
,`nombre` varchar(30)
,`apellido` varchar(25)
,`dui` char(9)
,`direccion` varchar(150)
,`telefono` decimal(8,0)
,`celular` decimal(8,0)
,`email` varchar(50)
,`fecha_nac` date
,`correlativo_equipo` int(11)
,`apertura_expediente` date
,`remision` tinyint(1)
,`fecha_remision` date
,`id_login` char(7)
,`estado_tesis` char(1)
,`carta_aptitud_pss` varchar(200)
,`apertura_expediente_pss` date
,`lugar_trabajo` varchar(200)
,`telefono_trabajo` decimal(8,0)
,`observaciones_exp_pss` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_expedientes_remitidos_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_expedientes_remitidos_pss`;
CREATE TABLE `view_expedientes_remitidos_pss` (
`id_due` varchar(7)
,`remision` tinyint(1)
,`fecha_remision` date
,`total_horas` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_gen_oficializacion_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_gen_oficializacion_pss`;
CREATE TABLE `view_gen_oficializacion_pss` (
`id_detalle_expediente` char(7)
,`id_servicio_social` char(7)
,`id_due` char(7)
,`estado` char(2)
,`fecha_inicio` date
,`oficializacion` tinyint(1)
,`encabezado_oficializacion` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_ingreso_documentacion_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_ingreso_documentacion_pss`;
CREATE TABLE `view_ingreso_documentacion_pss` (
`id_detalle_expediente` char(7)
,`id_servicio_social` char(7)
,`id_due` char(7)
,`horas_prestadas` int(11)
,`perfil_proyecto` varchar(200)
,`plan_trabajo` varchar(200)
,`informe_parcial` varchar(200)
,`informe_final` varchar(200)
,`memoria` varchar(200)
,`control_actividades` varchar(200)
,`carta_finalizacion_horas_sociales` varchar(200)
,`lugar_trabajo` varchar(200)
,`telefono_trabajo` decimal(8,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_ingre_tema_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_ingre_tema_pdg`;
CREATE TABLE `view_ingre_tema_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`sigla` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_institucion`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_institucion`;
CREATE TABLE `view_institucion` (
`id` int(11)
,`NIT` varchar(7)
,`EMPRESA` varchar(30)
,`RUBRO` varchar(50)
,`TIPO` varchar(7)
,`ESTADO` varchar(11)
,`DIRECCION` varchar(150)
,`TELEFONO` varchar(9)
,`EMAIL` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_perfil`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_perfil`;
CREATE TABLE `view_perfil` (
`id` int(11)
,`id_equipo_tg` char(7)
,`tema` varchar(500)
,`id_perfil` char(7)
,`id_detalle_pdg` char(7)
,`ciclo` smallint(6)
,`anio` int(11)
,`objetivo_general` longtext
,`objetivo_especifico` longtext
,`descripcion` longtext
,`ruta` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_perfil_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_perfil_pdg`;
CREATE TABLE `view_perfil_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_perfil` char(7)
,`id_detalle_pdg` char(7)
,`ciclo_perfil` smallint(6)
,`anio_perfil` int(11)
,`observaciones_perfil` text
,`objetivo_general` longtext
,`objetivo_especifico` longtext
,`descripcion` longtext
,`area_tematica_tg` longtext
,`resultados_esperados_tg` longtext
,`ruta` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_pss_contacto`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_pss_contacto`;
CREATE TABLE `view_pss_contacto` (
`INSTITUCION` varchar(30)
,`id_contacto` char(10)
,`id_institucion` char(17)
,`nombre` varchar(25)
,`apellido` varchar(25)
,`descripcion_cargo` varchar(100)
,`celular` varchar(9)
,`telefono` varchar(9)
,`email` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_pss_servicio_social`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_pss_servicio_social`;
CREATE TABLE `view_pss_servicio_social` (
`id_servicio_social` char(7)
,`id_contacto` char(10)
,`id_modalidad` char(7)
,`nombre_servicio_social` varchar(500)
,`cantidad_estudiante` int(11)
,`objetivo` varchar(500)
,`importancia` varchar(500)
,`presupuesto` double
,`logro` varchar(500)
,`localidad_proyecto` varchar(100)
,`beneficiario_directo` int(11)
,`beneficiario_indirecto` int(11)
,`descripcion` longtext
,`nombre_contacto_ss` varchar(100)
,`email_contacto_ss` varchar(50)
,`nombre` varchar(25)
,`apellido` varchar(25)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_ratificacion_notas_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_ratificacion_notas_pdg`;
CREATE TABLE `view_ratificacion_notas_pdg` (
`id_equipo_tg` char(7)
,`anio_tg` int(11)
,`tema` varchar(500)
,`sigla` varchar(20)
,`ciclo_tg` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_recolector_notas_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_recolector_notas_pdg`;
CREATE TABLE `view_recolector_notas_pdg` (
`id_equipo_tg` char(7)
,`anio_tg` int(11)
,`tema` varchar(500)
,`sigla` varchar(20)
,`ciclo_tg` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_regis_anteproy_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_regis_anteproy_pdg`;
CREATE TABLE `view_regis_anteproy_pdg` (
`id_equipo_tg` char(7)
,`id_detalle_pdg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`fecha_eva_anteproyecto` date
,`fecha_eva_etapa1` date
,`fecha_eva_etapa2` date
,`fecha_eva_publica` date
,`ruta` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_remision_notas_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_remision_notas_pdg`;
CREATE TABLE `view_remision_notas_pdg` (
`id_equipo_tg` char(7)
,`anio_tg` int(11)
,`tema` varchar(500)
,`sigla` varchar(20)
,`ciclo_tg` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_rep_listado_docen_asesores_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_rep_listado_docen_asesores_pdg`;
CREATE TABLE `view_rep_listado_docen_asesores_pdg` (
`id_equipo_tg` char(7)
,`anio_tg` int(11)
,`ciclo_tg` int(11)
,`NombreApellidoAlumno` varchar(57)
,`id_due` char(7)
,`tema_tesis` varchar(500)
,`NombreApellidoDocente` varchar(61)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_rep_resumen_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_rep_resumen_pss`;
CREATE TABLE `view_rep_resumen_pss` (
`id` int(11)
,`id_detalle_expediente` char(7)
,`id_due` char(7)
,`servicio_social` text
,`fecha_inicio` date
,`fecha_fin` date
,`horas_prestadas` int(11)
,`monto` double(19,2)
,`beneficiario_directo` int(11)
,`beneficiario_indirecto` int(11)
,`estado_case` varchar(10)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_resumen_apro_dene_anteproy_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_resumen_apro_dene_anteproy_pdg`;
CREATE TABLE `view_resumen_apro_dene_anteproy_pdg` (
`id_equipo_tg` char(7)
,`id_detalle_pdg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`estado_anteproyecto` char(2)
,`anteproy_ingresado_x_equipo` tinyint(1)
,`entrega_copia_anteproy_doc_ase` tinyint(1)
,`entrega_copia_anteproy_tribu_eva1` tinyint(1)
,`entrega_copia_anteproy_tribu_eva2` tinyint(1)
,`ruta` longtext
,`siglas` char(5)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_resum_apro_dene_cambio_nombre_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_resum_apro_dene_cambio_nombre_pdg`;
CREATE TABLE `view_resum_apro_dene_cambio_nombre_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_solicitud_academica` char(7)
,`ciclo` smallint(6)
,`anio` int(11)
,`ingresado_x_equipo` tinyint(1)
,`generado_x_coordinador_pdg` tinyint(1)
,`estado` char(2)
,`ruta` longtext
,`siglas` char(5)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_resum_apro_dene_prorro_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_resum_apro_dene_prorro_pdg`;
CREATE TABLE `view_resum_apro_dene_prorro_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_solicitud_academica` char(7)
,`ciclo` smallint(6)
,`anio` int(11)
,`ingresado_x_equipo` tinyint(1)
,`generado_x_coordinador_pdg` tinyint(1)
,`estado` char(2)
,`ruta` longtext
,`siglas` char(5)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_resum_subir_doc_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_resum_subir_doc_pdg`;
CREATE TABLE `view_resum_subir_doc_pdg` (
`id_equipo_tg` char(7)
,`id_detalle_pdg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_tipo_documento_pdg` char(7)
,`descripcion` longtext
,`ruta` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_servicio_social_pss`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_servicio_social_pss`;
CREATE TABLE `view_servicio_social_pss` (
`id_servicio_social` char(7)
,`id_contacto` char(10)
,`id_modalidad` char(7)
,`nombre_servicio_social` varchar(500)
,`estado_aprobacion` varchar(2)
,`cantidad_estudiante` int(11)
,`disponibilidad` int(11)
,`objetivo` varchar(500)
,`importancia` varchar(500)
,`presupuesto` double
,`logro` varchar(500)
,`localidad_proyecto` varchar(100)
,`beneficiario_directo` int(11)
,`beneficiario_indirecto` int(11)
,`descripcion` longtext
,`nombre_contacto_ss` varchar(100)
,`email_contacto_ss` varchar(50)
,`estado` varchar(2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_soli_cambio_nombre_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_soli_cambio_nombre_pdg`;
CREATE TABLE `view_soli_cambio_nombre_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_solicitud_academica` char(7)
,`ciclo` smallint(6)
,`anio` int(11)
,`tipo_solicitud` varchar(25)
,`estado` char(2)
,`acuerdo_junta` int(11)
,`nombre_actual` longtext
,`nombre_propuesto` longtext
,`justificacion` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_soli_prorro_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_soli_prorro_pdg`;
CREATE TABLE `view_soli_prorro_pdg` (
`id_equipo_tg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_solicitud_academica` char(7)
,`ciclo` smallint(6)
,`anio` int(11)
,`tipo_solicitud` varchar(25)
,`estado` char(2)
,`fecha_solicitud` date
,`fecha_ini_prorroga` date
,`fecha_fin_prorroga` date
,`duracion` decimal(4,0)
,`eva_antes_prorroga` longtext
,`eva_actual` longtext
,`cantidad_evaluacion_actual` int(11)
,`justificacion` longtext
,`caso_especial` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_subir_doc_pdg`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_subir_doc_pdg`;
CREATE TABLE `view_subir_doc_pdg` (
`id_equipo_tg` char(7)
,`id_detalle_pdg` char(7)
,`anio_tesis` int(11)
,`ciclo_tesis` int(11)
,`tema_tesis` varchar(500)
,`id_tipo_documento_pdg` char(7)
,`descripcion` longtext
,`ruta` longtext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_tipo_estudiante`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `view_tipo_estudiante`;
CREATE TABLE `view_tipo_estudiante` (
`tipo` decimal(23,0)
,`id_due` char(7)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_asi_doc`
--
DROP TABLE IF EXISTS `per_view_asi_doc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_asi_doc`  AS  select distinct `t`.`id_tipo_pera` AS `id_tipo_pera`,concat_ws(' ',`e`.`id_due`,'-',`e`.`nombre`,`e`.`apellido`) AS `estudiante`,`p`.`uv` AS `uv_pera`,`t`.`uv` AS `uv_asignadas`,concat_ws(' - ',`p`.`ciclo`,`p`.`anio`) AS `ciclo`,`a`.`id_docente` AS `id_docente`,`p`.`observaciones` AS `observaciones`,`t`.`comentario` AS `comentario`,`p`.`id_due` AS `due_sin_docente`,`t`.`docente_general` AS `docente_general`,`p`.`id_detalle_pera` AS `id_detalle_pera` from (((`gen_estudiante` `e` join `per_detalle` `p` on((`e`.`id_due` = `p`.`id_due`))) left join `asignado` `a` on(((`p`.`id_due` = `a`.`id_due`) and (`a`.`id_cargo` = 10)))) join `per_tipo` `t` on(((`p`.`id_detalle_pera` = `t`.`id_detalle_pera`) and (`a`.`correlativo_tutor_ss` = `t`.`id_tipo_pera`)))) where (`p`.`estado` = 'd') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_asi_doc_estudiante`
--
DROP TABLE IF EXISTS `per_view_asi_doc_estudiante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_asi_doc_estudiante`  AS  select `p`.`id_due` AS `id_due`,`e`.`nombre` AS `nombre`,`e`.`apellido` AS `apellido`,`a`.`id_docente` AS `id_docente` from ((`gen_estudiante` `e` join `per_detalle` `p` on((`e`.`id_due` = `p`.`id_due`))) left join `asignado` `a` on((`p`.`id_due` = `a`.`id_due`))) where ((`p`.`estado` = 'd') and (`a`.`id_cargo` = 3) and (`p`.`id_detalle_pera` = `a`.`correlativo_tutor_ss`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_asi_gen`
--
DROP TABLE IF EXISTS `per_view_asi_gen`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_asi_gen`  AS  select distinct `p`.`id_detalle_pera` AS `id_detalle_pera`,`p`.`id_due` AS `id_due`,`e`.`nombre` AS `nombre`,`e`.`apellido` AS `apellido`,`p`.`cum` AS `cum`,`p`.`uv` AS `uv`,`p`.`ciclo` AS `ciclo`,`p`.`anio` AS `anio`,`a`.`id_docente` AS `id_docente`,`p`.`observaciones` AS `observaciones`,`p`.`id_due` AS `due_sin_docente` from ((`gen_estudiante` `e` join `per_detalle` `p` on((`e`.`id_due` = `p`.`id_due`))) left join `asignado` `a` on(((`p`.`id_due` = `a`.`id_due`) and (`a`.`id_cargo` = 3) and (`a`.`correlativo_tutor_ss` = `p`.`id_detalle_pera`)))) where ((`p`.`estado` = 'i') or (`p`.`estado` = 'd')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_asi_gen_estudiante`
--
DROP TABLE IF EXISTS `per_view_asi_gen_estudiante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_asi_gen_estudiante`  AS  select distinct `p`.`id_due` AS `id_due`,`e`.`nombre` AS `nombre`,`e`.`apellido` AS `apellido` from ((`gen_estudiante` `e` join `per_detalle` `p` on((`e`.`id_due` = `p`.`id_due`))) left join `asignado` `a` on((`p`.`id_due` = `a`.`id_due`))) where (`p`.`estado` = 'i') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_cie_per`
--
DROP TABLE IF EXISTS `per_view_cie_per`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_cie_per`  AS  select `rg`.`estado_registro` AS `estado_registro`,`rg`.`estado` AS `estado`,`rg`.`estado` AS `estado_2`,`d`.`id_detalle_pera` AS `id_detalle_pera`,concat_ws(' - ',`d`.`ciclo`,`d`.`anio`) AS `ciclo`,`e`.`id_due` AS `id_due`,concat_ws(' ',`e`.`nombre`,`e`.`apellido`) AS `estudiante`,concat_ws(' ',`dc`.`nombre`,`dc`.`apellido`) AS `docente_mentor` from ((((`per_registro_nota` `rg` join `per_detalle` `d` on((`rg`.`id_detalle_pera` = `d`.`id_detalle_pera`))) join `gen_estudiante` `e` on((`d`.`id_due` = `e`.`id_due`))) join `asignado` `a` on(((`e`.`id_due` = `a`.`id_due`) and (`a`.`id_proceso` = 'PER') and (`a`.`id_cargo` = 3) and (`d`.`id_detalle_pera` = `a`.`correlativo_tutor_ss`)))) join `gen_docente` `dc` on((`a`.`id_docente` = `dc`.`id_docente`))) where ((`d`.`estado` = 'f') or (`d`.`estado` = 'a') or (`d`.`estado` = 'c')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_def_tip`
--
DROP TABLE IF EXISTS `per_view_def_tip`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_def_tip`  AS  select distinct `t`.`id_tipo_pera` AS `id_tipo_pera`,concat_ws(' ',`e`.`id_due`,'-',`e`.`nombre`,`e`.`apellido`) AS `estudiante`,`t`.`uv` AS `uv_asignadas`,concat_ws(' - ',`p`.`ciclo`,`p`.`anio`) AS `ciclo`,`t`.`tipo` AS `tipo`,`t`.`descripcion` AS `descripcion`,`t`.`inicio` AS `inicio`,`t`.`fin` AS `fin`,`a`.`id_docente` AS `id_docente`,`t`.`comentario` AS `comentario`,`p`.`id_due` AS `id_due`,`t`.`docente_general` AS `docente_general`,`p`.`id_detalle_pera` AS `id_detalle_pera` from (((`gen_estudiante` `e` join `per_detalle` `p` on((`e`.`id_due` = `p`.`id_due`))) left join `asignado` `a` on(((`p`.`id_due` = `a`.`id_due`) and (`a`.`id_cargo` = 10)))) join `per_tipo` `t` on(((`p`.`id_detalle_pera` = `t`.`id_detalle_pera`) and (`a`.`correlativo_tutor_ss` = `t`.`id_tipo_pera`)))) where (`p`.`estado` = 'd') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_def_tip_estudiantes`
--
DROP TABLE IF EXISTS `per_view_def_tip_estudiantes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_def_tip_estudiantes`  AS  (select `per_detalle`.`id_detalle_pera` AS `id_detalle_pera`,`per_detalle`.`id_due` AS `id_due`,`gen_estudiante`.`nombre` AS `nombre`,`gen_estudiante`.`apellido` AS `apellido` from (`per_detalle` join `gen_estudiante` on((`per_detalle`.`id_due` = `gen_estudiante`.`id_due`)))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_est_eva`
--
DROP TABLE IF EXISTS `per_view_est_eva`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_est_eva`  AS  select `e`.`id_evaluacion` AS `id_evaluacion`,`v`.`estudiante` AS `estudiante`,`v`.`ciclo` AS `ciclo`,`v`.`id_tipo_pera` AS `id_tipo_pera`,concat_ws(' ',`v`.`tipo`,'-',`m`.`nombre`,'-',`m`.`uv`,'U.V.') AS `tipo`,`v`.`descripcion` AS `descripcion_pera`,`e`.`nombre` AS `nombre`,`e`.`fecha` AS `fecha`,`e`.`descripcion` AS `descripcion_evaluacion`,`e`.`porcentaje` AS `porcentaje`,`e`.`nota` AS `nota`,`v`.`id_docente` AS `id_docente`,`v`.`id_due` AS `id_due`,`v`.`docente_general` AS `docente_general`,`v`.`id_detalle_pera` AS `id_detalle_pera` from ((`per_view_def_tip` `v` join `per_evaluacion` `e` on((`v`.`id_tipo_pera` = `e`.`id_tipo_pera`))) join `gen_materia` `m` on((`v`.`tipo` = `m`.`id_materia`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_est_eva_1`
--
DROP TABLE IF EXISTS `per_view_est_eva_1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_est_eva_1`  AS  (select `per_evaluacion`.`id_evaluacion` AS `id_evaluacion`,concat_ws(' ',`n`.`id_due`,`n`.`nombre`,`n`.`apellido`) AS `Estudiante`,`per_evaluacion`.`id_tipo_pera` AS `id_tipo_pera`,`per_evaluacion`.`nombre` AS `nombre`,`per_evaluacion`.`fecha` AS `fecha`,`per_evaluacion`.`descripcion` AS `descripcion`,`per_evaluacion`.`porcentaje` AS `porcentaje`,`per_evaluacion`.`nota` AS `nota` from (`per_view_est_eva_estudiantes` `n` join `per_evaluacion` on((`n`.`id_tipo_pera` = `per_evaluacion`.`id_tipo_pera`)))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_est_eva_estudiante`
--
DROP TABLE IF EXISTS `per_view_est_eva_estudiante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_est_eva_estudiante`  AS  (select distinct `d`.`id_due` AS `id_due`,`gen_estudiante`.`nombre` AS `nombre`,`gen_estudiante`.`apellido` AS `apellido` from ((`per_tipo` `t` join `per_detalle` `d` on((`t`.`id_detalle_pera` = `d`.`id_detalle_pera`))) join `gen_estudiante` on((`d`.`id_due` = `gen_estudiante`.`id_due`)))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_est_eva_estudiantes`
--
DROP TABLE IF EXISTS `per_view_est_eva_estudiantes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_est_eva_estudiantes`  AS  (select distinct `t`.`id_tipo_pera` AS `id_tipo_pera`,`d`.`id_due` AS `id_due`,`gen_estudiante`.`nombre` AS `nombre`,`gen_estudiante`.`apellido` AS `apellido` from ((`per_tipo` `t` join `per_detalle` `d` on((`t`.`id_detalle_pera` = `d`.`id_detalle_pera`))) join `gen_estudiante` on((`d`.`id_due` = `gen_estudiante`.`id_due`)))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_est_eva_id_tipo_pera`
--
DROP TABLE IF EXISTS `per_view_est_eva_id_tipo_pera`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_est_eva_id_tipo_pera`  AS  select `v`.`id_tipo_pera` AS `id_tipo_pera`,`v`.`tipo` AS `tipo`,`v`.`descripcion` AS `descripcion_pera`,`m`.`nombre` AS `nombre`,`m`.`uv` AS `uv`,`v`.`id_docente` AS `id_docente`,`v`.`id_due` AS `id_due`,`v`.`docente_general` AS `docente_general`,`v`.`id_detalle_pera` AS `id_detalle_pera` from (`per_view_def_tip` `v` join `gen_materia` `m` on((`m`.`id_materia` = `v`.`tipo`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_gen_docente`
--
DROP TABLE IF EXISTS `per_view_gen_docente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_gen_docente`  AS  (select `gen_docente`.`id_docente` AS `docente` from `gen_docente`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_reg_eva`
--
DROP TABLE IF EXISTS `per_view_reg_eva`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_reg_eva`  AS  (select `per_evaluacion`.`id_evaluacion` AS `id_evaluacion`,`n`.`id_due` AS `id_due`,`per_evaluacion`.`id_tipo_pera` AS `id_tipo_pera`,`per_evaluacion`.`nombre` AS `nombre`,`per_evaluacion`.`fecha` AS `fecha`,`per_evaluacion`.`descripcion` AS `descripcion`,`per_evaluacion`.`porcentaje` AS `porcentaje`,`per_evaluacion`.`nota` AS `nota`,`n`.`estudiante_pera` AS `estudiante_pera`,`n`.`tipo_pera` AS `tipo_pera` from (`per_view_reg_eva_estudiantes` `n` join `per_evaluacion` on((`n`.`id_tipo_pera` = `per_evaluacion`.`id_tipo_pera`)))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_reg_eva_estudiantes`
--
DROP TABLE IF EXISTS `per_view_reg_eva_estudiantes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_reg_eva_estudiantes`  AS  (select distinct `t`.`id_tipo_pera` AS `id_tipo_pera`,`d`.`id_due` AS `id_due`,concat_ws(' ',`d`.`id_due`,`gen_estudiante`.`nombre`,`gen_estudiante`.`apellido`) AS `estudiante_pera`,concat_ws(' ',`t`.`tipo`,':',`t`.`descripcion`) AS `tipo_pera` from ((`per_tipo` `t` join `per_detalle` `d` on((`t`.`id_detalle_pera` = `d`.`id_detalle_pera`))) join `gen_estudiante` on((`d`.`id_due` = `gen_estudiante`.`id_due`)))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_reg_not`
--
DROP TABLE IF EXISTS `per_view_reg_not`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_reg_not`  AS  select `rg`.`id_registro_nota` AS `id_registro_nota`,`rg`.`id_detalle_pera` AS `id_detalle_pera`,`rg`.`estado` AS `estado`,`rg`.`ciclo` AS `ciclo`,`rg`.`anio` AS `anio`,`rg`.`docente_mentor` AS `docente_mentor`,`rg`.`fecha_finalizacion` AS `fecha_finalizacion`,`rg`.`descripcion` AS `descripcion`,`rg`.`n1` AS `n1`,`rg`.`p1` AS `p1`,`rg`.`n2` AS `n2`,`rg`.`p2` AS `p2`,`rg`.`n3` AS `n3`,`rg`.`p3` AS `p3`,`rg`.`n4` AS `n4`,`rg`.`p4` AS `p4`,`rg`.`n5` AS `n5`,`rg`.`p5` AS `p5`,`rg`.`promedio` AS `promedio`,`a`.`id_due` AS `due`,`a`.`id_docente` AS `docente`,concat_ws(' ',`e`.`nombre`,`e`.`apellido`) AS `estudiante` from (((`per_registro_nota` `rg` join `per_detalle` `p` on((`rg`.`id_detalle_pera` = `p`.`id_detalle_pera`))) join `gen_estudiante` `e` on((`p`.`id_due` = `e`.`id_due`))) join `asignado` `a` on(((`e`.`id_due` = `a`.`id_due`) and (`rg`.`id_detalle_pera` = `a`.`correlativo_tutor_ss`) and (`a`.`id_cargo` = 3) and (`a`.`id_proceso` = 'PER')))) where (`p`.`estado` <> 'c') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_reg_not_estudiante`
--
DROP TABLE IF EXISTS `per_view_reg_not_estudiante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_reg_not_estudiante`  AS  select `p`.`id_detalle_pera` AS `id_detalle_pera`,`p`.`id_due` AS `id_due`,`e`.`nombre` AS `nombre`,`e`.`apellido` AS `apellido`,`a`.`id_docente` AS `id_docente` from ((`per_detalle` `p` join `gen_estudiante` `e` on((`p`.`id_due` = `e`.`id_due`))) join `asignado` `a` on(((`e`.`id_due` = `a`.`id_due`) and (`p`.`id_detalle_pera` = `a`.`correlativo_tutor_ss`) and (`a`.`id_cargo` = 3) and (`a`.`id_proceso` = 'PER')))) where (`p`.`estado` = 'd') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_reg_not_final`
--
DROP TABLE IF EXISTS `per_view_reg_not_final`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_reg_not_final`  AS  select `e`.`id_evaluacion` AS `id_evaluacion`,`e`.`porcentaje` AS `porcentaje`,`e`.`nota` AS `nota`,`t`.`id_tipo_pera` AS `id_tipo_pera`,`t`.`uv` AS `uv_tipo`,`d`.`id_detalle_pera` AS `id_detalle_pera`,`d`.`uv` AS `uv_detalle`,`d`.`estado` AS `estado`,sum((`e`.`nota` * `e`.`porcentaje`)) AS `nota_tipo`,(sum((`e`.`nota` * `e`.`porcentaje`)) * `t`.`uv`) AS `nota_tipo_uv` from ((`per_detalle` `d` join `per_tipo` `t` on((`d`.`id_detalle_pera` = `t`.`id_detalle_pera`))) join `per_evaluacion` `e` on((`t`.`id_tipo_pera` = `e`.`id_tipo_pera`))) where (`d`.`estado` = 'd') group by `d`.`id_detalle_pera`,`t`.`id_tipo_pera` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `per_view_seleccionar_estudiantes`
--
DROP TABLE IF EXISTS `per_view_seleccionar_estudiantes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `per_view_seleccionar_estudiantes`  AS  (select `p`.`id_due` AS `id_due`,`p`.`id_due` AS `due_sin_docente`,`e`.`nombre` AS `nombre`,`e`.`apellido` AS `apellido` from ((`gen_estudiante` `e` join `per_detalle` `p` on((`e`.`id_due` = `p`.`id_due`))) left join `asignado` `a` on((`p`.`id_due` = `a`.`id_due`))) where isnull(`a`.`id_due`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `rep_listado_docen_ase_tribu_eva_pdg`
--
DROP TABLE IF EXISTS `rep_listado_docen_ase_tribu_eva_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `rep_listado_docen_ase_tribu_eva_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tg`,concat(`c`.`apellido`,', ',`c`.`nombre`) AS `NombreApellidoAlumno`,`a`.`id_due` AS `id_due`,`b`.`tema_tesis` AS `tema_tesis`,concat('Ing. ',`b`.`NombreApellidoDocente`) AS `NombreApellidoDocente`,`d`.`NombreApellidoDocente` AS `NombreApellidoTribuEva` from (((`conforma` `a` join `asig_docente_aux_pdg` `b`) join `gen_estudiante` `c`) join `asig_tribunal_aux_pdg` `d`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tesis`) and (`a`.`ciclo_tg` = `b`.`ciclo_tesis`) and (`a`.`id_due` = `c`.`id_due`) and (`a`.`id_equipo_tg` = `d`.`id_equipo_tg`) and (`a`.`anio_tg` = `d`.`anio_tesis`) and (`a`.`ciclo_tg` = `d`.`ciclo_tesis`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_apro_dene_anteproy_pdg`
--
DROP TABLE IF EXISTS `view_apro_dene_anteproy_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_apro_dene_anteproy_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`b`.`id_detalle_pdg` AS `id_detalle_pdg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`b`.`estado_anteproyecto` AS `estado_anteproyecto`,`b`.`anteproy_ingresado_x_equipo` AS `anteproy_ingresado_x_equipo`,`b`.`entrega_copia_anteproy_doc_ase` AS `entrega_copia_anteproy_doc_ase`,`b`.`entrega_copia_anteproy_tribu_eva1` AS `entrega_copia_anteproy_tribu_eva1`,`b`.`entrega_copia_anteproy_tribu_eva2` AS `entrega_copia_anteproy_tribu_eva2`,`c`.`ruta` AS `ruta` from (((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`d`.`siglas` = 'ADPAA')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_apro_dene_cambio_nombre_pdg`
--
DROP TABLE IF EXISTS `view_apro_dene_cambio_nombre_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_apro_dene_cambio_nombre_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`e`.`id_solicitud_academica` AS `id_solicitud_academica`,`e`.`ciclo` AS `ciclo`,`e`.`anio` AS `anio`,`e`.`ingresado_x_equipo` AS `ingresado_x_equipo`,`e`.`generado_x_coordinador_pdg` AS `generado_x_coordinador_pdg`,`e`.`estado` AS `estado`,`c`.`ruta` AS `ruta`,`d`.`siglas` AS `siglas` from ((((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) join `pdg_solicitud_academica` `e`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`d`.`siglas` in ('ADPCN','MACN','MDCN')) and (`b`.`id_detalle_pdg` = `e`.`id_detalle_pdg`) and (`c`.`id_documento_pdg` = `e`.`id_documento_pdg`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_apro_dene_perfil_pdg`
--
DROP TABLE IF EXISTS `view_apro_dene_perfil_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_apro_dene_perfil_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`b`.`id_detalle_pdg` AS `id_detalle_pdg`,`e`.`id_perfil` AS `id_perfil`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`b`.`perfil_ingresado_x_equipo` AS `perfil_ingresado_x_equipo`,`b`.`entrega_copia_perfil` AS `entrega_copia_perfil`,`b`.`estado_perfil` AS `estado_perfil`,`b`.`observaciones_perfil` AS `observaciones_perfil`,`b`.`numero_acta_perfil` AS `numero_acta_perfil`,`b`.`punto_perfil` AS `punto_perfil`,`b`.`acuerdo_perfil` AS `acuerdo_perfil`,`b`.`fecha_aprobacion_perfil` AS `fecha_aprobacion_perfil`,`c`.`ruta` AS `ruta` from ((((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) join `pdg_perfil` `e`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`b`.`id_detalle_pdg` = `e`.`id_detalle_pdg`) and (`d`.`siglas` in ('MAP','MDP','ADPPP'))) order by `a`.`id_equipo_tg` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_apro_dene_prorro_pdg`
--
DROP TABLE IF EXISTS `view_apro_dene_prorro_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_apro_dene_prorro_pdg`  AS  select distinct `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`e`.`id_solicitud_academica` AS `id_solicitud_academica`,`e`.`ciclo` AS `ciclo`,`e`.`anio` AS `anio`,`e`.`ingresado_x_equipo` AS `ingresado_x_equipo`,`e`.`generado_x_coordinador_pdg` AS `generado_x_coordinador_pdg`,`e`.`estado` AS `estado`,`c`.`ruta` AS `ruta` from ((((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) join `pdg_solicitud_academica` `e`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`d`.`siglas` = 'ADPPR') and (`b`.`id_detalle_pdg` = `e`.`id_detalle_pdg`) and (`e`.`id_documento_pdg` = `c`.`id_documento_pdg`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_asig_docente_pdg`
--
DROP TABLE IF EXISTS `view_asig_docente_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_asig_docente_pdg`  AS  select distinct `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`d`.`id_docente` AS `id_docente`,concat(`d`.`nombre`,' ',`d`.`apellido`) AS `NombreApellidoDocente`,`d`.`email` AS `correo_docente`,`c`.`es_docente_director_pdg` AS `es_docente_director_pdg` from (((((`pdg_equipo_tg` `a` join `conforma` `bb`) join `gen_estudiante` `b`) join `asignado` `c`) join `gen_docente` `d`) join `gen_cargo` `e`) where ((`a`.`id_equipo_tg` = `bb`.`id_equipo_tg`) and (`a`.`anio_tg` = `bb`.`anio_tg`) and (`a`.`ciclo_tg` = `bb`.`ciclo_tg`) and (`bb`.`id_due` = `b`.`id_due`) and (`b`.`id_due` = `c`.`id_due`) and (`c`.`id_docente` = `d`.`id_docente`) and (`d`.`id_cargo` = `e`.`id_cargo`) and (`e`.`id_cargo` = '2')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_asig_docente_pss`
--
DROP TABLE IF EXISTS `view_asig_docente_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_asig_docente_pss`  AS  select distinct `a`.`id_detalle_expediente` AS `id_det_expediente`,`a`.`id_servicio_social` AS `id_servicio_social`,`b`.`id_due` AS `id_due`,`b`.`nombre` AS `nombre`,`b`.`apellido` AS `apellido`,`d`.`id_docente` AS `id_docente`,concat(`d`.`nombre`,' ',`d`.`apellido`) AS `NombreApellidoDocente`,`d`.`email` AS `correo_docente`,`c`.`es_docente_pss_principal` AS `es_docente_pss_principal`,`c`.`correlativo_tutor_ss` AS `correlativo_tutor_ss` from ((((`pss_detalle_expediente` `a` join `gen_estudiante` `b`) join `asignado` `c`) join `gen_docente` `d`) join `gen_cargo` `e`) where ((`a`.`id_due` = `b`.`id_due`) and (`b`.`id_due` = `c`.`id_due`) and (`c`.`id_docente` = `d`.`id_docente`) and (`d`.`id_cargo` = `e`.`id_cargo`) and (`a`.`id_detalle_expediente` = `c`.`id_detalle_expediente`) and (`c`.`id_proceso` = 'PSS') and (`e`.`id_cargo` = '4')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_asig_tribunal_pdg`
--
DROP TABLE IF EXISTS `view_asig_tribunal_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_asig_tribunal_pdg`  AS  select distinct `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`tema` AS `tema_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`d`.`id_docente` AS `id_docente`,concat(`d`.`nombre`,' ',`d`.`apellido`) AS `NombreApellidoDocente`,`d`.`email` AS `correo_docente`,`e`.`id_cargo` AS `id_cargo`,`e`.`descripcion` AS `descripcion`,`c`.`es_docente_tribu_principal` AS `es_docente_tribu_principal` from (((((`pdg_equipo_tg` `a` join `conforma` `bb`) join `gen_estudiante` `b`) join `asignado` `c`) join `gen_docente` `d`) join `gen_cargo` `e`) where ((`a`.`id_equipo_tg` = `bb`.`id_equipo_tg`) and (`a`.`anio_tg` = `bb`.`anio_tg`) and (`a`.`ciclo_tg` = `bb`.`ciclo_tg`) and (`bb`.`id_due` = `b`.`id_due`) and (`b`.`id_due` = `c`.`id_due`) and (`c`.`id_docente` = `d`.`id_docente`) and (`d`.`id_cargo` = `e`.`id_cargo`) and (`e`.`id_cargo` in (5,6))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_aux1_docente_pdg`
--
DROP TABLE IF EXISTS `view_aux1_docente_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_aux1_docente_pdg`  AS  select `gen_docente`.`id_docente` AS `id_docente`,`gen_docente`.`carnet` AS `carnet`,`gen_docente`.`nombre` AS `nombre`,`gen_docente`.`apellido` AS `apellido`,`gen_docente`.`email` AS `email` from `gen_docente` where (`gen_docente`.`id_cargo` = '2') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_aux1_docente_pss`
--
DROP TABLE IF EXISTS `view_aux1_docente_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_aux1_docente_pss`  AS  select `gen_docente`.`id_docente` AS `id_docente`,`gen_docente`.`carnet` AS `carnet`,`gen_docente`.`nombre` AS `nombre`,`gen_docente`.`apellido` AS `apellido`,`gen_docente`.`email` AS `email` from `gen_docente` where (`gen_docente`.`id_cargo` = '4') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_aux2_docente_pdg`
--
DROP TABLE IF EXISTS `view_aux2_docente_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_aux2_docente_pdg`  AS  select `gen_docente`.`id_docente` AS `id_docente`,`gen_docente`.`carnet` AS `carnet`,`gen_docente`.`nombre` AS `nombre`,`gen_docente`.`apellido` AS `apellido`,`gen_docente`.`email` AS `email` from `gen_docente` where (`gen_docente`.`id_cargo` in ('5','6')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_candidatos_para_aperturar_pss`
--
DROP TABLE IF EXISTS `view_candidatos_para_aperturar_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_candidatos_para_aperturar_pss`  AS  select `a`.`id_due` AS `id_due`,`a`.`nombre` AS `nombre`,`a`.`apellido` AS `apellido`,`a`.`dui` AS `dui`,`a`.`email` AS `email`,`a`.`apertura_expediente_pss` AS `apertura_expediente_pss`,`a`.`carta_aptitud_pss` AS `carta_aptitud_pss` from ((`gen_estudiante` `a` join `es` `b`) join `gen_tipo_estudiante` `c`) where ((`a`.`id_due` = `b`.`id_due`) and (`b`.`id_tipo_estudiante` = `c`.`id_tipo_estudiante`) and (`c`.`id_tipo_estudiante` = 'PSS') and isnull(`a`.`apertura_expediente_pss`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_cartas_renuncia_pss`
--
DROP TABLE IF EXISTS `view_cartas_renuncia_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_cartas_renuncia_pss`  AS  select `pss_detalle_expediente`.`id_detalle_expediente` AS `id_detalle_expediente`,`pss_detalle_expediente`.`id_servicio_social` AS `id_servicio_social`,`pss_detalle_expediente`.`id_due` AS `id_due`,`pss_detalle_expediente`.`estado` AS `estado`,`pss_detalle_expediente`.`encabezado_carta_renuncia` AS `encabezado_carta_renuncia`,`pss_detalle_expediente`.`motivos_renuncia` AS `motivos_renuncia` from `pss_detalle_expediente` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_cierre_expediente_pss`
--
DROP TABLE IF EXISTS `view_cierre_expediente_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_cierre_expediente_pss`  AS  select `a`.`id_due` AS `id_due`,`b`.`remision` AS `remision`,`b`.`fecha_remision` AS `fecha_remision`,sum(`a`.`horas_prestadas`) AS `total_horas`,`b`.`observaciones_exp_pss` AS `observaciones_exp_pss` from (`pss_detalle_expediente` `a` join `gen_estudiante` `b`) where ((`a`.`id_due` = `b`.`id_due`) and isnull(`b`.`fecha_remision`) and isnull(`b`.`remision`) and (`a`.`estado` = 'C')) having (`total_horas` >= 500) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_cierre_notas_pdg`
--
DROP TABLE IF EXISTS `view_cierre_notas_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_cierre_notas_pdg`  AS  select `pdg_nota_anteproyecto`.`id_equipo_tg` AS `id_equipo_tg`,`pdg_nota_anteproyecto`.`tema` AS `tema`,`pdg_nota_anteproyecto`.`anio_tg` AS `anio_tg`,`pdg_nota_anteproyecto`.`ciclo_tg` AS `ciclo_tg`,'1-Anteproyecto' AS `etapa_evaluativa`,`pdg_nota_anteproyecto`.`estado_nota` AS `estado_nota` from `pdg_nota_anteproyecto` where (`pdg_nota_anteproyecto`.`estado_nota` = 'A') union all select distinct `pdg_nota_etapa1`.`id_equipo_tg` AS `id_equipo_tg`,`pdg_nota_etapa1`.`tema` AS `tema`,`pdg_nota_etapa1`.`anio_tg` AS `anio_tg`,`pdg_nota_etapa1`.`ciclo_tg` AS `ciclo_tg`,'2-Etapa 1' AS `etapa_evaluativa`,`pdg_nota_etapa1`.`estado_nota` AS `estado_nota` from `pdg_nota_etapa1` where (`pdg_nota_etapa1`.`estado_nota` = 'A') union all select distinct `pdg_nota_etapa2`.`id_equipo_tg` AS `id_equipo_tg`,`pdg_nota_etapa2`.`tema` AS `tema`,`pdg_nota_etapa2`.`anio_tg` AS `anio_tg`,`pdg_nota_etapa2`.`ciclo_tg` AS `ciclo_tg`,'3-Etapa 2' AS `etapa_evaluativa`,`pdg_nota_etapa2`.`estado_nota` AS `estado_nota` from `pdg_nota_etapa2` where (`pdg_nota_etapa2`.`estado_nota` = 'A') union all select distinct `pdg_nota_defensa_publica`.`id_equipo_tg` AS `id_equipo_tg`,`pdg_nota_defensa_publica`.`tema` AS `tema`,`pdg_nota_defensa_publica`.`anio_tg` AS `anio_tg`,`pdg_nota_defensa_publica`.`ciclo_tg` AS `ciclo_tg`,'4-Defensa Pública' AS `etapa_evaluativa`,`pdg_nota_defensa_publica`.`estado_nota` AS `estado_nota` from `pdg_nota_defensa_publica` where (`pdg_nota_defensa_publica`.`estado_nota` = 'A') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_cierre_servicios_pss`
--
DROP TABLE IF EXISTS `view_cierre_servicios_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_cierre_servicios_pss`  AS  select `pss_detalle_expediente`.`id_detalle_expediente` AS `id_detalle_expediente`,`pss_detalle_expediente`.`id_servicio_social` AS `id_servicio_social`,`pss_detalle_expediente`.`id_due` AS `id_due`,`pss_detalle_expediente`.`estado` AS `estado`,`pss_detalle_expediente`.`fecha_inicio` AS `fecha_inicio`,`pss_detalle_expediente`.`fecha_fin` AS `fecha_fin`,`pss_detalle_expediente`.`oficializacion` AS `oficializacion`,`pss_detalle_expediente`.`carta_finalizacion_horas_sociales` AS `carta_finalizacion_horas_sociales` from `pss_detalle_expediente` where ((`pss_detalle_expediente`.`carta_finalizacion_horas_sociales` is not null) and (`pss_detalle_expediente`.`estado` = 'O')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_consolidado_notas_pdg`
--
DROP TABLE IF EXISTS `view_consolidado_notas_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_consolidado_notas_pdg`  AS  select `pdg_consolidado_notas`.`id_consolidado_notas` AS `id_consolidado_notas`,`pdg_consolidado_notas`.`id_equipo_tg` AS `id_equipo_tg`,`pdg_consolidado_notas`.`tema` AS `tema`,`pdg_consolidado_notas`.`anio_tg` AS `anio_tg`,`pdg_consolidado_notas`.`ciclo_tg` AS `ciclo_tg`,`pdg_consolidado_notas`.`id_due` AS `id_due`,`pdg_consolidado_notas`.`nota_anteproyecto` AS `nota_anteproyecto`,`pdg_consolidado_notas`.`nota_etapa1` AS `nota_etapa1`,`pdg_consolidado_notas`.`nota_etapa2` AS `nota_etapa2`,`pdg_consolidado_notas`.`nota_defensa_publica` AS `nota_defensa_publica`,((((`pdg_consolidado_notas`.`nota_anteproyecto` * 0.20) + (`pdg_consolidado_notas`.`nota_etapa1` * 0.35)) + (`pdg_consolidado_notas`.`nota_etapa2` * 0.25)) + (`pdg_consolidado_notas`.`nota_defensa_publica` * 0.20)) AS `nota_final` from `pdg_consolidado_notas` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_contacto_x_intitucion_pss`
--
DROP TABLE IF EXISTS `view_contacto_x_intitucion_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_contacto_x_intitucion_pss`  AS  select `a`.`nombre` AS `nombre_intitucion`,`a`.`id_institucion` AS `id_institucion`,concat(`b`.`nombre`,', ',`b`.`apellido`) AS `nombre_apellido_contacto`,concat(`a`.`nombre`,'-',concat(`b`.`nombre`,', ',`b`.`apellido`)) AS `nombre_apellido_contacto_e_institucion`,`b`.`id_contacto` AS `id_contacto` from (`pss_institucion` `a` join `pss_contacto` `b`) where (`a`.`id_institucion` = `b`.`id_institucion`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_control_aseso_pdg`
--
DROP TABLE IF EXISTS `view_control_aseso_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_control_aseso_pdg`  AS  select `c`.`id_bitacora` AS `id_bitacora`,`a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`c`.`ciclo` AS `ciclo_asesoria`,`c`.`anio` AS `anio_asesoria`,`c`.`fecha` AS `fecha`,`c`.`tema` AS `tema_asesoria`,`c`.`tematica_tratar` AS `tematica_tratar`,`c`.`hora_inicio` AS `hora_inicio`,`c`.`hora_fin` AS `hora_fin`,`c`.`lugar` AS `lugar`,`c`.`observaciones` AS `observaciones`,`c`.`id_due1` AS `id_due1`,`c`.`hora_inicio_alumno_1` AS `hora_inicio_alumno_1`,`c`.`hora_fin_alumno_1` AS `hora_fin_alumno_1`,`c`.`id_due2` AS `id_due2`,`c`.`hora_inicio_alumno_2` AS `hora_inicio_alumno_2`,`c`.`hora_fin_alumno_2` AS `hora_fin_alumno_2`,`c`.`id_due3` AS `id_due3`,`c`.`hora_inicio_alumno_3` AS `hora_inicio_alumno_3`,`c`.`hora_fin_alumno_3` AS `hora_fin_alumno_3`,`c`.`id_due4` AS `id_due4`,`c`.`hora_inicio_alumno_4` AS `hora_inicio_alumno_4`,`c`.`hora_fin_alumno_4` AS `hora_fin_alumno_4`,`c`.`id_due5` AS `id_due5`,`c`.`hora_inicio_alumno_5` AS `hora_inicio_alumno_5`,`c`.`hora_fin_alumno_5` AS `hora_fin_alumno_5`,`c`.`id_docente` AS `id_docente`,`c`.`hora_inicio_docente` AS `hora_inicio_docente`,`c`.`hora_fin_docente` AS `hora_fin_docente` from ((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_bitacora_control` `c`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_estudiantes_con_exp_con_pss`
--
DROP TABLE IF EXISTS `view_estudiantes_con_exp_con_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_estudiantes_con_exp_con_pss`  AS  select `a`.`id_due` AS `id_due`,`a`.`nombre` AS `nombre`,`a`.`apellido` AS `apellido`,`a`.`dui` AS `dui`,`a`.`email` AS `email`,`a`.`apertura_expediente_pss` AS `apertura_expediente_pss`,`a`.`fecha_remision` AS `fecha_remision`,`a`.`carta_aptitud_pss` AS `carta_aptitud_pss`,`d`.`id_detalle_expediente` AS `id_detalle_expediente`,`d`.`id_servicio_social` AS `id_servicio_social`,`e`.`id_modalidad` AS `id_modalidad`,`d`.`oficializacion` AS `oficializacion`,`d`.`estado` AS `estado`,`d`.`fecha_inicio` AS `fecha_inicio`,`d`.`fecha_fin` AS `fecha_fin`,`d`.`horas_prestadas` AS `horas_prestadas`,`d`.`cierre_modalidad` AS `cierre_modalidad`,`d`.`observacion` AS `observacion` from ((((`gen_estudiante` `a` join `es` `b`) join `gen_tipo_estudiante` `c`) join `pss_detalle_expediente` `d`) join `pss_servicio_social` `e`) where ((`a`.`id_due` = `b`.`id_due`) and (`b`.`id_tipo_estudiante` = `c`.`id_tipo_estudiante`) and (`a`.`id_due` = `d`.`id_due`) and (`d`.`id_servicio_social` = `e`.`id_servicio_social`) and (`c`.`id_tipo_estudiante` = 'PSS') and (`a`.`apertura_expediente_pss` is not null)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_estudiantes_con_exp_sin_pss`
--
DROP TABLE IF EXISTS `view_estudiantes_con_exp_sin_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_estudiantes_con_exp_sin_pss`  AS  select `a`.`id_due` AS `id_due`,`a`.`nombre` AS `nombre`,`a`.`apellido` AS `apellido`,`a`.`dui` AS `dui`,`a`.`email` AS `email`,`a`.`apertura_expediente_pss` AS `apertura_expediente_pss`,`a`.`carta_aptitud_pss` AS `carta_aptitud_pss` from ((`gen_estudiante` `a` join `es` `b`) join `gen_tipo_estudiante` `c`) where ((`a`.`id_due` = `b`.`id_due`) and (`b`.`id_tipo_estudiante` = `c`.`id_tipo_estudiante`) and (not(`a`.`id_due` in (select `pss_detalle_expediente`.`id_due` from `pss_detalle_expediente`))) and (`c`.`id_tipo_estudiante` = 'PSS') and (`a`.`apertura_expediente_pss` is not null)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_estudiantes_pdg`
--
DROP TABLE IF EXISTS `view_estudiantes_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_estudiantes_pdg`  AS  (select `ge`.`id_due` AS `id_due`,`ge`.`nombre` AS `nombre`,`ge`.`apellido` AS `apellido`,`ge`.`dui` AS `dui`,`ge`.`direccion` AS `direccion`,`ge`.`telefono` AS `telefono`,`ge`.`celular` AS `celular`,`ge`.`email` AS `email`,`ge`.`fecha_nac` AS `fecha_nac`,`ge`.`correlativo_equipo` AS `correlativo_equipo`,`ge`.`apertura_expediente` AS `apertura_expediente`,`ge`.`remision` AS `remision`,`ge`.`fecha_remision` AS `fecha_remision`,`ge`.`id_login` AS `id_login`,`ge`.`estado_tesis` AS `estado_tesis`,`ge`.`carta_aptitud_pss` AS `carta_aptitud_pss`,`ge`.`apertura_expediente_pss` AS `apertura_expediente_pss`,`ge`.`lugar_trabajo` AS `lugar_trabajo`,`ge`.`telefono_trabajo` AS `telefono_trabajo`,`ge`.`observaciones_exp_pss` AS `observaciones_exp_pss` from (`gen_estudiante` `ge` join `es` on(((`ge`.`id_due` = `es`.`id_due`) and (`es`.`id_tipo_estudiante` = 'PDG'))))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_estudiantes_pss`
--
DROP TABLE IF EXISTS `view_estudiantes_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_estudiantes_pss`  AS  (select `ge`.`id_due` AS `id_due`,`ge`.`nombre` AS `nombre`,`ge`.`apellido` AS `apellido`,`ge`.`dui` AS `dui`,`ge`.`direccion` AS `direccion`,`ge`.`telefono` AS `telefono`,`ge`.`celular` AS `celular`,`ge`.`email` AS `email`,`ge`.`fecha_nac` AS `fecha_nac`,`ge`.`correlativo_equipo` AS `correlativo_equipo`,`ge`.`apertura_expediente` AS `apertura_expediente`,`ge`.`remision` AS `remision`,`ge`.`fecha_remision` AS `fecha_remision`,`ge`.`id_login` AS `id_login`,`ge`.`estado_tesis` AS `estado_tesis`,`ge`.`carta_aptitud_pss` AS `carta_aptitud_pss`,`ge`.`apertura_expediente_pss` AS `apertura_expediente_pss`,`ge`.`lugar_trabajo` AS `lugar_trabajo`,`ge`.`telefono_trabajo` AS `telefono_trabajo`,`ge`.`observaciones_exp_pss` AS `observaciones_exp_pss` from (`gen_estudiante` `ge` join `es` on(((`ge`.`id_due` = `es`.`id_due`) and (`es`.`id_tipo_estudiante` = 'PSS'))))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_expedientes_remitidos_pss`
--
DROP TABLE IF EXISTS `view_expedientes_remitidos_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_expedientes_remitidos_pss`  AS  select `a`.`id_due` AS `id_due`,`b`.`remision` AS `remision`,`b`.`fecha_remision` AS `fecha_remision`,sum(`a`.`horas_prestadas`) AS `total_horas` from (`pss_detalle_expediente` `a` join `gen_estudiante` `b`) where ((`a`.`id_due` = `b`.`id_due`) and (`a`.`estado` = 'C') and (`b`.`remision` = 1)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_gen_oficializacion_pss`
--
DROP TABLE IF EXISTS `view_gen_oficializacion_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_gen_oficializacion_pss`  AS  select `pss_detalle_expediente`.`id_detalle_expediente` AS `id_detalle_expediente`,`pss_detalle_expediente`.`id_servicio_social` AS `id_servicio_social`,`pss_detalle_expediente`.`id_due` AS `id_due`,`pss_detalle_expediente`.`estado` AS `estado`,`pss_detalle_expediente`.`fecha_inicio` AS `fecha_inicio`,`pss_detalle_expediente`.`oficializacion` AS `oficializacion`,`pss_detalle_expediente`.`encabezado_oficializacion` AS `encabezado_oficializacion` from `pss_detalle_expediente` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_ingreso_documentacion_pss`
--
DROP TABLE IF EXISTS `view_ingreso_documentacion_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_ingreso_documentacion_pss`  AS  select `a`.`id_detalle_expediente` AS `id_detalle_expediente`,`a`.`id_servicio_social` AS `id_servicio_social`,`a`.`id_due` AS `id_due`,`a`.`horas_prestadas` AS `horas_prestadas`,`a`.`perfil_proyecto` AS `perfil_proyecto`,`a`.`plan_trabajo` AS `plan_trabajo`,`a`.`informe_parcial` AS `informe_parcial`,`a`.`informe_final` AS `informe_final`,`a`.`memoria` AS `memoria`,`a`.`control_actividades` AS `control_actividades`,`a`.`carta_finalizacion_horas_sociales` AS `carta_finalizacion_horas_sociales`,`b`.`lugar_trabajo` AS `lugar_trabajo`,`b`.`telefono_trabajo` AS `telefono_trabajo` from (`pss_detalle_expediente` `a` join `gen_estudiante` `b`) where (`a`.`id_due` = `b`.`id_due`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_ingre_tema_pdg`
--
DROP TABLE IF EXISTS `view_ingre_tema_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_ingre_tema_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`a`.`sigla` AS `sigla` from (`pdg_equipo_tg` `a` join `pdg_detalle` `b`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (isnull(`b`.`estado_perfil`) or (`b`.`estado_perfil` = 'O'))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_institucion`
--
DROP TABLE IF EXISTS `view_institucion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_institucion`  AS  select `func_inc_var_session`() AS `id`,`pss_institucion`.`id_institucion` AS `NIT`,`pss_institucion`.`nombre` AS `EMPRESA`,(select `pss_rubro`.`rubro` from `pss_rubro` where (`pss_institucion`.`id_rubro` = `pss_rubro`.`id_rubro`)) AS `RUBRO`,(case when (`pss_institucion`.`tipo` = 'PUB') then 'PUBLICA' when (`pss_institucion`.`tipo` = 'PRI') then 'PRIVADA' else '-' end) AS `TIPO`,(case when (`pss_institucion`.`estado` = 'APR') then 'APROBADA' when (`pss_institucion`.`tipo` = 'DES') then 'DESAPROBADA' else '-' end) AS `ESTADO`,`pss_institucion`.`direccion` AS `DIRECCION`,`pss_institucion`.`telefono` AS `TELEFONO`,`pss_institucion`.`email` AS `EMAIL` from `pss_institucion` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_perfil`
--
DROP TABLE IF EXISTS `view_perfil`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_perfil`  AS  select `func_inc_var_session`() AS `id`,`c`.`id_equipo_tg` AS `id_equipo_tg`,`c`.`tema` AS `tema`,`a`.`id_perfil` AS `id_perfil`,`a`.`id_detalle_pdg` AS `id_detalle_pdg`,`a`.`ciclo` AS `ciclo`,`a`.`anio` AS `anio`,`a`.`objetivo_general` AS `objetivo_general`,`a`.`objetivo_especifico` AS `objetivo_especifico`,`a`.`descripcion` AS `descripcion`,`d`.`ruta` AS `ruta` from (((`pdg_perfil` `a` join `pdg_detalle` `b`) join `pdg_equipo_tg` `c`) join `pdg_documento` `d`) where ((`a`.`id_detalle_pdg` = `b`.`id_detalle_pdg`) and (`b`.`id_equipo_tg` = `c`.`id_equipo_tg`) and (`b`.`id_detalle_pdg` = `d`.`id_detalle_pdg`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_perfil_pdg`
--
DROP TABLE IF EXISTS `view_perfil_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_perfil_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`c`.`id_perfil` AS `id_perfil`,`b`.`id_detalle_pdg` AS `id_detalle_pdg`,`c`.`ciclo` AS `ciclo_perfil`,`c`.`anio` AS `anio_perfil`,`b`.`observaciones_perfil` AS `observaciones_perfil`,`c`.`objetivo_general` AS `objetivo_general`,`c`.`objetivo_especifico` AS `objetivo_especifico`,`c`.`descripcion` AS `descripcion`,`c`.`area_tematica_tg` AS `area_tematica_tg`,`c`.`resultados_esperados_tg` AS `resultados_esperados_tg`,`d`.`ruta` AS `ruta` from ((((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_perfil` `c`) join `pdg_documento` `d`) join `pdg_tipo_documento` `e`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_detalle_pdg` = `d`.`id_detalle_pdg`) and (`d`.`id_tipo_documento_pdg` = `e`.`id_tipo_documento_pdg`) and (`e`.`siglas` = 'P') and (isnull(`b`.`estado_perfil`) or (`b`.`estado_perfil` = 'O'))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_pss_contacto`
--
DROP TABLE IF EXISTS `view_pss_contacto`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_pss_contacto`  AS  select `b`.`nombre` AS `INSTITUCION`,`a`.`id_contacto` AS `id_contacto`,`a`.`id_institucion` AS `id_institucion`,`a`.`nombre` AS `nombre`,`a`.`apellido` AS `apellido`,`a`.`descripcion_cargo` AS `descripcion_cargo`,`a`.`celular` AS `celular`,`a`.`telefono` AS `telefono`,`a`.`email` AS `email` from (`pss_contacto` `a` join `pss_institucion` `b`) where (`a`.`id_institucion` = `b`.`id_institucion`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_pss_servicio_social`
--
DROP TABLE IF EXISTS `view_pss_servicio_social`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_pss_servicio_social`  AS  select `a`.`id_servicio_social` AS `id_servicio_social`,`a`.`id_contacto` AS `id_contacto`,`a`.`id_modalidad` AS `id_modalidad`,`a`.`nombre_servicio_social` AS `nombre_servicio_social`,`a`.`cantidad_estudiante` AS `cantidad_estudiante`,`a`.`objetivo` AS `objetivo`,`a`.`importancia` AS `importancia`,`a`.`presupuesto` AS `presupuesto`,`a`.`logro` AS `logro`,`a`.`localidad_proyecto` AS `localidad_proyecto`,`a`.`beneficiario_directo` AS `beneficiario_directo`,`a`.`beneficiario_indirecto` AS `beneficiario_indirecto`,`a`.`descripcion` AS `descripcion`,`a`.`nombre_contacto_ss` AS `nombre_contacto_ss`,`a`.`email_contacto_ss` AS `email_contacto_ss`,`b`.`nombre` AS `nombre`,`b`.`apellido` AS `apellido` from (`pss_servicio_social` `a` join `pss_contacto` `b`) where (`a`.`id_contacto` = `b`.`id_contacto`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_ratificacion_notas_pdg`
--
DROP TABLE IF EXISTS `view_ratificacion_notas_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_ratificacion_notas_pdg`  AS  select `pdg_equipo_tg`.`id_equipo_tg` AS `id_equipo_tg`,`pdg_equipo_tg`.`anio_tg` AS `anio_tg`,`pdg_equipo_tg`.`tema` AS `tema`,`pdg_equipo_tg`.`sigla` AS `sigla`,`pdg_equipo_tg`.`ciclo_tg` AS `ciclo_tg` from `pdg_equipo_tg` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_recolector_notas_pdg`
--
DROP TABLE IF EXISTS `view_recolector_notas_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_recolector_notas_pdg`  AS  select `pdg_equipo_tg`.`id_equipo_tg` AS `id_equipo_tg`,`pdg_equipo_tg`.`anio_tg` AS `anio_tg`,`pdg_equipo_tg`.`tema` AS `tema`,`pdg_equipo_tg`.`sigla` AS `sigla`,`pdg_equipo_tg`.`ciclo_tg` AS `ciclo_tg` from `pdg_equipo_tg` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_regis_anteproy_pdg`
--
DROP TABLE IF EXISTS `view_regis_anteproy_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_regis_anteproy_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`b`.`id_detalle_pdg` AS `id_detalle_pdg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`b`.`fecha_eva_anteproyecto` AS `fecha_eva_anteproyecto`,`b`.`fecha_eva_etapa1` AS `fecha_eva_etapa1`,`b`.`fecha_eva_etapa2` AS `fecha_eva_etapa2`,`b`.`fecha_eva_publica` AS `fecha_eva_publica`,`c`.`ruta` AS `ruta` from (((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`d`.`siglas` = 'A')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_remision_notas_pdg`
--
DROP TABLE IF EXISTS `view_remision_notas_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_remision_notas_pdg`  AS  select `pdg_equipo_tg`.`id_equipo_tg` AS `id_equipo_tg`,`pdg_equipo_tg`.`anio_tg` AS `anio_tg`,`pdg_equipo_tg`.`tema` AS `tema`,`pdg_equipo_tg`.`sigla` AS `sigla`,`pdg_equipo_tg`.`ciclo_tg` AS `ciclo_tg` from `pdg_equipo_tg` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_rep_listado_docen_asesores_pdg`
--
DROP TABLE IF EXISTS `view_rep_listado_docen_asesores_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_rep_listado_docen_asesores_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tg`,`a`.`ciclo_tg` AS `ciclo_tg`,concat(`c`.`apellido`,', ',`c`.`nombre`) AS `NombreApellidoAlumno`,`a`.`id_due` AS `id_due`,`b`.`tema_tesis` AS `tema_tesis`,concat('Ing. ',`b`.`NombreApellidoDocente`) AS `NombreApellidoDocente` from ((`conforma` `a` join `asig_docente_aux_pdg` `b`) join `gen_estudiante` `c`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tesis`) and (`a`.`ciclo_tg` = `b`.`ciclo_tesis`) and (`a`.`id_due` = `c`.`id_due`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_rep_resumen_pss`
--
DROP TABLE IF EXISTS `view_rep_resumen_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_rep_resumen_pss`  AS  select `func_inc_var_session`() AS `id`,`a`.`id_detalle_expediente` AS `id_detalle_expediente`,`a`.`id_due` AS `id_due`,concat(`c`.`modalidad`,'-',`b`.`nombre_servicio_social`) AS `servicio_social`,`a`.`fecha_inicio` AS `fecha_inicio`,`a`.`fecha_fin` AS `fecha_fin`,`a`.`horas_prestadas` AS `horas_prestadas`,round((`a`.`horas_prestadas` * `a`.`costo_hora`),2) AS `monto`,`b`.`beneficiario_directo` AS `beneficiario_directo`,`b`.`beneficiario_indirecto` AS `beneficiario_indirecto`,(case when (`a`.`estado` = 'A') then 'Abandonado' when (`a`.`estado` = 'C') then 'Cerrado' end) AS `estado_case` from ((`pss_detalle_expediente` `a` join `pss_servicio_social` `b`) join `pss_modalidad` `c`) where ((`a`.`id_servicio_social` = `b`.`id_servicio_social`) and (`b`.`id_modalidad` = `c`.`id_modalidad`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_resumen_apro_dene_anteproy_pdg`
--
DROP TABLE IF EXISTS `view_resumen_apro_dene_anteproy_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_resumen_apro_dene_anteproy_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`b`.`id_detalle_pdg` AS `id_detalle_pdg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`b`.`estado_anteproyecto` AS `estado_anteproyecto`,`b`.`anteproy_ingresado_x_equipo` AS `anteproy_ingresado_x_equipo`,`b`.`entrega_copia_anteproy_doc_ase` AS `entrega_copia_anteproy_doc_ase`,`b`.`entrega_copia_anteproy_tribu_eva1` AS `entrega_copia_anteproy_tribu_eva1`,`b`.`entrega_copia_anteproy_tribu_eva2` AS `entrega_copia_anteproy_tribu_eva2`,`c`.`ruta` AS `ruta`,`d`.`siglas` AS `siglas` from (((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`d`.`siglas` in ('MAA','MDA'))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_resum_apro_dene_cambio_nombre_pdg`
--
DROP TABLE IF EXISTS `view_resum_apro_dene_cambio_nombre_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_resum_apro_dene_cambio_nombre_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`e`.`id_solicitud_academica` AS `id_solicitud_academica`,`e`.`ciclo` AS `ciclo`,`e`.`anio` AS `anio`,`e`.`ingresado_x_equipo` AS `ingresado_x_equipo`,`e`.`generado_x_coordinador_pdg` AS `generado_x_coordinador_pdg`,`e`.`estado` AS `estado`,`c`.`ruta` AS `ruta`,`d`.`siglas` AS `siglas` from ((((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) join `pdg_solicitud_academica` `e`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`d`.`siglas` in ('MACN','MDCN')) and (`b`.`id_detalle_pdg` = `e`.`id_detalle_pdg`) and (`c`.`id_documento_pdg` = `e`.`id_documento_pdg`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_resum_apro_dene_prorro_pdg`
--
DROP TABLE IF EXISTS `view_resum_apro_dene_prorro_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_resum_apro_dene_prorro_pdg`  AS  select distinct `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`e`.`id_solicitud_academica` AS `id_solicitud_academica`,`e`.`ciclo` AS `ciclo`,`e`.`anio` AS `anio`,`e`.`ingresado_x_equipo` AS `ingresado_x_equipo`,`e`.`generado_x_coordinador_pdg` AS `generado_x_coordinador_pdg`,`e`.`estado` AS `estado`,`c`.`ruta` AS `ruta`,`d`.`siglas` AS `siglas` from ((((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) join `pdg_solicitud_academica` `e`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`d`.`siglas` in ('MAPR','MDPR')) and (`b`.`id_detalle_pdg` = `e`.`id_detalle_pdg`) and (`e`.`id_documento_pdg` = `c`.`id_documento_pdg`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_resum_subir_doc_pdg`
--
DROP TABLE IF EXISTS `view_resum_subir_doc_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_resum_subir_doc_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`b`.`id_detalle_pdg` AS `id_detalle_pdg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`d`.`id_tipo_documento_pdg` AS `id_tipo_documento_pdg`,`d`.`descripcion` AS `descripcion`,`c`.`ruta` AS `ruta` from (((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`d`.`siglas` in ('E1','E2','DP'))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_servicio_social_pss`
--
DROP TABLE IF EXISTS `view_servicio_social_pss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_servicio_social_pss`  AS  select `pss_servicio_social`.`id_servicio_social` AS `id_servicio_social`,`pss_servicio_social`.`id_contacto` AS `id_contacto`,`pss_servicio_social`.`id_modalidad` AS `id_modalidad`,`pss_servicio_social`.`nombre_servicio_social` AS `nombre_servicio_social`,`pss_servicio_social`.`estado_aprobacion` AS `estado_aprobacion`,`pss_servicio_social`.`cantidad_estudiante` AS `cantidad_estudiante`,`pss_servicio_social`.`disponibilidad` AS `disponibilidad`,`pss_servicio_social`.`objetivo` AS `objetivo`,`pss_servicio_social`.`importancia` AS `importancia`,`pss_servicio_social`.`presupuesto` AS `presupuesto`,`pss_servicio_social`.`logro` AS `logro`,`pss_servicio_social`.`localidad_proyecto` AS `localidad_proyecto`,`pss_servicio_social`.`beneficiario_directo` AS `beneficiario_directo`,`pss_servicio_social`.`beneficiario_indirecto` AS `beneficiario_indirecto`,`pss_servicio_social`.`descripcion` AS `descripcion`,`pss_servicio_social`.`nombre_contacto_ss` AS `nombre_contacto_ss`,`pss_servicio_social`.`email_contacto_ss` AS `email_contacto_ss`,`pss_servicio_social`.`estado` AS `estado` from `pss_servicio_social` where (`pss_servicio_social`.`estado_aprobacion` = 'A') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_soli_cambio_nombre_pdg`
--
DROP TABLE IF EXISTS `view_soli_cambio_nombre_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_soli_cambio_nombre_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`c`.`id_solicitud_academica` AS `id_solicitud_academica`,`c`.`ciclo` AS `ciclo`,`c`.`anio` AS `anio`,`c`.`tipo_solicitud` AS `tipo_solicitud`,`c`.`estado` AS `estado`,`c`.`acuerdo_junta` AS `acuerdo_junta`,`c`.`nombre_actual` AS `nombre_actual`,`c`.`nombre_propuesto` AS `nombre_propuesto`,`c`.`justificacion` AS `justificacion` from ((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_solicitud_academica` `c`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`tipo_solicitud` = 'sol_modi_nombre_tg')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_soli_prorro_pdg`
--
DROP TABLE IF EXISTS `view_soli_prorro_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_soli_prorro_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`c`.`id_solicitud_academica` AS `id_solicitud_academica`,`c`.`ciclo` AS `ciclo`,`c`.`anio` AS `anio`,`c`.`tipo_solicitud` AS `tipo_solicitud`,`c`.`estado` AS `estado`,`c`.`fecha_solicitud` AS `fecha_solicitud`,`c`.`fecha_ini_prorroga` AS `fecha_ini_prorroga`,`c`.`fecha_fin_prorroga` AS `fecha_fin_prorroga`,round(`c`.`duracion`,0) AS `duracion`,`c`.`eva_antes_prorroga` AS `eva_antes_prorroga`,`c`.`eva_actual` AS `eva_actual`,`c`.`cantidad_evaluacion_actual` AS `cantidad_evaluacion_actual`,`c`.`justificacion` AS `justificacion`,`c`.`caso_especial` AS `caso_especial` from ((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_solicitud_academica` `c`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`tipo_solicitud` = 'sol_prorroga_tg')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_subir_doc_pdg`
--
DROP TABLE IF EXISTS `view_subir_doc_pdg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_subir_doc_pdg`  AS  select `a`.`id_equipo_tg` AS `id_equipo_tg`,`b`.`id_detalle_pdg` AS `id_detalle_pdg`,`a`.`anio_tg` AS `anio_tesis`,`a`.`ciclo_tg` AS `ciclo_tesis`,`a`.`tema` AS `tema_tesis`,`d`.`id_tipo_documento_pdg` AS `id_tipo_documento_pdg`,`d`.`descripcion` AS `descripcion`,`c`.`ruta` AS `ruta` from (((`pdg_equipo_tg` `a` join `pdg_detalle` `b`) join `pdg_documento` `c`) join `pdg_tipo_documento` `d`) where ((`a`.`id_equipo_tg` = `b`.`id_equipo_tg`) and (`a`.`anio_tg` = `b`.`anio_tg`) and (`a`.`ciclo_tg` = `b`.`ciclo_tg`) and (`b`.`id_detalle_pdg` = `c`.`id_detalle_pdg`) and (`c`.`id_tipo_documento_pdg` = `d`.`id_tipo_documento_pdg`) and (`d`.`siglas` in ('E1','E2','DP')) and (`a`.`id_equipo_tg`,`a`.`anio_tg`,`a`.`ciclo_tg`) in (select `cnp`.`id_equipo_tg`,`cnp`.`anio_tg`,`cnp`.`ciclo_tg` from `cierre_notas_pdg` `cnp` where ((`a`.`id_equipo_tg` = `cnp`.`id_equipo_tg`) and (`a`.`anio_tg` = `cnp`.`anio_tg`) and (`a`.`ciclo_tg` = `cnp`.`ciclo_tg`) and (substr(`cnp`.`etapa_evaluativa`,3) = convert(`d`.`descripcion` using utf8)) and (`cnp`.`estado_nota` <> 'C')))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_tipo_estudiante`
--
DROP TABLE IF EXISTS `view_tipo_estudiante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sigpanet`@`%` SQL SECURITY DEFINER VIEW `view_tipo_estudiante`  AS  (select sum((case `es`.`id_tipo_estudiante` when 'PDG' then 1 when 'PSS' then 2 when 'PERA' then 4 end)) AS `tipo`,`es`.`id_due` AS `id_due` from `es` group by `es`.`id_due`) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignado`
--
ALTER TABLE `asignado`
  ADD PRIMARY KEY (`id_due`,`id_docente`,`id_proceso`,`id_cargo`,`correlativo_tutor_ss`),
  ADD KEY `fk_asignado2` (`id_docente`);

--
-- Indices de la tabla `asig_docente_aux_pdg`
--
ALTER TABLE `asig_docente_aux_pdg`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asig_docente_aux_pss`
--
ALTER TABLE `asig_docente_aux_pss`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asig_tribunal_aux_pdg`
--
ALTER TABLE `asig_tribunal_aux_pdg`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aux1_contacto_x_institucion_pss_tmp`
--
ALTER TABLE `aux1_contacto_x_institucion_pss_tmp`
  ADD PRIMARY KEY (`id_institucion`,`id_contacto`);

--
-- Indices de la tabla `aux1_docente_pdg_tmp`
--
ALTER TABLE `aux1_docente_pdg_tmp`
  ADD PRIMARY KEY (`id_docente`);

--
-- Indices de la tabla `aux1_docente_pss_tmp`
--
ALTER TABLE `aux1_docente_pss_tmp`
  ADD PRIMARY KEY (`id_docente`);

--
-- Indices de la tabla `aux2_docente_pdg_tmp`
--
ALTER TABLE `aux2_docente_pdg_tmp`
  ADD PRIMARY KEY (`id_docente`);

--
-- Indices de la tabla `aux_candidatos_para_aperturar_tmp_pss`
--
ALTER TABLE `aux_candidatos_para_aperturar_tmp_pss`
  ADD PRIMARY KEY (`id_due`);

--
-- Indices de la tabla `aux_estudiantes_con_exp_con_tmp_pss`
--
ALTER TABLE `aux_estudiantes_con_exp_con_tmp_pss`
  ADD PRIMARY KEY (`id_detalle_expediente`);

--
-- Indices de la tabla `aux_estudiantes_con_exp_sin_tmp_pss`
--
ALTER TABLE `aux_estudiantes_con_exp_sin_tmp_pss`
  ADD PRIMARY KEY (`id_due`);

--
-- Indices de la tabla `aux_servicio_social_pss_tmp`
--
ALTER TABLE `aux_servicio_social_pss_tmp`
  ADD PRIMARY KEY (`id_servicio_social`);

--
-- Indices de la tabla `cartas_renuncia_tmp_pss`
--
ALTER TABLE `cartas_renuncia_tmp_pss`
  ADD PRIMARY KEY (`id_detalle_expediente`);

--
-- Indices de la tabla `cat_parametro_general`
--
ALTER TABLE `cat_parametro_general`
  ADD PRIMARY KEY (`parametro`);

--
-- Indices de la tabla `cat_proceso`
--
ALTER TABLE `cat_proceso`
  ADD PRIMARY KEY (`id_proceso`);

--
-- Indices de la tabla `cierre_expediente_tmp_pss`
--
ALTER TABLE `cierre_expediente_tmp_pss`
  ADD PRIMARY KEY (`id_due`);

--
-- Indices de la tabla `cierre_notas_pdg`
--
ALTER TABLE `cierre_notas_pdg`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `conforma`
--
ALTER TABLE `conforma`
  ADD PRIMARY KEY (`id_equipo_tg`,`anio_tg`,`id_due`,`ciclo_tg`),
  ADD KEY `fk_conforma2` (`id_due`),
  ADD KEY `fk_conforma` (`id_equipo_tg`,`anio_tg`,`ciclo_tg`);

--
-- Indices de la tabla `es`
--
ALTER TABLE `es`
  ADD PRIMARY KEY (`id_tipo_estudiante`,`id_due`),
  ADD KEY `fk_es2` (`id_due`);

--
-- Indices de la tabla `expedientes_remitidos_tmp_pss`
--
ALTER TABLE `expedientes_remitidos_tmp_pss`
  ADD PRIMARY KEY (`id_due`);

--
-- Indices de la tabla `gen_cargo`
--
ALTER TABLE `gen_cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `gen_cargo_administrativo`
--
ALTER TABLE `gen_cargo_administrativo`
  ADD PRIMARY KEY (`id_cargo_administrativo`);

--
-- Indices de la tabla `gen_departamento`
--
ALTER TABLE `gen_departamento`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `gen_docente`
--
ALTER TABLE `gen_docente`
  ADD PRIMARY KEY (`id_docente`),
  ADD KEY `fk_desempenia` (`id_cargo`),
  ADD KEY `fk_desempenia_2` (`id_cargo_administrativo`),
  ADD KEY `fk_pertenece_5` (`id_departamento`),
  ADD KEY `FK_gen_docente` (`id_login`);

--
-- Indices de la tabla `gen_estudiante`
--
ALTER TABLE `gen_estudiante`
  ADD PRIMARY KEY (`id_due`);

--
-- Indices de la tabla `gen_materia`
--
ALTER TABLE `gen_materia`
  ADD PRIMARY KEY (`id_materia`),
  ADD KEY `fk_coordina` (`id_docente`),
  ADD KEY `fk_materia_departamento` (`id_departamento`);

--
-- Indices de la tabla `gen_oficializacion_tmp_pss`
--
ALTER TABLE `gen_oficializacion_tmp_pss`
  ADD PRIMARY KEY (`id_detalle_expediente`);

--
-- Indices de la tabla `gen_perfil`
--
ALTER TABLE `gen_perfil`
  ADD PRIMARY KEY (`id_perfil_usuario`);

--
-- Indices de la tabla `gen_tipo_estudiante`
--
ALTER TABLE `gen_tipo_estudiante`
  ADD PRIMARY KEY (`id_tipo_estudiante`);

--
-- Indices de la tabla `gen_tipo_usuario`
--
ALTER TABLE `gen_tipo_usuario`
  ADD PRIMARY KEY (`id_tipo_usuario`);

--
-- Indices de la tabla `gen_usuario`
--
ALTER TABLE `gen_usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_concierne` (`id_perfil_usuario`),
  ADD KEY `fk_se_asocia` (`id_tipo_usuario`);

--
-- Indices de la tabla `ingreso_documentacion_pss_tmp`
--
ALTER TABLE `ingreso_documentacion_pss_tmp`
  ADD PRIMARY KEY (`id_detalle_expediente`);

--
-- Indices de la tabla `pdg_apro_dene_perfil_temp`
--
ALTER TABLE `pdg_apro_dene_perfil_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pdg_bitacora_control`
--
ALTER TABLE `pdg_bitacora_control`
  ADD PRIMARY KEY (`id_bitacora`),
  ADD KEY `fk_llena` (`id_detalle_pdg`);

--
-- Indices de la tabla `pdg_consolidado_notas`
--
ALTER TABLE `pdg_consolidado_notas`
  ADD PRIMARY KEY (`id_consolidado_notas`),
  ADD KEY `FK_pdg_consolidado_notas` (`id_equipo_tg`,`anio_tg`,`id_due`);

--
-- Indices de la tabla `pdg_criterio`
--
ALTER TABLE `pdg_criterio`
  ADD PRIMARY KEY (`id_criterio`);

--
-- Indices de la tabla `pdg_detalle`
--
ALTER TABLE `pdg_detalle`
  ADD PRIMARY KEY (`id_detalle_pdg`),
  ADD KEY `FK_pdg_detalle` (`id_equipo_tg`,`anio_tg`,`ciclo_tg`);

--
-- Indices de la tabla `pdg_deta_nota`
--
ALTER TABLE `pdg_deta_nota`
  ADD PRIMARY KEY (`id_deta_nota`),
  ADD KEY `fk_tiene_2` (`id_due`);

--
-- Indices de la tabla `pdg_documento`
--
ALTER TABLE `pdg_documento`
  ADD PRIMARY KEY (`id_documento_pdg`),
  ADD KEY `fk_anexa_2` (`id_detalle_pdg`),
  ADD KEY `fk_pertenece_4` (`id_tipo_documento_pdg`);

--
-- Indices de la tabla `pdg_equipo_tg`
--
ALTER TABLE `pdg_equipo_tg`
  ADD PRIMARY KEY (`id_equipo_tg`,`anio_tg`,`ciclo_tg`);

--
-- Indices de la tabla `pdg_ingre_tema_temp`
--
ALTER TABLE `pdg_ingre_tema_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pdg_nota_anteproyecto`
--
ALTER TABLE `pdg_nota_anteproyecto`
  ADD PRIMARY KEY (`id_nota_anteproyecto`),
  ADD KEY `FK_pdg_nota_anteproyecto` (`id_equipo_tg`,`anio_tg`);

--
-- Indices de la tabla `pdg_nota_defensa_publica`
--
ALTER TABLE `pdg_nota_defensa_publica`
  ADD PRIMARY KEY (`id_nota_defensa_publica`),
  ADD KEY `FK_pdg_nota_defensa_publica` (`id_equipo_tg`,`anio_tg`,`id_due`);

--
-- Indices de la tabla `pdg_nota_etapa1`
--
ALTER TABLE `pdg_nota_etapa1`
  ADD PRIMARY KEY (`id_nota_etapa1`),
  ADD KEY `FK_pdg_nota_etapa1` (`id_equipo_tg`,`anio_tg`,`id_due`);

--
-- Indices de la tabla `pdg_nota_etapa2`
--
ALTER TABLE `pdg_nota_etapa2`
  ADD PRIMARY KEY (`id_nota_etapa2`),
  ADD KEY `FK_pdg_nota_etapa2` (`id_equipo_tg`,`anio_tg`,`id_due`);

--
-- Indices de la tabla `pdg_perfil`
--
ALTER TABLE `pdg_perfil`
  ADD PRIMARY KEY (`id_perfil`),
  ADD KEY `fk_se_ingresa` (`id_detalle_pdg`);

--
-- Indices de la tabla `pdg_ratificacion_notas_temp`
--
ALTER TABLE `pdg_ratificacion_notas_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pdg_recolector_notas_temp`
--
ALTER TABLE `pdg_recolector_notas_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pdg_remision_notas_temp`
--
ALTER TABLE `pdg_remision_notas_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pdg_resum_subir_doc_temp`
--
ALTER TABLE `pdg_resum_subir_doc_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pdg_solicitud_academica`
--
ALTER TABLE `pdg_solicitud_academica`
  ADD PRIMARY KEY (`id_solicitud_academica`),
  ADD KEY `fk_solicita` (`id_detalle_pdg`);

--
-- Indices de la tabla `pdg_subir_doc_temp`
--
ALTER TABLE `pdg_subir_doc_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pdg_tipo_documento`
--
ALTER TABLE `pdg_tipo_documento`
  ADD PRIMARY KEY (`id_tipo_documento_pdg`);

--
-- Indices de la tabla `per_area_deficitaria`
--
ALTER TABLE `per_area_deficitaria`
  ADD PRIMARY KEY (`id_detalle_pera`,`id_materia`),
  ADD KEY `fk_materia_area_deficitaria` (`id_materia`);

--
-- Indices de la tabla `per_detalle`
--
ALTER TABLE `per_detalle`
  ADD PRIMARY KEY (`id_detalle_pera`),
  ADD KEY `fk_posee` (`id_due`);

--
-- Indices de la tabla `per_evaluacion`
--
ALTER TABLE `per_evaluacion`
  ADD PRIMARY KEY (`id_evaluacion`),
  ADD KEY `fk_tiene` (`id_tipo_pera`);

--
-- Indices de la tabla `per_registro_nota`
--
ALTER TABLE `per_registro_nota`
  ADD PRIMARY KEY (`id_registro_nota`),
  ADD KEY `fk_detalle_registro_nota` (`id_detalle_pera`);

--
-- Indices de la tabla `per_tipo`
--
ALTER TABLE `per_tipo`
  ADD PRIMARY KEY (`id_tipo_pera`),
  ADD KEY `fk_contiene` (`id_detalle_pera`);

--
-- Indices de la tabla `pss_contacto`
--
ALTER TABLE `pss_contacto`
  ADD PRIMARY KEY (`id_contacto`),
  ADD KEY `fk_representa` (`id_institucion`),
  ADD KEY `FK_pss_contacto` (`id_login`);

--
-- Indices de la tabla `pss_detalle_expediente`
--
ALTER TABLE `pss_detalle_expediente`
  ADD PRIMARY KEY (`id_detalle_expediente`),
  ADD KEY `fk_contiene_2` (`id_servicio_social`),
  ADD KEY `fk_posee_3` (`id_due`);

--
-- Indices de la tabla `pss_documento`
--
ALTER TABLE `pss_documento`
  ADD PRIMARY KEY (`id_documento_pss`),
  ADD KEY `fk_anexa` (`id_detalle_expediente`),
  ADD KEY `fk_pertenece_2` (`id_tipo_documento_pss`);

--
-- Indices de la tabla `pss_institucion`
--
ALTER TABLE `pss_institucion`
  ADD PRIMARY KEY (`id_institucion`),
  ADD KEY `fk_pertenece` (`id_rubro`);

--
-- Indices de la tabla `pss_modalidad`
--
ALTER TABLE `pss_modalidad`
  ADD PRIMARY KEY (`id_modalidad`);

--
-- Indices de la tabla `pss_rubro`
--
ALTER TABLE `pss_rubro`
  ADD PRIMARY KEY (`id_rubro`);

--
-- Indices de la tabla `pss_servicio_social`
--
ALTER TABLE `pss_servicio_social`
  ADD PRIMARY KEY (`id_servicio_social`),
  ADD KEY `fk_alimenta` (`id_contacto`),
  ADD KEY `fk_posee_2` (`id_modalidad`);

--
-- Indices de la tabla `pss_tipo_documento`
--
ALTER TABLE `pss_tipo_documento`
  ADD PRIMARY KEY (`id_tipo_documento_pss`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `per_detalle`
--
ALTER TABLE `per_detalle`
  MODIFY `id_detalle_pera` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `per_evaluacion`
--
ALTER TABLE `per_evaluacion`
  MODIFY `id_evaluacion` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `per_registro_nota`
--
ALTER TABLE `per_registro_nota`
  MODIFY `id_registro_nota` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `per_tipo`
--
ALTER TABLE `per_tipo`
  MODIFY `id_tipo_pera` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignado`
--
ALTER TABLE `asignado`
  ADD CONSTRAINT `fk_asignado` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`),
  ADD CONSTRAINT `fk_asignado2` FOREIGN KEY (`id_docente`) REFERENCES `gen_docente` (`id_docente`);

--
-- Filtros para la tabla `conforma`
--
ALTER TABLE `conforma`
  ADD CONSTRAINT `fk_conforma` FOREIGN KEY (`id_equipo_tg`,`anio_tg`,`ciclo_tg`) REFERENCES `pdg_equipo_tg` (`id_equipo_tg`, `anio_tg`, `ciclo_tg`),
  ADD CONSTRAINT `fk_conforma2` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`);

--
-- Filtros para la tabla `es`
--
ALTER TABLE `es`
  ADD CONSTRAINT `fk_es` FOREIGN KEY (`id_tipo_estudiante`) REFERENCES `gen_tipo_estudiante` (`id_tipo_estudiante`),
  ADD CONSTRAINT `fk_es2` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`);

--
-- Filtros para la tabla `gen_docente`
--
ALTER TABLE `gen_docente`
  ADD CONSTRAINT `FK_gen_docente` FOREIGN KEY (`id_login`) REFERENCES `gen_usuario` (`id_usuario`),
  ADD CONSTRAINT `fk_desempenia` FOREIGN KEY (`id_cargo`) REFERENCES `gen_cargo` (`id_cargo`),
  ADD CONSTRAINT `fk_desempenia_2` FOREIGN KEY (`id_cargo_administrativo`) REFERENCES `gen_cargo_administrativo` (`id_cargo_administrativo`),
  ADD CONSTRAINT `fk_pertenece_5` FOREIGN KEY (`id_departamento`) REFERENCES `gen_departamento` (`id_departamento`);

--
-- Filtros para la tabla `gen_materia`
--
ALTER TABLE `gen_materia`
  ADD CONSTRAINT `fk_coordina` FOREIGN KEY (`id_docente`) REFERENCES `gen_docente` (`id_docente`),
  ADD CONSTRAINT `fk_materia_departamento` FOREIGN KEY (`id_departamento`) REFERENCES `gen_departamento` (`id_departamento`);

--
-- Filtros para la tabla `gen_usuario`
--
ALTER TABLE `gen_usuario`
  ADD CONSTRAINT `fk_concierne` FOREIGN KEY (`id_perfil_usuario`) REFERENCES `gen_perfil` (`id_perfil_usuario`),
  ADD CONSTRAINT `fk_se_asocia` FOREIGN KEY (`id_tipo_usuario`) REFERENCES `gen_tipo_usuario` (`id_tipo_usuario`);

--
-- Filtros para la tabla `pdg_bitacora_control`
--
ALTER TABLE `pdg_bitacora_control`
  ADD CONSTRAINT `fk_llena` FOREIGN KEY (`id_detalle_pdg`) REFERENCES `pdg_detalle` (`id_detalle_pdg`);

--
-- Filtros para la tabla `pdg_consolidado_notas`
--
ALTER TABLE `pdg_consolidado_notas`
  ADD CONSTRAINT `FK_pdg_consolidado_notas` FOREIGN KEY (`id_equipo_tg`,`anio_tg`,`id_due`) REFERENCES `conforma` (`id_equipo_tg`, `anio_tg`, `id_due`);

--
-- Filtros para la tabla `pdg_detalle`
--
ALTER TABLE `pdg_detalle`
  ADD CONSTRAINT `FK_pdg_detalle` FOREIGN KEY (`id_equipo_tg`,`anio_tg`,`ciclo_tg`) REFERENCES `pdg_equipo_tg` (`id_equipo_tg`, `anio_tg`, `ciclo_tg`);

--
-- Filtros para la tabla `pdg_deta_nota`
--
ALTER TABLE `pdg_deta_nota`
  ADD CONSTRAINT `fk_tiene_2` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`);

--
-- Filtros para la tabla `pdg_documento`
--
ALTER TABLE `pdg_documento`
  ADD CONSTRAINT `fk_anexa_2` FOREIGN KEY (`id_detalle_pdg`) REFERENCES `pdg_detalle` (`id_detalle_pdg`),
  ADD CONSTRAINT `fk_pertenece_4` FOREIGN KEY (`id_tipo_documento_pdg`) REFERENCES `pdg_tipo_documento` (`id_tipo_documento_pdg`);

--
-- Filtros para la tabla `pdg_nota_anteproyecto`
--
ALTER TABLE `pdg_nota_anteproyecto`
  ADD CONSTRAINT `FK_pdg_nota_anteproyecto` FOREIGN KEY (`id_equipo_tg`,`anio_tg`) REFERENCES `pdg_equipo_tg` (`id_equipo_tg`, `anio_tg`);

--
-- Filtros para la tabla `pdg_nota_defensa_publica`
--
ALTER TABLE `pdg_nota_defensa_publica`
  ADD CONSTRAINT `FK_pdg_nota_defensa_publica` FOREIGN KEY (`id_equipo_tg`,`anio_tg`,`id_due`) REFERENCES `conforma` (`id_equipo_tg`, `anio_tg`, `id_due`);

--
-- Filtros para la tabla `pdg_nota_etapa1`
--
ALTER TABLE `pdg_nota_etapa1`
  ADD CONSTRAINT `FK_pdg_nota_etapa1` FOREIGN KEY (`id_equipo_tg`,`anio_tg`,`id_due`) REFERENCES `conforma` (`id_equipo_tg`, `anio_tg`, `id_due`);

--
-- Filtros para la tabla `pdg_nota_etapa2`
--
ALTER TABLE `pdg_nota_etapa2`
  ADD CONSTRAINT `FK_pdg_nota_etapa2` FOREIGN KEY (`id_equipo_tg`,`anio_tg`,`id_due`) REFERENCES `conforma` (`id_equipo_tg`, `anio_tg`, `id_due`);

--
-- Filtros para la tabla `pdg_perfil`
--
ALTER TABLE `pdg_perfil`
  ADD CONSTRAINT `fk_se_ingresa` FOREIGN KEY (`id_detalle_pdg`) REFERENCES `pdg_detalle` (`id_detalle_pdg`);

--
-- Filtros para la tabla `pdg_solicitud_academica`
--
ALTER TABLE `pdg_solicitud_academica`
  ADD CONSTRAINT `fk_solicita` FOREIGN KEY (`id_detalle_pdg`) REFERENCES `pdg_detalle` (`id_detalle_pdg`);

--
-- Filtros para la tabla `per_area_deficitaria`
--
ALTER TABLE `per_area_deficitaria`
  ADD CONSTRAINT `fk_detalle_area_deficitaria` FOREIGN KEY (`id_detalle_pera`) REFERENCES `per_detalle` (`id_detalle_pera`),
  ADD CONSTRAINT `fk_materia_area_deficitaria` FOREIGN KEY (`id_materia`) REFERENCES `gen_materia` (`id_materia`);

--
-- Filtros para la tabla `per_detalle`
--
ALTER TABLE `per_detalle`
  ADD CONSTRAINT `fk_posee` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`);

--
-- Filtros para la tabla `per_evaluacion`
--
ALTER TABLE `per_evaluacion`
  ADD CONSTRAINT `fk_tiene` FOREIGN KEY (`id_tipo_pera`) REFERENCES `per_tipo` (`id_tipo_pera`);

--
-- Filtros para la tabla `per_registro_nota`
--
ALTER TABLE `per_registro_nota`
  ADD CONSTRAINT `fk_detalle_registro_nota` FOREIGN KEY (`id_detalle_pera`) REFERENCES `per_detalle` (`id_detalle_pera`);

--
-- Filtros para la tabla `per_tipo`
--
ALTER TABLE `per_tipo`
  ADD CONSTRAINT `fk_contiene` FOREIGN KEY (`id_detalle_pera`) REFERENCES `per_detalle` (`id_detalle_pera`);

--
-- Filtros para la tabla `pss_contacto`
--
ALTER TABLE `pss_contacto`
  ADD CONSTRAINT `FK_pss_contacto` FOREIGN KEY (`id_login`) REFERENCES `gen_usuario` (`id_usuario`),
  ADD CONSTRAINT `fk_representa` FOREIGN KEY (`id_institucion`) REFERENCES `pss_institucion` (`id_institucion`);

--
-- Filtros para la tabla `pss_detalle_expediente`
--
ALTER TABLE `pss_detalle_expediente`
  ADD CONSTRAINT `fk_contiene_2` FOREIGN KEY (`id_servicio_social`) REFERENCES `pss_servicio_social` (`id_servicio_social`),
  ADD CONSTRAINT `fk_posee_3` FOREIGN KEY (`id_due`) REFERENCES `gen_estudiante` (`id_due`);

--
-- Filtros para la tabla `pss_documento`
--
ALTER TABLE `pss_documento`
  ADD CONSTRAINT `fk_anexa` FOREIGN KEY (`id_detalle_expediente`) REFERENCES `pss_detalle_expediente` (`id_detalle_expediente`),
  ADD CONSTRAINT `fk_pertenece_2` FOREIGN KEY (`id_tipo_documento_pss`) REFERENCES `pss_tipo_documento` (`id_tipo_documento_pss`);

--
-- Filtros para la tabla `pss_institucion`
--
ALTER TABLE `pss_institucion`
  ADD CONSTRAINT `fk_pertenece` FOREIGN KEY (`id_rubro`) REFERENCES `pss_rubro` (`id_rubro`);

--
-- Filtros para la tabla `pss_servicio_social`
--
ALTER TABLE `pss_servicio_social`
  ADD CONSTRAINT `fk_alimenta` FOREIGN KEY (`id_contacto`) REFERENCES `pss_contacto` (`id_contacto`),
  ADD CONSTRAINT `fk_posee_2` FOREIGN KEY (`id_modalidad`) REFERENCES `pss_modalidad` (`id_modalidad`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
