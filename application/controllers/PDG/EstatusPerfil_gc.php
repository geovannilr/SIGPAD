<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EstatusPerfil_gc extends CI_Controller {

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
            $this->load->model('PDG/EstatusPerfil_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }    
    public function _EstatusDenePerfil_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormEstatusPerfil_gc';
        $this->load->view('templates/content',$data);
                      
    }


    public function index()
    {
           
            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');

           /*Se utiliza la misma vista y tabla temporal de la aprobacion/denegacion de perfiles con la diferencia
           que se meustran menos campos en la contenedor que ve el usuario*/
            /*Resentear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE pdg_apro_dene_perfil_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_apro_dene_perfil_temp(id,id_equipo_tg,id_detalle_pdg,id_perfil,anio_tesis,ciclo_tesis,tema_tesis,perfil_ingresado_x_equipo,
                                                    entrega_copia_perfil,estado_perfil)
                  SELECT func_inc_var_session(),id_equipo_tg,id_detalle_pdg,id_perfil,anio_tesis,ciclo_tesis,tema_tesis,perfil_ingresado_x_equipo,
                         entrega_copia_perfil,estado_perfil
                         FROM view_apro_dene_perfil_pdg";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            
            //Seteando la tabla o vista
            $crud->set_table('pdg_apro_dene_perfil_temp');//$crud->set_table('view_perfil');
           if ($tipo == 'Estudiante'){
                //Encontrar id_equipo_tg en base al id_due logueado
                $id_equipo_tg_filtrar=$this->EstatusPerfil_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);            
                $crud->where('pdg_apro_dene_perfil_temp.id_equipo_tg',$id_equipo_tg_filtrar);
            }                  
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            $crud->set_primary_key('id');
            $crud->set_subject('Estatus de Perfiles de TG');
            $crud->set_language('spanish');
               
            $crud->columns('id','id_equipo_tg','tema_tesis','anio_tesis','estado_perfil');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id','Id')
                 ->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('estado_perfil','Estado Solicitud');

            //Validacion de ca´mpos en Edit Forms
            $crud->edit_fields('id','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','estado_perfil','observaciones_perfil','perfil_ingresado_x_equipo','entrega_copia_perfil','numero_acta_perfil','punto_perfil','acuerdo_perfil','fecha_aprobacion_perfil','ruta'); 


            $crud->field_type('estado_perfil','dropdown',array('A' => 'Aprobado','D' => 'Denegado','E'=>'En curso de aprobacion','O'=>'Con Observaciones'));

            //Para desabilitar estilo wisiwig
            $crud->unset_texteditor('observaciones_perfil','full_text');

            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            //Desabilitando opcion de editar
            $crud->unset_edit();            
            //Desabilitando opcion de visualizacion
            $crud->unset_read();

            $output = $crud->render();


            $this->_EstatusDenePerfil_output($output);
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
    function anioTesisSoloLectura($value){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" value="'.$value.'" readonly>';
    }
    function cicloTesisSoloLectura($value){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" value="'.$value.'" readonly>';
    }          
    function observacionesPerfilAgrandado($value){
        return '<textarea class="form-control" id="field-observaciones_perfil" name="field-observaciones_perfil">'.$value.'</textarea>';
    }        
   
   
}