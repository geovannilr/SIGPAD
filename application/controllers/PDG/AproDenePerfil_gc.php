<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AproDenePerfil_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='4'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/AproDenePerfil_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');

        }
    }
    public function _AproDenePerfil_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormAproDenePerfil_gc';
        $this->load->view('templates/content',$data);
                      
    }


    public function index()
    {
             /*$sql="TRUNCATE TABLE pdg_apro_dene_perfil_temp"; 
                $this->db->query($sql);
                $sql="INSERT INTO pdg_apro_dene_perfil_temp SELECT * FROM view_apro_dene_perfil_pdg";
                $this->db->query($sql);*/

            /*Resentear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE pdg_apro_dene_perfil_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_apro_dene_perfil_temp(id,id_equipo_tg,id_detalle_pdg,id_perfil,anio_tesis,ciclo_tesis,tema_tesis,perfil_ingresado_x_equipo,
                                                    entrega_copia_perfil,estado_perfil,observaciones_perfil,numero_acta_perfil,punto_perfil,acuerdo_perfil,fecha_aprobacion_perfil,ruta)
                  SELECT func_inc_var_session(),id_equipo_tg,id_detalle_pdg,id_perfil,anio_tesis,ciclo_tesis,tema_tesis,perfil_ingresado_x_equipo,
                         entrega_copia_perfil,estado_perfil,observaciones_perfil,numero_acta_perfil,punto_perfil,acuerdo_perfil,fecha_aprobacion_perfil,ruta
                         FROM view_apro_dene_perfil_pdg";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //$crud->columns('id_perfil','ciclo','anio','objetivo_general','objetivo_especifico','descripcion');
            
            //Seteando la tabla o vista
            $crud->set_table('pdg_apro_dene_perfil_temp');//$crud->set_table('view_perfil');
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            //$crud->where('anio',2016); si se desea incorporar un where a la tabla/vista
            /////////$crud->set_primary_key('id_equipo_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            ////////$crud->set_primary_key('anio_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            ////////$crud->set_primary_key('id_detalle_pdg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('id');
            $crud->set_subject('Aprobación-Denegación de Perfiles');
            $crud->set_language('spanish');
               
            /////$crud->columns('id','id_equipo_tg','tema_tesis','anio_tesis','estado_perfil','observaciones_perfil','perfil_ingresado_x_equipo','entrega_copia_perfil','numero_acta_perfil','punto_perfil','acuerdo_perfil','fecha_aprobacion_perfil','ruta');
            $crud->columns('id','id_perfil','id_equipo_tg','tema_tesis','anio_tesis','estado_perfil','observaciones_perfil','numero_acta_perfil','punto_perfil','acuerdo_perfil','fecha_aprobacion_perfil','ruta');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id','Id')
                 ->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('estado_perfil','Estado Solicitud')
                 ->display_as('observaciones_perfil','Observaciones Perfil')
                 /*->display_as('perfil_ingresado_x_equipo','¿Perfil ingresado por Equipo?')
                 ->display_as('entrega_copia_perfil','¿Entrega copia a Doc. Asesor?')*/
                 ->display_as('numero_acta_perfil','Acta °')
                 ->display_as('punto_perfil','Punto')
                 ->display_as('acuerdo_perfil','Acuerdo')
                 ->display_as('fecha_aprobacion_perfil','Fecha Aprobación Perfil')
                 ->display_as('ruta','Acta Aprobación Perfil');
           
            //Validacion de ca´mpos en Edit Forms
            //$crud->edit_fields('id','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado_perfil','observaciones_perfil','perfil_ingresado_x_equipo','entrega_copia_perfil','numero_acta_perfil','punto_perfil','acuerdo_perfil','fecha_aprobacion_perfil','ruta'); 
            $crud->edit_fields('id','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado_perfil','observaciones_perfil','numero_acta_perfil','punto_perfil','acuerdo_perfil','fecha_aprobacion_perfil','ruta'); 
            $crud->callback_edit_field('id',array($this,'idSoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tesis',array($this,'anioTesisSoloLectura'));
            $crud->callback_edit_field('ciclo_tesis',array($this,'cicloTesisSoloLectura'));;
            $crud->callback_edit_field('observaciones_perfil',array($this,'observacionesPerfilAgrandado'));

            $crud->field_type('estado_perfil','dropdown',array('A' => 'Aprobado','D' => 'Denegado','E'=>'En curso de aprobacion','O'=>'Con Observaciones'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO');            
            //Validación de requeridos
            /////$crud->required_fields('estado_perfil','perfil_ingresado_x_equipo','entrega_copia_perfil','numero_acta_perfil','punto_perfil','acuerdo_perfil','fecha_aprobacion_perfil');
            $crud->required_fields('estado_perfil','numero_acta_perfil','punto_perfil','acuerdo_perfil','fecha_aprobacion_perfil');
            //Para subir un archivo
            $crud->set_field_upload('ruta','assets/uploads/files');  
            
            //Para desabilitar estilo wisiwig
            $crud->unset_texteditor('observaciones_perfil','full_text');

            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_AproDenePerfil'));
            //$crud->add_action('Resumen_perfil','','','glyphicon glyphicon-download',array($this,'ResumenPerfil'));        
            $crud->add_action('Resumen_perfil','','','glyphicon glyphicon-download',array($this,'ResumenPerfilV2'));             

            $output = $crud->render();


            $this->_AproDenePerfil_output($output);
    }


    function ResumenPerfil($primary_key )
    {
            return site_url('PDG/GenResumenPerfil_gc/generar/'.$primary_key );
    }
    function ResumenPerfilV2($primary_key )
    {
            return site_url('PDG/GenResumenPerfilV2_gc/generar/'.$primary_key );
    } 


  //Para Edit forms
    function idSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id" name="field-id" value="'.$value.'" readonly>';
    }       
    function idEquipoTgSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_equipo_tg" name="field-id_equipo_tg" value="'.$value.'" readonly>';
    }   
    function agrandaCajaTexto($value){
        return '<textarea class="form-control" id="field-tema_tesis" name="field-tema_tesis" readonly>'.$value.'</textarea>';
    }   
    function anioTesisSoloLectura($value){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" value="'.$value.'" readonly>';
    }
    function cicloTesisSoloLectura($value){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" value="'.$value.'" readonly>';
    }          
    function observacionesPerfilAgrandado($value){
        return '<textarea class="form-control" id="field-observaciones_perfil" name="field-observaciones_perfil">'.$value.'</textarea>';
    }        
   
    public   function actualizacion_AproDenePerfil($post_array){                      
           //Validando el estado
            if ($post_array['estado_perfil']=='A'){
                //$estado='A';
                /*Definiendo el tipo documento que se le asginara dependiendo del estado seleccionado por el usuario
                6->Memorandum de Aprobacion de Perfil   MAP*/
                
                $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
                WHERE siglas IN ('MAP')";
                $query=$this->db->query($sql);
                foreach ($query->result_array() as $row)
                {
                        $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];

                }            

            }
            if ($post_array['estado_perfil']=='D'){
                //$estado='D';
                /*Definiendo el tipo documento que se le asginara dependiendo del estado seleccionado por el usuario
                7->Memorandum de Denegación de Perfil   MDP*/
                $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
                WHERE siglas IN ('MDP')";  
                $query=$this->db->query($sql);
                foreach ($query->result_array() as $row)
                {
                        $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];

                }   
            }

            if ($post_array['estado_perfil']=='E'){
                //$estado='D';
                /*Definiendo el tipo documento que se le asginara dependiendo del estado seleccionado por el usuario
                7->Memorandum de Denegación de Perfil   MDP*/
                $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
                WHERE siglas IN ('ADPPP')";  
                $query=$this->db->query($sql);
                foreach ($query->result_array() as $row)
                {
                        $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];

                }   
            }

            if ($post_array['estado_perfil']=='O'){
                //$estado='D';
                /*Definiendo el tipo documento que se le asginara dependiendo del estado seleccionado por el usuario
                7->Memorandum de Denegación de Perfil   MDP*/
                $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
                WHERE siglas IN ('ADPPP')";  
                $query=$this->db->query($sql);
                foreach ($query->result_array() as $row)
                {
                        $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];

                }   
            }            
            $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
            $update['anio_tg']=$post_array['field-anio_tesis'];
            $update['ciclo_tg']=$post_array['field-ciclo_tesis'];
            $update['estado_perfil']=$post_array['estado_perfil'];
            $update['observaciones_perfil']=$post_array['field-observaciones_perfil'];
            $update['perfil_ingresado_x_equipo']=$post_array['perfil_ingresado_x_equipo'];
            $update['entrega_copia_perfil']=$post_array['entrega_copia_perfil'];
            $update['numero_acta_perfil']=$post_array['numero_acta_perfil'];
            $update['punto_perfil']=$post_array['punto_perfil'];
            $update['acuerdo_perfil']=$post_array['acuerdo_perfil'];
            $update['fecha_aprobacion_perfil']=$this->AproDenePerfil_gc_model->cambiaf_a_mysql($post_array['fecha_aprobacion_perfil']);
            $update['ruta']=$post_array['ruta'];


            $update['id_tipo_documento']=$id_tipo_documento_pdg;
            
            $comprobar=$this->AproDenePerfil_gc_model->Update($update);
            if ($comprobar >=1){
                //$huboError=0;
                if ($update['estado_perfil']=='A'){
                    $correo_estudianteA=$this->AproDenePerfil_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tg'],$update['ciclo_tg']);
                    foreach ($correo_estudianteA as $row)
                    {
                            $correoA=$row['email'];
                            //ENVIAR EMAIL a estudiantes que integran el equipo al que se les aprobo el perfil
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoA);
                            $this->email->subject('Notificación: Aprobacion de Perfil de Trabajo de Graduación');
                            $this->email->message('<p>Se ha APROBADO el Perfil de Trabajo de Graduación cuyo Tema es: '.$post_array['field-tema_tesis'].'</p>');
                            $this->email->set_mailtype('html'); 
                            $this->email->send();                        
                    }          
                }    
                if ($update['estado_perfil']=='D'){
                    $correo_estudianteD=$this->AproDenePerfil_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tg'],$update['ciclo_tg']);
                    foreach ($correo_estudianteD as $row)
                    {
                            $correoD=$row['email'];
                            //ENVIAR EMAIL a estudiantes que integran el equipo al que se les aprobo el perfil
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoD);
                            $this->email->subject('Notificación: Denegación de Perfil de Trabajo de Graduación');
                            $this->email->message('<p>Se ha DENEGADO el Perfil de Trabajo de Graduación cuyo Tema es: '.$post_array['field-tema_tesis'].'</p>');
                            $this->email->set_mailtype('html'); 
                            $this->email->send();                        
                    }          
                }    
                if ($update['estado_perfil']=='E'){
                    $correo_estudianteD=$this->AproDenePerfil_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tg'],$update['ciclo_tg']);
                    foreach ($correo_estudianteD as $row)
                    {
                            $correoD=$row['email'];
                            //ENVIAR EMAIL a estudiantes que integran el equipo al que se les aprobo el perfil
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoD);
                            $this->email->subject('Notificación: Perfil de Trabajo de Graduación en estado de Procesamiento');
                            $this->email->message('<p>El Perfil de Trabajo de Graduación cuyo Tema es: '.$post_array['field-tema_tesis'].' ha entrado en estado: EN PROCESO DE APROBACION/DENEGACIÖN</p>');
                            $this->email->set_mailtype('html'); 
                            $this->email->send();                        
                    }          
                }   
                if ($update['estado_perfil']=='O'){
                    $correo_estudianteD=$this->AproDenePerfil_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tg'],$update['ciclo_tg']);
                    foreach ($correo_estudianteD as $row)
                    {
                            $correoD=$row['email'];
                            //ENVIAR EMAIL a estudiantes que integran el equipo al que se les aprobo el perfil
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoD);
                            $this->email->subject('Notificación: Perfil de Trabajo de Graduación con observaciones');
                            $this->email->message('<p>El Perfil de Trabajo de Graduación cuyo Tema es: '.$post_array['field-tema_tesis'].' contiene observaciones, favor corregirlas</p>');
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