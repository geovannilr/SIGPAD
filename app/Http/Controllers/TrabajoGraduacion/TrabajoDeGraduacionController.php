<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use App\pdg_tra_gra_trabajo_graduacionModel;
use App\pdg_tri_gru_tribunal_grupoModel;
use  Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use \App\pdg_ppe_pre_perfilModel;
use \App\gen_EstudianteModel;
use \App\pdg_gru_grupoModel;
use \App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
use \App\pdg_gru_est_grupo_estudianteModel;
use \App\pdg_dcn_docenteModel;
use \App\gen_int_integracionModel;
use \App\pub_publicacionModel;
use \App\pub_col_colaboradorModel;
use \App\rel_col_pub_colaborador_publicacionModel;
use \App\pub_aut_publicacion_autorModel;
use \App\pub_arc_publicacion_archivoModel;
use \App\pdg_apr_eta_tra_aprobador_etapa_trabajoModel;

use Zend\Ldap\Ldap;

class TrabajoDeGraduacionController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

   	public function index(){
        $userLogin=Auth::user();
        if ($userLogin->can(['prePerfil.index'])) {
            if (Auth::user()->isRole('administrador_tdg')){
                 $prePerfil = new  pdg_ppe_pre_perfilModel();
                 $gruposPrePerfil=$prePerfil->getGruposPrePerfil();
                return view('TrabajoGraduacion.PrePerfil.indexPrePerfil',compact('gruposPrePerfil'));
            }elseif (Auth::user()->isRole('estudiante')) {
                $estudiante = new gen_EstudianteModel();
                $idGrupo = $estudiante->getIdGrupo($userLogin->user);
                if ($idGrupo != 'NA'){
                    $grupo=self::verificarGrupo($userLogin->user)->getData();
                    $estudiantes=json_decode($grupo->msg->estudiantes);
                    $miGrupo = pdg_gru_grupoModel::find($idGrupo);
                    if ($miGrupo->id_cat_sta == 3 ) {//APROBADO
                        $prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
                        $numero=$miGrupo->numero_pdg_gru;
                        $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($idGrupo);
                        if(empty($tribunal)){
                            $tribunal="NA";
                        }
                        $etapas=self::getEtapasEvaluativas($idGrupo);
                        if (sizeof($etapas) == 0){
                        	$etapas="NA";
                        }
                        $traGra = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->first();
                        if (empty($traGra->tema_pdg_tra_gra)) {
                            Session::flash('message-error', 'tu grupo no tiene un perfil aprobado de trabajo de graduacion');
                            return redirect("/");
                        }else{
                           $tema = $traGra->tema_pdg_tra_gra; 
                        }
                        

                        $avance = self::getAvanceGrupo($traGra->id_pdg_tra_gra);
                        $actual = self::getIdEtapaActual($traGra->id_pdg_tra_gra);

                        return view('TrabajoGraduacion.TrabajoDeGraduacion.index',compact('numero','estudiantes','tribunal','etapas','tema','idGrupo','avance','actual'));
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
            }elseif (Auth::user()->isRole('docente_asesor')) {
                return "DOCENTE ASESOR";
            }
        }
        return redirect("/");
    }
    /*Listado de grupos filtrados por docente asesor*/
    public function dashboardIndex(){
        // Retrieve a piece of data from the session...
        //$grupos = session('misGrupos');
        //return var_dump($grupos);
        $userLogin=Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        //return $docente->id_pdg_dcn;
        $grupo = new pdg_gru_grupoModel();
        $grupos = $grupo->getGruposDocente($docente->id_pdg_dcn);
        return view('TrabajoGraduacion.Dashboard.Grupos.index',compact('grupos'));
        //CONSUMIMOS EL SP DE LISTADO DE GRUPOS POR DOCENTE
    }
    public function dashboardGrupo($idGrupo){
        $userLogin=Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        //SE DEBE VERIFICAR QUE EL GRUPO PERTENECE AL DOCENTE select id_pdg_gru from pdg_tri_gru_tribunal_grupo where id_pdg_dcn=idDocente
        $grupo= new pdg_gru_grupoModel();
        $estudiantesGrupo = $grupo->getDetalleGrupo($idGrupo);
        //$prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
        $grupo = pdg_gru_grupoModel::find($idGrupo);
        //return var_dump($grupo);
        if (empty($grupo->id_pdg_gru)) {
            return redirect("/");
        }
        $numero=$grupo->numero_pdg_gru;
        $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($idGrupo);
        if(empty($tribunal)){
            $tribunal="NA";
        }
        $etapas=self::getEtapasEvaluativas($idGrupo);

        if (empty($etapas)){
            $etapas="NA";
        }
        $traGra = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->first();
        $tema = $traGra->tema_pdg_tra_gra;
        $avance = self::getAvanceGrupo($traGra->id_pdg_tra_gra);
        $actual = self::getIdEtapaActual($traGra->id_pdg_tra_gra);

        if(!empty($tema)){
            return view('TrabajoGraduacion.TrabajoDeGraduacion.index',compact('numero','estudiantesGrupo','tribunal','etapas','tema','idGrupo','avance','actual'));
        }else{
            //Session::flash('message-error', 'El grupo seleccionado aún no ha empezado su proceso de trabajo de graduación');
            //return redirect()->route('listadoGrupos');
            return redirect("/");
        }


    }

    public function getIdEtapaActual($idTraGra){
        $etapaActual = pdg_apr_eta_tra_aprobador_etapa_trabajoModel::getEtapa($idTraGra,pdg_apr_eta_tra_aprobador_etapa_trabajoModel::T_BUSQ_ACTUAL);
        $actual = empty($etapaActual->id_cat_eta_eva) ? 0 : $etapaActual->id_cat_eta_eva;
        return $actual;
    }

    public function getAvanceGrupo($idTraGra){
        $aprobadas = pdg_apr_eta_tra_aprobador_etapa_trabajoModel::contarEtapas($idTraGra,pdg_apr_eta_tra_aprobador_etapa_trabajoModel::T_CONTEO_APROBADAS);
        $originales = pdg_apr_eta_tra_aprobador_etapa_trabajoModel::contarEtapas($idTraGra,pdg_apr_eta_tra_aprobador_etapa_trabajoModel::T_CONTEO_ORIGINALES);
        $avanceRaw = round($originales==0?0:100*$aprobadas/$originales);
        $avance = number_format((float)$avanceRaw, 2, '.', '');
        return $avance;
    }

    public function verificarGrupo($carnet) {
	    $estudiante = new gen_EstudianteModel();
	    $respuesta = $estudiante->getGrupoCarnet($carnet);
	    return $respuesta;
    }

    public function getEtapasEvaluativas($idGrupo) {
	    $grupo = new pdg_gru_grupoModel();
	    $respuesta = $grupo->getEtapas($idGrupo);
	    return $respuesta;
    }
    public function createCierreGrupo(){

        $userLogin=Auth::user();
        if (Auth::user()->isRole('estudiante')) {
                $estudiante = new gen_EstudianteModel();
                $idGrupo = $estudiante->getIdGrupo($userLogin->user);
        }else{
            return redirect("/");
        }
        $grupo = pdg_gru_grupoModel::find($idGrupo);
        $trabajoGraduacion = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->first();

        if(empty($trabajoGraduacion->id_pdg_tra_gra)){
            return redirect("/");
        }
        //VERIFICAMOS QUE PUEDE QUE LAS ETAPAS YA ESTAN CERRADAS Y PUEDE HACER EL CIERRE DE TRABAJO DE GRADUACION
        $verificar=pdg_apr_eta_tra_aprobador_etapa_trabajoModel::where("id_pdg_tra_gra","=",$trabajoGraduacion->id_pdg_tra_gra)->where("inicio","=","1")->where("aprobo","=","0")->first();
        if (empty($verificar->id_pdg_apr_eta_tra)) {
             Session::flash('message-warning', 'No puedes realizar el cierre de trabajo de graduación hasta que hayas aprobado todas las etapas evaluativas, consulta con tu docente asesor.');
             return redirect("/dashboard");
        }
         if(empty($trabajoGraduacion->tema_pdg_tra_gra)){
                $tema="NA";
        }else{
            $tema = $trabajoGraduacion->tema_pdg_tra_gra;
        }

        if (empty($grupo->id_pdg_gru)) {
            return redirect("/");
        }else{
            $numero=$grupo->numero_pdg_gru;
            $grupo= new pdg_gru_grupoModel();
            $estudiantesGrupo = $grupo->getDetalleGrupo($idGrupo);
            $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($idGrupo);

            if(sizeof($tribunal) == 0){
                $tribunal="NA";
            }

            return view('TrabajoGraduacion.TrabajoDeGraduacion.Cierre.create',compact('numero','estudiantesGrupo','tribunal','tema','idGrupo'));
        }

    }

    public function storeCierreGrupo(Request $request){
        if (!Auth::user()->isRole('estudiante')) {
            Session::flash('message-error', 'No puede acceder a esta opción.');
            return redirect('/');
        }
        $validatedData = $request->validate([
            'resumen' => 'required',
            'tomoFinal' => 'required',
            'idGrupo' =>'required'
        ]);
        $idGrupo = $request['idGrupo'];
        $trabajoGraduacion = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->first();

        if(empty($trabajoGraduacion->id_pdg_tra_gra)){
            return redirect("/");
        }
        //VERIFICAMOS QUE PUEDE QUE LAS ETAPAS YA ESTAN CERRADAS Y PUEDE HACER EL CIERRE DE TRABAJO DE GRADUACION
        $verificar=pdg_apr_eta_tra_aprobador_etapa_trabajoModel::where("id_pdg_tra_gra","=",$trabajoGraduacion->id_pdg_tra_gra)->where("inicio","=","1")->where("aprobo","=","0")->first();
        if (empty($verificar->id_pdg_apr_eta_tra)) {
             Session::flash('message-warning', 'No pueces realizar el cierre de trabajo de graduación hasta que hayas aprobado todas las etapas evaluativas, consulta con tu docente asesor.');
             return redirect("/dashboard");
        }
        $grupo = pdg_gru_grupoModel::find($idGrupo);
        $tema = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->select('tema_pdg_tra_gra')->first();
        if (empty($grupo->id_pdg_gru)) {
           return redirect("/");
        }else{
            $numero=$grupo->numero_pdg_gru;
            $grupo= new pdg_gru_grupoModel();
            $estudiantesGrupo = $grupo->getDetalleGrupo($idGrupo);

            $tribunalEvaluador = pdg_tri_gru_tribunal_grupoModel::getTribunalData($idGrupo);
            //INGRESAMOS LA PUBLICACION JUNTO CON SU LLAVE DE INTEGRACION
            $ultimaCorrPublicacionAnho=pub_publicacionModel::where("anio_pub","=",date('Y'))->orderBy('correlativo_pub', 'desc')->first();
            $correlativo=0;
            $codigo = "0";
            if (empty($ultimaCorrPublicacionAnho->correlativo_pub)) {
                 $correlativo =1;
            }else{
                 $correlativo = $ultimaCorrPublicacionAnho->correlativo_pub+1;
            }
            if ($correlativo >= 1 && $correlativo <= 9) {
                $codigo =  date('Y').'0'.$correlativo;
            }else{
               $codigo =  date('Y').$correlativo;
            }

            // TIPO 1 - GRUPO
               $lastIdIntegracion = gen_int_integracionModel::create
                ([
                    'id_gen_tpo_int' => 1,
                    'llave_gen_int' => $idGrupo

                ]);

                $lastIdPublicacion = pub_publicacionModel::create
                ([
                    'id_cat_tpo_pub' => 1, //TIPO TDG
                    'id_gen_int' => $lastIdIntegracion->id_gen_int,
                    'titulo_pub' => $tema->tema_pdg_tra_gra,
                    'anio_pub' => date('Y'),
                    'correlativo_pub' => $correlativo,
                    'codigo_pub' => $codigo,
                    'resumen_pub' => $request['resumen']
                ]);

            //INGRESAMOS LOS COLABORADORES JUNTO CON SU LLAVE DE INTEGRACION
            foreach ($tribunalEvaluador as $tribunal) { // TIPO 2 - DOCENTES
                //VERIFICAMOS SI EL DOCENTE YA ESTA INGRESADO
                $llaveIntegracion = gen_int_integracionModel::where("llave_gen_int","=",$tribunal->id_pdg_dcn)->where("id_gen_tpo_int","=",2)->first();
                if (empty($llaveIntegracion->id_gen_int)) {
                    $lastIdIntegracion = gen_int_integracionModel::create
                    ([
                        'id_gen_tpo_int' => 2,
                        'llave_gen_int' => $tribunal->id_pdg_dcn

                    ]);
                    $lastIdColaborador = pub_col_colaboradorModel::create
                    ([
                        'id_gen_int' => $lastIdIntegracion->id_gen_int,
                        'nombres_pub_col' => $tribunal->name,
                        'apellidos_pub_col' => ""
                    ]);

                    if ($tribunal->id_pdg_tri_rol == 1) {
                        $categoriaColaborador = 2 ;//ASESOR
                    }else{
                        $categoriaColaborador = 4; // OBSERVADOR
                    }
                    $lastIdRelacion = rel_col_pub_colaborador_publicacionModel::create
                    ([
                        'id_pub' => $lastIdPublicacion->id_pub,
                        'id_pub_col' => $lastIdColaborador->id_pub_col,
                        'id_cat_tpo_col_pub' => $categoriaColaborador
                    ]);

                }else{

                        if ($tribunal->id_pdg_tri_rol == 1) {
                            $categoriaColaborador = 2 ;//ASESOR
                        }else{
                            $categoriaColaborador = 4; // OBSERVADOR
                        }
                        $colaborador=pub_col_colaboradorModel::where("id_gen_int","=",$llaveIntegracion->id_gen_int)->first();
                        if (!empty($colaborador->id_pub_col)) {
                            $lastIdRelacion = rel_col_pub_colaborador_publicacionModel::create
                            ([
                                'id_pub' => $lastIdPublicacion->id_pub,
                                'id_pub_col' => $colaborador->id_pub_col,
                                'id_cat_tpo_col_pub' => $categoriaColaborador
                            ]);
                        }

                }

            }

             //INGRESAMOS LOS AUTORES
            foreach ($estudiantesGrupo as $estudiante) {
                 //VERIFICAMOS SI EL ESTUDIANTE YA ESTA INGRESADO 3-Estudiante
                $llaveIntegracion = gen_int_integracionModel::where("llave_gen_int","=",$estudiante->ID_Estudiante)->where("id_gen_tpo_int","=",3)->first();
                if (empty($llaveIntegracion->id_gen_int)) {
                    $lastIdIntegracion = gen_int_integracionModel::create
                    ([
                        'id_gen_tpo_int' => 3,
                        'llave_gen_int' => $estudiante->ID_Estudiante

                    ]);
                    $lastIdAutor = pub_aut_publicacion_autorModel::create
                    ([
                        'id_pub' => $lastIdPublicacion->id_pub,
                        'id_gen_int' => $lastIdIntegracion->id_gen_int,
                        'nombres_pub_aut' => $estudiante->Nombre,
                        'apellidos_pub_aut' => ""
                    ]);

                }else{
                    $autor=pub_aut_publicacion_autorModel::where("id_gen_int","=",$llaveIntegracion->id_gen_int)->first();
                     $lastIdAutor = pub_aut_publicacion_autorModel::create
                    ([
                        'id_pub' => $lastIdPublicacion->id_pub,
                        'id_gen_int' => $autor->id_gen_int,
                        'nombres_pub_aut' => $estudiante->Nombre,
                        'apellidos_pub_aut' => ""
                    ]);
                }
            }
            //GUARDAMOS EL DOCUMENTO ASOCIADO A LA PUBLICACION
            $file = $request->file('tomoFinal');
            $publicacion = pub_publicacionModel::find($request['publicacion']);
           //obtenemos el nombre del archivo
            $nombre = "Codigo".$codigo.date('hms').$file->getClientOriginalName();
           //indicamos que queremos guardar un nuevo archivo en el disco local
            Storage::disk('publicaciones')->put($nombre, File::get($file));
            $fecha=date('Y-m-d H:m:s');
            $path= public_path().$_ENV['PATH_PUBLICACIONES'];
             $lastIdDocumento = pub_arc_publicacion_archivoModel::create
            ([
                'id_pub'                     => $lastIdPublicacion->id_pub,
                'ubicacion_pub_arc'          => $nombre,
                'fecha_subida_pub_arc'       => $fecha,
                'nombre_pub_arc'             => $file->getClientOriginalName(),
                'descripcion_pub_arc'        => "Tomo final, cierre de trabajo de graduación",
                'ubicacion_fisica_pub_arc'   => 'PENDIENTE',
                'activo_pub_arc'             => 1
            ]);


        }
        Session::flash('message', 'Se ha realizado el cierre de trabajo de graduación Exitosamente!!');
       return redirect("/publicacion/".$lastIdPublicacion->id_pub);
    }

}
