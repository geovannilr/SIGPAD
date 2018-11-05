<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use App\pdg_tra_gra_trabajo_graduacionModel;
use App\pdg_tri_gru_tribunal_grupoModel;
use  Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Auth;
use \App\pdg_ppe_pre_perfilModel;
use \App\gen_EstudianteModel;
use \App\pdg_gru_grupoModel;
use \App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
use \App\pdg_gru_est_grupo_estudianteModel;
use \App\pdg_dcn_docenteModel;
use Zend\Ldap\Ldap;

class TrabajoDeGraduacionController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

   	public function index(){
        $userLogin=Auth::user();
        if ($userLogin->can(['prePerfil.index'])) {
            if (Auth::user()->isRole('administrador_tdg')){
                 $prePerfil = new  pdg_ppe_pre_perfilModel();
                 $gruposPrePerfil=$prePerfil->getGruposPrePerfil();
                return view('TrabajoGraduacion.PrePerfil.indexPrePerfil',compact('gruposPrePerfil'));
            }elseif (Auth::user()->isRole('estudiante')) {
                $estudiante = new gen_EstudianteModel();
                $idGrupo = $estudiante->getIdGrupo($userLogin->user);
                if ($idGrupo != 'NA'){
                    $grupo=self::verificarGrupo($userLogin->user)->getData();
                    $estudiantes=json_decode($grupo->msg->estudiantes);
                    $miGrupo = pdg_gru_grupoModel::find($idGrupo);
                    if ($miGrupo->id_cat_sta == 3 ) {//APROBADO
                        $prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
                        $numero=$miGrupo->numero_pdg_gru;
                        $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($idGrupo);
                        if(empty($tribunal)){
                            $tribunal="NA";
                        }
                        $etapas=self::getEtapasEvaluativas($idGrupo);
                        if (sizeof($etapas) == 0){
                        	$etapas="NA";
                        }
                        $tema = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->select('tema_pdg_tra_gra')->first();
                        return view('TrabajoGraduacion.TrabajoDeGraduacion.index',compact('numero','estudiantes','tribunal','etapas','tema','idGrupo'));
                    }else{
                        //EL GRUPO AUN NO HA SIDO APROBADO
                    Session::flash('message-error', 'Tu grupo de trabajo de graduación aún no ha sido aprobado');
                    return  view('template');
                    }
                }else{
                    //NO HA CONFORMADO UN GRUPO
                    Session::flash('message-error', 'Para poder acceder a esta opción, primero debes conformar un grupo de trabajo de graduación');
                    return  view('template');
                }
            }elseif (Auth::user()->isRole('docente_asesor')) {
                return "DOCENTE ASESOR";
            }
        }
       
    }
    /*Listado de grupos filtrados por docente asesor*/
    public function dashboardIndex(){
        $userLogin=Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        //return $docente->id_pdg_dcn;
        $grupo = new pdg_gru_grupoModel();
        $grupos = $grupo->getGruposDocente($docente->id_pdg_dcn);
        return view('TrabajoGraduacion.Dashboard.Grupos.index',compact('grupos'));
        //CONSUMIMOS EL SP DE LISTADO DE GRUPOS POR DOCENTE
    }
    public function dashboardGrupo($idGrupo){
        $userLogin=Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        //SE DEBE VERIFICAR QUE EL GRUPO PERTENECE AL DOCENTE select id_pdg_gru from pdg_tri_gru_tribunal_grupo where id_pdg_dcn=idDocente
        $grupo= new pdg_gru_grupoModel();
        $estudiantesGrupo = $grupo->getDetalleGrupo($idGrupo);
        //$prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
        $grupo = pdg_gru_grupoModel::find($idGrupo);
        //return var_dump($grupo);
        if (blank($grupo)) {
           return view("error");
        }
        $numero=$grupo->numero_pdg_gru;
        $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($idGrupo);
        if(empty($tribunal)){
            $tribunal="NA";
        }
        $etapas=self::getEtapasEvaluativas($idGrupo);
        
        if (blank($etapas)){
            $etapas="NA";
        }
        $tema = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->select('tema_pdg_tra_gra')->first();
        if(!blank($tema)){
            return view('TrabajoGraduacion.TrabajoDeGraduacion.index',compact('numero','estudiantesGrupo','tribunal','etapas','tema','idGrupo'));
        }else{
            //Session::flash('message-error', 'El grupo seleccionado aún no ha empezado su proceso de trabajo de graduación');
            //return redirect()->route('listadoGrupos');
            return view("error");
        }
        

    }
    public function verificarGrupo($carnet) {
	    $estudiante = new gen_EstudianteModel();
	    $respuesta = $estudiante->getGrupoCarnet($carnet);
	    return $respuesta;     
    }

    public function getEtapasEvaluativas($idGrupo) {
	    $grupo = new pdg_gru_grupoModel();
	    $respuesta = $grupo->getEtapas($idGrupo);
	    return $respuesta;     
    }

}
