<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\pdg_dcn_docenteModel;
use Illuminate\Support\Facades\Redirect;

class PerfilDocentePublicoController extends Controller
{
    public function index($idDocente){
   	   $docente = pdg_dcn_docenteModel::find($idDocente);
   	   if (empty($docente->id_pdg_dcn)) {
   	   		return Redirect::to($_ENV['URLSITIO']);
   	   }else if($docente->perfilPrivado == 1){
   	   		return Redirect::to($_ENV['URLSITIO']);
   	   }else{
   	   	return view('PerfilDocente.perfilDocente',compact('idDocente'));
   	   }
       
    }
public function index2($jornada){
   
       return view('PerfilDocente.docenteListado',compact('jornada'));
    }

}
