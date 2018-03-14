<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ResumenAproDeneCambioNombre_gc extends CI_Controller {

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

        if  (
                ($tipo == 'Estudiante' and 
                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
                ($tipo == 'Docente' and
                    (
                        $id_cargo_administrativo == '4' or $id_cargo_administrativo == '1' or
                        ($id_cargo_administrativo == '6' and ($id_cargo == '2' or $id_cargo == '5'
                            or $id_cargo == '6'))
                    )   
                )
            ) {
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/ResumenAproDeneCambioNombre_gc_model');
            $this->load->library('grocery_CRUD');
            }
        else{
            redirect('Login');
        }
            
    }
    public function _ResumenAproDeneCambioNombre_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormResumenAproDeneCambioNombre_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');
            
            $sql="TRUNCATE TABLE pdg_resum_apro_dene_cambio_nombre_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_resum_apro_dene_cambio_nombre_temp SELECT * FROM view_resum_apro_dene_cambio_nombre_pdg";
            $this->db->query($sql);

            //obteniendo el año actual
            $fecha_actual=date ("Y"); 

            $crud = new grocery_CRUD();
            $crud->set_table('pdg_resum_apro_dene_cambio_nombre_temp');
           if ($tipo == 'Estudiante'){
                //Encontrar id_equipo_tg en base al id_due logueado
                $id_equipo_tg_filtrar=$this->ResumenAproDeneCambioNombre_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);                    
                $crud->where('pdg_resum_apro_dene_cambio_nombre_temp.id_equipo_tg',$id_equipo_tg_filtrar);
            }                
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            $crud->set_primary_key('id_equipo_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('anio_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('ciclo_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('id_solicitud_academica'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->order_by('id_solicitud_academica','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Aprobación/Denegación de Solicitud de Cambio de Nombre de TG');
            //Definiendo las columnas que apareceran
            //$crud->columns('id_solicitud_academica','ciclo','anio','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado','ingresado_x_equipo','generado_x_coordinador_pdg','ruta');
            $crud->columns('id_solicitud_academica','ciclo','anio','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado','ruta');
            $crud->unset_columns(array('ingresado_x_equipo','generado_x_coordinador_pdg'));
            $crud->unset_fields('ingresado_x_equipo','generado_x_coordinador_pdg');
            //Cambiando nombre a los labels de los campos
           $crud->display_as('id_solicitud_academica','Id. Solicitud Académica')
                 ->display_as('ciclo','Ciclo Sol. Academica')
                 ->display_as('anio','Año Sol. Académica')
                 ->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('estado','Estado Solicitud')
                 /*->display_as('ingresado_x_equipo','¿ingresado por Equipo TG?')
                 ->display_as('generado_x_coordinador_pdg','¿Generado por Coordinador TG?')*/
                 ->display_as('ruta','Acuerdo de Modificación de Nombre');
             //Validación de columnas
            //$crud->callback_column('estado',array($this,'muestraEstado')); 
            /*$crud->callback_column('estado',function($valor){
                if ($valor=='A'){
                    $valor='Aprobado';
                }
                if ($valor=='D'){
                    $valor='Desaprobado';
                }                
                return $valor;
            });  */              
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
            //Desabilitando opcion de actualizar
            $crud->unset_edit();

            $output = $crud->render();

            $this->_ResumenAproDeneCambioNombre_output($output);
    }

   



}