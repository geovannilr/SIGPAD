<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CalifEtapa1_gc extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        if($id_cargo_administrativo=='6' and $id_cargo == '2'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/CalifEtapa1_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _CalifEtapa1_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormCalifEtapa1_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $id_doc_est = $this->session->userdata('id_doc_est');

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pdg_nota_etapa1');
            //Encontrar id_equipo_tg en base al id_docente logueado
            $id_equipo_tg_filtrar_docente=$this->CalifEtapa1_gc_model->EncontrarIdEquipoFiltrarDocente($id_doc_est);               
            foreach ($id_equipo_tg_filtrar_docente->result_array() as $row)
            {
                    $id_equipo_tg=$row['id_equipo_tg'];
                    $crud->or_where('id_equipo_tg',$id_equipo_tg); 
            }   
            
            $crud->set_relation('cod_criterio1','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio2','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio3','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio4','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio5','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio6','pdg_criterio','criterio');

            $crud->set_language('spanish');         
            $crud->set_subject('Etapa 1');  
            //Definiendo las columnas  que apareceran 
            $crud->columns('id_nota_etapa1','id_equipo_tg','tema','anio_tg','ciclo_tg','id_due',
                        'estado_nota','nota_documento',
                        'cod_criterio1','nota_criterio1','cod_criterio2','nota_criterio2',   
                        'cod_criterio3','nota_criterio3','cod_criterio4','nota_criterio4',  
                        'cod_criterio5','nota_criterio5','cod_criterio5','nota_criterio6',    
                        'nota_exposicion','nota_etapa1');

            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_nota_etapa1','Correlativo')
                 ->display_as('id_equipo_tg','Id. Equipo TG')            
                 ->display_as('tema','Tema TG')  
                 ->display_as('anio_tg','Año TG')  
                 ->display_as('ciclo_tg','Ciclo TG') 
                 ->display_as('id_due','DUE') 
                 ->display_as('estado_nota','Estado Nota')  
                 ->display_as('nota_documento','Nota Documento')
                 ->display_as('cod_criterio1','Cod. Criterio 1')  
                 ->display_as('nota_criterio1','Nota Criterio 1')  
                 ->display_as('cod_criterio2','Cod. Criterio 2')
                 ->display_as('nota_criterio2','Nota Criterio 2') 
                 ->display_as('cod_criterio3','Cod. Criterio 3')
                 ->display_as('nota_criterio3','Nota Criterio 3') 
                 ->display_as('cod_criterio4','Cod. Criterio 4')
                 ->display_as('nota_criterio4','Nota Criterio 4') 
                 ->display_as('cod_criterio5','Cod. Criterio 5')
                 ->display_as('nota_criterio5','Nota Criterio 5') 
                 ->display_as('cod_criterio6','Cod. Criterio 6')
                 ->display_as('nota_criterio6','Nota Criterio 6') 
                 ->display_as('nota_exposicion','Nota Exposición')
                 ->display_as('nota_etapa1','Nota Etapa 1');

            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_nota_etapa1','id_equipo_tg','tema','anio_tg','ciclo_tg','id_due',
                        'estado_nota','nota_documento',
                        'cod_criterio1','nota_criterio1','cod_criterio2','nota_criterio2',   
                        'cod_criterio3','nota_criterio3','cod_criterio4','nota_criterio4',  
                        'cod_criterio5','nota_criterio5','cod_criterio5','nota_criterio6',    
                        'nota_exposicion','nota_etapa1');
            //Definiendo validaciones para formulario de edición
            $crud->callback_edit_field('id_nota_etapa1',array($this,'idNotaEtapa1SoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tg',array($this,'anioTGSoloLectura'));    
            $crud->callback_edit_field('ciclo_tg',array($this,'cicloTGSoloLectura'));
            $crud->callback_edit_field('id_due',array($this,'idDueSoloLectura'));
            $crud->callback_edit_field('estado_nota',array($this,'estadoNotaSoloLectura'));
            //$crud->callback_edit_field('nota_documento',array($this,'notaDocumentoSoloLectura'));
            $crud->callback_edit_field('cod_criterio1',array($this,'codCriterio1SoloLectura'));
            $crud->callback_edit_field('cod_criterio2',array($this,'codCriterio2SoloLectura'));
            $crud->callback_edit_field('cod_criterio3',array($this,'codCriterio3SoloLectura'));
            $crud->callback_edit_field('cod_criterio4',array($this,'codCriterio4SoloLectura'));
            $crud->callback_edit_field('cod_criterio5',array($this,'codCriterio5SoloLectura'));
            $crud->callback_edit_field('cod_criterio6',array($this,'codCriterio6SoloLectura'));
            $crud->callback_edit_field('nota_exposicion',array($this,'notaExposicionSoloLectura'));
            $crud->callback_edit_field('nota_etapa1',array($this,'notaEtapa1SoloLectura'));
            $crud->field_type('estado_nota','dropdown',array('A' => 'Abierta','C' => 'Cerrada'));

            $crud->set_rules('nota_documento','nota_documento','callback_checar_nota_documento');
            $crud->set_rules('nota_criterio1','nota_criterio1','callback_checar_nota_criterio1');
            $crud->set_rules('nota_criterio2','nota_criterio2','callback_checar_nota_criterio2');
            $crud->set_rules('nota_criterio3','nota_criterio3','callback_checar_nota_criterio3');
            $crud->set_rules('nota_criterio4','nota_criterio4','callback_checar_nota_criterio4');
            $crud->set_rules('nota_criterio5','nota_criterio5','callback_checar_nota_criterio5');
            $crud->set_rules('nota_criterio6','nota_criterio6','callback_checar_nota_criterio6');
            

            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete(); 
            $crud->callback_update(array($this,'actualizacion_CalifEtapa1'));           
            $output = $crud->render();

            $this->_CalifEtapa1_output($output);


    }
    //Para Edit forms
    //Validaciones de rango de notas
   public function checar_nota_documento($nota_documento)
    {

       if(is_numeric($nota_documento)) 
       {
            if ($nota_documento < 0 or $nota_documento > 10)
            {
                $this->form_validation->set_message('checar_nota_documento', "La nota del documento debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_documento', "La nota del documento solo admite valores numericos");
            return FALSE;
       }
    }           
    public function checar_nota_criterio1($nota_criterio1)
    {

       if(is_numeric($nota_criterio1)) 
       {
            if ($nota_criterio1 < 0 or $nota_criterio1 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio1', "La nota del criterio 1 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio1', "La nota del criterio 1 solo admite valores numericos");
            return FALSE;
       }
    }
    public function checar_nota_criterio2($nota_criterio2)
    {

       if(is_numeric($nota_criterio2)) 
       {
            if ($nota_criterio2 < 0 or $nota_criterio2 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio2', "La nota del criterio 2 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio2', "La nota del criterio 2 solo admite valores numericos");
            return FALSE;
       }
    }
    public function checar_nota_criterio3($nota_criterio3)
    {

       if(is_numeric($nota_criterio3)) 
       {
            if ($nota_criterio3 < 0 or $nota_criterio3 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio3', "La nota del criterio 3 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio3', "La nota del criterio 3 solo admite valores numericos");
            return FALSE;
       }
    }
    public function checar_nota_criterio4($nota_criterio4)
    {

       if(is_numeric($nota_criterio4)) 
       {
            if ($nota_criterio4 < 0 or $nota_criterio4 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio4', "La nota del criterio 4 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio4', "La nota del criterio 4 solo admite valores numericos");
            return FALSE;
       }
    }
    public function checar_nota_criterio5($nota_criterio5)
    {

       if(is_numeric($nota_criterio5)) 
       {
            if ($nota_criterio5 < 0 or $nota_criterio5 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio5', "La nota del criterio 5 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio5', "La nota del criterio 5 solo admite valores numericos");
            return FALSE;
       }
    }
    public function checar_nota_criterio6($nota_criterio6)
    {

       if(is_numeric($nota_criterio6)) 
       {
            if ($nota_criterio6 < 0 or $nota_criterio6 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio6', "La nota del criterio 6 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio6', "La nota del criterio 6 solo admite valores numericos");
            return FALSE;
       }
    }
  
 
    public function checar_nota_exposicion($nota_exposicion)
    {

       if(is_numeric($nota_exposicion)) 
       {
            if ($nota_exposicion < 0 or $nota_exposicion > 10)
            {
                $this->form_validation->set_message('checar_nota_exposicion', "La nota del exposicion debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_exposicion', "La nota de la exposicion solo admite valores numericos");
            return FALSE;
       }
    }  
    public function checar_nota_etapa1($nota_etapa1)
    {

       if(is_numeric($nota_etapa1)) 
       {
            if ($nota_etapa1 < 0 or $nota_etapa1 > 10)
            {
                $this->form_validation->set_message('checar_nota_etapa1', "La nota de la Etapa 1 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_etapa1', "La nota de la Etapa 1 solo admite valores numericos");
            return FALSE;
       }
    }      
   //Edicion de solo lectura
   function idNotaEtapa1SoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_nota_etapa1" name="field-id_nota_etapa1" value="'.$value.'" readonly>';
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
    function notaDocumentoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-nota_documento" name="field-nota_documento" value="'.$value.'" readonly>';
    }    
    function codCriterio1SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifEtapa1_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio1" class="form-control" name="field-cod_criterio1" value="'.$cod_criterio.'" readonly>';
    }    
    function codCriterio2SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifEtapa1_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio2" class="form-control" name="field-cod_criterio2" value="'.$cod_criterio.'" readonly>';
    }   
    function codCriterio3SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifEtapa1_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio3"  class="form-control" name="field-cod_criterio3" value="'.$cod_criterio.'" readonly>';
    }    
    function codCriterio4SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifEtapa1_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio4" name="field-cod_criterio4" value="'.$cod_criterio.'" readonly>';
    }   
    function codCriterio5SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifEtapa1_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio5" name="field-cod_criterio5" value="'.$cod_criterio.'" readonly>';
    }    
    function codCriterio6SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifEtapa1_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio6" name="field-cod_criterio6" value="'.$cod_criterio.'" readonly>';
    }   
 
    function notaExposicionSoloLectura($value){
        return '<input class="form-control" type="text" id="field-nota_exposicion" name="field-nota_exposicion" value="'.$value.'" readonly>';
    }   
    function notaEtapa1SoloLectura($value){
        return '<input class="form-control" type="text" id="field-nota_etapa1" name="field-nota_etapa1" value="'.$value.'" readonly>';
    }                        

  public   function actualizacion_CalifEtapa1($post_array){                       
                     
            $update['id_nota_etapa1']=$post_array['field-id_nota_etapa1'];
            $update['nota_criterio1']=$post_array['nota_criterio1'];
            $update['nota_criterio2']=$post_array['nota_criterio2'];
            $update['nota_criterio3']=$post_array['nota_criterio3'];
            $update['nota_criterio4']=$post_array['nota_criterio4'];
            $update['nota_criterio5']=$post_array['nota_criterio5'];
            $update['nota_criterio6']=$post_array['nota_criterio6'];
            $update['nota_documento']=$post_array['nota_documento'];
            $update['nota_exposicion']=$post_array['nota_exposicion'];
            $update['nota_etapa1']=$post_array['nota_etapa1'];
           
            $comprobar=$this->CalifEtapa1_gc_model->Update($update);
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