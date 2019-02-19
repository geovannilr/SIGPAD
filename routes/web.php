<?php
date_default_timezone_set('America/Guatemala');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

//HOME
Route::get('/', 'FrontController@index')->name('inicio');

Route::resource('login', 'LogController');

//USUARIO
Route::resource('usuario', 'gen_UsuarioController');
Route::get('cargarUsuarios', 'gen_UsuarioController@createUsuarios')->name('cargarUsuarios');
Route::post('guardarUsuarios', 'gen_UsuarioController@storeUsuarios')->name('guardarUsuarios');
Route::post('plantillaUsuarioSigpad','gen_UsuarioController@downloadPlantillaSigpad')->name('plantillaUsuarioSigpad');

//LOGIN
Route::get('/logout', 'LogController@logout')->name('LogOut');
Route::get('/login', function () {
	return view('login');
})->name('login');

//Rol
Route::resource('rol', 'RolController');

//PERMISOS
Route::resource('permiso', 'PermissionController');
//------------------TRABAJO DE GRADUACIÓN-----------------------------------------------------------
//CONFORMAR GRUPO
Route::resource('grupo', 'TrabajoGraduacion\ConformarGrupoController');
Route::post('getAlumno', 'TrabajoGraduacion\ConformarGrupoController@getAlumno');
Route::post('verificarGrupo', 'TrabajoGraduacion\ConformarGrupoController@verificarGrupo');
Route::post('confirmarGrupo', 'TrabajoGraduacion\ConformarGrupoController@confirmarGrupo');
Route::post('enviarGrupo', 'TrabajoGraduacion\ConformarGrupoController@enviarGrupo')->name('enviarGrupo');
Route::post('aprobarGrupo', 'TrabajoGraduacion\ConformarGrupoController@aprobarGrupo')->name('aprobarGrupo');
Route::get('editRolGrupo/{idGrupo}', 'TrabajoGraduacion\ConformarGrupoController@editRolGrupo')->name('editRolGrupo');
Route::post('updateRolMiembro', 'TrabajoGraduacion\ConformarGrupoController@updateRolMiembro')->name('updateRolMiembro');
Route::post('verGrupo', 'TrabajoGraduacion\ConformarGrupoController@verGrupo')->name('verGrupo'); //EJRG edit
Route::delete('deleteRelacion', 'TrabajoGraduacion\ConformarGrupoController@deleteRelacion')->name('delRelacion'); //EJRG edit
Route::get('estSinGrupo/{anio}', 'TrabajoGraduacion\ConformarGrupoController@estSinGrupo')->name('estSinGrupo'); //EJRG edit
Route::post('addAlumno', 'TrabajoGraduacion\ConformarGrupoController@addAlumno')->name('addAlumno'); //EJRG edit

//PrePerfil
Route::resource('prePerfil', 'TrabajoGraduacion\PrePerfilController');
Route::post('downloadPrePerfil', 'TrabajoGraduacion\PrePerfilController@downloadPrePerfil')->name('downloadPrePerfil');
Route::post('aprobarPreperfil', 'TrabajoGraduacion\PrePerfilController@aprobarPreperfil')->name('aprobarPreperfil');
Route::post('rechazarPrePerfil', 'TrabajoGraduacion\PrePerfilController@rechazarPrePerfil')->name('rechazarPrePerfil');
Route::get('indexPrePerfil/{id}', 'TrabajoGraduacion\PrePerfilController@indexPrePerfil')->name('indexPrePerfil');

