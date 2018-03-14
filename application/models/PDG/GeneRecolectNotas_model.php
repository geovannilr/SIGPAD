<?php
class GeneRecolectNotas_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
    function Get_Equipo()
    {
        $query = $this->db->query('SELECT id_equipo_tg FROM pdg_equipo_tg');
        return $query->result_array();
    } 

}

?>

