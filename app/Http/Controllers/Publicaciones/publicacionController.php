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
use \App\pub_aut_publicacion_autorModel;
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
    		$titulo = "Datos Incorrectos" ;
    		$mensaje = "La publicación de trabajo de graduación de la cúal quiere ver el detalle  no existe.";
    		return view('error',compact('titulo','mensaje'));
    	}else{
    		$publicacionModel = new pub_publicacionModel();
    		$colaboradores = $publicacionModel->getColaboradores($id);
    		if (empty($colaboradores)) {
    			$colaboradores ="NA";
    		}
    		$autores =pub_aut_publicacion_autorModel::where('id_pub',$id)->get();
    		if (empty($autores)) {
    			$autores = "NA";
    		}
    		$publicacionesArchivos=pub_arc_publicacion_archivoModel::where('id_pub',$id)->get();
    		return view('publicacion.show',compact('publicacionesArchivos','publicacion','autores','colaboradores'));
    	}
    	
    }

    public function createDocumento($idPublicacion){
    	//VERIFICAMOS SI EXISTEN EN LA BASE DE DATOS ESOS ID
    	$publicacion = pub_publicacionModel::find($idPublicacion);
    	if(empty($publicacion)){
    		//return "LOS PARAMETROS RECIBIDOS NO SON CORRECTOS";
    		$titulo = "Datos Incorrectos" ;
    		$mensaje = "La publicación de trabajo de graduación a la cuál le quiere agregar un nuevo documento no existe.";
    		return view('error',compact('titulo','mensaje'));
    	}else{ //LOS PARAMETROS VIENEN CORRECTAMENTE
    		return view('publicacion.createDocumento',compact('publicacion'));
    	}
    }
     public function edit($id){

          	$publicaciones = pub_publicacionModel::all();
          	Session::flash('message-error', 'Funcionalidad no disponible en estos momentos');
       		 return view('publicacion.index',compact('publicaciones'));
            
    }
    public function destroy($id){

          	$publicaciones = pub_publicacionModel::all();
          	Session::flash('message-error', 'Funcionalidad no disponible en estos momentos');
       		 return view('publicacion.index',compact('publicaciones'));
            
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
    	 	$validatedData = $request->validate([
                'descripcion_pub_arc' => 'required|max:400',
                'documento' => 'required'
            ],
            [
    			'descripcion_pub_arc.required' => 'La descripción del documento publicación de trabajo de graduación es obligatoria',
    			'descripcion_pub_arc.max' => 'La descripción del documento publicación de trabajo de graduación debe contener como máximo 400 caracteres.',
    			'documento.required' => 'El documento de publicación de trabajo de graduación es obligatorio.'

    		]
        );
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
                'ubicacion_pub_arc'       	 => $nombre,
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
    		$path= public_path().$_ENV['PATH_PUBLICACIONES'];
    		$ruta = $path.$archivo->ubicacion_pub_arc;
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
	        	if (File::exists($path.$archivo->ubicacion_pub_arc)){
      	 			File::delete($path.$archivo->ubicacion_pub_arc);	
     			}
	        	
	        	$archivo->nombre_pub_arc = $file->getClientOriginalName();
	        	$archivo->ubicacion_pub_arc =$nombre;
	        	$archivo->fecha_subida_pub_arc = $fecha;
	        }
	        $archivo->save();
            Session::flash('message','Documento Modificado correctamente!');
            return Redirect::to('publicacion/'.$publicacion->id_pub);                            
    }

     function downloadDocumento(Request $request){
    	$archivo = pub_arc_publicacion_archivoModel::find($request['archivo']);
    	$path= public_path().$_ENV['PATH_PUBLICACIONES'];
    	//verificamos si el archivo existe y lo retornamos
    	$ruta =$path.$archivo->ubicacion_pub_arc;
     	if (File::exists($ruta)){
      	  return response()->download($ruta);
     	}else{
     		Session::flash('error','El documento no se encuentra disponible , es posible que haya sido  borrado');
            return Redirect::to('publicacion/'.$archivo->id_pub);
     	}
    	//return $path;
    }
    function  searchView(){
        return view('publicacion.search');
    }
     function  buscarPublicaciones(Request $request){
        $autor = $request["autor"];
        $docenteDirector = $request["docenteDirector"];
        $observador = $request["observador"];
        $coordinador = $request["coordinador"];
        $asesor = $request["asesor"];
        $bodyHtml="";
        if ($autor=="1") {
            $publicacion = new pub_publicacionModel();
            $publicacionesAutor = $publicacion->getPubNombreAutor($request["texto"]);
            $bodyHtml = self::armarHTMLResultado($publicacionesAutor,$bodyHtml,'Autor');
        }
        if ($docenteDirector=="1") {
            $publicacion = new pub_publicacionModel();
            $publicacionesDocente = $publicacion->getPubNombreColaborador($request["texto"],3);
            $bodyHtml = self::armarHTMLResultado($publicacionesDocente,$bodyHtml,'Docente Director');
        }
        if ($observador=="1") {
            $publicacion = new pub_publicacionModel();
            $publicacionesObservador = $publicacion->getPubNombreColaborador($request["texto"],4);
            $bodyHtml = self::armarHTMLResultado($publicacionesObservador,$bodyHtml,'Observador');
        }
        if ($coordinador=="1") {
            $publicacion = new pub_publicacionModel();
            $publicacionesCoordinador = $publicacion->getPubNombreColaborador($request["texto"],1);
            $bodyHtml = self::armarHTMLResultado($publicacionesCoordinador,$bodyHtml,'Coordinador');
        }
        if ($asesor=="1") {
            $publicacion = new pub_publicacionModel();
            $publicacionesAsesor = $publicacion->getPubNombreColaborador($request["texto"],2);
            $bodyHtml = self::armarHTMLResultado($publicacionesAsesor,$bodyHtml,'Asesor');
        }
        return $bodyHtml;
    }

    private function armarHTMLResultado($publicaciones,$bodyHtml,$criterio){
        $bodyHtml.='<div class="table-responsive">
            <p class="text-center"><b>RESULTADOS POR '.strtoupper($criterio).'</b></p>
            <table class="table table-hover table-striped  display" id="listTable">

                <thead>
                    <th>Cod. Publicación</th>
                    <th>Año</th>
                    <th>Título</th>
                    <th>'.$criterio.'</th>
                    '.($criterio=="Autor"?"":"<th>Acción</th>").'
                    <th style="width: 150px !important;">Acción</th> 
                </thead>
                <tbody>';

        if (sizeof($publicaciones)!=0) {
            foreach ($publicaciones as $publicacion) {
                $archivo = pub_arc_publicacion_archivoModel::where('id_pub',$publicacion->id_pub)->first();
                $bodyHtml.='<tr>';
                $bodyHtml.='<td>'.$publicacion->codigo_pub.'</td>';
                $bodyHtml.='<td>'.$publicacion->anio_pub.'</td>';
                $bodyHtml.='<td>'.$publicacion->titulo_pub.'</td>';
                $bodyHtml.='<td>'.$publicacion->nombre_criterio.'</td>';
                if($criterio!="Autor")
                    $bodyHtml.='<td>'.$publicacion->tipo_criterio.'</td>';
                $bodyHtml.='<td style="text-align: center;">
                                <a class="btn btn-dark" href="'.url("/").'/publicacion/'.$publicacion->id_pub.'" target="_blank" title="Ver detalles"><i class="fa fa-eye"></i></a>';
                if(!empty($archivo->id_pub_arc))
                    $bodyHtml.='&nbsp;&nbsp;<button type="button" class="btn btn-outline-info" onclick="downloadDocument('.($archivo->id_pub_arc).')" title="Descargar archivo"><i class="fa fa-download"></i></button>';
                $bodyHtml.='</td>';
                $bodyHtml.='</tr>';
            }
        }else{
            $bodyHtml.='<tr><td colspan="5">NO SE ENCONTRARON RESULTADOS EN LA BUSQUEDA</td></tr>';
        }
        $bodyHtml.='</tbody>
                        </table>
                        </div><br><br>';
        return $bodyHtml;
    }
}
