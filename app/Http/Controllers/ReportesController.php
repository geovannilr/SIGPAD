<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\pdg_dcn_docenteModel;
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
		return $pdf->stream('reporte.pdf');
        //return view('TrabajoGraduacion.Reports.TribunalPorGrupo',compact('datos','tribs', 'title'));
    }
}
