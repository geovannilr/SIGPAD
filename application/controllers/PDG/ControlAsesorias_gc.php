<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ControlAsesorias_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        $id_doc_est = $this->session->userdata('id_doc_est');
        $nombre = $this->session->userdata('nombre');
        $apellido = $this->session->userdata('apellidos');
        $usuario = $nombre.' '.$apellido;
        
        if  (
                                ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
                                ($tipo == 'Docente' and
                                    ($id_cargo_administrativo == '6' and $id_cargo == '2' )
                                       
                                )
                            ) {
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/ControlAsesorias_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
        
    }
    public function _ControlAsesorias_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormControlAsesorias_gc';
        $this->load->view('templates/content',$data);
                      
    }
  public function index()
    {

            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');

            /*Tabla temporal utilizada para alojar los docentes asesores de trabajo de graduacion*/
            /*$sql="TRUNCATE TABLE aux2_docente_pdg_tmp"; 
            $this->db->query($sql);
            $sql="INSERT INTO aux2_docente_pdg_tmp SELECT * FROM view_aux2_docente_pdg";
            $this->db->query($sql);*/

            /*Resentear el contador de id utilizados para las vistas*/
            /*$sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE asig_tribunal_aux_pdg"; 
            $this->db->query($sql);
            $sql="INSERT INTO asig_tribunal_aux_pdg(id,id_equipo_tg,anio_tg,tema,id_docente,NombreApellidoDocente,email,id_cargo,descripcion)
                  SELECT func_inc_var_session(),id_equipo_tg,anio_tg,tema,id_docente,NombreApellidoDocente,email,id_cargo,descripcion 
                  FROM view_asig_tribunal_pdg;";
            $this->db->query($sql);*/

            //obteniendo el año actual
            $fecha_actual=date ("Y"); 

            $crud = new grocery_CRUD();
            //$crud->set_table('view_asig_docente_aux_pdg');
            $crud->set_table('view_control_aseso_pdg');
           if ($tipo == 'Estudiante'){
                //Encontrar id_equipo_tg en base al id_due logueado
                $id_equipo_tg_filtrar=$this->ControlAsesorias_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);               
                $crud->where('view_control_aseso_pdg.id_equipo_tg',$id_equipo_tg_filtrar);
            }   
           if ($tipo == 'Docente'){
                //Encontrar id_equipo_tg en base al id_docente logueado
                $id_equipo_tg_filtrar_docente=$this->ControlAsesorias_gc_model->EncontrarIdEquipoFiltrarDocente($id_doc_est);               
                $crud->where('view_control_aseso_pdg.id_equipo_tg',$id_equipo_tg_filtrar_docente);
            }                        
            /*la vista view_asig_docente_aux_pdg se llena con view_asig_tribunal_pdg*/
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg',array('id_equipo_tg' => $id_equipo_tg_filtrar));
            //$crud->set_relation('id_docente','gen_docente','carnet');
            $crud->set_primary_key('id_bitacora');
            $crud->order_by('CAST(id_bitacora AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Control de bitacoras de TG');
            //Definiendo las columnas que apareceran
            $crud->columns('id_bitacora','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','ciclo_asesoria',
                'anio_asesoria','fecha','tema_asesoria','tematica_tratar',
                'hora_inicio','hora_fin','lugar','observaciones',
                'id_due1','hora_inicio_alumno_1','hora_fin_alumno_1',
                'id_due2','hora_inicio_alumno_2','hora_fin_alumno_2',
                'id_due3','hora_inicio_alumno_3','hora_fin_alumno_3',
                'id_due4','hora_inicio_alumno_4','hora_fin_alumno_4',
                'id_due5','hora_inicio_alumno_5','hora_fin_alumno_5',
                'id_docente','hora_inicio_docente','hora_fin_docente');          
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_bitacora','Id. Bitacora')
                 ->display_as('id_equipo_tg','Equipo TG')
                 ->display_as('tema_tesis','Tema')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('ciclo_asesoria','Ciclo Asesoría')
                 ->display_as('anio_asesoria','Año Asesoría')
                 ->display_as('fecha','Fecha Asesoría')
                 ->display_as('tema_asesoria','Tema Asesoría') 
                 ->display_as('tematica_tratar','Tematica a Tratar') 
                 ->display_as('hora_inicio','Hora Inicio Asesoria')
                 ->display_as('hora_fin','Hora Finalización Asesoría')
                 ->display_as('lugar','Lugar de la reunión')
                 ->display_as('observaciones','Observaciones de la reunión')
                 ->display_as('id_due1','DUE Alumno 1')
                 ->display_as('hora_inicio_alumno_1','Hora Inicio Alumno 1') 
                 ->display_as('hora_fin_alumno_1','Hora Finalización Alumno 1')
                 ->display_as('id_due2','DUE Alumno 2')
                 ->display_as('hora_inicio_alumno_2','Hora Inicio Alumno 2') 
                 ->display_as('hora_fin_alumno_2','Hora Finalización Alumno 2')
                 ->display_as('id_due3','DUE Alumno 3')
                 ->display_as('hora_inicio_alumno_3','Hora Inicio Alumno 3') 
                 ->display_as('hora_fin_alumno_3','Hora Finalización Alumno 3')
                 ->display_as('id_due4','DUE Alumno 4')
                 ->display_as('hora_inicio_alumno_4','Hora Inicio Alumno 4') 
                 ->display_as('hora_fin_alumno_4','Hora Finalización Alumno 4')
                 ->display_as('id_due5','DUE Alumno 5')
                 ->display_as('hora_inicio_alumno_5','Hora Inicio Alumno 5') 
                 ->display_as('hora_fin_alumno_5','Hora Finalización Alumno 5')
                 ->display_as('id_docente','Id. Docente Asesor')
                 ->display_as('hora_inicio_docente','Hora Inicio Docente')
                 ->display_as('hora_fin_docente','Hora Finalización Docente');
            //Validación de requeridos
            $crud->required_fields('id_equipo_tg','ciclo_asesoria',
                'anio_asesoria','fecha','tema_asesoria','tematica_tratar',
                'hora_inicio','hora_fin','lugar','observaciones',
                'id_due1','hora_inicio_alumno_1','hora_fin_alumno_1',
                'id_docente','hora_inicio_docente','hora_fin_docente');                                                   
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','ciclo_asesoria',
                'anio_asesoria','fecha','tema_asesoria','tematica_tratar',
                'hora_inicio','hora_fin','lugar','observaciones',
                'id_due1','hora_inicio_alumno_1','hora_fin_alumno_1',
                'id_due2','hora_inicio_alumno_2','hora_fin_alumno_2',
                'id_due3','hora_inicio_alumno_3','hora_fin_alumno_3',
                'id_due4','hora_inicio_alumno_4','hora_fin_alumno_4',
                'id_due5','hora_inicio_alumno_5','hora_fin_alumno_5',
                'id_docente','hora_inicio_docente','hora_fin_docente');
            //Definiendo los input que apareceran en el formulario de actualizacion
            $crud->edit_fields('id_bitacora','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','ciclo_asesoria',
                'anio_asesoria','fecha','tema_asesoria','tematica_tratar',
                'hora_inicio','hora_fin','lugar','observaciones',
                'id_due1','hora_inicio_alumno_1','hora_fin_alumno_1',
                'id_due2','hora_inicio_alumno_2','hora_fin_alumno_2',
                'id_due3','hora_inicio_alumno_3','hora_fin_alumno_3',
                'id_due4','hora_inicio_alumno_4','hora_fin_alumno_4',
                'id_due5','hora_inicio_alumno_5','hora_fin_alumno_5',
                'id_docente','hora_inicio_docente','hora_fin_docente');

            //Desabilitando el editor WISYWIG
            $crud->unset_texteditor('tematica_tratar','full_text');
            $crud->unset_texteditor('lugar','full_text');
            $crud->unset_texteditor('observaciones','full_text');
            //Validacion de ca´mpos en Add Forms
            $crud->callback_add_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_add_field('anio_tesis',array($this,'anioTGSoloLectura'));
            $crud->callback_add_field('ciclo_tesis',array($this,'cicloTGSoloLectura'));
            $crud->callback_add_field('lugar',array($this,'lugarAgrandado')); 
            $crud->callback_add_field('id_due1',array($this,'idDue1AddSoloLectura'));
            $crud->callback_add_field('id_due2',array($this,'idDue2AddSoloLectura'));
            $crud->callback_add_field('id_due3',array($this,'idDue3AddSoloLectura'));
            $crud->callback_add_field('id_due4',array($this,'idDue4AddSoloLectura'));
            $crud->callback_add_field('id_due5',array($this,'idDue5AddSoloLectura'));
            $crud->callback_add_field('id_docente',array($this,'idDocenteAddSoloLectura'));

            $crud->callback_field('tematica_tratar',array($this,'tematica_tratarAgrandado')); 
            $crud->callback_field('observaciones',array($this,'observacionesAgrandado')); 


            $crud->field_type('ciclo_asesoria','dropdown',array(1,2));
            $crud->field_type('anio_asesoria','dropdown',array($fecha_actual-1,$fecha_actual,$fecha_actual+1));              
            
            //Validacion de campos en Edit Form
            $crud->callback_edit_field('id_bitacora',array($this,'idBitacoraSoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'idEquipoTgSoloLectura'));
            $crud->callback_edit_field('tema_tesis',array($this,'agrandaCajaTextoEdit'));
            $crud->callback_edit_field('anio_tesis',array($this,'anioTGSoloLecturaEdit'));            
            $crud->callback_edit_field('ciclo_tesis',array($this,'cicloTGSoloLecturaEdit')); 
            $crud->callback_edit_field('ciclo_asesoria',array($this,'cicloAsesorSoloLectura')); 
            $crud->callback_edit_field('anio_asesoria',array($this,'anioAsesoSoloLectura')); 
            $crud->callback_edit_field('fecha',array($this,'fechaSoloLectura')); 
            $crud->callback_edit_field('hora_fin',array($this,'horaFinSoloLectura')); 
            $crud->callback_edit_field('lugar',array($this,'lugarSoloLectura')); 
            $crud->callback_edit_field('id_due1',array($this,'idDue1SoloLectura')); 
            $crud->callback_edit_field('hora_inicio_alumno_1',array($this,'horaIniAlumno1SoloLectura')); 
            $crud->callback_edit_field('hora_fin_alumno_1',array($this,'horaFinAlumno1SoloLectura')); 
            $crud->callback_edit_field('id_due2',array($this,'idDue2SoloLectura')); 
            $crud->callback_edit_field('hora_inicio_alumno_2',array($this,'horaIniAlumno2SoloLectura')); 
            $crud->callback_edit_field('hora_fin_alumno_2',array($this,'horaFinAlumno2SoloLectura'));
            $crud->callback_edit_field('id_due3',array($this,'idDue3SoloLectura')); 
            $crud->callback_edit_field('hora_inicio_alumno_3',array($this,'horaIniAlumno3SoloLectura')); 
            $crud->callback_edit_field('hora_fin_alumno_3',array($this,'horaFinAlumno3SoloLectura'));
            $crud->callback_edit_field('id_due4',array($this,'idDue4SoloLectura')); 
            $crud->callback_edit_field('hora_inicio_alumno_4',array($this,'horaIniAlumno4SoloLectura')); 
            $crud->callback_edit_field('hora_fin_alumno_4',array($this,'horaFinAlumno4SoloLectura'));                        
            $crud->callback_edit_field('id_due5',array($this,'idDue5SoloLectura')); 
            $crud->callback_edit_field('hora_inicio_alumno_5',array($this,'horaIniAlumno5SoloLectura')); 
            $crud->callback_edit_field('hora_fin_alumno_5',array($this,'horaFinAlumno5SoloLectura'));                                    
            $crud->callback_edit_field('id_docente',array($this,'idDocenteSoloLectura')); 
            $crud->callback_edit_field('hora_inicio_docente',array($this,'horaIniDocenteSoloLectura')); 
            $crud->callback_edit_field('hora_fin_docente',array($this,'horaFinDocenteSoloLectura'));                        
            

            $crud->unset_delete();

            $crud->callback_insert(array($this,'insercion_ControlAsesorias'));
            $crud->callback_update(array($this,'actualizacion_ControlAsesorias')); 
            //$crud->callback_delete(array($this,'delete_RegisTribuEva')); 
            $crud->add_action('Control_asesoria','','','glyphicon glyphicon-download',array($this,'ControlAsesorias'));            
            $output = $crud->render();
            $this->_ControlAsesorias_output($output);

    }
    function ControlAsesorias($primary_key )
    {
            return site_url('PDG/GenControlAsesorias_gc/generar/'.$primary_key );
    }    
    public function datos_tema($id){
        echo json_encode($this->ControlAsesorias_gc_model->Get_Datos_Tema($id));
    }
    public function datos_due1($id){
        echo json_encode($this->ControlAsesorias_gc_model->Get_Datos_Due1($id));
    }
    public function datos_due2($id1){
        echo json_encode($this->ControlAsesorias_gc_model->Get_Datos_Due2($id1));
    }
    public function datos_due3($id1){
        echo json_encode($this->ControlAsesorias_gc_model->Get_Datos_Due3($id1));
    }    
    public function datos_due4($id1){
        echo json_encode($this->ControlAsesorias_gc_model->Get_Datos_Due4($id1));
    }   
    public function datos_due5($id1){
        echo json_encode($this->ControlAsesorias_gc_model->Get_Datos_Due5($id1));
    }       
    public function datos_docente($id1,$id2,$id3){
        echo json_encode($this->ControlAsesorias_gc_model->Get_Datos_Docente($id1,$id2,$id3));
    }    
    //Para Add forms
    function agrandaCajaTexto(){
        return '<textarea class="form-control" id="field-tema" name="field-tema" readonly></textarea>';
    }   
    function anioTGSoloLectura(){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" readonly>';
    }   
    function cicloTGSoloLectura(){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" readonly>';
    }   
    function lugarAgrandado(){
        return '<input class="form-control" type="text" id="lugar" name="lugar">';
    }     
    /////////////////////////////////////////       
    function tematica_tratarAgrandado($value){
        return '<textarea class="form-control" id="tematica_tratar" name="tematica_tratar">'.$value.'</textarea>';
    }     
    function observacionesAgrandado($value){
        return '<textarea class="form-control" id="observaciones" name="observaciones">'.$value.'</textarea>';
    }     
    /////////////////////////////////////////
    function idDue1AddSoloLectura($value){
        return '<input class="form-control" type="text" id="id_due1" name="id_due1" value="'.$value.'" readonly>';
    } 
    function idDue2AddSoloLectura($value){
        return '<input class="form-control" type="text" id="id_due2" name="id_due2" value="'.$value.'" readonly>';
    } 
    function idDue3AddSoloLectura($value){
        return '<input class="form-control" type="text" id="id_due3" name="id_due3" value="'.$value.'" readonly>';
    } 
    function idDue4AddSoloLectura($value){
        return '<input class="form-control" type="text" id="id_due4" name="id_due4" value="'.$value.'" readonly>';
    } 
    function idDue5AddSoloLectura($value){
        return '<input class="form-control" type="text" id="id_due5" name="id_due5" value="'.$value.'" readonly>';
    }     
    function idDocenteAddSoloLectura($value){
        return '<input class="form-control" type="text" id="id_docente" name="id_docente" value="'.$value.'" readonly>';
    }                       
    
    //Para Edit Form
    function idBitacoraSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_bitacora" name="field-id_bitacora" value="'.$value.'" readonly>';
    }   
   
    function idEquipoTgSoloLectura($value){
        return '<input class="form-control" type="text" id="id_equipo_tg" name="id_equipo_tg" value="'.$value.'" readonly>';
    }  
    function agrandaCajaTextoEdit($value){
        return '<textarea class="form-control" id="field-tema" name="field-tema" readonly>'.$value.'</textarea>';
    }   
    function anioTGSoloLecturaEdit($value){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" value="'.$value.'" readonly>';
    } 
    function cicloTGSoloLecturaEdit($value){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" value="'.$value.'" readonly>';
    }     
    function cicloAsesorSoloLectura($value){
        return '<input class="form-control" type="text" id="ciclo_asesoria" name="ciclo_asesoria" value="'.$value.'" readonly>';
    }   
    function anioAsesoSoloLectura($value){
        return '<input class="form-control" type="text" id="anio_asesoria" name="anio_asesoria" value="'.$value.'" readonly>';
    }   
    function fechaSoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y'); // 31-07-2012
        return '<input class="form-control" type="text" id="fecha" name="fecha" value="'.$value.'" readonly>';
    } 
    function horaIniSoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12   
        return '<input class="form-control" type="text" id="hora_inicio" name="hora_inicio" value="'.$value.'" readonly>';
    }   
    function horaFinSoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_fin" name="hora_fin" value="'.$value.'" readonly>';
    }  
    function lugarSoloLectura($value){
        return '<input class="form-control" type="text" id="lugar" name="lugar" value="'.$value.'" readonly>';
    }   
    function idDue1SoloLectura($value){
        return '<input class="form-control" type="text" id="id_due1" name="id_due1" value="'.$value.'" readonly>';
    }   
    function horaIniAlumno1SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_inicio_alumno_1" name="hora_inicio_alumno_1" value="'.$value.'" readonly>';
    }   
    function horaFinAlumno1SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12  
        return '<input class="form-control" type="text" id="hora_fin_alumno_1" name="hora_fin_alumno_1" value="'.$value.'" readonly>';
    }   
    function idDue2SoloLectura($value){
        return '<input class="form-control" type="text" id="id_due2" name="id_due2" value="'.$value.'" readonly>';
    }  
    function horaIniAlumno2SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_inicio_alumno_2" name="hora_inicio_alumno_2" value="'.$value.'" readonly>';
    }   
    function horaFinAlumno2SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_fin_alumno_2" name="hora_fin_alumno_2" value="'.$value.'" readonly>';
    }   
    function idDue3SoloLectura($value){
        return '<input class="form-control" type="text" id="id_due3" name="id_due3" value="'.$value.'" readonly>';
    }       
    function horaIniAlumno3SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_inicio_alumno_3" name="hora_inicio_alumno_3" value="'.$value.'" readonly>';
    }   
    function horaFinAlumno3SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_fin_alumno_3" name="hora_fin_alumno_3" value="'.$value.'" readonly>';
    }   
    function idDue4SoloLectura($value){
        return '<input class="form-control" type="text" id="id_due4" name="id_due4" value="'.$value.'" readonly>';
    } 
    function horaIniAlumno4SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_inicio_alumno_4" name="hora_inicio_alumno_4" value="'.$value.'" readonly>';
    }   
    function horaFinAlumno4SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_fin_alumno_4" name="hora_fin_alumno_4" value="'.$value.'" readonly>';
    }   
    function idDue5SoloLectura($value){
        return '<input class="form-control" type="text" id="id_due5" name="id_due5" value="'.$value.'" readonly>';
    } 
    function horaIniAlumno5SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_inicio_alumno_5" name="hora_inicio_alumno_5" value="'.$value.'" readonly>';
    }   
    function horaFinAlumno5SoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_fin_alumno_5" name="hora_fin_alumno_5" value="'.$value.'" readonly>';
    }   
    function idDocenteSoloLectura($value){
        return '<input class="form-control" type="text" id="id_docente" name="id_docente" value="'.$value.'" readonly>';
    } 
    function horaIniDocenteSoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_inicio_docente" name="hora_inicio_docente" value="'.$value.'" readonly>';
    }   
    function horaFinDocenteSoloLectura($value){
        $source = $value;
        $date = new DateTime($source);
        $value=$date->format('d-m-Y H:i:s'); // 31-07-2012  15:12:12          
        return '<input class="form-control" type="text" id="hora_fin_docente" name="hora_fin_docente" value="'.$value.'" readonly>';
    }   
                             
    public function insercion_ControlAsesorias($post_array){
        
        //Validando el ciclo
        if ($post_array['ciclo_asesoria']==0){
            $ciclo='1';
        }
        if ($post_array['ciclo_asesoria']==1){
            $ciclo='2';
        }
        $insertar['ciclo_asesoria']=$ciclo;
        //Validando el año        
        if ($post_array['anio_asesoria']==0){
            $anio='2015';
        }
        if ($post_array['anio_asesoria']==1){
            $anio='2016';
        }
        if ($post_array['anio_asesoria']==2){
            $anio='2017';
        }                
        $insertar['anio_asesoria']=$anio;

        $insertar['id_equipo_tg']=$post_array['id_equipo_tg'];
        $insertar['anio_tg']=$post_array['field-anio_tesis'];
        $insertar['ciclo_tg']=$post_array['field-ciclo_tesis'];
        //$insertar['fecha']=$post_array['fecha'];
        $vFecha=$this->ControlAsesorias_gc_model->cambiaf_a_mysql($post_array['fecha']);
        $insertar['fecha']=$vFecha;
        $insertar['tema_asesoria']=$post_array['tema_asesoria'];
        $insertar['tematica_tratar']=$post_array['tematica_tratar'];
        $insertar['hora_inicio']=$post_array['hora_inicio'];
        $insertar['hora_fin']=$post_array['hora_fin'];
        $insertar['lugar']=$post_array['lugar'];
        $insertar['observaciones']=$post_array['observaciones'];
        $insertar['id_due1']=$post_array['id_due1'];
        $insertar['hora_inicio_alumno_1']=$post_array['hora_inicio_alumno_1'];
        $insertar['hora_fin_alumno_1']=$post_array['hora_fin_alumno_1'];
        $insertar['id_due2']=$post_array['id_due2'];
        $insertar['hora_inicio_alumno_2']=$post_array['hora_inicio_alumno_2'];
        $insertar['hora_fin_alumno_2']=$post_array['hora_fin_alumno_2'];
        $insertar['id_due3']=$post_array['id_due3'];
        $insertar['hora_inicio_alumno_3']=$post_array['hora_inicio_alumno_3'];
        $insertar['hora_fin_alumno_3']=$post_array['hora_fin_alumno_3'];
        $insertar['id_due4']=$post_array['id_due4'];
        $insertar['hora_inicio_alumno_4']=$post_array['hora_inicio_alumno_4'];
        $insertar['hora_fin_alumno_4']=$post_array['hora_fin_alumno_4'];
        $insertar['id_due5']=$post_array['id_due5'];
        $insertar['hora_inicio_alumno_5']=$post_array['hora_inicio_alumno_5'];
        $insertar['hora_fin_alumno_5']=$post_array['hora_fin_alumno_5'];        
        $insertar['id_docente']=$post_array['id_docente'];
        $insertar['hora_inicio_docente']=$post_array['hora_inicio_docente'];
        $insertar['hora_fin_docente']=$post_array['hora_fin_docente'];        

        $comprobar=$this->ControlAsesorias_gc_model->Create($insertar);
        if ($comprobar >=1){

            return true;
        }
        else{
            //significa que no se realizo la operacion DML
            return false;
        }                        
    }      

    public   function actualizacion_ControlAsesorias($post_array){

        $update['id_bitacora']=$post_array['field-id_bitacora'];
        $update['tema_asesoria']=$post_array['tema_asesoria'];
        $update['tematica_tratar']=$post_array['tematica_tratar'];
        $update['observaciones']=$post_array['observaciones'];
        $comprobar=$this->ControlAsesorias_gc_model->Update($update);
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