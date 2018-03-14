<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    function __construct(){
        parent::__construct();
        if($this->session->userdata('tipo')){


        $this->load->library('session');
        }
        else{
            redirect('login');
        }
        //$this->removeCache();
    }
    
	public function Index(){
        //echo($this->session->userdata('nombre'));
        if($this->session->userdata()) {
            $datos['output'] = null;
            $datos['contenido']='Home';
            $this->load->view('templates/content', $datos);
        }
        else{
            echo('sesion no iniciada');
        }
        /*
	*/
    }
    
    public function Entrar() {
 
    }//Entrar

    //-------------------------------------------------

 


}