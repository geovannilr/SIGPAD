<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegEva extends CI_Controller {
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
            $this->load->model('PERA/EstEva_model'); 
        }
        else{
            redirect('Login');
        }
    }
    public function RegEva_output($output=null){
        $data['output']=$output;
        $data['contenido']='PERA/FormRegEva';
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

            $crud->set_subject('Evaluación'); 

            $crud->set_primary_key('id_evaluacion');

            // where por tipo de usuario
            if($tipo  == 'Estudiante'){ //Estudiante PERA
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->where('id_due',$id_doc_est);
            }  
            if($id_cargo =='10'){ //Docente PERA                
                $crud->where('per_view_est_eva.id_docente',$id_doc_est);

            } 
            if($id_cargo =='3'){ //Docente General PERA
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->where('docente_general',$id_doc_est);
            } 

                                                                            
            $crud->columns('estudiante','ciclo','tipo','descripcion_pera','nombre','fecha','descripcion_evaluacion','porcentaje','nota');

            //$crud->unset_columns(array('id_evaluacion','estudiante_pera','tipo_pera'));
            $crud->unset_add();
            $crud->unset_delete();
            
            //$crud->columns('id_detalle_pera','tipo','uv','descripcion','inicio','fin');

            $crud->display_as('id_tipo_pera','Materia / Proyecto');
            $crud->display_as('tipo','Materia / Proyecto');     
            $crud->display_as('descripcion_pera','Descripción PERA');
            $crud->display_as('nombre','Evaluación');
            $crud->display_as('descripcion_evaluacion','Descripción');


            $crud->edit_fields('estudiante','ciclo','tipo','descripcion_pera','nombre','fecha','descripcion_evaluacion','porcentaje','nota');    
                       
                     
            $crud->unset_texteditor('descripcion_evaluacion','full_text');
            $crud->unset_texteditor('descripcion_pera','full_text');
            $crud->unset_fields('id_evaluacion');
            //$crud->add_fields('id_due','id_tipo_pera','nombre','fecha','descripcion','porcentaje','nota');
            
            $crud->unset_fields('id_evaluacion','id_tipo_pera','id_docente','id_due','docente_general','id_detalle_pera');

            //$crud->field_type('office_id', 'hidden', $office_id);
            //$crud->field_type('nota','true_false');
            

            $crud->callback_field('nombre',array($this,'solo_lectura_nombre'));
            $crud->callback_field('porcentaje',array($this,'solo_lectura_porcentaje'));

            $crud->callback_field('estudiante',array($this,'solo_lectura_estudiante'));
            $crud->callback_field('tipo',array($this,'solo_lectura_tipo'));
            $crud->callback_field('ciclo',array($this,'solo_lectura_ciclo'));
            $crud->callback_field('descripcion_pera',array($this,'solo_lectura_descripcion_pera'));
            $crud->callback_field('descripcion_evaluacion',array($this,'solo_lectura_descripcion_evaluacion'));

            // Validacion
            $crud->set_rules('nota','Nota','required|greater_than_equal_to[0]|less_than_equal_to[10]',
                array('greater_than_equal_to'=>'La Nota no puede ser menor que 0','less_than_equal_to'=>'La Nota no puede ser mayor que 10'));
            $crud->set_rules('fecha','Fecha',
                array(
                    'required',
                    'regex_match[/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/]'),
                array(
                        'regex_match'     => 'La Fecha debe tener el formato dd/mm/yyyy'
                )   
            ); 


            $crud->required_fields('descripcion_evaluacion');         
            
            $crud->callback_insert(array($this,'Establecer'));
            $crud->callback_delete(array($this,'Eliminar'));
            $crud->callback_update(array($this,'Actualizar'));

            
            $output = $crud->render();
            

            $this->RegEva_output($output);  
        }             
    
    }
    
  
    public function solo_lectura_nombre($valor,$primary_key){
        return '<input type="text" class="form-control" maxlength="50" value="'.$valor.'" name="nombre" width:100px readonly >';
    }
    public function solo_lectura_porcentaje($valor){     
        return '<input id="field-porcentaje" class="form-control" name="porcentaje" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_estudiante($valor,$primary_key){
        return '<input id="field-estudiante" class="form-control" type="text" maxlength="100" value="'.$valor.'" name="estudiante" width:500px readonly >';
    }

    public function solo_lectura_tipo($valor,$primary_key){
     
        return '<input id="field-tipo" class="form-control" name="tipo" type="text" value="'.$valor.'" maxlength="100" readonly />';
    }
    public function solo_lectura_ciclo($valor){
     
        return '<input id="field-ciclo" class="form-control" name="ciclo" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_descripcion_pera($valor){
     
        return '<textarea id="field-descripcion_pera" class="form-control" name="descripcion_pera" maxlength="1000" readonly>'.$valor.'</textarea>';
    }
    public function solo_lectura_descripcion_evaluacion($valor){
     
        return '<textarea id="field-descripcion_evaluacion" class="form-control" name="descripcion_evaluacion" maxlength="1000">'.$valor.'</textarea>';
    }

  

 
	 public function Establecer($post_array)
    {
		
		//$insertar['id_due']=$post_array['due_sin_docente'];    
		$insertar['id_tipo_pera']=$post_array['id_tipo_pera'];    
		$insertar['nombre']=$post_array['nombre'];
		$insertar['fecha']=$post_array['fecha'];
		$insertar['descripcion']=$post_array['descripcion'];
		$insertar['porcentaje']=$post_array['porcentaje'];
		$insertar['nota']=$post_array['nota'];		
		$comprobar=$this->EstEva_model->Establecer($insertar);
			
    }
    
	public function Eliminar($primary_key){
    	
     	$comprobar=$this->EstEva_model->Eliminar($primary_key);    	
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
		//$insertar['descripcion']=$post_array['descripcion_evaluacion']." ".$post_array['comentario'];        
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
            $this->email->subject('Notificación: Registro de Evaluación del PERA');
            $this->email->message('<p>Tiene una nueva nota en la evaluaci&oacute;n '.$nombre_evaluacion.' del PERA '.$post_array['descripcion_pera'].'</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            return true;   
        }
        else 
        {
            return false;
        }



		
	}
    
    
    public function AreaDeficitaria()
    {
        
        if($this->input->post())
        {
            $this->load->model('PERA/AsiDoc_model');
            $id_due['id_due']=$this->input->post('Estudiante');    
            //$insertar['id_docente']=$this->input->post('Asesor');    
                   
            $data['AreaDeficitaria']=$this->AsiDoc_model->AreaDeficitaria($id_due);
            //$data['AreaDeficitaria'] = 'Cambio';
            foreach($data['AreaDeficitaria'] as $row):
                echo $row['area_deficitaria'];
            endforeach;
        }
        else 
        {
            echo "aun no hay submit";
        }
    }


}