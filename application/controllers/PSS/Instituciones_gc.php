<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instituciones_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='3'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/Instituciones_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _Instituciones_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormInstituciones_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {



            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pss_institucion');
            $crud->order_by('CAST(id_institucion AS UNSIGNED)','asc');
            $crud->set_relation('id_rubro','pss_rubro','rubro');
            $crud->set_language('spanish');
            $crud->set_subject('Instituciones');  
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_institucion','Cod. Institucion')
                 ->display_as('id_rubro','Rubro')
                 ->display_as('nombre','Nombre de Institución')
                 ->display_as('tipo','Tipo de Institución')
                 //->display_as('estado','Descripcion de Cargo Administrativo')
                 ->display_as('direccion','Dirección') 
                 ->display_as('telefono','Telefono')
                 ->display_as('email','Email'); 

            //Definiendo los input que apareceran en el formulario de inserción
            $crud->columns('id_institucion','id_rubro','nombre','tipo','nit','direccion','telefono','email');
            //Definiendo las columnas que noa apereceran
            $crud->unset_fields('estado');
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_rubro','nombre','tipo','nit','direccion','telefono','email');
            //Definiendo los campos obligatorios
            $crud->required_fields('id_rubro','nombre','tipo','nit','direccion','telefono','email');
            //Desabilitando  el wisywig
            /////$crud->unset_texteditor('descripcion','full_text');
            
            $crud->callback_insert(array($this,'insercion_Instituciones'));
            //Validacion de ca´mpos en Edit Forms
            $crud->field_type('tipo','dropdown',array('PRI' => 'Privado','PUB' => 'Público'));

            $output = $crud->render();

            $this->_Instituciones_output($output);
    }
     public function insercion_Instituciones($post_array){

            $insertar['id_rubro']=$post_array['id_rubro'];
            $insertar['nombre']=$post_array['nombre'];
            $insertar['tipo']=$post_array['tipo'];
            $insertar['nit']=$post_array['nit'];
            $insertar['direccion']=$post_array['direccion'];
            $insertar['telefono']=$post_array['telefono'];
            $insertar['email']=$post_array['email'];
            $comprobar=$this->Instituciones_gc_model->Create($insertar);
            if ($comprobar >=1){
                return 1;
            }
            else{
                //significa que no se realizo la operacion DML
                return 0;
            }                           
        } 


}