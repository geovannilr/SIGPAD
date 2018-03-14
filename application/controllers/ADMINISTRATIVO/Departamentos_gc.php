<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departamentos_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='1'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('ADMINISTRATIVO/Departamentos_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('login');
        }
    }
    public function _Departamentos_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormDepartamentos_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('gen_departamento');
            $crud->set_language('spanish');
            $crud->set_subject('Departamentos');  
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_departamento','Cod. Departamento')
                 ->display_as('nombre','Nombre Departamento');           
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_departamento','nombre');   
            //Definiendo los campos obligatorios
            $crud->required_fields('id_departamento','nombre');   
            //Desabilitando  el wisywig
            $crud->unset_texteditor('nombre','full_text');

            $output = $crud->render();

            $this->_Departamentos_output($output);
    }



}