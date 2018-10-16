<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerfilDocentePublicoController extends Controller
{
    public function index($idDocente){
   
       return view('PerfilDocente.perfilDocente',compact('idDocente'));
    }
public function index2($jornada){
   
       return view('PerfilDocente.docenteListado',compact('jornada'));
    }

}
