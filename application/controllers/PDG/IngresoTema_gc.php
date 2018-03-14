<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class IngresoTema_gc extends CI_Controller {

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
            $this->load->model('PDG/IngresoTema_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }        
    }
    public function _IngresoTema_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormIngresoTema_gc';
        $this->load->view('templates/content',$data);
                      
    }
    public function index()
    {
             

            $id_doc_est = $this->session->userdata('id_doc_est');
           
            /*Resentear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE pdg_ingre_tema_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_ingre_tema_temp(id,id_equipo_tg,anio_tesis,ciclo_tesis,tema_tesis,sigla)
                  SELECT func_inc_var_session(),id_equipo_tg,anio_tesis,ciclo_tesis,tema_tesis,sigla
                  FROM view_ingre_tema_pdg";
            $this->db->query($sql);


            //obteniendo el año actual
            $fecha_actual=date ("Y");  
            $crud = new grocery_CRUD();
            //$crud->columns('id_perfil','ciclo','anio','objetivo_general','objetivo_especifico','descripcion');
            
            //Encontrar id_equipo_tg en base al id_due logueado
            $id_equipo_tg_filtrar=$this->IngresoTema_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);
            //Seteando la tabla o vista
            $crud->set_table('pdg_ingre_tema_temp');//$crud->set_table('view_perfil');
            $crud->where('id_equipo_tg',$id_equipo_tg_filtrar); 
            $crud->set_primary_key('id');

            $crud->set_subject('Registro de Tema de TG');
            $crud->set_language('spanish');
               
            $crud->columns('id_equipo_tg','anio_tesis','ciclo_tesis','tema_tesis','sigla');
            //Cambiando nombre a los labelspdg_perfil_temp de los campos
            $crud->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('tema_tesis','Tema')
                 ->display_as('sigla','Siglas');
        
            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id_equipo_tg','anio_tesis','ciclo_tesis','tema_tesis','sigla');
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('anio_tesis',array($this,'anioTesisSoloLectura'));
            $crud->callback_edit_field('ciclo_tesis',array($this,'cicloTesisSoloLectura'));
            $crud->callback_edit_field('tema_tesis',array($this,'temaTesisAgrandado'));
            $crud->callback_edit_field('sigla',array($this,'siglaAgrandado'));
            /*$crud->callback_edit_field('anio_tesis',array($this,'anioTesisSoloLectura'));
            $crud->callback_edit_field('ciclo_tesis',array($this,'cicloTesisSoloLectura'));
            $crud->callback_edit_field('id_perfil',array($this,'idPerfilSoloLectura'));
            $crud->callback_edit_field('observaciones_perfil',array($this,'observacionesPerfilSoloLectura'));

            $crud->callback_edit_field('objetivo_general',array($this,'objetivoGeneralSoloLectura'));
            //$crud->callback_edit_field('objetivo_especifico',array($this,'objetivoEspecificoSoloLectura'));
            $crud->callback_edit_field('descripcion',array($this,'descripcionSoloLectura'));
            $crud->callback_edit_field('area_tematica_tg',array($this,'areaTematicaSoloLectura'));*/
            //$crud->callback_edit_field('resultados_esperados_tg',array($this,'resultadosEsperadosSoloLectura'));

            //$crud->field_type('ciclo_perfil','dropdown',array(1,2));
            /*$crud->field_type('ciclo_perfil','dropdown',array('1' => '1', '2' => '2'));            
            $crud->field_type('anio_perfil','dropdown',array($fecha_actual-1,$fecha_actual,$fecha_actual+1));*/

            //Validación de requeridos
            $crud->required_fields('tema_tesis');
            
            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_Tema'));       
            $output = $crud->render();
            $this->_IngresoTema_output($output);
    }
  
  //Para Edit forms
    
    function idEquipoTgSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_equipo_tg" name="field-id_equipo_tg" value="'.$value.'" readonly>';
    }  
    function anioTesisSoloLectura($value){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" value="'.$value.'" readonly>';
    }  
    function cicloTesisSoloLectura($value){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" value="'.$value.'" readonly>';
    }          
    function temaTesisAgrandado($value){
        return '<input class="form-control" type="text" id="field-tema_tesis" name="field-tema_tesis" value="'.$value.'">';
    }   
    /*function siglaAgrandado($value){
        return '<textarea class="form-control" id="field-tema_tesis" name="field-tema_tesis" readonly>'.$value.'</textarea>';
    } */  
    function siglaAgrandado($value){
        return '<input class="form-control" type="text" id="field-sigla" name="field-sigla" value="'.$value.'">';
    }   
            
      
    public   function actualizacion_Tema($post_array){                      

            $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
            $update['anio_tesis']=$post_array['field-anio_tesis'];
            $update['ciclo_perfil']=$post_array['field-ciclo_perfil'];
            $update['tema_tesis']=$post_array['field-tema_tesis'];
            $update['sigla']=$post_array['field-sigla'];
  

            
            $comprobar=$this->IngresoTema_gc_model->Update($update);
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