<!-- Creado por: RMORAN -->
<?php
class Rubros_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function Create($insert)
    {
        //Generando el id_rubro
        $sql ="SELECT CASE WHEN MAX(CAST(id_rubro AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_rubro AS UNSIGNED))+1 END AS maximo
              FROM pss_rubro";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_rubro=$row['maximo'];
        };            
          
        //Inserción a gen_materia
        $sql = "INSERT INTO pss_rubro(id_rubro,rubro)
        VALUES ('".$id_rubro."',
                ".$this->db->escape($insert['rubro'])."
                )";
        $this->db->query($sql);
        $comprobar=$this->db->affected_rows();

                 
    }    
}
?>

