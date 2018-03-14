<?php
class EstEva_model extends CI_Model{

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
    

    function Establecer($insertar)
    {
                      
		  $query = $this->db->query("CALL per_proc_est_eva('".$insertar['id_tipo_pera']."','".$insertar['nombre']."','".$insertar['fecha']."','".$insertar['descripcion']."','".$insertar['porcentaje']."','".$insertar['nota']."');");
		  
		  //$query = $this->db->query("CALL per_proc_est_eva('".$insertar['id_tipo_pera']."',0,0,0,0,0);");
        
        return $this->db->affected_rows();                                   
            
    }
    
	public function Eliminar($id){
		
 		$query="CALL per_proc_est_eva_delete('".$id."');";
		$query=$this->db->query($query);
		return $this->db->affected_rows();
	} 
	
	function Actualizar($insertar){
		
		$query= "CALL per_proc_est_eva_update('".$insertar['id_evaluacion']."',
															'".$insertar['nombre']."',
															'".$insertar['fecha']."',
															'".$insertar['descripcion']."',
															'".$insertar['porcentaje']."',
															'".$insertar['nota']."');";
		              				
		$query = $this->db->query($query);    
		//$query = $this->db->query("CALL per_proc_asi_doc_update('FM09001','".$insertar['id_docente']."');");
		 
		return $this->db->affected_rows();                               
            
    }
    
    

    function Consultar($id_tipo_pera){

        $query = "CALL per_proc_est_eva_consultar('".$id_tipo_pera."');";

        $query = $this->db->query($query);

        return $query->result();
    }

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

    public function Obtener_usuarios_pera($primary_key){
        //Usuarios interesados en la notificación de ELIMINACION
        $this->db->select('id_due,nombre,descripcion_pera');
        $this->db->from('per_view_est_eva');
        $this->db->where('id_evaluacion',$primary_key);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $usuario['id_due'] = $row['id_due'];
                $usuario['nombre'] = $row['nombre'];
                $usuario['descripcion_pera'] = $row['descripcion_pera'];

        }
        return $usuario;
    }


    // Validacion del porcentaje total de las evaluaciones por Tipo de PERA
    public function porcentaje_total($id_tipo_pera){
          
        $query = "CALL per_proc_est_eva_porcentaje_total('".$id_tipo_pera."');";

        $query = $this->db->query($query);

        if($query->num_rows()>0)                
            return $query->result();
        else
            return $query->num_rows();
    }
}

?>

