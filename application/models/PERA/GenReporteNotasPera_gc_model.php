<?php 
class GenReporteNotasPera_gc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}


	function Datos($datos)
	    {

			$sql="SELECT prn.ciclo,prn.anio, pd.id_due, CONCAT(ge.nombre, ' ', ge.apellido) AS nombre,
			prn.docente_mentor, DATE_FORMAT(prn.fecha_finalizacion,'%d-%m-%Y') AS fecha_finalizacion, prn.descripcion, prn.n1, prn.n2, prn.n3, prn.n4, prn.n5, prn.promedio
			 FROM per_registro_nota AS prn
			JOIN
				per_detalle AS pd
			ON
				pd.id_detalle_pera = prn.id_detalle_pera
			JOIN
				gen_estudiante AS ge
			ON
				ge.id_due = pd.id_due
			 WHERE prn.id_registro_nota = '".$datos['id_registro_nota']."'";

			$query = $this->db->query($sql) ;				
		    return $query;
	    }  
 
}
