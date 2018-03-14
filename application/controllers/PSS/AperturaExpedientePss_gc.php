<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AperturaExpedientePss_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='3'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/AperturaExpedientePss_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _AperturaExpedientePss_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormAperturaExpedientePss_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            /*Tabla temporal utilizada para alojar los candidatos para apeturarles el expediente de pss*/
            $sql="TRUNCATE TABLE aux_candidatos_para_aperturar_tmp_pss";   
            $this->db->query($sql);
            $sql="INSERT INTO aux_candidatos_para_aperturar_tmp_pss SELECT * FROM view_candidatos_para_aperturar_pss";
            $this->db->query($sql);


            

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('aux_candidatos_para_aperturar_tmp_pss');
            
            $crud->order_by('id_due','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Expediente Servicio Social');  
            $crud->columns('id_due','nombre','apellido','dui','email','apertura_expediente_pss','carta_aptitud_pss');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_due','DUE')
                 ->display_as('nombre','Nombres de Estudiante')          
                 ->display_as('apellido','Apellidos de Estudiante') 
                 ->display_as('dui','DUI') 
                 ->display_as('email','Correo de Estudiante') 
                 ->display_as('apertura_expediente_pss','Fecha de apertura de Expediente de Servicio Social') 
                 ->display_as('carta_aptitud_pss','Constancia de Aptitud de Servicio Social');

            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_due','nombre','apellido','dui',
                'email','apertura_expediente_pss','carta_aptitud_pss'); 
            
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            $crud->callback_edit_field('nombre',array($this,'nombreSoloLectura'));
            $crud->callback_edit_field('apellido',array($this,'apellidoSoloLectura'));
            $crud->callback_edit_field('email',array($this,'emailSoloLectura'));
          
            //Definiendo los campos obligatorios
            //$crud->required_fields('dui','apertura_expediente_pss','carta_aptitud_pss');  
            $crud->required_fields('apertura_expediente_pss','carta_aptitud_pss');  

            //Para subir un archivo
            $crud->set_field_upload('carta_aptitud_pss','assets/uploads/files');   

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_AproDeneServicioSocial'));

            $output = $crud->render();

            $this->_AperturaExpedientePss_output($output);
    }
    //Para Edit forms  
    function idDueSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_due" name="field-id_due" value="'.$value.'" readonly>';
    }       
    function nombreSoloLectura($value){
        return '<input class="form-control" type="text" id="field-nombre" name="field-nombre" value="'.$value.'" readonly>';
    }   

    function apellidoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-apellido" name="field-apellido" value="'.$value.'" readonly>';
    }   
    function emailSoloLectura($value){
        return '<input class="form-control" type="text" id="field-email" name="field-email" value="'.$value.'" readonly>';
    }   
                        
 
 public   function actualizacion_AproDeneServicioSocial($post_array){                      


            $update['dui']=$post_array['dui'];
            $update['apertura_expediente_pss']=$this->AperturaExpedientePss_gc_model->cambiaf_a_mysql($post_array['apertura_expediente_pss']);
            $update['carta_aptitud_pss']=$post_array['carta_aptitud_pss'];
            $update['id_due']=$post_array['field-id_due'];
            
            $comprobar=$this->AperturaExpedientePss_gc_model->Update($update);
            if ($comprobar >=1){
                $huboError=0;
                //return true;
            }
            else{
                //significa que no se realizo la operacion DML
                $huboError=1;
                //return false;
            }                    

        }

}