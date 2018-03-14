<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AproDeneServicioSocial_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='3'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/AproDeneServicioSocial_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _AproDeneServicioSocial_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormAproDeneServicioSocial_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {


            /*Tabla temporal utilizada para alojar los conactos_x_intituciones*/
            $sql="TRUNCATE TABLE aux1_contacto_x_institucion_pss_tmp";   
            $this->db->query($sql);
            $sql="INSERT INTO aux1_contacto_x_institucion_pss_tmp SELECT * FROM view_contacto_x_intitucion_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pss_servicio_social');
            $crud->order_by('CAST(id_servicio_social AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_relation('id_contacto','aux1_contacto_x_institucion_pss_tmp','nombre_apellido_contacto_e_institucion');
            $crud->set_relation('id_modalidad','pss_modalidad','modalidad');
            $crud->set_subject('Aprobación/Denegación de Servicios Sociales');  
            $crud->columns('id_servicio_social','id_contacto','id_modalidad','nombre_servicio_social','estado_aprobacion','cantidad_estudiante','disponibilidad','objetivo','importancia','presupuesto','logro','localidad_proyecto','beneficiario_directo','beneficiario_indirecto','descripcion','nombre_contacto_ss','email_contacto_ss');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_servicio_social','Cod. Servicio Social')
                 ->display_as('id_contacto','Contacto')          
                 ->display_as('id_modalidad','Modalidad') 
                 ->display_as('nombre_servicio_social','Nombre Servicio Social') 
                 ->display_as('estado_aprobacion','Estado Aprobación Servicio Social') 
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

            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_servicio_social','id_contacto','id_modalidad','nombre_servicio_social','estado_aprobacion',
                'cantidad_estudiante','disponibilidad','objetivo','importancia','presupuesto','logro',
                'localidad_proyecto','beneficiario_directo','beneficiario_indirecto','descripcion',
                'nombre_contacto_ss','email_contacto_ss'); 
            
            //Definiendo las columnas que noa apereceran
            $crud->unset_fields('estado','estado_aprobacion');    

            $crud->callback_edit_field('id_servicio_social',array($this,'idServicioSocialSoloLectura'));
            $crud->callback_edit_field('id_contacto',array($this,'idContactoSoloLectura'));
            $crud->callback_edit_field('id_modalidad',array($this,'idModalidadSoloLectura'));
            $crud->callback_edit_field('nombre_servicio_social',array($this,'nombreServicioSocialSoloLectura'));
            /////////////// 
            $crud->field_type('estado_aprobacion','dropdown',array('A' => 'Aprobado','D' => 'Desaprobado'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO'); 
            /////////////// 
            $crud->callback_edit_field('cantidad_estudiante',array($this,'cantidadEstudianteSoloLectura'));
            $crud->callback_edit_field('disponibilidad',array($this,'disponibilidadSoloLectura'));
            $crud->callback_edit_field('objetivo',array($this,'objetivoSoloLectura'));
            $crud->callback_edit_field('importancia',array($this,'importanciaSoloLectura'));
            $crud->callback_edit_field('presupuesto',array($this,'presupuestoSoloLectura'));
            $crud->callback_edit_field('logro',array($this,'logroSoloLectura'));
            $crud->callback_edit_field('localidad_proyecto',array($this,'localidadProyectoSoloLectura'));
            $crud->callback_edit_field('beneficiario_directo',array($this,'beneficiarioDirectoSoloLectura'));
            $crud->callback_edit_field('beneficiario_indirecto',array($this,'beneficiarioIndirectoSoloLectura'));
            $crud->callback_edit_field('descripcion',array($this,'descripcionSoloLectura'));
            $crud->callback_edit_field('nombre_contacto_ss',array($this,'nombreContactoSSSoloLectura'));
            $crud->callback_edit_field('email_contacto_ss',array($this,'emailContactoSoloLectura'));


            //Definiendo los campos obligatorios
            $crud->required_fields('estado_aprobacion');  
            //Desabilitando  el wisywig
            $crud->unset_texteditor('descripcion','full_text');

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_AproDeneServicioSocial'));

            $output = $crud->render();

            $this->_AproDeneServicioSocial_output($output);
    }
    //Para Edit forms  
    function idServicioSocialSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_servicio_social" name="field-id_servicio_social" value="'.$value.'" readonly>';
    }       
    function idContactoSoloLectura($value){
        $value=$this->AproDeneServicioSocial_gc_model->DatosContactoInstitucion($value);
        return '<input class="form-control" type="text" id="field-id_contacto" name="field-id_contacto" value="'.$value.'" readonly>';
    }   
    function idModalidadSoloLectura($value){
         if ($value=="1"){
            $value="Ayudantia";
         }
         if ($value=="2"){
            $value="Curso Propedeutico";
         }
         if ($value=="3"){
            $value="Pasantia" ;
         }
         if ($value=="4"){
            $value="Proyecto";
         }                           
        return '<input class="form-control" type="text" id="field-field_id_modalidad" name="field-field_id_modalidad" value="'.$value.'" readonly>';         
    }   
    function nombreServicioSocialSoloLectura($value){
        return '<input class="form-control" type="text" id="field-nombre_servicio_social" name="field-nombre_servicio_social" value="'.$value.'" readonly>';
    }   
   //Para Edit forms
    function cantidadEstudianteSoloLectura($value){
        return '<input class="form-control" type="text" id="field-cantidad_estudiante" name="field-cantidad_estudiante" value="'.$value.'" readonly>';
    }   
    function disponibilidadSoloLectura($value){
        return '<input class="form-control" type="text" id="field-cantidad_estudiante" name="field-cantidad_estudiante" value="'.$value.'" readonly>';
    }       
    function objetivoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-objetivo" name="field-objetivo" value="'.$value.'" readonly>';
    }   
   //Para Edit forms
    function importanciaSoloLectura($value){
        return '<input class="form-control" type="text" id="field-importancia" name="field-importancia" value="'.$value.'" readonly>';
    }   
    function presupuestoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-presupuesto" name="field-presupuesto" value="'.$value.'" readonly>';
    }   
   //Para Edit forms
    function logroSoloLectura($value){
        return '<input class="form-control" type="text" id="field-logro" name="field-logro" value="'.$value.'" readonly>';
    }   
    function localidadProyectoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-localidad" name="field-localidad" value="'.$value.'" readonly>';
    }   
   //Para Edit forms
    function beneficiarioDirectoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-beneficiario_directo" name="field-beneficiario_directo" value="'.$value.'" readonly>';
    }   
    function beneficiarioIndirectoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-beneficiario_indirecto" name="field-beneficiario_indirecto" value="'.$value.'" readonly>';
    }       
    function descripcionSoloLectura($value){
        return '<textarea class="form-control" id="field-descripcion" name="field-descripcion" readonly>'.$value.'</textarea>';
    }       
    function nombreContactoSSSoloLectura($value){
        return '<input class="form-control" type="text" id="field-nombre_contacto_ss" name="field-nombre_contacto_ss" value="'.$value.'" readonly>';
    }       
    function emailContactoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-email_contacto_ss" name="field-email_contacto_ss" value="'.$value.'" readonly>';
    }                               
 
 public   function actualizacion_AproDeneServicioSocial($post_array){                      

            $update['id_servicio_social']=$post_array['field-id_servicio_social'];
            $update['estado_aprobacion']=$post_array['estado_aprobacion'];
            $update['nombre_servicio_social']=$post_array['field-nombre_servicio_social'];
            
            $comprobar=$this->AproDeneServicioSocial_gc_model->Update($update);
            if ($comprobar >=1){
                //$huboError=0;
                if ($update['estado_aprobacion']=='A'){
                    $correo_estudiante=$this->AproDeneServicioSocial_gc_model->ObtenerCorreoEstudiantePss();
                    foreach ($correo_estudiante as $row)
                    {
                            $correo=$row['email'];
                            //ENVIAR EMAIL a estudiantes que esten asociados a PSS y que no tenhgan cerrado el expediente de servicio social
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correo);
                            $this->email->subject('Notificación: Nuevo Servicio Social aprobado por Sub-Unidad de Proyección Social');
                            $this->email->message('<p>Se ha agregado el Servicio Social "'.$update['nombre_servicio_social'].'", mas información puede ser vista en el SIGPA.</p>');
                            $this->email->set_mailtype('html'); 
                            $this->email->send();                        
                    }          
                }
            return true;       



            }
            else{
                //significa que no se realizo la operacion DML
                //$huboError=1;
                return false;
            }                    

        }

}