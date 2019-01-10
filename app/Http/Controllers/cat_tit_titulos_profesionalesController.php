<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cat_tit_titulos_profesionalesModel;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
class cat_tit_titulos_profesionalesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catTitulos.index'])) {
            $catTitulos= cat_tit_titulos_profesionalesModel::all();
            return view('catTitulos.index',compact('catTitulos'));

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catTitulos.create'])) {
            return view('catTitulos.create');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre_titulo_cat_tit' => 'required|max:45'
        ],
            [
                'nombre_titulo_cat_tit.required' => 'El título es requerido',
                'nombre_titulo_cat_tit.max' => 'El título debe contener como maximo 45 caracteres'
            ]
        );

        cat_tit_titulos_profesionalesModel::create
        ([
            'nombre_titulo_cat_tit'=> $request['nombre_titulo_cat_tit']
        ]);

        Return redirect('catTitulos')->with('message','Título Registrado correctamente!') ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catTitulos.edit'])) {
            $catTitulos=cat_tit_titulos_profesionalesModel::find($id);

            return view('catTitulos.edit',compact(['catTitulos']));
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $catTitulos=cat_tit_titulos_profesionalesModel::find($id);

        $catTitulos->fill($request->all());
        $catTitulos->save();
        Session::flash('message','Titulo Modificado correctamente!');
        return Redirect::to('catTitulos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catTitulos.destroy'])) {
            cat_tit_titulos_profesionalesModel::destroy($id);
            Session::flash('message','Titulo Eliminado Correctamente!');
            return Redirect::to('catTitulos');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
