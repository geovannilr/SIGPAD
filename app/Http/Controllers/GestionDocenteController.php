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
use File;
class GestionDocenteController extends Controller
{
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
	function index(){

		return "test";
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
    
}
