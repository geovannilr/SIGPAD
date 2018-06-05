<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use \App\pdg_ppe_pre_perfilModel;
use \App\gen_EstudianteModel;
use \App\pdg_gru_grupoModel;
use \App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
class PrePerfilController extends Controller
{
   public function __construct(){
        $this->middleware('auth');
    }

	/**
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['prePerfil.index'])) {
        	if (Auth::user()->isRole('administrador_tdg')){
        		//VERIFICAMOS EL ROL
        		$prePerfiles =pdg_ppe_pre_perfilModel::all();
		       	return view('TrabajoGraduacion.PrePerfil.index',compact('prePerfiles'));
        	}else{
        		$grupo=self::verificarGrupo($userLogin->user)->getData();
		        $estudiantes=json_decode($grupo->msg->estudiantes);
		 
		        if ($grupo->errorCode == '0'){
		        	$idGrupo = $estudiantes[0]->idGrupo;
		        	$miGrupo = pdg_gru_grupoModel::find($idGrupo);
		        	if ($miGrupo->id_cat_sta == 3 ) {//APROBADO
		        		$numero=$miGrupo->numero_pdg_gru;
		        		$prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
		        		return view('TrabajoGraduacion.PrePerfil.index',compact('prePerfiles','numero'));
		        	}else{
		        		//EL GRUPO AUN NO HA SIDO APROBADO
		        	Session::flash('message-error', 'Tu grupo de trabajo de graduación aún no ha sido aprobado');
		            return  view('template');
		        	}
		        }else{
		        	//NO HA CONFORMADO UN GRUPO
		        	Session::flash('message-error', 'Para poder acceder a esta opción, primero debes conformar un grupo de trabajo de graduación');
		            return  view('template');
		        }
        	}
	     
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    $userLogin=Auth::user();
    	 if ($userLogin->can(['prePerfil.create'])) {
	        $grupo=self::verificarGrupo($userLogin->user)->getData();
	        $estudiantes=json_decode($grupo->msg->estudiantes);
	        if ($grupo->errorCode == '0'){
	        	$idGrupo = $estudiantes[0]->idGrupo;
	        	$miGrupo = pdg_gru_grupoModel::find($idGrupo);
	        	if ($miGrupo->id_cat_sta == 3 ) {//APROBADO
	        		$tiposTrabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::pluck('nombre_cat_tpo_tra_gra', 'id_Cat_tpo_tra_gra')->toArray();
	        		return view('TrabajoGraduacion.PrePerfil.create',compact('tiposTrabajos'));
	        	}else{
	        		//EL GRUPO AUN NO HA SIDO APROBADO
	        	Session::flash('message-error', 'Tu grupo de trabajo de graduación aún no ha sido aprobado');
	            return  view('template');
	        	}
	        }else{
	        	//NO HA CONFORMADO UN GRUPO
	        	Session::flash('message-error', 'Para poder acceder a esta opción, primero debes conformar un grupo de trabajo de graduación');
	            return  view('template');
	        }
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$validatedData = $request->validate([
                'tema_pdg_ppe' => 'required|max:80',
                'id_cat_tpo_tra_gra' => 'required',
                'documento' => 'required'
         ]);
  	   $userLogin=Auth::user();
  	   $grupo=self::verificarGrupo($userLogin->user)->getData();
	        $estudiantes=json_decode($grupo->msg->estudiantes);
	        if ($grupo->errorCode == '0'){
	        	$idGrupo = $estudiantes[0]->idGrupo;
	        }
       //obtenemos el campo file definido en el formulario
      	$file = $request->file('documento');
       //obtenemos el nombre del archivo
      	$nombre = "Grupo1"."_2018_".date('hms').$file->getClientOriginalName();
       //indicamos que queremos guardar un nuevo archivo en el disco local
        Storage::disk('prePerfiles')->put($nombre, File::get($file));
        $fecha=date('Y-m-d H:m:s');
            $path= public_path()."\Uploads\PrePerfil\ ";

           $lastId = pdg_ppe_pre_perfilModel::create
            ([
                'tema_pdg_ppe'   				 => $request['tema_pdg_ppe'],
                'nombre_archivo_pdg_ppe'       	 =>  $nombre,
                'ubicacion_pdg_ppe'  		 	 => trim($path).$nombre,
                'fecha_creacion_pdg_ppe'       	 => $fecha,
                'id_pdg_gru'					 => $idGrupo,
                'id_cat_sta'					 => 7,
                'id_cat_tpo_tra_gra'			 => $request['id_cat_tpo_tra_gra'],
                'id_gen_usuario'                 => $userLogin->id
            ]); 
            Session::flash('message','Pre-Perfil Registrado correctamente!');
        	return Redirect::to('prePerfil');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){

           $userLogin=Auth::user();
           if ($userLogin->can(['prePerfil.edit'])) {
           $prePerfil=pdg_ppe_pre_perfilModel::find($id);
           if ($prePerfil->id_cat_sta == 3){//aprobado
           		Session::flash('message-error','No puedes modificar un Pre-Perfil una vez ha sido aprobado!');
        		return Redirect::to('prePerfil');
           }elseif ($prePerfil->id_cat_sta == 8) { //RECHAZADO
           		ession::flash('message-error','No puedes modificar un Pre-Perfil una vez ha sido rechazado!');
        		return Redirect::to('prePerfil');
           }else{
           	 $tiposTrabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::pluck('nombre_cat_tpo_tra_gra', 'id_Cat_tpo_tra_gra')->toArray();
	       	return view('TrabajoGraduacion.PrePerfil.edit',compact('tiposTrabajos','prePerfil'));
           }
          
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {	$userLogin=Auth::user();
        $validatedData = $request->validate([
                'tema_pdg_ppe' => 'required|max:80',
                'id_cat_tpo_tra_gra' => 'required',
         ]);
        $file = $request->file('documento');
        $prePerfil=pdg_ppe_pre_perfilModel::find($id);
        $prePerfil->tema_pdg_ppe = $request['tema_pdg_ppe'];
        $prePerfil->id_cat_tpo_tra_gra = $request['id_cat_tpo_tra_gra'];

       if (!is_null($file)) {
       		//obtenemos el nombre del archivo
      		$nombre = "Grupo1"."_2018_".date('hms').$file->getClientOriginalName();
       		Storage::disk('prePerfiles')->delete($prePerfil->nombre_archivo_pdg_ppe);
       		//indicamos que queremos guardar un nuevo archivo en el disco local
        	Storage::disk('prePerfiles')->put($nombre, File::get($file));
        	$fecha=date('Y-m-d H:m:s');
            $path= public_path()."/Uploads/PrePerfil/ ";
            $prePerfil->nombre_archivo_pdg_ppe = $nombre;
            $prePerfil->ubicacion_pdg_ppe = trim($path).$nombre;
            $prePerfil->fecha_creacion_pdg_ppe = $fecha;
            $prePerfil->id_gen_usuario = $userLogin->id;
            $prePerfil->save();
            Session::flash('message','Pre-Perfil Modificado correctamente!');
       		return Redirect::to('/prePerfil');
       }else {
       	 	$prePerfil->save();
            Session::flash('message','Pre-Perfil Modificado correctamente!');
       		return Redirect::to('/prePerfil');
       }
        /*
        //$usuario->fill($request->all()); 
        $prePerfil->save();
        Session::flash('message','Pre-Perfil Modificado correctamente!');
        return Redirect::to('/prePerfil');*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['prePerfil.destroy'])) {
            pdg_ppe_pre_perfilModel::destroy($id);
            Session::flash('message','Pre-Perfil Eliminado Correctamente!');
            return Redirect::to('/prePerfil');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
        
    } 

    public function verificarGrupo($carnet) {
	    $estudiante = new gen_EstudianteModel();
	    $respuesta = $estudiante->getGrupoCarnet($carnet);
	    return $respuesta;     
    }
     public function aprobarPrePerfil(Request $request) {
	    $prePerfil =pdg_ppe_pre_perfilModel::find($request['idPrePerfil']);
	    $prePerfil->id_cat_sta = 3 ;//APROBADO
	    $prePerfil->save();
	    Session::flash('message','Pre-Perfil Aprobado Correctamente!');
            return Redirect::to('/prePerfil');     
    }
    public function rechazarPrePerfil(Request $request) {
	    $prePerfil =pdg_ppe_pre_perfilModel::find($request['idPrePerfil']);
	    $prePerfil->id_cat_sta = 8 ;//RECHAZADO
	    $prePerfil->save();
	    Session::flash('message','Pre-Perfil Rechazado Correctamente!');
            return Redirect::to('/prePerfil');    
    }
    function downloadPrePerfil(Request $request){
    	$name = $request['archivo'];
    	$path= public_path()."/Uploads/PrePerfil/ ";
    	//verificamos si el archivo existe y lo retornamos
     	if (Storage::disk('prePerfiles')->exists($name)){
      	  return response()->download(trim($path).$name);
     	}else{
     		Session::flash('error','El archivo no se encuentra disponible , es posible que fue borrado');
             return redirect()->route('prePerfil.index');
     	}
    	//return $path;
    }
}
