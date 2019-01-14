<?php
namespace App\Http\Controllers\TrabajoGraduacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\pdg_dcn_docenteModel;
use App\pdg_gru_grupoModel;
use Session;
use Redirect;
use PDF;

class ReportesController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }
    function test(){
        $pdf = PDF::loadView('pdfTemplate');
        return $pdf->stream('reporte.pdf');
        //return view('pdfTemplate');
    }
    public function index(){
        $reportes = array('R1'=>'Grupos y Jurados','R2'=>'Docentes y Asignaciones');
        return view('TrabajoGraduacion.Reports.index',compact('reportes'));
    }

    public function createTribunalPorGrupo(){
        return view('TrabajoGraduacion.Reports.Create.createTribunalPorGrupo');
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
        $title = "Reporte de Tribunal Evaluador por Grupos ". $nombre." - ".$anio;
        $datos = pdg_dcn_docenteModel::getLideres($anio,$estado);
        $tribs = pdg_dcn_docenteModel::getTribunales($anio,$estado);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.TribunalPorGrupo',compact('datos','tribs', 'title'));
        if ($tipo == 1) {
            return $pdf->stream('Reporte de Tribunal Evaluador por Grupos Activos.pdf');
        }elseif ($tipo == 2) {
            return $pdf->download('Reporte de Tribunal Evaluador por Grupos Activos.pdf');
        }else{
            return view("template");
        }
        
    }

    public function createAsignacionesPorDocente(){
        return view('TrabajoGraduacion.Reports.Create.createAsignacionesPorDocente');
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
        $title = "Reporte de Asignaciones ".$nombre." por Docente ".$anio;
        $datos = pdg_dcn_docenteModel::getDocentes($anio,$estado);
        $grupos = pdg_dcn_docenteModel::getGrupos($anio,$estado);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.asignacionesPorDocente',compact('datos','grupos', 'title'));
        if ($tipo == 1) {
            return $pdf->stream('Reporte de Tribunal Evaluador por Grupos Activos.pdf');
        }elseif ($tipo == 2) {
            return $pdf->download('Reporte de Tribunal Evaluador por Grupos Activos.pdf');
        }else{
            return view("template");
        }
    }

    public function createEstadoGruposEtapa(){
        return view('TrabajoGraduacion.Reports.Create.createEstadoGruposEtapa');
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
        $title = "Reporte de Estado de Grupos";
        $datos = pdg_gru_grupoModel::getEstadoGrupos($anio, $estado);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.EstadoGruposEtapas',compact('datos', 'title'));
        if ($tipo == 1) {
            return $pdf->stream('Reporte de Estado de Grupos Activos.pdf');
        }elseif ($tipo == 2) {
            return $pdf->download('Reporte de Estado de Grupos Activos.pdf');
        }else{
            return view("template");
        }
    }

    public function createDetalleGruposTdg(){
        return view('TrabajoGraduacion.Reports.Create.createDetalleGruposTdg');
    }

    public function detalleGruposTdg(Request $request){
        $anio = 2019;
        $title = "Reporte de Detalle de Grupos de Trabajo de Graduación";
        $datos = pdg_gru_grupoModel::getDetalleGrupos($anio);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.detalleGruposTdg',compact('datos', 'title'));
        return $pdf->stream('Reporte de Estado de Grupos Activos.pdf');
    }
}
