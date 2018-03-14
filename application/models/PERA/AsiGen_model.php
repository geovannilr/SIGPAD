<?php
class AsiGen_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
    /*function obtener_estudiante(){
        //$query = $this->db->get('pdg_perfil');
        $query = $this->db->get('gen_estudiante');
        
        return $query->result_array();
    }*/
    
      
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
		
            
            
        $query = $this->db->query("CALL per_proc_asi_gen('".$insertar['id_due']."','".$insertar['id_docente']."','".$insertar['id_detalle_pera']."','".$insertar['observaciones']."');");
        //$query = $this->db->query("CALL per_proc_asi_gen('".$insertar['id_due']."','".$insertar['id_docente']."',".$insertar['id_docente'].");");
        
        //$query = $this->db->query("CALL per_proc_asi_gen('".$insertar['id_due']."','".$insertar['id_docente']."',99);");
        
        return $this->db->affected_rows();
           
            
            
            
    }
    
    function Actualizar($insertar)
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
		
        $query = $this->db->query("CALL per_proc_asi_gen_update('".$insertar['id_due']."','".$insertar['id_docente']."','".$insertar['id_detalle_pera']."','".$insertar['observaciones']."');");
            
        //$query = $this->db->query("CALL per_proc_asi_doc_update('FM09001','".$insertar['id_docente']."');");
        
        return $this->db->affected_rows();
           
            
            
            
    }
    
    public function borrar_Asignacion($id){
        $query="CALL per_proc_asi_gen_delete('".$id."');";
        $query=$this->db->query($query);
        return $this->db->affected_rows();
    } 
    
    
    /*function AreaDeficitaria($id_due){
                /*$sql= "SELECT area_deficitaria FROM per_detalle 
                    WHERE id_due = 'aa12345'";
        
        /*$sql = "SELECT area_deficitaria FROM per_detalle 
                    WHERE id_due = ".$this->db->escape($id_due['id_due'])."";
                    
        $query = $this->db->query($sql);             
                    
        $query = $this->db->query("CALL per_proc_consultar_area_deficitaria('".$id_due['id_due']."');");
        
        return $query->result_array();
    }*/
    
      function AreaDeficitarias($id_due){
                /*$sql= "SELECT area_deficitaria FROM per_detalle 
                    WHERE id_due = 'aa12345'";*/
        
        /*$sql = "SELECT area_deficitaria FROM per_detalle 
                    WHERE id_due = ".$this->db->escape($id_due['id_due'])."";
                    
        $query = $this->db->query($sql);*/             
        //$id_due='PP01095';
        
        /*$id_due="PP01085";
        
        echo $id_due;*/
                            
        //$query = $this->db->query("CALL per_proc_consultar_area_deficitaria('".$id_due['id_due']."');");
        
        $query = $this->db->query("CALL per_proc_asi_gen_consultar('".$id_due."');");
        
        /*$sql= "SELECT area_deficitaria FROM per_detalle 
                    WHERE id_due = 'PP01085'";
        $query = $this->db->query($sql);*/  
        
        return $query->result();
    }

    public function Areas_Deficitarias($id_detalle_pera){
        $query= "CALL per_proc_asi_gen_areas_deficitarias('".$id_detalle_pera."')";
        $query= $this->db->query($query);
        return $query->result();
    }
    // ** Para la obtencion del correo de Estudiante ******************************

    public function ObtenerIdLogin($id_due){
        /*buscando el correo */
        $this->db->select('id_login');
        $this->db->from('gen_estudiante');
        $this->db->where('id_due',$id_due);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $id_login=$row['id_login'];
        }
        return $id_login;
    }

    public function Obtener_Estudiante($id_due){
        /*buscando el correo */
        $this->db->select('nombre,apellido');
        $this->db->from('gen_estudiante');
        $this->db->where('id_due',$id_due);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $estudiante = $row['nombre'].' '.$row['apellido'];                

        }
        return $estudiante;
    }

    public function Obtener_usuarios_pera($primary_key){
        //Usuarios interesados en la notificación de ELIMINACION
        $this->db->select('id_due,id_docente,ciclo,anio');
        $this->db->from('per_view_asi_gen');
        $this->db->where('id_detalle_pera',$primary_key);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $usuario['id_due'] = $row['id_due'];
                $usuario['id_docente'] = $row['id_docente'];
                $usuario['ciclo'] = $row['ciclo'].'-'.$row['anio'];

        }
        return $usuario;
    }

    public function ObtenerCorreoUsuario($id_login){
        /*buscando el correo */
        $this->db->select('correo_usuario');
        $this->db->from('gen_usuario');
        $this->db->where('id_usuario',$id_login);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $email=$row['correo_usuario'];
        }
        return $email;
    }
    // ********************************************************************** ** //

    public function ComprobarFK($id){

        $this->db->select('id_tipo_pera');
        $this->db->from('per_tipo');
        $this->db->where('id_detalle_pera',$id);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $filas = $row['id_tipo_pera'];
        }
        return $filas;
    }

    // ** Para obtener el correo del Docente Geneal ***************************

    public function ObtenerIdLogin_Docente($id_docente){
        /*buscando el correo */
        $this->db->select('id_login');
        $this->db->from('gen_docente');
        $this->db->where('id_docente',$id_docente);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $id_login = $row['id_login'];
        }
        return $id_login;
    }

    public function ObtenerDocente($id_docente){
        /*buscando el correo */
        $this->db->select('nombre,apellido');
        $this->db->from('gen_docente');
        $this->db->where('id_docente',$id_docente);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $docente=$row['nombre'].' '.$row['apellido'];                

        }
        return $docente;
    }



    // Comprobacion de seguridad del Estudiante para el read
    public function comprobar_id($id_due){
        $this->db->select('id_detalle_pera');
        $this->db->from('per_view_asi_gen');
        $this->db->where('id_due',$id_due);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $id_detalle_pera = $row['id_detalle_pera'];

        }
        return $id_detalle_pera;
    }    

}

?>

