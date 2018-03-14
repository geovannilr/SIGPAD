<?php
class Contacto_gc_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function Agregar_Contacto($datos){
        $dui = $datos['dui'];
        $id_institucion = $datos['id_institucion'];
        $nombre = $datos['nombre'];
        $apellido = $datos['apellido'];
        $nombre_usuario = $datos['nombre_usuario'];
        $password = $datos['password'];
        $confirme_password = $datos['confirme_password'];
        $descripcion_cargo = $datos['descripcion_cargo'];
        $telefono = $datos['telefono'];
        $celular = $datos['celular'];
        $email = $datos['email'];
        

        $query = $this->db->query("CALL Crear_Contacto('".$dui."','".$id_institucion."','".$nombre."','".$apellido."','".$nombre_usuario."','".$password."','".$confirme_password."','".$descripcion_cargo."','".$telefono."','".$celular."','".$email."');");
        
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        
        return $query->row_array();
    }
    
    function Leer_Id_Usuario($value){
        $query = $this->db->query("SELECT nombre_usuario FROM gen_usuario WHERE id_usuario = '".$value."' LIMIT 1;");
        //mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        
        return $row = $query->first_row('array');
    }

}
?>

