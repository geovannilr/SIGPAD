<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CierreExpedientePss_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='3'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/CierreExpedientePss_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _CierreExpedientePss_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormCierreExpedientePss_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {


            /*Tabla temporal utilizada para alojar los expedientes que tienen 500 o mas horas de servicio*/
            $sql="TRUNCATE TABLE cierre_expediente_tmp_pss";   
            $this->db->query($sql);
            $sql="INSERT INTO cierre_expediente_tmp_pss SELECT * FROM view_cierre_expediente_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('cierre_expediente_tmp_pss');
            $crud->order_by('id_due','asc');
            $crud->set_primary_key('id_due');
            $crud->set_language('spanish');
////////            $crud->set_relation('id_servicio_social','pss_servicio_social','nombre_servicio_social');
            $crud->set_subject('Cierre de Expediente de Servicio Social');  
            //////$crud->columns('id_due','remision','fecha_remision','total_horas');
            $crud->columns('id_due','total_horas','observaciones_exp_pss');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_due','DUE')
                 //->display_as('remision','¿Remitido?')          
                 ->display_as('fecha_remision','Fecha de Remisión') 
                 ->display_as('total_horas','Total de Horas Servidas')
                 ->display_as('observaciones_exp_pss','Observaciones sobre Expediente de Servicio Social');

            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_due','fecha_remision','total_horas');
            
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            $crud->callback_edit_field('total_horas',array($this,'totalHorasSoloLectura'));
    
            /*$crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO'); */
            /////////////// 
      

            //Definiendo los campos obligatorios
            $crud->required_fields('fecha_remision','observaciones_exp_pss');  

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();
                              
            
            $crud->callback_update(array($this,'actualizacion__CierreExpedientePss'));

            $output = $crud->render();

            $this->_CierreExpedientePss_output($output);
    }

    //Para Edit forms  
    function idDueSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_due" name="field-id_due" value="'.$value.'" readonly>';
    }       

    function totalHorasSoloLectura($value){
        return '<input class="form-control" type="text" id="field-total_horas" name="field-total_horas" value="'.$value.'" readonly>';
    }       
    

          
 
 public   function actualizacion__CierreExpedientePss($post_array){                      

            $update['id_due']=$post_array['field-id_due'];
            $update['fecha_remision']=$this->CierreExpedientePss_gc_model->cambiaf_a_mysql($post_array['fecha_remision']);
            $update['observaciones_exp_pss']=$post_array['observaciones_exp_pss'];
           
            $comprobar=$this->CierreExpedientePss_gc_model->Update($update);
            if ($comprobar >=1){
                //$huboError=0;
                $correo_estudiante=$this->CierreExpedientePss_gc_model->ObtenerCorreoEstudiantePss($post_array['field-id_due']);                  
                
                //ENVIAR EMAIL a estudiante que se le esta cerrando el expediente de servicio social
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                $this->email->to($correo_estudiante);
                $this->email->subject('Notificación: Cierre de Expediente de Servicio Social');
                $this->email->message('<p>Se ha cerrado el expediente de servicio social asociado a su carnet: '.$post_array['field-id_due'].'<br />
                                        Fecha Remisi&oacute;n Expediente: '.$this->ControlAsesorias_gc_model->cambiaf_a_mysql($post_array['fecha_remision']).'</p>
                                        <p>Llamar por tel&eacute;fono al 2235-0235 que es la Unidad de Proyecci&oacute;n Social <br /> <br /> <br /> <br />(UPS) de la Facultad de Ingenier&iacute;a y Arquitectura (FIA), con la Sra.<br />Guadalupe Herrera para preguntar si ya est&aacute; listo el Certificado de Servicio<br />Social para pasar a recogerlo al final de las 2da planta del Edificio<br />Administrativo de la FIA.</p>
                                        <p>Realizar llamada una semana habil despues de recibir este correo electronico.</p>');
                $this->email->set_mailtype('html');                
                $this->email->send();                                  
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