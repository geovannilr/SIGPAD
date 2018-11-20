<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Monolog\Handler\ElasticSearchHandler;
use Redirect;
use Session;
use \App\cat_eta_eva_etapa_evalutativaModel;
use \App\gen_EstudianteModel;
use \App\pdg_gru_est_grupo_estudianteModel;
use \App\pdg_gru_grupoModel;
use \App\pdg_not_cri_tra_nota_criterio_trabajoModel;
use \App\pdg_ppe_pre_perfilModel;
use \App\pdg_tra_gra_trabajo_graduacionModel;
use \App\pdg_apr_eta_tra_aprobador_etapa_trabajoModel;


class EtapaEvaluativaController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$userLogin = Auth::user();
		if ($userLogin->can(['prePerfil.index'])) {
			if (Auth::user()->isRole('administrador_tdg')) {
				$prePerfil = new pdg_ppe_pre_perfilModel();
				$gruposPrePerfil = $prePerfil->getGruposPrePerfil();
				return view('TrabajoGraduacion.PrePerfil.indexPrePerfil', compact('gruposPrePerfil'));
			} elseif (Auth::user()->isRole('estudiante')) {
				$grupo = self::verificarGrupo($userLogin->user)->getData();
				$estudiantes = json_decode($grupo->msg->estudiantes);
				if ($grupo->errorCode == '0') {
					$idGrupo = $estudiantes[0]->idGrupo;
					$miGrupo = pdg_gru_grupoModel::find($idGrupo);
					if ($miGrupo->id_cat_sta == 3) {
//APROBADO
						$prePerfiles = pdg_ppe_pre_perfilModel::where('id_pdg_gru', '=', $idGrupo)->get();
						$numero = $miGrupo->numero_pdg_gru;
						$tribunal = "NA";
						$etapas = self::getEtapasEvaluativas($idGrupo);
						if (sizeof($etapas) == 0) {
							$etapas = "NA";
						}
						return view('TrabajoGraduacion.TrabajoDeGraduacion.index', compact('numero', 'estudiantes', 'tribunal', 'etapas'));
					} else {
						//EL GRUPO AUN NO HA SIDO APROBADO
						Session::flash('message-error', 'Tu grupo de trabajo de graduación aún no ha sido aprobado');
						return view('template');
					}
				} else {
					//NO HA CONFORMADO UN GRUPO
					Session::flash('message-error', 'Para poder acceder a esta opción, primero debes conformar un grupo de trabajo de graduación');
					return view('template');
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
	public function show($id) {
		$parametros = explode("G", $id);
		if (sizeof($parametros) == 2) {
			$id = $parametros[0];
			$idGrupo = $parametros[1];
			$etapa = new cat_eta_eva_etapa_evalutativaModel();
			$documentos = $etapa->getDocumentos($id, 1); //ID ETAPA, ID TRABAJO DE GRADUACIÓN QUEMADO, CAMBIAR!!
			$bodyHtml = "";
			$userLogin = Auth::user();
			$estudiante = new gen_EstudianteModel();
			$idGrupo = $estudiante->getIdGrupo($userLogin->user);
			if (sizeof($documentos) == 0) {
				// NO CONFIGURADOS LOS DOCUMENTOS QUE SE VAN A REQUERIR EN UNA ETAPA EN ESPECIFICO
				$documentos = "NA";
				$bodyHtml = '<p class="text-center">NO SE HAN REGISTRADO DOCUMENTOS ASOCIADOS A ESTA ETAPA EVALUATIVA, CONSULTE AL ADMINISTRADOR<p>';
//				$nombreEtapa = "";
//				$ponderacion = "";
			} else {
				$nombreEtapa = $documentos[0]->nombre_cat_eta_eva;
				$ponderacion = $documentos[0]->ponderacion_cat_eta_eva . '%';
				foreach ($documentos as $doc) {
					$tipoDocumento = $doc->id_cat_tpo_doc;
					$bodyHtml .= '
		    					<div class="col-sm-3">
		    						<p>Nuevo <a class="btn btn-primary" href="' . url("/") . '/nuevoDocumento/' . $id . '/' . $doc->id_cat_tpo_doc . '"><i class="fa fa-plus"></i></a></p>
	    						</div>';
					$bodyHtml .= '<h2 class="text-center">Entregables de ' . $doc->nombre_pdg_tpo_doc . '</h2>';
					$bodyHtml .= '<div class="table-responsive">';
					$bodyHtml .= '<table class="table table-hover table-striped  display" id="listTable">';
					$bodyHtml .= '<thead>
		        				<th>Nombre Archivo</th>
		        				<th>Fecha Subida</th>
		        				<th>Modificar</th>
		        				<th>Eliminar</th>
		        				<th>Descargar</th>
		        				</thead>
		        				<tbody>';
					$archivos = $etapa->getArchivos($id, $idGrupo);
					if (sizeof($archivos) != 0) {
						foreach ($archivos as $archivo) {
							if ($tipoDocumento == $archivo->id_cat_tpo_doc) {
								$bodyHtml .= '<tr>
												<td>' . $archivo->nombreArchivo . '</td>
												<td>' . $archivo->fechaSubidaArchivo . '</td>';
								if ($archivo->esArchivoActico == 1) {
									$bodyHtml .= '
																<td><a class="btn btn-primary" href="' . url("/") . '/editDocumento/' . $id . '/' . $archivo->id_pdg_doc . '/' . $doc->id_cat_tpo_doc . '"><i class="fa fa-pencil"></i></a></td>
																<td>
																	<form method="POST" action="' . url("/") . '/documento/' . $archivo->id_pdg_doc . '" class="deleteButton formPost">
																		<input name="_method" value="DELETE" type="hidden">
																		<input class="form-control" name="etapa" value="' . $id . '" type="hidden">
														 				<div class="btn-group">
																			<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
																		</div>
																	</form>
																</td>
																<td>
																	<form method="POST" action="' . url("/") . '/downloadDocumento" accept-charset="UTF-8" class ="formPost">
														 				<div class="btn-group">
														 					<input class="form-control" name="documento" value="' . $archivo->id_pdg_arc_doc . '" type="hidden">
														 					<input class="form-control" name="etapa" value="' . $id . '" type="hidden">
																			<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
																		</div>
																	</form>
																</td>
							        						</tr>';
								} else {
									$bodyHtml .= '
															<td></td>
															<td></td>
															<td>
																<form method="POST" action="' . url("/") . '/downloadDocumento" accept-charset="UTF-8" class ="formPost">
													 				<div class="btn-group">
													 					<input class="form-control" name="documento" value="' . $archivo->id_pdg_arc_doc . '" type="hidden">
													 					<input class="form-control" name="etapa" value="' . $id . '" type="hidden">
																		<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
																	</div>
																</form>
															</td>
						        						</tr>';
								}
							}
						}
					}
					$bodyHtml .= '</tbody>
								</table>
		        				</div>
		        				<hr>
		        				';

				}
			}
			return view('TrabajoGraduacion.EtapaEvaluativa.show', compact('bodyHtml', 'nombreEtapa', 'ponderacion', 'id'));
			//return $bodyHtml;
		} else {
			return view("error");
		}

	}
	public function showEtapa($idEtapa,$idGrupo) {
		//VERIFICAMOS SI LA ETAPA Y EL GRUPO SON VALIDOS
		$etaparecibida = cat_eta_eva_etapa_evalutativaModel::find($idEtapa);
		$grupoRecibido = pdg_gru_grupoModel::find($idGrupo);
		if (empty($grupoRecibido->id_pdg_gru) || empty($etaparecibida->id_cat_eta_eva)) {
			return view('error');
		}else if (Auth::user()->isRole('docente_asesor')) {
			//TRAEMOS LOS GRUPOS QUE ESTAN ASOCIADOS AL DOCENTE ASESOR
        	$grupos = session('misGrupos');
        	$pertenece = 0;
        	$misGrupos = explode(",", $grupos);
        	foreach ($misGrupos as $grupo) {
        		if ($grupo == $idGrupo) {
        			$pertenece = 1;
        		}
        	}
        	if ($pertenece == 0) {
        		return view('error');
        	}
		}
			
			$id = $idEtapa;
			$etapa = new cat_eta_eva_etapa_evalutativaModel();
			$trabajoGraduacion = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->first();
			$documentos = $etapa->getDocumentos($id, $trabajoGraduacion->id_pdg_tra_gra); //ID ETAPA, ID TRABAJO DE GRADUACIÓN QUEMADO, CAMBIAR!!
			$bodyHtml = "";
			$userLogin = Auth::user();
			$estudiante = new gen_EstudianteModel();
			//$idGrupo = $estudiante->getIdGrupo($userLogin->user);
			if (sizeof($documentos) == 0) {
				// NO CONFIGURADOS LOS DOCUMENTOS QUE SE VAN A REQUERIR EN UNA ETAPA EN ESPECIFICO
				$documentos = "NA";
				$bodyHtml = '<p class="text-center">NO SE HAN REGISTRADO DOCUMENTOS ASOCIADOS A ESTA ETAPA EVALUATIVA, CONSULTE AL ADMINISTRADOR<p>';
				$nombreEtapa = "";
				$ponderacion = "";
			} else {
				$nombreEtapa = $documentos[0]->nombre_cat_eta_eva;
				$ponderacion = $documentos[0]->ponderacion_cat_eta_eva . '%';
				foreach ($documentos as $doc) {
					$tipoDocumento = $doc->id_cat_tpo_doc;
					$bodyHtml .= '
		    					<div class="col-sm-3">
		    						<p>Nuevo <a class="btn btn-primary" href="' . url("/") . '/nuevoDocumento/' . $id . '/' . $doc->id_cat_tpo_doc . '"><i class="fa fa-plus"></i></a></p>
	    						</div>';
					$bodyHtml .= '<h2 class="text-center">Entregables de ' . $doc->nombre_pdg_tpo_doc . '</h2>';
					$bodyHtml .= '<div class="table-responsive">';
					$bodyHtml .= '<table class="table table-hover table-striped  display" id="listTable">';
					$bodyHtml .= '<thead>
		        				<th>Nombre Archivo</th>
		        				<th>Fecha Subida</th>
		        				<th>Modificar</th>
		        				<th>Eliminar</th>
		        				<th>Descargar</th>
		        				</thead>
		        				<tbody>';
					$archivos = $etapa->getArchivos($id, $idGrupo);
					if (sizeof($archivos) != 0) {
						foreach ($archivos as $archivo) {
							if ($tipoDocumento == $archivo->id_cat_tpo_doc) {
								$bodyHtml .= '<tr>
												<td>' . $archivo->nombreArchivo . '</td>
												<td>' . $archivo->fechaSubidaArchivo . '</td>';
								if ($archivo->esArchivoActico == 1) {
									$bodyHtml .= '
																<td><a class="btn btn-primary" href="' . url("/") . '/editDocumento/' . $id . '/' . $archivo->id_pdg_doc . '/' . $doc->id_cat_tpo_doc . '"><i class="fa fa-pencil"></i></a></td>
																<td>
																	<form method="POST" action="' . url("/") . '/documento/' . $archivo->id_pdg_doc . '" class="deleteButton formPost">
																		<input name="_method" value="DELETE" type="hidden">
																		<input class="form-control" name="etapa" value="' . $id . '" type="hidden">
														 				<div class="btn-group">
																			<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
																		</div>
																	</form>
																</td>
																<td>
																	<form method="POST" action="' . url("/") . '/downloadDocumento" accept-charset="UTF-8" class ="formPost">
														 				<div class="btn-group">
														 					<input class="form-control" name="documento" value="' . $archivo->id_pdg_arc_doc . '" type="hidden">
														 					<input class="form-control" name="etapa" value="' . $id . '" type="hidden">
																			<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
																		</div>
																	</form>
																</td>
							        						</tr>';
								} else {
									$bodyHtml .= '
															<td></td>
															<td></td>
															<td>
																<form method="POST" action="' . url("/") . '/downloadDocumento" accept-charset="UTF-8" class ="formPost">
													 				<div class="btn-group">
													 					<input class="form-control" name="documento" value="' . $archivo->id_pdg_arc_doc . '" type="hidden">
													 					<input class="form-control" name="etapa" value="' . $id . '" type="hidden">
																		<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
																	</div>
																</form>
															</td>
						        						</tr>';
								}
							}
						}
					}
					$bodyHtml .= '</tbody>
								</table>
		        				</div>
		        				<hr>
		        				';

				}
			}
			$actual =  self::getIdEtapaActual($trabajoGraduacion->id_pdg_tra_gra);
			$configura = ($trabajoGraduacion->id_cat_tpo_tra_gra!=1);//validar si ya se configuró, consultando la tabla de EntregablesxEtapa
			return view('TrabajoGraduacion.EtapaEvaluativa.show', compact('bodyHtml', 'nombreEtapa', 'ponderacion', 'id','idGrupo','actual','configura'));
			//return $bodyHtml;
		

	}
	
	public function configurarEtapa(Request $request) {
		$trabajoGraduacion = new pdg_tra_gra_trabajo_graduacionModel();
		$trabajoGraduacion = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$request['idGrupo'])->first();
		$resultado = $trabajoGraduacion->updateEntregablesEtapaGrupo($request["cantidadEntregables"],$trabajoGraduacion->id_pdg_tra_gra, $request["idEtapa"]);
		Session::flash('message', 'Entregables por Etapa Modificado con éxito!');
		return Redirect::to('detalleEtapa/' . $request['idEtapa']."/".$request['idGrupo']);
	}
	public function createNotas($idEtapa) {
		//VERIFICAMOS SI EXISTEN EN LA BASE DE DATOS ESOS ID
		$etapa = cat_eta_eva_etapa_evalutativaModel::find($idEtapa);
		if (empty($etapa)) {
			return view("error");
		} else {
			//LOS PARAMETROS VIENEN CORRECTAMENTE
			return view('TrabajoGraduacion.NotaEtapaEvaluativa.create', compact('etapa'));
		}
	}
	public function storeNotas(Request $request) {
		$validatedData = $request->validate([
			'documentoNotas' => 'required',
		]);
		$idEtapa = $request["etapa"];
		$etapa=cat_eta_eva_etapa_evalutativaModel::find($idEtapa);
		$data = Excel::load($request->file('documentoNotas'), function ($reader) {
			$reader->setSelectedSheetIndices(array(1));
		})->get();
		$notas = $data->toArray();
		$primerAlumno = $notas[0];
		$cortarNombreGrupo = explode("-",$primerAlumno["grupo"]);
		$nombreCambiado ="NA";
		if (sizeof($cortarNombreGrupo)!=2) {
			return "El formato escrito del grupo es incorrecto Ej:2018-01, 2018-14";
		}else{
					$nombreCambiado = $cortarNombreGrupo[1]."-".$cortarNombreGrupo[0];

		}
		$bodyHtml = '';
		foreach ($notas as $row) {
			//VERIFICAMOS QUE VENGAN TODOS LOS CAMPOS EN LA FILA
			if ($row["carnet"]!="" && $row["nota"]!="" && $row["grupo"]!="" ) {
				$alumno = $row; 
				$estudianteModel = new gen_EstudianteModel();
				$idGrupo = $estudianteModel->getIdGrupo(strtolower($alumno["carnet"]));
				$idGenEstudiante = "NA";
				$nombreEstudiante = "N/A";
				$carnet = strtolower($row["carnet"]);
				$estudiantes = gen_EstudianteModel::where("carnet_gen_est", "=", $carnet)->get();
				foreach ($estudiantes as $estudiante) {
					$idGenEstudiante = $estudiante->id_gen_est;
					$nombreEstudiante=  $estudiante->nombre_gen_est;
				}
				$trabajosGraduacion = pdg_tra_gra_trabajo_graduacionModel::where("id_pdg_gru", "=", $idGrupo)->get();
				$grupo = pdg_gru_grupoModel::find($idGrupo);//obtenemos el grupo
				if (sizeof($estudiantes)==0) {
					$bodyHtml .= '<tr>';
					$bodyHtml .= '<td>' . $alumno ["carnet"] . '</td>';
					$bodyHtml .= '<td>' . $nombreEstudiante . '</td>';
					$bodyHtml .= '<td>' . $alumno ["nota"] . '</td>';
					$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
					$bodyHtml .= '<td>El alumno no se encuentra registrado o no existe.</td>';
					$bodyHtml .= '</tr>';
				}
				else if (sizeof($grupo)!=0) {
					$nombreGrupo = $grupo->numero_pdg_gru;
					if ($grupo->numero_pdg_gru !=$nombreCambiado) {
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $alumno ["carnet"] . '</td>';
						$bodyHtml .= '<td>' . $nombreEstudiante . '</td>';
						$bodyHtml .= '<td>' . $alumno ["nota"] . '</td>';
						$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
						$bodyHtml .= '<td>El alumno no coincide con el grupo de trabajo de graduación ingresado.</td>';
						$bodyHtml .= '</tr>';
					}else{
						$idTraGra = "NA";
						foreach ($trabajosGraduacion as $trabajo) {
							$idTraGra = $trabajo->id_pdg_tra_gra;
						}
						
						$estudiantesGrupo = pdg_gru_est_grupo_estudianteModel::where("id_gen_est", "=", $idGenEstudiante)->get();
						$idEstGrupo = "NA";
						foreach ($estudiantesGrupo as $est) {
							$idEstGrupo = $est->id_pdg_gru_est;
						}
						if ($idEstGrupo != "NA") {
							//$nota = pdg_not_cri_tra_nota_criterio_trabajoModel::where("id_pdg_gru_est","=",$idEstGrupo)->get();
							$evaluado= pdg_not_cri_tra_nota_criterio_trabajoModel::verificarNotaAlumno($idEtapa,$idEstGrupo); // NOS TRAE EL CAMPO SI YA ESTA EVALUADO
							$nota=pdg_not_cri_tra_nota_criterio_trabajoModel::find($evaluado->idNota);
							$nota->nota_pdg_not_cri_tra = $alumno ["nota"];
							$nota->evaluado_pdg_not_cri_tra = 1; //YA SE LLENO
							$nota->save();
							if ($evaluado->yaEvaluado==1) {

								$bodyHtml .= '<tr>';
								$bodyHtml .= '<td>' . $alumno ["carnet"] . '</td>';
								$bodyHtml .= '<td>' . $nombreEstudiante . '</td>';
								$bodyHtml .= '<td>' . $alumno ["nota"] . '</td>';
								$bodyHtml .= '<td><span class="badge badge-warning">Actualizado</span></td>';
								$bodyHtml .= '<td>La nota se actualizó exitosamente.</td>';
								$bodyHtml .= '</tr>';
							}else{
								/*$lastId = pdg_not_cri_tra_nota_criterio_trabajoModel::create
								([
								'nota_pdg_not_cri_tra' => $row["nota"],
								'id_cat_cri_eva' => 1,
								'id_pdg_tra_gra' => $idTraGra,
								'id_pdg_gru_est' => $idEstGrupo,
								]);*/

								$bodyHtml .= '<tr>';
								$bodyHtml .= '<td>' . $alumno ["carnet"] . '</td>';
								$bodyHtml .= '<td>' . $nombreEstudiante . '</td>';
								$bodyHtml .= '<td>' . $alumno ["nota"] . '</td>';
								$bodyHtml .= '<td><span class="badge badge-success">OK</span></td>';
								$bodyHtml .= '<td>La nota se ingresó exitosamente.</td>';
								$bodyHtml .= '</tr>';
							}
							
						}else{
							$bodyHtml .= '<tr>';
							$bodyHtml .= '<td>' . $alumno ["carnet"] . '</td>';
							$bodyHtml .= '<td>' . $nombreEstudiante . '</td>';
							$bodyHtml .= '<td>' . $alumno ["nota"] . '</td>';
							$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
							$bodyHtml .= '<td>El alumno no coincide con el grupo de trabajo de graduación ingresado.</td>';
							$bodyHtml .= '</tr>';
						}
						/*echo "Consolidado";
						echo "<br>";
						echo "Carnet: " . $row["carnet"];
						echo "<br>";
						echo "Nota: " . $row["nota"];
						echo "<br>";
						echo "TrabajoGraduacion: " . $idTraGra;
						echo "<br>";
						echo "Estudiante: " . $idEstGrupo;
						echo "<br>";
						echo "<br>";*/
					}
				}else{		$nombreGrupo = "N/A";
							$bodyHtml .= '<tr>';
							$bodyHtml .= '<td>' . $alumno ["carnet"] . '</td>';
							$bodyHtml .= '<td>' . $nombreEstudiante . '</td>';
							$bodyHtml .= '<td>' . $alumno ["nota"] . '</td>';
							$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
							$bodyHtml .= '<td>El alumno no se encuentra registrado en ningún grupo de  trabajo de graduación.</td>';
							$bodyHtml .= '</tr>';
				}
				
				
			}
			
		}
		return view('TrabajoGraduacion.NotaEtapaEvaluativa.index', compact('bodyHtml','etapa',"nombreGrupo",'idGrupo'));

	}
	public function verificarGrupo($carnet) {
		$estudiante = new gen_EstudianteModel();
		$respuesta = $estudiante->getGrupoCarnet($carnet);
		return $respuesta;
	}
    public function calificarEtapa(Request $request){
        $idGrupo = $request['grupo'];
        $idEtaEva = $request['etapa'];

        $criterios = pdg_not_cri_tra_nota_criterio_trabajoModel::getCriteriosEtapa($idGrupo,$idEtaEva);
        $notas = pdg_not_cri_tra_nota_criterio_trabajoModel::getNotasEtapa($idGrupo,$idEtaEva);
        $grupo = pdg_gru_grupoModel::find($idGrupo);
        $etapa = cat_eta_eva_etapa_evalutativaModel::find($idEtaEva);
        $subida = ($grupo->relacion_gru_tdg->id_cat_tpo_tra_gra!=1);  // 1-Tipo clásico de trabajo de graduación 2-Variable
        //$subida = true; PARA PRUEBAS CON GRUPOS QUE NO SEAN DE TIPO VARIABLE!!!!!!!!!!!!
        if (empty($grupo->id_pdg_gru) || empty($etapa->id_cat_eta_eva)){
            return view('error');
        }
        return view('TrabajoGraduacion.NotaEtapaEvaluativa.list',
            compact('criterios', 'notas', 'grupo','etapa', 'subida')
        );
    }
    public function updateNotas(Request $request){
        $errorCode = -1;
        $errorMessage = "No se procesaron los datos";
        try{
            $idGru = $request['idGru'];
            $idEtaEva = $request['idEtaEva'];
            $notas = $request['notas'];
            $errorCode = pdg_not_cri_tra_nota_criterio_trabajoModel::bulkUpdateNotas($idGru,$idEtaEva,$notas);
            $errorMessage = "¡Notas del grupo GRUPO-04 guardadas éxitosamente!";
            $info = $notas;
        }catch (Exception $exception){
            $info = $exception->getMessage();
            $errorCode = 1;
            $errorMessage = "Su solicitud no pudo ser procesada, intente más tarde.";
        }
        return response()->json(['errorCode'=>$errorCode,'errorMessage'=>$errorMessage,'info'=>$info]);
    }
	
	public function aprobarEtapa(Request $request){
        $idGrupo=$request['idGrupo'];
        $idEtapa =$request['idEtapa'];

        $msgType = "error";

    	$trabajoGraduacion = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->first();
        $idEtaActual = self::getIdEtapaActual($trabajoGraduacion->id_pdg_tra_gra);

        if (intval($idEtapa)==$idEtaActual){
            $resultadoAvance = pdg_tra_gra_trabajo_graduacionModel::avanzarProceso($trabajoGraduacion->id_pdg_tra_gra);
            if($resultadoAvance->p_result == 0){
                $msgType = "success";
                $msg = "Etapa aprobada éxitosamente!";
            }else{
                $msg = "Error al intentar aprobar la etapa";
            }
        }else{
            $msg = "No puede aprobar esta etapa, no es la etapa actual.";
        }
        Session::flash($msgType,$msg);
        return Redirect::to('detalleEtapa/'.$idEtapa.'/'.$idGrupo);
    }

    public function dataAprbEta($idGrupo,$idEtapa){
        $traGra = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru', '=',$idGrupo)->first();

        $etapa = pdg_apr_eta_tra_aprobador_etapa_trabajoModel::getEtapa($traGra->id_pdg_tra_gra,pdg_apr_eta_tra_aprobador_etapa_trabajoModel::T_BUSQ_ACTUAL);
        $etapaSig = pdg_apr_eta_tra_aprobador_etapa_trabajoModel::getEtapa($traGra->id_pdg_tra_gra,pdg_apr_eta_tra_aprobador_etapa_trabajoModel::T_BUSQ_SIGUIENTE);

        $coinciden = false;

        if(!empty($etapa->id_cat_eta_eva)){
            if($etapa->id_cat_eta_eva!=999){//Valor etapa de cierre
                if($etapaSig!=null){
                    $coinciden = (intval($idEtapa) === $etapa->id_cat_eta_eva);
                    $message = $coinciden ? "Aprobar esta etapa bloqueará la subida de archivos al grupo y, por consiguiente, habilitará la subida de archivos, calficaciones y configuraciones de la siguiente etapa: <b>".$etapaSig->nombre_cat_eta_eva.".</b><br>¿Desea continuar?"
                        : "No puede aprobar esta etapa.<br>El proceso se encuentra en <b>".$etapa->nombre_cat_eta_eva."</b>, verifique el estado de dicha etapa primero.";
                }else{
                    $coinciden = true;
                    $message =  "Aprobar esta etapa habilitará la etapa de <b>Cierre de Trabajo de Graduación</b>, los estudiantes podrán cargar los documentos requeridos para la Biblioteca de Trabajos de Graduación<br>".
                        "<i>Tenga en cuenta que tiene que calificar todas las etapas para dar por finalizado el Proceso de Trabajo de Graduación.</i>";
                }
            }else{
                $message = "El proceso se encuentra en etapa de <b>Cierre de Trabajo de Graduación</b>, consulte la opción en el Dashboard";
            }
        }else{
            $message = "Esta acción está deshabilitada debido el avance actual del Trabajo de Graduación.";
        }
	    return response()->json(['success'=>$coinciden,'msg'=>$message]);
    }

    public function getIdEtapaActual($idTraGra){
        $etapaActual = pdg_apr_eta_tra_aprobador_etapa_trabajoModel::getEtapa($idTraGra,pdg_apr_eta_tra_aprobador_etapa_trabajoModel::T_BUSQ_ACTUAL);
        $actual = empty($etapaActual->id_cat_eta_eva) ? 0 : $etapaActual->id_cat_eta_eva;
        return $actual;
    }
}
