<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use \App\pdg_ppe_pre_perfilModel;
use \App\gen_EstudianteModel;
use \App\pdg_gru_grupoModel;
use \App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
use \App\cat_eta_eva_etapa_evalutativaModel;
use \App\cat_tpo_doc_tipo_documentoModel;
use \App\pdg_doc_documentoModel;
use \App\pdg_arc_doc_archivo_documentoModel;


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
    		return view('TrabajoGraduacion.DocumentoEtapaEvaluativa.create',compact('etapa','tipoDocumento','idEtapa','idTipoDoc'));
    	}
    }

    public function store(Request $request){
    	$userLogin=Auth::user();
    	$estudiante = new gen_EstudianteModel();
	    $idGrupo = $estudiante->getIdGrupo($userLogin->user);
	    if ($idGrupo !="NA") {
	    	$grupo = pdg_gru_grupoModel::find($idGrupo);
	    	$anioGrupo = $grupo->anio_pdg_gru;
	    	//obtenemos el campo file definido en el formulario
	      	$file = $request->file('documento');
	       //obtenemos el nombre del archivo
	      	$nombre = "Grupo".$idGrupo."_".$anioGrupo."_".date('hms').$file->getClientOriginalName();
	       //indicamos que queremos guardar un nuevo archivo en el disco local
	        Storage::disk('prePerfiles')->put($nombre, File::get($file));
	        $fecha=date('Y-m-d H:m:s');
	        $path= public_path().$_ENV['PATH_PREPERFIL'];
           
           $lastIdDocumento = pdg_doc_documentoModel::create
            ([
                'id_pdg_gru'   				     => $idGrupo,
                'id_cat_tpo_doc'       			 => $request['tipoDocumento'],
                'fecha_creacion_pdg_doc'       	 => $fecha
            ]); 

            $lastIdArchivo = pdg_arc_doc_archivo_documentoModel::create
            ([
                'id_pdg_doc'					 => $lastIdDocumento->id_pdg_doc,
                'ubicacion_arc_doc'				 => trim($path).$nombre,
                'fecha_subida_arc_doc'			 => $fecha,
                'nombre_arc_doc'                 => $file->getClientOriginalName(),
                'activo'                         => 1
            ]);
           
            Session::flash('message','Documento Envíado correctamente!');
        	return Redirect::to('etapaEvaluativa/'.$request['etapa']);
	    }else{
	    	// NO POSEE GRUPO
	    }
       
    }

}
