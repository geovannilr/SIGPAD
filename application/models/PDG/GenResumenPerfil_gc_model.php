<?php 
class GenResumenPerfil_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function DatosPerfil($datos)
	    {

			$sql="SELECT id_perfil,id_detalle_pdg,ciclo, anio,objetivo_general,
						objetivo_especifico,descripcion 
						FROM pdg_perfil
						where id_perfil='".$datos['id_perfil']."'";

			$query = $this->db->query($sql) ;				
		    return $query;
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
