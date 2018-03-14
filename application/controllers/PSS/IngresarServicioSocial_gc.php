<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class IngresarServicioSocial_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $id_doc_est = $this->session->userdata('id_doc_est');
        if($tipo == 'Contacto'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/IngresarServicioSocial_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }  
    }
    public function _IngresarServicioSocial_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormIngresarServicioSocial_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $id_doc_est = $this->session->userdata('id_doc_est');
            /*$sql="INSERT INTO tabla(valor) VALUES('".$id_doc_est."')";
            $this->db->query($sql); */


            /*Tabla temporal utilizada para alojar los conactos_x_intituciones*/
            $sql="TRUNCATE TABLE aux1_contacto_x_institucion_pss_tmp";   
            $this->db->query($sql);
            $sql="INSERT INTO aux1_contacto_x_institucion_pss_tmp SELECT * FROM view_contacto_x_intitucion_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pss_servicio_social');
            $crud->where('pss_servicio_social.id_contacto',$id_doc_est);
            $crud->order_by('CAST(id_servicio_social AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_relation('id_contacto','aux1_contacto_x_institucion_pss_tmp','nombre_apellido_contacto_e_institucion');
            $crud->set_relation('id_modalidad','pss_modalidad','modalidad');
            $crud->set_subject('Servicios Sociales');  
            $crud->columns('id_servicio_social','id_contacto','id_modalidad','nombre_servicio_social','cantidad_estudiante','disponibilidad','objetivo','importancia','presupuesto','logro','localidad_proyecto','beneficiario_directo','beneficiario_indirecto','descripcion','nombre_contacto_ss','email_contacto_ss');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_servicio_social','Cod. Servicio Social')
                 ->display_as('id_contacto','Contacto')          
                 ->display_as('id_modalidad','Modalidad') 
                 ->display_as('nombre_servicio_social','Nombre Servicio Social') 
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
                 ->display_as('email_contacto_ss','Email Contacto Direcito');
            
            $crud->callback_add_field('descripcion',array($this,'descripcionSoloLectura'));
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_contacto','id_modalidad','nombre_servicio_social','cantidad_estudiante','objetivo','importancia','presupuesto','logro','localidad_proyecto','beneficiario_directo','beneficiario_indirecto','descripcion','nombre_contacto_ss','email_contacto_ss');   
            //Definiendo las columnas que noa apereceran
            $crud->unset_fields('estado','estado_aprobacion');            
            //Definiendo los campos obligatorios
            $crud->required_fields('id_contacto','id_modalidad','nombre_servicio_social','cantidad_estudiante','objetivo','importancia','presupuesto','logro','localidad_proyecto','beneficiario_directo','beneficiario_indirecto','descripcion','nombre_contacto_ss','email_contacto_ss');  

            $crud->set_rules('presupuesto','presupuesto','callback_checar_presupuesto'); 
            $crud->set_rules('email_contacto_ss','email_contacto_ss','callback_checar_email_contacto_directo'); 

            //Desabilitando  el wisywig
            $crud->unset_texteditor('descripcion','full_text');

            //quitar la opcion de borrado
            $crud->unset_delete();
            //quitar la opcion de edicion
            $crud->unset_edit();
            
            $crud->callback_insert(array($this,'insercion_ServicioSocial'));

            $output = $crud->render();

            $this->_IngresarServicioSocial_output($output);
    }
    //Para Add forms
    /*function idDetalleExpedienteSoloLectura(){
        return '<input type="text" id="field-id_det_expediente" name="field-id_det_expediente" readonly>';
    }   */
    function descripcionSoloLectura($value){
        ///////$dues_iterar=$this->AsigDocenteAsesor_gc_model->BuscarDUEsEquipo($post_array['id_equipo_tg'])
 //       return '<input class="form-control" type="text" id="descripcion" name="descripcion">';
        return '<textarea class="form-control" id="descripcion" name="descripcion">'.$value.'</textarea>';
    }   

    //Para Edit forms
   public function checar_presupuesto($presupuesto)
    {

       if(is_numeric($presupuesto)) 
       {
            if ($presupuesto < 0 or $presupuesto > 10000)
            {
                $this->form_validation->set_message('checar_presupuesto', "El presupuesto asignado debe estar en el rango de 0 a 10000 dolares");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_presupuesto', "El presupuesto solo admite valores numericos");
            return FALSE;
       }
    }       
    //Validaciones de email
   public function checar_email_contacto_directo($email_contacto_ss)
    {
        if (filter_var($email_contacto_ss, FILTER_VALIDATE_EMAIL)) {
           return TRUE;
        }
        else{
            $this->form_validation->set_message('checar_email_contacto_directo', "El email ingresado no es valido");
            return FALSE;            
        }

    }     
     public function insercion_ServicioSocial($post_array){

            $insertar['id_contacto']=$post_array['id_contacto'];
            $insertar['id_modalidad']=$post_array['id_modalidad'];
            $insertar['nombre_servicio_social']=$post_array['nombre_servicio_social'];
            $insertar['cantidad_estudiante']=$post_array['cantidad_estudiante'];
            $insertar['objetivo']=$post_array['objetivo'];
            $insertar['importancia']=$post_array['importancia'];
            $insertar['presupuesto']=$post_array['presupuesto'];
            $insertar['logro']=$post_array['logro'];
            $insertar['localidad_proyecto']=$post_array['localidad_proyecto'];
            $insertar['beneficiario_directo']=$post_array['beneficiario_directo'];
            $insertar['beneficiario_indirecto']=$post_array['beneficiario_indirecto'];
            $insertar['descripcion']=$post_array['descripcion'];
            $insertar['nombre_contacto_ss']=$post_array['nombre_contacto_ss'];
            $insertar['email_contacto_ss']=$post_array['email_contacto_ss'];
            $comprobar=$this->IngresarServicioSocial_gc_model->Create($insertar);
            if ($comprobar >=1){
                return 1;
            }
            else{
                //significa que no se realizo la operacion DML
                return 0;
            }                           
        } 


}