<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ResumenAproDebeAnteproy_gc extends CI_Controller {

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
            $this->load->model('PDG/ResumenAproDebeAnteproy_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _ResumenAproDebeAnteproy_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormResumenAproDebeAnteproy_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');
            
            $sql="TRUNCATE TABLE pdg_resumen_pro_dene_anteproy_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_resumen_pro_dene_anteproy_temp SELECT * FROM view_resumen_apro_dene_anteproy_pdg";
            $this->db->query($sql);



            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pdg_resumen_pro_dene_anteproy_temp');//$crud->set_table('view_perfil');
           if ($tipo == 'Estudiante'){
                //Encontrar id_equipo_tg en base al id_due logueado
                $id_equipo_tg_filtrar=$this->ResumenAproDebeAnteproy_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);            
                $crud->where('pdg_resumen_pro_dene_anteproy_temp.id_equipo_tg',$id_equipo_tg_filtrar);
            }                
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            $crud->set_primary_key('id_equipo_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('anio_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('ciclo_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('id_detalle_pdg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->order_by('id_equipo_tg','asc');
            $crud->set_subject('Resumen de Aprobación-Denegación de Anteproyectos');
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
                 ->display_as('entrega_copia_anteproy_tribu_eva2','¿Entrega copia a Docente 2 Tribunal Evaluador?')*/
                 
           
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
            $crud->set_lang_string('form_inactive','NO'); */           
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

            //Desabilitando opcion de editar
            $crud->unset_edit();
            
            $output = $crud->render();

            $this->_ResumenAproDebeAnteproy_output($output);
    }

  

}