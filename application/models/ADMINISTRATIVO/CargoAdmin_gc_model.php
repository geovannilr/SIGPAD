<?php
class CargoAdmin_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function Create($insert)
    {
        //Generando el id_cargo_administrativo
        $sql ="SELECT CASE WHEN MAX(CAST(id_cargo_administrativo AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_cargo_administrativo AS UNSIGNED))+1 END AS maximo
              FROM gen_cargo_administrativo";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_cargo_administrativo=$row['maximo'];
        };            
          
        //Inserción a gen_materia
        $sql = "INSERT INTO gen_cargo_administrativo(id_cargo_administrativo,descripcion)
        VALUES ('".$id_cargo_administrativo."',
                ".$this->db->escape($insert['descripcion'])."
                )";
        $this->db->query($sql);
        $comprobar=$this->db->affected_rows();

                 
    }    
}
?>

