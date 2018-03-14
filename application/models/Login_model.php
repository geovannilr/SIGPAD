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
   
    function Leer($usuario,$password){
        $query = $this->db->query("CALL Login('".$usuario."','".$password."');");
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        //return $query->result();
        if($query->num_rows()==1){
                //echo "Encontrado";
     	      return $query->result();
        }
        else {
            $this->session->set_flashdata('usuario_incorrecto','Los datos introducidos son incorrectos');

        } 
        # code...
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