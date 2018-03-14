<?php 
class GenSoliProrro_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function DatosSolicitud($datos)
	    {

			$sql="SELECT a.anio_tg,a.ciclo_tg,a.tema,a.id_equipo_tg,
				c.fecha_solicitud,c.caso_especial,c.fecha_ini_prorroga,c.fecha_fin_prorroga,
				c.duracion,c.cantidad_evaluacion_actual,c.eva_actual,c.eva_antes_prorroga,c.justificacion
				FROM pdg_equipo_tg a, pdg_detalle b, pdg_solicitud_academica c
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
				AND b.id_detalle_pdg=c.id_detalle_pdg
				AND c.tipo_solicitud='sol_prorroga_tg'
				AND c.id_solicitud_academica='".$datos['id_solicitud_academica']."'";

			$query = $this->db->query($sql) ;				
		    return $query;
	    }  
 	function ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$ciclo_tg)
	    {
	        
	        $sql="SELECT id_due,CONCAT(apellido,  ', ', nombre) AS apellido_nombre FROM gen_estudiante
					WHERE id_due IN (SELECT id_due FROM conforma
							 WHERE id_equipo_tg=".$id_equipo_tg."
							 AND anio_tg=".$anio_tg."
							 AND ciclo_tg=".$ciclo_tg.")
				  order by id_due asc";	
			$query = $this->db->query($sql) ;				        
		    return $query;

	    } 
	function DatosDocenteAsesor($id_equipo_tg,$anio_tg,$ciclo_tg)
		    {

			/*ubicando el codigo del docente asesor del equipo: es el asesor que esta como docente director.
			Se utiliza un alumno comodin segun el equipo, año, ciclo pasado como parametro*/
			$sql="SELECT DISTINCT id_docente FROM asignado
			WHERE id_cargo='2'
			AND es_docente_director_pdg='1'
			AND id_proceso='PDG'
			AND id_due IN (SELECT id_due FROM conforma
			WHERE id_equipo_tg='".$id_equipo_tg."'
			AND anio_tg='".$anio_tg."'
			AND ciclo_tg='".$ciclo_tg."')";
			
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
