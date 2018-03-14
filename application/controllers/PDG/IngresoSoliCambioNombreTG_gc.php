<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class IngresoSoliCambioNombreTG_gc extends CI_Controller {

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
        if($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/IngresoSoliCambioNombreTG_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _IngresoSoliCambioNombreTG_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormIngresoSoliCambioNombreTG_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $id_doc_est = $this->session->userdata('id_doc_est');
            
            //obteniendo el año actual
            $fecha_actual=date ("Y"); 

            //Encontrar id_equipo_tg en base al id_due logueado
            $id_equipo_tg_filtrar=$this->IngresoSoliCambioNombreTG_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);

            $crud = new grocery_CRUD();
            $crud->set_table('view_soli_cambio_nombre_pdg');
            $crud->where('view_soli_cambio_nombre_pdg.id_equipo_tg',$id_equipo_tg_filtrar);   
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg',array('id_equipo_tg' => $id_equipo_tg_filtrar));
            //$crud->set_primary_key('id_equipo_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            //$crud->set_primary_key('anio_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('id_solicitud_academica'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->order_by('CAST(id_solicitud_academica AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Ingreso de Solicitud de Cambio de Nombre de TG');
            //Definiendo las columnas que apareceran
            $crud->columns('id_solicitud_academica','ciclo','anio','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado','acuerdo_junta','nombre_actual','nombre_propuesto','justificacion');
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
                 //->display_as('acuerdo_junta','Acuerdo Junta')
                 ->display_as('nombre_actual','Nombre Actual Tema TG')
                 ->display_as('nombre_propuesto','Nombre Propuesto Tema TG')
                 ->display_as('justificacion','Justificación');
            //Validación de requeridos
            $crud->required_fields('ciclo','anio','id_equipo_tg','nombre_actual','nombre_propuesto','justificacion');        
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('ciclo','anio','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','nombre_actual','nombre_propuesto','justificacion');            
            //Desabilitando  el wisywig
            $crud->unset_texteditor('nombre_actual','full_text');
            $crud->unset_texteditor('nombre_propuesto','full_text');
            $crud->unset_texteditor('justificacion','full_text');
            //Validacion de ca´mpos en Add Forms
            $crud->field_type('ciclo','dropdown',array(1,2));
            $crud->field_type('anio','dropdown',array($fecha_actual-1,$fecha_actual,$fecha_actual+1));
            $crud->callback_add_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_add_field('anio_tesis',array($this,'anioTGSoloLectura'));
            $crud->callback_add_field('ciclo_tesis',array($this,'cicloTGSoloLectura'));
    
            $crud->callback_add_field('nombre_actual',array($this,'nombreActualSoloLectura'));
            $crud->callback_add_field('nombre_propuesto',array($this,'nombrePropuestoSoloLectura'));
            $crud->callback_add_field('justificacion',array($this,'justificacionSoloLectura'));
  

            //Desabilitando opcion de editar
            $crud->unset_edit();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_insert(array($this,'insercion_IngresoSoliCambioNombreTG'));
            $crud->add_action('Solicitud_Cambio_Nombre','','','glyphicon glyphicon-download',array($this,'SoliCambioNombre'));        
            $output = $crud->render();

            $this->_IngresoSoliCambioNombreTG_output($output);
    }

     function SoliCambioNombre($primary_key )
    {
            return site_url('PDG/GenSoliCambioNombre_gc/generar/'.$primary_key );
    }

    public function datos_tema($id){
        echo json_encode($this->IngresoSoliCambioNombreTG_gc_model->Get_Datos_Tema($id));
    }
   
    //Para Add forms
    function agrandaCajaTexto(){
        return '<textarea class="form-control" id="field-tema_tesis" name="field-tema_tesis" readonly></textarea>';
    }   
    function anioTGSoloLectura(){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" readonly>';
    }    
    function cicloTGSoloLectura(){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" readonly>';
    }        

    function nombreActualSoloLectura($value){
        return '<textarea class="form-control" id="nombre_actual" name="nombre_actual">'.$value.'</textarea>';
    }   
    function nombrePropuestoSoloLectura($value){
        return '<textarea class="form-control" id="nombre_propuesto" name="nombre_propuesto">'.$value.'</textarea>';
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
        $insertar['tipo_solicitud']='sol_modi_nombre_tg';
        $insertar['nombre_actual']=$post_array['nombre_actual'];
        $insertar['nombre_propuesto']=$post_array['nombre_propuesto'];
        $insertar['justificacion']=$post_array['justificacion'];


        $comprobar=$this->IngresoSoliCambioNombreTG_gc_model->Create($insertar);
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