<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cargo_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='1'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('ADMINISTRATIVO/Cargo_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('login');
        }
    }
    public function _Cargo_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormCargo_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $crud = new grocery_CRUD();
            $crud->unset_add();
            $crud->unset_delete();
            //Seteando la tabla o vista
            $crud->set_table('gen_cargo');
            $crud->order_by('CAST(id_cargo AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Cargo Docente'); 
            //Definiendo las columnas que apareceran
            $crud->columns('id_cargo','descripcion');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_cargo','Cod. Cargo')
                 ->display_as('descripcion','Descripcion de Cargo');         
            $crud->callback_field('descripcion',array($this,'descripcionSoloLectura'));

            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('descripcion');   
            //Definiendo los campos obligatorios
            $crud->required_fields('descripcion');   
            //Desabilitando  el wisywig
            $crud->unset_texteditor('descripcion','full_text');
            
            $crud->callback_insert(array($this,'insercion_Cargo'));

            $output = $crud->render();

            $this->_Cargo_output($output);
    }
    function descripcionSoloLectura($value){
        return '<textarea class="form-control" id="descripcion" name="descripcion">'.$value.'</textarea>';
    }           
     public function insercion_Cargo($post_array){

            $insertar['descripcion']=$post_array['descripcion'];
            $comprobar=$this->Cargo_gc_model->Create($insertar);
            if ($comprobar >=1){
                return 1;
            }
            else{
                //significa que no se realizo la operacion DML
                return 0;
            }                           
        } 
}