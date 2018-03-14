<?php 
class GenDefensaPublica_gc_model extends CI_Model
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
	function ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg)
	    {
	        
	        $sql="SELECT id_due,CONCAT(apellido,  ', ', nombre) AS apellido_nombre FROM gen_estudiante
					WHERE id_due IN (SELECT id_due FROM conforma
							 WHERE id_equipo_tg=".$id_equipo_tg."
							 AND anio_tg=".$anio_tg.")
				  order by id_due asc";	
			$query = $this->db->query($sql) ;				        
		    return $query;

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
	function DatosDocenteTribunalEva1($id_equipo_tg,$anio_tg)
		    {

			/*ubicando el codigo del docente del tribunal evaluador 1 del equipo: 
			Se utiliza un alumno comodin segun el equipo, año, ciclo pasado como parametro*/
			$sql="SELECT DISTINCT id_docente FROM asignado
			WHERE id_cargo='5'
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
				WHERE id_cargo='5'
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
	function DatosDocenteTribunalEva2($id_equipo_tg,$anio_tg)
		    {

			/*ubicando el codigo del docente del tribunal evaluador 1 del equipo: 
			Se utiliza un alumno comodin segun el equipo, año, ciclo pasado como parametro*/
			$sql="SELECT DISTINCT id_docente FROM asignado
			WHERE id_cargo='6'
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
				WHERE id_cargo='6'
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
		function NotaSegunCarnet($id_equipo_tg,$anio_tg,$id_due)
			    {

					$sql="SELECT (nota_anteproyecto*0.20)+(nota_etapa1*0.35)+(nota_etapa2*0.25)+
							(nota_defensa_publica*0.20) nota_final FROM  pdg_consolidado_notas
							WHERE id_equipo_tg='".$id_equipo_tg."'
							AND anio_tg='".$anio_tg."'
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
	function NotaSegunCarnetEnLetra($resultadoNota)
	    {
	        $sql="SELECT CONCAT(a.primer_numero,' punto ', a.segundo_numero) AS nota_en_letras FROM( 
					SELECT 
					  CASE SUBSTRING(CAST(ROUND(".$resultadoNota.",1) AS CHAR),1,1)  
					    WHEN 1 THEN 'Uno' 
					    WHEN 2 THEN 'Dos' 
					    WHEN 3 THEN 'Tres' 
					    WHEN 4 THEN 'Cuatro' 
					    WHEN 5 THEN 'Cinco' 
					    WHEN 6 THEN 'Seis' 
					    WHEN 7 THEN 'Siete' 
					    WHEN 8 THEN 'Ocho'     
					    WHEN 9 THEN 'Nueve'     
					    ELSE 'Cero' 
					  END primer_numero,
					  CASE SUBSTRING(CAST(ROUND(".$resultadoNota.",1) AS CHAR),3,1)  
					    WHEN 1 THEN 'uno' 
					    WHEN 2 THEN 'sos' 
					    WHEN 3 THEN 'tres' 
					    WHEN 4 THEN 'cuatro' 
					    WHEN 5 THEN 'cinco' 
					    WHEN 6 THEN 'seis' 
					    WHEN 7 THEN 'siete' 
					    WHEN 8 THEN 'ocho'     
					    WHEN 9 THEN 'nueve'     
					    ELSE 'Cero' 
					  END segundo_numero  
					FROM DUAL) a; 
					";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['nota_en_letras'];

	        } 		        
		        return $valor;
	    } 			    
}
