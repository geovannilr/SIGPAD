<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstEva extends CI_Controller {
    public function __construct(){
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
            $this->load->model('PERA/EstEva_model'); 
        }
        else{
            redirect('Login');
        }       
    }
    public function EstEva_output($output=null){
        $data['output']=$output;
        $data['contenido']='PERA/FormEstEva';
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
			$crud->set_table('per_view_est_eva');

            $crud->set_subject('Evaluacion'); 

            $crud->set_primary_key('id_evaluacion');

            $estado = $crud->getState();

            // where por tipo de usuario
            if($tipo  == 'Estudiante'){ //Estudiante PERA
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->where('id_due',$id_doc_est);
            }  
            if($id_cargo =='10'){ //Docente PERA                
                $crud->where('per_view_est_eva.id_docente',$id_doc_est);
                $crud->set_primary_key('id_tipo_pera','per_view_est_eva_id_tipo_pera');
                if($estado =='add') 
                    $crud->set_relation('id_tipo_pera','per_view_est_eva_id_tipo_pera','{id_tipo_pera}. {id_due} - {nombre} ({uv} U.V.)',array('id_docente' => $id_doc_est));                

            } 
            if($id_cargo =='3'){ //Docente General PERA
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->where('docente_general',$id_doc_est);
            } 
    		  				
			
			//$crud->set_primary_key('id_due','per_view_est_eva_estudiante'); 
			//$crud->set_primary_key('id_due','gen_estudiante');

			$crud->columns('estudiante','ciclo','tipo','descripcion_pera','nombre','fecha','descripcion_evaluacion','porcentaje');

			
			$crud->display_as('id_tipo_pera','Materia / Proyecto');
			$crud->display_as('tipo','Materia / Proyecto');		
			$crud->display_as('descripcion_pera','Descripción PERA');
			$crud->display_as('nombre','Evaluación');
			$crud->display_as('descripcion_evaluacion','Descripción');

			$crud->add_fields('id_tipo_pera','estudiante','ciclo','descripcion_pera','nombre','fecha','descripcion_evaluacion','porcentaje_disponible','porcentaje');
			$crud->edit_fields('id_tipo_pera','estudiante','ciclo','tipo','descripcion_pera','nombre','fecha','descripcion_evaluacion','porcentaje_disponible','porcentaje');

			$crud->unset_texteditor('descripcion_pera','full_text');
			$crud->unset_texteditor('descripcion_evaluacion','full_text');

			$crud->unset_fields('id_evaluacion','id_tipo_pera','nota','id_docente','id_due','docente_general','id_detalle_pera','porcentaje_disponible');
			//$crud->unset_read();
			$crud->unset_columns(array('id_evaluacion','nota')); 


			//Validación de entrada
			//$crud->field_type('fecha','date');   
			$crud->required_fields('id_tipo_pera','nombre','fecha','porcentaje','estudiante','descripcion_evaluacion','porcentaje_disponible');  

            // EDIT                        
            if($estado == 'edit')                                 
                $crud->field_type('id_tipo_pera','hidden');           
             
			
			// Validacion
            $crud->set_rules('porcentaje','Porcentaje','required|greater_than[0]|less_than_equal_to[1]|callback_validar_porcentaje[]',
                    array(
                        'less_than_equal_to'     => 'El campo Porcentaje no debe contener un número mayor que 1 (100%).'
                    )  
            );                           

            $crud->set_rules('fecha','Fecha',
                array(
                    'required',
                    'regex_match[/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/]'),
                array(
                        'regex_match'     => 'La Fecha debe tener el formato dd/mm/yyyy'
                )   
            );     


                                      
            


	        $crud->callback_field('estudiante',array($this,'solo_lectura_estudiante'));
	        $crud->callback_field('tipo',array($this,'solo_lectura_tipo'));
	        $crud->callback_field('ciclo',array($this,'solo_lectura_ciclo'));
	        $crud->callback_field('descripcion_pera',array($this,'solo_lectura_descripcion_pera'));
	        $crud->callback_field('descripcion_evaluacion',array($this,'solo_lectura_descripcion_evaluacion'));

            $crud->callback_field('porcentaje_disponible',array($this,'solo_lectura_porcentaje_disponible'));


			$crud->callback_insert(array($this,'Establecer'));
			$crud->callback_delete(array($this,'Eliminar'));
			$crud->callback_update(array($this,'Actualizar'));







			$output = $crud->render();


			$this->EstEva_output($output);  	
		}	      
    
    }

    public function solo_lectura_estudiante($valor,$primary_key){
    	return '<input id="field-estudiante" class="form-control" type="text" maxlength="100" value="'.$valor.'" name="estudiante" width:500px readonly >';
    }

    public function solo_lectura_tipo($valor,$primary_key){
     
        return '<input id="field-tipo" class="form-control" name="tipo" type="text" value="'.$valor.'" maxlength="100" readonly />';
    }
    public function solo_lectura_ciclo($valor,$primary_key){
     
        return '<input id="field-ciclo" class="form-control" name="ciclo" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_descripcion_pera($valor){
     
    	return '<textarea id="field-descripcion_pera" class="form-control" name="descripcion_pera" maxlength="1000" readonly>'.$valor.'</textarea>';
    }
    public function solo_lectura_descripcion_evaluacion($valor){
     
    	return '<textarea id="field-descripcion_evaluacion" class="form-control" name="descripcion_evaluacion" maxlength="1000">'.$valor.'</textarea>';
    }

    public function solo_lectura_porcentaje_disponible($valor){
     
        return '<input id="field-porcentaje_disponible" class="form-control" name="porcentaje_disponible" type="text" value="'.$valor.'" maxlength="4" readonly />';
    }





 
	public function Establecer($post_array){
		
		//$insertar['id_due']=$post_array['due_sin_docente'];    
		$insertar['id_tipo_pera']=$post_array['id_tipo_pera'];    
		$insertar['nombre']=$post_array['nombre'];

		
        $fecha = $post_array['fecha'];
        $fch = explode("/",$fecha);
        $fecha = $fch[2]."-".$fch[1]."-".$fch[0];
        $insertar['fecha'] = $fecha;

		$insertar['descripcion']=$post_array['descripcion_evaluacion'];
		$insertar['porcentaje']=$post_array['porcentaje'];
		$insertar['nota']=$post_array['nota'];		
		$comprobar=$this->EstEva_model->Establecer($insertar);

        if($comprobar>=1){
            $id_due = $post_array['estudiante'];

            $id_due = substr($id_due,0,7);

            $id_login = $this->EstEva_model->ObtenerIdLogin($id_due);
            $correo_estudiante = $this->EstEva_model->ObtenerCorreoUsuario($id_login);

            $nombre_evaluacion = $post_array['nombre'];
            //$ciclo = $post_array['ciclo'].'-'.$post_array['anio'];

            //$docente = $this->EstEva_model->ObtenerDocente($insertar['id_docente']);


            //ENVIAR EMAIL a alumno al que se le asigna el docente
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
            $this->email->to($correo_estudiante);
            $this->email->subject('Notificación: Establecimiento de Evaluación del PERA');
            $this->email->message('<p>Se le ha definido la evaluaci&oacute;n '.$nombre_evaluacion.' de su PERA '.$post_array['descripcion_pera'].'</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            return true;   
        }
        else 
        {
            return false;
        }
			
    }
    
	public function Eliminar($primary_key){
    	
     	$usuario = $this->EstEva_model->Obtener_usuarios_pera($primary_key);

        $comprobar=$this->EstEva_model->Eliminar($primary_key);

        if($comprobar>=1){

            $id_due = $usuario['id_due'];
            $nombre_evaluacion = $usuario['nombre'];
            $descripcion_pera = $usuario['descripcion_pera'];            

            $id_login = $this->EstEva_model->ObtenerIdLogin($id_due);
            $correo_estudiante = $this->EstEva_model->ObtenerCorreoUsuario($id_login);                    


            //ENVIAR EMAIL a alumno al que se le asigna el docente
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
            $this->email->to($correo_estudiante);
            $this->email->subject('Notificación: Eliminación de Evaluación del PERA');
            $this->email->message('<p>Se le ha ELIMINADO la evaluaci&oacute;n '.$nombre_evaluacion.' de su PERA '.$descripcion_pera.'</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            return true;   
        }
        else 
        {
            return false;
        }    	
  	}    
    
    
 
	public function Actualizar($post_array,$primary_key){
	
		//$insertar['id_due']=$post_array['due_sin_docente'];    
		$insertar['id_evaluacion']=$primary_key;    
		$insertar['nombre']=$post_array['nombre'];

        $fecha = $post_array['fecha'];
        $fch = explode("/",$fecha);
        $fecha = $fch[2]."-".$fch[1]."-".$fch[0];
        $insertar['fecha'] = $fecha;		

		$insertar['descripcion']=$post_array['descripcion_evaluacion'];
		$insertar['porcentaje']=$post_array['porcentaje'];
		$insertar['nota']=$post_array['nota'];		  				
		  
		$comprobar=$this->EstEva_model->Actualizar($insertar);

        if($comprobar>=1){
            $id_due = $post_array['estudiante'];

            $id_due = substr($id_due,0,7);

            $id_login = $this->EstEva_model->ObtenerIdLogin($id_due);
            $correo_estudiante = $this->EstEva_model->ObtenerCorreoUsuario($id_login);

            $nombre_evaluacion = $post_array['nombre'];
            //$ciclo = $post_array['ciclo'].'-'.$post_array['anio'];

            //$docente = $this->EstEva_model->ObtenerDocente($insertar['id_docente']);


            //ENVIAR EMAIL a alumno al que se le asigna el docente
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
            $this->email->to($correo_estudiante);
            $this->email->subject('Notificación: Actualización de Evaluación del PERA');
            $this->email->message('<p>Se le ha definido la evaluaci&oacute;n '.$nombre_evaluacion.' de su PERA '.$post_array['descripcion_pera'].'</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            return true;   
        }
        else 
        {
            return false;
        }
		
	}
    
    
    public function Consultar($id_tipo_pera){
        //echo $id_due;
		echo json_encode($this->EstEva_model->Consultar($id_tipo_pera));
		//echo "aun no hay submit";
        
        
        $array = array(1,2,3,4,5,6,99);       
        $arra = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
        //echo implode('~',$array);
        //echo json_encode($array); 
        //echo json_encode($arra);
      
        
        //echo json_encode('area_deficitaria');
	}


    // Validacion del porcentaje total de las evaluaciones por Tipo de PERA
    public function porcentaje_total($id_tipo_pera){

        echo json_encode($this->EstEva_model->porcentaje_total($id_tipo_pera));        
    }


    public function validar_porcentaje(){
        
        $porcentaje = $this->input->post('porcentaje');       

        $porcentaje_disponible = $this->input->post('porcentaje_disponible');

        if($porcentaje_disponible<0 OR $porcentaje_disponible>1){
            $this->form_validation->set_message('validar_porcentaje', "El valor del campo Porcentaje disponible debe estar entre 0.00 - 1.00 (0 - 100)%.");
            return FALSE;    
        }                    
        if($porcentaje<0.01 OR $porcentaje>1){
            $this->form_validation->set_message('validar_porcentaje', "El valor del campo Porcentaje debe estar entre 0.01 - 1.00 (1 - 100)%.");
            return FALSE;    
        }

        if ($porcentaje <= $porcentaje_disponible)
          {
                return TRUE;
          }
          else
          {
                $this->form_validation->set_message('validar_porcentaje', "El campo Porcentaje no debe ser mayor que el Porcentaje disponible.");
                return FALSE;
          }
    }


}