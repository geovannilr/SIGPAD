<?php 
class GenCartaOficializacion_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

function ObtenerEncabezado($datos)
	    {
	        
	        $sql="SELECT REPLACE(REPLACE(encabezado_oficializacion,'<div>','<tr><td>'),'</div>','</td></tr>') encabezado
        		 FROM pss_detalle_expediente
        		 where id_detalle_expediente='".$datos['id_detalle_expediente']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $encabezado=$row['encabezado'];

	        } 				        
		    return $encabezado;

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
function ObtenerNombreServicioSocial($datos)
	    {
			/*Obtener el nombre del servicio social a oficializar*/
	        $sql="SELECT b.nombre_servicio_social  FROM pss_detalle_expediente a, pss_servicio_social b
			WHERE a.id_servicio_social=b.id_servicio_social
			AND a.id_detalle_expediente='".$datos['id_detalle_expediente']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $nombre_servicio_social=$row['nombre_servicio_social'];

	        } 				        
		    return $nombre_servicio_social;

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
function ObtenerNombreApellidoDirectorEisi($datos)
	    {
			/*obtener el nombre_apellido del director de la eisi*/

	        $sql="SELECT valor FROM cat_parametro_general
			WHERE parametro='NDEISI'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['valor'];

	        } 				        
		    return $valor;

	    } 	 

function ObtenerNombreApellidoCoorSubUniPss($datos)
	    {
			/*obtener el nombre_apellido del coordinador de la subunidad de servicio social*/
	        $sql="SELECT valor FROM cat_parametro_general
			WHERE parametro='NCPSS'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['valor'];

	        } 				        
		    return $valor;


	    } 	 

}
