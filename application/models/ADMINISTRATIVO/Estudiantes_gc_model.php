<?php
class Estudiantes_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

   	function eliminar_Estudiante($primary_key){
        $query = $this->db->query("CALL eliminar_Estudiante('".$primary_key."');");
        return $query->result_array();
    } 

}
?>

