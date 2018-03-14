<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Estudiantes_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $tipo = $this->session->userdata('tipo');
        if($id_cargo_administrativo == '1' or $id_cargo_administrativo == '3' or $id_cargo_administrativo == '4' or $tipo == 'Estudiante'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('ADMINISTRATIVO/Estudiantes_gc_model');
            $this->load->library('grocery_CRUD');
            $this->load->library('session');
        }
        else{
            redirect('login');   
        }
    }
    public function _Estudiantes_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormEstudiantes_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $tipo = $this->session->userdata('tipo');
            $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
            $crud = new grocery_CRUD();
            
            $estado = $crud->getState();
            $info_estado = $crud->getStateInfo();
            
            if($tipo=='Estudiante'){
                $crud->where('id_login',$this->session->userdata('id_login'));
                $crud->unset_delete();
                /*
                switch($estado){
                case 'edit':
                    if ($info_estado->primary_key!=$this->session->userdata('id_login')){
                        redirect('Login');
                    }
                    break;
                }//switch
                */
            }
            if($id_cargo_administrativo == '3' or $id_cargo_administrativo == '4'){
                $crud->unset_edit();
                $crud->unset_delete();
                //Seteando la tabla o vista
                switch($id_cargo_administrativo){
                    case '3':
                        $crud->set_table('view_estudiantes_PSS');
                        $crud->order_by('id_due','asc');
                        $crud->set_primary_key('id_due');
                        $crud->set_subject('Estudiantes que están inscritos en Servicio Social'); 
                    break;
                    case '4':
                        $crud->set_table('view_estudiantes_PDG');
                        $crud->order_by('id_due','asc');
                        $crud->set_primary_key('id_due');
                        $crud->set_subject('Estudiantes que están inscritos en Trabajo de Graduación'); 
                    break;
                }//switch
                
            }
            else{
                //Seteando la tabla o vista
                $crud->set_table('gen_estudiante');
                $crud->order_by('id_due','asc');
                $crud->set_subject('Estudiante'); 
            }

            
            $crud->set_language('spanish');         
             
            
            $crud->set_relation('id_login','gen_usuario','nombre_usuario');

            $crud->unset_add();
            $crud->unset_read();
            

            //columnas para el 
            $crud->columns('id_due','nombre','apellido','dui','direccion','telefono','celular','fecha_nac');//,'id_login');

            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_due','DUE')
                 ->display_as('nombre','Nombre')  
                 ->display_as('apellido','Apellidos')
                 ->display_as('dui','DUI')
                 ->display_as('direccion','Dirección')  
                 ->display_as('telefono','Teléfono')  
                 ->display_as('celular','Celular')
                 ->display_as('fecha_nac','Fecha de Nacimiento');
                 //->display_as('id_login','Nombre de Usuario');
            
            

            $crud->edit_fields('id_due','nombre','apellido','dui','direccion','telefono','celular','fecha_nac');            
            $crud->callback_edit_field('id_due',array($this,'idDUESoloLectura'));
            $crud->callback_delete(array($this,'eliminar_Estudiante'));
            //-------------------------------
            //VALIDACIONES
            $crud->set_rules('dui','DUI',array('exact_length[9]','numeric'));
            $crud->set_rules('telefono','Teléfono',array('exact_length[8]','numeric'));
            $crud->set_rules('celular','Celular',array('exact_length[8]','numeric'));
            //-------------------------------

            $output = $crud->render();
            $this->_Estudiantes_output($output);
    }
    //Para Edit forms

    function idDUESoloLectura($value){

        return '<input type="text" id="field-id_due" name="field-id_due" value="'.$value.'" readonly>';
    }    


    function id_login_SoloLectura($value,$primary_key){
        $Leer_Id_Usuario = $this->Docentes_gc_model->Leer_Id_Usuario($value);
        $nombre_usuario = $Leer_Id_Usuario['nombre_usuario'];
        return '<input type="text" id="field-id_login" name="field-id_login" value="'.$nombre_usuario.'" readonly>';
    }  

    function eliminar_Estudiante($primary_key){
        $verificar = $this->Estudiantes_gc_model->eliminar_Estudiante($primary_key);
            foreach ($verificar as $key) {
                $resultado =  $key['RETORNA'];
            } 
        echo $resultado;
        if ($resultado == '1'){
            $comprobacion = true;
        }else if ($resultado == '0'){
            $comprobacion = false;
        }
        return $comprobacion;    
    }

}