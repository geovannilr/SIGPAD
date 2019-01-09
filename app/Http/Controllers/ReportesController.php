<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\pdg_dcn_docenteModel;
use App\pdg_gru_grupoModel;
use PDF;

class ReportesController extends Controller{
    function test(){
    	$pdf = PDF::loadView('pdfTemplate');
		return $pdf->stream('reporte.pdf');
    	//return view('pdfTemplate');
    }
    public function tribunalPorGrupo(Request $request){
        $title = "Reporte de Tribunal Evaluador por Grupos Activos";
        $datos = pdg_dcn_docenteModel::getLideres(2019);
        $tribs = pdg_dcn_docenteModel::getTribunales(2019);

        $pdf = PDF::loadView('TrabajoGraduacion.Reports.TribunalPorGrupo',compact('datos','tribs', 'title'));
		return $pdf->stream('Reporte de Tribunal Evaluador por Grupos Activos.pdf');
    }
    public function asignacionesPorDocente(Request $request){
        $title = "Reporte de Asignaciones Activas por Docente Asesor";
        $datos = pdg_dcn_docenteModel::getDocentes(2019);
        $grupos = pdg_dcn_docenteModel::getGrupos(2019);
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.asignacionesPorDocente',compact('datos','grupos', 'title'));
        return $pdf->stream('Reporte de Tribunal Evaluador por Grupos Activos.pdf');
    }

    public function estadoGruposEtapa(Request $request){
        $title = "Reporte de Estado de Grupos Activos";
        $datos = pdg_gru_grupoModel::getEstadoGrupos();
        $pdf = PDF::loadView('TrabajoGraduacion.Reports.EstadoGruposEtapas',compact('datos', 'title'));
        return $pdf->stream('Reporte de Estado de Grupos Activos.pdf');
    }
}
