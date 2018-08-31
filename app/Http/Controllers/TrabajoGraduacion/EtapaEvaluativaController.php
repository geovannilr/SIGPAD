<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Auth;
use \App\pdg_ppe_pre_perfilModel;
use \App\gen_EstudianteModel;
use \App\pdg_gru_grupoModel;
use \App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
use \App\cat_eta_eva_etapa_evalutativaModel;
use Maatwebsite\Excel\Facades\Excel;

class EtapaEvaluativaController extends Controller{
	public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $userLogin=Auth::user();
        if ($userLogin->can(['prePerfil.index'])) {
            if (Auth::user()->isRole('administrador_tdg')){
                 $prePerfil = new  pdg_ppe_pre_perfilModel();
                 $gruposPrePerfil=$prePerfil->getGruposPrePerfil();
                return view('TrabajoGraduacion.PrePerfil.indexPrePerfil',compact('gruposPrePerfil'));
            }elseif (Auth::user()->isRole('estudiante')) {
                $grupo=self::verificarGrupo($userLogin->user)->getData();
                $estudiantes=json_decode($grupo->msg->estudiantes);
                if ($grupo->errorCode == '0'){
                    $idGrupo = $estudiantes[0]->idGrupo;
                    $miGrupo = pdg_gru_grupoModel::find($idGrupo);
                    if ($miGrupo->id_cat_sta == 3 ) {//APROBADO
                        $prePerfiles =pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=',$idGrupo)->get();
                        $numero=$miGrupo->numero_pdg_gru;
                        $tribunal ="NA";
                        $etapas=self::getEtapasEvaluativas($idGrupo);
                        if (sizeof($etapas) == 0){
                        	$etapas="NA";
                        }
                        return view('TrabajoGraduacion.TrabajoDeGraduacion.index',compact('numero','estudiantes','tribunal','etapas'));
                    }else{
                        //EL GRUPO AUN NO HA SIDO APROBADO
                    Session::flash('message-error', 'Tu grupo de trabajo de graduación aún no ha sido aprobado');
                    return  view('template');
                    }
                }else{
                    //NO HA CONFORMADO UN GRUPO
                    Session::flash('message-error', 'Para poder acceder a esta opción, primero debes conformar un grupo de trabajo de graduación');
                    return  view('template');
                }
            }
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $etapa = new cat_eta_eva_etapa_evalutativaModel();
	    $documentos = $etapa->getDocumentos($id); //ID ETAPA
	    $bodyHtml = "" ;
	    $userLogin=Auth::user();
	    $estudiante = new gen_EstudianteModel();
	    $idGrupo = $estudiante->getIdGrupo($userLogin->user);
	    if (sizeof($documentos)==0) { // NO CONFIGURADOS LOS DOCUMENTOS QUE SE VAN A REQUERIR EN UNA ETAPA EN ESPECIFICO
	    	$documentos = "NA";
	    	$bodyHtml ='<p class="text-center">NO SE HAN REGISTRADO DOCUMENTOS ASOCIADOS A ESTA ETAPA EVALUATIVA, CONSULTE AL ADMINISTRADOR<p>';
	    	$nombreEtapa="";
	    	$ponderacion = "";
	    }else{
	    	$nombreEtapa =$documentos[0]->nombre_cat_eta_eva;
	    	$ponderacion =$documentos[0]->ponderacion_cat_eta_eva.'%';
	    	foreach ($documentos as  $doc) {
	    		$tipoDocumento=$doc->id_cat_tpo_doc;
	    		$bodyHtml.=' 
	    					<div class="col-sm-3">
	    						<p>Nuevo <a class="btn btn-primary" href="'.url("/").'/nuevoDocumento/'.$id.'/'.$doc->id_cat_tpo_doc.'"><i class="fa fa-plus"></i></a></p> 
    						</div>';
		    	$bodyHtml.='<h2 class="text-center">Entregables de '.$doc->nombre_pdg_tpo_doc.'</h2>';
		    	$bodyHtml.= '<div class="table-responsive">';
	        	$bodyHtml.='<table class="table table-hover table-striped  display" id="listTable">';
	        	$bodyHtml.='<thead>
	        				<th>Nombre Archivo</th>
	        				<th>Fecha Subida</th>
	        				<th>Modificar</th>
	        				<th>Eliminar</th>
	        				<th>Descargar</th>
	        				</thead>
	        				<tbody>';
	        	$archivos=$etapa->getArchivos($id,$idGrupo);
	        	if (sizeof($archivos)!=0) {
	        		foreach ($archivos as  $archivo) {
	        			if ($tipoDocumento == $archivo->id_cat_tpo_doc) {
		        				$bodyHtml.='<tr>
											<td>'.$archivo->nombreArchivo.'</td>
											<td>'.$archivo->fechaSubidaArchivo.'</td>';
											if ($archivo->esArchivoActico == 1) {
												$bodyHtml.='			
															<td><a class="btn btn-primary" href="'.url("/").'/editDocumento/'.$id.'/'.$archivo->id_pdg_doc.'/'.$doc->id_cat_tpo_doc.'"><i class="fa fa-pencil"></i></a></td>
															<td>
																<form method="POST" action="'.url("/").'/documento/'.$archivo->id_pdg_doc.'" class="deleteButton formPost">
																	<input name="_method" value="DELETE" type="hidden">
																	<input class="form-control" name="etapa" value="'.$id.'" type="hidden">
													 				<div class="btn-group">
																		<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
																	</div>
																</form>
															</td>
															<td>
																<form method="POST" action="'.url("/").'/downloadDocumento" accept-charset="UTF-8" class ="formPost">
													 				<div class="btn-group">
													 					<input class="form-control" name="documento" value="'.$archivo->id_pdg_arc_doc.'" type="hidden">
													 					<input class="form-control" name="etapa" value="'.$id.'" type="hidden">
																		<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
																	</div>
																</form>
															</td>
						        						</tr>';
											}else{
												$bodyHtml.='			
														<td></td>
														<td></td>
														<td>
															<form method="POST" action="'.url("/").'/downloadDocumento" accept-charset="UTF-8" class ="formPost">
												 				<div class="btn-group">
												 					<input class="form-control" name="documento" value="'.$archivo->id_pdg_arc_doc.'" type="hidden">
												 					<input class="form-control" name="etapa" value="'.$id.'" type="hidden">
																	<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
																</div>
															</form>
														</td>
					        						</tr>';
											}
	        			}
	        		}
	        	}
	        	$bodyHtml.='</tbody>
							</table>
	        				</div>
	        				<hr>
	        				';	

	    	}
	    }
	    return view('TrabajoGraduacion.EtapaEvaluativa.show',compact('bodyHtml','nombreEtapa','ponderacion','id'));
	    //return $bodyHtml;
    }

    public function configurarEtapa(Request $request){
    	return $request;
    }
    public function createNotas($idEtapa){
    	//VERIFICAMOS SI EXISTEN EN LA BASE DE DATOS ESOS ID
    	$etapa = cat_eta_eva_etapa_evalutativaModel::find($idEtapa);
    	if(empty($etapa) ){
    		return "LOS PARAMETROS RECIBIDOS NO SON CORRECTOS";
    	}else{ //LOS PARAMETROS VIENEN CORRECTAMENTE
    		return view('TrabajoGraduacion.NotaEtapaEvaluativa.create',compact('etapa'));
    	}
    }
     public function storeNotas(Request $request){
     	Excel::load($request->file('result-file'), function ($reader) {

            foreach ($reader->toArray() as $row) {
                dd($row);
                //User::firstOrCreate($row);
            }
        });
    	return $request;
    }
    public function verificarGrupo($carnet) {
	    $estudiante = new gen_EstudianteModel();
	    $respuesta = $estudiante->getGrupoCarnet($carnet);
	    return $respuesta;     
    }
}
