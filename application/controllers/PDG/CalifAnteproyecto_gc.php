<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CalifAnteproyecto_gc extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        if($id_cargo_administrativo=='6' and $id_cargo == '2'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/CalifAnteproyecto_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _CalifAnteproyecto_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormCalifAnteproyecto_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            $id_doc_est = $this->session->userdata('id_doc_est');

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pdg_nota_anteproyecto');
            //Encontrar id_equipo_tg en base al id_docente logueado
            $id_equipo_tg_filtrar_docente=$this->CalifAnteproyecto_gc_model->EncontrarIdEquipoFiltrarDocente($id_doc_est);               
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
            $crud->set_relation('cod_criterio7','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio8','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio9','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio10','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio11','pdg_criterio','criterio');
            $crud->set_relation('cod_criterio12','pdg_criterio','criterio');

            $crud->set_language('spanish');         
            $crud->set_subject('Anteproyecto');  
            //Definiendo las columnas  que apareceran 
            $crud->columns('id_nota_anteproyecto','id_equipo_tg','tema','anio_tg','ciclo_tg','estado_nota',
                        'cod_criterio1','nota_criterio1','cod_criterio2','nota_criterio2',   
                        'cod_criterio3','nota_criterio3','cod_criterio4','nota_criterio4',  
                        'cod_criterio5','nota_criterio5','cod_criterio5','nota_criterio6',  
                        'cod_criterio7','nota_criterio7','cod_criterio8','nota_criterio8',  
                        'cod_criterio9','nota_criterio9','cod_criterio10','nota_criterio10','nota_documento',  
                        'cod_criterio11','nota_criterio11','cod_criterio12','nota_criterio12',  
                        'nota_exposicion','nota_anteproyecto');            
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_nota_anteproyecto','Correlativo')
                 ->display_as('id_equipo_tg','Id. Equipo TG')            
                 ->display_as('tema','Tema TG')  
                 ->display_as('anio_tg','Año TG')  
                 ->display_as('ciclo_tg','Ciclo TG') 
                 ->display_as('estado_nota','Estado Nota')  
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
                 ->display_as('cod_criterio7','Cod. Criterio 7')
                 ->display_as('nota_criterio7','Nota Criterio 7') 
                 ->display_as('cod_criterio8','Cod. Criterio 8')
                 ->display_as('nota_criterio8','Nota Criterio 8') 
                 ->display_as('cod_criterio9','Cod. Criterio 9')
                 ->display_as('nota_criterio9','Nota Criterio 9') 
                 ->display_as('cod_criterio10','Cod. Criterio 10')
                 ->display_as('nota_criterio10','Nota Criterio 10') 
                 ->display_as('nota_documento','Nota Documento')
                 ->display_as('cod_criterio11','Cod. Criterio 11')
                 ->display_as('nota_criterio11','Nota Criterio 11') 
                 ->display_as('cod_criterio12','Cod. Criterio 12')
                 ->display_as('nota_criterio12','Nota Criterio 12') 
                 ->display_as('nota_exposicion','Nota Exposición')
                 ->display_as('nota_anteproyecto','Nota Anteproyecto');

            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_nota_anteproyecto','id_equipo_tg','tema','anio_tg','ciclo_tg','estado_nota',
                        'cod_criterio1','nota_criterio1','cod_criterio2','nota_criterio2',   
                        'cod_criterio3','nota_criterio3','cod_criterio4','nota_criterio4',  
                        'cod_criterio5','nota_criterio5','cod_criterio5','nota_criterio6',  
                        'cod_criterio7','nota_criterio7','cod_criterio8','nota_criterio8',  
                        'cod_criterio9','nota_criterio9','cod_criterio10','nota_criterio10','nota_documento',  
                        'cod_criterio11','nota_criterio11','cod_criterio12','nota_criterio12',  
                        'nota_exposicion','nota_anteproyecto');
            //Definiendo validaciones para formulario de edición
            $crud->callback_edit_field('id_nota_anteproyecto',array($this,'idNotaAnteproyectoSoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tg',array($this,'anioTGSoloLectura'));    
            $crud->callback_edit_field('ciclo_tg',array($this,'cicloTGSoloLectura'));
            $crud->callback_edit_field('estado_nota',array($this,'estadoNotaSoloLectura'));     
            $crud->callback_edit_field('cod_criterio1',array($this,'codCriterio1SoloLectura'));
            $crud->callback_edit_field('cod_criterio2',array($this,'codCriterio2SoloLectura'));
            $crud->callback_edit_field('cod_criterio3',array($this,'codCriterio3SoloLectura'));
            $crud->callback_edit_field('cod_criterio4',array($this,'codCriterio4SoloLectura'));
            $crud->callback_edit_field('cod_criterio5',array($this,'codCriterio5SoloLectura'));
            $crud->callback_edit_field('cod_criterio6',array($this,'codCriterio6SoloLectura'));
            $crud->callback_edit_field('cod_criterio7',array($this,'codCriterio7SoloLectura'));
            $crud->callback_edit_field('cod_criterio8',array($this,'codCriterio8SoloLectura'));
            $crud->callback_edit_field('cod_criterio9',array($this,'codCriterio9SoloLectura'));
            $crud->callback_edit_field('cod_criterio10',array($this,'codCriterio10SoloLectura'));
            $crud->callback_edit_field('nota_documento',array($this,'notaDocumentoSoloLectura'));
            $crud->callback_edit_field('cod_criterio11',array($this,'codCriterio11SoloLectura'));
            $crud->callback_edit_field('cod_criterio12',array($this,'codCriterio12SoloLectura'));
            $crud->callback_edit_field('nota_exposicion',array($this,'notaExposicionSoloLectura'));
            $crud->callback_edit_field('nota_anteproyecto',array($this,'notaAnteproyectoSoloLectura'));
            $crud->field_type('estado_nota','dropdown',array('A' => 'Abierta','C' => 'Cerrada'));
            $crud->set_rules('nota_criterio1','nota_criterio1','callback_checar_nota_criterio1');
            $crud->set_rules('nota_criterio2','nota_criterio2','callback_checar_nota_criterio2');
            $crud->set_rules('nota_criterio3','nota_criterio3','callback_checar_nota_criterio3');
            $crud->set_rules('nota_criterio4','nota_criterio4','callback_checar_nota_criterio4');
            $crud->set_rules('nota_criterio5','nota_criterio5','callback_checar_nota_criterio5');
            $crud->set_rules('nota_criterio6','nota_criterio6','callback_checar_nota_criterio6');
            $crud->set_rules('nota_criterio7','nota_criterio7','callback_checar_nota_criterio7');
            $crud->set_rules('nota_criterio8','nota_criterio8','callback_checar_nota_criterio8');
            $crud->set_rules('nota_criterio9','nota_criterio9','callback_checar_nota_criterio9');
            $crud->set_rules('nota_criterio10','nota_criterio10','callback_checar_nota_criterio10');
            //$crud->set_rules('nota_documento','nota_documento','callback_checar_nota_documento');
            $crud->set_rules('nota_criterio11','nota_criterio11','callback_checar_nota_criterio11');
            $crud->set_rules('nota_criterio12','nota_criterio12','callback_checar_nota_criterio12');
            //$crud->set_rules('nota_exposicion','nota_exposicion','callback_checar_nota_exposicion');
            //$crud->set_rules('nota_anteproyecto','nota_anteproyecto','callback_checar_nota_anteproyecto');

            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete(); 
            $crud->callback_update(array($this,'actualizacion_CalifAnteproyecto'));           
            //$output = "<h1>List 1</h1>".$output1->output."<h1>List 2</h1>".$output2->output."<h1>List 3</h1>".$output3->output;
            $output = $crud->render();

            $this->_CalifAnteproyecto_output($output);


    }
    //Para Edit forms
    //Validaciones de rango de notas
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
    public function checar_nota_criterio7($nota_criterio7)
    {

       if(is_numeric($nota_criterio7)) 
       {
            if ($nota_criterio7 < 0 or $nota_criterio7 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio7', "La nota del criterio 7 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio7', "La nota del criterio 7 solo admite valores numericos");
            return FALSE;
       }
    }
    public function checar_nota_criterio8($nota_criterio8)
    {

       if(is_numeric($nota_criterio8)) 
       {
            if ($nota_criterio8 < 0 or $nota_criterio8 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio8', "La nota del criterio 8 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio8', "La nota del criterio 8 solo admite valores numericos");
            return FALSE;
       }
    }
    public function checar_nota_criterio9($nota_criterio9)
    {

       if(is_numeric($nota_criterio9)) 
       {
            if ($nota_criterio9 < 0 or $nota_criterio9 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio9', "La nota del criterio 9 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio9', "La nota del criterio 9 solo admite valores numericos");
            return FALSE;
       }
    }
    public function checar_nota_criterio10($nota_criterio10)
    {

       if(is_numeric($nota_criterio10)) 
       {
            if ($nota_criterio10 < 0 or $nota_criterio10 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio10', "La nota del criterio 10 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio10', "La nota del criterio 10 solo admite valores numericos");
            return FALSE;
       }
    }
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
    public function checar_nota_criterio11($nota_criterio11)
    {

       if(is_numeric($nota_criterio11)) 
       {
            if ($nota_criterio11 < 0 or $nota_criterio11 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio11', "La nota del criterio 11 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio11', "La nota del criterio 11 solo admite valores numericos");
            return FALSE;
       }
    }
    public function checar_nota_criterio12($nota_criterio12)
    {

       if(is_numeric($nota_criterio12)) 
       {
            if ($nota_criterio12 < 0 or $nota_criterio12 > 10)
            {
                $this->form_validation->set_message('checar_nota_criterio12', "La nota del criterio 12 debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_criterio12', "La nota del criterio 12 solo admite valores numericos");
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
    public function checar_nota_anteproyecto($nota_anteproyecto)
    {

       if(is_numeric($nota_anteproyecto)) 
       {
            if ($nota_anteproyecto < 0 or $nota_anteproyecto > 10)
            {
                $this->form_validation->set_message('checar_nota_anteproyecto', "La nota del anteproyecto debe estar entre 0 y 10");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
       } 
       else 
       {
            $this->form_validation->set_message('checar_nota_anteproyecto', "La nota del anteproyecto solo admite valores numericos");
            return FALSE;
       }
    }      
   //Edicion de solo lectura
   function idNotaAnteProyectoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_nota_anteproyecto" name="field-id_nota_anteproyecto" value="'.$value.'" readonly>';
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
    function estadoNotaSoloLectura($value){
        if($value=='A'){
            $value='Abierta';
        }
        if($value=='C'){
            $value='Cerrada';
        }           
        return '<input class="form-control" type="text" id="field-estado_nota" name="field-estado_nota" value="'.$value.'" readonly>';
    }   
    function codCriterio1SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio1" name="field-cod_criterio1" value="'.$cod_criterio.'" readonly>';
    }    
    function codCriterio2SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio2" name="field-cod_criterio2" value="'.$cod_criterio.'" readonly>';
    }   
    function codCriterio3SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio3" name="field-cod_criterio3" value="'.$cod_criterio.'" readonly>';
    }    
    function codCriterio4SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio4" name="field-cod_criterio4" value="'.$cod_criterio.'" readonly>';
    }   
    function codCriterio5SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio5" name="field-cod_criterio5" value="'.$cod_criterio.'" readonly>';
    }    
    function codCriterio6SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio6" name="field-cod_criterio6" value="'.$cod_criterio.'" readonly>';
    }   
    function codCriterio7SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio7" name="field-cod_criterio7" value="'.$cod_criterio.'" readonly>';
    }    
    function codCriterio8SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio8" name="field-cod_criterio8" value="'.$cod_criterio.'" readonly>';
    }   
    function codCriterio9SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio9" name="field-cod_criterio9" value="'.$cod_criterio.'" readonly>';
    }    
    function codCriterio10SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio10" name="field-cod_criterio10" value="'.$cod_criterio.'" readonly>';
    }  
    function notaDocumentoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-nota_documento" name="field-nota_documento" value="'.$value.'" readonly>';
    }        
    function codCriterio11SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio11" name="field-cod_criterio11" value="'.$cod_criterio.'" readonly>';
    }    
    function codCriterio12SoloLectura($value){
            $var['cod_criterio']=$value;        
            $cod_criterio=$this->CalifAnteproyecto_gc_model->EncuentraCriterio($var);
        return '<input class="form-control" type="text" id="field-cod_criterio12" name="field-cod_criterio12" value="'.$cod_criterio.'" readonly>';
    }   
    function notaExposicionSoloLectura($value){
        return '<input class="form-control" type="text" id="field-nota_exposicion" name="field-nota_exposicion" value="'.$value.'" readonly>';
    }   
    function notaAnteproyectoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-nota_anteproyecto" name="field-nota_anteproyecto" value="'.$value.'" readonly>';
    }                        

  public   function actualizacion_CalifAnteproyecto($post_array){                       
                     
            $update['id_nota_anteproyecto']=$post_array['field-id_nota_anteproyecto'];
            $update['nota_criterio1']=$post_array['nota_criterio1'];
            $update['nota_criterio2']=$post_array['nota_criterio2'];
            $update['nota_criterio3']=$post_array['nota_criterio3'];
            $update['nota_criterio4']=$post_array['nota_criterio4'];
            $update['nota_criterio5']=$post_array['nota_criterio5'];
            $update['nota_criterio6']=$post_array['nota_criterio6'];
            $update['nota_criterio7']=$post_array['nota_criterio7'];
            $update['nota_criterio8']=$post_array['nota_criterio8'];
            $update['nota_criterio9']=$post_array['nota_criterio9'];
            $update['nota_criterio10']=$post_array['nota_criterio10'];
            $update['nota_criterio11']=$post_array['nota_criterio11'];
            $update['nota_criterio12']=$post_array['nota_criterio12'];
            $update['nota_documento']=$post_array['nota_documento'];
            $update['nota_exposicion']=$post_array['nota_exposicion'];
            $update['nota_anteproyecto']=$post_array['nota_anteproyecto'];
           
            $comprobar=$this->CalifAnteproyecto_gc_model->Update($update);
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