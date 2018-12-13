<?php

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
Route::get('cierreTDG/{idGrupo}', 'TrabajoGraduacion\TrabajoDeGraduacionController@createCierreGrupo')->name('cierreTDG');


//Reportes
Route::get('reportesTDG', 'TrabajoGraduacion\ReportesController@index')->name('reportesTDG');
Route::get('reportesTDG/R1', 'TrabajoGraduacion\ReportesController@r1')->name('R1');
Route::get('reportesTDG/R2', 'TrabajoGraduacion\ReportesController@r2')->name('R2');

//Documentos de trabajo de graduación
Route::get('nuevoDocumento/{idEtapa}/{idTipoDoc?}', 'TrabajoGraduacion\DocumentoController@createDocumento')->name('nuevoDocumento');
Route::get('editDocumento/{idEtapa}/{idDocumento}/{idTipoDoc?}', 'TrabajoGraduacion\DocumentoController@editDocumento')->name('editDocumento');
Route::resource('documento', 'TrabajoGraduacion\DocumentoController');
Route::post('downloadDocumento', 'TrabajoGraduacion\DocumentoController@downloadDocumento')->name('downloadDocumento');
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


//------------------------------------------------------------------------------------------------------------------------

//------------------GESTION DOCENTE----------------------------------------------------------------
Route::get('pruebaGestionDocente', 'GestionDocenteController@index')->name('pruebaGestionDocente');
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

//------------------------------------------------------------------------------------------------------------------------
//------------------UESPLAY--------------------------------------------------------------------------------------
Route::post('storeUsersUplay', 'gen_UsuarioController@storeUsuariosUesplay')->name('storeUsersUplay');
Route::get('cargarUsuariosUesplay', 'gen_UsuarioController@createUsuariosUesPlay')->name('cargarUsuariosUesplay');
//------------------------------------------------------------------------------------------------------------------------

//------------------------------------CATÁLOGOS-------------------------------------------------------------------
// Catálogo Categoría Trabajo de Graduación
Route::resource ('categoriaTDG','cat_ctg_tra_categoria_trabajo_graduacionController');
Route::resource ('tipoDocumento','cat_tpo_doc_tipo_documentoController');
Route::resource ('tipoTrabajo','cat_tpo_tra_gra_tipo_trabajo_graduacionController');
Route::resource ('etapaEvaluativa','cat_eta_eva_etapa_evalutativaController');
Route::resource ('catEstado','cat_eta_eva_etapa_evalutativaController');


