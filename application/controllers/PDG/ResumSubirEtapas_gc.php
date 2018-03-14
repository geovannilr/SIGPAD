<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ResumSubirEtapas_gc extends CI_Controller {

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
        if(
            ($tipo == 'Estudiante' and 
            ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7'))  or
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
            $this->load->model('PDG/ResumSubirEtapas_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }

    }
    public function _ResumSubirEtapas_gc_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormResumSubirEtapas_gc';
        $this->load->view('templates/content',$data);
                      
    }


    public function index()
    {
            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');

            /*Resetear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE pdg_resum_subir_doc_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_resum_subir_doc_temp(id,id_equipo_tg,id_detalle_pdg,anio_tesis,ciclo_tesis,tema_tesis,id_tipo_documento_pdg,descripcion,ruta)
                  SELECT func_inc_var_session(),id_equipo_tg,id_detalle_pdg,anio_tesis,ciclo_tesis,tema_tesis,id_tipo_documento_pdg,descripcion,ruta
                  FROM view_resum_subir_doc_pdg;";
            $this->db->query($sql);


            //Encontrar id_equipo_tg en base al id_due logueado
            $id_equipo_tg_filtrar=$this->ResumSubirEtapas_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);
            $crud = new grocery_CRUD();
            //$crud->columns('id_perfil','ciclo','anio','objetivo_general','objetivo_especifico','descripcion');
            //Seteando la tabla o pdg_subir_doc_temp
            $crud->set_table('pdg_resum_subir_doc_temp');
            if ($tipo == 'Estudiante'){
                $crud->where('id_equipo_tg',$id_equipo_tg_filtrar);   
            } 
            $crud->set_primary_key('id'); 

            $crud->set_subject('Subir Etapas');
            $crud->set_language('spanish');
            $crud->set_subject('Subir Documentos Académicos de TG');
            //Definiendo las columnas que apareceran
            $crud->columns('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','id_tipo_documento_pdg','descripcion','ruta');
            $crud->unset_columns(array('id'));
            $crud->unset_edit_fields('id');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('ciclo_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('id_tipo_documento_pdg','Cod. Tipo Documento')
                 ->display_as('descripcion','Tipo de Documento Académico')
                 ->display_as('ruta','Documento Académico');
           
            //Para subir un archivo
            $crud->set_field_upload('ruta','assets/uploads/files');    
            
            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            //Desabilitando opcion de actualizar
            $crud->unset_edit();            
            
            $output = $crud->render();
            $this->_ResumSubirEtapas_gc_output($output);
    }


   

}