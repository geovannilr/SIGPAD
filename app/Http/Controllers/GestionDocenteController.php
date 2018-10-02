<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use \App\pdg_dcn_docenteModel;

class GestionDocenteController extends Controller
{
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
    
    
}
