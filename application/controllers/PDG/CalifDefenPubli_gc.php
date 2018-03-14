<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CalifDefenPubli_gc extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        if($id_cargo_administrativo=='6' and ($id_cargo == '2' or $id_cargo == '5' or $id_cargo== '6')){
                        
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/CalifDefenPubli_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _CalifDefenPubli_gc_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormCalifDefenPubli_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $id_doc_est = $this->session->userdata('id_doc_est');
            $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
            $id_cargo = $this->session->userdata('id_cargo');


            $crud = new grocery_CRUD();
            
            //Seteando la tabla o vista
            $crud->set_table('pdg_nota_defensa_publica');
            if($id_cargo_administrativo=='6' and $id_cargo == '2'){
                //Encontrar id_equipo_tg en base al id_docente logueado  (Docente asesor)
                /*$id_equipo_tg_filtrar_docente=$this->CalifDefenPubli_gc_model->EncontrarIdEquipoFiltrarDocente($id_doc_est);               
                $crud->where('id_equipo_tg',$id_equipo_tg_filtrar_docente);    */
                $crud->where('id_docente',$id_doc_est); 
                $crud->where('estado_nota','A');              
            }

            if($id_cargo_administrativo=='6' and $id_cargo == '5'){
                //Encontrar id_equipo_tg en base al id_docente logueado  (Docente tribunal evaluador 1)
                /*$id_equipo_tg_filtrar_docente_ase1=$this->CalifDefenPubli_gc_model->EncontrarIdEquipoFiltrarDocenteAse1($id_doc_est);               
                $crud->where('id_equipo_tg',$id_equipo_tg_filtrar_docente_ase1); */   
                $crud->where('id_docente',$id_doc_est); 
                $crud->where('estado_nota','A');
            }

            if($id_cargo_administrativo=='6' and $id_cargo== '6'){
                //Encontrar id_equipo_tg en base al id_docente logueado  (Docente tribunal evaluador 2)
                /*$id_equipo_tg_filtrar_docente_ase2=$this->CalifDefenPubli_gc_model->EncontrarIdEquipoFiltrarDocenteAse2($id_doc_est);               
                $crud->where('id_equipo_tg',$id_equipo_tg_filtrar_docente_ase2); */
                $crud->where('id_docente',$id_doc_est); 
                $crud->where('estado_nota','A');
            }
            
            /*************************************/
            //Esta solamente esta de prueba mientras flroes no pase el cambio de usuarios
            //////////////////$crud->where('id_docente','ok87984');
            /************************************/
            $crud->order_by('CAST(pdg_nota_defensa_publica.id_nota_defensa_publica AS UNSIGNED)','asc');
            $crud->set_language('spanish');         
            $crud->set_subject('Defensa Pública'); 
            $crud->columns(array('id_nota_defensa_publica','id_equipo_tg','tema','anio_tg','ciclo_tg','id_due',
                        'estado_nota','nota_defensa_publica')); 
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_nota_defensa_publica','Correlativo')
                 ->display_as('id_equipo_tg','Id. Equipo TG')            
                 ->display_as('tema','Tema TG')  
                 ->display_as('anio_tg','Año TG')  
                 ->display_as('ciclo_tg','Ciclo TG') 
                 ->display_as('id_due','DUE') 
                 ->display_as('estado_nota','Estado Nota')  
                 ->display_as('nota_defensa_publica','Nota Defensa Pública');

            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_nota_defensa_publica','id_equipo_tg','tema','anio_tg','ciclo_tg','id_due',
                        'estado_nota','nota_defensa_publica');
            //Definiendo validaciones para formulario de edición
            $crud->callback_edit_field('id_nota_defensa_publica',array($this,'idNotaDefensaPublicaSoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tg',array($this,'anioTGSoloLectura'));    
            $crud->callback_edit_field('ciclo_tg',array($this,'cicloTGSoloLectura'));  
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            $crud->callback_edit_field('estado_nota',array($this,'estadoNotaSoloLectura'));
            $crud->field_type('estado_nota','dropdown',array('A' => 'Abierta','C' => 'Cerrada'));
            ///$crud->callback_edit_field('nota_defensa_publica',array($this,'notaDefensaPublicaSoloLectura'));

            $crud->set_rules('nota_defensa_publica','nota_defensa_publica','callback_checar_nota_defensa_publica');


            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete(); 
            $crud->callback_update(array($this,'actualizacion_CalifDefenPubli'));           
            $output = $crud->render();

            $this->_CalifDefenPubli_gc_output($output); 


    }
    //Para Edit forms
    //Validaciones de rango de notas
   public function checar_nota_defensa_publica($nota_defensa_publica)
    {

       if(is_numeric($nota_defensa_publica)) 
       {
            if ($nota_defensa_publica < 0 or $nota_defensa_publica > 10)
            {
                $this->form_validation->set_message('checar_nota_defensa_publica', "La nota de la Defensa Pública debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_defensa_publica', "La nota de la Defensa Pública solo admite valores numericos");
            return FALSE;
       }
    }           
 
 
   //Edicion de solo lectura
   function idNotaDefensaPublicaSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_nota_defensa_publica" name="field-id_nota_defensa_publica" value="'.$value.'" readonly>';
    }   
    function idEquipoTgSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_equipo_tg" name="field-id_equipo_tg" value="'.$value.'" readonly>';
    }   
    function agrandaCajaTexto($value){
        return '<textarea class="form-control" id="field-tema" name="field-tema" readonly>'.$value.'</textarea>';
    }   
    function anioTGSoloLectura($value){
        return '<input class="form-control" type="text" id="field-anio_tg" name="field-anio_tg" value="'.$value.'" readonly>';
    }   
    function cicloTGSoloLectura($value){
        return '<input class="form-control" type="text" id="field-ciclo_tg" name="field-ciclo_tg" value="'.$value.'" readonly>';
    }       
    function idDueSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_due" name="field-id_due" value="'.$value.'" readonly>';
    }       
    function estadoNotaSoloLectura($value){
        if($value=='A'){
            $value='Abierta';
        }
        if($value=='C'){
            $value='Cerrada';
        }        
        return '<input class="form-control" type="text" id="field-estado_nota" name="field-estado_nota" value="'.$value.'" readonly>';
    }   
                   

  public   function actualizacion_CalifDefenPubli($post_array){                       
                     
   
            $update['id_nota_defensa_publica']=$post_array['field-id_nota_defensa_publica'];
            $update['nota_defensa_publica']=$post_array['nota_defensa_publica'];
           
            $comprobar=$this->CalifDefenPubli_gc_model->Update($update);
            if ($comprobar >=1){
                $huboError=0;
                //return true;
            }
            else{
                //significa que no se realizo la operacion DML
                $huboError=1;
                //return false;
            }                    

        }
}        
?>