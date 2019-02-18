<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Session;
use \App\gen_EstudianteModel;
class IncidenciasController extends Controller{
	public function __construct() {
		$this->middleware('auth');
	}
   
   function getAlumnosRetirados(){
   	$estudiantes = gen_EstudianteModel::getAlumnosRetirados();
   	return var_dump($estudiantes);
   }
}
