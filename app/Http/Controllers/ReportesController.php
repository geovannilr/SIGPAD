<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller{
    function test(){
    	return view('pdfTemplate');
    }
}
