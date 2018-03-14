<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AsiGen extends CI_Controller {
    function __construct(){
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        $id_doc_est = $this->session->userdata('id_doc_est');
        $nombre = $this->session->userdata('nombre');
        $apellido = $this->session->userdata('apellidos');
        
        if($id_cargo == '3' or
            ($tipo == 'Estudiante' and 
                ($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
            //$this->removeCache();
            $this->load->helper('url');
            $this->load->library('grocery_CRUD');
            $this->load->model('PERA/AsiGen_model');
            $this->load->library('session');        
        }
        else{
            redirect('Login');
        }                       
    }
    
    public function _AsiGen_output($output=null){
        $data['output']=$output;
        $data['contenido']='PERA/FormAsiGen';

        $id_login = $this->session->userdata('id_login'); // id del usuario
        $id_doc_est = $this->session->userdata('id_doc_est'); // id del usuario
        $nombre=$this->session->userdata('nombre');
        $apellidos=$this->session->userdata('apellidos');
        $email = $this->session->userdata('email');
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

        //$data['estado'] = $this->grocery_CRUD->getState();
        //$data['info_estado'] = $this->grocery_CRUD->getStateInfo();

        $this->load->view('templates/content', $data);

        
        
    }
    
    public function index(){
        
        /*$this->session->set_userdata('nombre',$nombre);
        $this->session->set_userdata('apellidos',$apellido);
        $this->session->set_userdata('id_login',$id_login);
        $this->session->set_userdata('email',$email);*/        

        $id_doc_est = $this->session->userdata('id_doc_est'); // id del usuario
        $tipo = $this->session->userdata('tipo'); //Estudiante o Docente
        $id_cargo = $this->session->userdata('id_cargo');// 3 o 10 PERA
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');//-1 Estud        
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        
     
        //if($tipo == 'Estudiante' or $tipo == 'Docente'){
        if($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7' or $id_cargo == '3'){

            $crud = new grocery_CRUD();
            //introduciendo la vista:
            $crud->set_table('per_view_asi_gen');
            $crud->set_primary_key('id_detalle_pera'); 
            $crud->set_subject('Asesor General de Estudiante');
            $crud->set_language("spanish"); 
            $crud->unset_fields('due_sin_docente','d1','d2','d3','d4');

            if($tipo  == 'Estudiante'){
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->where('per_view_asi_gen.id_due',$id_doc_est);                
                $crud->unset_columns('observaciones');
                $crud->unset_fields('due_sin_docente','d1','d2','d3','d4','observaciones');
            }                       
            
            //$crud->set_theme('datatables');                    
            

            /*if($this->session->userdata('id_cargo_administrativo')<>1){
                $crud->where('id_login',$this->session->userdata('id_login'));
                $crud->unset_add();
            }*/
            
            //$crud->unset_read();
            
            
            
            //$crud->where('view_asi_doc_2.area_deficitaria !=','PER'); // si se desea incorporar un where a la tabla/vista
        
            $crud->set_primary_key('id_due','per_view_asi_gen_estudiante'); 
            //
            //$crud->unset_columns(array('id'));
            
            $crud->columns('id_due','nombre','apellido','cum','uv','ciclo','anio','id_docente','observaciones');
            $crud->display_as('id_detalle_pera','No');
            $crud->display_as('id_due','DUE');
            $crud->display_as('id_docente','Asesor General asignado');
            $crud->display_as('due_sin_docente','Estudiante');
            $crud->display_as('cum','CUM');
            $crud->display_as('uv','U.V.');
            $crud->display_as('anio','A&ntilde;o');

            $crud->display_as('d1','Programación y manejo de datos');
            $crud->display_as('d2','Comunicaciones y Ciencias de Computación');
            $crud->display_as('d3','Desarrollo de sistemas');
            $crud->display_as('d4','Administración');




            //$crud->unset_columns(array('id','due_sin_docente'));
            //$crud->fields('estado');
            $crud->add_fields('id_detalle_pera','due_sin_docente','cum','d1','d2','d3','d4','id_docente','uv','ciclo','anio','observaciones');
            $crud->edit_fields('id_detalle_pera','id_due','nombre','apellido','cum','d1','d2','d3','d4','id_docente','uv','ciclo','anio','observaciones');
            $crud->required_fields('id_docente','due_sin_docente','observaciones');
            
            
            // Ocultar el campo en el add y edit
            $crud->field_type('id_detalle_pera','hidden');
            
            
                  

                           
            //$crud->set_relation('nombre campo Listbox en el Add','tabla o view a relacionar','<-id-relacion-llave primaria de la tabla');
            
            
            $crud->set_relation('id_docente','gen_docente','{nombre} {apellido}',array('id_cargo'=>'3'));
            
            // REVISAR PARA BUSQUEDA
            $crud->set_relation('due_sin_docente','per_view_asi_gen_estudiante','{id_due} - {nombre} {apellido}');
            
            //$crud->set_relation('id_docente','per_view_gen_docente','docente');
            

            $crud->callback_edit_field('id_due',array($this,'solo_lectura_due'));
            $crud->callback_edit_field('nombre',array($this,'solo_lectura_nombre'));
            $crud->callback_edit_field('apellido',array($this,'solo_lectura_apellido'));
            
            //$crud->callback_field('id_detalle_pera',array($this,'solo_lectura_id_detalle_pera'));
            $crud->callback_field('cum',array($this,'solo_lectura_cum'));
            $crud->callback_field('uv',array($this,'solo_lectura_uv'));
            $crud->callback_field('ciclo',array($this,'solo_lectura_ciclo'));
            $crud->callback_field('anio',array($this,'solo_lectura_anio'));            
            $crud->callback_field('observaciones',array($this,'solo_lectura_obsevaciones'));

            $crud->callback_field('d1',array($this,'solo_lectura_d1'));
            $crud->callback_field('d2',array($this,'solo_lectura_d2'));
            $crud->callback_field('d3',array($this,'solo_lectura_d3'));
            $crud->callback_field('d4',array($this,'solo_lectura_d4'));

            
            //$crud->field_type('areas_deficitarias', 'text');

            //$crud->unset_texteditor('observaciones','full_text');

            //$crud->unset_texteditor('observaciones');

            
    		$crud->callback_insert(array($this,'Asignar'));
        
        	$crud->callback_update(array($this,'Actualizar'));
        
        	$crud->callback_delete(array($this,'borrar_asignacion'));


            /* READ Y EDIT

            $estado = $crud->getState();
            

            if ($estado == 'edit') {
                //redirect('Login');
                $info_estado = $crud->getStateInfo();
                
                echo $estado;
                echo ':'.$info_estado->primary_key;

                
            } else if($estado =='read') {
                //redirect('Login');
                $info_estado = $crud->getStateInfo();                                
                $id_read = $info_estado->primary_key;
                $id_detalle_pera = $this->AsiGen_model->comprobar_id($id_doc_est);
                echo $estado;
                echo ':'.$id_read;
                echo 'id_detalle_pera:'.$id_detalle_pera;
                if($id_detalle_pera != $id_read)
                    redirect('Login');                

            }     */                   

            //$crud->field_type('estado','dropdown',array('estado'=>$estado,'info_estado'=>$info_estado->primary_key));

            

            //$crud->field_type('estado','dropdown',array('info_estado'=>'Pelotudo'));

            //$crud->field_type('info_estado','hidden','info_estado');


            

            $output = $crud->render();


            $this->_AsiGen_output($output);            
        }     
        else{
            redirect('/');
        }   
    }
    
    
    public function solo_lectura_id_detalle_pera($valor){
        return '<input id="field-id_detalle_pera" type="text" class="form-control" maxlength="50" value="'.$valor.'" name="id_detalle_pera" readonly >';
    }
    public function solo_lectura_due($valor){
     return '<input id="field-id_due" type="text" class="form-control" maxlength="50" value="'.$valor.'" name="id_due" width:100px readonly >';
    }
    public function solo_lectura_nombre($valor,$primary_key){
     return '<input id="field-nombre" type="text" class="form-control" maxlength="50" value="'.$valor.'" name="nombre" width:100px readonly >';
    }
    public function solo_lectura_apellido($valor,$primary_key){
     return '<input id="field-apellido" type="text" class="form-control" maxlength="50" value="'.$valor.'" name="apellido" width:100px readonly >';
    }
    public function solo_lectura_cum($valor,$primary_key){
     //return '<input type="text" maxlength="50" value="'.$valor.'" name="area_deficitaria" width:100px readonly >';
     return '<input id="field-cum" class="form-control" name="cum" type="text" value="'.$valor.'" maxlength="50" width:100px readonly />';
    }
   public function solo_lectura_uv($valor,$primary_key){
     
     return '<input id="field-uv" class="form-control" name="uv" type="text" value="'.$valor.'" maxlength="50" width:100px readonly />';
    }
    public function solo_lectura_ciclo($valor,$primary_key){
     
    	return '<input id="field-ciclo" class="form-control" name="ciclo" type="text" value="'.$valor.'" maxlength="50" width:100px readonly />';
    }
    public function solo_lectura_anio($valor,$primary_key){
     
    	return '<input id="field-anio" class="form-control" name="anio" type="text" value="'.$valor.'" maxlength="50" width:100px readonly />';
    }
    public function solo_lectura_obsevaciones($valor,$primary_key){        
     
        return '<textarea id="field-observaciones" class="form-control" name="observaciones" maxlength="1000">'.$valor.'</textarea>';
    }

    /*public function solo_lectura_obsevaciones($valor,$primary_key){
     
    	return '<textarea id="field-observaciones" class="form-control" name="observaciones" maxlength="1000">'.$valor.'</textarea>';
    }*/
    public function solo_lectura_d1($valor,$primary_key){
     
        return '<textarea id="field-d1" class="form-control" name="d1" readonly wrap="hard" rows="5"></textarea>';
    }
    public function solo_lectura_d2($valor,$primary_key){
     
        return '<textarea id="field-d2" class="form-control" name="d2" readonly wrap="hard" rows="5"></textarea>';
    }
    public function solo_lectura_d3($valor,$primary_key){
     
        return '<textarea id="field-d3" class="form-control" name="d3" readonly wrap="hard" rows="5"></textarea>';
    }
    public function solo_lectura_d4($valor,$primary_key){
     
        return '<textarea id="field-d4" class="form-control" name="d4" readonly wrap="hard" rows="5"></textarea>';
    }

 
    public function Asignar($post_array)
    {

        $insertar['id_due']=$post_array['due_sin_docente'];    
        $insertar['id_docente']=$post_array['id_docente'];                
        $insertar['id_detalle_pera']=$post_array['id_detalle_pera'];
        $insertar['observaciones']=$post_array['observaciones'];                        
                                        
        $comprobar=$this->AsiGen_model->Asignar($insertar);
       
        if($comprobar>=1){
            $id_due = $insertar['id_due'];
            $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);
            $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
            $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);

            $ciclo = $post_array['ciclo'].'-'.$post_array['anio'];
            $observaciones = $insertar['observaciones'];

            $id_docente = $insertar['id_docente'];
            $id_login_docente = $this->AsiGen_model->ObtenerIdLogin_Docente($id_docente);
            $docente = $this->AsiGen_model->ObtenerDocente($id_docente);
            $correo_docente = $this->AsiGen_model->ObtenerCorreoUsuario($id_login_docente);


            //ENVIAR EMAIL a alumno al que se le asigna el docente
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
            $this->email->to($correo_estudiante);
            $this->email->subject('Notificación: Asignación del Asesor General del PERA');
            $this->email->message('<p>Se le asign&oacute; como Asesor General del PERA al docente '.$docente.' para el ciclo '.$ciclo.'.</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            //ENVIAR EMAIL al Docente General al que se le asigna el Estudiante
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
            $this->email->to($correo_docente);
            $this->email->subject('Notificación: Asignación como Asesor General del PERA');
            $this->email->message('<p>Se le asign&oacute; como Estudiante del PERA a '.$estudiante.' para el ciclo '.$ciclo.'.<br /> Con las siguientes observaciones al respecto: '.$observaciones.'</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            return true;   
        }
        else 
        {
            return false;
        }
    }
    
    public function Actualizar($post_array,$primary_key)
    {

        // Comprobación de registros dependientes de la Asignación que no puede ser Actualizada
        $filas = $this->AsiGen_model->ComprobarFK($primary_key); 
        // Si no existen registros ACTUALIZAR
        if($filas == 0){ 
            $insertar['id_due']=$post_array['id_due'];    
            $insertar['id_docente']=$post_array['id_docente'];
            $insertar['id_detalle_pera']=$primary_key;  
            $insertar['observaciones']=$post_array['observaciones'];            
                                                    
            $comprobar=$this->AsiGen_model->Actualizar($insertar);

            if($comprobar>=1){
                $id_due = $insertar['id_due'];
                $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);
                $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
                $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);

                $ciclo = $post_array['ciclo'].'-'.$post_array['anio'];
                $observaciones = $insertar['observaciones'];

                $id_docente = $insertar['id_docente'];
                $id_login_docente = $this->AsiGen_model->ObtenerIdLogin_Docente($id_docente);
                $docente = $this->AsiGen_model->ObtenerDocente($id_docente);
                $correo_docente = $this->AsiGen_model->ObtenerCorreoUsuario($id_login_docente);


                //ENVIAR EMAIL a alumno al que se le asigna el docente
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                $this->email->to($correo_estudiante);
                $this->email->subject('Notificación: Actualización de la asignación del Asesor General del PERA');
                $this->email->message('<p>Se le asign&oacute; como Asesor General del PERA al docente '.$docente.' para el ciclo '.$ciclo.'.</p>');
                $this->email->set_mailtype('html'); 
                $this->email->send();

                //ENVIAR EMAIL al Docente General al que se le asigna el Estudiante
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                $this->email->to($correo_docente);
                $this->email->subject('Notificación: Actualización de la asignación como Asesor General del PERA');
                $this->email->message('<p>Se le asign&oacute; como Estudiante del PERA a '.$estudiante.' para el ciclo '.$ciclo.'.<br /> Con las siguientes observaciones al respecto: '.$observaciones.'</p>');
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
    
     public function borrar_asignacion($primary_key)
    {
    
        $usuario = $this->AsiGen_model->Obtener_usuarios_pera($primary_key);

        $filas = $this->AsiGen_model->ComprobarFK($primary_key);
        if($filas == 0){
            $comprobar=$this->AsiGen_model->borrar_Asignacion($primary_key);

            //Si se eliminó Notificar 
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
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                $this->email->to($correo_estudiante);
                $this->email->subject('Notificación: Eliminación de la asignación del Asesor General del PERA');
                $this->email->message('<p>Se ha ELIMINADO su asignaci&oacute;n al Asesor General del PERA '.$docente.' para el ciclo '.$ciclo.'.</p>');
                $this->email->set_mailtype('html');
                $this->email->send();

                //ENVIAR EMAIL al Docente General al que se le asigna el Estudiante
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                $this->email->to($correo_docente);
                $this->email->subject('Notificación: Eliminación de la Asignación como Asesor General del PERA del Estudiante');
                $this->email->message('<p>Se ha ELIMINADO su asignaci&oacute;n al PERA del Estudiante '.$estudiante.' para el ciclo '.$ciclo.'.</p>');
                $this->email->set_mailtype('html');
                $this->email->send();

                return true;   
            }
            else             
                return false;  
                //*/
        }
        else
            return false;

              
        /*if($comprobar>=1)
            return true;   
        
        else 
        {
            return false;
        }*/
    }
    
    public function AreaDeficitaria()
    {
        
        if($this->input->post())
        {
            $this->load->model('PERA/AsiGen_model');
            $id_due['id_due']=$this->input->post('field-due_sin_docente');    
            //$insertar['id_docente']=$this->input->post('Asesor');    
                   
            $data['AreaDeficitaria']=$this->AsiGen_model->AreaDeficitaria($id_due);
            //$data['AreaDeficitaria'] = 'Cambio';
            foreach($data['AreaDeficitaria'] as $row):
                echo $row['area_deficitaria'];
                echo '~';
                echo $row['uv'];
                echo '~';
                echo $row['ciclo'];
                echo '~';
                echo $row['anio'];                
            endforeach;
        }
        else 
        {
            echo "aun no hay submit";
        }
    }
    
    public function AreaDeficitarias($id_due){
        //echo $id_due;
		echo json_encode($this->AsiGen_model->AreaDeficitarias($id_due));
		//echo "aun no hay submit";
        
        
        $array = array(1,2,3,4,5,6,99);       
        $arra = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
        //echo implode('~',$array);
        //echo json_encode($array); 
        //echo json_encode($arra);
      
        
        //echo json_encode('area_deficitaria');
	}

    public function Areas_Deficitarias($id_detalle_pera){

        echo json_encode($this->AsiGen_model->Areas_Deficitarias($id_detalle_pera));

    }


}