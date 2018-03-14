<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GenerarCartaRenuncia_gc extends CI_Controller {

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

        if($tipo == 'Estudiante' and ($tipo_estudiante == '2' or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7')){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/GenerarCartaRenuncia_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _GenerarCartaRenuncia_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormGenerarCartaRenuncia_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {


            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');

            /*Tabla temporal utilizada para alojar las cartas de renuncias*/
            $sql="TRUNCATE TABLE cartas_renuncia_tmp_pss";   
            $this->db->query($sql);
            $sql="INSERT INTO cartas_renuncia_tmp_pss SELECT * FROM view_cartas_renuncia_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('cartas_renuncia_tmp_pss');
            if ($tipo == 'Estudiante'){
                $crud->where('id_due',$id_doc_est);    
            }              
            $crud->order_by('CAST(id_detalle_expediente AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_relation('id_servicio_social','pss_servicio_social','nombre_servicio_social');
            $crud->set_subject('Generacion de Carta de Renuncia');  
            $crud->columns('id_detalle_expediente','id_servicio_social','id_due','estado','encabezado_carta_renuncia','motivos_renuncia');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_detalle_expediente','Cod. Expediente de Servicio Social')
                 ->display_as('id_servicio_social','Servicio Social')          
                 ->display_as('id_due','DUE') 
                 ->display_as('estado','Estatus Expediente de Servicio Social') 
                 ->display_as('encabezado_carta_renuncia','Encabezado de Carta de Renuncia') 
                 ->display_as('motivos_renuncia','Motivos de renuncia');

            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_detalle_expediente','id_servicio_social','id_due','estado','encabezado_carta_renuncia','motivos_renuncia'); 
            
            $crud->callback_edit_field('id_detalle_expediente',array($this,'idDetalleExpSocialSoloLectura'));
            $crud->callback_edit_field('id_servicio_social',array($this,'idServicioSocialSoloLectura'));
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            /////////////// 
            $crud->field_type('estado','dropdown',array('I' => 'Inscrito','O' => 'Oficializado','A' => 'Abandonado','C' => 'Cerrado'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO'); 
            /////////////// 
            //Validaciones
            $crud->set_rules('estado','estado','callback_checar_estado');                    
            //Definiendo los campos obligatorios
            $crud->required_fields('encabezado_carta_renuncia','motivos_renuncia');  

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();
            
            $crud->add_action('Carta de Renuncia','','','glyphicon glyphicon-download',array($this,'CartaRenuncia'));  
            
            $crud->callback_update(array($this,'actualizacion_GenerarCartaRenuncia'));

            $output = $crud->render();

            $this->_GenerarCartaRenuncia_output($output);
    }
     function CartaRenuncia($primary_key )
    {
            return site_url('PSS/GenCartaRenuncia_gc/generar/'.$primary_key );
    }
    //Validaciones de cambio de estado
   public function checar_estado($estado)
    {

       if($estado!='O')
       {

            $this->form_validation->set_message('checar_estado', "No se puede cambiar de estado OFICIALIZADO, favor validar");
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


 
 public   function actualizacion_GenerarCartaRenuncia($post_array){                      

            $update['id_detalle_expediente']=$post_array['field-id_detalle_expediente'];
            $update['id_servicio_social']=$post_array['field-id_servicio_social'];
            $update['encabezado_carta_renuncia']=$post_array['encabezado_carta_renuncia'];
            $update['motivos_renuncia']=$post_array['motivos_renuncia'];
            
            $comprobar=$this->GenerarCartaRenuncia_gc_model->Update($update);
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