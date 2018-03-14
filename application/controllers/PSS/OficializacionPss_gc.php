<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OficializacionPss_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='3'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/OficializacionPss_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _OficializacionPss_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormOficializacionPss_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {


            /*Tabla temporal utilizada para alojar los la info de estudiants a los que les oficilziaran el servicio social*/
            $sql="TRUNCATE TABLE gen_oficializacion_tmp_pss";   
            $this->db->query($sql);
            $sql="INSERT INTO gen_oficializacion_tmp_pss SELECT * FROM view_gen_oficializacion_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('gen_oficializacion_tmp_pss');
            $crud->order_by('CAST(id_detalle_expediente AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_relation('id_servicio_social','pss_servicio_social','nombre_servicio_social');
            $crud->set_subject('Oficializacion de Servicios Sociales');  
            $crud->columns('id_detalle_expediente','id_servicio_social','id_due','estado','fecha_inicio','oficializacion','encabezado_oficializacion');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_detalle_expediente','Cod. Expediente de Servicio Social')
                 ->display_as('id_servicio_social','Servicio Social')          
                 ->display_as('id_due','DUE') 
                 ->display_as('estado','Estatus Expediente de Servicio Social') 
                 ->display_as('fecha_inicio','Fecha de Inicio de Oficializacion') 
                 ->display_as('oficializacion','¿Oficializado?') 
                 ->display_as('encabezado_oficializacion','Encabezado de Carta de Oficialización (Ayudantia,Pasantia,Proyecto)');

            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_detalle_expediente','id_servicio_social','id_due','estado','encabezado_oficializacion'); 
            
            $crud->callback_edit_field('id_detalle_expediente',array($this,'idDetalleExpSocialSoloLectura'));
            $crud->callback_edit_field('id_servicio_social',array($this,'idServicioSocialSoloLectura'));
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            /////////////// 
            $crud->field_type('estado','dropdown',array('I' => 'Inscrito','O' => 'Oficializado','A' => 'Abandonado','C' => 'Cerrado'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO'); 
            /////////////// 
            $crud->callback_edit_field('oficializacion',array($this,'oficializacionSoloLectura'));
            //Validaciones
            $crud->set_rules('estado','estado','callback_checar_estado');

            //Definiendo los campos obligatorios
            $crud->required_fields('estado','fecha_inicio');  

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();
            
            $crud->add_action('Carta de oficializacion','','','glyphicon glyphicon-download',array($this,'CartaOficializacion'));                             
            
            $crud->callback_update(array($this,'actualizacion_OficializacionPss'));

            $output = $crud->render();

            $this->_OficializacionPss_output($output);
    }
     function CartaOficializacion($primary_key )
    {
            //validar en base al id_detalle_expediente si el servicio social es de curso propeutico
            $modalidadSS=$this->OficializacionPss_gc_model->EncontrarModalidadSS($primary_key);
            //si la modalida del servicio social es de curso propedeutico
            if ($modalidadSS==2){
                return site_url('PSS/GenOficializacionCursoPropedeutico_gc/generar/'.$primary_key );
            }
            //si la modalidad del servicio social es disinto a curso propedeutico
            if ($modalidadSS!=2){
                return site_url('PSS/GenCartaOficializacion_gc/generar/'.$primary_key );
            }
            
    }
    //Validaciones de cambio de estado
   public function checar_estado($estado)
    {

       if($estado!='O')
       {

            $this->form_validation->set_message('checar_estado', "Solo se admite cambiar estado a OFICIALIZADO, favor validar");
            return FALSE;
    
        } 
       else 
       {
            //$this->form_validation->set_message('checar_nota_documento', "La nota del documento solo admite valores numericos");
            return TRUE;
       }
    }           
    //Para Edit forms  
    function idDetalleExpSocialSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_detalle_expediente" name="field-id_detalle_expediente" value="'.$value.'" readonly>';
    }       

    function idServicioSocialSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_servicio_social" name="field-id_servicio_social" value="'.$value.'" readonly>';
    }       
    function idDueSoloLectura($value){
        //$value=$this->AproDeneServicioSocial_gc_model->DatosContactoInstitucion($value);
        return '<input class="form-control" type="text" id="field-id_due" name="field-id_due" value="'.$value.'" readonly>';
    }   
    /*function idModalidadSoloLectura($value){
         if ($value=="1"){
            $value="Ayudantia";
         }
         if ($value=="2"){
            $value="Curso Propedeutico";
         }
         if ($value=="3"){
            $value="Pasantia";
         }
         if ($value=="4"){
            $value="Proyecto";
         }                           
        return '<input type="text" id="field-field_id_modalidad" name="field-field_id_modalidad" value="'.$value.'" readonly>';         
    }   */
    function oficializacionSoloLectura($value){
        return '<input class="form-control" type="text" id="field-oficializacion" name="field-oficializacion" value="'.$value.'" readonly>';
    }   
          
 
 public   function actualizacion_OficializacionPss($post_array){                      

            $update['id_detalle_expediente']=$post_array['field-id_detalle_expediente'];
            $update['id_servicio_social']=$post_array['field-id_servicio_social'];
            $update['estado']=$post_array['estado'];
            $update['encabezado_oficializacion']=$post_array['encabezado_oficializacion'];
            
            $comprobar=$this->OficializacionPss_gc_model->Update($update);
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
?>