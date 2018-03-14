<?php 
class GenResumenExpediente_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
function ObtenerDatosAlumno($datos)
	    {
			/*obtener los datos del alumno */

	        $sql="SELECT a.id_due,CONCAT(a.nombre,', ',a.apellido) nombre_apellido_alumno,
				DATE_FORMAT(a.apertura_expediente_pss, '%d-%m-%Y') apertura_expediente_pss,
				DATE_FORMAT(a.fecha_remision, '%d-%m-%Y') fecha_remision,
				a.direccion,a.email,a.lugar_trabajo,a.telefono_trabajo,a.observaciones_exp_pss
				FROM gen_estudiante a, es b, gen_tipo_estudiante c
				WHERE a.id_due=b.id_due
				AND b.id_tipo_estudiante=c.id_tipo_estudiante
				AND c.id_tipo_estudiante='PSS'
				AND a.id_due='".$datos['id_due']."'";		
				$query = $this->db->query($sql) ; 
			return $query;

	    } 	
 function ValidarDatosResumen($id_due,$posicion)
	    {
			
			$sql="SELECT count(*) cantidad
			 FROM view_rep_resumen_pss
			 WHERE id_due='".$id_due."'
		     AND id=".$posicion."";
	        $query = $this->db->query($sql) ; 
	        foreach ($query->result_array() as $row)
	        {
	                $cantidad=$row['cantidad'];
	        }	
			return $cantidad;		

	    }        
function ObtenerDatosResumen($id_due,$posicion)
	    {
			$sql="SELECT id_detalle_expediente,servicio_social,fecha_inicio,fecha_fin,horas_prestadas,monto,beneficiario_directo,
			 beneficiario_indirecto,estado_case
			 FROM view_rep_resumen_pss
			WHERE id_due='".$id_due."'
		    AND id=".$posicion."";
			$query = $this->db->query($sql) ; 
						return $query;
	    } 
 function ObtenerNombreApellidoDocenteAsesor($id_detalle_expediente)
	    {
	        /*****$sql="INSERT INTO tabla(valor) VALUES('".$id_detalle_expediente."')";
	        $this->db->query($sql); *****/
	        	
        	/*validando si para ese expediente de ss hay docente asesor asignado*/
			$sql="SELECT count(*) cantidad
			FROM asignado a,gen_docente b
			WHERE a.id_docente=b.id_docente
			AND a.id_proceso='PSS'
			AND a.id_detalle_expediente='".$id_detalle_expediente."'
			AND a.es_docente_pss_principal=1";
	        $query = $this->db->query($sql) ; 
	        foreach ($query->result_array() as $row)
	        {
	                $cantidad=$row['cantidad'];
	        }

	        if($cantidad>=1){
	        	/*para encontrar el nombre del docente*/
				$sql="SELECT CONCAT(b.nombre,', ',b.apellido) nombre_tutor_ss
				FROM asignado a,gen_docente b
				WHERE a.id_docente=b.id_docente
				AND a.id_proceso='PSS'
				AND a.id_detalle_expediente='".$id_detalle_expediente."'
				AND a.es_docente_pss_principal=1";
		        $query = $this->db->query($sql) ; 
		        foreach ($query->result_array() as $row)
		        {
		                $nombre_tutor_ss=$row['nombre_tutor_ss'];
		        }	
				return $nombre_tutor_ss;	
	        }else{
	        	return ' ';
	        }
	

	    }        	    		 
}
