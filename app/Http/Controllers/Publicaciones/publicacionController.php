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
    public function show($id){
    	
    }
    public function createDocumento($idPublicacion){
    	//VERIFICAMOS SI EXISTEN EN LA BASE DE DATOS ESOS ID
    	$publicacion = pub_publicacionModel::find($idPublicacion);
    	if(empty($publicacion)){
    		return "LOS PARAMETROS RECIBIDOS NO SON CORRECTOS";
    	}else{ //LOS PARAMETROS VIENEN CORRECTAMENTE
    		return view('publicacion.createDocumento',compact('publicacion'));
    	}
    }
    public function storeDocumento(Request $request){
    		$file = $request->file('documento');
    		$publicacion = pub_publicacionModel::find($request['idPublicacion']);
	       //obtenemos el nombre del archivo
	      	$nombre = "Codigo".$publicacion->codigo_pub.date('hms').$file->getClientOriginalName();
	       //indicamos que queremos guardar un nuevo archivo en el disco local
	        Storage::disk('Uploads')->put($nombre, File::get($file));
	        $fecha=date('Y-m-d H:m:s');
	        $path= public_path().$_ENV['PATH_UPLOADS'];
    }
}
