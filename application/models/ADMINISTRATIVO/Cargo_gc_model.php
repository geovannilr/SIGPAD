<?php
class Cargo_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
function Create($insert)
    {
        //Generando el id_cargo
        $sql ="SELECT CASE WHEN MAX(CAST(id_cargo AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_cargo AS UNSIGNED))+1 END AS maximo
              FROM gen_cargo";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_cargo=$row['maximo'];
        };            
          
        //Inserción a gen_cargo
        $sql = "INSERT INTO gen_cargo(id_cargo,descripcion)
        VALUES ('".$id_cargo."',
                ".$this->db->escape($insert['descripcion'])."
                )";
        $this->db->query($sql);
        $comprobar=$this->db->affected_rows();

                 
    }        
}
?>

