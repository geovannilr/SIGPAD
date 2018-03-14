<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class IngresoDocumentacionPss_gc extends CI_Controller {

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
            $this->load->model('PSS/IngresoDocumentacionPss_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _IngresoDocumentacionPss_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormIngresoDocumentacionPss_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');

            /*Tabla temporal utilizada para alojar los conactos_x_intituciones*/
            $sql="TRUNCATE TABLE ingreso_documentacion_pss_tmp";   
            $this->db->query($sql);
            $sql="INSERT INTO ingreso_documentacion_pss_tmp SELECT * FROM view_ingreso_documentacion_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('ingreso_documentacion_pss_tmp');
            if ($tipo == 'Estudiante'){
                $crud->where('id_due',$id_doc_est);    
            }              
            $crud->order_by('CAST(id_detalle_expediente AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_relation('id_servicio_social','pss_servicio_social','nombre_servicio_social');
            $crud->set_subject('Ingreso de documentacion, horas servidas y otros');  
            $crud->columns('id_detalle_expediente','id_servicio_social','id_due','horas_prestadas',
                            'perfil_proyecto','plan_trabajo','informe_parcial','informe_final','memoria',
                            'control_actividades','carta_finalizacion_horas_sociales','lugar_trabajo',
                            'telefono_trabajo');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_detalle_expediente','Cod. Expediente de Servicio Social')
                 ->display_as('id_servicio_social','Servicio Social')          
                 ->display_as('id_due','DUE') 
                 ->display_as('horas_prestadas','Horas Prestadas') 
                 ->display_as('perfil_proyecto','Perfil de Trabajo') 
                 ->display_as('plan_trabajo','Plan de Trabajo') 
                 ->display_as('informe_parcial','Informe Parcial') 
                 ->display_as('informe_final','Informe Final') 
                 ->display_as('memoria','Memoria') 
                 ->display_as('control_actividades','Control de Actividades') 
                 ->display_as('carta_finalizacion_horas_sociales','Carta de Finalizacion de Horas Sociales')
                 ->display_as('lugar_trabajo','Lugar Trabajo')
                 ->display_as('telefono_trabajo','Telefono Trabajo');

            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_detalle_expediente','id_servicio_social','id_due','horas_prestadas',
                                'perfil_proyecto','plan_trabajo','informe_parcial','informe_final','memoria',
                                'control_actividades','carta_finalizacion_horas_sociales','lugar_trabajo',
                                'telefono_trabajo'); 
            
            $crud->callback_edit_field('id_detalle_expediente',array($this,'idDetalleExpSocialSoloLectura'));
            $crud->callback_edit_field('id_servicio_social',array($this,'idServicioSocialSoloLectura'));
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            
            //Definiendo los campos obligatorios
            $crud->required_fields('horas_prestadas','perfil_proyecto','plan_trabajo','informe_parcial',
                                    'informe_final','memoria','control_actividades',
                                    'carta_finalizacion_horas_sociales','lugar_trabajo',
                                    'telefono_trabajo');
            //Para subir un archivo
            $crud->set_field_upload('perfil_proyecto','assets/uploads/files');
            $crud->set_field_upload('plan_trabajo','assets/uploads/files');
            $crud->set_field_upload('informe_parcial','assets/uploads/files');
            $crud->set_field_upload('informe_final','assets/uploads/files');
            $crud->set_field_upload('memoria','assets/uploads/files');
            $crud->set_field_upload('control_actividades','assets/uploads/files');
            $crud->set_field_upload('carta_finalizacion_horas_sociales','assets/uploads/files');

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();
            
                           
            
            $crud->callback_update(array($this,'actualizacion_IngresoDocumentacionPss'));

            $output = $crud->render();

            $this->_IngresoDocumentacionPss_output($output);
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
  
 
 public   function actualizacion_IngresoDocumentacionPss($post_array){                      

            $update['id_detalle_expediente']=$post_array['field-id_detalle_expediente'];
            $update['id_servicio_social']=$post_array['field-id_servicio_social'];
            $update['horas_prestadas']=$post_array['horas_prestadas'];
            $update['perfil_proyecto']=$post_array['perfil_proyecto'];
            $update['plan_trabajo']=$post_array['plan_trabajo'];
            $update['informe_parcial']=$post_array['informe_parcial'];
            $update['informe_final']=$post_array['informe_final'];
            $update['memoria']=$post_array['memoria'];
            $update['control_actividades']=$post_array['control_actividades'];
            $update['carta_finalizacion_horas_sociales']=$post_array['carta_finalizacion_horas_sociales'];
            $update['lugar_trabajo']=$post_array['lugar_trabajo'];
            $update['telefono_trabajo']=$post_array['telefono_trabajo'];
            
            $comprobar=$this->IngresoDocumentacionPss_gc_model->Update($update);
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