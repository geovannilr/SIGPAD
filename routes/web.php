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
Route::get('/', 'FrontController@index');

Route::resource('login','LogController');

//USUARIO
Route::resource('usuario','gen_UsuarioController');

//LOGIN
Route::get('/logout', 'LogController@logout')->name('LogOut');
Route::get('/login', function () {
    return view ('login');
})->name('login');

//Rol
Route::resource('rol','RolController');

//PERMISOS
Route::resource('permiso','PermissionController');
//------------------TRABAJO DE GRADUACIÓN-----------------------------------------------------------
	//CONFORMAR GRUPO
Route::resource('grupo','TrabajoGraduacion\ConformarGrupoController');
Route::post('getAlumno', 'TrabajoGraduacion\ConformarGrupoController@getAlumno');
Route::post('verificarGrupo', 'TrabajoGraduacion\ConformarGrupoController@verificarGrupo');
Route::post('confirmarGrupo', 'TrabajoGraduacion\ConformarGrupoController@confirmarGrupo');
Route::post('enviarGrupo', 'TrabajoGraduacion\ConformarGrupoController@enviarGrupo')->name('enviarGrupo');
Route::post('aprobarGrupo', 'TrabajoGraduacion\ConformarGrupoController@aprobarGrupo')->name('aprobarGrupo');
	//PrePerfil
Route::resource('prePerfil','TrabajoGraduacion\PrePerfilController');
Route::post('downloadPrePerfil','TrabajoGraduacion\PrePerfilController@downloadPrePerfil')->name('downloadPrePerfil');
Route::post('aprobarPreperfil','TrabajoGraduacion\PrePerfilController@aprobarPreperfil')->name('aprobarPreperfil');
Route::post('rechazarPrePerfil','TrabajoGraduacion\PrePerfilController@rechazarPrePerfil')->name('rechazarPrePerfil');
Route::get('indexPrePerfil/{id}','TrabajoGraduacion\PrePerfilController@indexPrePerfil')->name('indexPrePerfil');

	//Perfil
Route::resource('perfil','TrabajoGraduacion\PerfilController');
Route::post('downloadPerfil','TrabajoGraduacion\PerfilController@downloadPerfil')->name('downloadPerfil');
Route::post('aprobarPerfil','TrabajoGraduacion\PerfilController@aprobarPerfil')->name('aprobarPerfil');
Route::post('rechazarPerfil','TrabajoGraduacion\PerfilController@rechazarPerfil')->name('rechazarPerfil');
Route::get('indexPerfil/{id}','TrabajoGraduacion\PerfilController@indexPerfil')->name('indexPerfil');

//Trabajo de graduacion
Route::get('Dashboard/','TrabajoGraduacion\TrabajoDeGraduacionController@index')->name('Dashboard');
Route::resource('etapaEvaluativa','TrabajoGraduacion\EtapaEvaluativaController');

//Documentos de trabajo de graduación
Route::get('nuevoDocumento/{idEtapa}/{idTipoDoc?}','TrabajoGraduacion\DocumentoController@createDocumento')->name('nuevoDocumento');
Route::get('editDocumento/{idEtapa}/{idDocumento}/{idTipoDoc?}','TrabajoGraduacion\DocumentoController@editDocumento')->name('editDocumento');
Route::resource('documento','TrabajoGraduacion\DocumentoController');
Route::post('downloadDocumento','TrabajoGraduacion\DocumentoController@downloadDocumento')->name('downloadDocumento');
//------------------------------------------------------------------------------------------------------------------------


//------------------PUBLICACIONES DE TRABAJOS DE RADUACIÓN----------------------------------------------------------------
Route::resource('publicacion','Publicaciones\publicacionController');
Route::get('nuevoDocumentoPublicacion/{idPublicacion}','Publicaciones\publicacionController@createDocumento')->name('nuevoDocumentoPublicacion');
Route::post('storageDocPublicacion','Publicaciones\publicacionController@storeDocumento')->name('storageDocPublicacion');


//------------------------------------------------------------------------------------------------------------------------

