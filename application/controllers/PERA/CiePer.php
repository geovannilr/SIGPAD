<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CiePer extends CI_Controller {
    function __construct(){
        parent::__construct();

        $tipo = $this->session->userdata('tipo');
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        $id_doc_est = $this->session->userdata('id_doc_est');
        $nombre = $this->session->userdata('nombre');
        $apellido = $this->session->userdata('apellidos');

        /*if($id_cargo == '3' or
            ($tipo == 'Estudiante' and 
                ($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) */
        if($id_cargo_administrativo == '2'){

            //$this->removeCache();
            $this->load->helper('url');
            $this->load->library('grocery_CRUD');
           	$this->load->library('gc_dependent_select');
            $this->load->model('PERA/CiePer_model'); 
            $this->load->model('PERA/AsiGen_model');
        }
        else{
            redirect('Login');
        }        
    }
    public function CiePer_output($output=null){
        $data['output']=$output;
        $data['contenido']='PERA/FormCiePer';
        $this->load->view('templates/content', $data);        
        
    }
    

    public function index(){

        $id_doc_est = $this->session->userdata('id_doc_est'); // id del usuario
        $tipo = $this->session->userdata('tipo'); //Estudiante o Docente
        $id_cargo = $this->session->userdata('id_cargo');// 3 o 10 PERA
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');//-1 Estud        
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        
     
        //if($tipo == 'Estudiante' or $tipo == 'Docente'){
        //if($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7' or $id_cargo == '3'){

            $crud = new grocery_CRUD();
            //introduciendo la vista:
            $crud->set_table('per_view_cie_per');
            $crud->set_primary_key('id_detalle_pera'); 
            $crud->set_subject('Cierre del Programa');
            $crud->set_language("spanish");
            $crud->unset_add();    
            $crud->unset_delete();
            $crud->unset_read();


            if($tipo  == 'Estudiante'){
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                //$crud->set_relation('id_detalle_pera','per_view_reg_not_estudiante','{id_due} - {nombre} {apellido}');
                $crud->where('due',$id_doc_est);

            }

            if($id_cargo =='3'){ //Docente General PERA
                //$crud->where('docente',$id_doc_est);
                //$crud->set_relation('id_detalle_pera','per_view_reg_not_estudiante','{id_due} - {nombre} {apellido}', array('id_docente'=>$id_doc_est));
            }  

        
     
        
        //$crud->set_relation('nombre campo Listbox en el Add','tabla o view a relacionar','<-id-relacion-llave primaria de la tabla');
        
        $crud->columns('estado_registro','ciclo','estado_2','id_due','estudiante','docente_mentor');

        $crud->edit_fields('id_detalle_pera','id_due','estudiante','ciclo','docente_mentor','estado','estado_registro');

        //$crud->field_type('id_detalle_pera','invisible');
        
        // Ocultar el campo en el add y edit
        $crud->field_type('id_detalle_pera','hidden');
        $crud->field_type('estado_registro','dropdown',array('a'=>'Abierto','c'=>'Cerrado'));
        $crud->field_type('estado_2','dropdown',array('f'=>'Finalizado','a'=>'Abandonado'));

        $crud->required_fields('estado_registro');


        // Etiquetas
        $crud->display_as('id_due','DUE');
        $crud->display_as('estado_registro','PERA');  
        $crud->display_as('docente_mentor','Docente');
        $crud->display_as('estado_2','Estado');


        $crud->callback_field('id_due',array($this,'solo_lectura_id_due'));
        $crud->callback_field('estudiante',array($this,'solo_lectura_estudiante'));
        $crud->callback_field('estado',array($this,'solo_lectura_estado'));
        $crud->callback_field('ciclo',array($this,'solo_lectura_ciclo'));        
        $crud->callback_field('docente_mentor',array($this,'solo_lectura_docente_mentor'));



        // CRUD
        $crud->callback_insert(array($this,'insertar'));
        $crud->callback_update(array($this,'actualizar'));
        $crud->callback_delete(array($this,'eliminar'));



        
        $output = $crud->render();
        

        $this->CiePer_output($output);  
    }    
              
        /*else{
            redirect('/');
        }               
    }*/
    
  
    public function solo_lectura_id_due($valor,$primary_key){
        return '<input id="field-id_due" type="text" class="form-control" value="'.$valor.'" name="id_due" width:100px readonly >';
    }
    
    public function solo_lectura_estudiante($valor,$primary_key){
        return '<input id="field-estudiante" class="form-control" type="text" maxlength="100" value="'.$valor.'" name="estudiante" width:500px readonly >';
    }

    public function solo_lectura_estado($valor){
        if ($valor == 'f')
            $valor = 'Finalizado';
        else //if($valor == 'f')
            $valor = 'Abandonado';
     
        return '<input id="field-estado" class="form-control" name="estado" type="text" value="'.$valor.'" maxlength="100" readonly />';
    }
    public function solo_lectura_ciclo($valor){
     
        return '<input id="field-ciclo" class="form-control" name="ciclo" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }  

    public function solo_lectura_docente_mentor($valor){    

        return '<input id="field-docente_mentor" class="form-control" name="docente_mentor" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }  

	public function actualizar($post_array,$primary_key){

        $insertar['id_detalle_pera']=$primary_key;    
        $insertar['estado_registro']=$post_array['estado_registro'];

        $id_due = $post_array['id_due'];        

        $usuario = $this->CiePer_model->Obtener_usuarios_pera($primary_key);

        $id_docente = $usuario['id_docente'];

		$comprobar = $this->CiePer_model->actualizar($insertar);

        if($comprobar>=1){
            
            $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);
            $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
            $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);

            $ciclo = $post_array['ciclo'];
            $estado = $post_array['estado'];
            
            $id_login_docente = $this->AsiGen_model->ObtenerIdLogin_Docente($id_docente);
            $docente = $this->AsiGen_model->ObtenerDocente($id_docente);
            $correo_docente = $this->AsiGen_model->ObtenerCorreoUsuario($id_login_docente);

            $estado_registro = $insertar['estado_registro'];
            if($estado_registro=='c')
                $estado_registro = 'Cerrado';
            else if($estado_registro=='a')
                $estado_registro = 'Abierto';


            //ENVIAR EMAIL a alumno al que se le asigna el docente
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
            $this->email->to($correo_estudiante);
            $this->email->subject('Notificación: Cierre del Programa Especial de Refuerzo Academico - PERA');
            $this->email->message('<p>Su expediente del PERA '.$estado.' en el ciclo '.$ciclo.' se ha '.$estado_registro.'.</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            //ENVIAR EMAIL al Docente General al que se le asigna el Estudiante
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
            $this->email->to($correo_docente);
            $this->email->subject('Notificación: Asignación como Asesor General del PERA');
            $this->email->message('<p>El expediente del PERA del estudiante '.$estudiante.' en el ciclo '.$ciclo.' se ha '.$estado_registro.'.</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            return true;   
        }
        else 
        {
            return false;
        }	


	}
        
}