<?php
class IngresoTema_gc_model extends CI_Model{

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

        
        //Actualiacion a pdg_equipo_tg
          $sql="UPDATE pdg_equipo_tg SET 
          tema='".$update['tema_tesis']."',
          sigla='".$update['sigla']."'
          WHERE id_equipo_tg='".$update['id_equipo_tg']."'
          and anio_tg='".$update['anio_tesis']."'
          and ciclo_tg='".$update['ciclo_perfil']."'";  

          $this->db->query($sql);

        return $this->db->affected_rows();


    }    


}
?>

