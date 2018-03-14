<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RegisTribuEva_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='4'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/RegisTribuEva_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _RegisTribuEva_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormRegisTribuEva_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            /*Tabla temporal utilizada para alojar los docentes asesores de trabajo de graduacion*/
            $sql="TRUNCATE TABLE aux2_docente_pdg_tmp"; 
            $this->db->query($sql);
            $sql="INSERT INTO aux2_docente_pdg_tmp SELECT * FROM view_aux2_docente_pdg";
            $this->db->query($sql);

            /*Resentear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE asig_tribunal_aux_pdg"; 
            $this->db->query($sql);
            $sql="INSERT INTO asig_tribunal_aux_pdg(id,id_equipo_tg,anio_tesis,ciclo_tesis,tema_tesis,id_docente,NombreApellidoDocente,correo_docente,id_cargo,descripcion,es_docente_tribu_principal)
                  SELECT func_inc_var_session(),id_equipo_tg,anio_tesis,ciclo_tesis,tema_tesis,id_docente,NombreApellidoDocente,correo_docente,id_cargo,descripcion,es_docente_tribu_principal
                  FROM view_asig_tribunal_pdg;";
            $this->db->query($sql);


            $crud = new grocery_CRUD();
            //$crud->set_table('view_asig_docente_aux_pdg');
            $crud->set_table('asig_tribunal_aux_pdg');
            /*la vista view_asig_docente_aux_pdg se llena con view_asig_tribunal_pdg*/
            $crud->set_relation('id_equipo_tg','pdg_equipo_tg','id_equipo_tg');
            $crud->set_relation('id_docente','aux2_docente_pdg_tmp','{carnet} - {nombre} {apellido}');
            $crud->set_primary_key('id');
            $crud->order_by('id','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Asignación de Tribunal Evaluador');
            //Definiendo las columnas que apareceran
            $crud->columns('id','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','NombreApellidoDocente','id_cargo','descripcion','correo_docente','es_docente_tribu_principal');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id','Id.')
                 ->display_as('id_equipo_tg','Equipo TG')
                 ->display_as('tema_tesis','Tema TG')
                 ->display_as('anio_tesis','Año TG')
                 ->display_as('ciclo_tesis','Ciclo TG')
                 ->display_as('id_docente','Id. Docente')
                 ->display_as('NombreApellidoDocente','Nombre Docente Tribunal Evaluador TG')
                 ->display_as('id_cargo','Cod. Cargo') 
                 ->display_as('descripcion','Cargo') 
                 ->display_as('correo_docente','Email')
                 ->display_as('es_docente_tribu_principal','¿Docente Principal tribunal Evaluador?');
            //Validación de requeridos
            $crud->required_fields('id_equipo_tg','id_docente','es_docente_tribu_principal');                                  
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','id_docente','NombreApellidoDocente','id_cargo','descripcion','correo_docente','es_docente_tribu_principal');
            //Definiendo los input que apareceran en el formulario de actualizacion
            $crud->edit_fields('id','id_equipo_tg','tema_tesis','anio_tesis','ciclo_tesis','id_docente','NombreApellidoDocente','id_cargo','descripcion','correo_docente');
           
            //Validacion de ca´mpos en Add Forms
            $crud->callback_add_field('tema_tesis',array($this,'agrandaCajaTexto'));
            $crud->callback_add_field('anio_tesis',array($this,'anioTesisSoloLectura'));
            $crud->callback_add_field('ciclo_tesis',array($this,'cicloTesisSoloLectura'));
            $crud->callback_add_field('NombreApellidoDocente',array($this,'NombreApellidoDocenteSoloLectura'));
            $crud->callback_add_field('id_cargo',array($this,'codCargoSoloLectura'));
            $crud->callback_add_field('descripcion',array($this,'descripCargoSoloLectura'));
            $crud->callback_add_field('correo_docente',array($this,'EmailSoloLectura'));
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO');             
            
            //Validacion de campos en Update Forms  
            $crud->callback_field('id',array($this,'IdSoloLectura'));
            $crud->callback_edit_field('id_equipo_tg',array($this,'mostrarEquipoTG'));            
            $crud->callback_edit_field('tema_tesis',array($this,'mostrarTema'));            
            $crud->callback_edit_field('anio_tesis',array($this,'mostrarAnioTesis'));  
            $crud->callback_edit_field('ciclo_tesis',array($this,'mostrarCicloTesis')); 
            $crud->callback_edit_field('NombreApellidoDocente',array($this,'mostrarnombreApellidoDocente'));            
            $crud->callback_edit_field('id_cargo',array($this,'mostrarCodCargo'));
            $crud->callback_edit_field('descripcion',array($this,'mostrarDescripCargo'));
            $crud->callback_edit_field('correo_docente',array($this,'mostrarEmail'));            
            //$crud->callback_edit_field('es_docente_tribu_principal',array($this,'mostrarDocTribuPrinci')); 

            //
            $crud->callback_insert(array($this,'insercion_RegisTribuEva'));
            $crud->callback_update(array($this,'actualizacion_RegisTribuEva')); 
            $crud->callback_delete(array($this,'delete_RegisTribuEva')); 
                
            $output = $crud->render();
            $this->_RegisTribuEva_output($output);

    }



    public function datos_tema($id){
        echo json_encode($this->RegisTribuEva_gc_model->Get_Datos_Tema($id));
    }
    
    public function datos_docente($id){
        //echo json_encode($this->RegisTribuEva_gc_model->Get_Datos_Docentes($id));
        echo json_encode($this->RegisTribuEva_gc_model->Get_Datos_Docentes($id));
    }    
    
    public function valida_data($id1,$id2,$id3,$id4){
        
        /*Valida si el docente del tribunal Evaluador de trabajo de graduacion ya ha sido ingresado para el mismo equipo */
        $comprobar=$this->RegisTribuEva_gc_model->Get_Valida_Data($id1,$id2,$id3,$id4);
        //$comprobar=2;
                    if ($comprobar ==1){

                        echo ("Este docente ya ha sido asignado a este mismo equipo");
                    }
                    if ($comprobar ==2){

                        echo ("Este equipo ya posee docente 1 del tribunal Evaluador");
                    }                    
                    /*else{
                        //significa que no se realizo la operacion DML
                        echo ("Se registro un error inesperado, favor consulte con el administrador del sistema");
                    }   */               
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
    function codCargoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-id_cargo" name="field-id_cargo" value="'.$value.'" readonly>';
    }     
    function descripCargoSoloLectura($value){
        return '<input class="form-control" type="text" id="field-descripcion" name="field-descripcion" value="'.$value.'" readonly>';
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
    function mostrarAnioTesis($value){
        return '<input class="form-control" type="text" id="field-anio_tesis" name="field-anio_tesis" value="'.$value.'" readonly>';
    }    
    function mostrarCicloTesis($value){
        return '<input class="form-control" type="text" id="field-ciclo_tesis" name="field-ciclo_tesis" value="'.$value.'" readonly>';
    }        
    function mostrarCodCargo($value){
        return '<input class="form-control" type="text" id="field-id_cargo" name="field-id_cargo" value="'.$value.'" readonly>';
    }    
    function mostrarDescripCargo($value){
        return '<input class="form-control" type="text" id="field-descripcion" name="field-descripcion" value="'.$value.'" readonly>';
    } 
    function mostrarnombreApellidoDocente($value){
        return '<input class="form-control" type="text" id="field-nombreApellidoDocente" name="field-nombreApellidoDocente" value="'.$value.'"  readonly>';
    }       
    function mostrarEmail($value){
        return '<input class="form-control" type="text" id="field-correo_docente" name="field-correo_docente" value="'.$value.'" readonly>';
    }   


    public function insercion_RegisTribuEva($post_array){
        


            $dues_iterar=$this->RegisTribuEva_gc_model->BuscarDUEsEquipo($post_array['id_equipo_tg'],$post_array['field-anio_tesis'],$post_array['field-ciclo_tesis']);

            foreach ($dues_iterar->result_array()  as $row)
            {
                    $resultado=$row['id_due'];
                    $insertar['id_equipo_tg']=$post_array['id_equipo_tg'];
                    $insertar['anio_tesis']=$post_array['field-anio_tesis'];
                    $insertar['ciclo_tesis']=$post_array['field-ciclo_tesis'];
                    $insertar['id_due']=$resultado;
                    $insertar['id_docente']=$post_array['id_docente'];
                    $insertar['id_proceso']='PDG';
                    $insertar['id_cargo']=$post_array['field-id_cargo'];
                    $insertar['correlativo_tutor_ss']=0;
                    $insertar['es_docente_tribu_principal']=$post_array['es_docente_tribu_principal'];;

                    $comprobar=$this->RegisTribuEva_gc_model->Create($insertar);
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
                    $this->email->subject('Notificación: Asignación de Docente de Tribunal Evaluador de Proceso de Graduación');
                    $this->email->message('<p>Usted ha sido asignado como docente asesor de Tribunal Evaluador del Proceso de Graduación del equipo '.$insertar['id_equipo_tg'].' cuyo tema es: '.$post_array['field-tema_tesis'].'</p>');
                    $this->email->set_mailtype('html'); 
                    $this->email->send();
                    
                    $correo_estudianteA=$this->RegisTribuEva_gc_model->ObtenerCorreosIntegrantesEquipo($insertar['id_equipo_tg'],$insertar['anio_tesis'],$insertar['ciclo_tesis']);
                    foreach ($correo_estudianteA as $row)
                    {
                            $correoA=$row['email'];
                            //ENVIAR EMAIL a alumno al que se le asigna el docente
                            $this->load->library('email');
                            $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                            $this->email->to($correoA);
                            $this->email->subject('Notificación: Asignación de Docente de Tribunal Evaluador de Proceso de Graduación');
                            $this->email->message('<p>Se le ha asignado como docente asesor  de Tribunal Evaluador del Proceso de Graduación, cuyo tema es: '.$post_array['field-tema_tesis'].', al docente: '.$post_array['field-nombreApellidoDocente'].'</p>');
                            $this->email->set_mailtype('html'); 
                            $this->email->send();                        
                    }                                     
                    return true;
            }

    }        
    public   function actualizacion_RegisTribuEva($post_array){
            /*Identificar el id_docente alamcenado en la bd para ese equipo segun la llave primaria*/
            $id_docente_old=$this->RegisTribuEva_gc_model->ConsultaDocenteAsesorOld($post_array['field-id']);
            
            /*Identificar el id_cargo alamcenado en la bd para ese equipo segun la llave primaria*/
            $id_cargo_old=$this->RegisTribuEva_gc_model->ConsultaIdCargorOld($post_array['field-id']);

            $dues_iterar=$this->RegisTribuEva_gc_model->BuscarDUEsEquipo($post_array['field-id_equipo_tg'],$post_array['field-anio_tesis'],$post_array['field-ciclo_tesis']);

        //        Inserción a asignado
        /*$sql = "INSERT INTO nuevo(valor)
        VALUES (".$this->db->escape($post_array['field-id']).")";
        $this->db->query($sql);

        $sql = "INSERT INTO nuevo(valor)
        VALUES ('".$id_docente_old."')";
        $this->db->query($sql);
        $sql = "INSERT INTO nuevo(valor)
        VALUES ('".$id_cargo_old."')";
        $this->db->query($sql);
               $sql = "INSERT INTO nuevo(valor)
        VALUES (".$this->db->escape($post_array['field-id_equipo_tg']).")";
        $this->db->query($sql);
        $sql = "INSERT INTO nuevo(valor)
        VALUES (".$this->db->escape($post_array['field-anio_tesis']).")";
        $this->db->query($sql);*/


            foreach ($dues_iterar->result_array() as $row)
            {
                    $resultado=$row['id_due'];
                    $update['id_equipo_tg']=$post_array['field-id_equipo_tg'];
                    $update['anio_tesis']=$post_array['field-anio_tesis'];
                    $update['ciclo_tesis']=$post_array['field-ciclo_tesis'];
                    $update['id_due']=$resultado;
                    $update['id_cargo']=$post_array['field-id_cargo'];
                    $update['id_docente']=$post_array['id_docente'];
                    $update['id_proceso']='PDG';
                    $update['id_cargo_old']=$id_cargo_old;
                    $update['id_docente_old']=$id_docente_old;
                    $update['es_docente_tribu_principal']=$post_array['es_docente_tribu_principal'];
                    $comprobar=$this->RegisTribuEva_gc_model->Update($update);
                    if ($comprobar >=1){
                        $huboError=0;
                        //ENVIAR EMAIL a Docente asesor asignado
                        $this->load->library('email');
                        $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                        $this->email->to($post_array['field-correo_docente']);
                        $this->email->subject('Notificación: Asignación de Docente de Tribunal Evaluador de Proceso de Graduación');
                        $this->email->message('<p>Usted ha sido asignado como docente asesor de Tribunal Evaluador del Proceso de Graduación del equipo '.$update['id_equipo_tg'].' cuyo tema es: '.$post_array['field-tema_tesis'].'</p>');
                        $this->email->set_mailtype('html'); 
                        $this->email->send();
                        
                        $correo_estudianteA=$this->RegisTribuEva_gc_model->ObtenerCorreosIntegrantesEquipo($update['id_equipo_tg'],$update['anio_tesis'],$update['ciclo_tesis']);
                        foreach ($correo_estudianteA as $row)
                        {
                                $correoA=$row['email'];
                                //ENVIAR EMAIL a alumno al que se le asigna el docente
                                $this->load->library('email');
                                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                                $this->email->to($correoA);
                                $this->email->subject('Notificación: Asignación de Docente de Tribunal Evaluador de Proceso de Graduación');
                                $this->email->message('<p>Se le ha asignado como docente asesor  de Tribunal Evaluador del Proceso de Graduación, cuyo tema es: '.$post_array['field-tema_tesis'].', al docente: '.$post_array['field-nombreApellidoDocente'].'</p>');
                                $this->email->set_mailtype('html'); 
                                $this->email->send();                        
                        }                            
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
                    return true;
            }    
        }

    public function delete_RegisTribuEva($primary_key){

            /*Encontrando id_equipo anio_tesis ciclo_tesis y id_docente segun llave primaria seleccionada*/

            $llaves_delete['primary_key']=$primary_key;

            $llaves=$this->RegisTribuEva_gc_model->EncontrarLLavesDelete($llaves_delete);
            foreach ($llaves as $row)
            {
                    $id_equipo_tg=$row['id_equipo_tg'];
                    $anio_tesis=$row['anio_tesis'];
                    $ciclo_tesis=$row['ciclo_tesis'];
                    $id_docente=$row['id_docente'];
                    $id_cargo=$row['id_cargo'];
            } 


            /*Haciendo borrado de asignación de docente asesor de trabajo de graduación por cada DUE encontrado*/
            $dues_iterar=$this->RegisTribuEva_gc_model->BuscarDUEsEquipo($id_equipo_tg,$anio_tesis,$ciclo_tesis);

            foreach ($dues_iterar->result_array()  as $row)
            {
                    $resultado=$row['id_due'];
                    $delete['id_equipo_tg']=$id_equipo_tg;
                    $delete['anio_tesis']=$anio_tesis;
                    $delete['ciclo_tesis']=$ciclo_tesis;
                    $delete['id_due']=$resultado;
                    $delete['id_docente']=$id_docente;
                    $delete['id_proceso']='PDG';
                    $delete['id_cargo']=$id_cargo;

                    $comprobar=$this->RegisTribuEva_gc_model->Delete($delete);
                    if ($comprobar >=1){
                        $huboError=0;
                        //return true;


                            /**************Se coloco aca ya que por alguna razon desconocida
                                            cuandop se colocaba en el modelo hacia la actualizacion
                                            pero transaccionaba como si fuera error*******************/
                          //validando si es docente 1 de tribunal evaluador es decir cargo=5 ó docente 2 de tribunal evaluador es decir cargo=6
                                if ($delete['id_cargo']=='5'){
                                    //Actualizar en tabla pdg_nota_defensa_publica el alumno en cuestion asociado al id_equipo, anio_tg y ciclo_tgy cargo=5
                                    $sql = "UPDATE pdg_nota_defensa_publica SET id_docente=null
                                        WHERE id_equipo_tg='".$delete['id_equipo_tg']."'
                                        AND anio_tg='".$delete['anio_tesis']."'
                                        AND ciclo_tg='".$delete['ciclo_tesis']."'
                                        AND id_due='".$delete['id_due']."'
                                        AND id_cargo='5'";  
                                    $this->db->query($sql);              
                                }
                                if ($delete['id_cargo']=='6'){
                                    //Actualizar en tabla pdg_nota_defensa_publica el alumno en cuestion asociado al id_equipo, anio_tg y ciclo_tgy cargo=5
                                    $sql = "UPDATE pdg_nota_defensa_publica SET id_docente=null
                                        WHERE id_equipo_tg='".$delete['id_equipo_tg']."'
                                        AND anio_tg='".$delete['anio_tesis']."'
                                        AND ciclo_tg='".$delete['ciclo_tesis']."'
                                        AND id_due='".$update['id_due']."'
                                        AND id_cargo='6'";   
                                    $this->db->query($sql);             
                                } 



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