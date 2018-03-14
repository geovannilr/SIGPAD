<?php 
class GenActaAnteproy_gc_model extends CI_Model
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
	
	function NotaAnteproy($id_equipo_tg,$anio_tg,$ciclo_tg)
	    {

			$sql="SELECT nota_criterio1,nota_criterio2,nota_criterio3,nota_criterio4,nota_criterio5,nota_criterio6,
					nota_criterio7,nota_criterio8,nota_criterio9,nota_criterio10,nota_documento,nota_criterio11,nota_criterio12,
					nota_exposicion,nota_anteproyecto FROM pdg_nota_anteproyecto
					WHERE id_equipo_tg='".$id_equipo_tg."'
					AND anio_tg='".$anio_tg."'
					AND ciclo_tg='".$ciclo_tg."'";

			$query = $this->db->query($sql) ;			
	       	return $query;
	    }
}
