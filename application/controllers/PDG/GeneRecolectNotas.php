<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GeneRecolectNotas extends CI_Controller {
    function __construct(){
        parent::__construct();
        //$this->removeCache();
    }
    public function index(){
        $this->load->model('PDG/GeneRecolectNotas_model');
        $data['equipo'] = $this->GeneRecolectNotas_model->Get_Equipo();
        //$data['equipo'] = 'hola equipo';
        $data['output'] = null;
        $data['contenido']='PDG/FormGeneRecolectNotas';
        $this->load->view('templates/content', $data);        
    }
}
?>