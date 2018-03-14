<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AproDebeAnteproy_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='4'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/AproDebeAnteproy_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _AproDebeAnteproy_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormAproDebeAnteproy_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $sql="TRUNCATE TABLE pdg_apro_dene_anteproy_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_apro_dene_anteproy_temp SELECT * FROM view_apro_dene_anteproy_pdg";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pdg_apro_dene_anteproy_temp');//$crud->set_table('view_perfil');
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            $crud->set_primary_key('id_equipo_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('anio_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('ciclo_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('id_detalle_pdg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->order_by('id_equipo_tg','asc');
            $crud->set_subject('Aprobación-Denegación de Anteproyectos');
            $crud->set_language('spanish');
     
            //$crud->columns('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado_anteproyecto','anteproy_ingresado_x_equipo','entrega_copia_anteproy_doc_ase','entrega_copia_anteproy_tribu_eva1','entrega_copia_anteproy_tribu_eva2','ruta');
            $crud->columns('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado_anteproyecto','ruta');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('estado_anteproyecto','Estado Solicitud')
                 ->display_as('ruta','Acta Aprobación Anteproyecto');
                 /*->display_as('anteproy_ingresado_x_equipo','¿Anteproyecto ingresado por Equipo?')
                 ->display_as('entrega_copia_anteproy_doc_ase','¿Entrega copia a Doc. Asesor?')
                 ->display_as('entrega_copia_anteproy_tribu_eva1','¿Entrega copia a Docente 1 Tribunal Evaluador?')
                 ->display_as('entrega_copia_anteproy_tribu_eva2','¿Entrega copia a Docente 2 Tribunal Evaluador?')
                 ->display_as('ruta','Acta Aprobación Anteproyecto');*/
           
            //Validacion de ca´mpos en Edit Forms
            //$crud->edit_fields('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado_anteproyecto','anteproy_ingresado_x_equipo','entrega_copia_anteproy_doc_ase','entrega_copia_anteproy_tribu_eva1','entrega_copia_anteproy_tribu_eva2','ruta'); 
            $crud->edit_fields('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado_anteproyecto','ruta'); 
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tesis',array($this,'anioTGSoloLectura'));
            $crud->callback_edit_field('ciclo_tesis',array($this,'cicloTGSoloLectura'));
            $crud->field_type('estado_anteproyecto','dropdown',array('A' => 'Aprobado','D' => 'Denegado'));
            //$crud->callback_edit_field('anteproy_ingresado_x_equipo',array($this,'anteproyIngreXEquipoSoloLectura'));
            /*$crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO');     */       
            //Validación de requeridos
            /*$crud->required_fields('estado_anteproyecto','anteproy_ingresado_x_equipo','entrega_copia_anteproy_doc_ase',
                                    'entrega_copia_anteproy_tribu_eva1','entrega_copia_anteproy_tribu_eva2','ruta');*/
            $crud->required_fields('estado_anteproyecto','ruta');            
            //Para subir un archivo
            $crud->set_field_upload('ruta','assets/uploads/files');  
            
            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_AproDebeAnteproy'));

            $output = $crud->render();

            $this->_AproDebeAnteproy_output($output);
    }

   //Para Edit forms
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
    function anteproyIngreXEquipoSoloLectura($value){
        if ($value==1){
            return '<label for="field-anteproy_ingresado_x_equipo-true">
                        <input type="radio" id="field-anteproy_ingresado_x_equipo-true" name="field-anteproy_ingresado_x_equipo-true" value="'.$value.'" checked="checked" readonly>            
                        SI
                    </label>   
                    <label for="field-anteproy_ingresado_x_equipo-false">                     
                        <input type="radio" id="field-anteproy_ingresado_x_equipo-false" name="field-anteproy_ingresado_x_equipo-false" value="'.$value.'" readonly>
                        NO
                    </label>';                             
        }
        if ($value==0){
            return '<label for="field-anteproy_ingresado_x_equipo-true">
                        <input type="radio" id="field-anteproy_ingresado_x_equipo-true" name="field-anteproy_ingresado_x_equipo-true" value="'.$value.'" readonly>            
                        SI
                    </label>   
                    <label for="field-anteproy_ingresado_x_equipo-false"> 
                        <input type="radio" id="field-anteproy_ingresado_x_equipo-false" name="field-anteproy_ingresado_x_equipo-false" value="'.$value.'" checked="checked"  readonly>
                        NO
                    </label>';            
        }
    }       
   
    public   function actualizacion_AproDebeAnteproy($post_array){                      
           //Validando el estado
            if ($post_array['estado_anteproyecto']=='A'){
                //$estado='A';
                /*Definiendo el tipo documento que se le asginara dependiendo del estado seleccionado por el usuario
                8->Memorandum de Aprobacion de Anteproyecto   MAA*/
                
                $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
                WHERE siglas IN ('MAA')";
                $query=$this->db->query($sql);
                foreach ($query->result_array() as $row)
                {
                        $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];

                }            

            }
            if ($post_array['estado_anteproyecto']=='D'){
                //$estado='D';
                /*Definiendo el tipo documento que se le asginara dependiendo del estado seleccionado por el usuario
                9->Memorandum de Denegación de Anteproyecto   MDA*/
                $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
                WHERE siglas IN ('MDA')";  
                $query=$this->db->query($sql);
                foreach ($query->result_array() as $row)
                {
                        $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];

                }   
            }

            $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
            $update['anio_tg']=$post_array['field-anio_tesis'];
            $update['ciclo_tg']=$post_array['field-ciclo_tesis'];
            $update['estado_anteproyecto']=$post_array['estado_anteproyecto'];
            $update['anteproy_ingresado_x_equipo']=$post_array['anteproy_ingresado_x_equipo'];
            $update['entrega_copia_anteproy_doc_ase']=$post_array['entrega_copia_anteproy_doc_ase'];
            $update['entrega_copia_anteproy_tribu_eva1']=$post_array['entrega_copia_anteproy_tribu_eva1'];
            $update['entrega_copia_anteproy_tribu_eva2']=$post_array['entrega_copia_anteproy_tribu_eva2'];
            $update['ruta']=$post_array['ruta'];
            $update['id_tipo_documento']=$id_tipo_documento_pdg;
            
            $comprobar=$this->AproDebeAnteproy_gc_model->Update($update);
            if ($comprobar >=1){
                //$huboError=0;
                if ($update['estado_anteproyecto']=='A'){
                    $correo_estudianteA=$this->AproDebeAnteproy_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tg'],$update['ciclo_tg']);
                    foreach ($correo_estudianteA as $row)
                    {
                            $correoA=$row['email'];
                            //ENVIAR EMAIL a estudiantes que integran el equipo al que se les aprobo el anteproyecto
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoA);
                            $this->email->subject('Notificación: Aprobacion de Anteproyecto de Trabajo de Graduación');
                            $this->email->message('<p>Se ha APROBADO el Anteproyecto de Trabajo de Graduación cuyo Tema es: '.$post_array['field-tema_tesis'].'</p>');
                            $this->email->set_mailtype('html'); 
                            $this->email->send();                        
                    }          
                }    
                if ($update['estado_perfil']=='D'){
                    $correo_estudianteD=$this->AproDebeAnteproy_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tg'],$update['ciclo_tg']);
                    foreach ($correo_estudianteD as $row)
                    {
                            $correoD=$row['email'];
                            //ENVIAR EMAIL a estudiantes que integran el equipo al que se les reprobo  el anteproyecto
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoD);
                            $this->email->subject('Notificación: Denegación de Anteproyecto de Trabajo de Graduación');
                            $this->email->message('<p>Se ha DENEGADO el Anteproyecto de Trabajo de Graduación cuyo Tema es: '.$post_array['field-tema_tesis'].'</p>');
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