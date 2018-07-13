<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Auth;
use \App\pdg_ppe_pre_perfilModel;
use \App\gen_EstudianteModel;
use \App\pdg_gru_grupoModel;
use \App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
use \App\cat_eta_eva_etapa_evalutativaModel;
use \App\cat_tpo_doc_tipo_documentoModel;

class DocumentoController extends Controller{
	public function __construct(){
        $this->middleware('auth');
    }
    public function createDocumento($idEtapa,$idTipoDoc){
    	//VERIFICAMOS SI EXISTEN EN LA BASE DE DATOS ESOS ID
    	$etapa = cat_eta_eva_etapa_evalutativaModel::find($idEtapa);
    	$tipoDocumento = cat_tpo_doc_tipo_documentoModel::find($idTipoDoc);
    	if(sizeof($tipoDocumento)==0 || sizeof($etapa)==0 ){
    		return "LOS PARAMETROS RECIBIDOS NO SON CORRECTOS";
    	}else{ //LOS PARAMETROS VIENEN CORRECTAMENTE
    		return view('TrabajoGraduacion.DocumentoEtapaEvaluativa.create',compact('etapa','tipoDocumento'));
    	}
    }

    public function store(Request $request){
    	$userLogin=Auth::user();
    	$estudiante = new gen_EstudianteModel();
	    $idGrupo = $estudiante->getIdGrupo($userLogin->user);
	    return $idGrupo;
       //obtenemos el campo file definido en el formulario
      	/*$file = $request->file('documento');
       //obtenemos el nombre del archivo
      	$nombre = "Grupo1"."_2018_".date('hms').$file->getClientOriginalName();
       //indicamos que queremos guardar un nuevo archivo en el disco local
        Storage::disk('prePerfiles')->put($nombre, File::get($file));
        $fecha=date('Y-m-d H:m:s');
        $path= public_path().$_ENV['PATH_PREPERFIL'];
           
           $lastId = pdg_ppe_pre_perfilModel::create
            ([
                'tema_pdg_ppe'   				 => $request['tema_pdg_ppe'],
                'nombre_archivo_pdg_ppe'       	 =>  $nombre,
                'ubicacion_pdg_ppe'  		 	 => trim($path).$nombre,
                'fecha_creacion_pdg_ppe'       	 => $fecha,
                'id_pdg_gru'					 => $idGrupo,
                'id_cat_sta'					 => 7,
                'id_cat_tpo_tra_gra'			 => $request['id_cat_tpo_tra_gra'],
                'id_gen_usuario'                 => $userLogin->id
            ]); 
            Session::flash('message','Pre-Perfil Registrado correctamente!');
        	return Redirect::to('prePerfil');*/
    }

}
