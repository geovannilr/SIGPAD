<?php
namespace App\Http\Controllers\TrabajoGraduacion;

use App\Http\Controllers\Controller;
use \App\gen_frmt_formatoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Session;
use Redirect;
use PDF;

class FormatoController extends Controller {

    const FORMATOS = [
        'SOLPRO'=>'frmt-solicitud-prorroga.docx',
        'SOLDEF'=>'frmt-solicitud-defensa-final.docx',
        'SOLLIC'=>'frmt-solicitud-licencia-uso.docx',
        'EVAANT'=>'frmt-evaluacion-anteproyecto.docx',
        'EVAETA'=>'frmt-evaluacion-etapa.docx',
        'EVADEF'=>'frmt-evaluacion-defensa-final.docx',
        'OFITEM'=>'frmt-oficializa-tema-estudiantes-asesores.docx',
        'AUTGRU'=>'frmt-autoriza-grupos-cuatro-cinco.docx',
        'LEGTRIB'=>'frmt-legaliza-tribunal-evaluador.docx',
        'ACTAPR'=>'frmt-acta-aprobacion.docx',
        'RATRES'=>'frmt-ratifica-notas.docx',
        'REMEJE'=>'frmt-remision-ejemplares.docx',
    ];

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $formatos = gen_frmt_formatoModel::findFormatosDisponibles(Auth::user()->id);
        if(empty($formatos)){
            return redirect("/")->with(['message-error'=>'Actualmente no posee formatos disponibles para descargar']);
        }
        return view('TrabajoGraduacion.Formatos.index',compact('formatos'));
    }

    public function descargaFormato(Request $request){
        if(!Auth::user()->can(['formatos.download'])){
            return redirect("/")->with(['message-error'=>'No tiene permisos para acceder a esta opción']);
        }else{
            $codigo = $request['idFormato'];
            $fileName = self::FORMATOS[$codigo];
            $path= public_path().$_ENV['PATH_FORMATOS'].$fileName;
            if (File::exists($path)){
                return response()->download($path);
            }else{
                return redirect("formatos")->with(['message-error'=>'El formato solicitado no se encuentra disponible , es posible que haya sido  borrado.']);
            }
        }
    }
}