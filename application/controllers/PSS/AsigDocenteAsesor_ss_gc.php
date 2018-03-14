<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AsigDocenteAsesor_ss_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='3'){

            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/AsigDocenteAsesor_ss_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _AsigDocenteAsesor_ss_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormAsigDocenteAsesor_ss_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            /*Tabla temporal utilizada para alojar los docentes asesores de servicio social*/
            $sql="TRUNCATE TABLE aux1_docente_pss_tmp"; 
            $this->db->query($sql);
            $sql="INSERT INTO aux1_docente_pss_tmp SELECT * FROM view_aux1_docente_pss";
            $this->db->query($sql);

            /*Tabla temporal utilizada para alojar los expedientes de servicio social que no tiene docentes asesores*/
            /*$sql="TRUNCATE TABLE expedientes_pss_sin_asesor_pss_tmp"; 
            $this->db->query($sql);
            $sql="INSERT INTO expedientes_pss_sin_asesor_pss_tmp SELECT * FROM view_expedientes_pss_sin_asesor_pss";
            $this->db->query($sql);*/

            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE asig_docente_aux_pss"; 
            $this->db->query($sql);
            $sql="INSERT INTO asig_docente_aux_pss(id,id_det_expediente,id_servicio_social,id_due,nombre,apellido,id_docente,NombreApellidoDocente,correo_docente,es_docente_pss_principal,correlativo_tutor_ss)
                 SELECT func_inc_var_session(),id_det_expediente,id_servicio_social,id_due,nombre,apellido,id_docente,NombreApellidoDocente,
                 correo_docente,es_docente_pss_principal,correlativo_tutor_ss FROM view_asig_docente_pss";
            $this->db->query($sql);


            $crud = new grocery_CRUD();
            //$crud->set_table('view_asig_docente_aux_pdg');
            $crud->set_table('asig_docente_aux_pss');
            $crud->set_relation('id_det_expediente','pss_detalle_expediente','id_detalle_expediente');
            $crud->set_relation('id_servicio_social','pss_servicio_social','nombre_servicio_social');
            $crud->set_relation('id_docente','aux1_docente_pss_tmp','carnet');
            $crud->set_primary_key('id');
            $crud->order_by('id','asc');
            ////////$crud->set_primary_key('id_det_expediente');
            ///////$crud->order_by('CAST(id_det_expediente AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Asignación de Docente Asesor de Servicio Social');
            //Definiendo las columnas que apareceran
            $crud->columns('id','id_det_expediente','id_servicio_social','id_due','nombre','apellido',
                'id_docente','NombreApellidoDocente','correo_docente','es_docente_pss_principal');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id','Id.')
                 ->display_as('id_det_expediente','Cod. Expediente Servicio Social')
                 ->display_as('id_servicio_social','Servicio Social')
                 ->display_as('id_due','DUE')
                 ->display_as('nombre','Nombre Estudiante')
                 ->display_as('apellido','Apellido Estudiante')
                 ->display_as('id_docente','Carnet Docente')
                 ->display_as('NombreApellidoDocente','Nombre Docente Asesor PSS')
                 ->display_as('correo_docente','Email')
                 ->display_as('es_docente_pss_principal','¿Es Docente Asesor Principal?');
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_det_expediente','id_servicio_social','id_due','nombre','apellido',
                'id_docente','NombreApellidoDocente','correo_docente','es_docente_pss_principal');
            //Definiendo los input que apareceran en el formulario de actualizacion
            $crud->edit_fields('id','id_det_expediente','id_servicio_social','id_due','nombre','apellido',
                'id_docente','NombreApellidoDocente','correo_docente','es_docente_pss_principal');
           
            //Validacion de ca´mpos en Add Forms
            //$crud->callback_add_field('id_det_expediente',array($this,'idDetalleExpedienteSoloLectura'));
            $crud->callback_add_field('id_servicio_social',array($this,'idServicioSocialSoloLectura'));
            $crud->callback_add_field('id_due',array($this,'idDueSoloLectura'));
            $crud->callback_add_field('nombre',array($this,'nombreSoloLectura'));
            $crud->callback_add_field('apellido',array($this,'apellidoSoloLectura'));
            $crud->callback_add_field('NombreApellidoDocente',array($this,'NombreApellidoDocenteSoloLectura'));
            $crud->callback_add_field('correo_docente',array($this,'EmailSoloLectura'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO');               
            //Validacion de campos en Update Forms  
            $crud->callback_field('id',array($this,'IdSoloLectura'));
            $crud->callback_edit_field('id_det_expediente',array($this,'mostrarIdDetalleExpediente'));  
            $crud->callback_edit_field('id_servicio_social',array($this,'mostrarServicioSocial'));            
            $crud->callback_edit_field('id_due',array($this,'mostrarIdDue'));            
            $crud->callback_edit_field('nombre',array($this,'mostrarNombre'));            
            $crud->callback_edit_field('apellido',array($this,'mostrarApellido'));  
            $crud->callback_edit_field('NombreApellidoDocente',array($this,'mostrarnombreApellidoDocente'));            
            $crud->callback_edit_field('correo_docente',array($this,'mostrarEmail'));            
            
            //Definiendo los campos obligatorios
            $crud->required_fields('id','id_docente','es_docente_pss_principal');  


            
            $crud->callback_insert(array($this,'insercion_AsigDocenteAsesor_ss'));
            $crud->callback_update(array($this,'actualizacion_AsigDocenteAsesor_ss')); 
            $crud->callback_delete(array($this,'delete_AsigDocenteAsesor_ss')); 
                
           

            $output = $crud->render();
            $this->_AsigDocenteAsesor_ss_output($output);
    }
    public function datos_expediente($id){
        echo json_encode($this->AsigDocenteAsesor_ss_gc_model->Get_Datos_Expediente($id));
    }

    public function datos_docente($id){
        //echo json_encode($this->AsigDocenteAsesor_ss_gc_model->Get_Datos_Docentes($id));
        echo json_encode($this->AsigDocenteAsesor_ss_gc_model->Get_Datos_Docentes($id));
    }    
    
    public function valida_data($id1,$id2,$id3,$id4){
        
        /*Valida casos de error en la insercion del docente asesor en el modulo de pss*/
        $comprobar=$this->AsigDocenteAsesor_ss_gc_model->Get_Valida_Data($id1,$id2,$id3,$id4);
                    /*Nota si en el modelo pasa las validaciones se mandara un 0, eso significa que
                    todo esta bien, por lo tanto no entraria a ningun if y no se mostraria ningun mensaje
                    */
                    if ($comprobar ==1){
                        //caso 1
                        echo ("A este expediente de servicio social ya se le ha asignado un docente principal, favor validar");
                    }  
                    if ($comprobar ==2){
                        //Caso 2
                        echo ("Este docente ya ha sido asignado a este mismo expediente de servicio social");
                    }
                  
                    /*else{
                        //significa que no se realizo la operacion DML
                        echo ("Se registro un error inesperado, favor consulte con el administrador del sistema");
                    } */                 
    }  
        


    //Para Add forms
    /*function idDetalleExpedienteSoloLectura(){
        return '<input type="text" id="field-id_det_expediente" name="field-id_det_expediente" readonly>';
    }   */
    function idServicioSocialSoloLectura(){
        ///////$dues_iterar=$this->AsigDocenteAsesor_gc_model->BuscarDUEsEquipo($post_array['id_equipo_tg'])
        return '<input class="form-control" type="text" id="field-id_servicio_social" name="field-id_servicio_social" readonly>';
    }       
    function idDueSoloLectura(){
        return '<input class="form-control" type="text" id="field-id_due" name="field-id_due" readonly>';
    }           
    function nombreSoloLectura(){
        return '<input class="form-control" type="text" id="field-nombre" name="field-nombre" readonly>';
    }           
    function apellidoSoloLectura(){
        return '<input class="form-control" type="text" id="field-apellido" name="field-apellido" readonly>';
    }                                
    function NombreApellidoDocenteSoloLectura(){
        return '<input class="form-control" type="text" id="field-nombreApellidoDocente" name="field-nombreApellidoDocente" readonly>';
    }       
    function EmailSoloLectura(){
        return '<input class="form-control" type="text" id="field-correo_docente" name="field-correo_docente" readonly>';
    }   

      /*Para edit form*/  
    function IdSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id" name="field-id" value="'.$value.'" readonly>';
    }                  
    function mostrarIdDetalleExpediente($value){
        return '<input class="form-control" type="text" id="field-id_det_expediente" name="field-id_det_expediente" value="'.$value.'" readonly>';
    }        
    function mostrarServicioSocial($value){
        $servicio_social=$this->AsigDocenteAsesor_ss_gc_model->NombreServicioSocial($value);
        return '<input class="form-control" type="text" id="field-id_servicio_social" name="field-id_servicio_social" value="'.$servicio_social.'" readonly>';
    }          
    function mostrarIdDue($value){
        return '<input class="form-control" type="text" id="field-id_due" name="field-id_due" value="'.$value.'" readonly>';
    }                  
    function mostrarNombre($value){
        return '<input class="form-control" type="text" id="field-nombre" name="field-nombre" value="'.$value.'" readonly>';
    }            
    function mostrarApellido($value){
        return '<input class="form-control" type="text" id="field-apellido" name="field-apellido" value="'.$value.'" readonly>';
    }                
    function mostrarnombreApellidoDocente($value){
        return '<input class="form-control" type="text" id="field-nombreApellidoDocente" name="field-nombreApellidoDocente" value="'.$value.'"  readonly>';
    }       
    function mostrarEmail($value){
        return '<input class="form-control" type="text" id="field-correo_docente" name="field-correo_docente" value="'.$value.'" readonly>';
    }   

    public function insercion_AsigDocenteAsesor_ss($post_array){
        $correlativo_tutor_ss=$this->AsigDocenteAsesor_ss_gc_model->ObtenerCorrelativoTutorSS($post_array['field-id_due'],$post_array['id_docente']);
 
        $insertar['id_detalle_expediente']=$post_array['id_det_expediente'];
        $insertar['id_due']=$post_array['field-id_due'];
        $insertar['id_docente']=$post_array['id_docente'];
        $insertar['id_proceso']='PSS';
        $insertar['id_cargo']='4';
        $insertar['correlativo_tutor_ss']=$correlativo_tutor_ss;
        $insertar['es_docente_pss_principal']=$post_array['es_docente_pss_principal'];

        $correo_estudiante=$this->AsigDocenteAsesor_ss_gc_model->ObtenerCorreoEstudiantePss($post_array['field-id_due']);
        $comprobar=$this->AsigDocenteAsesor_ss_gc_model->Create($insertar);
        $sql = "SELECT func_reset_inc_var_session()";
        $this->db->query($sql);         
        if ($comprobar >=1){
            //$huboError=0;

            //ENVIAR EMAIL a Docente asesor asignado
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
            $this->email->to($post_array['field-correo_docente']);
            $this->email->subject('Notificación: Asignación de Docente Asesor de PSS');
            $this->email->message('Usted ha sido asignado como docente asesor de servicio social del alumno con carnet '.$post_array['field-id_due'].': '.$post_array['field-nombre'].', '.$post_array['field-apellido'].' para el servicio social '.$post_array['field-id_servicio_social'].'.');
            $this->email->send();
            
            //ENVIAR EMAIL a alumno al que se le asigna el docente
            $this->load->library('email');
            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
            $this->email->to($correo_estudiante);
            $this->email->subject('Notificación: Asignación de Docente Asesor de PSS');
            $this->email->message('Se le ha asignado como docente asesor de servicio social al docente: '.$post_array['field-nombreApellidoDocente'].' para el servicio social '.$post_array['field-id_servicio_social'].'.');
            $this->email->send();
            return true;
        }
        else{
            //significa que no se realizo la operacion DML
            //$huboError=1;
            return false;
        }                    

            

    }        
    public   function actualizacion_AsigDocenteAsesor_ss($post_array){
            /*Identificar el id_docente alamcenado en la bd para ese equipo segun la llave primaria*/
            $id_docente_old=$this->AsigDocenteAsesor_ss_gc_model->ConsultaDocenteAsesorOld($post_array['field-id']);
            /*Identificar el es_docente_principal alamcenado en la bd para ese equipo segun la llave primaria*/
            $es_docente_principal_pss_old=$this->AsigDocenteAsesor_ss_gc_model->ConsultaEsDocentePrincipalOld($post_array['field-id']);
            /*Identificar el correlativo_tutor alamcenado en la bd para ese equipo segun la llave primaria*/
            $correlativo_tutor_ss_old=$this->AsigDocenteAsesor_ss_gc_model->ConsultaCorrelativoTutorOld($post_array['field-id']);

            $update['id_detalle_expediente']=$post_array['field-id_det_expediente'];
            $update['id_due']=$post_array['field-id_due'];
            $update['id_docente']=$post_array['id_docente'];
            $update['id_proceso']='PSS';
            $update['id_cargo']='4';
            $update['es_docente_pss_principal']=$post_array['es_docente_pss_principal'];
            
            $update['id_docente_old']=$id_docente_old;
            $update['es_docente_principal_pss_old']=$es_docente_principal_pss_old;
            $update['correlativo_tutor_ss_old']=$correlativo_tutor_ss_old;  

            $correo_estudiante=$this->AsigDocenteAsesor_ss_gc_model->ObtenerCorreoEstudiantePss($post_array['field-id_due']);                  
            $comprobar=$this->AsigDocenteAsesor_ss_gc_model->Update($update);
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql);             
            if ($comprobar >=1){
                //$huboError=0;
                //ENVIAR EMAIL a Docente asesor asignado
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                $this->email->to($post_array['field-correo_docente']);
                $this->email->subject('Notificación: Asignación de Docente Asesor de PSS');
                $this->email->message('<p>Usted ha sido asignado como docente asesor de servicio social del alumno con carnet '.$post_array['field-id_due'].': '.$post_array['field-nombre'].', '.$post_array['field-apellido'].' para el servicio social '.$post_array['field-id_servicio_social'].'.</p>');
                $this->email->set_mailtype('html'); 
                $this->email->send();
                
                //ENVIAR EMAIL a alumno al que se le asigna el docente
                $this->load->library('email');
                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                $this->email->to($correo_estudiante);
                $this->email->subject('Notificación: Asignación de Docente Asesor de PSS');
                $this->email->message('<p>Se le ha asignado como docente asesor de servicio social al docente: '.$post_array['field-nombreApellidoDocente'].' para el servicio social '.$post_array['field-id_servicio_social'].'.</p>');
                $this->email->set_mailtype('html'); 
                $this->email->send();                
                return true;
            }
            else{
                //significa que no se realizo la operacion DML
                //$huboError=1;
                return false;
            }                    
                  


        }

    public function delete_AsigDocenteAsesor_ss($primary_key)
    {
            /*Encontradndo id_due,id_detalle_expediente,id_docente y correlativo_tutor segun llave primaria seleccionada*/
            $llaves_delete['primary_key']=$primary_key;

            $llaves=$this->AsigDocenteAsesor_ss_gc_model->EncontrarLLavesDelete($llaves_delete);
            foreach ($llaves as $row)
            {
                    $id_due=$row['id_due'];
                    $id_detalle_expediente=$row['id_det_expediente'];
                    $id_docente=$row['id_docente'];
                    $correlativo_tutor_ss=$row['correlativo_tutor_ss'];
            } 

                    $delete['id_due']=$id_due;
                    $delete['id_detalle_expediente']=$id_detalle_expediente;
                    $delete['id_docente']=$id_docente;                    
                    $delete['correlativo_tutor_ss']=$correlativo_tutor_ss;
                    $delete['id_proceso']='PSS';
                    $delete['id_cargo']='4';

                    $comprobar=$this->AsigDocenteAsesor_ss_gc_model->Delete($delete);
                    $sql = "SELECT func_reset_inc_var_session()";
                    $this->db->query($sql);             
                    if ($comprobar >=1){
                        //$huboError=0;
                        return true;
                    }
                    else{
                        //significa que no se realizo la operacion DML
                        //$huboError=1;
                        return false;
                    }                    
                

                /*if ($comprobar >=1){
                        $huboError=0;
                        //return true;

                    }
                    else{
                        //significa que no se realizo la operacion DML
                        $huboError=1;
                        //return false;
                    }                    

            }
            if ($huboError==1) {
                    /*Resentear el contador de id utilizados para las vistas*/
                 /*   $sql = "SELECT func_reset_inc_var_session()";
                    $this->db->query($sql); 
                    return false;
            }
            else{
                    /*Resentear el contador de id utilizados para las vistas*/
                    /*$sql = "SELECT func_reset_inc_var_session()";
                    $this->db->query($sql);                     
                    return true;
            }  */

    }      


}