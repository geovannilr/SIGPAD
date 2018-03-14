<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AproDeneProrro_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='4'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/AproDeneProrro_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _AproDeneProrro_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormAproDeneProrro_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $sql="TRUNCATE TABLE pdg_apro_dene_prorro_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_apro_dene_prorro_temp SELECT * FROM view_apro_dene_prorro_pdg";
            $this->db->query($sql);

            //obteniendo el año actual
            $fecha_actual=date ("Y"); 
            $crud = new grocery_CRUD();
            $crud->set_table('pdg_apro_dene_prorro_temp');
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            $crud->set_primary_key('id_equipo_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('anio_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('id_solicitud_academica'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->order_by('id_solicitud_academica','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Aprobación/Denegación de Solicitud de Prorroga de TG');
            //Definiendo las columnas que apareceran
            $crud->columns('id_solicitud_academica','ciclo','anio','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado','ingresado_x_equipo','generado_x_coordinador_pdg','ruta');
            //Cambiando nombre a los labels de los campos
           $crud->display_as('id_solicitud_academica','Id. Solicitud Académica')
                 ->display_as('ciclo','Ciclo Sol. Academica')
                 ->display_as('anio','Año Sol. Académica')
                 ->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('estado','Estado Solicitud')
                 ->display_as('ingresado_x_equipo','¿ingresado por Equipo TG?')
                 ->display_as('generado_x_coordinador_pdg','¿Generado por Coordinador TG?')
                 ->display_as('ruta','Acuerdo de Solicitud de Prorroga');
           
            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_solicitud_academica','ciclo','anio','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado','ruta'); 
            $crud->callback_edit_field('id_solicitud_academica',array($this,'idSolicitudAcademicaSoloLectura'));
            $crud->callback_edit_field('ciclo',array($this,'cicloSoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tesis',array($this,'anioTGSoloLectura'));
            $crud->callback_edit_field('ciclo_tesis',array($this,'cicloTGSoloLectura'));
            $crud->field_type('estado','dropdown',array('A' => 'Aprobado','D' => 'Denegado'));
            //Validación de requeridos
            $crud->required_fields('estado','ruta');
            //Para subir un archivo
            $crud->set_field_upload('ruta','assets/uploads/files');    
            
            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_AproDeneProrro'));

            $output = $crud->render();

            $this->_AproDeneProrro_output($output);
    }

   
    //Para Edit forms
    function idSolicitudAcademicaSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_solicitud_academica" name="field-id_solicitud_academica" value="'.$value.'" readonly>';
    }   
    function cicloSoloLectura($value){
        return '<input class="form-control" type="text" id="field-ciclo" name="field-ciclo" value="'.$value.'" readonly>';
    }   
    function idEquipoTgSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_equipo_tg" name="field-id_equipo_tg" value="'.$value.'" readonly>';
    }   
    function agrandaCajaTexto($value){
        return '<textarea class="form-control" id="field-tema_tesis" name="field-tema_tesis" readonly>'.$value.'</textarea>';
    }   
    function anioTGSoloLectura($value){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" value="'.$value.'" readonly>';
    }   
    function cicloTGSoloLectura($value){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" value="'.$value.'" readonly>';
    }       
   
    public   function actualizacion_AproDeneProrro($post_array){
           //Validando el estado
            if ($post_array['estado']=='A'){
                //$estado='A';
                /*Definiendo el tipo documento que se le asginara dependiendo del estado seleccionado por el usuario
                10->Memorandum de Aprobacion de Cambio de Nombre    MACN*/
                
                $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
                WHERE siglas IN ('MAPR')";
                /*WHERE siglas IN ('ADPPR')";*/
                $query=$this->db->query($sql);
                foreach ($query->result_array() as $row)
                {
                        $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];

                }            

            }
            if ($post_array['estado']=='D'){
                //$estado='D';
                /*Definiendo el tipo documento que se le asginara dependiendo del estado seleccionado por el usuario
                11->Memorandum de Denegación de Cambio de Nombre    MDCN*/
                $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
                WHERE siglas IN ('MDPR')";  
                /*WHERE siglas IN ('ADPPR')";*/
                $query=$this->db->query($sql);
                foreach ($query->result_array() as $row)
                {
                        $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];

                }   
            }
                         
            $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
            $update['anio_tg']=$post_array['field-anio_tesis'];
            $update['ciclo_tg']=$post_array['field-ciclo_tesis'];        
                           
            $update['id_solicitud_academica']=$post_array['field-id_solicitud_academica'];
            $update['estado']=$post_array['estado'];
            $update['ruta']=$post_array['ruta'];
            $update['id_tipo_documento']=$id_tipo_documento_pdg;
            
            $comprobar=$this->AproDeneProrro_gc_model->Update($update);
            if ($comprobar >=1){
                //$huboError=0;
                if ($update['estado']=='A'){
                    $correo_estudianteA=$this->AproDeneProrro_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tg'],$update['ciclo_tg']);
                    foreach ($correo_estudianteA as $row)
                    {
                            $correoA=$row['email'];
                            //ENVIAR EMAIL a estudiantes que integran el equipo al que se les aprobo la prorroga de TG
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoA);
                            $this->email->subject('Notificación: Aprobacion de Prorroga de Trabajo de Graduación');
                            $this->email->message('<p>Se ha APROBADO la solicitud de Prorroga de Trabajo de Graduación cuyo Tema es: '.$post_array['field-tema_tesis'].'</p>');
                            $this->email->set_mailtype('html'); 
                            $this->email->send();                        
                    }          
                }    
                if ($update['estado']=='D'){
                    $correo_estudianteD=$this->AproDeneProrro_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tg'],$update['ciclo_tg']);
                    foreach ($correo_estudianteD as $row)
                    {
                            $correoD=$row['email'];
                            //ENVIAR EMAIL a estudiantes que integran el equipo al que se les reprobo  la prorroga de tg
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoD);
                            $this->email->subject('Notificación: Denegación de Prorroga de Trabajo de Graduación');
                            $this->email->message('<p>Se ha DENEGADO la solicitud de Prorroga de Trabajo de Graduación cuyo Tema es: '.$post_array['field-tema_tesis'].'</p>');
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