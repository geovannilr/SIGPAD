<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Session;
use \App\gen_EstudianteModel;

class IncidenciasController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    function indexIncidencias(){
        return view('TrabajoGraduacion.Incidencias.index');
    }

    function getAlumnosRetirados(){
        $estudiantes = gen_EstudianteModel::getAlumnosRetirados();
        return view('TrabajoGraduacion.Incidencias.listadoRetirados',compact(['estudiantes']));
        
    }
    function cambiarEstadoRetirado(Request $request){
    	$validatedData = $request->validate([
            'carnet' => 'required'
        	], [
            'carnet.required' => 'Ocurrió un problema al cambiar disponibilidad del alumno'
        ]);

    	$carnet = $request['carnet'];
    	try {
	    	$estudiante = gen_EstudianteModel::where('carnet_gen_est','=',$carnet)->first();
	    	$estudiante->disponible_gen_est = 1;
	    	$estudiante->save();
	    	Session::flash('message', 'El alumno con carnet '.$carnet. ' ahora esta disponible para conformar grupo de trabajo de graduación');
    	} catch (\Exception $e) {
    		Session::flash('message-error', 'Ocurrió un problema al cambiar disponibilidad del alumno');
    	}
    	return redirect('incidencias/alumnosRetirados');
    }
}
