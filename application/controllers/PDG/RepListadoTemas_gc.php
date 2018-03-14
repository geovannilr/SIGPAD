<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RepListadoTemas_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='4' or $id_cargo_administrativo == '1'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/RepListadoTemas_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _RepListadoTemas_gc_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormRepListadoTemas_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $crud = new grocery_CRUD();
            $crud->set_table('pdg_equipo_tg');
            $crud->set_primary_key('id_equipo_tg'); 
            //$crud->order_by('id_equipo_tg');
            $crud->order_by('CAST(id_equipo_tg AS UNSIGNED)');
            $crud->set_language('spanish');
            $crud->set_subject('Listado de Temas de TG');
            //Definiendo las columnas que apareceran
            $crud->columns('id_equipo_tg','tema');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_equipo_tg','Equipo TG')
                 ->display_as('tema','Tema');
                
            $output = $crud->unset_add();
            $output = $crud->unset_edit();
            $output = $crud->unset_delete();
            $output = $crud->unset_read();
            $output = $crud->render();
            $this->_RepListadoTemas_gc_output($output);
    }


}