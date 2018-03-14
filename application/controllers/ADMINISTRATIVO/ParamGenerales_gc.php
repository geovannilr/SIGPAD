<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ParamGenerales_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='1'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('ADMINISTRATIVO/ParamGenerales_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('login');
        }
    }
    public function _ParamGenerales_gc_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormParamGenerales_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $crud = new grocery_CRUD();
            //$crud->set_theme('datatables');
            //Seteando la tabla o vista
            $crud->set_table('cat_parametro_general');
            $crud->order_by('parametro');
            $crud->set_language('spanish');         
            $crud->set_subject('Parametros Generales');  
            $crud->unset_delete();
            //Cambiando nombre a los labels de los campos
            $crud->display_as('parametro','Parametro')            
                 ->display_as('descripcion','Descripción')  
                 ->display_as('valor','Valor Parametro');   
            $crud->unset_texteditor('descripcion','full_text');

            $crud->callback_field('parametro',array($this,'parametroSoloLectura'));
            $crud->callback_field('descripcion',array($this,'descripcionSoloLectura'));

            $output = $crud->render();

            $this->_ParamGenerales_gc_output($output);
    }
    //Para Edit forms

    function parametroSoloLectura($value){
        return '<input class="form-control" type="text" id="field-parametro" name="field-parametro" value="'.$value.'" readonly>';
    }
    function descripcionSoloLectura($value){
        return '<textarea class="form-control" id="descripcion" name="descripcion">'.$value.'</textarea>';
    }           
}

?>
