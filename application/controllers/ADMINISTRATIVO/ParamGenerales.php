<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ParamGenerales extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('ADMINISTRATIVO/ParamGenerales_model');
        //$this->removeCache();
    }
    public function index(){
        $this->load->model('ADMINISTRATIVO/ParamGenerales_model');
        //$data['equipo'] = $this->Perfil_model->Get_Equipo();
        //$data['equipo'] = 'hola equipo';
        $data['contenido']='ADMINISTRATIVO/FormParamGenerales';
        $this->load->view('templates/content', $data);        
    }



   public function Create()
    {
        if($this->input->post())
        {
            
            $insertar['nombres_director_escuela']=$this->input->post('nombres_director_escuela');    
            $insertar['apellidos_director_escuela']=$this->input->post('apellidos_director_escuela');    
            $insertar['nombres_coordinador_horas_sociales']=$this->input->post('nombres_coordinador_horas_sociales');    
            $insertar['apellidos_coordinador_horas_sociales']=$this->input->post('apellidos_coordinador_horas_sociales');    
            $insertar['nombres_coordinador_pera']=$this->input->post('nombres_coordinador_pera');   
            $insertar['apellidos_coordinador_pera']=$this->input->post('apellidos_coordinador_pera');    
            $insertar['nombres_director_general_pdg']=$this->input->post('nombres_director_general_pdg');    
            $insertar['apellidos_director_general_pdg']=$this->input->post('apellidos_director_general_pdg');    
            $insertar['nombres_administrador_academica_fia']=$this->input->post('nombres_administrador_academica_fia');    
            $insertar['apellidos_administrador_academica_fia']=$this->input->post('apellidos_administrador_academica_fia');  
            $insertar['nombres_secretario_eisi']=$this->input->post('nombres_secretario_eisi');    
            $insertar['apellidos_administrador_academica_fia']=$this->input->post('apellidos_administrador_academica_fia');    
            $insertar['precio_hora_sercvicio_social']=$this->input->post('precio_hora_sercvicio_social');                            
            $comprobar=$this->ParamGenerales_model->Create($insertar);
             //$comprobar=TRUE;   
            //echo $comprobar;
            if ($comprobar >=1){
                //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                //$this->index();
                //redirect('http://localhost/SIGPA/index.php/pdg/perfil');

                echo 1;
            }
            else{
                //significa que no realizaron la operacion DML
                echo 0;
            }
            //echo "A";
           
        }
        else 
        {
            echo "aun no hay submit";
        }
    }


}
?>
