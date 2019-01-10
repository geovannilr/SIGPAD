<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use App\cat_sta_estadoModel;
use App\cat_tpo_sta_tipo_estadoModel;

class cat_sta_estadoController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catEstado.index'])) {
            $catEstado=cat_sta_estadoModel::all();
            return view('catEstado.index',compact('catEstado'));
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
        if ($userLogin->can(['catEstado.create'])) {
            $tpoSta = cat_tpo_sta_tipo_estadoModel::pluck("nombre_cat_tpo_sta","id_cat_tpo_sta");
            return view('catEstado.create',compact('tpoSta'));
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
        //
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
            'nombre_cat_sta' => 'required|max:45',
            'descripcion_cat_sta' => 'required|max:250'
        ],
            [
                'nombre_cat_sta.required' => 'El nombre del estado es necesario',
                'nombre_cat_sta.max' => 'El nombre del estado debe contener como maximo 45 caracteres',
                'descripcion_cat_sta.required'=> 'La descripcion del estado es necesaria',
                'descripcion_cat_sta.max'=> 'La descripción del estado debe contener como maximo 250 caracteres',
            ]
        );

//        return var_dump($request);
        cat_sta_estadoModel::create
        ([
            'nombre_cat_sta'       	 => $request['nombre_cat_sta'],
            'descripcion_cat_sta'       	 => $request['descripcion_cat_sta'],
            'id_cat_tpo_sta'        => $request['id_cat_tpo_sta']
        ]);

        Return redirect('catEstado')->with('message','Estado Registrado correctamente!') ;
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
        if ($userLogin->can(['catEstado.edit'])) {
            $catEstado= cat_sta_estadoModel::find($id);
            $tpoSta = cat_tpo_sta_tipo_estadoModel::pluck("nombre_cat_tpo_sta","id_cat_tpo_sta");

            return view('catEstado.edit',compact(['catEstado'],'tpoSta'));
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
        $catEstado=cat_sta_estadoModel::find($id);

        $catEstado->fill($request->all());
        $catEstado->save();
        // Session::flash('message','Tipo Documento Modificado correctamente!');
        return Redirect::to('catEstado');
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
        if ($userLogin->can(['catEstado.destroy']))
        {
        try {
            cat_sta_estadoModel::destroy($id);
        } catch (\PDOException $e)
        {
            Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
            return Redirect::to('catEstado');
        }
            Session::flash('message','Estado Eliminado Correctamente!');
            return Redirect::to('catEstado');

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }


    }
}
