<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use \App\pdg_dcn_docenteModel;
use \App\dcn_exp_experienciaModel;
use \App\dcn_his_historial_academicoModel;
use \App\cat_mat_materiaModel;
use \App\dcn_cer_certificacionesModel;
use \App\cat_car_cargo_eisiModel;
use \App\gen_UsuarioModel;
use \App\cat_ski_skillModel;
use \App\User;
use File;;
use Illuminate\Support\Facades\Storage;
class GestionDocenteController extends Controller
{   
    public function __construct(){
        $this->middleware('auth', ['only' => ['index','create','store','downloadPlantilla','actualizarPerfilDocente','updateDocente','listadoDocentes','edit','createUpdateDocente','updateDocenteExcel','downloadPlantillaAdministraDocente','validaPermiso']]);
    }
    function index(){
       $userLogin = Auth::user();
       $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
       $idDocente = $docente->id_pdg_dcn; 
       $docente = new pdg_dcn_docenteModel();
       $info = $docente->getGeneralInfo($idDocente);
       $academica = $docente->getHistorialAcademico($idDocente);
       $laboral = $docente->getDataExperienciaDocente($idDocente);
       $certificaciones = $docente->getDataCertificacionesDocente($idDocente);
       $habilidadesSelect = cat_ski_skillModel::pluck("nombre_cat_ski","id_cat_ski");
       $habilidades = $docente->getDataSkillsDocente($idDocente);
       $cargosPrincipal = cat_car_cargo_eisiModel::all();
       $cargosSegundarios = cat_car_cargo_eisiModel::all();
       $bodySelectPrincipal="";
       $bodySelectSecundario="";
       foreach ($cargosPrincipal as $principal) {
            if ($principal->id_cat_car == $info[0]->id_cat_car) {
                 $bodySelectPrincipal.='<option value="'.$principal->id_cat_car.'" selected="selected">
                                    '.$principal->nombre_cargo.'
                                    </option>';
            }else{
                 $bodySelectPrincipal.='<option value="'.$principal->id_cat_car.'">
                                    '.$principal->nombre_cargo.'
                                    </option>';
            }
           
       }
       
       foreach ($cargosSegundarios as $secundario) {
            if ($secundario->id_cat_car == $info[0]->id_segundo_cargo) {
                 $bodySelectSecundario.='<option value="'.$secundario->id_cat_car.'" selected="selected">
                '.$secundario->nombre_cargo.'
                </option>';
            }else{
                    $bodySelectSecundario.='<option value="'.$secundario->id_cat_car.'">
                    '.$secundario->nombre_cargo.'
                    </option>';
            }
           
       }
       return view('PerfilDocente.index', compact('info','academica','laboral','certificaciones','habilidades','bodySelectPrincipal','bodySelectSecundario','habilidadesSelect'));
    }
    function create(){
        return view('PerfilDocente.create');
    }
    function store(Request $request){
        $validatedData = $request->validate([
            'documentoPerfil' => 'required',
        ]);
        //return var_dump($request);
        $bodyHtml = '';
        $userLogin = Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        $idDocente = $docente->id_pdg_dcn;
        $bodyHtml = '';
        $dataLaboral = Excel::load($request->file('documentoPerfil'), function ($reader) {
            $reader->setSelectedSheetIndices(array(4)); //4-6
        })->get();
        $dataAcademica = Excel::load($request->file('documentoPerfil'), function ($reader) {
            $reader->setSelectedSheetIndices(array(5)); 
        })->get();
        $dataCertificaciones = Excel::load($request->file('documentoPerfil'), function ($reader) {
            $reader->setSelectedSheetIndices(array(6)); 
        })->get();
        $experienciaLaboral = $dataLaboral->toArray();
        $experienciaAcademica = $dataAcademica->toArray();
        $certificaciones = $dataCertificaciones->toArray();
        //return var_dump($certificaciones[0]);
        //INSERTANDO LA EXPERIENCIA LABORAL
        try {
            foreach ($experienciaLaboral as $laboral) {
                if (!is_null($laboral["lugartrabajo"]) && !is_null($laboral["fechainicio"]) && !is_null($laboral["descripcion"]) && !is_null($laboral["idioma"])) {
                    $fechafin = $laboral["fechafin"];
                    if (is_null($laboral["fechafin"])) {
                        $fechafin = "N/A";
                    }
                   $lastId = dcn_exp_experienciaModel::create
                    ([
                        'lugar_trabajo_dcn_exp' => $laboral["lugartrabajo"],
                        'anio_inicio_dcn_exp'   => $laboral["fechainicio"],
                        'anio_fin_dcn_exp'      => $laboral["fechafin"],
                        'descripcion_dcn_exp'   => $laboral["descripcion"],
                        'id_cat_idi'            => $laboral["idioma"],
                        'id_pdg_dcn'            => $idDocente  
                        
                    ]);
                }
            }
            $bodyHtml .= '<tr>';
                        $bodyHtml .= '<td>EXPERIENCIA LABORAL</td>';
                        $bodyHtml .= '<td><span class="badge badge-success">OK</span></td>';
                        $bodyHtml .= '<td>Todos los registros se realizaron exitosamente.</td>';
                        $bodyHtml .= '</tr>';
        } catch (Exception $e) {
           $bodyHtml .= '<tr>';
                        $bodyHtml .= '<td>EXPERIENCIA LABORAL</td>';
                        $bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
                        $bodyHtml .= '<td>Ocurrió un problema en alguno de los registros de la experiencia Laboral</td>';
                        $bodyHtml .= '</tr>';
        }

        //INSERTANDO LA EXPERIENCIA ACADEMICA
        try {
            foreach ($experienciaAcademica as $academica) {
                if (!is_null($academica["cargo"]) && !is_null($academica["anho"]) && !is_null($academica["materia"])) {
                    $materia = cat_mat_materiaModel::where("codigo_mat","=",$academica["materia"])->first();
                    $idMateria=$materia->id_cat_mat;
                    $descripcion = $academica["descripcion"];
                    if (is_null($academica["descripcion"])) {
                        $descripcion = "N/A";
                    }
                   $lastId = dcn_his_historial_academicoModel::create
                    ([
                        'id_pdg_dcn'                => $idDocente,
                        'id_cat_mat'                => $idMateria,
                        'id_cat_car'                => $academica["cargo"],
                        'anio'                      => $academica["anho"],
                        'descripcion_adicional'     => $academica["descripcion"] 
                        
                    ]);
                }
            }
            $bodyHtml .= '<tr>';
                        $bodyHtml .= '<td>EXPERIENCIA ACADEMICA</td>';
                        $bodyHtml .= '<td><span class="badge badge-success">OK</span></td>';
                        $bodyHtml .= '<td>Todos los registros se realizaron exitosamente.</td>';
                        $bodyHtml .= '</tr>';
        } catch (Exception $e) {
           $bodyHtml .= '<tr>';
                        $bodyHtml .= '<td>EXPERIENCIA ACADEMICA</td>';
                        $bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
                        $bodyHtml .= '<td>Ocurrió un problema en alguno de los registros de la experiencia academica.</td>';
                        $bodyHtml .= '</tr>';
        }

        //INSERTANDO CERTIFICACIONES
         
        try {
            foreach ($certificaciones as $certificacion) {
                if (!is_null($certificacion["nombrecert"]) && !is_null($certificacion["anhocert"]) && !is_null($certificacion["institucioncert"]) && !is_null($certificacion["idiomacert"])) {
                    
                   $lastId = dcn_cer_certificacionesModel::create
                    ([
                        'nombre_dcn_cer'                => $certificacion["nombrecert"],
                        'anio_expedicion_dcn_cer'       => $certificacion["anhocert"],
                        'institucion_dcn_cer'           => $certificacion["institucioncert"],
                        'id_cat_idi'                    => $certificacion["idiomacert"],
                        'id_dcn'                        => $idDocente               
                    ]);
                }
            }
            $bodyHtml .= '<tr>';
                        $bodyHtml .= '<td>CERTIFICACIONES</td>';
                        $bodyHtml .= '<td><span class="badge badge-success">OK</span></td>';
                        $bodyHtml .= '<td>Todos los registros se realizaron exitosamente.</td>';
                        $bodyHtml .= '</tr>';
        } catch (Exception $e) {
           $bodyHtml .= '<tr>';
                        $bodyHtml .= '<td>EXPERIENCIA LABORAL</td>';
                        $bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
                        $bodyHtml .= '<td>Ocurrió un problema en alguno de los registros de la experiencia Laboral</td>';
                        $bodyHtml .= '</tr>';
        }
        $docenteObjeto = pdg_dcn_docenteModel::find($idDocente);
        if (isset($request["perfilPrivado"])) {
            $docenteObjeto->perfilPrivado='0'; //PERFIL DEBE SER PUBLICO
            $docenteObjeto->save();
        }else{
             $docenteObjeto->perfilPrivado='1'; //PERFIL DEBE SER PRIVADO
             $docenteObjeto->save();
        }
       
        
        return view('PerfilDocente.resultadoCarga', compact('bodyHtml'));
    }
	
