<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CargoAdmin_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='1'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('ADMINISTRATIVO/CargoAdmin_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('login');
        }
        
    }
    public function _CargoAdmin_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormCargoAdmin_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {



            $crud = new grocery_CRUD();
            $crud->unset_add();
            $crud->unset_delete();
            //Seteando la tabla o vista
            $crud->set_table('gen_cargo_administrativo');
            $crud->order_by('CAST(id_cargo_administrativo AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Cargos Administrativos');  
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_cargo_administrativo','Cod. Cargo Administrativo')
                 ->display_as('descripcion','Descripcion de Cargo Administrativo');            
            
            $crud->callback_field('descripcion',array($this,'descripcionSoloLectura'));

            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('descripcion');   
            //Definiendo los campos obligatorios
            $crud->required_fields('descripcion');   
            //Desabilitando  el wisywig
            $crud->unset_texteditor('descripcion','full_text');
            
            $crud->callback_insert(array($this,'insercion_CargoAdmin'));

            $output = $crud->render();

            $this->_CargoAdmin_output($output);
    }
    function descripcionSoloLectura($value){
        return '<textarea class="form-control" id="descripcion" name="descripcion">'.$value.'</textarea>';
    }       
     public function insercion_CargoAdmin($post_array){

            $insertar['descripcion']=$post_array['descripcion'];
            $comprobar=$this->CargoAdmin_gc_model->Create($insertar);
            if ($comprobar >=1){
                return 1;
            }
            else{
                //significa que no se realizo la operacion DML
                return 0;
            }                           
        } 


}