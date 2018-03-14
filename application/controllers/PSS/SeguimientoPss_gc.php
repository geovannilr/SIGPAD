<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SeguimientoPss_gc extends CI_Controller {

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
            or ($id_cargo_administrativo == '6' and ($id_cargo == '1' or $id_cargo == '4' ))
            or ($tipo == 'Estudiante' and ($tipo_estudiante == '2' or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
        
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/SeguimientoPss_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _SeguimientoPss_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormSeguimientoPss_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');
            
            /*Tabla temporal utilizada para alojar los estudiantes que no tienen servicio sociales inscritos pero si expediente*/
            $sql="TRUNCATE TABLE aux_estudiantes_con_exp_con_tmp_pss";   
            $this->db->query($sql);
            $sql="INSERT INTO aux_estudiantes_con_exp_con_tmp_pss SELECT * FROM view_estudiantes_con_exp_con_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('aux_estudiantes_con_exp_con_tmp_pss');
            if ($tipo == 'Estudiante'){
                $crud->where('id_due',$id_doc_est);    
            }            
            $crud->order_by('id_due','asc');
            $crud->set_relation('id_modalidad','pss_modalidad','modalidad');
            $crud->set_relation('id_servicio_social','pss_servicio_social','nombre_servicio_social');
            $crud->set_language('spanish');
            $crud->set_subject('Seguimiento de Servicios Sociales inscritos');   
            $crud->columns('id_due','nombre','apellido','dui','email','apertura_expediente_pss',
                'fecha_remision','carta_aptitud_pss','id_detalle_expediente','id_modalidad',
                'id_servicio_social','oficializacion','estado','fecha_inicio',
                'fecha_fin','horas_prestadas','cierre_modalidad');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_due','DUE')
                 ->display_as('nombre','Nombres de Estudiante')          
                 ->display_as('apellido','Apellidos de Estudiante') 
                 ->display_as('email','Email Estudiante') 
                 ->display_as('apertura_expediente_pss','Fecha de Apertura de Expediente de PSS') 
                 ->display_as('fecha_remision','Fecha de Remisión de Expediente de PSS') 
                 ->display_as('carta_aptitud_pss','Carta de Aptitud de Servicio Social') 
                 ->display_as('id_detalle_expediente','Cod. Expediente Servicio Social') 
                 ->display_as('id_modalidad','Modalidad Servicio Social') 
                 ->display_as('id_servicio_social','Servicio Social') 
                 ->display_as('oficializacion','¿Oficializado?') 
                 ->display_as('estado','Status Servicio Social') 
                 ->display_as('fecha_inicio','Fecha Inicio Servicio Social') 
                 ->display_as('fecha_fin','Fecha Cierre Servicio Social') 
                 ->display_as('horas_prestadas','Horas Prestadas') 
                 ->display_as('cierre_modalidad','¿Servicio Social Cerrado?');
                 //->display_as('observacion','Observaciones x Servicio Social'); 
            $crud->unset_fields('observacion');
            //Validacion de ca´mpos en Edit Forms
            /*$crud->edit_fields('id_due','nombre','apellido','dui',
                'email','apertura_expediente_pss','carta_aptitud_pss'); 
            
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            $crud->callback_edit_field('nombre',array($this,'nombreSoloLectura'));
            $crud->callback_edit_field('apellido',array($this,'apellidoSoloLectura'));
            $crud->callback_edit_field('email',array($this,'emailSoloLectura'));*/
            $crud->field_type('estado','dropdown',array('I' => 'Inscrito','O' => 'Oficializado','A' => 'Abandonado','C' => 'Cerrado'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO'); 
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

            $this->_SeguimientoPss_output($output);
    }

                        
 
 

}