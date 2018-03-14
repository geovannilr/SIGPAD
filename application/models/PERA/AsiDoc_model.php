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
        $query = $this->db->query('SELECT id_due FROM per_detalle');
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
        /* $query = $this->db->query("CALL per_proc_asi_doc('".$insertar['id_due']."','".$insertar['id_docente']."');");*/

        $query = "CALL per_proc_asi_doc('".$insertar['id_due']."',
                                        '".$insertar['uv_asignadas']."',
                                        '".$insertar['id_docente']."',                                        
                                        '".$insertar['comentario']."',
                                        '".$insertar['docente_general']."');";
                                        //'6');";
                                        //'".$insertar['docente_general']."');";
        $query = $this->db->query($query);        
        return $this->db->affected_rows();                    
            
    }
    
    function Actualizar($insertar)
    {
               	
        $query = "CALL per_proc_asi_doc_update('".$insertar['id_tipo_pera']."',
                                                '".$insertar['id_due']."',
                                                '".$insertar['uv_asignadas']."',
                                                '".$insertar['id_docente']."',                                        
                                                '".$insertar['comentario']."',
                                                '".$insertar['docente_general']."');";   

        $query = $this->db->query($query);
            
        //$query = $this->db->query("CALL per_proc_asi_doc_update('FM09001','".$insertar['id_docente']."');");
                
        return $this->db->affected_rows();
                                               
    }

    
    public function borrar_Asignacion($id, $docente_general){

        $query="CALL per_proc_asi_doc_delete('".$id."','".$docente_general."');";

        $query=$this->db->query($query);

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
    
      function Consultar($id_due){
  
        
        $query = $this->db->query("CALL per_proc_asi_doc_consultar('".$id_due."');");
        
        /*$sql= "SELECT area_deficitaria FROM per_detalle 
                    WHERE id_due = 'PP01085'";
        $query = $this->db->query($sql);*/  
        
        return $query->result();
    }

    public function uv_asignables($id_due){
          
        $query = "CALL per_proc_asi_doc_uv_asignables('".$id_due."');";

        $query = $this->db->query($query);

        if($query->num_rows()>0)                
            return $query->result();
        else
            return $query->num_rows();
    }


    public function ComprobarFK($id){

        $this->db->select('id_evaluacion');
        $this->db->from('per_evaluacion');
        $this->db->where('id_tipo_pera',$id);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $filas = $row['id_evaluacion'];
        }
        return $filas;
    }

    public function Obtener_usuarios_pera($primary_key){
        //Usuarios interesados en la notificación de ELIMINACION
        $this->db->select('due_sin_docente,id_docente,ciclo');
        $this->db->from('per_view_asi_doc');
        $this->db->where('id_tipo_pera',$primary_key);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $usuario['id_due'] = $row['due_sin_docente'];
                $usuario['id_docente'] = $row['id_docente'];
                $usuario['ciclo'] = $row['ciclo'];

        }
        return $usuario;
    }

}

?>

