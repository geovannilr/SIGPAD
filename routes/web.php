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
//TRABAJO DE GRADUACIÓN
Route::resource('grupo','TrabajoGraduacion\ConformarGrupoController');
Route::post('getAlumno', 'TrabajoGraduacion\ConformarGrupoController@getAlumno');
Route::post('verificarGrupo', 'TrabajoGraduacion\ConformarGrupoController@verificarGrupo');
Route::post('confirmarGrupo', 'TrabajoGraduacion\ConformarGrupoController@confirmarGrupo');
Route::post('enviarGrupo', 'TrabajoGraduacion\ConformarGrupoController@enviarGrupo')->name('enviarGrupo');