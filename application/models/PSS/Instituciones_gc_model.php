<?php
class Instituciones_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function Create($insert)
    {
        //Generando el id_institucion
        $sql ="SELECT CASE WHEN MAX(CAST(id_institucion AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_institucion AS UNSIGNED))+1 END AS maximo
              FROM pss_institucion";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_institucion=$row['maximo'];
        };            
          
        //Inserción a gen_materia
        $sql = "INSERT INTO pss_institucion(id_institucion,id_rubro,nombre,tipo,nit,direccion,telefono,email)
        VALUES ('".$id_institucion."',
                ".$this->db->escape($insert['id_rubro']).",
                ".$this->db->escape($insert['nombre']).",
                ".$this->db->escape($insert['tipo']).",
                ".$this->db->escape($insert['nit']).",
                ".$this->db->escape($insert['direccion']).",
                ".$this->db->escape($insert['telefono']).",
                ".$this->db->escape($insert['email'])."
                )";
        $this->db->query($sql);
        $comprobar=$this->db->affected_rows();

                 
    }    
}
?>

