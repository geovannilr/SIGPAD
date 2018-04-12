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