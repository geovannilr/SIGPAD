<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        $id_doc_est = $this->session->userdata('id_doc_est');
        if($tipo == 'Estudiante' and 
                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/Perfil_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }        
    }
    public function _perfil_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormPerfil_gc';
        $this->load->view('templates/content',$data);
                      
    }
    public function index()
    {
             

            $id_doc_est = $this->session->userdata('id_doc_est');

            $sql="TRUNCATE TABLE pdg_perfil_temp"; 
                $this->db->query($sql);
                $sql="INSERT INTO pdg_perfil_temp SELECT * FROM view_perfil_pdg";
                $this->db->query($sql);


            //obteniendo el año actual
            $fecha_actual=date ("Y");  
            $crud = new grocery_CRUD();
            //$crud->columns('id_perfil','ciclo','anio','objetivo_general','objetivo_especifico','descripcion');
            
            //Encontrar id_equipo_tg en base al id_due logueado
            $id_equipo_tg_filtrar=$this->Perfil_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);
            //Seteando la tabla o vista
            $crud->set_table('pdg_perfil_temp');//$crud->set_table('view_perfil');
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            $crud->where('pdg_perfil_temp.id_equipo_tg',$id_equipo_tg_filtrar); 
            $crud->set_primary_key('id_perfil');

            /****************RMORAN: este bloque se comentario antes de ingresar cambio de incorporacion de reporte de resumen de perfil que solo requeria diferenciar entre un registro y oitro por el id_perfil
            $crud->set_primary_key('id_equipo_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('anio_tg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            $crud->set_primary_key('id_detalle_pdg'); //si se usa vista es primordial establecer que campo sera la llave primaria
            *********************/
            $crud->set_subject('Registro de Perfiles');
            $crud->set_language('spanish');
               
            //$crud->columns('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','id_perfil','ciclo_perfil','anio_perfil','observaciones_perfil','area_tematica_tg','objetivo_general','objetivo_especifico','descripcion','resultados_esperados_tg','ruta');
            $crud->columns('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','id_perfil','ciclo_perfil','anio_perfil','observaciones_perfil','area_tematica_tg','objetivo_general','objetivo_especifico','descripcion','resultados_esperados_tg');
            //Cambiando nombre a los labelspdg_perfil_temp de los campos
            $crud->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('id_perfil','Id. Perfil')
                 ->display_as('ciclo_perfil','Ciclo Perfil')
                 ->display_as('anio_perfil','Año Perfil')
                 ->display_as('observaciones_perfil','Observaciones Perfil')
                 ->display_as('area_tematica_tg','Área Tematica de TG')
                 ->display_as('objetivo_general','Objetivo General')
                 ->display_as('objetivo_especifico','Objetivo Especificos')
                 ->display_as('resultados_esperados_tg','Resultados Esperados de TG')
                 ->display_as('descripcion','Descripcion de Perfil');
                 //->display_as('ruta','Memorandum');
            //Quitando el plugin ckeditor.com a los campos textarea
            $crud->unset_texteditor('objetivo_general','full_text');
            //$crud->unset_texteditor('objetivo_especifico','full_text');
            $crud->unset_texteditor('descripcion','full_text');           
            //Validacion de ca´mpos en Edit Forms
            //$crud->edit_fields('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','id_perfil','ciclo_perfil','anio_perfil','area_tematica_tg','objetivo_general','objetivo_especifico','descripcion','resultados_esperados_tg','ruta'); 
            $crud->edit_fields('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','id_perfil','ciclo_perfil','anio_perfil','area_tematica_tg','objetivo_general','objetivo_especifico','descripcion','resultados_esperados_tg'); 
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_edit_field('anio_tesis',array($this,'anioTesisSoloLectura'));
            $crud->callback_edit_field('ciclo_tesis',array($this,'cicloTesisSoloLectura'));
            $crud->callback_edit_field('id_perfil',array($this,'idPerfilSoloLectura'));
            $crud->callback_edit_field('observaciones_perfil',array($this,'observacionesPerfilSoloLectura'));

            $crud->callback_edit_field('objetivo_general',array($this,'objetivoGeneralSoloLectura'));
            //$crud->callback_edit_field('objetivo_especifico',array($this,'objetivoEspecificoSoloLectura'));
            $crud->callback_edit_field('descripcion',array($this,'descripcionSoloLectura'));
            $crud->callback_edit_field('area_tematica_tg',array($this,'areaTematicaSoloLectura'));
            
            //$crud->callback_edit_field('resultados_esperados_tg',array($this,'resultadosEsperadosSoloLectura'));

            //$crud->field_type('ciclo_perfil','dropdown',array(1,2));
            $crud->field_type('ciclo_perfil','dropdown',array('1' => '1', '2' => '2'));            
            $crud->field_type('anio_perfil','dropdown',array($fecha_actual-1,$fecha_actual,$fecha_actual+1));

            //Validación de requeridos
            $crud->required_fields('ciclo_perfil','anio_perfil','objetivo_general','objetivo_especifico','descripcion','ruta');
            //Para subir un archivo
            $crud->set_field_upload('ruta','assets/uploads/files');  
            
            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            
            $crud->callback_update(array($this,'actualizacion_Perfil'));
            /*se comentario porqque segun los cambios del nuevo formato de perfil por logica de negocio de los campos ingresados
                es mejor que lo imprima el coordinador de */
            /*$crud->add_action('Resumen_perfil','','','glyphicon glyphicon-download',array($this,'ResumenPerfil'));        
            $crud->add_action('Resumen_perfil V2','','','glyphicon glyphicon-download',array($this,'ResumenPerfilV2'));*/        
            $output = $crud->render();
            $this->_Perfil_output($output);
    }
    function ResumenPerfil($primary_key )
    {
            return site_url('PDG/GenResumenPerfil_gc/generar/'.$primary_key );
    }
    function ResumenPerfilV2($primary_key )
    {
            return site_url('PDG/GenResumenPerfilV2_gc/generar/'.$primary_key );
    }    
  //Para Edit forms
    function idEquipoTgSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_equipo_tg" name="field-id_equipo_tg" value="'.$value.'" readonly>';
    }   
    function agrandaCajaTexto($value){
        return '<textarea class="form-control" id="field-tema_tesis" name="field-tema_tesis" readonly>'.$value.'</textarea>';
    }   
    function anioTesisSoloLectura($value){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" value="'.$value.'" readonly>';
    }   
    function cicloTesisSoloLectura($value){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" value="'.$value.'" readonly>';
    }       
    function idPerfilSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_perfil" name="field-id_perfil" value="'.$value.'" readonly>';
    } 
    function observacionesPerfilSoloLectura($value){
        return '<textarea class="form-control" id="field-observaciones_perfil" name="field-observaciones_perfil" readonly>'.$value.'</textarea>';
    }     

    function objetivoGeneralSoloLectura($value){
        return '<textarea class="form-control" id="objetivo_general" name="objetivo_general">'.$value.'</textarea>';
    }    
    function objetivoEspecificoSoloLectura($value){
        return '<textarea class="form-control" id="objetivo_especifico" name="objetivo_especifico">'.$value.'</textarea>';
    }      
    function descripcionSoloLectura($value){
        return '<textarea class="form-control" id="descripcion" name="descripcion">'.$value.'</textarea>';
    }    
    function areaTematicaSoloLectura($value){
        return '<textarea class="form-control" id="area_tematica_tg" name="area_tematica_tg">'.$value.'</textarea>';
    }  
    function resultadosEsperadosSoloLectura($value){
        return '<textarea class="form-control" id="resultados_esperados_tg" name="resultados_esperados_tg">'.$value.'</textarea>';
    }               
         
    public   function actualizacion_Perfil($post_array){                      
          
            $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
            $update['anio_tesis']=$post_array['field-anio_tesis'];
            $update['ciclo_tesis']=$post_array['field-ciclo_tesis'];
            $update['id_perfil']=$post_array['field-id_perfil'];
            $update['perfil_ingresado_x_equipo']=$post_array['perfil_ingresado_x_equipo'];
            $update['ciclo_perfil']=$post_array['ciclo_perfil'];
            $update['anio_perfil']=$post_array['anio_perfil'];
            $update['objetivo_general']=$post_array['objetivo_general'];
            $update['objetivo_especifico']=$post_array['objetivo_especifico'];
            $update['descripcion']=$post_array['descripcion'];
            $update['area_tematica_tg']=$post_array['area_tematica_tg'];
            $update['resultados_esperados_tg']=$post_array['resultados_esperados_tg'];
            ///////////$update['ruta']=$post_array['ruta'];

            
            $comprobar=$this->Perfil_gc_model->Update($update);
            if ($comprobar >=1){
                $huboError=0;
                //return true;
                $correo_coordinadora_pdg=$this->Perfil_gc_model->ObtenerCorreoCoordinadorPDG();
                //ENVIAR EMAIL a coordinador de Proceso de graduación indicando que ha habido un cambio en el perfil y que proceda a revisar
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                $this->email->to($correo_coordinadora_pdg);
                $this->email->subject('Notificación: Perfil de Trabajo de Graduación Modificado');
                $this->email->message('<p>Se requeire su atención en la siguiente actividad: Se ha MODIFICADO Perfil de Trabajo de Graduación cuyo Tema es: '.$post_array['field-tema_tesis'].'</p>');
                $this->email->set_mailtype('html'); 
                $this->email->send();                  
            }
            else{
                //significa que no se realizo la operacion DML
                $huboError=1;
                //return false;
            }                    

        }

       
}