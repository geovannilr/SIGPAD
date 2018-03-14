<?php 
class GenRemiEjemplares_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function DatosEquipo($datos_equipo)
	    {
	 
	        //Obteniendo datos del equipo a traves del id
	        $sql="SELECT id_equipo_tg,anio_tg,ciclo_tg,tema,sigla FROM pdg_remision_notas_temp
	                WHERE id=".$datos_equipo['id'];
	        $query = $this->db->query($sql) ; 
	        return $query;

	    }    

	function DatosAdminAcademica()
	    {
	        $sql="SELECT valor FROM cat_parametro_general
					WHERE parametro='NAADACAD'";
			$query = $this->db->query($sql) ;			
	        foreach ($query->result_array() as $row)
	        {
	                $valor=$row['valor'];

	        } 		        
		        return $valor;
	    }   
	
	function DatosSecreEscuela()
	    {
	        $sql="SELECT valor FROM cat_parametro_general
					WHERE parametro='NSEISI'";
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
