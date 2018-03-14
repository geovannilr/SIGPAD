<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AsiDoc extends CI_Controller {




    function __construct(){
        parent::__construct();

        $tipo = $this->session->userdata('tipo');
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        $id_doc_est = $this->session->userdata('id_doc_est');
        $nombre = $this->session->userdata('nombre');
        $apellido = $this->session->userdata('apellidos');
        
        if($id_cargo == '3' or $id_cargo == '10' or
            ($tipo == 'Estudiante' and 
                ($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
        //$this->removeCache();
            $this->load->helper('url');
            $this->load->library('grocery_CRUD');
           	//$this->load->library('gc_dependent_select');
            $this->load->model('PERA/AsiDoc_model');
            $this->load->model('PERA/AsiGen_model');
            $this->load->library('session');         
        }
        else{
            redirect('Login');
        }
    }
    
    public function _AsiDoc_output($output=null){
        $data['output']=$output;
        $data['contenido']='PERA/FormAsiDoc';


        /*** TEMPORAL VARIABLES DE SESION ******/

        $id_login = $this->session->userdata('id_login'); // id del usuario
        
        $nombre=$this->session->userdata('nombre');
        $apellidos=$this->session->userdata('apellidos');
        $email = $this->session->userdata('email');
        $id_doc_est = $this->session->userdata('id_doc_est'); // id del usuario
        $tipo = $this->session->userdata('tipo'); //Estudiante o Docente
        $id_cargo = $this->session->userdata('id_cargo');// 3 o 10 PERA
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');//-1 Estud        
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');  
              
        
        $data['id_login']=$id_login;
        $data['tipo']=$tipo;
        $data['id_doc_est']=$id_doc_est;
        $data['nombre']=$nombre;
        $data['apellidos']=$apellidos;
        $data['email']=$email;
        $data['id_cargo']=$id_cargo;
        $data['id_cargo_administrativo']=$id_cargo_administrativo;
        $data['tipo_estudiante']=$tipo_estudiante;

        /**********************************************/


        $this->load->view('templates/content', $data);
        
        
    }
    
    public function index(){

        $id_doc_est = $this->session->userdata('id_doc_est'); // id del usuario
        $tipo = $this->session->userdata('tipo'); //Estudiante o Docente
        $id_cargo = $this->session->userdata('id_cargo');// 3 o 10 PERA
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');//-1 Estudiante        
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');  

        if($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7' or $id_cargo == '3' or $id_cargo=='10'){
        

            //$docente_general=6;

            $crud = new grocery_CRUD();
                    
            $crud->set_language("spanish");

            $crud->set_subject('Docente Asesor'); 
            
            //introduciendo la vista:
            $crud->set_table('per_view_asi_doc');

            $crud->set_primary_key('id_tipo_pera');

            // where por tipo de usuario
            if($tipo  == 'Estudiante'){ //Estudiante PERA
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->where('due_sin_docente',$id_doc_est);
                $crud->columns('estudiante','uv_pera','uv_asignadas','ciclo','id_docente');
                $crud->unset_fields('id_tipo_pera','observaciones','comentario','due_sin_docente','uv_asignables','docente_general','id_detalle_pera');
            }  
            if($id_cargo =='10'){ //Docente PERA
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->where('per_view_asi_doc.id_docente',$id_doc_est);
                $crud->columns('estudiante','uv_asignadas','ciclo','comentario');
                $crud->unset_fields('id_tipo_pera','observaciones','uv_pera','due_sin_docente','uv_asignables','docente_general','id_detalle_pera');
            } 
            if($id_cargo =='3'){ //Docente General PERA
                $crud->where('docente_general',$id_doc_est);
                $crud->columns('estudiante','uv_pera','uv_asignadas','ciclo','observaciones','id_docente','comentario');
                $crud->unset_fields('id_tipo_pera','due_sin_docente','uv_asignables','docente_general','id_detalle_pera');
            } 


            //$crud->where('docente_general',$this->docente_general);
            
            //$crud->unset_read();
            
            
            
            //$crud->where('view_asi_doc_2.area_deficitaria !=','PER'); // si se desea incorporar un where a la tabla/vista
            //Definiendo los input que apareceran en el formulario de inserción/edición
            //$crud->fields('id_perfil','ciclo','anio','objetivo_general','objetivo_especifico','descripcion'); 
            //$crud->fields('id_due','area_deficitaria','id_docente');
            
            
            //$crud->set_primary_key('docente','per_view_gen_docente'); 
            $crud->set_primary_key('id_due','per_view_asi_doc_estudiante'); 
            //
            //$crud->unset_columns(array('id'));

            
            
            //$crud->columns('id_tipo_pera','id_due','nombre','apellido','area_deficitaria','uv','ciclo','anio','id_docente');
            $crud->display_as('id_tipo_pera','No');        
            $crud->display_as('id_docente','Docente asignado');
            $crud->display_as('due_sin_docente','Estudiante');
            $crud->display_as('uv_pera','U.V. PERA');
            $crud->display_as('uv_asignadas','U.V. Asignadas');
            $crud->display_as('uv_asignables','U.V. Asignables');
            $crud->display_as('anio','A&ntilde;o');
            $crud->display_as('observaciones','Observaci&oacute;n de Areas Deficitarias');
            $crud->display_as('comentario','Comentario de la asignaci&#243;n');
            //$crud->unset_columns(array('id','due_sin_docente'));
            $crud->add_fields('due_sin_docente','uv_pera','ciclo','observaciones','id_docente','uv_asignables','uv_asignadas','comentario');
            $crud->edit_fields('estudiante','uv_pera','ciclo','observaciones','id_docente','uv_asignables','uv_asignadas','comentario');

            $crud->required_fields('id_docente','due_sin_docente','uv_asignadas','estudiante','comentario','uv_asignables');
            //$crud->required_fields('uv_asignadas','due_sin_docente');
            $crud->field_type('comentario', 'textarea');
            

            
            $crud->set_relation('id_docente','gen_docente','{nombre} {apellido}',array('id_cargo'=>'10'));
            
            $crud->set_relation('due_sin_docente','per_view_asi_doc_estudiante','{id_due} - {nombre} {apellido}',array('id_docente'=>$this->session->userdata('id_doc_est')));    
            
            //$crud->set_relation('due_sin_docente','per_view_asi_gen_estudiante','{id_due} - {nombre} {apellido}');
            
            //$crud->set_relation('id_due','gen_estudiante','{id_due} - {nombre} {apellido}');
            
            //$crud->set_relation('id_docente','per_view_gen_docente','docente');
            
            $crud->callback_insert(array($this,'Asignar'));
            //$crud->callback_edit_field('id_due',array($this,'solo_lectura_due'));
            $crud->callback_edit_field('estudiante',array($this,'solo_lectura_estudiante'));
            //$crud->callback_edit_field('apellido',array($this,'solo_lectura_apellido'));
            
            $crud->callback_field('observaciones',array($this,'solo_lectura_observaciones'));
            $crud->callback_field('uv_pera',array($this,'solo_lectura_uv_pera'));
            $crud->callback_field('ciclo',array($this,'solo_lectura_ciclo'));
            $crud->callback_field('anio',array($this,'solo_lectura_anio'));
            $crud->callback_field('comentario',array($this,'solo_lectura_comentario'));

            $crud->callback_field('uv_asignables',array($this,'solo_lectura_uv_asignables'));
            
            $crud->field_type('uv_asignadas','dropdown',array('2'=>'2','4'=>'4','8'=>'8','12'=>'12','16'=>'16'));  
            //$crud->field_type('uv_asignadas','dropdown');  


            // Validación de U.V.

            //$crud->set_rules('uv_asignables','mama1','required');
            $crud->set_rules('uv_asignadas','U.V. Asignadas','required',array('required' => 'El campo U.V. Asignadas es obligatorio y su valor debe ser menor o igual a U.V. Asignables '));

            //$crud->set_rules('uv_asignadas','','callback_uv_asignadas');
            //$crud->callback_before_insert(array($this,'validar_uv'));
            
            
            $crud->callback_update(array($this,'Actualizar'));
            
            $crud->callback_delete(array($this,'borrar_asignacion'));
            
          

            //$js = $categories->get_js();
            $output = $crud->render();
            //$output->output.= $js;

            $this->_AsiDoc_output($output);   
        }     
    }
    
    
    public function solo_lectura_due($valor){
     return '<input type="text" maxlength="50" value="'.$valor.'" name="id_due" width:100px readonly >';
    }
    public function solo_lectura_estudiante($valor){
        return '<input id="field-estudiante" class="form-control" type="text" value="'.$valor.'" name="estudiante" width:100px readonly >';
    }
    /*public function solo_lectura_apellido($valor,$primary_key){
     return '<input type="text" maxlength="50" value="'.$valor.'" name="apellido" width:100px readonly >';
    }*/
    public function solo_lectura_observaciones($valor,$primary_key){
     //return '<input type="text" maxlength="50" value="'.$valor.'" name="area_deficitaria" width:100px readonly >';
     return '<textarea id="field-observaciones" class="form-control" name="observaciones" type="text" maxlength="1000" readonly>'.$valor.'</textarea>';
    }
    public function solo_lectura_uv_pera($valor,$primary_key){
     
     return '<input id="field-uv_pera" class="form-control" name="uv_pera" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_uv_asignables($valor,$primary_key){
     
        return '<input id="field-uv_asignables" class="form-control" name="uv_asignables" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_ciclo($valor,$primary_key){
     
     return '<input id="field-ciclo" class="form-control" name="ciclo" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_anio($valor,$primary_key){
     
     return '<input id="field-anio" class="form-control" name="anio" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    
    public function solo_lectura_comentario($valor,$primary_key){
     
        return '<textarea id="field-comentario" class="form-control" name="comentario" maxlength="1000">'.$valor.'</textarea>';
    }
 
    public function Asignar($post_array)
    {

        if ($this->session->userdata('id_cargo')=='3'){
             
            $insertar['id_due']=$post_array['due_sin_docente'];    
            $insertar['id_docente']=$post_array['id_docente'];    
            $insertar['uv_asignadas']=$post_array['uv_asignadas'];    
            $insertar['comentario']=$post_array['comentario'];    
            $insertar['docente_general']=$this->session->userdata('id_doc_est');


            $comprobar=$this->AsiDoc_model->Asignar($insertar);

            if($comprobar>=1){
                $id_due = $insertar['id_due'];
                $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);
                $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
                $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);

                $ciclo = $post_array['ciclo'];
                $comentario = $insertar['comentario'];

                $id_docente = $insertar['id_docente'];
                $id_login_docente = $this->AsiGen_model->ObtenerIdLogin_Docente($id_docente);
                $docente = $this->AsiGen_model->ObtenerDocente($id_docente);
                $correo_docente = $this->AsiGen_model->ObtenerCorreoUsuario($id_login_docente);


                //ENVIAR EMAIL a Estudiante al que se le asigna el docente
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
                $this->email->to($correo_estudiante);
                $this->email->subject('Notificacion: Asignacion de Docente Asesor de Tipo de PERA');
                $this->email->message('<p>Se le ha asignado como Docente Asesor del Tipo de PERA a '.$docente.' para el ciclo '.$ciclo.'.</p>');
                $this->email->set_mailtype('html');
                $this->email->send();

                //ENVIAR EMAIL al Docente General al que se le asigna el Estudiante
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
                $this->email->to($correo_docente);
                $this->email->subject('Notificacion: Asignacion como Docente Asesor de Tipo de PERA');
                $this->email->message('<p>Se le ha asignado como Estudiante del PERA a '.$estudiante.' para el ciclo '.$ciclo.'.</p>
                                        <p>Con el siguiente comentario al respecto: '.$comentario.'</p>');
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
    
    public function Actualizar($post_array,$primary_key)
    {
        $filas = $this->AsiDoc_model->ComprobarFK($primary_key);
        if($filas == 0){
            if($this->session->userdata('id_cargo')=='3'){

                $id_due = $post_array['estudiante'];
                $id_due = substr($id_due,0,7);    
            
                $insertar['id_due']=$id_due;
                $insertar['id_docente']=$post_array['id_docente'];    
                $insertar['id_tipo_pera']=$primary_key;
                $insertar['docente_general']=$this->session->userdata('id_doc_est');                    
                $insertar['comentario']=$post_array['comentario'];
                $insertar['uv_asignadas']=$post_array['uv_asignadas'];          


                            
                                    
                $comprobar=$this->AsiDoc_model->Actualizar($insertar);                


                $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);
                $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
                $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);

                $ciclo = $post_array['ciclo'];
                $comentario = $insertar['comentario'];

                $id_docente = $insertar['id_docente'];
                $id_login_docente = $this->AsiGen_model->ObtenerIdLogin_Docente($id_docente);
                $docente = $this->AsiGen_model->ObtenerDocente($id_docente);
                $correo_docente = $this->AsiGen_model->ObtenerCorreoUsuario($id_login_docente);


                //ENVIAR EMAIL a alumno al que se le asigna el docente
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
                $this->email->to($correo_estudiante);
                $this->email->subject('Notificacion: Actualizacion de asignacion de Docente Asesor de Tipo de PERA');
                $this->email->message('<p>Se le ha asignado como Docente Asesor del Tipo de PERA a '.$docente.' para el ciclo '.$ciclo.'.</p>');
                $this->email->set_mailtype('html'); 
                $this->email->send();

                //ENVIAR EMAIL al Docente General al que se le asigna el Estudiante
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
                $this->email->to($correo_docente);
                $this->email->subject('Notificacion: Actualizacion de asignacion como Docente Asesor de Tipo de PERA');
                $this->email->message('<p>Se le ha asignado como Estudiante del PERA a '.$estudiante.' para el ciclo '.$ciclo.'.</p>
                                    <p>Con el siguiente comentario al respecto: '.$comentario.'</p>');
                $this->email->set_mailtype('html');
                $this->email->send();

                return true;   
            }
        }
        else
            return false;              
    }

    
    public function borrar_asignacion($primary_key)
    {
      
        $usuario = $this->AsiDoc_model->Obtener_usuarios_pera($primary_key);

        $filas = $this->AsiDoc_model->ComprobarFK($primary_key);
        if($filas == 0){
            $comprobar=$this->AsiDoc_model->borrar_Asignacion($primary_key, $this->session->userdata('id_doc_est'));

            if($comprobar>=1){                

                $id_due = $usuario['id_due'];
                $id_docente = $usuario['id_docente'];
                $ciclo = $usuario['ciclo'];
                
                $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);                
                $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
                $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);                
                                
                $docente = $this->AsiGen_model->ObtenerDocente($id_docente);
                $id_login_docente = $this->AsiGen_model->ObtenerIdLogin_Docente($id_docente);
                $correo_docente = $this->AsiGen_model->ObtenerCorreoUsuario($id_login_docente);


                //ENVIAR EMAIL a alumno al que se le asigna el docente
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
                $this->email->to($correo_estudiante);
                $this->email->subject('Notificacion: Eliminacion de asignacion de Docente Asesor de Tipo de PERA');
                $this->email->message('<p>Se ELIMIN&Oacute; su asignaci&oacute;n al Docente Asesor del Tipo de PERA '.$docente.' para el ciclo '.$ciclo.'.</p>');
                $this->email->set_mailtype('html');
                $this->email->send();

                //ENVIAR EMAIL al Docente General al que se le asigna el Estudiante
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
                $this->email->to($correo_docente);
                $this->email->subject('Notificacion: Eliminacion de asignacion como Docente Asesor de Tipo de PERA');
                $this->email->message('<p>Se ELIMIN&Oacute; su asignaci&oacute;n al Tipo de PERA del Estudiante '.$estudiante.' para el ciclo '.$ciclo.'.</p>');
                $this->email->set_mailtype('html');
                $this->email->send();

                return true;   
            }
            else             
                return false; 
        }
        else
            return false;
    }
    
    
    public function Consultar($id_due){
        //echo $id_due;
		echo json_encode($this->AsiDoc_model->Consultar($id_due));
		//echo "aun no hay submit";
        

	}

    public function uv_asignables($id_due){

        echo json_encode($this->AsiDoc_model->uv_asignables($id_due));
    }


    public function uv_asignadas($id_due){

        $uv_sobrantes = $this->AsiDoc_model->uv_asignables($id_due);
        foreach ($uv_sobrantes as $value) {
            echo $value->uv_pera;
            echo $value->uv_total_asignadas;
            echo $value->uv_pera-$value->uv_total_asignadas;
            $uv_disponibles = $value->uv_pera-$value->uv_total_asignadas;
        }
        echo "<div>uv_disponibles:".$uv_disponibles."</div>";      
    }

}