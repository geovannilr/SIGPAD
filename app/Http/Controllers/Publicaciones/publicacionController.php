<?php

namespace App\Http\Controllers\Publicaciones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Auth;
use \App\pub_publicacionModel;

class publicacionController extends Controller{
	public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $userLogin=Auth::user();
        /*if ($userLogin->can(['prePerfil.index'])) {
                //VERIFICAMOS EL ROL
                $prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$id)->get();
                return view('TrabajoGraduacion.PrePerfil.index',compact('prePerfiles'));
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }*/
        $publicaciones = pub_publicacionModel::all();
        return view('publicacion.index',compact('publicaciones'));
    }
}
