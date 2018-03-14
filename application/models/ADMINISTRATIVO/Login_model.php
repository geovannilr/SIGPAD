<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*------------------------------------------------------
    Modelo para generar el Login de Los usuarios
    siempre en el models se reaizarn las consultas respectivas y se manda
    a un arreglo para que lo reciba el controlador y se envie dichos datos
    a la vista.
    Siempre debe de existir una base para cada modelo CRUD
------------------------------------------------------*/
class Login_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
    function Validate($tipo_usuario)
    {
        
    }
    function Crear(){
/**
   *         $query = $this->db->query("CALL Consultar_Login_Usuario('".$insert['username']."');");
   *         mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
   *         if($query->num_rows()==1){
   *             return true;
   *         }
   *         else{
   *             $this->db->query("call Crear_Usuario('".$insert['username']."','".$insert['password']."');");
   *             return false;
   *         } 
   */  
    }
   
    function Verificar_Usuario($datos){
        // solamente verifica si está activado o no el usuario
        //0- no activado
        //1- activado
        //2- no existe ese usuario
        //$query = $this->db->query("CALL Verificar_Usuario('".$datos['email']."','".$datos['password']."','".$datos['tipo_usuario']."','".$datos['proc_admin']."');");
        $query = $this->db->query("CALL Verificar_Usuario('".$datos['nombre_usuario']."','".$datos['password']."');");
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys

        return $query->result_array();
    }  
    
    function Crear_Id_Confirmacion($confirmacion){
        $query = $this->db->query("CALL Crear_Id_Confirmacion('".$confirmacion['id_login']."','".$confirmacion['id_confirmacion']."');");
        //mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys

    }//function

    function Activar_Usuario($datos){
        
        $query = $this->db->query("CALL Activar_Usuario('".$datos['id']."','".$datos['codigo']."');");
        //mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        return $this->db->affected_rows();
    }//function
/*
    function Get_Tipo_Usuario(){
        $query = $this->db->query('SELECT id_tipo_usuario,nombre_tipo_usuario FROM tipo_usuario;');
        return $query->result();
    }

    function Get_Proc_Admin(){
        $query = $this->db->query('SELECT id_proc_admin,nombre_proc_admin FROM proc_admin;');
        return $query->result();
    }
*/
    //Lee Datos del Usuario
    function Leer_Datos_Usuario($email){
        $query = $this->db->query("CALL Leer_Datos_Usuario('".$nombre_usuario."');");
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        return $query->result_array();
    }

    function Update($update)
    {
/**
 *     $this->db->where('id_tipo_usuario',$update['id_tipo_usuario']);
 *     $this->db->update('tipo_usuario',$update);
 */
    }

    function Delete($parametros)
    {
        # code...
    }
    function Show()
    {
//    $query=$this->db->order_by("id_tipo_usuario","ASC");
//    $query = $this->db->get('tipo_usuario');
//   
    return $query->result();
    }
    function login($user,$pass)
    {   
    }    
}