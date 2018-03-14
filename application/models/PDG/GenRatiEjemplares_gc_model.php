<?php 
class GenRatiEjemplares_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function DatosEquipo($datos_equipo)
	    {
	 
	        //Obteniendo datos del equipo a traves del id
	        $sql="SELECT id_equipo_tg,anio_tg,ciclo_tg,tema,sigla FROM pdg_ratificacion_notas_temp
	                WHERE id=".$datos_equipo['id'];
	        $query = $this->db->query($sql) ; 
	        return $query;

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
	
	function DatosDirEscuela()
	    {
	        $sql="SELECT valor FROM cat_parametro_general
					WHERE parametro='NDEISI'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['valor'];

	        } 		        
		        return $valor;
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

	function NotaSegunCarnet($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due)
	    {

			$sql="SELECT (nota_anteproyecto*0.20)+(nota_etapa1*0.35)+(nota_etapa2*0.25)+
					(nota_defensa_publica*0.20) nota_final FROM  pdg_consolidado_notas
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'
					AND id_due='".$id_due."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_final'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    }  
 
}
