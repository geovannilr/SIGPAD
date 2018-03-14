<?php
class Rubro_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function Create($insert)
    {
        
        $sql ="SELECT CASE WHEN MAX(CAST(id_rubro AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_rubro AS UNSIGNED))+1 END AS maximo
              FROM pss_rubro";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_rubro=$row['maximo'];
        };            
          
        
        $sql = "INSERT INTO pss_rubro(id_rubro,rubro)
        VALUES ('".$id_rubro."',
                ".$this->db->escape($insert['rubro'])."
                )";
        $this->db->query($sql);
        $comprobar=$this->db->affected_rows();

                 
    }    
	
	///////////eliminacion
	function EncontrarLLavesDelete($llaves_delete)
    {
        $this->db->select('id_rubro');
        $this->db->from('pss_rubro');
        $this->db->where('id_rubro',$llaves_delete['primary_key']);
        $query = $this->db->get();
        return $query->result_array();

    } 

    function Delete($delete)
    {
        $sql = "DELETE FROM pss_rubro
                 WHERE id_rubro='".$delete['id_rubro']."'";


        $this->db->query($sql);
        return $this->db->affected_rows();
    }    


	////////////////////
	//////////////////////////////ACTUALIZANDO
	function Update($update)
    {
             
        //Actualiacion Rubro
          $sql="UPDATE pss_rubro SET 
          rubro='".$update['rubro']."'
          WHERE id_rubro='".$update['id_rubro']."'";  
          $this->db->query($sql);
        return $this->db->affected_rows();


    }
	////////
	
	
}
?>

