<?php
class AsiDoc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
    function obtener_estudiante(){
        //$query = $this->db->get('pdg_perfil');
        $query = $this->db->get('gen_estudiante');
        
        return $query->result_array();
    }
    
      
    function get_Due()
    {
        $query = $this->db->query('SELECT id_due FROM gen_estudiante');
        return $query->result_array();
    } 
    
    function get_Docente()
    {
        /*$sql = "SELECT area_deficitaria as id_docente FROM per_detalle 
                    WHERE id_due = 'aa12345';";
        $query = $this->db->query($sql);*/
        $query = $this->db->query('SELECT id_docente FROM gen_docente');
      
        return $query->result_array();
    } 
    

    function Asignar($insertar)
    {
               
		/*$sql = "INSERT INTO asignado (id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss) 
		          VALUES (
                            ".$this->db->escape($insertar['id_due']).",
                    		".$this->db->escape($insertar['id_docente']).",
                            'PER',3,0)";
        		/*".$this->db->escape($insert['id_proceso']).",
			".$this->db->escape($insert['id_cargo']).",
					".$this->db->escape($insert['correlativo_tutor_ss'])."
					)";*/
                                                         
		//$this->db->query($sql);
		
            
            
        $query = $this->db->query("CALL per_proc_asi_doc('".$insertar['id_due']."','".$insertar['id_docente']."');");
        
        return $this->db->affected_rows();
           
            
            
            
    }
    
    function AreaDeficitaria($id_due){
                /*$sql= "SELECT area_deficitaria FROM per_detalle 
                    WHERE id_due = 'aa12345'";*/
        
        /*$sql = "SELECT area_deficitaria FROM per_detalle 
                    WHERE id_due = ".$this->db->escape($id_due['id_due'])."";
                    
        $query = $this->db->query($sql);*/             
                    
        $query = $this->db->query("CALL per_proc_consultar_area_deficitaria('".$id_due['id_due']."');");
        
        return $query->result_array();
    }
    
}

?>

