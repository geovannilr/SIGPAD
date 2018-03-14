<?php
class RegNot_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
      
    

    function insertar($insertar)
    {
               
		
        $query = "CALL per_proc_reg_not(
            '".$insertar['id_detalle_pera']."',
            '".$insertar['ciclo']."',
            '".$insertar['anio']."',
            '".$insertar['docente_mentor']."',
            '".$insertar['fecha_finalizacion']."',
            '".$insertar['descripcion']."',
            '".$insertar['n1']."',
            '".$insertar['n2']."',
            '".$insertar['n3']."',
            '".$insertar['n4']."',
            '".$insertar['n5']."',
            '".$insertar['p1']."',
            '".$insertar['p2']."',
            '".$insertar['p3']."',
            '".$insertar['p4']."',
            '".$insertar['p5']."',
            '".$insertar['promedio']."',
            '".$insertar['estado']."'
            );";
            
        $query = $this->db->query($query);
        
        return $this->db->affected_rows();           
                                    
    }


    function actualizar($insertar){                    

        $query = "CALL per_proc_reg_not_update(
            '".$insertar['id_registro_nota']."',
            '".$insertar['fecha_finalizacion']."',
            '".$insertar['descripcion']."',
            '".$insertar['estado']."'
            );";
            
        $query = $this->db->query($query);
        
        return $this->db->affected_rows();                                            
    }

    function eliminar($primary_key){

        $query = "CALL per_proc_reg_not_delete('".$primary_key."');";
            
        $query = $this->db->query($query);
        
        return $this->db->affected_rows();  
    }
    
    
    function Nota_Final($id_detalle_pera){
            
                    
        $query = $this->db->query("CALL per_proc_reg_not_final('".$id_detalle_pera."');");
        
        return $query->result_array();
    }

    public function Obtener_usuarios_pera($primary_key){
        //Usuarios interesados en la notificación de ELIMINACION
        $this->db->select('due,docente','docente_mentor');
        $this->db->from('per_view_reg_not');
        $this->db->where('id_registro_nota',$primary_key);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $usuario['id_due'] = $row['due'];
                $usuario['id_docente'] = $row['docente'];                
                $usuario['docente_mentor'] = $row['docente_mentor'];      
                $usuario['estado'] = $row['estado'];
        }
        return $usuario;
    }
}

?>

