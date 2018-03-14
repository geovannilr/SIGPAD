<?php
class Docentes_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function Agregar_Docente($datos){
    	$carnet = $datos['carnet'];
    	$id_cargo = $datos['id_cargo'];
    	$id_cargo_administrativo = $datos['id_cargo_administrativo'];
    	$id_departamento = $datos['id_departamento'];
    	$nombre_usuario = $datos['nombre_usuario'];
    	$password = $datos['password'];
    	$confirme_password = $datos['confirme_password'];
    	$nombre = $datos['nombre'];
    	$apellido = $datos['apellido'];
    	$direccion = $datos['direccion'];
    	$telefono = $datos['telefono'];
    	$celular = $datos['celular'];
    	$email = $datos['email'];

    	$query = $this->db->query("CALL Crear_Docente('".$carnet."','".$id_cargo."','".$id_cargo_administrativo."','".$id_departamento."','".$nombre_usuario."','".$password."','".$confirme_password."','".$nombre."','".$apellido."','".$direccion."','".$telefono."','".$celular."','".$email."');");
        
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        
        return $query->row_array();
    }
    
    function Leer_Id_Usuario($value){
        $query = $this->db->query("SELECT nombre_usuario FROM gen_usuario WHERE id_usuario = '".$value."' LIMIT 1;");
            
        
        //mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        
        return $row = $query->first_row('array');
    }

    function eliminar_Docente($primary_key){
        $query = $this->db->query("CALL Eliminar_Docente('".$primary_key."');");
        return $query->result_array();
    } 

}
?>

