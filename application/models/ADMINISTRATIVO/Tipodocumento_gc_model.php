<?php
class Tipodocumento_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function Create($insert)
    {
        
        $sql ="SELECT CASE WHEN MAX(CAST(id_tipo_documento_pss AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_tipo_documento_pss AS UNSIGNED))+1 END AS maximo
              FROM pss_tipo_documento";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_tipo_documento_pss=$row['maximo'];
        };            
          
        
        $sql = "INSERT INTO pss_tipo_documento(id_tipo_documento_pss,descripcion)
        VALUES ('".$id_tipo_documento_pss."',
                ".$this->db->escape($insert['descripcion'])."
                )";
        $this->db->query($sql);
        $comprobar=$this->db->affected_rows();

                 
    }    
	
	///////////eliminacion
	function EncontrarLLavesDelete($llaves_delete)
    {
        $this->db->select('id_tipo_documento_pss');
        $this->db->from('pss_tipo_documento');
        $this->db->where('id_tipo_documento_pss',$llaves_delete['primary_key']);
        $query = $this->db->get();
        return $query->result_array();

    } 

    function Delete($delete)
    {
        $sql = "DELETE FROM pss_tipo_documento
                 WHERE id_tipo_documento_pss='".$delete['id_tipo_documento_pss']."'";


        $this->db->query($sql);
        return $this->db->affected_rows();
    }    


	////////////////////
	//////////////////////////////ACTUALIZANDO
	function Update($update)
    {
             
        //Actualiacion Rubro
          $sql="UPDATE pss_tipo_documento SET 
          descripcion='".$update['descripcion']."'
          WHERE id_tipo_documento_pss='".$update['id_tipo_documento_pss']."'";  
          $this->db->query($sql);
        return $this->db->affected_rows();


    }
	////////
	
	
}
?>

