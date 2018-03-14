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
    
       
}