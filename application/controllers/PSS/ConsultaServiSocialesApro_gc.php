<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConsultaServiSocialesApro_gc extends CI_Controller {

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
            or ($tipo == 'Estudiante' and ($tipo_estudiante == '2' or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))
            or ($tipo == 'Contacto')) {

            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/ConsultaServiSocialesApro_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _ConsultaServiSocialesApro_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormConsultaServiSocialesApro_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');

            /*Tabla temporal utilizada para alojar los conactos_x_intituciones*/
            $sql="TRUNCATE TABLE aux1_contacto_x_institucion_pss_tmp";   
            $this->db->query($sql);
            $sql="INSERT INTO aux1_contacto_x_institucion_pss_tmp SELECT * FROM view_contacto_x_intitucion_pss";
            $this->db->query($sql);

            /*Tabla temporal utilizada para alojar los conactos_x_intituciones*/
            $sql="TRUNCATE TABLE aux_servicio_social_pss_tmp";   
            $this->db->query($sql);
            $sql="INSERT INTO aux_servicio_social_pss_tmp SELECT * FROM view_servicio_social_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('aux_servicio_social_pss_tmp');
            if ($tipo == 'Contacto'){
                $crud->where('aux_servicio_social_pss_tmp.id_contacto',$id_doc_est);    
            }
            $crud->order_by('CAST(id_servicio_social AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_relation('id_contacto','aux1_contacto_x_institucion_pss_tmp','nombre_apellido_contacto_e_institucion');
            $crud->set_relation('id_modalidad','pss_modalidad','modalidad');
            $crud->set_subject('Servicios Sociales');  
            //////$crud->columns('id_servicio_social','id_contacto','id_modalidad','nombre_servicio_social','estado_aprobacion','cantidad_estudiante','disponibilidad','objetivo','importancia','presupuesto','logro','localidad_proyecto','beneficiario_directo','beneficiario_indirecto','descripcion','nombre_contacto_ss','email_contacto_ss');
            $crud->columns('id_servicio_social','id_contacto','id_modalidad','nombre_servicio_social','cantidad_estudiante','disponibilidad','objetivo','importancia','presupuesto','logro','localidad_proyecto','beneficiario_directo','beneficiario_indirecto','descripcion','nombre_contacto_ss','email_contacto_ss');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_servicio_social','Cod. Servicio Social')
                 ->display_as('id_contacto','Contacto')          
                 ->display_as('id_modalidad','Modalidad') 
                 ->display_as('nombre_servicio_social','Nombre Servicio Social') 
                 //->display_as('estado_aprobacion','Estado Aprobación Servicio Social') 
                 ->display_as('cantidad_estudiante','Cantidad de Estudiantes requeridos') 
                 ->display_as('disponibilidad','Disponibilidad de Cupos') 
                 ->display_as('objetivo','Objetivo del Servicio Social') 
                 ->display_as('importancia','Importancia') 
                 ->display_as('presupuesto','Presupuesto ($)') 
                 ->display_as('logro','Logro')
                 ->display_as('localidad_proyecto','Localidad del proyecto') 
                 ->display_as('beneficiario_directo','Cantidad de Beneficicarios Directos') 
                 ->display_as('beneficiario_indirecto','Cantidad Benficiarios Indirectos') 
                 ->display_as('descripcion','Descripcion Servicio Social') 
                 ->display_as('nombre_contacto_ss','Nombre Contacto Directo')
                 ->display_as('email_contacto_ss','Email Contacto Directo');

            ////$crud->field_type('estado_aprobacion','dropdown',array('A' => 'Aprobado','D' => 'Desaprobado'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO'); 

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();
            //quitar la opcion de edicion
            $crud->unset_edit();
            //quitar la opcion de lupa
            $crud->unset_read();
            $output = $crud->render();

            $this->_ConsultaServiSocialesApro_output($output);
    }
                             
 


}