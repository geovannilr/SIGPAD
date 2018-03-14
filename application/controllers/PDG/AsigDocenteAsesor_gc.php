<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AsigDocenteAsesor_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='4'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/AsigDocenteAsesor_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _AsigDocenteAsesor_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormAsigDocenteAsesor_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            /*Tabla temporal utilizada para alojar los docentes asesores de trabajo de graduacion*/
            $sql="TRUNCATE TABLE aux1_docente_pdg_tmp"; 
            $this->db->query($sql);
            $sql="INSERT INTO aux1_docente_pdg_tmp SELECT * FROM view_aux1_docente_pdg";
            $this->db->query($sql);

            /*$sql="CREATE OR REPLACE VIEW view_asig_docente_aux_pdg AS
                    SELECT func_inc_var_session() AS id, id_equipo_tg,anio_tg,tema,id_docente,NombreApellidoDocente,email
                    FROM view_asig_docente_pdg";
            $this->db->query($sql);*/
                    /*Resentear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            /*$sql ="CREATE OR REPLACE VIEW view_asig_docente_aux_pdg AS
            SELECT func_inc_var_session() AS id, id_equipo_tg,anio_tg,tema,id_docente,NombreApellidoDocente,email
            FROM view_asig_docente_pdg
            order by id asc"; 
            $this->db->query($sql); */

            $sql="TRUNCATE TABLE asig_docente_aux_pdg"; 
            $this->db->query($sql);
            $sql="INSERT INTO asig_docente_aux_pdg(id,id_equipo_tg,anio_tesis,ciclo_tesis,tema_tesis,id_docente,NombreApellidoDocente,correo_docente,es_docente_director_pdg)
                    SELECT func_inc_var_session(),id_equipo_tg,anio_tesis,ciclo_tesis,tema_tesis,id_docente,NombreApellidoDocente,correo_docente,es_docente_director_pdg
                    FROM view_asig_docente_pdg";
            $this->db->query($sql);


            $crud = new grocery_CRUD();
            //$crud->set_table('view_asig_docente_aux_pdg');
            $crud->set_table('asig_docente_aux_pdg');
            /*la vista view_asig_docente_aux_pdg se llena con view_asig_docente_pdg*/
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            $crud->set_relation('id_docente','aux1_docente_pdg_tmp','{carnet} - {nombre} {apellido}');
            $crud->set_primary_key('id');
            $crud->order_by('id','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Asignación de Docente Asesor');
            //Definiendo las columnas que apareceran
            $crud->columns('id','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','NombreApellidoDocente','correo_docente','es_docente_director_pdg');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id','Id.')
                 ->display_as('id_equipo_tg','Equipo TG')
                 ->display_as('tema_tesis','Tema')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('id_docente','Id. Docente')
                 ->display_as('NombreApellidoDocente','Nombre Docente Asesor TG')
                 ->display_as('correo_docente','Email')
                 ->display_as('es_docente_director_pdg','¿Es Docente Director?');
            //Validación de requeridos
            $crud->required_fields('id_equipo_tg','id_docente','es_docente_director_pdg');                 
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','id_docente','NombreApellidoDocente','correo_docente','es_docente_director_pdg');
            //Definiendo los input que apareceran en el formulario de actualizacion
            $crud->edit_fields('id','id_equipo_tg','tema_tesis','anio_tesis','id_docente','NombreApellidoDocente','correo_docente','es_docente_director_pdg');
           
            //Validacion de ca´mpos en Add Forms
            $crud->callback_add_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_add_field('anio_tesis',array($this,'anioTesisSoloLectura'));
            $crud->callback_add_field('ciclo_tesis',array($this,'cicloTesisSoloLectura'));
            $crud->callback_add_field('NombreApellidoDocente',array($this,'NombreApellidoDocenteSoloLectura'));
            $crud->callback_add_field('correo_docente',array($this,'EmailSoloLectura'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO');               
            //Validacion de campos en Update Forms  
            $crud->callback_field('id',array($this,'IdSoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'mostrarEquipoTG'));            
            $crud->callback_edit_field('tema_tesis',array($this,'mostrarTema'));            
            $crud->callback_edit_field('anio_tesis',array($this,'mostrarAnioTg'));            
            $crud->callback_edit_field('ciclo_tesis',array($this,'mostrarCicloTg'));  
            $crud->callback_edit_field('NombreApellidoDocente',array($this,'mostrarnombreApellidoDocente'));            
            $crud->callback_edit_field('correo_docente',array($this,'mostrarEmail'));            
            
            //
            $crud->callback_insert(array($this,'insercion_AsigDocenteAsesor'));
            $crud->callback_update(array($this,'actualizacion_AsigDocenteAsesor')); 
            $crud->callback_delete(array($this,'delete_AsigDocenteAsesor')); 
                
            $output = $crud->render();
            $this->_AsigDocenteAsesor_output($output);
    }



    public function datos_tema($id){
        echo json_encode($this->AsigDocenteAsesor_gc_model->Get_Datos_Tema($id));
    }
    
    public function datos_docente($id){
        //echo json_encode($this->AsigDocenteAsesor_gc_model->Get_Datos_Docentes($id));
        echo json_encode($this->AsigDocenteAsesor_gc_model->Get_Datos_Docentes($id));
    }    
    
    public function valida_data($id1,$id2){
        
        /*Valida si el docente asesor de trabajo de graduacion ya ha sido ingresado para el mismo equipo */
        $comprobar=$this->AsigDocenteAsesor_gc_model->Get_Valida_Data($id1,$id2);
                    if ($comprobar ==1){

                        echo ("Este docente ya ha sido asignado a este mismo equipo");
                    }
                    else{
                        //significa que no se realizo la operacion DML
                        echo ("Se registro un error inesperado, favor consulte con el administrador del sistema");
                    }                  
    }    
        

    //Para Add forms
    function agrandaCajaTexto(){
        return '<textarea class="form-control" id="field-tema_tesis" name="field-tema_tesis" readonly></textarea>';
    }   
    function anioTesisSoloLectura(){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" readonly>';
    }       
    function cicloTesisSoloLectura(){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" readonly>';
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
    function mostrarEquipoTG($value){
        return '<input class="form-control" type="text" id="field-id_equipo_tg" name="field-id_equipo_tg" value="'.$value.'" readonly>';
    }          
    function mostrarTema($value){
        return '<textarea class="form-control" id="field-tema_tesis" name="field-tema_tesis" readonly>'.$value.'</textarea>';
    }                  
    function mostrarAnioTg($value){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" value="'.$value.'" readonly>';
    }            
    function mostrarCicloTg($value){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" value="'.$value.'" readonly>';
    }                
    function mostrarnombreApellidoDocente($value){
        return '<input class="form-control" type="text" id="field-nombreApellidoDocente" name="field-nombreApellidoDocente" value="'.$value.'"  readonly>';
    }       
    function mostrarEmail($value){
        return '<input class="form-control" type="text" id="field-correo_docente" name="field-correo_docente" value="'.$value.'" readonly>';
    }   

    public function insercion_AsigDocenteAsesor($post_array){
        


            $dues_iterar=$this->AsigDocenteAsesor_gc_model->BuscarDUEsEquipo($post_array['id_equipo_tg'],$post_array['field-anio_tesis'],$post_array['field-ciclo_tesis']);

            foreach ($dues_iterar->result_array()  as $row)
            {
                    $resultado=$row['id_due'];
                    $insertar['id_equipo_tg']=$post_array['id_equipo_tg'];
                    $insertar['anio_tesis']=$post_array['field-anio_tesis'];
                    $insertar['ciclo_tesis']=$post_array['field-ciclo_tesis'];
                    $insertar['id_due']=$resultado;
                    $insertar['id_docente']=$post_array['id_docente'];
                    $insertar['id_proceso']='PDG';
                    $insertar['id_cargo']='2';
                    $insertar['correlativo_tutor_ss']=0;
                    $insertar['es_docente_director_pdg']=$post_array['es_docente_director_pdg'];;

                    $comprobar=$this->AsigDocenteAsesor_gc_model->Create($insertar);
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
            if ($huboError==1) {
                    /*Resentear el contador de id utilizados para las vistas*/
                    $sql = "SELECT func_reset_inc_var_session()";
                    $this->db->query($sql);
                    return false;
            }
            else{
                    /*Resentear el contador de id utilizados para las vistas*/
                    $sql = "SELECT func_reset_inc_var_session()";
                    $this->db->query($sql);                
                    //ENVIAR EMAIL a Docente asesor asignado
                    $this->load->library('email');
                    $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                    $this->email->to($post_array['field-correo_docente']);
                    $this->email->subject('Notificación: Asignación de Docente Asesor de Proceso de Graduación');
                    $this->email->message('<p>Usted ha sido asignado como docente asesor de Proceso de Graduación del equipo '.$insertar['id_equipo_tg'].' cuyo tema es: '.$post_array['field-tema_tesis'].'</p>');
                    $this->email->set_mailtype('html'); 
                    $this->email->send();
                    
                    $correo_estudianteA=$this->AsigDocenteAsesor_gc_model->ObtenerCorreosIntegrantesEquipo($insertar['id_equipo_tg'],$insertar['anio_tesis'],$insertar['ciclo_tesis']);
                    foreach ($correo_estudianteA as $row)
                    {
                            $correoA=$row['email'];
                            //ENVIAR EMAIL a alumno al que se le asigna el docente
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoA);
                            $this->email->subject('Notificación: Asignación de Docente Asesor de Proceso de Graduación');
                            $this->email->message('<p>Se le ha asignado como docente asesor de Proceso de Graduación, cuyo tema es: '.$post_array['field-tema_tesis'].', al docente: '.$post_array['field-nombreApellidoDocente'].'</p>');
                            $this->email->set_mailtype('html'); 
                            $this->email->send();                        
                    } 
                    return true;
            }

    }        
    public   function actualizacion_AsigDocenteAsesor($post_array){
            /*Identificar el id_docente alamcenado en la bd para ese equipo segun la llave primaria*/
            $id_docente_old=$this->AsigDocenteAsesor_gc_model->ConsultaDocenteAsesorOld($post_array['field-id']);
        //Inserción a asignado
        /*$sql = "INSERT INTO nuevo(valor)
        VALUES (".$this->db->escape($post_array['field-id']).")";
        $this->db->query($sql);

        $sql = "INSERT INTO nuevo(valor)
        VALUES ('".$id_docente_old."')";
        $this->db->query($sql);*/

            $dues_iterar=$this->AsigDocenteAsesor_gc_model->BuscarDUEsEquipo($post_array['field-id_equipo_tg'],$post_array['field-anio_tesis'],$post_array['field-ciclo_tesis']);

            foreach ($dues_iterar->result_array()  as $row)
            {
                    $resultado=$row['id_due'];
                    $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
                    $update['anio_tesis']=$post_array['field-anio_tesis'];
                    $update['ciclo_tesis']=$post_array['field-ciclo_tesis'];
                    $update['id_due']=$resultado;
                    $update['id_docente']=$post_array['id_docente'];
                    $update['id_proceso']='PDG';
                    $update['id_cargo']='2';
                    $update['id_docente_old']=$id_docente_old;
                    $update['es_docente_director_pdg']=$post_array['es_docente_director_pdg'];

                    $comprobar=$this->AsigDocenteAsesor_gc_model->Update($update);
                    if ($comprobar >=1){
                    $huboError=0;
                    //ENVIAR EMAIL a Docente asesor asignado
                    $this->load->library('email');
                    $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                    $this->email->to($post_array['field-correo_docente']);
                    $this->email->subject('Notificación: Asignación de Docente Asesor de Proceso de Graduación');
                    $this->email->message('<p>Usted ha sido asignado como docente asesor de Proceso de Graduación del equipo '.$update['id_equipo_tg'].' cuyo tema es: '.$post_array['field-tema_tesis'].'</p>');
                    $this->email->set_mailtype('html'); 
                    $this->email->send();
                    
                    $correo_estudianteA=$this->AsigDocenteAsesor_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tesis'],$update['ciclo_tesis']);
                    foreach ($correo_estudianteA as $row)
                    {
                            $correoA=$row['email'];
                            //ENVIAR EMAIL a alumno al que se le asigna el docente
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoA);
                            $this->email->subject('Notificación: Asignación de Docente Asesor de Proceso de Graduación');
                            $this->email->message('<p>Se le ha asignado como docente asesor de Proceso de Graduación, cuyo tema es: '.$post_array['field-tema_tesis'].', al docente: '.$post_array['field-nombreApellidoDocente'].'</p>');
                            $this->email->set_mailtype('html'); 
                            $this->email->send();                        
                    }   
                    
                    return true;
                    }
                    else{
                        //significa que no se realizo la operacion DML
                        $huboError=1;
                        return false;
                    }                    

            }
            if ($huboError==1) {
                    /*Resentear el contador de id utilizados para las vistas*/
                    $sql = "SELECT func_reset_inc_var_session()";
                    $this->db->query($sql); 
                    return false;
            }
            else{
                    /*Resentear el contador de id utilizados para las vistas*/
                    $sql = "SELECT func_reset_inc_var_session()";
                    $this->db->query($sql);                 
                    return true;
            }    
        }

    public function delete_AsigDocenteAsesor($primary_key)
    {

            /*Encontrando id_equipo anio_tesis, ciclo_tesis y id_docente segun llave primaria seleccionada*/

            $llaves_delete['primary_key']=$primary_key;

            $llaves=$this->AsigDocenteAsesor_gc_model->EncontrarLLavesDelete($llaves_delete);
            foreach ($llaves as $row)
            {
                    $id_equipo_tg=$row['id_equipo_tg'];
                    $anio_tesis=$row['anio_tesis'];
                    $ciclo_tesis=$row['ciclo_tesis'];
                    $id_docente=$row['id_docente'];
            } 


            /*Haciendo borrado de asignación de docente asesor de trabajo de graduación por cada DUE encontrado*/
            $dues_iterar=$this->AsigDocenteAsesor_gc_model->BuscarDUEsEquipo($id_equipo_tg,$anio_tesis,$ciclo_tesis);

            foreach ($dues_iterar->result_array()  as $row)
            {
                    $resultado=$row['id_due'];
                    $delete['id_equipo_tg']=$id_equipo_tg;
                    $delete['anio_tesis']=$anio_tesis;
                    $delete['ciclo_tesis']=$ciclo_tesis;                    
                    $delete['id_due']=$resultado;
                    $delete['id_docente']=$id_docente;
                    $delete['id_proceso']='PDG';
                    $delete['id_cargo']='2';

                    $comprobar=$this->AsigDocenteAsesor_gc_model->Delete($delete);
                    if ($comprobar >=1){
                        $huboError=0;
                        //return true;
                          /**************Se coloco aca ya que por alguna razon desconocida
                                            cuandop se colocaba en el modelo hacia la actualizacion
                                            pero transaccionaba como si fuera error*******************/
                          
                              
                                    //Actualizar en tabla pdg_nota_defensa_publica el alumno en cuestion asociado al id_equipo, anio_tg y ciclo_tgy cargo=2
                                    $sql = "UPDATE pdg_nota_defensa_publica SET id_docente=null
                                        WHERE id_equipo_tg='".$delete['id_equipo_tg']."'
                                        AND anio_tg='".$delete['anio_tesis']."'
                                        AND ciclo_tg='".$delete['ciclo_tesis']."'
                                        AND id_due='".$delete['id_due']."'
                                        AND id_cargo='2'";  
                                    $this->db->query($sql);              

                        /***********************************************************************************/                        
                    }
                    else{
                        //significa que no se realizo la operacion DML
                        $huboError=1;
                        //return false;
                    }                    

            }
            if ($huboError==1) {
                    /*Resentear el contador de id utilizados para las vistas*/
                    $sql = "SELECT func_reset_inc_var_session()";
                    $this->db->query($sql); 
                    return false;
            }
            else{
                    /*Resentear el contador de id utilizados para las vistas*/
                    $sql = "SELECT func_reset_inc_var_session()";
                    $this->db->query($sql);                     
                    return true;
            }  

    }      


}