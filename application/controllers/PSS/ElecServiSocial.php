<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ElecServiSocial extends CI_Controller {
    function __construct(){
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        $id_doc_est = $this->session->userdata('id_doc_est');
        $nombre = $this->session->userdata('nombre');
        $apellido = $this->session->userdata('apellidos');
        
        if($tipo == 'Estudiante' and ($tipo_estudiante == '2' or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7')){

            $this->load->model('PSS/ElecServiSocial_model');
        //$this->removeCache();
        }
        else{
            redirect('Login');
        }
    }
    public function index($mensaje = null){
        $this->load->model('PSS/ElecServiSocial_model');
        //$data['equipo'] = $this->ConfEquiTg_model->Get_Equipo();
        $data['mensaje'] = $mensaje;
        $data['candidatos'] = $this->ElecServiSocial_model->Get_Candidatos();
        $data['output']=null;
        $data['contenido']='PSS/FormElecServiSocial';
        $this->load->view('templates/content', $data);        
    }
    //usarla como ejemplo luego borrarla
    /*function consultarPerfiles(){
        $this->load->model('PSS/Perfil_model');
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
    }*/

  public function Crear()
    {
        if($this->input->post())
        {
            
            /*Obtencion de datos necesarios para las inserciones/actualizacion  de instancias*/
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
         
            //obtener el costo de la hor de servicio social
            $costo_hora=$this->ElecServiSocial_model->Obtener_Costo_Hora();
            //DUE del alumno que esta en la sesion 
            $id_due= $this->session->userdata('id_doc_est');

                       
            /*Corroborar que se han seleccionado como maximo 2 servicios sociales*/
            
            if (is_null($this->input->post("id_candidato"))) {
                $mensaje = 'Debe seleccionar como mínimo 1 servicio social';
                $this->index($mensaje);
            }else{
                    $cant=0;
                    foreach($this->input->post("id_candidato") as $idCandidatos){
                        $cant=$cant+1;
                    }           
                    if ($cant>2){
                        $mensaje = 'Debe seleccionar un máximo de 2 servicios sociales por transacción';
                        $this->index($mensaje);
                    }
                    else{
                        if ($cant==0){
                            $mensaje = 'Debe seleccionar un minimo de 1 servicio social por transacción';
                            $this->index($mensaje);
                        }
                        else{
                            //Si el usuario escogio la cantidad correcta de servicios sociales el flujo continua
                            //Recuperacion de variables de la view

                            $id_servicio_social=$this->input->post('id_servicio_social');
                            //$institucion=$this->input->post('institucion');
                            //$modalidad=$this->input->post('modalidad');
                            //$nombre_servicio_social=$this->input->post('nombre_servicio_social');
                            //$cantidad_estudiante=$this->input->post('cantidad_estudiante');
                            //$disponibilidad=$this->input->post('disponibilidad');
                            //$descripcion=$this->input->post('descripcion');
                            //$nombre_contacto_ss=$this->input->post('nombre_contacto_ss');
                            //$email_contacto_ss=$this->input->post('email_contacto_ss');
                                       
                            
                            //Recorrer los servicios sociales seleccionados en la vista
                            foreach($this->input->post("id_candidato") as $idCandidatos){
                                //obtener nuevo numero de detalle
                                $id_detalle_expediente_new=$this->ElecServiSocial_model->Obtener_Numero_Detalle();
                                
                                //creacion de pss_detalle_expediente
                                $comprobarCreaExpediente=$this->ElecServiSocial_model->Crear_Expediente($id_detalle_expediente_new,$idCandidatos,$id_due,$costo_hora);
                                
                                if ($comprobarCreaExpediente == 0){
                                    //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                    $mensaje = 'comprobarCreaExpediente Ha ocurrido un error, favor contactese con el administrador del sistema';
                                    $this->index($mensaje);
                                }   
                                //Actualizacion de disponibilidad de estudiante por servicio social
                                $comprobarActDisponXSS=$this->ElecServiSocial_model->Actualizar_Disponibilidad_Estudiante_x_SS($idCandidatos);
                                
                                if ($comprobarActDisponXSS == 0){
                                    //Si afecto como minimo a un registro la oepracion fue correcta, por lo tanto volver a cargar el index
                                    $mensaje = 'comprobarActDisponXSS Ha ocurrido un error, favor contactese con el administrador del sistema';
                                    $this->index($mensaje);
                                }                                      

                            } 


                            $mensaje = "Inscripción realizada correctamente";
                            //redirect('http://localhost/SIGPA/index.php/PDG/ConfEquiTg');
                            $this->index($mensaje);                    
                        }
                    }

            }


            
           
        }
        else 
        {
            echo "aun no hay submit";
        }
    }



}
?>
