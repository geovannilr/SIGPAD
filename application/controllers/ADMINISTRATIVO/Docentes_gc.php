<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Docentes_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        if($tipo == 'Docente'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('ADMINISTRATIVO/Docentes_gc_model');
            $this->load->library('grocery_CRUD');
            $this->load->library('session');
        } 
        else{
            redirect('Login');
        }
    }
    public function _Docentes_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormDocentes_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

        
        $crud = new grocery_CRUD();
        //Seteando la tabla o vista
        $estado = $crud->getState();
        $info_estado = $crud->getStateInfo();
        //echo $info_estado->primary_key;
        if($this->session->userdata('id_cargo_administrativo')<>1){
            $crud->where('id_login',$this->session->userdata('id_login'));
            $crud->unset_add();
            $crud->unset_delete();
            switch($estado){
                case 'edit':
                    if ($info_estado->primary_key!=$this->session->userdata('id_login')){
                        redirect('Login');
                    }
                    break;
            }//switch
        }
        $crud->set_table('gen_docente');
        $crud->order_by('CAST(gen_docente.id_docente AS UNSIGNED)','asc');
        $crud->set_relation('id_cargo','gen_cargo','descripcion');
        $crud->set_relation('id_departamento','gen_departamento','nombre');
        $crud->set_relation('id_cargo_administrativo','gen_cargo_administrativo','descripcion');
        $crud->set_relation('id_login','gen_usuario','nombre_usuario');
        $crud->set_language('spanish');         
        $crud->set_subject('Docentes');  

        $crud->unset_read();
        

        //columnas para el 
        $crud->columns('id_docente','carnet','id_login','nombre','apellido','id_cargo','id_cargo_administrativo','id_departamento','direccion','telefono','celular');

        //Cambiando nombre a los labels de los campos
        $crud->display_as('id_docente','Id')
            ->display_as('carnet','Carnét')            
            ->display_as('id_cargo','Cargo')   
            ->display_as('id_login','Nombre de Usuario')        
            ->display_as('id_cargo_administrativo','Cargo Administrativo')   
             ->display_as('id_departamento','Departamento')  
             ->display_as('nombre','Nombres Docente')  
             ->display_as('apellido','Apellidos Docente')  
             ->display_as('direccion','Dirección Docente')  
             ->display_as('telefono','Telefono Docente')  
             ->display_as('celular','Celular Docente')
             ->display_as('email','Correo');
        //Definiendo los input que apareceran en el formulario de inserción
        $crud->add_fields('carnet','id_cargo','id_cargo_administrativo','id_departamento','nombre_usuario','password','confirme_password','nombre','apellido','direccion','telefono','celular','email');   

        //Definiendo validaciones para formulario de edición
        $crud->callback_add_field('id_docente',array($this,'idDocenteSoloLectura'));

        $crud->edit_fields('carnet','id_cargo','id_cargo_administrativo','id_departamento','id_login','nombre','apellido','direccion','telefono','celular');
        $crud->callback_edit_field('id_login',array($this,'id_login_SoloLectura'));

        //Definiendo los campos obligatorios
        $crud->required_fields('carnet','id_docente','email','nombre_usuario','password','confirme_password','id_cargo','id_cargo_administrativo','id_departamento','nombre','apellido');   
        $crud->change_field_type('password', 'password');
        $crud->change_field_type('confirme_password', 'password');
        //--------------------------------------------------------------------------------
        //VALIDACIONES
        $crud->set_rules('carnet','Carnét',array('required','exact_length[7]'));
        $crud->set_rules('email','Correo',array('required','valid_email','max_length[50]'));
        $crud->set_rules('nombre_usuario','Nombre de Usuario',array('min_length[6]','max_length[50]'));
        $crud->set_rules('password','Password',
                array('required',
                    'regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\*\_\+\-\.\,\!\@\#\$\%\^\&\*\(\)\;\<\>]{8,50}$/]'),
                array(
                        'regex_match'     => 'El campo Password debe tener una longitud mínima de 8 y máxima 50, además debe contener al menos una mayúscula, una minúscula y un número.'
                )   
            );

        $crud->set_rules('confirme_password', 'Confirme password', 
                array('matches[password]',
                        'required',
                        'regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\*\_\+\-\.\,\!\@\#\$\%\^\&\*\(\)\;\<\>]{8,50}$/]'),
                array(
                        'regex_match'     => 'El campo Confirme password debe tener una longitud mínima de 8 y máxima 50, además debe contener al menos una mayúscula, una minúscula y un número.'
                ) 
            );
        $crud->set_rules('telefono','Teléfono',array('exact_length[8]','numeric'));
        $crud->set_rules('celular','Celular',array('exact_length[8]','numeric'));
        //--------------------------------------------------------------------------------
        //Desabilitando  el wisywig
        $crud->unset_texteditor('nombre','full_text');
        
        $crud->set_lang_string('update_error_message', 'This data cannot be deleted, because there are still a constrain data, please delete that constrain data first.');

        $crud->callback_insert(array($this,'Insertar'));
        $crud->callback_delete(array($this,'eliminar_Docente'));
        $output = $crud->render();
        $this->_Docentes_output($output);
    }
    //Para Edit forms

    function idDocenteSoloLectura($value){

        return '<input class="form-control" type="text" id="field-id_docente" name="field-id_docente" value="'.$value.'" readonly>';
    }    


    function id_login_SoloLectura($value,$primary_key){
        $Leer_Id_Usuario = $this->Docentes_gc_model->Leer_Id_Usuario($value);
        $nombre_usuario = $Leer_Id_Usuario['nombre_usuario'];
        return '<input class="form-control" type="text" id="field-id_login" name="field-id_login" value="'.$nombre_usuario.'" readonly>';
    }  

    public function Insertar($post_array){
            $datos = $post_array;
            $verificar = $this->Docentes_gc_model->Agregar_Docente($datos);
            foreach ($verificar as $key) {
                $resultado =  $key['RETORNA'];
            }   
            
            
            
            switch($resultado){
                case 0:
                    $retorna = FALSE;
                    break;
                case 1:
                    $retorna = TRUE;
                    break;
                case 2:
                    $retorna = FALSE;
                    break;            
            }//switch


            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql);
            return $retorna;
            
    }        

    function eliminar_Docente($primary_key){
        
        $verificar = $this->Docentes_gc_model->eliminar_Docente($primary_key);
            foreach ($verificar as $key) {
                $resultado =  $key['RETORNA'];
            } 
        
        if ($resultado == '1'){
            $comprobacion = true;
        }else if ($resultado == '0'){
            $comprobacion = false;
        }
        return $comprobacion;    
        
    }



}