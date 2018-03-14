<?php 
class GenCartaRenuncia_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
function ObtenerEncabezado($datos)
	    {

	        $sql="SELECT REPLACE(REPLACE(encabezado_carta_renuncia,'<div>','<tr><td>'),'</div>','</td></tr>') encabezado_carta_renuncia
        		 FROM pss_detalle_expediente
        		 where id_detalle_expediente='".$datos['id_detalle_expediente']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $encabezado_carta_renuncia=$row['encabezado_carta_renuncia'];

	        } 				        
		    return $encabezado_carta_renuncia;

	    } 	
function ObtenerMotivosRenuncia($datos)
	    {  

	        $sql="SELECT REPLACE(REPLACE(motivos_renuncia,'<div>','<tr><td>'),'</div>','</td></tr>') motivos_renuncia
        		 FROM pss_detalle_expediente
        		 where id_detalle_expediente='".$datos['id_detalle_expediente']."'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $motivos_renuncia=$row['motivos_renuncia'];

	        } 				        
		    return $motivos_renuncia;

	    } 		    
function ObtenerFechaDesglosada($datos)
	    {
			/*Obtener fecha desglosada en dia mes y año de la fecha actual*/

	        $sql="SELECT CURDATE(),DAYOFMONTH(CURDATE()) dia,
					CASE  
					WHEN MONTH(CURDATE()) =1 THEN 'Enero'
					WHEN MONTH(CURDATE()) =2 THEN 'Febrero'
					WHEN MONTH(CURDATE()) =3 THEN 'Marzo'
					WHEN MONTH(CURDATE()) =4 THEN 'Abril'
					WHEN MONTH(CURDATE()) =5 THEN 'Mayo'
					WHEN MONTH(CURDATE()) =6 THEN 'Junio'
					WHEN MONTH(CURDATE()) =7 THEN 'Julio'
					WHEN MONTH(CURDATE()) =8 THEN 'Agosto'
					WHEN MONTH(CURDATE()) =9 THEN 'Septiembre'
					WHEN MONTH(CURDATE()) =10 THEN 'Octubre'
					WHEN MONTH(CURDATE()) =11 THEN 'Noviembre'
					WHEN MONTH(CURDATE()) =12 THEN 'Diciembre'
					END AS mes_case,       
					YEAR(CURDATE()) anio FROM DUAL";
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