//Tribunal Evaluador
Route::get('getTribunalData/{id}', 'TrabajoGraduacion\PrePerfilController@getTribunalData')->name('getTribunalData');
Route::get('verTribunal/{id}', 'TrabajoGraduacion\PrePerfilController@verTribunal')->name('verTribunal');
Route::get('dcnDisp/{id}', 'TrabajoGraduacion\PrePerfilController@docentesDisponibles')->name('dcnDisp');
Route::get('rolesDisp/{id}', 'TrabajoGraduacion\PrePerfilController@rolesDisponibles')->name('rolesDisp');
Route::post('asignDcnTrib', 'TrabajoGraduacion\PrePerfilController@asignarDocenteTribunal')->name('asignDcnTrib');
Route::post('delRelTrib', 'TrabajoGraduacion\PrePerfilController@deleteDocenteTribunal')->name('delRelTrib');

//Perfil
Route::resource('perfil', 'TrabajoGraduacion\PerfilController');
Route::post('downloadPerfil', 'TrabajoGraduacion\PerfilController@downloadPerfil')->name('downloadPerfil');
Route::post('downloadPerfilResumen', 'TrabajoGraduacion\PerfilController@downloadPerfilResumen')->name('downloadPerfilResumen');
Route::post('aprobarPerfil', 'TrabajoGraduacion\PerfilController@aprobarPerfil')->name('aprobarPerfil');
Route::post('rechazarPerfil', 'TrabajoGraduacion\PerfilController@rechazarPerfil')->name('rechazarPerfil');
Route::get('indexPerfil/{id}', 'TrabajoGraduacion\PerfilController@indexPerfil')->name('indexPerfil');

//Trabajo de graduacion
Route::get('dashboard/', 'TrabajoGraduacion\TrabajoDeGraduacionController@index')->name('dashboard'); //ALUMNO
Route::get('dashboardGrupo/{idGrupo}', 'TrabajoGraduacion\TrabajoDeGraduacionController@dashboardGrupo')->name('dashboardGrupo'); //DOCENTE ASESOR
Route::get('listadoGrupos/', 'TrabajoGraduacion\TrabajoDeGraduacionController@dashboardIndex')->name('listadoGrupos'); //DOCENTE ASESOR
Route::resource('etapaEvaluativa', 'TrabajoGraduacion\EtapaEvaluativaController');
Route::get('detalleEtapa/{idEtapa}/{ifGrupo?}', 'TrabajoGraduacion\EtapaEvaluativaController@showEtapa')->name('detalleEtapa');
Route::post('enviarConfigEtapa', 'TrabajoGraduacion\EtapaEvaluativaController@configurarEtapa')->name('enviarConfigEtapa');
Route::get('createNotas/{idEtapa}', 'TrabajoGraduacion\EtapaEvaluativaController@createNotas')->name('createNotas');
Route::post('enviarNotas', 'TrabajoGraduacion\EtapaEvaluativaController@storeNotas')->name('enviarNotas');
Route::get('cierreTDG', 'TrabajoGraduacion\TrabajoDeGraduacionController@createCierreGrupo')->name('cierreTDG');
Route::post('aprobarEtapa','TrabajoGraduacion\EtapaEvaluativaController@aprobarEtapa')->name('aprobarEtapa');
Route::post('calificaEtapa','TrabajoGraduacion\EtapaEvaluativaController@calificarEtapa')->name('calificaEtapa');
Route::post('updateNotas','TrabajoGraduacion\EtapaEvaluativaController@updateNotas')->name('updateNotas');
Route::post('storeCierreTDG', 'TrabajoGraduacion\TrabajoDeGraduacionController@storeCierreGrupo')->name('storeCierreTDG');
Route::get('dataAprbEta/{ifGrupo?}/{idEtapa}','TrabajoGraduacion\EtapaEvaluativaController@dataAprbEta')->name('dataAprbEta');
Route::post('plantillaNotasVariable','TrabajoGraduacion\EtapaEvaluativaController@downloadPlantillaNotasVariable')->name('plantillaNotasVariable');
Route::post('aprobarCierreGrupo','TrabajoGraduacion\TrabajoDeGraduacionController@aprobarCierreGrupo')->name('aprobarCierreGrupo');


