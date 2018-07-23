<?php

namespace App\Http\Controllers\Publicaciones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use \App\pub_publicacionModel;
use \App\pub_arc_publicacion_archivoModel;
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
    	$publicacion = pub_publicacionModel::find($id);
    	if (empty($publicacion)) {
    		return "PARAMETROS INCORRECTOS";
    	}else{
    		$publicacionesArchivos=pub_arc_publicacion_archivoModel::where('id_pub',$id)->get();
    		return view('publicacion.show',compact('publicacionesArchivos','publicacion'));
    	}
    	
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
     public function editDocumento($idPublicacion,$idArchivo){
    	//VERIFICAMOS SI EXISTEN EN LA BASE DE DATOS ESOS ID
    	$publicacion = pub_publicacionModel::find($idPublicacion);
    	$archivo = pub_arc_publicacion_archivoModel::find($idArchivo);
    	if(empty($publicacion)){
    		return "LOS PARAMETROS RECIBIDOS NO SON CORRECTOS";
    	}else{ //LOS PARAMETROS VIENEN CORRECTAMENTE
    		return view('publicacion.editDocumento',compact('publicacion','archivo'));
    	}
    }

  
    public function storeDocumento(Request $request){
    		$file = $request->file('documento');
    		$publicacion = pub_publicacionModel::find($request['publicacion']);
	       //obtenemos el nombre del archivo
	      	$nombre = "Codigo".$publicacion->codigo_pub.date('hms').$file->getClientOriginalName();
	       //indicamos que queremos guardar un nuevo archivo en el disco local
	        Storage::disk('publicaciones')->put($nombre, File::get($file));
	        $fecha=date('Y-m-d H:m:s');
	        $path= public_path().$_ENV['PATH_PUBLICACIONES'];
	         $lastIdDocumento = pub_arc_publicacion_archivoModel::create
            ([
                'id_pub'   				     => $publicacion->id_pub,
                'ubicacion_pub_arc'       	 => $path.$nombre,
                'fecha_subida_pub_arc'       => $fecha,
                'nombre_pub_arc'       	 	 => $file->getClientOriginalName(),
                'descripcion_pub_arc'        => $request['descripcion_pub_arc'],
                'ubicacion_fisica_pub_arc'   => 'PENDIENTE',
                'activo_pub_arc'   			 => 1
            ]);
            Session::flash('message','Documento Envíado correctamente!');
            return Redirect::to('publicacion/'.$publicacion->id_pub);                                     
    }
    public function deleteDocumento(Request $request){
    		$publicacion = pub_publicacionModel::find($request['publicacion']);
    		$archivo = pub_arc_publicacion_archivoModel::find($request['archivo']);
    		$ruta = $archivo->ubicacion_pub_arc;
    		if (File::exists($ruta)){
      	 		 File::delete($ruta);	
     		}
    		pub_arc_publicacion_archivoModel::destroy($request['archivo']);
            Session::flash('message','Documento Eliminado correctamente!');
            return Redirect::to('publicacion/'.$publicacion->id_pub);                                     
    }
    public function updateDocumento(Request $request){
    		$file = $request->file('documento');
    		$publicacion = pub_publicacionModel::find($request['publicacion']);
	        $fecha=date('Y-m-d H:m:s');
	        $path= public_path().$_ENV['PATH_PUBLICACIONES'];
	        //$archivos = pub_arc_publicacion_archivoModel::where('id_pub',$publicacion->id_pub)->first();
	        $archivo = pub_arc_publicacion_archivoModel::find($request['archivo']);
	        $archivo->descripcion_pub_arc = $request['descripcion_pub_arc'];
	        if (!empty($file)) {
	        //obtenemos el nombre del archivo
	      	$nombre = "Codigo".$publicacion->codigo_pub.date('hms').$file->getClientOriginalName();
	       //indicamos que queremos guardar un nuevo archivo en el disco local
	        Storage::disk('publicaciones')->put($nombre, File::get($file));
	        	if (File::exists($archivo->ubicacion_pub_arc)){
      	 			File::delete($archivo->ubicacion_pub_arc);	
     			}
	        	
	        	$archivo->nombre_pub_arc = $file->getClientOriginalName();
	        	$archivo->ubicacion_pub_arc = $path.$nombre;
	        	$archivo->fecha_subida_pub_arc = $fecha;
	        }
	        $archivo->save();
            Session::flash('message','Documento Modificado correctamente!');
            return Redirect::to('publicacion/'.$publicacion->id_pub);                            
    }
}
