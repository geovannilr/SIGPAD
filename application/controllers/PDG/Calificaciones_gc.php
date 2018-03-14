<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calificaciones extends CI_Controller {
    function __construct(){
        parent::__construct();
        //$this->removeCache();
    }
    public function index(){
        $this->load->model('PDG/Calificaciones_model');
        $data['equipo'] = $this->Calificaciones_model->Get_Equipo();
        //$data['equipo'] = 'hola equipo';
        $data['output']=null;
        $data['contenido']='PDG/FormCalificaciones';
        $this->load->view('templates/content', $data);        
    }

}
?>