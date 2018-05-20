<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use \App\gen_UsuarioModel;
use \App\gen_EstudianteModel;
use \App\pdg_gru_est_grupo_estudianteModel;
use \App\pdg_gru_grupoModel;
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
        $grupo = new pdg_gru_grupoModel();
        $grupos= $grupo->getGrupos();
        //return var_dump($grupos);
       return view('TrabajoGraduacion\ConformarGrupo.index',compact(['grupos']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){  
        $cards="";
        $enviado =0;
        $cantidadMinima = 4;
       $estudiante = new gen_EstudianteModel();
       $miCarnet=Auth::user()->user;
       $respuesta =$estudiante->getGrupoCarnet($miCarnet)->getData();  //getdata PARA CAMBIAR LOS VALORES DEL JSON DE PUBLICOS A PRIVADOS
       if ($respuesta->errorCode == '0') {
            $estudiantes=json_decode($respuesta->msg->estudiantes);//decode string to json
            $cantidadEstudiantes = sizeof($estudiantes);
            $contadorAceptado = 0;
            $idGrupo = -1;
            foreach ($estudiantes as $estudiante ) { 
                $idGrupo = $estudiante->idGrupo;
                $card='';
                $card.='<div class="col-sm-4" id="card'.$estudiante->carnet.'">';
                $card.='<div class="card border-primary mb-3">';
                if ( $estudiante->lider == 1){
                        $card.='<h5 class="card-header"><b>'.strtoupper($estudiante->carnet).'</b> - '.$estudiante->nombre.' <span class="badge badge-info">LIDER</span> </h5>';
                }else{
                        $card.='<h5 class="card-header"><b>'.strtoupper($estudiante->carnet).'</b> - '.$estudiante->nombre.'</h5>';
                }
                $card.='<div class="card-body">';
                $card.='<table>
                            <tr>
                                ';
                 if ($miCarnet == $estudiante->carnet && $estudiante->estado == "5" ) { //si soy el líder automaticamente ya acepte, estado 5 no aceptado , estado 6 aceptado
                    $card.='    <td>
                                    <h5 class="card-title">Estado</h5>
                                    <p class="card-text">Pendiente de confirmación</p><br>
                                </td>
                                <td>
                                 <button id="btnConfirmar" type="button" data-id="'.$estudiante->id.'" class="btn btn-success">
                                    <i class="fa fa-check" ></i>
                                </button>&nbsp;&nbsp;
                                <button id="btnDenegar" type="button"  data-id="'.$estudiante->id.'" class="btn btn-danger">
                                    <i class="fa fa-remove"></i>
                                </button>
                            
                           ';
                }else if($estudiante->estado == "6"){
                    $contadorAceptado+=1;
                    $card.='<td>
                                    <h5 class="card-title">Estado</h5>
                                    <p class="badge badge-success card-text">Confirmado</p><br>
                                </td>
                                <td>';
                }else{
                    $card.='<td>
                                    <h5 class="card-title">Estado</h5>
                                    <p class="badge badge-secondary card-text">Pendiente de confirmación</p><br>
                                </td>
                                <td>';
                }
                $card.='</td>
                            </tr>
                        </table>';  
                $card.='</div></div></div>'; 
                $cards.=$card;                  
            }
            if ($cantidadEstudiantes == $contadorAceptado) {
                $enviado = 1 ; //EL GRUPO YA ESTA LISTO PARA SER ENVIADO
            }
           return view('TrabajoGraduacion\ConformarGrupo.create',compact(['cards','enviado','cantidadMinima','cantidadEstudiantes','idGrupo']));
       }else if($respuesta->errorCode == '1'){
            return view('TrabajoGraduacion\ConformarGrupo.create',compact(['cantidadMinima']));
       }else{
            return view('TrabajoGraduacion\ConformarGrupo.create',compact(['cantidadMinima']));
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
            Session::flash('message','Ocurrió un problema al momento de enviar el grupo de trabajo de graduación!');
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
    public function show($id){
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

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
   
    public function verificarGrupo(Request $request) {
        if ($request->ajax()) {
            $estudiante = new gen_EstudianteModel();
            $respuesta = $estudiante->getGrupoCarnet($request['carnet']);
            return $respuesta; 
        }
    }
     
    public function confirmarGrupo(Request $request) { // Confirmar grupo de trabajo de graduación por parte del alumno
       if ($request->ajax()){
            try{
                  $estudianteGrupo= new pdg_gru_est_grupo_estudianteModel(); //mandamos id de estudiante
                  $resultado= $estudianteGrupo ->cambiarEstadoGrupo($request['id'],$request['aceptar']);
                  if ($resultado == "0"){
                         return response()->json(['errorCode'=>0,'errorMessage'=>'Has confirmado que perteceneces a este grupo de trabajo de graduación','msg'=>$resultado]);
                  }else
                  {
                     return response()->json(['errorCode'=>2,'errorMessage'=>'Error al modificar estado de alumno en el grupo de trabajo de graduación','msg'=>$resultado]);

                  }
                  
                }
            catch(Exception $e){
               return response()->json(['errorCode'=>1,'errorMessage'=>'Ha ocurrido un error al procesar su petición de grupo de trabajo de graduación','msg'=>$e]);
            }
       } 
    }
    public function enviarGrupo(Request $request){
        try {
            $grupo=pdg_gru_grupoModel::find($request['idGrupo']);
            $grupo->id_cat_sta='7'; //ESTADO ENVIADO PARA APROBACION
            $filasAfetadas=$grupo->save();
            Session::flash('message','Se envió el grupo de trabajo de graduación para su Aprobación!');
            return redirect()->route('grupo.create');
        } catch (Exception $e) {
           Session::flash('message','Ocurrió un problema al momento de enviar el grupo de trabajo de graduación!');
            return redirect()->route('grupo.create');
        }
       
    }
  
}
