<?php
class SubirEtapas_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
    function EncontrarIdEquipoFiltrar($id_due){
        $sql = "SELECT DISTINCT id_equipo_tg FROM conforma
                WHERE id_due='".$id_due."'";
        $query = $this->db->query($sql);                  
        foreach ($query->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
        }
        if (empty($id_equipo_tg)){
          $id_equipo_tg=0;
        }           
        return $id_equipo_tg;
    }      
function Update($update)
    {
 
 		//Obteniendo el id_detalle_pdg a traves del identificador de equipo y anio_tg y ciclo_tesis
        $sql="SELECT id_detalle_pdg FROM pdg_detalle
			WHERE id_equipo_tg='".$update['id_equipo_tg']."'
			AND anio_tg='".$update['anio_tg']."'
            AND ciclo_tg='".$update['ciclo_tg']."'";
		$query = $this->db->query($sql) ;			
        foreach ($query->result_array() as $row)
        {
                $id_detalle_pdg=$row['id_detalle_pdg'];

        } 		
  
		//Actualizando la pdg_documento
        $sql = "UPDATE pdg_documento set ruta='".$update['ruta']."'
                where id_tipo_documento_pdg='".$update['id_tipo_documento_pdg']."'
                and id_detalle_pdg='".$id_detalle_pdg."'";		
		$this->db->query($sql);
        return $this->db->affected_rows();

    }    


}

?>
