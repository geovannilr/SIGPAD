<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ConfEquiTg extends CI_Controller {
    function __construct(){
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        if($tipo == 'Estudiante' and 
            ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
            $this->load->model('PDG/ConfEquiTg_model');
        }
        else{
            redirect('Login');
        }
        //$this->removeCache();
    }
    public function index($mensaje = null){
        $this->load->model('PDG/ConfEquiTg_model');
        //$data['equipo'] = $this->ConfEquiTg_model->Get_Equipo();
        $data['mensaje'] = $mensaje;
        $data['candidatos'] = $this->ConfEquiTg_model->Get_Candidatos();
        $data['output']=null;
        $data['contenido']='PDG/FormConfEquiTg';
        $this->load->view('templates/content', $data);        
    }
    public function equipo_creado($mensaje = null){
        //$this->load->model('PDG/ConfEquiTg_model');
        //$data['equipo'] = $this->ConfEquiTg_model->Get_Equipo();
        $data['mensaje'] = $mensaje;
        //$data['candidatos'] = $this->ConfEquiTg_model->Get_Candidatos();
        $data['output']=null;
        $data['contenido']='PDG/FormEquipoCreado';

        $this->load->view('templates/content', $data);        
    }    
    //usarla como ejemplo luego borrarla
    function consultarPerfiles(){
        $this->load->model('PDG/Perfil_model');
        $listadoPerfiles = $this->Perfil_model->obtener_perfiles();
            
        $l_perfiles= array();
        foreach($listadoPerfiles as $i){
            $itemPerfil = array();
            $itemPerfil['id_perfil'] = $i['id_perfil'];
            $itemPerfil['id_detalle_pdg'] = $i['id_detalle_pdg'];
            $itemPerfil['ciclo'] = $i['ciclo'];
            $itemPerfil['anio'] = $i['anio'];
            $itemPerfil['objetivo_general'] = $i['objetivo_general'];
            $itemPerfil['objetivo_especifico'] = $i['objetivo_especifico'];
            $itemPerfil['descripcion'] = $i['descripcion'];
            $l_perfiles[] = $itemPerfil;
        }

        $data = array(
            'perfiles' => $l_perfiles
        );    

        //$data['camaras'] = $this->camara_model->Show();
        $data['contenido'] = 'PDG/consultar_perfiles';
        $this->load->view('templates/content',$data); 
        //$this->load->view('pagina_principal', $data);
    }
    
 

  public function Crear()
    {
        if($this->input->post())
        {
            
            /*Obtencion de datos necesarios para las inserciones/actualizaciond e instancias*/
            //obtencion de año y mes
            $mes=date("n");
            $anio=date("Y");
            //obtencion del ciclo
            if ($mes>=1 and $mes<=6){
                $ciclo=1;
            }
            else{
                $ciclo=2;
            }
            //obtener nuevo numero de equipo
            $id_equipo_tg_new=$this->ConfEquiTg_model->Obtener_Numero_Equipo($anio);
            //obtener nuevo numero de detalle
            $id_detalle_pdg_new=$this->ConfEquiTg_model->Obtener_Numero_Detalle();  
            //obtener nuevo numero de perfil
            $id_perfil_new=$this->ConfEquiTg_model->Obtener_Numero_Perfil();                        
            /*Corroborar que se han seleccionado 4 estudiantes*/
            $cant=0;
            

            if (is_null($this->input->post("id_candidato"))) {
                $mensaje = 'Debe seleccionar como mínimo 1 estudiante';
                $this->index($mensaje);
            }else
            {
                    $cant=0;
                    foreach($this->input->post("id_candidato") as $idCandidatos){
                        /*****$sql="INSERT INTO tabla(valor) VALUES('1')";
                        $this->db->query($sql); *****/
                        $cant=$cant+1;
                    }  
                    if ($cant>5){
                        $mensaje = 'Debe seleccionar un máximo de 5 estudiantes';
                        $this->index($mensaje);
                    }else{
                        if ($cant==0){
                            $mensaje = 'Debe seleccionar un minimo 1 estudiante';
                            $this->index($mensaje);
                        }else{
                            //Si el usuario escogio la cantidad correcta de alumnos el flujo continua
                            //Recuperacion de variables de la view
                            /////////////////////////////////////////////////////
                            //se comentario por isntrucciones de ing. vigil para que el tema y siglas sean ingresados de forma posterior
                            /////////////////////////////////////////////////////
                            //$tema_tg=$this->input->post('tema_tg'); 
                            //$siglas_tg=$this->input->post('siglas_tg'); 
                            ////////////////////////////////////////////////////
                            $tema_tg=' '; 
                            $siglas_tg=' ';

                            //Creacion de equipo
                            $comprobarCreaEquipo=$this->ConfEquiTg_model->Crear_Equipo($id_equipo_tg_new,$anio,$ciclo,$tema_tg,$siglas_tg);
                            if ($comprobarCreaEquipo == 0){
                                //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                $mensaje = ' comprobarCreaEquipo Ha ocurrido un error, favor contactese con el administrador del sistema';
                                $this->index($mensaje);
                            }
                            
                            
                            //Recorrer los alumnos seleccionados en la vista
                            foreach($this->input->post("id_candidato") as $idCandidatos){
                                //Actualizacion de estado en tabla gen_estudiantes
                                $comprobarActGenEstu=$this->ConfEquiTg_model->Actualizar_Gen_Estudiante($id_equipo_tg_new,$idCandidatos);
                                if ($comprobarActGenEstu == 0){
                                    //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                    $mensaje = 'comprobarActGenEstu Ha ocurrido un error, favor contactese con el administrador del sistema';
                                    $this->index($mensaje);
                                }
                                //ingreso a la tabla conforma
                                $comprobarCreaConforma=$this->ConfEquiTg_model->Crear_Conforma($id_equipo_tg_new,$anio,$ciclo,$idCandidatos);
                                
                                if ($comprobarCreaConforma == 0){
                                    //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                    $mensaje = 'comprobarCreaConforma Ha ocurrido un error, favor contactese con el administrador del sistema';
                                    $this->index($mensaje);
                                }                    

                            } 

                             //Creacion de detalle
                            $comprobarCreaDetalle=$this->ConfEquiTg_model->Crear_Detalle($id_detalle_pdg_new,$id_equipo_tg_new,$anio,$ciclo);

                            if ($comprobarCreaDetalle == 0){
                                //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                $mensaje = 'comprobarCreaDetalle Ha ocurrido un error, favor contactese con el administrador del sistema';
                                $this->index($mensaje);
                            }
                            //creacion de documentos
                            $comprobarCreaDocumentos=$this->ConfEquiTg_model->Crear_Documentos($id_detalle_pdg_new);

                            if ($comprobarCreaDocumentos == 0){
                                //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                $mensaje = 'comprobarCreaDocumentos Ha ocurrido un error, favor contactese con el administrador del sistema';
                                $this->index($mensaje);
                            }                

                            //creacion de perfil
                            $comprobarCreaPerfil=$this->ConfEquiTg_model->Crear_Perfil($id_perfil_new,$id_detalle_pdg_new);

                            if ($comprobarCreaPerfil == 0){
                                //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                $mensaje = 'comprobarCreaPerfil Ha ocurrido un error, favor contactese con el administrador del sistema';
                                $this->index($mensaje);
                            }                
                            //creacion de Notas de Antpeproyecto
                            $comprobarCreaNotaAnteproy=$this->ConfEquiTg_model->Crear_Notas_Anteproyecto($id_equipo_tg_new,$tema_tg,$anio,$ciclo);

                            if ($comprobarCreaNotaAnteproy == 0){
                                //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                $mensaje = 'comprobarCreaNotaAnteproy Ha ocurrido un error, favor contactese con el administrador del sistema';
                                $this->index($mensaje);
                            }

                            //Recorrer los alumnos seleccionados en la vista
                            foreach($this->input->post("id_candidato") as $idCandidatos){
                                //Creación de nota de Etapa 1
                                $comprobarCreaNotaEta1=$this->ConfEquiTg_model->Crear_Notas_Etapa1($id_equipo_tg_new,$tema_tg,$anio,$ciclo,$idCandidatos);
                                if ($comprobarCreaNotaEta1 == 0){
                                    //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                    $mensaje = 'comprobarCreaNotaEta1 Ha ocurrido un error, favor contactese con el administrador del sistema';
                                    $this->index($mensaje);
                                }
                                //Creación de nota de Etapa 2
                                $comprobarCreaNotaEta2=$this->ConfEquiTg_model->Crear_Notas_Etapa2($id_equipo_tg_new,$tema_tg,$anio,$ciclo,$idCandidatos);
                                if ($comprobarCreaNotaEta2 == 0){
                                    //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                    $mensaje = 'comprobarCreaNotaEta2 Ha ocurrido un error, favor contactese con el administrador del sistema';
                                    $this->index($mensaje);
                                }
                                //Creación de nota de Defensa Publica
                                $comprobarCreaNotaDefenPubli=$this->ConfEquiTg_model->Crear_Notas_Defensa_Publica($id_equipo_tg_new,$tema_tg,$anio,$ciclo,$idCandidatos);
                                if ($comprobarCreaNotaDefenPubli == 0){
                                    //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                    $mensaje = 'comprobarCreaNotaDefenPubli Ha ocurrido un error, favor contactese con el administrador del sistema';
                                    $this->index($mensaje);
                                }         
                               //Creación de nota de Consolidado de Notas
                                $comprobarCreaConsoNotas=$this->ConfEquiTg_model->Crear_Consolidado_Notas($id_equipo_tg_new,$tema_tg,$anio,$ciclo,$idCandidatos);
                                if ($comprobarCreaConsoNotas == 0){
                                    //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                    $mensaje = 'comprobarCreaConsoNotas Ha ocurrido un error, favor contactese con el administrador del sistema';
                                    $this->index($mensaje);
                                }                                
                            } 
                            //foreach($this->input->post("nombre_campo") as $idCandidatos){
                                
                            /*$sql = "INSERT INTO tabla (valor) 
                                VALUES (
                                '".$siglas_tg."'
                                )";
                            $this->db->query($sql);*/

                            //} 
                            $mensaje = "Se ha creado correctamente el equipo. Su numero de Equipo para el Año ".$anio."
                                        y ciclo ".$ciclo." es el ".$id_equipo_tg_new." ";
                            //redirect('http://localhost/SIGPA/index.php/PDG/ConfEquiTg');
                            $this->index($mensaje);                            
                        }

                    }                   
            }//fin del else de ifnull de validacion      
            
           
        }
        else 
        {
            echo "aun no hay submit";
        }
    }



}
?>
