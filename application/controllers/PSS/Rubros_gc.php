<!-- Creado por: RMORAN -->
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rubros_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='3'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/Rubros_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _Rubros_gc_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormRubros_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {



            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pss_rubro');
            $crud->order_by('CAST(id_rubro AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Rubros');  
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_rubro','Id. Rubro')
                 ->display_as('rubro','Rubro');            
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('rubro');   
            //Definiendo los campos obligatorios
            $crud->required_fields('rubro');   
            //Desabilitando  el wisywig
            $crud->unset_texteditor('rubro','full_text');
            
            $crud->callback_insert(array($this,'insercion_Rubro'));

            $output = $crud->render();

            $this->_Rubros_gc_output($output);
    }
     public function insercion_Rubro($post_array){

            $insertar['rubro']=$post_array['rubro'];
            $comprobar=$this->Rubros_gc_model->Create($insertar);
            if ($comprobar >=1){
                return 1;
            }
            else{
                //significa que no se realizo la operacion DML
                return 0;
            }                           
        } 


}