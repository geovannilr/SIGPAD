<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacto_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='3' or $tipo == 'Contacto'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/Contacto_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('home');
        
        }
    }
    public function _Contacto_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormContacto_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $tipo = $this->session->userdata('tipo');
            $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');

            $crud = new grocery_CRUD();

            //Seteando la tabla o vista
            if($this->session->userdata('id_cargo_administrativo')<>3 and $tipo == 'Contacto'){
                $crud->where('id_login',$this->session->userdata('id_login'));
                $crud->unset_add();
                $crud->unset_delete();
            }

            $crud->set_table('pss_contacto');

            $crud->set_relation('id_institucion','pss_institucion','nombre');
            $crud->set_relation('id_login','gen_usuario','nombre_usuario');

            $crud->set_language('spanish');         
            $crud->set_subject('Contactos');  

            //$crud->unset_read();
            

            //columnas para el 
            //$crud->columns('id_contacto','id_institucion','dui','nombre','apellido','descripcion_cargo','celular','telefono','id_login');
            $crud->columns('id_contacto','id_institucion','nombre','apellido','descripcion_cargo','celular','telefono','id_login');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_contacto','Id')
                ->display_as('id_institucion','Institucion')  
                ->display_as('nombre','Nombres Contacto')  
                ->display_as('apellido','Apellidos Contacto')   
                ->display_as('descripcion_cargo','Descripción')  
                ->display_as('telefono','Telefono Contacto')  
                ->display_as('celular','Celular Contacto')
                ->display_as('id_login','Nombre de Usuario')
                //->display_as('dui','DUI')
                ->display_as('email','Correo');

            
            //Definiendo los input que apareceran en el formulario de inserción
            //$crud->add_fields('dui','id_institucion','nombre','apellido','nombre_usuario','password','confirme_password','descripcion_cargo','telefono','celular','email');   
            //$crud->edit_fields('id_institucion','dui','nombre','apellido','descripcion_cargo','celular','telefono','id_login');
            $crud->add_fields('id_institucion','nombre','apellido','nombre_usuario','password','confirme_password','descripcion_cargo','telefono','celular','email');   
            $crud->edit_fields('id_institucion','nombre','apellido','descripcion_cargo','celular','telefono','id_login');
            
            $crud->callback_edit_field('id_login',array($this,'id_login_SoloLectura'));

            $crud->callback_delete(array($this,'eliminar_Contacto'));

            //Definiendo los campos obligatorios
            $crud->required_fields('id_contacto','id_institucion','nombre','apellido','nombre_usuario','password','confirme_password','email');   
            $crud->change_field_type('password', 'password');
            $crud->change_field_type('confirme_password', 'password');
            //--------------------------------------------------------------------------------
            //VALIDACIONES
            //$crud->set_rules('dui','DUI',array('exact_length[9]','numeric'));
            $crud->set_rules('nombre_usuario','Nombre de Usuario',array('min_length[6]','max_length[50]'));
            /*$crud->set_rules('password','Password',array('required','regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8}$/]','exact_length[8]'));
            $crud->set_rules('confirme_password', 'Confirme password', array('matches[password]','required','regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8}$/]','exact_length[8]'));*/
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
            $crud->set_rules('email','Correo',array('required','valid_email','max_length[50]'));
            //--------------------------------------------------------------------------------

            $crud->callback_insert(array($this,'Insertar'));
            
            $output = $crud->render();
            $this->_Contacto_output($output);
    }
    //Para Edit forms

/*    function idDocenteSoloLectura($value){

        return '<input type="text" id="field-id_docente" name="field-id_docente" value="'.$value.'" readonly>';
    } */   


    function id_login_SoloLectura($value,$primary_key){
        $Leer_Id_Usuario = $this->Contacto_gc_model->Leer_Id_Usuario($value);
        $nombre_usuario = $Leer_Id_Usuario['nombre_usuario'];
        return '<input type="text" id="field-id_login" name="field-id_login" value="'.$nombre_usuario.'" readonly>';
    }  

    public function Insertar($post_array){
            $datos = $post_array;
            $verificar = $this->Contacto_gc_model->Agregar_Contacto($datos);
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

    public function eliminar_Contacto($primary_key){
        //BUSCA EL LOGIN DEL CONTACTO        
        /*
        $this->db->select('id_login');
        $this->db->from('pss_contacto');
        $this->db->where('id_contacto', $primary_key);
        $query = $this->db->get();

        foreach ($query->result() as $row)
        {
            $id_login = $row->id_login;
        }
        */
        //BORRA EL USUARIO CON EL LOGIN Y BORRA EL CONTACTO
        return $this->db->query("DELETE pss_contacto, gen_usuario FROM pss_contacto JOIN gen_usuario WHERE gen_usuario.id_usuario = pss_contacto.id_login
            AND id_contacto = ".$primary_key);
    }

}