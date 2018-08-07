<?php

namespace App\Http\Controllers\TrabajoGraduacion;

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
                        $tribunal ="NA";
                        $etapas=self::getEtapasEvaluativas($idGrupo);
                        if (sizeof($etapas) == 0){
                        	$etapas="NA";
                        }
                        return view('TrabajoGraduacion.TrabajoDeGraduacion.index',compact('numero','estudiantes','tribunal','etapas'));
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
            }
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
