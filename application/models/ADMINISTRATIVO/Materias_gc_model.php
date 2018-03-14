<?php
class Materias_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
     
	/*Solamente ocupar si el codigo fuera incremental
    function Create($insert)
    {
        //Generando el id_materia
        $sql ="SELECT CASE WHEN MAX(CAST(id_materia AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_materia AS UNSIGNED))+1 END AS maximo
              FROM gen_materia";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_materia=$row['maximo'];
        };            
          
        //Inserción a gen_materia
        $sql = "INSERT INTO gen_materia(id_materia,id_docente,nombre)
        VALUES ('".$id_materia."',
                ".$this->db->escape($insert['id_docente']).",
                ".$this->db->escape($insert['nombre'])."
                )";
        $this->db->query($sql);
        $comprobar=$this->db->affected_rows();

                 
    }*/
}
?>

