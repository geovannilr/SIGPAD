<?php
class CiePer_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
          

    function actualizar($insertar){                    

        $query = "CALL per_proc_cie_per_update(
            '".$insertar['id_detalle_pera']."',
            '".$insertar['estado_registro']."'
            );";
            
        $query = $this->db->query($query);
        
        return $this->db->affected_rows();                                            
    }   

    public function Obtener_usuarios_pera($primary_key){
        //Usuarios interesados en la notificación de ELIMINACION
        $this->db->select('docente');
        $this->db->from('per_view_reg_not');
        $this->db->where('id_detalle_pera',$primary_key);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {                
                $usuario['id_docente'] = $row['docente'];                                
        }
        return $usuario;
    } 
}

?>

