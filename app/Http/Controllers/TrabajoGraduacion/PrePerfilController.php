<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use App\pdg_dcn_docenteModel;
use App\pdg_tri_gru_tribunal_grupoModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use \App\pdg_ppe_pre_perfilModel;
use \App\pdg_per_perfilModel;
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
                 $prePerfil = new  pdg_ppe_pre_perfilModel();
                 $gruposPrePerfil=$prePerfil->getGruposPrePerfil();
                return view('TrabajoGraduacion.PrePerfil.indexPrePerfil',compact('gruposPrePerfil'));
            }elseif (Auth::user()->isRole('docente_asesor')) {
                 $prePerfil = new  pdg_ppe_pre_perfilModel();
                 
                 $gruposPrePerfil=$prePerfil->getGruposPrePerfilDocente($userLogin->id);
                 if ($gruposPrePerfil == "NA") {
                    Session::flash('message-error', 'Usted no ha sido asignado como asesor de un grupo de trabajo de graduación');
                    return  view('template'); 
                 }else{
                    return view('TrabajoGraduacion.PrePerfil.indexPrePerfil',compact('gruposPrePerfil'));
                 }
                 
            }
                elseif (Auth::user()->isRole('estudiante')) {
                $estudiante = new gen_EstudianteModel();
                //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
                $idGrupo = session('idGrupo');
                if ($idGrupo > 0){
                    $miGrupo = pdg_gru_grupoModel::find($idGrupo);
                    if ($miGrupo->id_cat_sta == 3 ) {//APROBADO
                        $prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
                        $numero=$miGrupo->numero_pdg_gru;
                        $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($idGrupo);
                        if(empty($tribunal[0])){
                            $tribunal="NA";
                        }
                        return view('TrabajoGraduacion.PrePerfil.index',compact('prePerfiles','numero','tribunal'));
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
        }
       
    }

    public function indexPrePerfil($id){
        $userLogin=Auth::user();
        if ($userLogin->can(['prePerfil.index'])) {
            $perfil = new  pdg_per_perfilModel();
            $grupos = $perfil->getGruposPerfilDocente($userLogin->id);
            $contador = 0 ;
            foreach ($grupos as $grupo) {
                if ($grupo->id_pdg_gru == $id) {
                    $contador++;
                }   
            }
            if ($contador==0 && !$userLogin->isRole('administrador_tdg')) {
                 return redirect('prePerfil');
            }
                //VERIFICAMOS EL ROL
            $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($id);
            if(empty($tribunal[0])){
                $tribunal="NA";
            }
                $prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$id)->get();
                return view('TrabajoGraduacion.PrePerfil.index',compact('prePerfiles','tribunal'));
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
	        /*$grupo=self::verificarGrupo($userLogin->user)->getData();
	        $estudiantes=json_decode($grupo->msg->estudiantes);*/
            $idGrupo = session('idGrupo');
	        if ($idGrupo > 0){
	        	//$idGrupo = $estudiantes[0]->idGrupo;
	        	$miGrupo = pdg_gru_grupoModel::find($idGrupo);
	        	if ($miGrupo->id_cat_sta == 3 ) {//APROBADO
                    $prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
                    $prePerfilAprobado=0;
                    foreach ($prePerfiles as $prePerfil) {
                        if ($prePerfil->id_cat_sta == 10) {
                            $prePerfilAprobado+=1;
                        }
                    }
                        $tiposTrabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::pluck('nombre_cat_tpo_tra_gra', 'id_Cat_tpo_tra_gra')->toArray();
                        return view('TrabajoGraduacion.PrePerfil.create',compact('tiposTrabajos'));
                    //}
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
        try {
                $validatedData = $request->validate([
                'tema_pdg_ppe' => 'required|max:200',
                'id_cat_tpo_tra_gra' => 'required',
                'documento' => 'required'
                ]);
               $userLogin=Auth::user();
               $estudiante = new gen_EstudianteModel();
               $idGrupo = session('idGrupo');
               //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
               $grupo = pdg_gru_grupoModel::find($idGrupo);
               $anioGrupo = $grupo->anio_pdg_gru;
               $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
               //obtenemos el campo file definido en el formulario
                $file = $request->file('documento');
               //obtenemos el nombre del archivo
                $nombre = 'Grupo'.$numeroGrupo."_".$anioGrupo."_".date('his').$file->getClientOriginalName();
                Storage::disk('Uploads')->put($nombre, File::get($file));
                 //movemos el archivo a la ubicación correspondiente segun grupo y años
                if ($_ENV['SERVER'] =="win") {
                        $nuevaUbicacion=trim($anioGrupo.'\Grupo'.$numeroGrupo.'\PrePerfil\ ').$nombre;
                }else{
                        $nuevaUbicacion=$anioGrupo.'/Grupo'.$numeroGrupo.'/PrePerfil/'.$nombre;
                }
                    
                Storage::disk('Uploads')->move($nombre, $nuevaUbicacion);
                $fecha=date('Y-m-d H:i:s'); 
                //$path= public_path()."\Uploads\PrePerfil\ ";
                 $path= public_path().$_ENV['PATH_UPLOADS'];
                   
                   $lastId = pdg_ppe_pre_perfilModel::create
                    ([
                        'tema_pdg_ppe'                   => $request['tema_pdg_ppe'],
                        'nombre_archivo_pdg_ppe'         =>  $file->getClientOriginalName(),
                        'ubicacion_pdg_ppe'              => $nombre,
                        'fecha_creacion_pdg_ppe'         => $fecha,
                        'id_pdg_gru'                     => $idGrupo,
                        'id_cat_sta'                     => 7,
                        'id_cat_tpo_tra_gra'             => $request['id_cat_tpo_tra_gra'],
                        'id_gen_usuario'                 => $userLogin->id
                    ]); 
                    Session::flash('message','Pre-Perfil Registrado correctamente!');
                    return Redirect::to('prePerfil');
        } catch (\Exception $e) {
           Session::flash('error', 'Ocurrió un error el guardar el PrePerfil');
            return Redirect::to('prePerfil');
        }
    
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
           $idGrupo = session('idGrupo');
           if ($idGrupo > 0 ) {
               if ($userLogin->can(['prePerfil.edit'])) {
                   $prePerfil=pdg_ppe_pre_perfilModel::find($id);
                   if (empty($prePerfil->id_pdg_ppe)){ //PREPERFIL NO EXISTE
                     return redirect('/');
                   }else if ($prePerfil->id_pdg_gru != $idGrupo) { //PREPERFIL NO LE PERTENECE AL GRUPO QUE QUIERE EDITARLO
                     return redirect('/');
                   }
                   else if ($prePerfil->id_cat_sta == 10){//aprobado
                        Session::flash('message-error','No puedes modificar un Pre-Perfil una vez ha sido aprobado!');
                        return Redirect::to('prePerfil');
                   }elseif ($prePerfil->id_cat_sta == 12) { //RECHAZADO
                        ession::flash('message-error','No puedes modificar un Pre-Perfil una vez ha sido rechazado!');
                        return Redirect::to('prePerfil');
                   }else{
                     $tiposTrabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::pluck('nombre_cat_tpo_tra_gra', 'id_Cat_tpo_tra_gra')->toArray();
                    return view('TrabajoGraduacion.PrePerfil.edit',compact('tiposTrabajos','prePerfil'));
                   }
          
                }else{
                    Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
                   return redirect('/');
                }
           }else{
            return redirect('/');
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
    {	
        try 
        {
                $userLogin=Auth::user();
                $validatedData = $request->validate([
                        'tema_pdg_ppe' => 'required|max:80',
                        'id_cat_tpo_tra_gra' => 'required',
                 ]);
                $file = $request->file('documento');
                $prePerfil=pdg_ppe_pre_perfilModel::find($id);
                $prePerfil->tema_pdg_ppe = $request['tema_pdg_ppe'];
                $prePerfil->id_cat_tpo_tra_gra = $request['id_cat_tpo_tra_gra'];
                $estudiante = new gen_EstudianteModel();
                //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
                $idGrupo = session('idGrupo');
                $grupo = pdg_gru_grupoModel::find($idGrupo);
                $anioGrupo = $grupo->anio_pdg_gru;
                $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
                $nombreViejo = $prePerfil->ubicacion_pdg_ppe;
               if (!empty($file)) {
                    //obtenemos el nombre del archivo
                    $nombre = 'Grupo'.$numeroGrupo."_".$anioGrupo."_".date('his').$file->getClientOriginalName();
                    Storage::disk('Uploads')->put($nombre, File::get($file));
                     //movemos el archivo a la ubicación correspondiente segun grupo y años
                    $nuevaUbicacion=$anioGrupo.'/Grupo'.$numeroGrupo.'/PrePerfil/'.$nombre;
                    Storage::disk('Uploads')->move($nombre, $nuevaUbicacion);
                    $fecha=date('Y-m-d H:i:s');
                     if ($_ENV['SERVER'] =="win") {
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\PrePerfil\ ';
                     }else{
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/PrePerfil/';
                     }
                    
                    $prePerfil->nombre_archivo_pdg_ppe = $file->getClientOriginalName();
                    $prePerfil->ubicacion_pdg_ppe = $nombre; //SOLO SE GUARDA NOMBRE AHORA
                    $prePerfil->fecha_creacion_pdg_ppe = $fecha;
                    $prePerfil->id_gen_usuario = $userLogin->id;
                    if (File::exists(trim($path).$prePerfil->ubicacion_pdg_ppe)){
                            File::delete(trim($path).$nombreViejo);    
                    }
                    $prePerfil->save();
                    Session::flash('message','Pre-Perfil Modificado correctamente!');
                    return Redirect::to('/prePerfil');
               }else {
                    $prePerfil->save();
                    Session::flash('message','Pre-Perfil Modificado correctamente!');
                    return Redirect::to('/prePerfil');
               }
        } catch (\Exception $e) {
            Session::flash('error', 'Ocurrió un error el actualizar el PrePerfil');
            return Redirect::to('prePerfil');
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        try {
                 $userLogin=Auth::user();
                $prePerfil=pdg_ppe_pre_perfilModel::find($id);
                if ($prePerfil->id_cat_sta == 10){//aprobado
                    Session::flash('message-error','No puedes eliminar un Pre-Perfil una vez ha sido aprobado!');
                    return Redirect::to('prePerfil');
                }
                $name=$prePerfil->nombre_archivo_pdg_ppe;
                $estudiante = new gen_EstudianteModel();
                //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
                $idGrupo = session('idGrupo');
                $grupo = pdg_gru_grupoModel::find($idGrupo);
                $anioGrupo = $grupo->anio_pdg_gru;
                $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
                $nombreViejo = $prePerfil->ubicacion_pdg_ppe;
                if ($_ENV['SERVER'] =="win") {
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\PrePerfil\ ';
                }else{
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/PrePerfil/';
                }
                if ($userLogin->can(['prePerfil.destroy'])) {
                    if (File::exists(trim($path).$prePerfil->ubicacion_pdg_ppe)){
                            File::delete(trim($path).$nombreViejo);    
                    }
                    pdg_ppe_pre_perfilModel::destroy($id);
                    Session::flash('message','Pre-Perfil Eliminado Correctamente!');
                    return Redirect::to('/prePerfil');
                }else{
                    Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
                    return  view('template');
                }

        } catch (\Exception $e) {
           Session::flash('error','Ocurrió un problema el elminiar el PrePerfil');
            return Redirect::to('/prePerfil');
        }
       
        
    } 

    public function verificarGrupo($carnet) {
	    $estudiante = new gen_EstudianteModel();
	    $respuesta = $estudiante->getGrupoCarnet($carnet);
	    return $respuesta;     
    }
     public function aprobarPrePerfil(Request $request) {
	    $prePerfil =pdg_ppe_pre_perfilModel::find($request['idPrePerfil']);
	    $prePerfil->id_cat_sta = 10 ;//APROBADO
	    $prePerfil->save();
	    Session::flash('message','Pre-Perfil Aprobado Correctamente!');
        return Redirect::to('/indexPrePerfil/'.$prePerfil->id_pdg_gru);     
    }
    public function rechazarPrePerfil(Request $request) {
	    $prePerfil =pdg_ppe_pre_perfilModel::find($request['idPrePerfil']);
	    $prePerfil->id_cat_sta = 12 ;//RECHAZADO
	    $prePerfil->save();
	    Session::flash('message','Pre-Perfil Rechazado Correctamente!');
            return Redirect::to('/indexPrePerfil/'.$prePerfil->id_pdg_gru);   
    }
    function downloadPrePerfil(Request $request){
        //$userLogin=Auth::user();
        $id = $request['archivo'];
        $prePerfil=pdg_ppe_pre_perfilModel::find($id);
        $name=$prePerfil->nombre_archivo_pdg_ppe;
        //$estudiante = new gen_EstudianteModel();
        //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
        //$idGrupo=$request['grupo'];
        $idGrupo = session('idGrupo');
        $grupo = pdg_gru_grupoModel::find($idGrupo);
        $anioGrupo = $grupo->anio_pdg_gru;
        $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
        if ($_ENV['SERVER'] =="win") {
                $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\PrePerfil\ ';
        }else{
                $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/PrePerfil/';
        }
    	//$path= public_path().$_ENV['PATH_PREPERFIL'];
    	//verificamos si el archivo existe y lo retornamos
     	if (File::exists(trim($path).$prePerfil->ubicacion_pdg_ppe)){
      	  return response()->download(trim($path).$prePerfil->ubicacion_pdg_ppe);
     	}else{
     		Session::flash('error','El archivo no se encuentra disponible , es posible que fue borrado');
             return redirect()->route('prePerfil.index');
     	}
    	//return $path;
    }
    public function getTribunalData($id){
        $new = null;
        $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($id);
        if(!$tribunal->isEmpty()){
            $new = false;
        }else{
            $new = true;
        }
        return response()->json(['state'=>$new,'info'=>$tribunal]);
    }
    private function validarPermisos($idGrupo){
        $permiso = Auth::user()->can(['grupo.index']);
        $grupoExists = pdg_gru_grupoModel::where('id_pdg_gru',$idGrupo)->exists();
        return $grupoExists&&$permiso;
    }
    public function verTribunal($id){
        $valid = $this->validarPermisos($id);
        if($valid){
            $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($id);
            return view('TrabajoGraduacion.PrePerfil.view',compact('tribunal','id'));
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
    public function docentesDisponibles($id){
        $docentes = pdg_dcn_docenteModel::getDocentesDisponibles($id);
        return $docentes;
    }
    public function rolesDisponibles($id){
        $roles = pdg_tri_gru_tribunal_grupoModel::getRolesDisponibles($id);
        return $roles;
    }
    public function asignarDocenteTribunal(Request $request){
        $errorCode = -1;
        $errorMessage = "No se procesaron los datos";
        try{
            $idDcn = $request['dcn'];
            $idGru = $request['gru'];
            $idRol = $request['rol'];
            $relacionTribunal = new pdg_tri_gru_tribunal_grupoModel();
            $relacionTribunal ->id_pdg_tri_rol = $idRol;
            $relacionTribunal ->id_pdg_gru = $idGru;
            $relacionTribunal ->id_pdg_dcn = $idDcn;
            $relacionTribunal ->save();
            $errorCode = 0;
            $errorMessage = "¡Docente asignado satisfactoriamente!";
        }catch (Exception $exception){
            $errorCode = 1;
            $errorMessage = "Su solicitud no pudo ser procesada";
        }
        return response()->json(['errorCode'=>$errorCode,'errorMessage'=>$errorMessage]);
    }
    public function deleteDocenteTribunal(Request $request){
        $errorCode = -1;
        $errorMessage = "No se procesaron los datos";
        try{
            $idRelTrib = $request['id_pdg_tri_gru'];
            $relacion = pdg_tri_gru_tribunal_grupoModel::find($idRelTrib);
            $relacion->delete();
            $errorCode = 0;
            $errorMessage = "¡Docente eliminado satisfactoriamente!";
        }catch (Exception $exception){
            $errorCode = 1;
            $errorMessage = "Su solicitud no pudo ser procesada";
        }
        return response()->json(['errorCode'=>$errorCode,'errorMessage'=>$errorMessage]);
    }
}
