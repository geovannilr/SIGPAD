<?php
class Usuarios_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function Actualizar_Usuario($datos){
        $id_usuario = $datos['id_usuario'];
        $nombre_usuario = $datos['nombre_usuario'];
        $correo_usuario = $datos['correo_usuario'];
        $id_tipo_usuario = 1;
        $id_perfil_usuario = 1;
        $password = ($datos['password']);
        $confirme_password = ($datos['confirme_password']);
        $query = $this->db->query("CALL Actualizar_Usuario('".$id_usuario."','".$nombre_usuario."','".$correo_usuario."','".$id_tipo_usuario."','".$id_perfil_usuario."','".$password."','".$confirme_password."');");
        
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        
        return $query->row_array();
    }

}
?>

