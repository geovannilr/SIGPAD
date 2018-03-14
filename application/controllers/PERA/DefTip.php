<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DefTip extends CI_Controller {
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
           	$this->load->library('gc_dependent_select');
            $this->load->model('PERA/DefTip_model');
            $this->load->model('PERA/AsiGen_model');
            $this->load->model('PERA/AsiDoc_model');

            $this->load->library('session');   
        }
        else{
            redirect('Login');
        }
    }
      
    public function _DefTip_output($output=null){
        $data['output']=$output;
        $data['contenido']='PERA/FormDefTip';

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
            
            //introduciendo la vista:
            $crud->set_table('per_view_def_tip');

            $crud->set_subject('Definición de Tipo de PERA'); 

            $crud->set_primary_key('id_tipo_pera'); 

            // where por tipo de usuario
            if($tipo  == 'Estudiante'){ //Estudiante PERA
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->where('id_due',$id_doc_est);
                $crud->columns('estudiante','ciclo','uv_asignadas','tipo','descripcion','inicio','fin');
                $crud->unset_fields('id_tipo_pera','id_due','id_docente','docente_general','id_detalle_pera','comentario');

            }  
            if($id_cargo =='10'){ //Docente PERA                
                $crud->where('per_view_def_tip.id_docente',$id_doc_est);
                $crud->columns('estudiante','ciclo','comentario','uv_asignadas','tipo','descripcion','inicio','fin');
                $crud->unset_fields('id_tipo_pera','id_due','id_docente','docente_general','id_detalle_pera');
            } 
            if($id_cargo =='3'){ //Docente General PERA
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->where('docente_general',$id_doc_est);
                $crud->columns('estudiante','ciclo','comentario','uv_asignadas','tipo','descripcion','inicio','fin');
                $crud->unset_fields('id_tipo_pera','id_due','id_docente','docente_general','id_detalle_pera');
            } 

                
      
            $crud->set_primary_key('id_detalle_pera','per_view_def_tip_estudiantes'); 
            //
            //$crud->unset_columns(array('id'));
            
            //$crud->columns('id_detalle_pera','tipo','uv','descripcion','inicio','fin');
            
            //$crud->fields('id_detalle_pera','tipo','materia','uv','descripcion','inicio','fin');


            
            $crud->fields('estudiante','ciclo','comentario','uv_asignadas','tipo','descripcion','inicio','fin');            

            $crud->required_fields('tipo','inicio','fin','descripcion');
            
            $crud->display_as('id_tipo_pera','Nº');
            $crud->display_as('tipo','Tipo de Desarrollo');
            $crud->display_as('uv_asignadas','U.V.');
            $crud->display_as('descripcion','Descripci&oacute;n');

            $crud->unset_texteditor('descripcion','full_text');
            
            //$crud->field_type('tipo','dropdown',array('Materia'=>'Cursar Materia','Proyecto'=>'Proyecto'));
            $crud->field_type('uv','dropdown',array('4'=>'4','8'=>'8','12'=>'12','16'=>'16'));
            
            

            $crud->callback_field('estudiante',array($this,'solo_lectura_estudiante'));
            $crud->callback_field('uv_asignadas',array($this,'solo_lectura_uv_asignadas'));
            $crud->callback_field('ciclo',array($this,'solo_lectura_ciclo'));
            $crud->callback_field('comentario',array($this,'solo_lectura_comentario'));
            $crud->callback_field('descripcion',array($this,'solo_lectura_descripcion'));
            

                           
            //$crud->set_relation('nombre campo Listbox en el Add','tabla o view a relacionar','<-id-relacion-llave primaria de la tabla');
            
            
            $crud->set_relation('tipo','gen_materia','{id_materia} - {nombre} ({uv} U.V.)');
            //$crud->set_relation('id_detalle_pera','per_detalle','{nombre} {apellido}',array('id_cargo'=>'3'));


            // Validaciones
            //$crud->set_rules('inicio','mama1','required');
            $crud->set_rules('inicio','Inicio',
                array(
                    'required',
                    'regex_match[/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/]'),
                array(
                        'regex_match'     => 'La Fecha inicio debe tener el formato dd/mm/yyyy'
                )   
            );

            $crud->set_rules('fin','Fin',
                array(
                    'required',
                    'regex_match[/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/]'),
                array(
                        'regex_match'     => 'La Fecha fin debe tener el formato dd/mm/yyyy'
                )   
            );

            $crud->set_rules('fin','Fin','callback_check_dates[inicio]');
            //$crud->set_rules('fin','Fin','required|greater_than_equal_to[inicio]');
            


            $crud->callback_update(array($this,'Actualizar'));
            $crud->callback_delete(array($this,'Eliminar'));
            

            $crud->unset_add();
            
          

            //$js = $categories->get_js();
            $output = $crud->render();
            //$output->output.= $js;

            $this->_DefTip_output($output);  
        }
                
    }

    /*public function solo_lectura_due($valor){
     return '<input type="text" maxlength="50" value="'.$valor.'" name="id_due" width:100px readonly >';
    }*/
    public function solo_lectura_estudiante($valor,$primary_key){
        return '<input id="field-estudiante" class="form-control" type="text" value="'.$valor.'" name="estudiante" width:500px readonly >';
    }
    /*public function solo_lectura_apellido($valor,$primary_key){
     return '<input type="text" maxlength="50" value="'.$valor.'" name="apellido" width:100px readonly >';
    }*/
    /*public function solo_lectura_observaciones($valor,$primary_key){
     //return '<input type="text" maxlength="50" value="'.$valor.'" name="area_deficitaria" width:100px readonly >';
     return '<input id="field-observaciones" class="form-control" name="observaciones" type="text" value="'.$valor.'" maxlength="1000" readonly />';
    }*/
    public function solo_lectura_uv_asignadas($valor,$primary_key){
     
        return '<input id="field-uv_asignadas" class="form-control" name="uv_asignadas" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_ciclo($valor,$primary_key){
     
        return '<input id="field-ciclo" class="form-control" name="ciclo" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_comentario($valor){
     
        return '<textarea id="field-comentario" class="form-control" name="comentario" maxlength="1000" readonly>'.$valor.'</textarea>';
    }
    public function solo_lectura_descripcion($valor){
     
        return '<textarea id="field-descripcion" class="form-control" name="descripcion" maxlength="1000">'.$valor.'</textarea>';
    }

    
    public function Actualizar($post_array,$primary_key)
    {
        /*if($this->input->post())
        {
            $this->load->model('PERA/AsiDoc_model');*/


        $filas = $this->DefTip_model->ComprobarFK($primary_key);
        if($filas == 0){

            $insertar['id_tipo_pera']=$primary_key;
            $insertar['tipo']=$post_array['tipo'];    
            $insertar['descripcion']=$post_array['descripcion'];


            $fecha = $post_array['inicio'];
            $fch = explode("/",$fecha);
            $fecha = $fch[2]."-".$fch[1]."-".$fch[0];
            $insertar['inicio'] = $fecha;

            $fecha = $post_array['fin'];
            $fch = explode("/",$fecha);
            $fecha = $fch[2]."-".$fch[1]."-".$fch[0];
            $insertar['fin'] = $fecha;
                        
                                    
            $comprobar=$this->DefTip_model->Actualizar($insertar);  

            if($comprobar>=1){

                $id_due = $post_array['estudiante'];
                $id_due = substr($id_due,0,7); 

                $tipo = $insertar['tipo'];

                $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);
                $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
                $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);

                $ciclo = $post_array['ciclo'];                

                $id_docente = $this->session->userdata('id_doc_est'); // id del usuario
                
                $id_login_docente = $this->AsiGen_model->ObtenerIdLogin_Docente($id_docente);
                $docente = $this->AsiGen_model->ObtenerDocente($id_docente);
                $correo_docente = $this->AsiGen_model->ObtenerCorreoUsuario($id_login_docente);


                //ENVIAR EMAIL a alumno al que se le asigna el docente
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
                $this->email->to($correo_estudiante);
                $this->email->subject('Notificacion: Definicion de Tipo de PERA');
                $this->email->message('<p>Su asesor '.$docente.' ha definido el Tipo de PERA a desarrollar. PERA inscrito en el ciclo '.$ciclo.'.</p>');
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
    
    public function Eliminar($primary_key)
    {
      
        $usuario = $this->AsiDoc_model->Obtener_usuarios_pera($primary_key);

        $filas = $this->DefTip_model->ComprobarFK($primary_key);
        if($filas == 0){
            $comprobar=$this->DefTip_model->Eliminar($primary_key);
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
                $this->email->subject('Notificacion: Eliminacion de definicion de Tipo de PERA');
                $this->email->message('<p>Su asesor '.$docente.' ha ELIMINADO el Tipo de PERA a desarrollar. PERA inscrito en el ciclo '.$ciclo.'.</p>');
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


    public function check_dates($fecha2, $fecha1){
        
        $partes = explode('/', $this->input->post('inicio'));
        //$fecha1 = join('-', $partes);
        $fecha1 = $partes[2]."-".$partes[1]."-".$partes[0];        

        $partes2 = explode('/', $this->input->post('fin'));
        //$fecha2 = join('-', $partes2);
        $fecha2 = $partes2[2]."-".$partes2[1]."-".$partes2[0];        

          if ($fecha2 > $fecha1)
          {
                return TRUE;
          }
          else
          {
                $this->form_validation->set_message('check_dates', "La fecha de inicio no puede ser igual o posterior a la fecha de finalización.");
                return FALSE;
          }
    }


}