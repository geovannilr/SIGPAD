<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use App\pdg_tra_gra_trabajo_graduacionModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use \App\pdg_per_perfilModel;
use \App\pdg_ppe_pre_perfilModel;
use \App\gen_EstudianteModel;
use \App\pdg_gru_grupoModel;
use \App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
use App\cat_tpo_doc_tipo_documentoModel;
use \App\pdg_doc_documentoModel;
use \App\pdg_arc_doc_archivo_documentoModel;
use \App\cat_ctg_tra_categoria_trabajo_graduacionModel;
use \App\pdg_dcn_docenteModel;


class PerfilController extends Controller
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
        if ($userLogin->can(['perfil.index'])) {
            if (Auth::user()->isRole('administrador_tdg')){
                 $perfil = new  pdg_per_perfilModel();
                 $gruposPerfil=$perfil->getGruposPerfil();
                return view('TrabajoGraduacion.Perfil.indexPerfil',compact('gruposPerfil'));
            }elseif (Auth::user()->isRole('docente_asesor')) { 
                $perfil = new  pdg_per_perfilModel();
                $grupos = $perfil->getGruposPerfilDocente($userLogin->id);
                if (sizeof($grupos) != 0) {
                    
                    $perfiles =pdg_per_perfilModel::getPerfilesByDocente($grupos);
                   
                    return view('TrabajoGraduacion.Perfil.index',compact('perfiles'));
                }else{
                    Session::flash('message-error', 'Usted no ha sido asignado como asesor de un grupo de trabajo de graduación');
                            return  view('template'); 
                }
                
            }elseif (Auth::user()->isRole('estudiante')) {
                $estudiante = new gen_EstudianteModel();
                //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
                $idGrupo = session('idGrupo');
                if ($idGrupo  > 0 ){
                    $miGrupo = pdg_gru_grupoModel::find($idGrupo);
                    if ($miGrupo->id_cat_sta == 3 ) {//APROBADO
                        $prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$idGrupo)->where('id_cat_sta','=','10')->get();
                        if (sizeof($prePerfiles)==0) {
                           Session::flash('message-error', 'Para poder ingresar a Perfiles de trabajo de graduación, primero debes enviar tus Pre-Perfiles y que al menos uno de estos sea aprobado por Coordinación.');
                            return  view('template'); 
                        }else{
                            $perfiles =pdg_per_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
                            $numero=$miGrupo->numero_pdg_gru;
                            return view('TrabajoGraduacion.Perfil.index',compact('perfiles','numero'));
                        }
                        
                    }else{
                        //EL GRUPO AUN NO HA SIDO APROBADO
                    Session::flash('message-error', 'Tu grupo de trabajo de graduación aún no ha sido aprobado');
                    return  redirect('/');
                    }
                }else{
                    //NO HA CONFORMADO UN GRUPO
                    Session::flash('message-error', 'Para poder acceder a esta opción, primero debes conformar un grupo de trabajo de graduación');
                    return  redirect('/');
                }
            }
        }else{
            Session::flash('message-error', 'No tienes permiso para acceder a esta opción');
                    return  redirect('/');
        }
       
    }

    public function indexPerfil($id){
        $userLogin=Auth::user();
        if ($userLogin->can(['prePerfil.index'])) {
                //VERIFICAMOS EL ROL
            $miGrupo = pdg_gru_grupoModel::find($id);
            $numero=$miGrupo->numero_pdg_gru;
                $perfiles =pdg_per_perfilModel::where('id_pdg_gru', '=',$id)->get();
                return view('TrabajoGraduacion.Perfil.index',compact('perfiles','numero'));
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
	       // $grupo=self::verificarGrupo($userLogin->user)->getData();
	        //$estudiantes=json_decode($grupo->msg->estudiantes);
            $idGrupo = session('idGrupo');
	        if ($idGrupo > 0){
	        	$miGrupo = pdg_gru_grupoModel::find($idGrupo);
	        	if ($miGrupo->id_cat_sta == 3 ) {//APROBADO
                    $perfiles =pdg_per_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
                    $perfilAprobado=0;
                    foreach ($perfiles as $perfil) {
                        if ($perfil->id_cat_sta == 9) {
                            $perfilAprobado+=1;
                        }
                    }
                   /* if ($prePerfilAprobado>0) {
                        //AL MENOS UN PREPERFIL ENVIADO YA FUE APROBADO POR COORDINADOR DE TRABAJO DE GRADUACION
                        Session::flash('message-error', 'No puedes crear nuevos Pre-Perfiles, uno de los Pre-Perfiles enviados por tu grupo de trabajo de graduación ya ha sido aprobado!');

                        return  view('template');
                    }*/ //else{

                       // return  view('TrabajoGraduacion.PrePerfil.index');
                    //}else{
                        $tiposTrabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::pluck('nombre_cat_tpo_tra_gra', 'id_Cat_tpo_tra_gra')->toArray();
                        $catTrabajos =  cat_ctg_tra_categoria_trabajo_graduacionModel::pluck('nombre_cat_ctg_tra', 'id_cat_ctg_tra')->toArray();

                        return view('TrabajoGraduacion.Perfil.create',compact('tiposTrabajos','catTrabajos'));
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
        $validatedData = $request->validate([
                    'tema_pdg_per' => 'required|max:200',
                    'id_cat_tpo_tra_gra' => 'required',
                    'documento' => 'required',
                    'resumen' => 'required',
                    'boleta' => 'required'
                    
                ],
            [
                'tema_pdg_per.required' => 'Debe ingresar un tema de perfil de  trabajo de graduación.',
                'tema_pdg_per.max' => 'El tema de perfil de trabajo de graduación debe contener como maximo 200 caracteres',
                'id_cat_tpo_tra_gra.required' => 'Debe seleccionar un tipo de trabajo de graduación.',
                'documento.required' => 'Debe subir un documento de Perfil.',
                'resumen.required' => 'Debe subir un documento resumen de Perfil.',
                'boleta.required' => 'Debe subir un documento con los comprobantes de inscripción de los integrantes del grupo de la materia Trabajo de graduación..'
            ]);
        try {
                
               $userLogin=Auth::user();
               $estudiante = new gen_EstudianteModel();
               //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
               $idGrupo = session('idGrupo');
               $grupo = pdg_gru_grupoModel::find($idGrupo);
               $anioGrupo = $grupo->anio_pdg_gru;
               $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
               //obtenemos el campo file definido en el formulario
                $file = $request->file('documento');
                $resumen = $request->file('resumen');
                $boleta = $request->file('boleta');
               //obtenemos el nombre del archivo
                $nombre = 'Grupo'.$numeroGrupo."_".$anioGrupo."_".date('his').$file->getClientOriginalName();
                $nombreResumen = 'Resumen_Grupo'.$numeroGrupo."_".$anioGrupo."_".date('his').$resumen->getClientOriginalName();
                $nombreBoleta = 'Inscripcion'.$numeroGrupo."_".$anioGrupo."_".date('his').$boleta->getClientOriginalName();
                Storage::disk('Uploads')->put($nombre, File::get($file));
                Storage::disk('Uploads')->put($nombreResumen, File::get($resumen));
                Storage::disk('Uploads')->put($nombreBoleta, File::get($boleta));
                 //movemos el archivo a la ubicación correspondiente segun grupo y años
                if ($_ENV['SERVER'] =="win") {
                        $nuevaUbicacion=trim($anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ').$nombre;
                        $nuevaUbicacionResumen=trim($anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ').$nombreResumen;
                        $nuevaUbicacionBoleta=trim($anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ').$nombreBoleta;
                     }else{
                        $nuevaUbicacion=$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/'.$nombre;
                        $nuevaUbicacionResumen=$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/'.$nombreResumen;
                        $nuevaUbicacionBoleta=$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/'.$nombreBoleta;
                     }
                    
                Storage::disk('Uploads')->move($nombre, $nuevaUbicacion);
                Storage::disk('Uploads')->move($nombreResumen, $nuevaUbicacionResumen);
                 Storage::disk('Uploads')->move($nombreBoleta, $nuevaUbicacionBoleta);
                $fecha=date('Y-m-d H:i:s');
                //$path= public_path()."\Uploads\PrePerfil\ ";
                 $path= public_path().$_ENV['PATH_UPLOADS'];
                //cambiamos a 0 el documento que esta activo actualmente de ese tipo
                   $ultimoDocumentoInsertado = pdg_doc_documentoModel::where('id_cat_tpo_doc', 6) // 6 ES PERFIL
                                        ->where('id_pdg_gru', $idGrupo)
                                        ->orderBy('id_pdg_doc', 'desc')
                                        ->first();
                    if (!empty($ultimoDocumentoInsertado)) {
                        $archivo = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertado->id_pdg_doc)
                                   ->where('activo','1')
                                   ->first();
                        if (!empty($archivo)) {
                            $archivo->activo = 0;
                            $archivo->save();
                        }
                    }
                    $ultimoDocumentoInsertado = pdg_doc_documentoModel::where('id_cat_tpo_doc', 7) // RESUMEN
                                        ->where('id_pdg_gru', $idGrupo)
                                        ->orderBy('id_pdg_doc', 'desc')
                                        ->first();
                    if (!empty($ultimoDocumentoInsertado)) {
                        $archivo = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertado->id_pdg_doc)
                                   ->where('activo','1')
                                   ->first();
                        if (!empty($archivo)) {
                            $archivo->activo = 0;
                            $archivo->save();
                        }
                    }            
                   $lastId = pdg_per_perfilModel::create
                    ([
                        'tema_pdg_per'                   => $request['tema_pdg_per'],
                        'id_pdg_gru'                     => $idGrupo,
                        'id_cat_sta'                     => 7,
                        'id_cat_tpo_tra_gra'             => $request['id_cat_tpo_tra_gra'],
                        'id_cat_ctg_tra'                 => $request['id_cat_ctg_tra'],
                        'fecha_creacion_per'             => $fecha

                    ]); 

                    $lastIdDocumento = pdg_doc_documentoModel::create
                    ([
                        'id_pdg_gru'                     => $idGrupo,
                        'id_cat_tpo_doc'                 => 6, // 6 PERFIL
                        'fecha_creacion_pdg_doc'         => $fecha
                    ]); 

                    $lastIdArchivo = pdg_arc_doc_archivo_documentoModel::create
                    ([
                        'id_pdg_doc'                     => $lastIdDocumento->id_pdg_doc,
                        'ubicacion_arc_doc'              => $nombre,
                        'fecha_subida_arc_doc'           => $fecha,
                        'nombre_arc_doc'                 => $file->getClientOriginalName(),
                        'activo'                         => 1
                    ]);

                     $lastIdDocumentoResumen = pdg_doc_documentoModel::create
                    ([
                        'id_pdg_gru'                     => $idGrupo,
                        'id_cat_tpo_doc'                 => 7, // 7 resumen PERFIL
                        'fecha_creacion_pdg_doc'         => $fecha
                    ]); 

                    $lastIdArchivoResumen = pdg_arc_doc_archivo_documentoModel::create
                    ([
                        'id_pdg_doc'                     => $lastIdDocumentoResumen->id_pdg_doc,
                        'ubicacion_arc_doc'              => $nombreResumen,
                        'fecha_subida_arc_doc'           => $fecha,
                        'nombre_arc_doc'                 => $resumen->getClientOriginalName(),
                        'activo'                         => 1
                    ]);

                     $lastIdDocumentoBoleta = pdg_doc_documentoModel::create
                    ([
                        'id_pdg_gru'                     => $idGrupo,
                        'id_cat_tpo_doc'                 => 26, // Boleta inscripción
                        'fecha_creacion_pdg_doc'         => $fecha
                    ]); 

                    $lastIdArchivoBoleta = pdg_arc_doc_archivo_documentoModel::create
                    ([
                        'id_pdg_doc'                     => $lastIdDocumentoBoleta->id_pdg_doc,
                        'ubicacion_arc_doc'              => $nombreBoleta,
                        'fecha_subida_arc_doc'           => $fecha,
                        'nombre_arc_doc'                 => $boleta->getClientOriginalName(),
                        'activo'                         => 1
                    ]);
                    Session::flash('message','Perfil Registrado correctamente!');
                    return Redirect::to('perfil');
        } catch (\Exception $e) {
             Session::flash('error','Ocurrió un problema al momento de guardar el Perfil');
            return Redirect::to('perfil');
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
                   $perfil=pdg_per_perfilModel::find($id);
                   if (empty($perfil->id_pdg_per)){ //PERFIL NO EXISTE
                     return redirect('/');
                   }else if ($perfil->id_pdg_gru != $idGrupo) { //PERFIL NO LE PERTENECE AL GRUPO QUE QUIERE EDITARLO
                     return redirect('/');
                   }
                   else if ($perfil->id_cat_sta == 9){//aprobado
                        Session::flash('message-error','No puedes modificar un Perfil una vez ha sido aprobado!');
                        return Redirect::to('perfil');
                   }elseif ($perfil->id_cat_sta == 11) { //RECHAZADO
                        Session::flash('message-error','No puedes modificar un Pre-Perfil una vez ha sido rechazado!');
                        return Redirect::to('perfil');
                   }else{
                     $tiposTrabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::pluck('nombre_cat_tpo_tra_gra', 'id_Cat_tpo_tra_gra')->toArray();
                     $catTrabajos =  cat_ctg_tra_categoria_trabajo_graduacionModel::pluck('nombre_cat_ctg_tra', 'id_cat_ctg_tra')->toArray();
                    return view('TrabajoGraduacion.Perfil.edit',compact('tiposTrabajos','catTrabajos','perfil'));
                   }
                  
                }else{
                    Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
                    return  view('template');
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
    public function update(Request $request, $id){
        $validatedData = $request->validate([
                        'tema_pdg_per' => 'required|max:200',
                        'id_cat_tpo_tra_gra' => 'required',
                 ],
            [
                'tema_pdg_per.required' => 'Debe ingresar un tema de perfil de  trabajo de graduación.',
                'tema_pdg_per.max' => 'El tema de perfil de trabajo de graduación debe contener como maximo 200 caracteres',
                'id_cat_tpo_tra_gra.required' => 'Debe seleccionar un tipo de trabajo de graduación.'
            ]);
        try {
                $userLogin=Auth::user();
                
                $file = $request->file('documento');
                $resumen = $request->file('resumen');
                $boleta = $request->file('boleta');
                $perfil=pdg_per_perfilModel::find($id);
                $perfil->tema_pdg_per = $request['tema_pdg_per'];
                $perfil->id_cat_tpo_tra_gra = $request['id_cat_tpo_tra_gra'];
                $perfil->id_cat_ctg_tra = $request['id_cat_ctg_tra'];
                $estudiante = new gen_EstudianteModel();
                //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
                $idGrupo = session('idGrupo');
                $grupo = pdg_gru_grupoModel::find($idGrupo);
                $anioGrupo = $grupo->anio_pdg_gru;
                $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
               if (!empty($file)) {
                    $ultimoDocumentoInsertado = pdg_doc_documentoModel::where('id_cat_tpo_doc', 6) // 6 ES PERFIL
                                        ->where('id_pdg_gru', $idGrupo)
                                        ->orderBy('id_pdg_doc', 'desc')
                                        ->first();
                
                    $archivo = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertado->id_pdg_doc)
                                                                  ->first();
                    $nombreViejo = $archivo->ubicacion_arc_doc;
                    if ($_ENV['SERVER'] =="win") {
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ';
                    }else{
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/';
                    }
                    //obtenemos el nombre del archivo
                    $nombre = 'Grupo'.$numeroGrupo."_".$anioGrupo."_".date('his').$file->getClientOriginalName();
                    Storage::disk('Uploads')->put($nombre, File::get($file));
                     //movemos el archivo a la ubicación correspondiente segun grupo y años
                    if ($_ENV['SERVER'] =="win") {
                        $nuevaUbicacion=trim($anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ').$nombre;
                     }else{
                        $nuevaUbicacion=$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/'.$nombre;
                     }
                    
                    Storage::disk('Uploads')->move($nombre, $nuevaUbicacion);
                    $fecha=date('Y-m-d H:i:s');
                    $archivo->nombre_arc_doc = $file->getClientOriginalName();
                    $archivo->ubicacion_arc_doc = $nombre; //SOLO SE GUARDA NOMBRE AHORA
                    $archivo->fecha_subida_arc_doc = $fecha;
                    if (File::exists(trim($path).$nombreViejo)){
                            File::delete(trim($path).$nombreViejo);    
                    }
                    $archivo->save();
               }
               if (!empty($resumen)) {
                    $ultimoDocumentoInsertado = pdg_doc_documentoModel::where('id_cat_tpo_doc', 7) // 6 Resumen
                                        ->where('id_pdg_gru', $idGrupo)
                                        ->orderBy('id_pdg_doc', 'desc')
                                        ->first();
                
                    $archivo = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertado->id_pdg_doc)
                                                                  ->first();
                    $nombreViejo = $archivo->ubicacion_arc_doc;
                    if ($_ENV['SERVER'] =="win") {
                        $path= trim(public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ');
                    }else{
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/';
                    }
                    //obtenemos el nombre del archivo
                    $nombreResumen = 'Resumen_Grupo'.$numeroGrupo."_".$anioGrupo."_".date('his').$resumen->getClientOriginalName();
                    Storage::disk('Uploads')->put($nombreResumen, File::get($resumen));
                     //movemos el archivo a la ubicación correspondiente segun grupo y años
                   if ($_ENV['SERVER'] =="win") {
                        $nuevaUbicacionResumen=trim($anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ').$nombreResumen;
                     }else{
                        $nuevaUbicacionResumen=$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/'.$nombreResumen;
                     }
                    
                    Storage::disk('Uploads')->move($nombreResumen, $nuevaUbicacionResumen);
                    $fecha=date('Y-m-d H:i:s');
                    $archivo->nombre_arc_doc = $resumen->getClientOriginalName();
                    $archivo->ubicacion_arc_doc = $nombreResumen; //SOLO SE GUARDA NOMBRE AHORA
                    $archivo->fecha_subida_arc_doc = $fecha;
                    if (File::exists(trim($path).$nombreViejo)){
                            File::delete(trim($path).$nombreViejo);    
                    }
                    $archivo->save();
               }

               if (!empty($boleta)) {
                    $ultimoDocumentoInsertado = pdg_doc_documentoModel::where('id_cat_tpo_doc', 26) // 6 Resumen
                                        ->where('id_pdg_gru', $idGrupo)
                                        ->orderBy('id_pdg_doc', 'desc')
                                        ->first();
                
                    $archivo = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertado->id_pdg_doc)
                                                                  ->first();
                    $nombreViejo = $archivo->ubicacion_arc_doc;
                    if ($_ENV['SERVER'] =="win") {
                        $path= trim(public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ');
                    }else{
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/';
                    }
                    //obtenemos el nombre del archivo
                    $nombreBoleta = 'Inscripcion'.$numeroGrupo."_".$anioGrupo."_".date('his').$boleta->getClientOriginalName();
                    Storage::disk('Uploads')->put($nombreBoleta, File::get($boleta));
                     //movemos el archivo a la ubicación correspondiente segun grupo y años
                   if ($_ENV['SERVER'] =="win") {
                        $nuevaUbicacionBoleta=trim($anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ').$nombreBoleta;
                     }else{
                        $nuevaUbicacionBoleta=$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/'.$nombreBoleta;
                     }
                    
                    Storage::disk('Uploads')->move($nombreBoleta, $nuevaUbicacionBoleta);
                    $fecha=date('Y-m-d H:i:s');
                    $archivo->nombre_arc_doc = $boleta->getClientOriginalName();
                    $archivo->ubicacion_arc_doc = $nombreBoleta; //SOLO SE GUARDA NOMBRE AHORA
                    $archivo->fecha_subida_arc_doc = $fecha;
                    if (File::exists(trim($path).$nombreViejo)){
                            File::delete(trim($path).$nombreViejo);    
                    }
                    $archivo->save();
               }
               $perfil->save();
               Session::flash('message','Perfil Modificado correctamente!');
               return Redirect::to('perfil');
        } catch (\Exception $e) {
            Session::flash('error','Ocurrió un problema al momento de actualizar el Perfil');
            return Redirect::to('perfil');
        }
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        try {
            
                $userLogin=Auth::user();
                $perfil=pdg_per_perfilModel::find($id);
                if ($perfil->id_cat_sta == 9){//aprobado
                    Session::flash('message-error','No puedes eliminar un Perfil una vez ha sido aprobado!');
                    return Redirect::to('perfil');
                }
                $estudiante = new gen_EstudianteModel();
                //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
                $idGrupo = session('idGrupo');
                $grupo = pdg_gru_grupoModel::find($idGrupo);
                $anioGrupo = $grupo->anio_pdg_gru;
                $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
                $ultimoDocumentoInsertado = pdg_doc_documentoModel::where('id_cat_tpo_doc', 6) // 6 ES PERFIL
                                        ->where('id_pdg_gru', $idGrupo)
                                        ->orderBy('id_pdg_doc', 'desc')
                                        ->first();
                
                $archivo = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertado->id_pdg_doc)->first();
                $ultimoDocumentoInsertadoResumen = pdg_doc_documentoModel::where('id_cat_tpo_doc', 7) // 7 Es Resumen
                                        ->where('id_pdg_gru', $idGrupo)
                                        ->orderBy('id_pdg_doc', 'desc')
                                        ->first();
                
                $archivoResumen = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertadoResumen->id_pdg_doc)->first();

                $ultimoDocumentoInsertadoBoleta = pdg_doc_documentoModel::where('id_cat_tpo_doc', 26) // 7 Es Boleta
                                        ->where('id_pdg_gru', $idGrupo)
                                        ->orderBy('id_pdg_doc', 'desc')
                                        ->first();
                
                $archivoBoleta = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertadoBoleta->id_pdg_doc)->first();
                                                                  
                $nombreViejo = $archivo->ubicacion_arc_doc;
                $nombreViejoResumen = $archivoResumen->ubicacion_arc_doc;
                $nombreViejoBoleta = $archivoBoleta->ubicacion_arc_doc;
                if ($_ENV['SERVER'] =="win") {
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ';
                }else{
                        $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/';
                }
                if ($userLogin->can(['prePerfil.destroy'])) {
                    if (File::exists(trim($path).$nombreViejo)){
                            File::delete(trim($path).$nombreViejo);    
                    }
                    if (File::exists(trim($path).$nombreViejoResumen)){
                            File::delete(trim($path).$nombreViejoResumen);    
                    }
                    if (File::exists(trim($path).$nombreViejoBoleta)){
                            File::delete(trim($path).$nombreViejoBoleta);    
                    }
                    pdg_per_perfilModel::destroy($id);
                    pdg_arc_doc_archivo_documentoModel::destroy($archivo->id_pdg_arc_doc);
                    pdg_arc_doc_archivo_documentoModel::destroy($archivoResumen->id_pdg_arc_doc);
                     pdg_arc_doc_archivo_documentoModel::destroy($archivoBoleta->id_pdg_arc_doc);
                    pdg_doc_documentoModel::destroy($ultimoDocumentoInsertado->id_pdg_doc);
                    pdg_doc_documentoModel::destroy($ultimoDocumentoInsertadoResumen->id_pdg_doc);
                    pdg_doc_documentoModel::destroy($ultimoDocumentoInsertadoBoleta->id_pdg_doc);
                    Session::flash('message','Perfil Eliminado Correctamente!');
                    return Redirect::to('/perfil');
                }else{
                    Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
                    return  redirect('/');
                }
        } catch (\Exception $e) {
            Session::flash('error','Ocurrió un error al eliminar el perfil');
            return Redirect::to('/perfil');
        }
 
        
    } 

    public function verificarGrupo($carnet) {
	    $estudiante = new gen_EstudianteModel();
	    $respuesta = $estudiante->getGrupoCarnet($carnet);
	    return $respuesta;     
    }
    public function aprobarPerfil(Request $request) {
        $perfil =pdg_per_perfilModel::find($request['idPerfil']);
        $msgType = "error";
        if(!empty($perfil->id_cat_sta)){
            if($perfil->id_cat_sta==7){
                $tragra = self::saveTDG($perfil);
                if($tragra==null){
                    $msg = 'Ocurrió un error al intentar aprobar el perfil.\nSolicite al grupo subir el documento nuevamente para aprobarlo';
                } else {
                    $perfil->id_cat_sta = 9 ;//APROBADO
                    $perfil->save();
                    $msgType = "message";
                    $msg = '¡Perfil Aprobado Correctamente!\nAdicionalmente se crearon las etapas del Trabajo de Graduación, el grupo puede empezar a subir archivos.';
                }
            } else {
                $msg = "¡Error! Perfil ya fue aprobado.";
            }
        }else{
            Session::flash('error',"¡Error! Ese perfil ya no existe, elimínelo y solicite al grupo que lo suba nuevamente.");
            return Redirect::back();
        }
        Session::flash($msgType,$msg);
        //return Redirect::to('/indexPerfil/'.$perfil->id_pdg_gru);
        return Redirect::to('/perfil');
    }
    private function saveTDG($perfil){
        $tragra = pdg_tra_gra_trabajo_graduacionModel::createOrUpdateTDG($perfil);
        return $tragra;
    }
    public function rechazarPerfil(Request $request) {
	    $perfil =pdg_per_perfilModel::find($request['idPerfil']);
	    $perfil->id_cat_sta = 11 ;//RECHAZADO
	    $perfil->save();
	    Session::flash('message','Perfil Rechazado Correctamente!');
        return Redirect::to('/perfil');
            //return Redirect::to('/indexPerfil/'.$perfil->id_pdg_gru); 
    }
    function downloadPerfil(Request $request){
        //$userLogin=Auth::user();
        $id = $request['archivo'];
        $idGrupo=$request['grupo'];
        //$estudiante = new gen_EstudianteModel();
        //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
//        $idGrupo = session('idGrupo');
        $grupo = pdg_gru_grupoModel::find($idGrupo);
        $anioGrupo = $grupo->anio_pdg_gru;
        $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
        $ultimoDocumentoInsertado = pdg_doc_documentoModel::where('id_cat_tpo_doc', 6) // 6 ES PERFIL
                                ->where('id_pdg_gru', $idGrupo)
                                ->orderBy('id_pdg_doc', 'desc')
                                ->first();
        
         $archivo = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertado->id_pdg_doc)->first();                                                 
        $nombreViejo = $archivo->ubicacion_arc_doc;
        if ($_ENV['SERVER'] =="win") {
                $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ';
        }else{
                $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/';
        }
    	//$path= public_path().$_ENV['PATH_PREPERFIL'];
    	//verificamos si el archivo existe y lo retornamos
     	if (File::exists(trim($path).$nombreViejo)){
      	  return response()->download(trim($path).$nombreViejo);
     	}else{
     		Session::flash('error','El archivo no se encuentra disponible , es posible que fue borrado');
             return redirect()->route('prePerfil.index');
     	}
    }

    function downloadPerfilResumen(Request $request){
        //$userLogin=Auth::user();
        $id = $request['archivo'];
        //$estudiante = new gen_EstudianteModel();
        //$idGrupo = $estudiante->getIdGrupo($userLogin->user);
        //$idGrupo=$request['grupo'];
        $idGrupo = session('idGrupo');
        $grupo = pdg_gru_grupoModel::find($idGrupo);
        $anioGrupo = $grupo->anio_pdg_gru;
        $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
         $ultimoDocumentoInsertadoResumen = pdg_doc_documentoModel::where('id_cat_tpo_doc', 7) // 7 Es Resumen
                                ->where('id_pdg_gru', $idGrupo)
                                ->orderBy('id_pdg_doc', 'desc')
                                ->first();
        
        $archivoResumen = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertadoResumen->id_pdg_doc)->first();
        $nombreViejoResumen = $archivoResumen->ubicacion_arc_doc;
        if ($_ENV['SERVER'] =="win") {
                $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ';
        }else{
                $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/';
        }
        //$path= public_path().$_ENV['PATH_PREPERFIL'];
        //verificamos si el archivo existe y lo retornamos
        if (File::exists(trim($path).$nombreViejoResumen)){
          return response()->download(trim($path).$nombreViejoResumen);
        }else{
            Session::flash('error','El archivo no se encuentra disponible , es posible que fue borrado');
             return redirect()->route('prePerfil.index');
        }
    }

    function downloadPerfilBoleta(Request $request){
        $id = $request['archivo'];
        $idGrupo = session('idGrupo');
        $grupo = pdg_gru_grupoModel::find($idGrupo);
        $anioGrupo = $grupo->anio_pdg_gru;
        $numeroGrupo = $grupo->correlativo_pdg_gru_gru;
         $ultimoDocumentoInsertadoBoleta = pdg_doc_documentoModel::where('id_cat_tpo_doc', 26) 
                                ->where('id_pdg_gru', $idGrupo)
                                ->orderBy('id_pdg_doc', 'desc')
                                ->first();
        
        $archivoBoleta = pdg_arc_doc_archivo_documentoModel::where('id_pdg_doc', $ultimoDocumentoInsertadoBoleta->id_pdg_doc)->first();
        $nombreViejoBoleta = $archivoBoleta->ubicacion_arc_doc;
        if ($_ENV['SERVER'] =="win") {
                $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'\Grupo'.$numeroGrupo.'\Perfil\ ';
        }else{
                $path= public_path().$_ENV['PATH_UPLOADS'].$anioGrupo.'/Grupo'.$numeroGrupo.'/Perfil/';
        }
    
        //verificamos si el archivo existe y lo retornamos
        if (File::exists(trim($path).$nombreViejoBoleta)){
          return response()->download(trim($path).$nombreViejoBoleta);
        }else{
            Session::flash('error','El archivo no se encuentra disponible , es posible que fue borrado');
             return redirect()->route('prePerfil.index');
        }
    }
}
