<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use \App\gen_UsuarioModel;
use \App\gen_EstudianteModel;
use \App\User;
use Caffeinated\Shinobi\Models\Permission;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Support\Facades\Auth;

class ConformarGrupoController extends Controller
{
     public function __construct(){
        $this->middleware('auth');
    }
	/**
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios =gen_UsuarioModel::all();
        return view('usuario.index',compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){  
        $cards="";
       $estudiante = new gen_EstudianteModel();
       $miCarnet=Auth::user()->user;
       $respuesta =$estudiante->getGrupoCarnet($miCarnet)->getData();  //getdata PARA CAMBIAR LOS VALORES DEL JSON DE PUBLICOS A PRIVADOS
       if ($respuesta->errorCode == '0') {
            $estudiantes=json_decode($respuesta->msg->estudiantes);//decode string to json
            foreach ($estudiantes as $estudiante ) { 
                $card='';
                $card.='<div class="col-sm-4" id="card'.$estudiante->carnet.'">';
                $card.='<div class="card border-primary mb-3">';
                if ($estudiantes[0]->carnet == $estudiante->carnet){
                        $card.='<h5 class="card-header"><b>'.strtoupper($estudiante->carnet).'</b> - '.$estudiante->nombre.' <span class="badge badge-info">LIDER</span> </h5>';
                }else{
                        $card.='<h5 class="card-header"><b>'.strtoupper($estudiante->carnet).'</b> - '.$estudiante->nombre.'</h5>';
                }
                $card.='<div class="card-body">';
                $card.='<table>
                            <tr>
                                <td>
                                    <h5 class="card-title">Estado</h5>
                                    <p class="card-text">Pendiente de confirmación</p><br>
                                </td>
                                <td>';
                 if ($miCarnet == $estudiante->carnet) {
                    $card.='
                                 <button type="button" class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                </button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-danger">
                                    <i class="fa fa-remove"></i>
                                </button>
                            
                           ';
                }else{
                   // $card.='<div class="row"></div>';
                }
                $card.='</td>
                            </tr>
                        </table>';  
                $card.='</div></div></div>'; 
                $cards.=$card;                  
            }
            
           return view('TrabajoGraduacion\ConformarGrupo.create',compact(['cards']));
       }else if($respuesta->errorCode == '1'){
            return view('TrabajoGraduacion\ConformarGrupo.create');
       }else{
            return view('TrabajoGraduacion\ConformarGrupo.create');
       }
    	//return view('TrabajoGraduacion\ConformarGrupo.create',compact(['respuesta']));
       return $cards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $xmlRequest="";
        $xmlRequest.="<carnets>";
        foreach ($request['estudiantes'] as  $estudiante) {
            $xmlRequest.="<carnet>";
            $xmlRequest.=$estudiante;
            $xmlRequest.="</carnet>";
        }
        $xmlRequest.="</carnets>";
        $estudiante = new gen_EstudianteModel();
        $respuesta = $estudiante->conformarGrupoSp($xmlRequest);
        if ($respuesta[0]->resultado == '0' ) {
             //return "Grupo de trabajo de gracuación creado exitosamente"; 
             return redirect()->route('grupo.create');
        }
    
        return $respuesta; 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $usuario = new gen_UsuarioModel();
       $test = $usuario->testSp();
       return var_dump($test);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario=gen_UsuarioModel::find($id);
        $user=User::find($id);
        $roles=$user->getRoles();
        return view('usuario.edit',compact(['usuario','roles']));
       // $perfiles =tbl_perfil::lists('nombrePerfil', 'idPerfil');
    
        //return view('usuario.edit',compact(['usuario','perfiles']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuario=gen_UsuarioModel::find($id);
        $usuario->fill($request->all()); 
        $usuario->save();
        Session::flash('message','Usuario Modificado correctamente!');
        return Redirect::to('/usuario');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        gen_UsuarioModel::destroy($id);
        Session::flash('message','Usuario Eliminado Correctamente!');
        return Redirect::to('/usuario');
    }

    public function getAlumno(Request $request) 
    {
        if ($request->ajax()) {
           $estudiante=gen_EstudianteModel::where('carnet_gen_est', '=',$request['carnet'])->get();
           
           if (sizeof($estudiante) == 0){
            return response()->json(['errorCode'=>1,'errorMessage'=>'No se encontró ningún Alumno con ese Carnet','msg'=>""]);
           }else{
             return response()->json(['errorCode'=>0,'errorMessage'=>'Alumno agregado a grupo de Trabajo de Graduación','msg'=>$estudiante]);
           }
           
        }
       
    }
    public function confirmarGrupo(Request $request) 
    {
        if ($request->ajax()) {
           /*$estudiante=gen_EstudianteModel::where('carnet_estudiante', '=',$request['carnet'])->get();
           
           if (sizeof($estudiante) == 0){
            return response()->json(['errorCode'=>1,'errorMessage'=>'No se encontró ningún alumno con ese Carnet','msg'=>""]);
           }else{
             return response()->json(['errorCode'=>0,'errorMessage'=>'Alumno agregado a grupo de Trabajo de Graduación','msg'=>$estudiante]);
           }*/
           
        }
       
    }
     public function verificarGrupo(Request $request) {
        if ($request->ajax()) {
            $estudiante = new gen_EstudianteModel();
            $respuesta = $estudiante->getGrupoCarnet($request['carnet']);
            return $respuesta; 
        }
    }
     
}
