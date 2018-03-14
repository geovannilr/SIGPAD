<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GeneRatiNotas extends CI_Controller {
    function __construct(){
        parent::__construct();
        //$this->removeCache();
    }
    public function index(){
        $this->load->model('PDG/GeneRatiNotas_model');
        $data['equipo'] = $this->GeneRatiNotas_model->Get_Equipo();
        $data['output'] = null;
        $data['contenido']='PDG/FormGeneRatiNotas';
        $this->load->view('templates/content', $data);        
    }


}
?>