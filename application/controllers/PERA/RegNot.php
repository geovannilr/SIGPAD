<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegNot extends CI_Controller {
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
           	$this->load->library('gc_dependent_select');
            $this->load->model('PERA/RegNot_model');
            $this->load->model('PERA/AsiGen_model'); 
        }
        else{
            redirect('Login');
        }        
    }
    public function RegNot_output($output=null){
        $data['output']=$output;
        $data['contenido']='PERA/FormRegNot';
        $this->load->view('templates/content', $data);        
        
    }
    

    public function index(){

        $id_doc_est = $this->session->userdata('id_doc_est'); // id del usuario
        $tipo = $this->session->userdata('tipo'); //Estudiante o Docente
        $id_cargo = $this->session->userdata('id_cargo');// 3 o 10 PERA
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');//-1 Estud        
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        
        $fecha_actual=date ("Y");
     
        //if($tipo == 'Estudiante' or $tipo == 'Docente'){
        if($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7' or $id_cargo == '3'){

            $crud = new grocery_CRUD();
            //introduciendo la vista:
            $crud->set_table('per_view_reg_not');
            $crud->set_primary_key('id_registro_nota'); 
            $crud->set_subject('Registro de Nota');
            $crud->set_language("spanish");
            $crud->set_primary_key('id_detalle_pera','per_view_reg_not_estudiante');   

            if($tipo  == 'Estudiante'){
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();
                //$crud->set_relation('id_detalle_pera','per_view_reg_not_estudiante','{id_due} - {nombre} {apellido}');
                $crud->where('due',$id_doc_est);

            }

            if($id_cargo =='3'){ //Docente General PERA
                $crud->where('docente',$id_doc_est);
                $crud->set_relation('id_detalle_pera','per_view_reg_not_estudiante','{id_due} - {nombre} {apellido}',
                    array('id_docente'=>$id_doc_est));
            }  

        
     
        
        //$crud->set_relation('nombre campo Listbox en el Add','tabla o view a relacionar','<-id-relacion-llave primaria de la tabla');
        
        $crud->columns('due','estudiante','estado','ciclo','anio','docente_mentor','fecha_finalizacion','descripcion');

        $crud->unset_fields('id_detalle_pera','docente');

        //$crud->fields('fecha_finalizacion');

        $crud->add_fields('id_registro_nota','id_detalle_pera','ciclo','anio','docente_mentor','fecha_finalizacion','descripcion','n1','p1','n2','p2','n3','p3','n4','p4','n5','p5','promedio','estado');

        $crud->edit_fields('id_registro_nota','due','estudiante','ciclo','anio','docente_mentor','fecha_finalizacion','descripcion','n1','p1','n2','p2','n3','p3','n4','p4','n5','p5','promedio','estado');

        //$crud->field_type('id_registro_nota','invisible');
        
        // Ocultar el campo en el add y edit
        $crud->field_type('id_registro_nota','hidden');
        $crud->field_type('estado','dropdown',array('f'=>'Finalizado','a'=>'Abandonado'));
        $crud->field_type('fecha_finalizacion','date');




        // Etiquetas
        $crud->display_as('due','DUE');
        $crud->display_as('fecha_finalizacion','Fecha de finalización');
        $crud->display_as('anio','Año');
        $crud->display_as('id_detalle_pera','Estudiante');  
        $crud->display_as('descripcion','Descripción');
        $crud->display_as('n1','Nota 1');
        $crud->display_as('n2','Nota 2');
        $crud->display_as('n3','Nota 3');
        $crud->display_as('n4','Nota 4');
        $crud->display_as('n5','Nota 5');
        $crud->display_as('p1','Porcentaje 1');
        $crud->display_as('p2','Porcentaje 2');
        $crud->display_as('p3','Porcentaje 3');
        $crud->display_as('p4','Porcentaje 4');
        $crud->display_as('p5','Porcentaje 5');

        //$crud->unset_texteditor('descripcion','full_text');

        $crud->required_fields('id_detalle_pera','estado','descripcion','fecha_finalizacion','promedio');


        $crud->callback_field('due',array($this,'solo_lectura_due'));
        $crud->callback_field('nombre',array($this,'solo_lectura_nombre'));
        $crud->callback_field('promedio',array($this,'solo_lectura_promedio'));
        $crud->callback_field('estudiante',array($this,'solo_lectura_estudiante'));
        $crud->callback_field('tipo',array($this,'solo_lectura_tipo'));

        $crud->callback_add_field('ciclo',array($this,'solo_lectura_ciclo'));
        $crud->callback_edit_field('ciclo',array($this,'solo_lectura_ciclo_2'));

        $crud->callback_add_field('anio',array($this,'solo_lectura_anio'));
        $crud->callback_edit_field('anio',array($this,'solo_lectura_anio_2'));

        $crud->callback_field('descripcion',array($this,'solo_lectura_descripcion'));
        $crud->callback_field('descripcion_evaluacion',array($this,'solo_lectura_descripcion_evaluacion'));

        $crud->callback_field('docente_mentor',array($this,'solo_lectura_docente_mentor'));

        $crud->callback_field('n1',array($this,'solo_lectura_n1'));
        $crud->callback_field('n2',array($this,'solo_lectura_n2'));
        $crud->callback_field('n3',array($this,'solo_lectura_n3'));
        $crud->callback_field('n4',array($this,'solo_lectura_n4'));
        $crud->callback_field('n5',array($this,'solo_lectura_n5'));


        $crud->callback_field('p1',array($this,'solo_lectura_p1'));
        $crud->callback_field('p2',array($this,'solo_lectura_p2'));
        $crud->callback_field('p3',array($this,'solo_lectura_p3'));
        $crud->callback_field('p4',array($this,'solo_lectura_p4'));
        $crud->callback_field('p5',array($this,'solo_lectura_p5'));


        //$crud->set_rules('fecha_finalizacion','Fin','required|regex_match[/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/]');

        $crud->set_rules('fecha_finalizacion','Fecha de finalizacion',
                array(
                    'required',
                    'regex_match[/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/]'),
                array(
                        'regex_match'     => 'La Fecha de finalizacion debe tener el formato dd/mm/yyyy'
                )   
            );


        // CRUD
        $crud->callback_insert(array($this,'insertar'));
        $crud->callback_update(array($this,'actualizar'));
        $crud->callback_delete(array($this,'eliminar'));



        $crud->add_action('Reporte de Notas','','','glyphicon glyphicon-download',array($this,'ReporteNota'));

        $output = $crud->render();
        

        $this->RegNot_output($output);  
    }
              
        else{
            redirect('/');
        }               
    }
    
    function ReporteNota($primary_key )
    {
            return site_url('PERA/GenReporteNotasPera_gc/generar/'.$primary_key );
    }
  
    public function solo_lectura_due($valor){
        return '<input  id="field-due" type="text" class="form-control" maxlength="50" value="'.$valor.'" name="due" width:100px readonly >';
    }
    public function solo_lectura_nombre($valor){
        return '<input  id="field-nombre" type="text" class="form-control" maxlength="50" value="'.$valor.'" name="nombre" width:100px readonly >';
    }
    
    public function solo_lectura_estudiante($valor){
        return '<input id="field-estudiante" class="form-control" type="text" maxlength="100" value="'.$valor.'" name="estudiante" width:500px readonly >';
    }

    public function solo_lectura_tipo($valor,$primary_key){
     
        return '<input id="field-tipo" class="form-control" name="tipo" type="text" value="'.$valor.'" maxlength="100" readonly />';
    }

    // Fechas automaticas del sistema
    public function solo_lectura_ciclo($valor){
     
        $mes = date('n');
        if($mes>2 and $mes<9)
            return '<input id="field-ciclo" class="form-control" name="ciclo" type="text" value="1" maxlength="50" readonly />';
        if($mes>8 or $mes<3)
            return '<input id="field-ciclo" class="form-control" name="ciclo" type="text" value="2" maxlength="50" readonly />';
    }
    public function solo_lectura_ciclo_2($valor){
        return '<input id="field-ciclo" class="form-control" name="ciclo" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }    

    public function solo_lectura_anio($valor){
     
        $anio = date('Y');
        return '<input id="field-anio" class="form-control" name="anio" type="text" value="'.$anio.'" maxlength="50" readonly />';
    }
    public function solo_lectura_anio_2($valor){     
        return '<input id="field-anio" class="form-control" name="anio" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }


    public function solo_lectura_descripcion($valor){
     
        return '<textarea id="field-descripcion" class="form-control" name="descripcion" maxlength="1000">'.$valor.'</textarea>';
    }

    // NOTAS 
    public function solo_lectura_n1($valor){     
        return '<input id="field-n1" class="form-control" name="n1" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_n2($valor){     
        return '<input id="field-n2" class="form-control" name="n2" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_n3($valor){     
        return '<input id="field-n3" class="form-control" name="n3" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_n4($valor){     
        return '<input id="field-n4" class="form-control" name="n4" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
    public function solo_lectura_n5($valor){     
        return '<input id="field-n5" class="form-control" name="n5" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }

    // PORCENTAJES
    public function solo_lectura_p1($valor){     
        return '<input id="field-p1" class="form-control" name="p1" type="text" value=0.15 maxlength="50" readonly />';
    }
    public function solo_lectura_p2($valor){     
        return '<input id="field-p2" class="form-control" name="p2" type="text" value=0.15 maxlength="50" readonly />';
    }
    public function solo_lectura_p3($valor){     
        return '<input id="field-p3" class="form-control" name="p3" type="text" value=0.20 maxlength="50" readonly />';
    }
    public function solo_lectura_p4($valor){     
        return '<input id="field-p4" class="form-control" name="p4" type="text" value=0.20 maxlength="50" readonly />';
    }
    public function solo_lectura_p5($valor){     
        return '<input id="field-p5" class="form-control" name="p5" type="text" value=0.30 maxlength="50" readonly />';
    }
    // Promedio
    public function solo_lectura_promedio($valor){     
        return '<input id="field-promedio" class="form-control" name="promedio" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }

    public function solo_lectura_docente_mentor($valor){    
        $nombre=$this->session->userdata('nombre');
        $apellidos=$this->session->userdata('apellidos');
        $valor=$nombre." ".$apellidos;

        return '<input id="field-docente_mentor" class="form-control" name="docente_mentor" type="text" value="'.$valor.'" maxlength="50" readonly />';
    }
  

 
	public function insertar($post_array)
    {
		
		//$insertar['id_due']=$post_array['due_sin_docente'];    
		$insertar['id_detalle_pera']=$post_array['id_detalle_pera'];    
		$insertar['ciclo']=$post_array['ciclo'];
		$insertar['anio']=$post_array['anio'];
		$insertar['docente_mentor']=$post_array['docente_mentor'];

		$fecha_normal = $post_array['fecha_finalizacion'];
        $fecha_mysql = $this->cambiaf_a_mysql($fecha_normal);        

        /*$fecha = $post_array['fecha_finalizacion'];
        $fch=explode("/",$fecha);
        $fecha=$fch[2]."-".$fch[1]."-".$fch[0];*/

        $insertar['fecha_finalizacion']=$fecha_mysql;

        $insertar['descripcion']=$post_array['descripcion'];

		$insertar['n1']=$post_array['n1'];
        $insertar['n2']=$post_array['n2'];
        $insertar['n3']=$post_array['n3'];
        $insertar['n4']=$post_array['n4'];
        $insertar['n5']=$post_array['n5'];
        $insertar['p1']=$post_array['p1'];
        $insertar['p2']=$post_array['p2'];
        $insertar['p3']=$post_array['p3'];
        $insertar['p4']=$post_array['p4'];
        $insertar['p5']=$post_array['p5'];
        $insertar['promedio']=$post_array['promedio'];

        $insertar['estado']=$post_array['estado'];	

        $primary_key = $insertar['id_detalle_pera'];
        $usuario = $this->AsiGen_model->Obtener_usuarios_pera($primary_key);	

		$comprobar=$this->RegNot_model->insertar($insertar);


        if($comprobar>=1){
            

            $id_due = $usuario['id_due'];
            $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);
            $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
            $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);

            $docente_mentor = $insertar['docente_mentor'];

            $estado = $insertar['estado'];
            if($estado=='f')
                $estado = 'Finalizado';
            else if($estado=='a')
                $estado = 'Abandonado';            
                    

            //ENVIAR EMAIL a Estudiante al que se le asigna el docente
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
            $this->email->to($correo_estudiante);
            $this->email->subject('Notificacion: Registro de Nota Final del PERA');
            $this->email->message('<p>Su Asesor General '.$docente_mentor.' ha registrado la nota del Programa Especial de Refuerzo Acad&eacute;mico con el estado de '.$estado.'.</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            return true;   
        }        
        else 
        {
            return false;
        }    
			
    }
    
	public function eliminar($primary_key){
    	     	
        $usuario = $this->RegNot_model->Obtener_usuarios_pera($primary_key);        
        
        $comprobar=$this->RegNot_model->eliminar($primary_key);   

        if($comprobar>=1){
            
            $id_due = $usuario['id_due'];
            $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);
            $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
            $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);

            $docente_mentor = $usuario['docente_mentor'];

            $estado = $usuario['estado'];
            if($estado=='f')
                $estado = 'Finalizado';
            else if($estado=='a')
                $estado = 'Abandonado';
                    


            //ENVIAR EMAIL a Estudiante al que se le asigna el docente
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
            $this->email->to($correo_estudiante);
            $this->email->subject('Notificacion: Eliminacion del Registro de Nota Final del PERA');
            $this->email->message('<p>Su Asesor General '.$docente_mentor.' ha ELIMINADO la nota del Programa Especial de Refuerzo Acad&eacute;mico con el estado de '.$estado.'.</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

                return true;   
        }        
        else 
        {
            return false;
        } 


  	}    
    
    
 
	public function actualizar($post_array,$primary_key){

        $insertar['id_registro_nota']=$primary_key;

        $fecha_normal = $post_array['fecha_finalizacion'];
        $fecha_mysql = $this->cambiaf_a_mysql($fecha_normal);             
        $insertar['fecha_finalizacion'] = $fecha_mysql;

        $insertar['descripcion']=$post_array['descripcion'];
        $insertar['estado']=$post_array['estado'];        

		$comprobar=$this->RegNot_model->actualizar($insertar);	

        //if($comprobar>=1){            

            $id_due = $post_array['due'];
            $estudiante = $this->AsiGen_model->Obtener_Estudiante($id_due);
            $id_login = $this->AsiGen_model->ObtenerIdLogin($id_due);
            $correo_estudiante = $this->AsiGen_model->ObtenerCorreoUsuario($id_login);

            $docente_mentor = $post_array['docente_mentor'];

            $estado = $insertar['estado'];
            if($estado =='f')
                $estado = 'Finalizado';
            else if($estado =='a')
                $estado = 'Abandonado';
                    


            //ENVIAR EMAIL a Estudiante al que se le asigna el docente
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informatico para el Control de Procesos Academicos-Administrativos UES');
            $this->email->to($correo_estudiante);
            $this->email->subject('Notificacion: Actualizacion del Registro de Nota Final del PERA');
            $this->email->message('<p>Su Asesor General '.$docente_mentor.' ha registrado la nota del Programa Especial de Refuerzo Acad&eacute;mico con el estado de '.$estado.'.</p>');
            $this->email->set_mailtype('html');
            $this->email->send();

            /*return true;   
        }        
        else 
        {
            return false;
        }  	*/
	}
    
    
    public function Nota_Final($id_detalle_pera){
        //echo $id_due;
        echo json_encode($this->RegNot_model->Nota_Final($id_detalle_pera));
        //echo "aun no hay submit";
        
        
        //echo implode('~',$array);
        //echo json_encode($array); 
        //echo json_encode($arra);
      
        
        //echo json_encode('area_deficitaria');
    }


    ////////////////////////////////////////////////////
    //Convierte fecha de mysql a normal
    ////////////////////////////////////////////////////
    function cambiaf_a_normal($fecha){
        ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
        $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1];
        return $lafecha;
    }

    ////////////////////////////////////////////////////
    //Convierte fecha de normal a mysql
    ////////////////////////////////////////////////////

    function cambiaf_a_mysql($fecha){
        ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
        $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
        return $lafecha;
    } 
}