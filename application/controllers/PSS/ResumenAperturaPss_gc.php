<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ResumenAperturaPss_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        $id_doc_est = $this->session->userdata('id_doc_est');
        $nombre = $this->session->userdata('nombre');
        $apellido = $this->session->userdata('apellidos');
        
        if($id_cargo_administrativo=='3' 
            or $id_cargo_administrativo =='1' 
            or ($id_cargo_administrativo == '6' and $id_cargo == '1')
            or ($tipo == 'Estudiante' and ($tipo_estudiante == '2'  or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
        
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/ResumenAperturaPss_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _ResumenAperturaPss_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormResumenAperturaPss_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');

            /*Tabla temporal utilizada para alojar los estudiantes que no tienen servicio sociales inscritos pero si expediente*/
            $sql="TRUNCATE TABLE aux_estudiantes_con_exp_sin_tmp_pss";   
            $this->db->query($sql);
            $sql="INSERT INTO aux_estudiantes_con_exp_sin_tmp_pss SELECT * FROM view_estudiantes_con_exp_sin_pss";
            $this->db->query($sql);


            

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('aux_estudiantes_con_exp_sin_tmp_pss');
            if ($tipo == 'Estudiante'){
                $crud->where('id_due',$id_doc_est);    
            }                
            $crud->order_by('id_due','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Expedientes de SS aperturados sin SS inscritos');  
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
            /*$crud->edit_fields('id_due','nombre','apellido','dui',
                'email','apertura_expediente_pss','carta_aptitud_pss'); 
            
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            $crud->callback_edit_field('nombre',array($this,'nombreSoloLectura'));
            $crud->callback_edit_field('apellido',array($this,'apellidoSoloLectura'));
            $crud->callback_edit_field('email',array($this,'emailSoloLectura'));*/
          
            //Definiendo los campos obligatorios
            /*$crud->required_fields('dui','apertura_expediente_pss','carta_aptitud_pss');  */

            //Para subir un archivo
            $crud->set_field_upload('carta_aptitud_pss','assets/uploads/files');   

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();            
            //quitar la opcion de edicion
            $crud->unset_edit();            

            $output = $crud->render();

            $this->_ResumenAperturaPss_output($output);
    }
    //Para Edit forms  
    /*function idDueSoloLectura($value){
        return '<input type="text" id="field-id_due" name="field-id_due" value="'.$value.'" readonly>';
    }       
    function nombreSoloLectura($value){
        return '<input type="text" id="field-nombre" name="field-nombre" value="'.$value.'" readonly>';
    }   

    function apellidoSoloLectura($value){
        return '<input type="text" id="field-apellido" name="field-apellido" value="'.$value.'" readonly>';
    }   
    function emailSoloLectura($value){
        return '<input type="text" id="field-email" name="field-email" value="'.$value.'" readonly>';
    }   */
                        
 
 

}