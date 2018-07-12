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
    	if(sizeof($tipoDocumento)==0){
    		$tipoDocumento="NA";
    	}
    	if(sizeof($etapa)==0){
    		$etapa="NA";
    	}
    	return $tipoDocumento;
    }
}
