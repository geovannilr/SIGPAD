<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Maatwebsite\Excel\Facades\Excel;
use \App\pdg_dcn_docenteModel;

class GestionDocenteController extends Controller
{
    function create(){
        return view('PerfilDocente.create');
    }
    function store(Request $request){
        $validatedData = $request->validate([
            'documentoPerfil' => 'required',
        ]);
        $bodyHtml = '';
        $data = Excel::load($request->file('documentoPerfil'), function ($reader) {
            $reader->setSelectedSheetIndices(array(4)); //4-6
        })->get();
        $experienciaLaboral = $data->toArray();
        var_dump($experienciaLaboral);
    }
	function index(){

		return "test";
	}
    function getInfoDocente(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getDataGestionDocente($request['docente']);
    	return $info;
    }
    function getHistorial(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getHistorialAcademico($request['docente']);
    	return $info;
    }
    function getExperiencia(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getDataExperienciaDocente($request['docente']);
    	return $info;
    	
    }
    function getCertificaciones(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getDataCertificacionesDocente($request['docente']);
    	return $info;
    	
    }
    function getSkills(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getDataSkillsDocente($request['docente']);
    	return $info;
    	
    }
    function getGeneralInfoDocente(Request $request){
        $docente = new pdg_dcn_docenteModel();
        $info = $docente->getGeneralInfo($request['docente']);
        return $info;
        
    }

 function getListadoDocentes(Request $request){
        $docente = new pdg_dcn_docenteModel();
        $info = $docente->getListadoDocenteByJornada($request['jornada']);
        return $info;
        
    }    
    
    
}
