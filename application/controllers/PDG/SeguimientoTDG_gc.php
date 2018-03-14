<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SeguimientoTDG extends CI_Controller {
    function __construct(){
        parent::__construct();
        //$this->removeCache();
    }
    public function index(){
        $this->load->model('PDG/SeguimientoTDG_model');
        $data['equipo'] = $this->SeguimientoTDG_model->Get_Equipo();
        //$data['equipo'] = 'hola equipo';
        $data['contenido']='PDG/FormSeguimientoTDG';
        $this->load->view('templates/content', $data);        
    }

}
?>