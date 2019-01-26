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
        'SOLPRO'=>'x',
        'SOLDEF'=>'x',
        'EVAANT'=>'x',
        'EVAETA'=>'x',
        'EVADEF'=>'x',
        'OFITEM'=>'x',
        'AUTGRU'=>'x',
        'LEGTRIB'=>'x'
    ];

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $formatos = gen_frmt_formatoModel::findFormatosDisponibles(Auth::user()->id);
        return view('TrabajoGraduacion.Formatos.index',compact('formatos'));
    }

    public function descargaFormato(Request $request){
        //TODO permiso y los archivos colocarlos en server. Recordar modificar archivo ENV en srvr!
//        if(!Auth::user()->can(['formatos.descargar'])){
//            return redirect("/")->with(['message-error'=>'No tiene permisos para acceder a esta opción']);
//        }else{
            $codigo = $request['idFormato'];
            $fileName = self::FORMATOS[$codigo];
            $path= public_path().$_ENV['PATH_FORMATOS'].$fileName;
            if (File::exists($path)){
                return response()->download($path);
            }else{
                return redirect("formatos")->with(['message-error'=>'El formato solicitado no se encuentra disponible , es posible que haya sido  borrado.']);
            }
//        }
    }
}