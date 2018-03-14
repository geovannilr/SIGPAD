<?php 
class GenActaEtapa2_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function DatosEquipo($datos_equipo)
	    {
	 
	        //Obteniendo datos del equipo a traves del id
	        $sql="SELECT id_equipo_tg,anio_tg,ciclo_tg,tema FROM pdg_consolidado_notas
	                WHERE id_consolidado_notas=".$datos_equipo['id'];
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

			$sql="SELECT nota_etapa2 FROM  pdg_consolidado_notas
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'
					AND id_due='".$id_due."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_etapa2'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    } 
	function NotaCriterio1($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due)
	    {

			$sql="SELECT nota_criterio1 FROM pdg_nota_etapa2
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'
					AND id_due='".$id_due."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_criterio1'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    } 
	function NotaCriterio2($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due)
	    {

			$sql="SELECT nota_criterio2 FROM pdg_nota_etapa2
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'
					AND id_due='".$id_due."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_criterio2'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    } 
	function NotaCriterio3($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due)
	    {

			$sql="SELECT nota_criterio3 FROM pdg_nota_etapa2
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'
					AND id_due='".$id_due."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_criterio3'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    } 
	function NotaCriterio4($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due)
	    {

			$sql="SELECT nota_criterio4 FROM pdg_nota_etapa2
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'
					AND id_due='".$id_due."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_criterio4'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    } 
	function NotaCriterio5($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due)
	    {

			$sql="SELECT nota_criterio5 FROM pdg_nota_etapa2
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'
					AND id_due='".$id_due."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_criterio5'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    } 
	function NotaCriterio6($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due)
	    {

			$sql="SELECT nota_criterio6 FROM pdg_nota_etapa2
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'
					AND id_due='".$id_due."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_criterio6'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    }     	    	    	    	    
	function NotaExposicion($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due)
	    {

			$sql="SELECT nota_exposicion FROM pdg_nota_etapa2
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'
					AND id_due='".$id_due."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_exposicion'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    }  	
	function NotaDocumento($id_equipo_tg,$anio_tg,$ciclo_tg)
	    {

			$sql="SELECT DISTINCT nota_documento FROM pdg_nota_etapa2
				  WHERE id_equipo_tg='".$id_equipo_tg."'
				  AND anio_tg='".$anio_tg."'
				  AND ciclo_tg='".$ciclo_tg."'";

			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_documento'];

	        } 		        
		        
            if (empty($valor)){
            	$valor=0;
            }		
		    return $valor;
	    }  



}
