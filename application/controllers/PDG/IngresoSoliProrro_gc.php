<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class IngresoSoliProrro_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        $id_doc_est = $this->session->userdata('id_doc_est');
        if($tipo == 'Estudiante' and 
            ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/IngresoSoliProrro_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _IngresoSoliProrro_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormIngresoSoliProrro_gc';
        $this->load->view('templates/content',$data);
                      
    }

   public function index()
    {
            
            $id_doc_est = $this->session->userdata('id_doc_est');

            //obteniendo el año actual
            $fecha_actual=date ("Y"); 
            //Encontrar id_equipo_tg en base al id_due logueado
            $id_equipo_tg_filtrar=$this->IngresoSoliProrro_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);

            $crud = new grocery_CRUD();
            //$crud->set_theme('bootstrap');
            $crud->set_table('view_soli_prorro_pdg');
            $crud->where('view_soli_prorro_pdg.id_equipo_tg',$id_equipo_tg_filtrar);             
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg',array('id_equipo_tg' => $id_equipo_tg_filtrar));
            $crud->set_primary_key('id_equipo_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('anio_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('ciclo_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('id_solicitud_academica'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->order_by('id_solicitud_academica','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Ingreso de Solicitud de Prorroga de TG');
            //Definiendo las columnas que apareceran
            $crud->columns('id_solicitud_academica','ciclo','anio','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado','fecha_solicitud','fecha_ini_prorroga','fecha_fin_prorroga','duracion','eva_antes_prorroga','eva_actual','cantidad_evaluacion_actual','justificacion','caso_especial');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_solicitud_academica','Id. Solicitud Académica')
                 ->display_as('ciclo','Ciclo Sol. Academica')
                 ->display_as('anio','Año Sol. Académica')
                 ->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('tipo_solicitud','Tipo Solicitud')
                 ->display_as('estado','Estado Solicitud')
                 ->display_as('fecha_solicitud','Fecha Solicitud')
                 ->display_as('fecha_ini_prorroga','Fecha Inicio de Prorroga')
                 ->display_as('fecha_fin_prorroga','Fecha Fin Prorroga')
                 ->display_as('duracion','Duración de Prorroga (Meses)')
                 ->display_as('eva_antes_prorroga','Evaluación antes de prorroga')
                 ->display_as('eva_actual','Evaluación Actual')
                 ->display_as('cantidad_evaluacion_actual','Cantidad de Evaluaciones Actuales')
                 ->display_as('justificacion','Justificación')
                 ->display_as('caso_especial','¿Es Caso Especial?');
            //Validación de requeridos
            $crud->required_fields('ciclo','anio','id_equipo_tg','fecha_solicitud','fecha_ini_prorroga','fecha_fin_prorroga','duracion','eva_antes_prorroga','eva_actual','cantidad_evaluacion_actual','justificacion','caso_especial');  
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('ciclo','anio','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','fecha_solicitud','fecha_ini_prorroga','fecha_fin_prorroga','duracion','eva_antes_prorroga','eva_actual','cantidad_evaluacion_actual','justificacion','caso_especial'); 
            //Desabilitando  el wisywig
            $crud->unset_texteditor('eva_antes_prorroga','full_text');
            $crud->unset_texteditor('eva_actual','full_text');
            $crud->unset_texteditor('cantidad_evaluacion_actual','full_text');
            $crud->unset_texteditor('justificacion','full_text');
            $crud->unset_texteditor('caso_especial','full_text');
            //Validacion de ca´mpos en Add Forms
            $crud->field_type('ciclo','dropdown',array(1,2));
            $crud->field_type('anio','dropdown',array($fecha_actual-1,$fecha_actual,$fecha_actual+1));
            $crud->field_type('cantidad_evaluacion_actual','dropdown',array(1,2,3,4,5));
            $crud->callback_add_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_add_field('anio_tesis',array($this,'anioTGSoloLectura'));
            $crud->callback_add_field('ciclo_tesis',array($this,'cicloTGSoloLectura'));
            $crud->callback_add_field('eva_antes_prorroga',array($this,'evaAntesProrrogaSoloLectura'));
            $crud->callback_add_field('eva_actual',array($this,'evaActualSoloLectura'));
            $crud->callback_add_field('justificacion',array($this,'justificacionSoloLectura'));

            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO');
            //Desabilitando opcion de editar
            $crud->unset_edit();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_insert(array($this,'insercion_IngresoSoliCambioNombreTG'));
            $crud->add_action('Solicitud_Prorroga','','','glyphicon glyphicon-download',array($this,'SoliProrro'));        
            $output = $crud->render();

            $this->_IngresoSoliProrro_output($output);
    }
    function SoliProrro($primary_key )
    {
            return site_url('PDG/GenSoliProrro_gc/generar/'.$primary_key );
    }

    public function datos_tema($id){
        echo json_encode($this->IngresoSoliProrro_gc_model->Get_Datos_Tema($id));
    }
   
    //Para Add forms
    function agrandaCajaTexto(){
        return '<textarea class="form-control" id="field-tema" name="field-tema" readonly></textarea>';
    }   
    function anioTGSoloLectura(){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" readonly>';
    }    
    function cicloTGSoloLectura(){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" readonly>';
    }        


    function evaAntesProrrogaSoloLectura($value){
        return '<textarea class="form-control" id="eva_antes_prorroga" name="eva_antes_prorroga">'.$value.'</textarea>';
    }   
    function evaActualSoloLectura($value){
        return '<textarea class="form-control" id="eva_actual" name="eva_actual">'.$value.'</textarea>';
    }   
    function justificacionSoloLectura($value){
        return '<textarea class="form-control" id="justificacion" name="justificacion">'.$value.'</textarea>';
    }   

    public function insercion_IngresoSoliCambioNombreTG($post_array){
        
        //Validando el ciclo
        if ($post_array['ciclo']==0){
            $ciclo='1';
        }
        if ($post_array['ciclo']==1){
            $ciclo='2';
        }
        $insertar['ciclo']=$ciclo;
        //Validando el año        
        if ($post_array['anio']==0){
            $anio='2015';
        }
        if ($post_array['anio']==1){
            $anio='2016';
        }
        if ($post_array['anio']==2){
            $anio='2017';
        }                
        $insertar['anio']=$anio;
        $insertar['id_equipo_tg']=$post_array['id_equipo_tg'];
        $insertar['anio_tg']=$post_array['field-anio_tesis'];
        $insertar['ciclo_tg']=$post_array['field-ciclo_tesis'];
        $insertar['tipo_solicitud']='sol_prorroga_tg';
        $insertar['fecha_solicitud']=$this->IngresoSoliProrro_gc_model->cambiaf_a_mysql($post_array['fecha_solicitud']);
        $insertar['fecha_ini_prorroga']=$this->IngresoSoliProrro_gc_model->cambiaf_a_mysql($post_array['fecha_ini_prorroga']);
        $insertar['fecha_fin_prorroga']=$this->IngresoSoliProrro_gc_model->cambiaf_a_mysql($post_array['fecha_fin_prorroga']);
        $insertar['duracion']=$post_array['duracion'];
        $insertar['eva_antes_prorroga']=$post_array['eva_antes_prorroga'];
        $insertar['eva_actual']=$post_array['eva_actual'];
        $insertar['cantidad_evaluacion_actual']=$post_array['cantidad_evaluacion_actual'];
        $insertar['justificacion']=$post_array['justificacion'];
        $insertar['caso_especial']=$post_array['caso_especial'];                


        $comprobar=$this->IngresoSoliProrro_gc_model->Create($insertar);
        if ($comprobar >=1){
            return true;
        }
        else{
            //significa que no se realizo la operacion DML
            $comprobar=0;
            return false;
        }                           
    }   

  



}