//Reportes
Route::get('reportesTDG', 'TrabajoGraduacion\ReportesController@index')->name('reportesTDG');
//Route::get('reportesTDG/R1', 'TrabajoGraduacion\ReportesController@r1')->name('R1');
//Route::get('reportesTDG/R2', 'TrabajoGraduacion\ReportesController@r2')->name('R2');
Route::get('testReporte', 'TrabajoGraduacion\ReportesController@test')->name('testReporte');
Route::post('reportes/tribunalPorGrupo', 'TrabajoGraduacion\ReportesController@tribunalPorGrupo')->name('reportes/tribunalPorGrupo');
Route::post('reportes/asignacionesPorDocente', 'TrabajoGraduacion\ReportesController@asignacionesPorDocente')->name('reportes/asignacionesPorDocente');
Route::post('reportes/estadoGruposEtapa', 'TrabajoGraduacion\ReportesController@estadoGruposEtapa')->name('reportes/estadoGruposEtapa');
Route::post('reportes/detalleGruposTdg', 'TrabajoGraduacion\ReportesController@detalleGruposTdg')->name('reportes/detalleGruposTdg');
Route::post('reportes/estudiantesTdg', 'TrabajoGraduacion\ReportesController@estudiantesTdg')->name('reportes/estudiantesTdg');
Route::post('reportes/consolidadoNotas', 'TrabajoGraduacion\ReportesController@consolidadoNotas')->name('reportes/consolidadoNotas');
Route::get('reportes/createTribunalPorGrupo', 'TrabajoGraduacion\ReportesController@createTribunalPorGrupo')->name('reportes/createTribunalPorGrupo');
Route::get('reportes/createAsignacionesPorDocente', 'TrabajoGraduacion\ReportesController@createAsignacionesPorDocente')->name('reportes/createAsignacionesPorDocente');
Route::get('reportes/createEstadoGruposEtapa', 'TrabajoGraduacion\ReportesController@createEstadoGruposEtapa')->name('reportes/createEstadoGruposEtapa');
Route::get('reportes/createDetalleGruposTdg', 'TrabajoGraduacion\ReportesController@createDetalleGruposTdg')->name('reportes/createDetalleGruposTdg');
Route::get('reportes/createEstudiantesTdg', 'TrabajoGraduacion\ReportesController@createEstudiantesTdg')->name('reportes/createEstudiantesTdg');
Route::get('reportes/createConsolidadoNotas', 'TrabajoGraduacion\ReportesController@createConsolidadoNotas')->name('reportes/createConsolidadoNotas');

//Reportes WORD
Route::get('reportesTDGWord', 'TrabajoGraduacion\ReportesWordController@index')->name('reportesTDGWord');
Route::get('reporteActaAprobacion', 'TrabajoGraduacion\ReportesWordController@actaAprobacion')->name('reporteActaAprobacion');
Route::get('autorizacionGrupos', 'TrabajoGraduacion\ReportesWordController@autorizacionGrupos')->name('autorizacionGrupos');


//Documentos de trabajo de graduación
Route::get('nuevoDocumento/{idEtapa}/{idTipoDoc?}', 'TrabajoGraduacion\DocumentoController@createDocumento')->name('nuevoDocumento');
Route::get('editDocumento/{idEtapa}/{idDocumento}/{idTipoDoc?}', 'TrabajoGraduacion\DocumentoController@editDocumento')->name('editDocumento');
Route::resource('documento', 'TrabajoGraduacion\DocumentoController');
Route::post('downloadDocumento', 'TrabajoGraduacion\DocumentoController@downloadDocumento')->name('downloadDocumento');

//Formatos de trabajo de graduación
Route::get('formatos', 'TrabajoGraduacion\FormatoController@index')->name('formatos');
Route::post('descargaFormatos', 'TrabajoGraduacion\FormatoController@descargaFormato')->name('descargaFormato');

//------------------------------------------------------------------------------------------------------------------------

