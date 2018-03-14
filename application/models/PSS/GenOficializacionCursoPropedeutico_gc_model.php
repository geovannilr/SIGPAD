<?php 
class GenOficializacionCursoPropedeutico_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

function ObtenerFechaDesglosada($datos)
	    {
	        /*obtener la fecha de oficilizacion desglosada en dia mes y año*/

	        $sql="SELECT fecha_inicio,DAYOFMONTH(fecha_inicio) dia,
				CASE  
				WHEN MONTH(fecha_inicio) =1 THEN 'Enero'
				WHEN MONTH(fecha_inicio) =2 THEN 'Febrero'
				WHEN MONTH(fecha_inicio) =3 THEN 'Marzo'
				WHEN MONTH(fecha_inicio) =4 THEN 'Abril'
				WHEN MONTH(fecha_inicio) =5 THEN 'Mayo'
				WHEN MONTH(fecha_inicio) =6 THEN 'Junio'
				WHEN MONTH(fecha_inicio) =7 THEN 'Julio'
				WHEN MONTH(fecha_inicio) =8 THEN 'Agosto'
				WHEN MONTH(fecha_inicio) =9 THEN 'Septiembre'
				WHEN MONTH(fecha_inicio) =10 THEN 'Octubre'
				WHEN MONTH(fecha_inicio) =11 THEN 'Noviembre'
				WHEN MONTH(fecha_inicio) =12 THEN 'Diciembre'
				END AS mes_case,       
				YEAR(fecha_inicio) anio FROM pss_detalle_expediente
				where id_detalle_expediente='".$datos['id_detalle_expediente']."'";		
			$query = $this->db->query($sql) ; 
			return $query;

	    } 	 
function ObtenerDatosAlumno($datos)
	    {
			/*obtener los datos del alumno a oficilizar*/

	        $sql="SELECT a.id_due,CONCAT(nombre,', ',apellido) nombre_apellido_alumno 
	        	FROM gen_estudiante a, pss_detalle_expediente b
				WHERE a.id_due=b.id_due
				AND id_detalle_expediente='".$datos['id_detalle_expediente']."'";		
			$query = $this->db->query($sql) ; 
			return $query;

	    } 		     	       
}
