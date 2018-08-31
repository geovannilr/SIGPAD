<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use App\Http\Controllers\Controller;
use App\pdg_dcn_docenteModel;
use Session;
use Redirect;

class ReportesController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

   	public function index(){
        $reportes = array('R1'=>'Grupos y Jurados','R2'=>'Docentes y Asignaciones');
        return view('TrabajoGraduacion.Reports.index',compact('reportes'));
    }
    public function r1(){
        $title = "Grupos y Jurados";
        $datos = pdg_dcn_docenteModel::getLideres(2018);
        $tribs = pdg_dcn_docenteModel::getTribunales(2018);
        return view('TrabajoGraduacion.Reports.R1',compact('datos','tribs', 'title'));
    }
    public function r2(){
        $title = "Docentes y Asignaciones";
        $datos = pdg_dcn_docenteModel::getDocentes(2018);
        $grupos = pdg_dcn_docenteModel::getGrupos(2018);
        return view('TrabajoGraduacion.Reports.R2',compact('datos','grupos', 'title'));
    }
}