//------------------PUBLICACIONES DE TRABAJOS DE GRADUACIÓN----------------------------------------------------------------
Route::resource('publicacion', 'Publicaciones\publicacionController');
Route::get('nuevoDocumentoPublicacion/{idPublicacion}', 'Publicaciones\publicacionController@createDocumento')->name('nuevoDocumentoPublicacion');
Route::post('storageDocPublicacion', 'Publicaciones\publicacionController@storeDocumento')->name('storageDocPublicacion');
Route::post('updateDocumentoPublicacion', 'Publicaciones\publicacionController@updateDocumento')->name('updateDocumentoPublicacion');
Route::post('deleteDocumentoPublicacion', 'Publicaciones\publicacionController@deleteDocumento')->name('deleteDocumentoPublicacion');
Route::get('editDocumentoPublicacion/{idPublicacion}/{idArchivo?}', 'Publicaciones\publicacionController@editDocumento')->name('editDocumentoPublicacion');
Route::post('downloadDocumentoPublicacion', 'Publicaciones\publicacionController@downloadDocumento')->name('downloadDocumentoPublicacion');
Route::post('buscarPublicaciones', 'Publicaciones\publicacionController@buscarPublicaciones')->name('buscarPublicaciones');
Route::get('BuscarPublicaciones', 'Publicaciones\publicacionController@searchView')->name('BuscarPublicaciones');
Route::get('cargaPublicaciones', 'Publicaciones\publicacionController@createPublicaciones')->name('createPublicaciones');
Route::post('plantillaPublicaciones','Publicaciones\publicacionController@downloadPlantillaPublicaciones')->name('plantillaPublicaciones');
Route::post('storePublicaciones','Publicaciones\publicacionController@storePublicaciones')->name('storePublicaciones');

//------------------------------------------------------------------------------------------------------------------------

//------------------GESTION DOCENTE----------------------------------------------------------------
Route::get('DashboardPerfilDocente', 'GestionDocenteController@index')->name('DashboardPerfilDocente');
Route::post('getInfoDocente', 'GestionDocenteController@getInfoDocente')->name('getInfoDocente');
Route::post('getHistorial', 'GestionDocenteController@getHistorial')->name('getHistorial');
Route::post('getExperiencia', 'GestionDocenteController@getExperiencia')->name('getExperiencia');
Route::post('getCertificaciones', 'GestionDocenteController@getCertificaciones')->name('getCertificaciones');
Route::post('getSkills', 'GestionDocenteController@getSkills')->name('getSkills');
Route::post('getGeneralInfo', 'GestionDocenteController@getGeneralInfoDocente')->name('getGeneralInfo');

Route::get('perfilDocente/{idDocente}', 'PerfilDocentePublicoController@index')->name('perfilDocente');
Route::get('TiempoCompleto/{jornada}', 'PerfilDocentePublicoController@index2')->name('listadoDocentes');
Route::get('TiempoParcial/{jornada}', 'PerfilDocentePublicoController@index2')->name('listadoDocentes');
Route::post('getListadoDocentes', 'GestionDocenteController@getListadoDocentes')->name('getListadoDocentes');
Route::get('cargarPerfilDocente', 'GestionDocenteController@create')->name('cargarPerfilDocente');
Route::post('guardarPerfilDocente', 'GestionDocenteController@store')->name('guardarPerfilDocente');
Route::post('plantillaPerfilDocente','GestionDocenteController@downloadPlantilla')->name('plantillaPerfilDocente');
Route::resource('academico', 'HistorialAcademicoController');
Route::resource('laboral', 'ExperienciaLaboralController');
Route::resource('certificacion', 'CertificacionController');
Route::resource('habilidad', 'HabilidadController');
Route::post('actualizarPerfilDocente','GestionDocenteController@actualizarPerfilDocente')->name('actualizarPerfilDocente');
Route::get('listadoDocentes', 'GestionDocenteController@listadoDocentes')->name('listadoDocentes');
Route::get('docenteEdit/{idDocente}', 'GestionDocenteController@edit')->name('docenteEdit');
Route::post('actualizarDocente','GestionDocenteController@updateDocente')->name('actualizarDocente');
Route::get('cargarActualizacionDocente', 'GestionDocenteController@createUpdateDocente')->name('cargarActualizacionDocente');
Route::post('actualizarDocenteExcel','GestionDocenteController@updateDocenteExcel')->name('actualizarDocenteExcel');
Route::post('plantillaAdministraDocente','GestionDocenteController@downloadPlantillaAdministraDocente')->name('plantillaAdministraDocente');

