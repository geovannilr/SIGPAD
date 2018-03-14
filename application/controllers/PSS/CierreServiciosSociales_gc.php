<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CierreServiciosSociales_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        
        if($id_cargo_administrativo=='3'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/CierreServiciosSociales_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _CierreServiciosSociales_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormCierreServiciosSociales_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {


            /*Tabla temporal utilizada para alojar los conactos_x_intituciones*/
            $sql="TRUNCATE TABLE cierre_servicios_tmp_pss";   
            $this->db->query($sql);
            $sql="INSERT INTO cierre_servicios_tmp_pss SELECT * FROM view_cierre_servicios_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('cierre_servicios_tmp_pss');
            $crud->order_by('CAST(id_detalle_expediente AS UNSIGNED)','asc');
            $crud->set_primary_key('id_detalle_expediente');
            $crud->set_language('spanish');
            $crud->set_relation('id_servicio_social','pss_servicio_social','nombre_servicio_social');
            $crud->set_subject('Cierre de  Servicios Sociales');  
            $crud->columns('id_detalle_expediente','id_servicio_social','id_due','estado','fecha_inicio','fecha_fin','oficializacion','carta_finalizacion_horas_sociales');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_detalle_expediente','Cod. Expediente de Servicio Social')
                 ->display_as('id_servicio_social','Servicio Social')          
                 ->display_as('id_due','DUE') 
                 ->display_as('estado','Estatus Expediente de Servicio Social') 
                 ->display_as('fecha_inicio','Fecha de Inicio de Oficializacion') 
                 ->display_as('fecha_fin','Fecha de Finalizacion de Servicio Social') 
                 ->display_as('oficializacion','¿Oficializado?') 
                 ->display_as('carta_finalizacion_horas_sociales','Carta de Finalizacion de Servicio Social');

            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_detalle_expediente','id_servicio_social','id_due','estado','fecha_inicio','fecha_fin','oficializacion'); 
            
            $crud->callback_edit_field('id_detalle_expediente',array($this,'idDetalleExpSocialSoloLectura'));
            $crud->callback_edit_field('id_servicio_social',array($this,'idServicioSocialSoloLectura'));
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            /////////////// 
            $crud->field_type('estado','dropdown',array('I' => 'Inscrito','O' => 'Oficializado','A' => 'Abandonado','C' => 'Cerrado'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO'); 
            /////////////// 
            $crud->callback_edit_field('fecha_inicio',array($this,'fechaInicioSoloLectura'));
            $crud->callback_edit_field('oficializacion',array($this,'oficializacionSoloLectura'));
            //$crud->callback_edit_field('carta_finalizacion_horas_sociales',array($this,'cartaFinalizacionSoloLectura'));
            //Validaciones
            $crud->set_rules('estado','estado','callback_checar_estado');    

            $crud->set_field_upload('carta_finalizacion_horas_sociales','assets/uploads/files');

            //Definiendo los campos obligatorios
            $crud->required_fields('estado','fecha_fin');  

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();
                              
            
            $crud->callback_update(array($this,'actualizacion_OficializacionPss'));

            $output = $crud->render();

            $this->_CierreServiciosSociales_output($output);
    }
    //Validaciones de cambio de estado
   public function checar_estado($estado)
    {

       if(($estado=='A') or ($estado=='C'))
       {
            return TRUE;
    
        } 
       else 
       {
            //$this->form_validation->set_message('checar_nota_documento', "La nota del documento solo admite valores numericos");
            $this->form_validation->set_message('checar_estado', "Solo se admite cambiar estado a ABANDONADO ó CERRADO , favor validar");
            return FALSE;            
       }
    }  
    //Para Edit forms  
    function idDetalleExpSocialSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_detalle_expediente" name="field-id_detalle_expediente" value="'.$value.'" readonly>';
    }       

    function idServicioSocialSoloLectura($value){
        $value=$this->CierreServiciosSociales_gc_model->DatosServicioSocial($value);
        return '<input class="form-control" type="text" id="field-id_servicio_social" name="field-id_servicio_social" value="'.$value.'" readonly>';
    }       
    function idDueSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_due" name="field-id_due" value="'.$value.'" readonly>';
    }     
    function fechaInicioSoloLectura($value){
        return '<input class="form-control" type="text" id="field-fecha_inicio" name="field-fecha_inicio" value="'.$value.'" readonly>'; 
    }       
    function oficializacionSoloLectura($value){
        if ($value==1){
            $value='SI';
        }else{
            $value='NO';
        }
        return '<input class="form-control" type="text" id="field-oficializacion" name="field-oficializacion" value="'.$value.'" readonly>';
    }     
    /*function cartaFinalizacionSoloLectura($value){
        return '<a href="http://localhost/SIGPA/assets/uploads/files/8f69f-diseno-de-entradas-copia-2-.docx" id="file_1786256757" class="open-file" target="_blank">'.$value.'</a>';
    } */ 
        
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
 
          
 
 public   function actualizacion_OficializacionPss($post_array){                      

            $update['id_detalle_expediente']=$post_array['field-id_detalle_expediente'];
            $update['estado']=$post_array['estado'];
            $update['fecha_fin']=$this->CierreServiciosSociales_gc_model->cambiaf_a_mysql($post_array['fecha_fin']);
            
            $comprobar=$this->CierreServiciosSociales_gc_model->Update($update);
            if ($comprobar >=1){
                //$huboError=0;
                if ($update['estado']=='C'){
                    $correo_estudianteC=$this->CierreServiciosSociales_gc_model->ObtenerCorreoEstudiantePss($post_array['field-id_due']);                  
                    $nombre_apellidoC=$this->CierreServiciosSociales_gc_model->ObtenerNombreApellidoEstudiantePss($post_array['field-id_due']);                  
                    
                    //ENVIAR EMAIL a estudiante que se le esta cerradno el servicio social
                    $this->load->library('email');
                    $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                    $this->email->to($correo_estudianteC);
                    $this->email->subject('Notificación: Servicio Social Abandonado');
                    $this->email->message('<p>Estimad@ Br. '.$nombre_apellidoC.'</p>
                                            <p><br /> L@ felicit@ por concluir su Servicio Social "'.$post_array['field-id_servicio_social'].'"</p>
                                            <p><br /> Cuando se desea hacer un buen trabajo, no cuentan las horas que faltan sino dejar bien satisfecho<br /> al usuario. Excelente!!!</p>
                                            <p><br /> Las bit&aacute;coras est&aacute;n bien. Debe tra&eacute;rmelas firmadas y selladas aqu&iacute; a la EISI, si no me encuentra,<br /> me las puede dejar con la secretaria. <br /> Puede traerlas de lunes a viernes de 9am a 12m y en las tardes: de 2 a 4:30pm. Si desarroll&oacute; software, debe entregar en un CD la aplicaci&oacute;n incluyendo los manuales de instalaci&oacute;n y de usuario en forma digital y llenarme el formulario F3-5. Reenv&iacute;emelo ya completo a&nbsp;amsansv@yahoo.es <br /> <br /> Licda. Ang&eacute;lica Nuila de S&aacute;nchez<br /> Coordinadora de Proyecci&oacute;n Social EISI</p>');
                    $this->email->set_mailtype('html'); 
                    $this->email->send();                                  

                }
                if ($update['estado']=='A'){
                    $correo_estudianteA=$this->CierreServiciosSociales_gc_model->ObtenerCorreoEstudiantePss($post_array['field-id_due']);                  
                    
                    //ENVIAR EMAIL a estudiante que se le esta abandonando el servicio social
                    $this->load->library('email');
                    $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                    $this->email->to($correo_estudianteA);
                    $this->email->subject('Notificación: Servicio Social Abandonado');
                    $this->email->message('<p>El servicio social "'.$post_array['field-id_servicio_social'].'", ha caido en abandono, favor contactarse con la Coordinadora de Servicio Social.</p>');
                    $this->email->set_mailtype('html'); 
                    $this->email->send();                                  

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
?>