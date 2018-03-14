<?php 
class GenSoliCambioNombre_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function DatosEquipo($datos_equipo)
	    {
	 
	        //Obteniendo datos del equipo a traves del id
	        $sql="SELECT id_equipo_tg,anio_tg,tema,sigla FROM pdg_remision_notas_temp
	                WHERE id=".$datos_equipo['id'];
	        $query = $this->db->query($sql) ; 
	        return $query;
	    }
	function DatosSolicitud($datos)
	    {

			$sql="SELECT a.anio_tg,a.ciclo_tg,
				c.nombre_actual,c.nombre_propuesto,
				c.justificacion
				FROM pdg_equipo_tg a, pdg_detalle b, pdg_solicitud_academica c
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
				AND b.id_detalle_pdg=c.id_detalle_pdg
				AND c.tipo_solicitud='sol_modi_nombre_tg'
				AND c.id_solicitud_academica='".$datos['id_solicitud_academica']."'";

			$query = $this->db->query($sql) ;				
		    return $query;
	    }  
 
}
