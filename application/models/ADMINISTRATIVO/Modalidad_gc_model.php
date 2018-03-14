<?php
class Modalidad_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function Create($insert)
    {
        
        $sql ="SELECT CASE WHEN MAX(CAST(id_modalidad AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_modalidad AS UNSIGNED))+1 END AS maximo
              FROM pss_modalidad";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_modalidad=$row['maximo'];
        };            
          
        
        $sql = "INSERT INTO pss_modalidad(id_modalidad,modalidad)
        VALUES ('".$id_modalidad."',
                ".$this->db->escape($insert['modalidad'])."
                )";
        $this->db->query($sql);
        $comprobar=$this->db->affected_rows();

                 
    }    
	
	///////////eliminacion
	function EncontrarLLavesDelete($llaves_delete)
    {
        $this->db->select('id_modalidad');
        $this->db->from('pss_modalidad');
        $this->db->where('id_modalidad',$llaves_delete['primary_key']);
        $query = $this->db->get();
        return $query->result_array();

    } 

    function Delete($delete)
    {
        $sql = "DELETE FROM pss_modalidad
                 WHERE id_modalidad='".$delete['id_modalidad']."'";


        $this->db->query($sql);
        return $this->db->affected_rows();
    }    


	////////////////////
	//////////////////////////////ACTUALIZANDO
	function Update($update)
    {
             
        //Actualiacion Rubro
          $sql="UPDATE pss_modalidad SET 
          modalidad='".$update['modalidad']."'
          WHERE id_modalidad='".$update['id_modalidad']."'";  
          $this->db->query($sql);
        return $this->db->affected_rows();


    }
	////////
	
	
}
?>

