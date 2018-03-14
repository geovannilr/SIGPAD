<?php
class ResumenAproDeneProrro_gc_model extends CI_Model{

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

         
        //Obteniendo el identificador del doumento a actualizar a partir del id_solicitud_academica
        $this->db->select('id_documento_pdg');
        $this->db->from('pdg_solicitud_academica');     
        $this->db->where('id_solicitud_academica',$update['id_solicitud_academica']);
        $query = $this->db->get();
        
        foreach ($query->result_array() as $row)
        {
                $id_documento_pdg=$row['id_documento_pdg'];

        }
   
        //Actualizando la pdg_documento
        $sql = "UPDATE pdg_documento set id_tipo_documento_pdg='".$update['id_tipo_documento']."',ruta='".$update['ruta']."'
                where id_documento_pdg='".$id_documento_pdg."'";        
        $this->db->query($sql);

        //Actualizacion a pdg_solicitud_academica
        $sql = "UPDATE pdg_solicitud_academica set estado='".$update['estado']."'
                where id_solicitud_academica='".$update['id_solicitud_academica']."'";
        $this->db->query($sql);
        return $this->db->affected_rows();

    }    

}

?>




