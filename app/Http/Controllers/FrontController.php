<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
class FrontController extends Controller
{
	public function __construct(){
		$this->middleware('auth');
	}

    public function index(){
      //Session::flash('message-error', 'Usuario o Contraseña Incorrecta');
      return  view('template');
    }
}
