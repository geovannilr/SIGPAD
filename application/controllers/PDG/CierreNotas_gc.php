<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CierreNotas_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='4'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/CierreNotas_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _CierreNotas_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormCierreNotas_gc';
        $this->load->view('templates/content',$data);
                      
    }


    public function index()
    {
            /*Resentear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE cierre_notas_pdg"; 
            $this->db->query($sql);
            $sql="INSERT INTO cierre_notas_pdg (id,id_equipo_tg,tema,anio_tg,ciclo_tg,etapa_Evaluativa,estado_nota)
                  SELECT func_inc_var_session(),id_equipo_tg,tema,anio_tg,ciclo_tg,etapa_Evaluativa,estado_nota
                  FROM view_cierre_notas_pdg";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o pdg_subir_doc_temp
            $crud->set_table('cierre_notas_pdg');//$crud->set_table('view_perfil');
            //$crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            //$crud->where('anio',2016); si se desea incorporar un where a la tabla/vista
            $crud->set_primary_key('id'); //si se usa vista es primordial establecer que campo sera la llave primaria

            $crud->set_subject('Cierre de Notas');
            $crud->set_language('spanish');
            $crud->set_subject('Subir Documentos Académicos de TG');
            //Definiendo las columnas que apareceran
            $crud->columns('id','id_equipo_tg','tema','anio_tg','ciclo_tg','etapa_evaluativa','estado_nota');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id','Correlativo')
                 ->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema','Tema TG')
                 ->display_as('anio_tg','Año TG')
                 ->display_as('ciclo_tg','Ciclo TG')
                 ->display_as('etapa_evaluativa','Etapa Evaluativa')
                 ->display_as('estado_nota','Estado Nota');
           
            //Validacion de campos en Edit Forms
            $crud->edit_fields('id','id_equipo_tg','tema','anio_tg','ciclo_tg','etapa_evaluativa','estado_nota'); 
            $crud->callback_edit_field('id',array($this,'idSoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tg',array($this,'anioTGSoloLectura'));
            $crud->callback_edit_field('ciclo_tg',array($this,'cicloTGSoloLectura'));
            $crud->callback_edit_field('etapa_evaluativa',array($this,'etapaEvaluativaSoloLectura'));
            $crud->field_type('estado_nota','dropdown',array('A' => 'Abierto','C' => 'Cerrado'));
            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_CierreNotas'));
            $output = $crud->render();
            $this->_CierreNotas_output($output);
    }


   //Para Edit forms
 
    function idSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id" name="field-id" value="'.$value.'" readonly>';
    }    
    function idEquipoTgSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_equipo_tg" name="field-id_equipo_tg" value="'.$value.'" readonly>';
    }   
    function agrandaCajaTexto($value){
        return '<textarea class="form-control" id="field-tema" name="field-tema" readonly>'.$value.'</textarea>';
    }   
    function anioTGSoloLectura($value){
        return '<input class="form-control" type="text" id="field-anio_tg" name="field-anio_tg" value="'.$value.'" readonly>';
    }   
    function cicloTGSoloLectura($value){
        return '<input class="form-control" type="text" id="field-ciclo_tg" name="field-ciclo_tg" value="'.$value.'" readonly>';
    }   
    function  etapaEvaluativaSoloLectura($value){
        return '<input class="form-control" type="text" id="field-etapa_evaluativa" name="field-etapa_evaluativa" value="'.$value.'" readonly>';
    }       
    
    public   function actualizacion_CierreNotas($post_array){           
     
                
            $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
            $update['anio_tg']=$post_array['field-anio_tg'];
            $update['ciclo_tg']=$post_array['field-ciclo_tg'];
            $update['etapa_evaluativa']=$post_array['field-etapa_evaluativa'];
            $update['estado_nota']=$post_array['estado_nota'];
            
            $comprobar=$this->CierreNotas_gc_model->Update($update);
            if ($comprobar >=1){
                //$huboError=0;
                 $correo_estudianteA=$this->CierreNotas_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tg'],$update['ciclo_tg']);
                foreach ($correo_estudianteA as $row)
                {
                        $correoA=$row['email'];
                        //ENVIAR EMAIL a estudiantes que integran el equipo al que se le cerro una nora
                        $this->load->library('email');
                        $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                        $this->email->to($correoA);
                        $this->email->subject('Notificación: Cierre de Nota de Trabajo de Graduación');
                        $this->email->message('<p>Se ha cerrado la etapa evaluativa '.$update['etapa_evaluativa'].' correspondiente al Tema: '.$post_array['field-tema'].', puede consultar sus notas en SIGPA.net</p>');
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