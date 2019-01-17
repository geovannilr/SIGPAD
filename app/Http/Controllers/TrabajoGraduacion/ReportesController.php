<?php
namespace App\Http\Controllers\TrabajoGraduacion;
use App\gen_EstudianteModel;
use App\Http\Controllers\Controller;
use App\pdg_not_cri_tra_nota_criterio_trabajoModel;
use Illuminate\Http\Request;
use App\pdg_dcn_docenteModel;
use App\pdg_gru_grupoModel;
use Session;
use Redirect;
use PDF;

class ReportesController extends Controller{

    const REPORTES = [
        'Reporte de Tribunal Evaluador por Grupos',
        'Reporte de Asignaciones XYZ por Docente',
        'Reporte de Estado de Grupos',
        'Reporte de Detalle de Grupos XYZ de Trabajo de Graduación',
        'Reporte de Estudiantes Activos en Trabajo de Graduacion',
        'Reporte Consolidado de Notas'
    ];

    public function __construct(){
        $this->middleware('auth');
    }
    function test(){
        $pdf = PDF::loadView('pdfTemplate');
        return $pdf->stream('reporte.pdf');
        //return view('pdfTemplate');
    }
    public function index(){
        return view('TrabajoGraduacion.Reports.index');
    }

    public function createTribunalPorGrupo(){
        $title = self::REPORTES[0];
        return view('TrabajoGraduacion.Reports.Create.createTribunalPorGrupo',compact('title'));
    }
    public function tribunalPorGrupo(Request $request){
        $anio = $request['anio'];
        $estado = $request['estado'];
        $tipo = $request['tipo'];
        switch ($estado) {
            case '0':
            $nombre = "Activos";
                break;
            case '1':
            $nombre = "Finalizados";
                break;
            default:
            $nombre = ""; 
                break;
        }
        $title = self::REPORTES[0]." ". $nombre." - ".$anio;
        $datos = pdg_dcn_docenteModel::getLideres($anio,$estado);
        $tribs = pdg_dcn_docenteModel::getTribunales($anio,$estado);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.TribunalPorGrupo',compact('datos','tribs', 'title'));
        if ($tipo == 1) {
            return $pdf->stream($title.'.pdf');
        }elseif ($tipo == 2) {
            return $pdf->download($title.'.pdf');
        }else{
            return view("template");
        }
        
    }

    public function createAsignacionesPorDocente(){
        $title = self::REPORTES[1];
        $title = str_replace("XYZ","",$title);
        return view('TrabajoGraduacion.Reports.Create.createAsignacionesPorDocente',compact('title'));
    }

    public function asignacionesPorDocente(Request $request){
        $anio = $request['anio'];
        $estado = $request['estado'];
        $tipo = $request['tipo'];
        switch ($estado) {
            case '0':
                $nombre = "Activas";
                break;
            case '1':
                $nombre = "Finalizadas";
                break;
            default:
                $nombre = "";
                break;
        }
        $title = self::REPORTES[1]." ".$anio;
        $title = str_replace("XYZ",$nombre,$title);
        $datos = pdg_dcn_docenteModel::getDocentes($anio,$estado);
        $grupos = pdg_dcn_docenteModel::getGrupos($anio,$estado);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.asignacionesPorDocente',compact('datos','grupos', 'title'));
        if ($tipo == 1) {
            return $pdf->stream($title.'.pdf');
        }elseif ($tipo == 2) {
            return $pdf->download($title.'.pdf');
        }else{
            return view("template");
        }
    }

    public function createEstadoGruposEtapa(){
        $title = self::REPORTES[2];
        return view('TrabajoGraduacion.Reports.Create.createEstadoGruposEtapa',compact('title'));
    }

    public function estadoGruposEtapa(Request $request){
        $anio = $request['anio'];
        $estado = $request['estado'];
        $tipo = $request['tipo'];
        switch ($estado) {
            case '0':
                $nombre = "Activas";
                break;
            case '1':
                $nombre = "Finalizadas";
                break;
            default:
                $nombre = "";
                break;
        }
        $title = self::REPORTES[2];
        $datos = pdg_gru_grupoModel::getEstadoGrupos($anio, $estado);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.EstadoGruposEtapas',compact('datos', 'title'));
        if ($tipo == 1) {
            return $pdf->stream($title.'.pdf');
        }elseif ($tipo == 2) {
            return $pdf->download($title.'.pdf');
        }else{
            return view("template");
        }
    }

    public function createDetalleGruposTdg(){
        $title = self::REPORTES[3];
        $title = str_replace("XYZ","",$title);
        return view('TrabajoGraduacion.Reports.Create.createDetalleGruposTdg',compact('title'));
    }

    public function detalleGruposTdg(Request $request){
        $anio = $request['anio'];
        $estado = $request['estado'];
        $tipo = $request['tipo'];
        switch ($estado) {
            case '0':
                $nombre = "Activos";
                break;
            case '1':
                $nombre = "Finalizados";
                break;
            default:
                $nombre = "";
                break;
        }
        $title = self::REPORTES[3]." ".$anio;
        $title = str_replace("XYZ"," ".$nombre,$title);
        $datos = pdg_gru_grupoModel::getDetalleGrupos($anio,$estado);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.detalleGruposTdg',compact('datos', 'title'));
        if ($tipo == 1) {
            return $pdf->stream($title.'.pdf');
        }elseif ($tipo == 2) {
            return $pdf->download($title.'.pdf');
        }else{
            return view("template");
        }
    }

    public function createEstudiantesTdg(){
        $title = self::REPORTES[4];
        return view('TrabajoGraduacion.Reports.Create.createEstudiantesTdg',compact('title'));
    }

    public function estudiantesTdg(Request $request){
        $tipo = $request['tipo'];
        $title = self::REPORTES[4];
        $datos = gen_EstudianteModel::getEstudiantesActivos();
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.estudiantesTdg',compact('datos', 'title'));
        if ($tipo == 1) {
            return $pdf->stream($title.'.pdf');
        }elseif ($tipo == 2) {
            return $pdf->download($title.'.pdf');
        }else{
            return view("template");
        }
    }

    public function createConsolidadoNotas(){
        $title = self::REPORTES[5];
        $grupos = pdg_gru_grupoModel::getAllGrupos();
        return view('TrabajoGraduacion.Reports.Create.createConsolidadoNotas',compact('title','grupos'));
    }

    public function consolidadoNotas(Request $request){
        $tipo = $request['tipo'];
        $grupo = $request['grupo'];
        $title = self::REPORTES[5];
        $subtitle = 'GRUPO '.pdg_gru_grupoModel::find($grupo)->numero_pdg_gru;
        $datos = pdg_not_cri_tra_nota_criterio_trabajoModel::getConsolidadoNotas($grupo);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.consolidadoNotas',compact('datos', 'title', 'subtitle'));
        if ($tipo == 1) {
            return $pdf->stream($title.'.pdf');
        }elseif ($tipo == 2) {
            return $pdf->download($title.'.pdf');
        }else{
            return view("template");
        }
    }
}
