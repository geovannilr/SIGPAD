<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RegisAnteproy_gc extends CI_Controller {

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
            $this->load->model('PDG/RegisAnteproy_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }        
    }
    public function _RegisAnteproy_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormRegisAnteproy_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $id_doc_est = $this->session->userdata('id_doc_est');
            
            $sql="TRUNCATE TABLE pdg_regis_ateproy_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_regis_ateproy_temp SELECT * FROM view_regis_anteproy_pdg";
            $this->db->query($sql);
            
            //Encontrar id_equipo_tg en base al id_due logueado
            $id_equipo_tg_filtrar=$this->RegisAnteproy_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);

            $crud = new grocery_CRUD();
            //$crud->columns('id_perfil','ciclo','anio','objetivo_general','objetivo_especifico','descripcion');
            //Seteando la tabla o vista
            $crud->set_table('pdg_regis_ateproy_temp');//$crud->set_table('view_perfil');
            $crud->where('id_equipo_tg',$id_equipo_tg_filtrar);             
            $crud->set_primary_key('id_equipo_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('anio_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('ciclo_tesis'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('id_detalle_pdg'); //si se usa vista es primordial establecer que campo sera la llave primaria

            $crud->set_subject('Registrar Anteproyecto');
            $crud->set_language('spanish');
            $crud->set_subject('Registrar Anteproyectos');
            //Definiendo las columnas que apareceran
            $crud->columns('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','fecha_eva_anteproyecto','fecha_eva_etapa1','fecha_eva_etapa2','fecha_eva_publica','ruta');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('fecha_eva_anteproyecto','Fecha Evaluación Anteproyecto')
                 ->display_as('fecha_eva_etapa1','Fecha Evaluación Etapa 1')
                 ->display_as('fecha_eva_etapa2','Fecha Evaluación Etapa 2')
                 ->display_as('fecha_eva_publica','Fecha de Evaluación Def. Pública')
                 ->display_as('ruta','Anteproyecto');
           
            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','fecha_eva_anteproyecto','fecha_eva_etapa1','fecha_eva_etapa2','fecha_eva_publica','ruta');
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tesis',array($this,'anioTesisSoloLectura'));
            $crud->callback_edit_field('ciclo_tesis',array($this,'cicloTesisSoloLectura'));
            //Validación de requeridos
            $crud->required_fields('fecha_eva_anteproyecto','fecha_eva_etapa1','fecha_eva_etapa2','fecha_eva_publica','ruta');
            //Para subir un archivo
            $crud->set_field_upload('ruta','assets/uploads/files');    
            
            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_RegisAnteproy_gc'));
            $output = $crud->render();
            $this->_RegisAnteproy_output($output);
    }
          //Para Edit forms
 
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
    public   function actualizacion_RegisAnteproy_gc($post_array){           

            $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
            $update['anio_tg']=$post_array['field-anio_tesis'];
            $update['ciclo_tg']=$post_array['field-ciclo_tesis'];
            $update['fecha_eva_anteproyecto']=$this->RegisAnteproy_gc_model->cambiaf_a_mysql($post_array['fecha_eva_anteproyecto']);
            $update['fecha_eva_etapa1']=$this->RegisAnteproy_gc_model->cambiaf_a_mysql($post_array['fecha_eva_etapa1']);
            $update['fecha_eva_etapa2']=$this->RegisAnteproy_gc_model->cambiaf_a_mysql($post_array['fecha_eva_etapa2']);
            $update['fecha_eva_publica']=$this->RegisAnteproy_gc_model->cambiaf_a_mysql($post_array['fecha_eva_publica']);
            $update['ruta']=$post_array['ruta'];
            
            $comprobar=$this->RegisAnteproy_gc_model->Update($update);
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