<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Materias_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->model('ADMINISTRATIVO/Materias_gc_model');
        $this->load->library('grocery_CRUD');
        $this->load->library('form_validation');
        
    }
    public function _Materias_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormMaterias_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {



            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('gen_materia');
            $crud->set_relation('id_docente','gen_docente','nombre');
            $crud->set_language('spanish');
            $crud->set_subject('Materias');  
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_solicitud_academica','Cod. Materia')
                 ->display_as('id_docente','Id. Docente')
                 ->display_as('nombre','Nombre Materia');            
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_materia','id_docente','nombre');   
            //Definiendo los campos obligatorios
            $crud->required_fields('id_materia','id_docente','nombre');   
            //Desabilitando  el wisywig
            $crud->unset_texteditor('nombre','full_text');
            
            //$crud->callback_insert(array($this,'insercion_Materias'));
            $output = $crud->render();

            $this->_Materias_output($output);
    }


    /*Solamente ocupar si el codigo fuera incremental
    public function insercion_Materias($post_array){
   
        
        $insertar['id_docente']=$post_array['id_docente'];
        $insertar['nombre']=$post_array['field-nombre'];
        $comprobar=$this->Materias_gc_model->Create($insertar);
        if ($comprobar >=1){
            return true;
        }
        else{
            //significa que no se realizo la operacion DML
            $comprobar=0;
            return false;
        }                           
    }  */  

}