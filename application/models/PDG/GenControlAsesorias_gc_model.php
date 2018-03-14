<?php 
class GenControlAsesorias_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function DatosAsesoria($datos)
	    {

			$sql="SELECT id_bitacora,id_equipo_tg,anio_tesis,ciclo_tesis,ciclo_asesoria,anio_asesoria,tema_asesoria,
			tematica_tratar,hora_inicio,hora_fin,lugar,observaciones,
			id_due1,hora_inicio_alumno_1,hora_fin_alumno_1,
			id_due2,hora_inicio_alumno_2,hora_fin_alumno_2,
			id_due3,hora_inicio_alumno_3,hora_fin_alumno_3,
			id_due4,hora_inicio_alumno_4,hora_fin_alumno_4,
			id_due5,hora_inicio_alumno_5,hora_fin_alumno_5,
			id_docente,hora_inicio_docente,hora_fin_docente
			 FROM view_control_aseso_pdg
			 WHERE id_bitacora='".$datos['id_bitacora']."'";

			$query = $this->db->query($sql) ;				
		    return $query;
	    }  
 	function ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$id_due)
	    {
	        
	        $sql="SELECT CONCAT(apellido,  ', ', nombre) AS apellido_nombre FROM gen_estudiante
					WHERE id_due IN (SELECT id_due FROM conforma
							 WHERE id_equipo_tg='".$id_equipo_tg."'
							 AND anio_tg='".$anio_tg."')
				  	and id_due='".$id_due."'";	
			$query = $this->db->query($sql) ;
	        foreach ($query->result_array() as $row)
	        {
	                $apellido_nombre=$row['apellido_nombre'];

	        } 	

	    	if (isset($apellido_nombre)){
				return $apellido_nombre;		        	
	        	/*Si no han asignado ningun docente entonces se mapea un espacio a la varible que llegara al nombre apelliudo del docente para que no reviente*/
	        	
	        }else{
	        	$valor=' ';
	        	return $valor;
	        }



	    } 
	function DatosDocenteAsesor($id_equipo_tg,$anio_tg)
		    {

			/*ubicando el codigo del docente asesor del equipo: es el asesor que esta como docente director.
			Se utiliza un alumno comodin segun el equipo, año, ciclo pasado como parametro*/
			$sql="SELECT DISTINCT id_docente FROM asignado
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

	    	if (isset($id_docente)){
				/*ubicando el nombre del docente asesor segun carnet docente*/
				$sql="SELECT CONCAT(nombre,', ', apellido) AS nombre_apellido FROM gen_docente
				WHERE id_cargo='2'
				AND id_docente='".$id_docente."'";

				$query = $this->db->query($sql) ;			
		        foreach ($query->result_array() as $row)
		        {
		                $valor=$row['nombre_apellido'];

		        } 		        	
	        	/*Si no han asignado ningun docente entonces se mapea un espacio a la varible que llegara al nombre apelliudo del docente para que no reviente*/
	        	
	        }else{
	        	$valor=' ';
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
	    }  	      	       
}
