<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*------------------------------------------------------
    Modelo para generar el Login de Los usuarios
    siempre en el models se reaizarn las consultas respectivas y se manda
    a un arreglo para que lo reciba el controlador y se envie dichos datos
    a la vista.
    Siempre debe de existir una base para cada modelo CRUD
------------------------------------------------------*/
class Usuario_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
    
    function Verificar_Cambio_Password($datos){
        $query = $this->db->query("CALL Cambiar_Password_Usuario('".$datos['actual']."','".$datos['nuevo']."','".$datos['confirm']."','".$datos['id_login']."');");
        
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys

        return $query->result_array();
    }
    function Verificar_Crear_Usuario($datos){
        //echo(json_encode($datos));
        $query = $this->db->query("CALL Crear_Usuario('".$datos['proceso_admin']."','".$datos['tipo_usuario']."','".$datos['email']."','".$datos['nombre']."','".$datos['apellidos']."','".$datos['dui']."','".$datos['fecha_nac']."','".$datos['id_confirmacion']."')");
        
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        //echo($this->db->last_query());
        return $query->row_array();
        //return $this->db->affected_rows();
    }

    function Verificar_Cambio_Datos_Usuario($datos){
        //echo(json_encode($datos));
        $query = $this->db->query("CALL Cambiar_Datos_Usuario('".$datos['nombre']."','".$datos['apellidos']."','".$datos['dui']."','".$datos['fecha_nac']."','".$datos['id_login']."')");
        //mysqli_next_result( $this->db->conn_id );
        return $this->db->affected_rows();
    }

    function Get_Tipo_Usuario($datos){
        $query = $this->db->query('SELECT id_tipo_usuario,nombre_tipo_usuario FROM tipo_usuario WHERE id_tipo_usuario = '.$datos['tipo_usuario'].';');
        return $query->row_array();
    }

    function Get_Proc_Admin($datos){
        $query = $this->db->query('SELECT id_proc_admin,nombre_proc_admin FROM proc_admin WHERE id_proc_admin = '.$datos['proceso_admin'].';');
        return $query->row_array();
    }

    function Get_Tipo_Usuario_Sin_Admin(){
        $query = $this->db->query('SELECT id_tipo_usuario,nombre_tipo_usuario FROM tipo_usuario WHERE id_tipo_usuario <> 1;');
        return $query->result();
    }

    function Get_Datos_Usuario($usuario){
        $query = $this->db->query("SELECT p.nombre, p.apellidos, p.DUI,DATE_FORMAT(p.fecha_nac,'%d-%m-%Y') as fecha_nac FROM persona p JOIN login l ON l.id_persona    = p.id_persona  WHERE id_login =".$usuario.";");            
        return $query->row_array();
        //echo($usuario);
    }
       
}