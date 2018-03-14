<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SubirEtapas_gc extends CI_Controller {

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
            $this->load->model('PDG/SubirEtapas_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _SubirEtapas_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormSubirEtapas_gc';
        $this->load->view('templates/content',$data);
                      
    }


    public function index()
    {
            $id_doc_est = $this->session->userdata('id_doc_est');

            /*Resetear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE pdg_subir_doc_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_subir_doc_temp(id,id_equipo_tg,id_detalle_pdg,anio_tesis,ciclo_tesis,tema_tesis,id_tipo_documento_pdg,descripcion,ruta)
                  SELECT func_inc_var_session(),id_equipo_tg,id_detalle_pdg,anio_tesis,ciclo_tesis,tema_tesis,id_tipo_documento_pdg,descripcion,ruta
                  FROM view_subir_doc_pdg;";
            $this->db->query($sql);


            //Encontrar id_equipo_tg en base al id_due logueado
            $id_equipo_tg_filtrar=$this->SubirEtapas_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);
            $crud = new grocery_CRUD();
            //$crud->columns('id_perfil','ciclo','anio','objetivo_general','objetivo_especifico','descripcion');
            //Seteando la tabla o pdg_subir_doc_temp
            $crud->set_table('pdg_subir_doc_temp');//$crud->set_table('view_perfil');
            $crud->where('id_equipo_tg',$id_equipo_tg_filtrar);   
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
           
            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','id_tipo_documento_pdg','descripcion','ruta'); 
            ///////$crud->callback_edit_field('id',array($this,'idSoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tesis',array($this,'anioTGSoloLectura'));
            $crud->callback_edit_field('ciclo_tesis',array($this,'cicloTGSoloLectura'));
            $crud->callback_edit_field('id_tipo_documento_pdg',array($this,'codTipoDocumentoSoloLectura'));
            $crud->callback_edit_field('descripcion',array($this,'tipoDocumentoAcademicoSoloLectura'));
            //Validación de requeridos
            $crud->required_fields('ruta');
            //Para subir un archivo
            $crud->set_field_upload('ruta','assets/uploads/files');    
            
            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_SubirEtapas'));
            $output = $crud->render();
            $this->_SubirEtapas_output($output);
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
    function anioTGSoloLectura($value){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" value="'.$value.'" readonly>';
    }   
    function cicloTGSoloLectura($value){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" value="'.$value.'" readonly>';
    }       
    function codTipoDocumentoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_tipo_documento_pdg" name="field-id_tipo_documento_pdg" value="'.$value.'" readonly>';
    }       
    function tipoDocumentoAcademicoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-descripcion" name="field-descripcion" value="'.$value.'" readonly>';
    }      
    public   function actualizacion_SubirEtapas($post_array){           

            $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
            $update['anio_tg']=$post_array['field-anio_tesis'];
            $update['ciclo_tg']=$post_array['field-ciclo_tesis'];
            $update['id_tipo_documento_pdg']=$post_array['field-id_tipo_documento_pdg'];
            $update['ruta']=$post_array['ruta'];
            
            $comprobar=$this->SubirEtapas_gc_model->Update($update);
            if ($comprobar >=1){
                $huboError=0;
                //return true;
            }
            else{
                //significa que no se realizo la operacion DML
                $huboError=1;
                //return false;
            }                    

        }

}