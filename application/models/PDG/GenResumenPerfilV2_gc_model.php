<?php 
class GenResumenPerfilV2_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function ObtenerIdPerfil($datos)
	    {
	        $sql="SELECT id_perfil FROM pdg_apro_dene_perfil_temp
					WHERE id='".$datos['id']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $id_perfil=$row['id_perfil'];

	        } 		        
		        return $id_perfil;
	    } 

	function ObtenerCantidadAlumnosXEquipo($datos)
	    {
	        $sql="SELECT COUNT(*) cantidad_alumnos FROM conforma
				  WHERE (id_equipo_tg,anio_tg,ciclo_tg) IN (
				  SELECT id_equipo_tg,anio_tesis,ciclo_tesis FROM pdg_apro_dene_perfil_temp
				  WHERE id='".$datos['id']."')";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $cantidad_alumnos=$row['cantidad_alumnos'];

	        } 		        
		        return $cantidad_alumnos;
	    } 


	function ObtenerCantidadAsesores($datos)
	    {
			$sql="SELECT COUNT(DISTINCT id_cargo) cantidad_asesores FROM asignado
				WHERE id_proceso='PDG'
				AND id_due IN (
				SELECT id_due FROM conforma
				WHERE (id_equipo_tg,anio_tg,ciclo_tg) IN (
				SELECT id_equipo_tg,anio_tesis,ciclo_tesis FROM pdg_apro_dene_perfil_temp
				WHERE id='".$datos['id']."'))";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $cantidad_asesores=$row['cantidad_asesores'];

	        } 		        
		        return $cantidad_asesores;
	    } 
	function ObtenerEstadoPerfil($datos)
	    {
	        $sql="SELECT estado_perfil FROM pdg_apro_dene_perfil_temp
					WHERE id='".$datos['id']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $estado_perfil=$row['estado_perfil'];

	        } 		        
		        return $estado_perfil;
	    } 

	function ObtenerActaN($datos)
	    {
	        $sql="SELECT numero_acta_perfil FROM pdg_apro_dene_perfil_temp
				  WHERE id='".$datos['id']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $numero_acta_perfil=$row['numero_acta_perfil'];

	        } 		        
		        if(is_null($numero_acta_perfil)){
		        	$numero_acta_perfil='__________';
		        }	 	        
		        return $numero_acta_perfil;
	    } 

	function ObtenerPunto($datos)
	    {
	        $sql="SELECT punto_perfil FROM pdg_apro_dene_perfil_temp
				  WHERE id='".$datos['id']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $punto_perfil=$row['punto_perfil'];

	        } 		
		        if(is_null($punto_perfil)){
		        	$punto_perfil='_______';
		        }	                
		        return $punto_perfil;
	    } 

	function ObtenerAcuerdo($datos)
	    {
	        $sql="SELECT acuerdo_perfil FROM pdg_apro_dene_perfil_temp
				  WHERE id='".$datos['id']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $acuerdo_perfil=$row['acuerdo_perfil'];

	        } 		        
		        if(is_null($acuerdo_perfil)){
		        	$acuerdo_perfil='_____________';
		        }
		        return $acuerdo_perfil;
	    } 

	function ObtenerFechaAprobacion($datos)
	    {
	        $sql="SELECT fecha_aprobacion_perfil FROM pdg_apro_dene_perfil_temp
				  WHERE id='".$datos['id']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $fecha_aprobacion_perfil=$row['fecha_aprobacion_perfil'];

	        } 		 
		        if(is_null($fecha_aprobacion_perfil)){
		        	$fecha_aprobacion_perfil='______________';
		        }	               
		        return $fecha_aprobacion_perfil;
	    } 

	function ObtenerTema($datos)
	    {
	        $sql="SELECT tema_tesis FROM pdg_apro_dene_perfil_temp
				  WHERE id='".$datos['id']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $tema_tesis=$row['tema_tesis'];

	        } 		        
			return $tema_tesis;
	    } 

	function DatosPerfil($datos)
	    {

			$sql="SELECT id_perfil,id_detalle_pdg,ciclo, anio,objetivo_general,
						descripcion,area_tematica_tg 
						FROM pdg_perfil
						where id_perfil='".$datos['id_perfil']."'";

			$query = $this->db->query($sql) ;				
		    return $query;
	    }  
 	
 	function ObtenerObjetivosEspecificos($datos)
	    {  

	        $sql="SELECT REPLACE(REPLACE(objetivo_especifico,'<div>','<tr><td>'),'</div>','</td></tr>') objetivos_especificos 
	        	 FROM pdg_perfil
        		 where id_perfil='".$datos['id_perfil']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $objetivos_especificos=$row['objetivos_especificos'];

	        } 				        
		    return $objetivos_especificos;
		}		    
 	function ObtenerResultadosEsperados($datos)
	    {  

	        $sql="SELECT REPLACE(REPLACE(resultados_esperados_tg,'<div>','<tr><td>'),'</div>','</td></tr>') resultados_esperados_tg 
	        	  FROM pdg_perfil
        		 where id_perfil='".$datos['id_perfil']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $resultados_esperados_tg=$row['resultados_esperados_tg'];

	        } 				        
		    return $resultados_esperados_tg;
		}	
 	//Esta parte quedo comentariado por si en algfuna de las defensas quisieran que aparezcan los nombres de los fulanos
	/*function DatosDocenteAsesor($id_equipo_tg,$anio_tg)
		    {

			/*ubicando el codigo del docente asesor del equipo: es el asesor que esta como docente director.
			Se utiliza un alumno comodin segun el equipo, año, ciclo pasado como parametro*/
	/*		$sql="SELECT DISTINCT id_docente FROM asignado
			WHERE id_cargo='2'
			AND es_docente_director_pdg='1'
			AND id_proceso='PDG'
			AND id_due IN (SELECT id_due FROM conforma
			WHERE id_equipo_tg='".$id_equipo_tg."'
			AND anio_tg='".$anio_tg."')";
			
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $id_docente=$row['id_docente'];

	        } 	

			/*ubicando el nombre del docente asesor segun carnet docente*/
	/*		$sql="SELECT CONCAT(nombre,', ', apellido) AS nombre_apellido FROM gen_docente
			WHERE id_cargo='2'
			AND id_docente='".$id_docente."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nombre_apellido'];

	        } 		        
		        return $valor;
	    } 
	
	function DatosDirGenProcesosGraduacion()
	    {
	        $sql="SELECT valor FROM cat_parametro_general
					WHERE parametro='NDGPDG'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['valor'];

	        } 		        
		        return $valor;
	    } 
	function DatosCoordinadorProcesosGraduacion()
	    {
	        $sql="SELECT valor FROM cat_parametro_general
					WHERE parametro='NCPDG'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['valor'];

	        } 		        
		        return $valor;
	    }  	  */    	       
}