//------------------------------------------------------------------------------------------------------------------------
//------------------UESPLAY--------------------------------------------------------------------------------------
Route::post('storeUsersUplay', 'gen_UsuarioController@storeUsuariosUesplay')->name('storeUsersUplay');
Route::get('cargarUsuariosUesplay', 'gen_UsuarioController@createUsuariosUesPlay')->name('cargarUsuariosUesplay');
Route::post('plantillaUsuarioUesplay','gen_UsuarioController@downloadPlantillaUesplay')->name('plantillaUsuarioUesplay');
Route::post('storeUsersCatUesplay', 'gen_UsuarioController@storeUsuariosCatUesplay')->name('storeUsersCatUesplay');
Route::get('cargarUsuariosCatUesplay', 'gen_UsuarioController@createUsuariosCatUesPlay')->name('cargarUsuariosCatUesplay');
Route::post('plantillaUsuarioCategoriaUesplay','gen_UsuarioController@downloadPlantillaUesplayCategoria')->name('plantillaUsuarioCategoriaUesplay');
//------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------INCIDENCIAS---------------------------------------------------
Route::get('incidencias','TrabajoGraduacion\IncidenciasController@indexIncidencias')->name('incidencias');
Route::get('incidencias/alumnosRetirados', 'TrabajoGraduacion\IncidenciasController@getAlumnosRetirados')->name('incidencias/alumnosRetirados');
Route::post('incidencias/cambiarRetirado', 'TrabajoGraduacion\IncidenciasController@cambiarEstadoRetirado')->name('incidencias/cambiarRetirado');
//------------------------------------CATÁLOGOS-------------------------------------------------------------------
// Catálogo Categoría Trabajo de Graduación
Route::resource ('categoriaTDG','cat_ctg_tra_categoria_trabajo_graduacionController');
Route::resource ('tipoDocumento','cat_tpo_doc_tipo_documentoController');
Route::resource ('tipoTrabajo','cat_tpo_tra_gra_tipo_trabajo_graduacionController');
Route::resource ('etapaEvaluativa','cat_eta_eva_etapa_evalutativaController');
Route::resource ('catEstado','cat_sta_estadoController');
Route::resource ('tipoEstado','cat_tpo_sta_tipo_estadoController');
Route::resource ('cargoEisi','cat_car_cargo_eisiController');
Route::resource ('catIdioma','cat_idi_idiomaController');
Route::resource ('catMateria','cat_mat_materiaController');
Route::resource ('catSki','cat_ski_skillController');
Route::resource ('tipoSki','cat_tpo_ski_tipo_skillController');
Route::resource ('catTitulos','cat_tit_titulos_profesionalesController');
Route::resource ('catTpublicacion','cat_tpo_pub_tipo_publicacionController');
Route::resource ('catTcolaborador','cat_tpo_col_pub_tipo_colaboradorController');
Route::resource ('catCatalogo','gen_cat_catalogoController');
Route::resource ('pdgAspectos','pdg_asp_aspectos_evaluativosController');
Route::resource ('catCriterios','cat_cri_eva_criterio_evaluacionController');
Route::resource ('parParametros','gen_par_parametrosController');
Route::resource ('catJornada','cat_tpo_jrn_dcn_tipo_jornada_docenteController');
