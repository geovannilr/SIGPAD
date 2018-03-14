<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('ADMINISTRATIVO/Login_model');
        $this->load->library('session');
        //$this->removeCache();
    }

	public function Index($mensaje = null){

	   $datos['mensaje'] = $mensaje;
	   if ($this->session->userdata('id_login') == NULL){
            //$datos['tipo_usuario'] = $this->Login_model->Get_Tipo_Usuario();
            //$datos['proc_admin'] = $this->Login_model->Get_Proc_Admin();
            $this->load->view('ADMINISTRATIVO/Login', $datos);
	   }//if
       else{
            redirect('home');
            /*$datos['output'] = null;
            $datos['contenido'] = "Administrador/Home";
            $this->load->view('templates/content',$datos);*/
       }//else

	}
    
    public function Entrar() {
        if ($this->input->post()){
            $activo = null;
            $id_login = null;
            $datos['nombre_usuario'] = $this->input->post('nombre_usuario');
            $datos['password'] = md5($this->input->post('password')); //md5
            $datos['tipo_usuario'] = $this->input->post('tipo_usuario');
            $datos['proc_admin'] = $this->input->post('proc_admin');
            $verificar_usuario_activo = $this->Login_model->Verificar_Usuario($datos);

            foreach ($verificar_usuario_activo as $key) {
                $id_login = $key['id_login'];
                $activo =  $key['activo'];
                $email = $key['email'];
                $nombre = $key['nombre'];
                $apellido = $key['apellido'];
                $tipo = $key['tipo'];
                $id_doc_est = $key['id_doc_est'];
                $id_cargo_administrativo = $key['id_cargo_administrativo'];
                $id_cargo = $key['id_cargo'];
                $tipo_estudiante = $key['tipo_estudiante'];
            }            
            switch($activo){
                
                case '0': //Cuenta no activada Administrador

                    //crear id de confirmacion y guardarla en el login---------------
                    $confirmacion['id_login'] = $id_login;
                    $confirmacion['id_confirmacion'] = uniqid();
                    $this->Login_model->Crear_Id_Confirmacion($confirmacion);

                    //---------------------------------------------------------------
                    //ENVIAR EMAIL PARA CONFIRMACION
                    $this->load->library('email');
                    $url_activacion = BASE_URL.'/ADMINISTRATIVO/Login/Activar_Usuario';
                    $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                    $this->email->to($email);
                    $this->email->subject('Notificación: Activar usuario');
                    $this->email->message('Para poder activar tu cuenta favor de seguir el siguiente link, si no puedes hacer click, favor de copiar y pegarlo en la barra de direcciones de tu navegador. '
                    .$url_activacion.'/'.$confirmacion['id_login'].'/'.$confirmacion['id_confirmacion']);
                    if($this->email->send()){
                        $this->index('Tu cuenta no esta activada. Se le ha enviado un email para activar su cuenta');
                    }
                    else{
                        echo $this->email->print_debugger();
                    }


                    //---------------------------------------------------------------
                    
                    //$this->load->view('Administrador/Login', $datos);
                    break;
                case '1':
                    $this->session->set_userdata('nombre',$nombre);
                    $this->session->set_userdata('apellidos',$apellido);
                    $this->session->set_userdata('id_login',$id_login);
                    $this->session->set_userdata('email',$email);
                    $this->session->set_userdata('tipo',$tipo);
                    $this->session->set_userdata('id_doc_est',$id_doc_est);
                    $this->session->set_userdata('id_cargo_administrativo',$id_cargo_administrativo);
                    $this->session->set_userdata('id_cargo',$id_cargo);
                    $this->session->set_userdata('tipo_estudiante',$tipo_estudiante);

                    redirect('home');
                    break;
                case null:
                    $this->index('La cuenta o contraseña es incorrecta');
                    break;
            }//switch*/
        }//if ctf 
            //echo($usuario);
            //echo($password);   
    }//Entrar

    //-------------------------------------------------
    //ACTIVAR USUARIO
    public function Activar_Usuario($id,$codigo){
        $datos['id'] = $id;
        $datos['codigo'] = $codigo;
        $activar_usuario = $this->Login_model->Activar_Usuario($datos);
        switch($activar_usuario){
            case 0:
                $mensaje = 'ERROR al Activar Usuario';
            break;
            case 1:
                $mensaje = 'El Usuario fue activado con éxito';
            break; 
        }//switch
        $this->index($mensaje);

    }

    
    public function Destroy(){
		$this->session->sess_destroy();
        //$this->index(null);
        redirect('Login','refresh');
	}

}