    function getInfoDocente(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getDataGestionDocente($request['docente']);
    	return $info;
    }
    function getHistorial(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getHistorialAcademico($request['docente']);
    	return $info;
    }
    function getExperiencia(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getDataExperienciaDocente($request['docente']);
    	return $info;
    	
    }
    function getCertificaciones(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getDataCertificacionesDocente($request['docente']);
    	return $info;
    	
    }
    function getSkills(Request $request){
    	$docente = new pdg_dcn_docenteModel();
    	$info = $docente->getDataSkillsDocente($request['docente']);
    	return $info;
    	
    }
    function getGeneralInfoDocente(Request $request){
        $docente = new pdg_dcn_docenteModel();
        $info = $docente->getGeneralInfo($request['docente']);
        return $info;
        
    }

    function getListadoDocentes(Request $request){
            $docente = new pdg_dcn_docenteModel();
            $info = $docente->getListadoDocenteByJornada($request['jornada']);
            return $info;
            
    }
    function downloadPlantilla(Request $request){
            $path= public_path().$_ENV['PATH_RECURSOS'].'temp-perfil-docente.xlsx';
            if (File::exists($path)){
                return response()->download($path);
            }else{
                Session::flash('error','El documento no se encuentra disponible , es posible que haya sido  borrado');
                return view('PerfilDocente.create');
            }
    }
    function actualizarPerfilDocente(Request $request){
       $validatedData = $request->validate(
            [
                'nombre' => 'required',
                'cargoPrincipal' => 'required',
                'descripcion' => 'max:800',
                'email' => 'required'
            ],
            [
                'nombre.required' => 'Debe ingresar el nombre para mostrar en su perfil',
                'email.required' => 'Debe ingresar un correo electrónico',
                'cargoPrincipal.required' => 'Debe seleccionar un cargo principal.',
                'descripcion.max' => 'La descripcición debe ser máximo de 800 caracteres.'
            ]
        );
      $userLogin = Auth::user();
      $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
      //Obtenemos la información del usuario.
      $usuario = gen_UsuarioModel::find($userLogin->id);
      $idDocente = $docente->id_pdg_dcn;
      //Obtenemos la información del docente
      $infoDocente = pdg_dcn_docenteModel::find($idDocente);
      if (!empty($request->file('fotoPerfil'))) {
         //OBTENEMOS EL ARCHIVO
          $file = $request->file('fotoPerfil');
          //obtenemos el nombre del archivo
           $nombre = $userLogin->user.'.'.$file->getClientOriginalExtension();
          //indicamos que queremos guardar un nuevo archivo en el disco local
          Storage::disk('perfilDocente')->put($nombre, File::get($file));
          $path= url('/').$_ENV['PATH_PERFIL_DOCENTE'];
      }
     
      $usuario->email = $request['email'];

      $infoDocente->descripcionDocente=$request['descripcion'];
      $infoDocente->id_cargo_actual=$request['cargoPrincipal'];
      $infoDocente->link_fb=$request['fb'];
      $infoDocente->link_linke=$request['linkedin'];
      $infoDocente->link_tw=$request['tw'];
      $infoDocente->link_git=$request['git'];
      $infoDocente->display_name=$request['nombre'];
      if (!empty($request->file('fotoPerfil'))) {
         $infoDocente->dcn_profileFoto=$nombre;
      }
      if(isset($request["perfilPrivado"])){
      	 $infoDocente->perfilPrivado=1;
      }else{
      	 $infoDocente->perfilPrivado=0;
      }
      $infoDocente->id_segundo_cargo=$request['cargoSegundario'];
     
      $infoDocente->save();
      $usuario->save();
      Session::flash('message','Actualización  de información general de Perfil Docente realizada con éxito.');
      return Redirect::to('DashboardPerfilDocente'); 

    }
    function listadoDocentes (){
        if(!self::validaPermiso('gestionDocente.index')){
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
        $docentes = pdg_dcn_docenteModel::all();
        return view('PerfilDocente.listadoDocentes',compact('docentes'));
    }
    
    public function edit($id){
        if(!self::validaPermiso('gestionDocente.edit')){
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
      $docente = pdg_dcn_docenteModel::find($id);
      $cargos = cat_car_cargo_eisiModel::all();
      $bodySelectPrincipal="";
      $bodySelectSecundario="";
      $bodySelectJornada="";
       foreach ($cargos as $principal) {
            if ($principal->id_cat_car == $docente->id_cargo_actual) {
                 $bodySelectPrincipal.='<option value="'.$principal->id_cat_car.'" selected="selected">
                                    '.$principal->nombre_cargo.'
                                    </option>';
            }else{
                 $bodySelectPrincipal.='<option value="'.$principal->id_cat_car.'">
                                    '.$principal->nombre_cargo.'
                                    </option>';
            }
           
       }
       
       foreach ($cargos as $secundario) {
            if ($secundario->id_cat_car == $docente->id_segundo_cargo) {
                 $bodySelectSecundario.='<option value="'.$secundario->id_cat_car.'" selected="selected">
                '.$secundario->nombre_cargo.'
                </option>';
            }else{
                    $bodySelectSecundario.='<option value="'.$secundario->id_cat_car.'">
                    '.$secundario->nombre_cargo.'
                    </option>';
            }
           
       }
       if ($docente->tipoJornada == 1) {
         $bodySelectJornada ='
                  <option value="">Seleccione una Jornada</option>
                  <option value="1" selected="selected">Tiempo Completo</option>
                  <option value="2">Tiempo Parcial</option>
                  <option value="3">Servicio Profesional</option>';
       }else if($docente->tipoJornada == 2){
          $bodySelectJornada ='
                  <option value="">Seleccione una Jornada</option>
                  <option value="1">Tiempo Completo</option>
                  <option value="2" selected="selected">Tiempo Parcial</option>
                  <option value="3">Servicio Profesional</option>';
       }
       else if($docente->tipoJornada == 3){
          $bodySelectJornada ='
                  <option value="">Seleccione una Jornada</option>
                  <option value="1">Tiempo Completo</option>
                  <option value="2">Tiempo Parcial</option>
                  <option value="3" selected="selected">Servicio Profesional</option>';
       }else{
          $bodySelectJornada ='
                  <option value="">Seleccione una Jornada</option>
                  <option value="1">Tiempo Completo</option>
                  <option value="2">Tiempo Parcial</option>
                  <option value="3">Servicio Profesional</option>';
       }
      return view('PerfilDocente.edit',compact('docente','bodySelectPrincipal','bodySelectSecundario','bodySelectJornada'));
    }
    public function updateDocente(Request $request){
        if(!self::validaPermiso('gestionDocente.edit')){
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
       $validatedData = $request->validate(
            [
                'docente' => 'required',
                'cargoPrincipal' => 'required',
                'jornada' => 'required'
            ],
            [
                'docente.required' => 'El docente es obligatorio',
                'cargoPrincipal.required' => 'Debe seleccionar un cargo principal.',
                'jornada.required' => 'Debe seleccionar una jornada'
            ]
        );
       $docente = pdg_dcn_docenteModel::find($request['docente']);
       if (empty($docente->id_pdg_dcn)) {
         return Redirect::to('/'); 
       }
       $docente->id_cargo_actual    = $request['cargoPrincipal'];
       if ( $request['cargoSegundario']!="" &&  $request['cargoSegundario']!=NULL &&  isset($request['cargoSegundario'])) {
          $docente->id_segundo_cargo   = $request['cargoSegundario'];
       }
       $docente->tipoJornada        = $request['jornada'];
       $docente->save();
      Session::flash('message','Actualización  de información de Docente realizada con éxito.');
      return Redirect::to('listadoDocentes'); 
    }


    public function createUpdateDocente() {
        if(!self::validaPermiso('gestionDocente.cargar')){
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
        return view('PerfilDocente.UpdateExcel.create');
    }
    public function updateDocenteExcel(Request $request) {
        if(!self::validaPermiso('gestionDocente.cargar')){
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
        $validatedData = $request->validate([
          'documentoDocentes' => 'required',
        ]);
        $bodyHtml = '';
        $data = Excel::load($request->file('documentoDocentes'), function ($reader) {
          $reader->setSelectedSheetIndices(array(1));
        })->get();
        $docentes = $data->toArray();
        if (sizeof($docentes) != 0) {
          foreach ($docentes as $docente) {
            //return var_dump($usuario);
            if (!is_null($docente["usuario"])) {
              //Verificamos si el docente se encuentra registrado 
              $user  = User::where('user','=',$docente["usuario"])->first();
              if (!empty($user->id)){
                $registroDocente = pdg_dcn_docenteModel::where('id_gen_usuario','=',$user->id)->first();
                $docenteById=pdg_dcn_docenteModel::find($registroDocente->id_pdg_dcn);
                $jornada = $docenteById->tipoJornada;
                if (!is_null($docente["jornada"])){
                  $jornada=$docente["jornada"];
                  $docenteById->tipoJornada = $docente["jornada"];
                }
                if (!is_null($docente["cargo1"])){
                  $docenteById->id_cargo_actual = $docente["cargo1"];
                }
                if (!is_null($docente["cargo2"])){
                  $docenteById->id_segundo_cargo = $docente["cargo2"];
                }
                $docenteById->save();
                $jornadaTexto = "N/A";
                if ($jornada ==1) {
                  $jornadaTexto ="Tiempo Completo";
                }else if($jornada ==2){
                   $jornadaTexto ="Tiempo Parcial";
                }else{
                   $jornadaTexto ="Servicio Profesional";
                }

                $bodyHtml .= '<tr>';
                $bodyHtml .= '<td>' . $docente["usuario"].'</td>';
                $bodyHtml .= '<td>' . $docenteById->display_name. '</td>';
                $bodyHtml .= '<td><span class="badge badge-success">OK</span></td>';
                $bodyHtml .= '<td>Docente actualizado exitosamente</td>';
                $bodyHtml .= '</tr>';
              } else {
                //Usuario repetido
                $bodyHtml .= '<tr>';
                $bodyHtml .= '<td>' .  "N/A"  . '</td>';
                $bodyHtml .= '<td> N/A</td>';
                $bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
                $bodyHtml .= '<td>El Docente que esta intentando actualizar no se encuentra registrado.</td>';
                $bodyHtml .= '</tr>';
               
              }

                }

              }

            }
            return view('PerfilDocente.UpdateExcel.index', compact('bodyHtml'));
  }
  public function downloadPlantillaAdministraDocente(){
      if(!self::validaPermiso('gestionDocente.cargar')){
          Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
          return  view('template');
      }
      $path= public_path().$_ENV['PATH_RECURSOS'].'temp-administra-docentes.xlsx';
      if (File::exists($path)){
          return response()->download($path);
      }else{
          Session::flash('error','El documento no se encuentra disponible , es posible que haya sido  borrado');
          return view('PerfilDocente.UpdateExcel.create');
      }
  }

  private static function validaPermiso($slug){
        return Auth::user()->can([$slug]);
  }
}
