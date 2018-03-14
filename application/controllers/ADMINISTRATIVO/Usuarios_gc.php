<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if($this->session->userdata('tipo')){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('ADMINISTRATIVO/Usuarios_gc_model');
            $this->load->library('grocery_CRUD');
            $this->load->library('session');
        }
        else{
            redirect('login');
        }
    }
    public function _Usuarios_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormUsuarios_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista

            $estado = $crud->getState();
            $info_estado = $crud->getStateInfo();
            if($this->session->userdata('id_cargo_administrativo')<>1){
                $crud->where('id_usuario',$this->session->userdata('id_login'));
                switch($estado){
                case 'edit':
                    if ($info_estado->primary_key!=$this->session->userdata('id_login')){
                        redirect('Login');
                    }
                    break;
            }//switch
            }
            

            $crud->set_table('gen_usuario');
            $crud->order_by('CAST(gen_usuario.id_usuario AS UNSIGNED)','asc');

            //$crud->set_relation('id_tipo_usuario','gen_tipo_usuario','nombre_tipo_usuario');
            //$crud->set_relation('id_perfil_usuario','gen_perfil','nombre_perfil');


            $crud->set_language('spanish');
            $crud->set_subject('Usuarios');
            $crud->unset_add();
            $crud->unset_read();
            $crud->unset_delete();
            //columnas para el 
            //$crud->columns('id_usuario','nombre_usuario','correo_usuario','fecha_creacion_usuario','id_tipo_usuario','id_perfil_usuario');
            $crud->columns('id_usuario','nombre_usuario','correo_usuario','fecha_creacion_usuario');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_usuario','Id')
                ->display_as('nombre_usuario','Nombre de Usuario')            
                ->display_as('correo_usuario','Correo')   
                ->display_as('fecha_creacion_usuario','Fecha de Creación')
                ->display_as('id_tipo_usuario','Tipo de usuario')
                ->display_as('id_perfil_usuario','Perfil de Usuario')
                ;
            //$crud->add_fields('id_usuario','nombre_usuario','correo_usuario','id_tipo_usuario','id_perfil_usuario','password','confirme_password');

            //$crud->edit_fields('id_usuario','nombre_usuario','correo_usuario','id_tipo_usuario','id_perfil_usuario','password','confirme_password');
            $crud->edit_fields('id_usuario','nombre_usuario','correo_usuario','password','confirme_password');
            //Definiendo validaciones para formulario de edición
            $crud->callback_edit_field('id_usuario',array($this,'id_usuario_SoloLectura'));
            
            $crud->required_fields('nombre_usuario','correo_usuario','password','confirme_password');

            $crud->change_field_type('password', 'password');
            $crud->change_field_type('confirme_password', 'password');

            //Validaciones
            $crud->set_rules('nombre_usuario','Nombre de Usuario',array('min_length[6]','max_length[50]'));
            $crud->set_rules('correo_usuario','Correo',array('valid_email','max_length[50]'));
            $crud->set_rules('password','Password',
                array(
                    'regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\*\_\+\-\.\,\!\@\#\$\%\^\&\*\(\)\;\<\>]{8,50}$/]'),
                array(
                        'regex_match'     => 'El campo Password debe tener una longitud mínima de 8 y máxima 50, además debe contener al menos una mayúscula, una minúscula y un número.'
                )   
            );
            $crud->set_rules('confirme_password', 'Confirme password', 
                array('matches[password]',
                        'regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\*\_\+\-\.\,\!\@\#\$\%\^\&\*\(\)\;\<\>]{8,50}$/]'),
                array(
                        'regex_match'     => 'El campo Confirme password debe tener una longitud mínima de 8 y máxima 50, además debe contener al menos una mayúscula, una minúscula y un número.'
                ) 
            );
            //$crud->set_rules('password','Password', array('regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8}$/]','exact_length[8]'));
            //$crud->set_rules('confirme_password', 'Confirme password', array('matches[password]','regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8}$/]','exact_length[8]'));



            $crud->callback_update(array($this,'Actualizar'));
            


            $output = $crud->render();

            $this->_Usuarios_output($output);
    }

    function id_usuario_SoloLectura($value){

        return '<input type="text" id="field-id_usuario" name="id_usuario" value="'.$value.'" readonly>';
    }

    public function Actualizar($post_array){
            $datos = $post_array;
            //$retorna = true;
            $verificar = $this->Usuarios_gc_model->Actualizar_Usuario($datos);
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


    public function regex_check($str)
    {
        if (1 !== preg_match("/^[a-zA-Z]+$/", $str))
        {
            $this->form_validation->set_message('regex_check', 'The %s field is not valid!');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

}


