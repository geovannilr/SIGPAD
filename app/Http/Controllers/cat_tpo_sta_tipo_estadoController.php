<?php

namespace App\Http\Controllers;

use App\cat_tpo_sta_tipo_estadoModel;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;

class cat_tpo_sta_tipo_estadoController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['tipoEstado.index'])) {

            $tipoEstado =cat_tpo_sta_tipo_estadoModel::all();
            return view('tipoEstado.index',compact('tipoEstado'));
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
        if ($userLogin->can(['tipoEstado.create'])) {
            return view('tipoEstado.create');
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
            'nombre_cat_tpo_sta' => 'required|max:60',
            'descripcion_cat_tpo_sta' => 'required|max:100'

        ] ,[
                'nombre_cat_tpo_sta.required' => 'El nombre del tipo estado es necesario',
                'nombre_cat_tpo_sta.max' => 'El nombre del tipo estado debe contener como maximo 60 caracteres',
                'descripcion_cat_tpo_sta.required' => 'Ingrese descripción ya que es necesaria',
                'descripcion_cat_tpo_sta.max' => 'La descripción no debe poseer más de 100 caractéres'
            ]
        );



        cat_tpo_sta_tipo_estadoModel::create
        ([
            'nombre_cat_tpo_sta'       	 => $request['nombre_cat_tpo_sta'],
            'descripcion_cat_tpo_sta'       	 => $request['descripcion_cat_tpo_sta']

        ]);

        Return redirect('tipoEstado')->with('message','Tipo Estado Registrado correctamente!') ;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cat_tpo_sta_tipo_estadoModel  $cat_tpo_sta_tipo_estadoModel
     * @return \Illuminate\Http\Response
     */
    public function show(cat_tpo_sta_tipo_estadoModel $cat_tpo_sta_tipo_estadoModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cat_tpo_sta_tipo_estadoModel  $cat_tpo_sta_tipo_estadoModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $userLogin=Auth::user();
        if ($userLogin->can(['tipoEstado.edit'])) {
            $tipoEstado= cat_tpo_sta_tipo_estadoModel::find($id);

            return view('tipoEstado.edit',compact(['tipoEstado']));
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cat_tpo_sta_tipo_estadoModel  $cat_tpo_sta_tipo_estadoModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipoEstado=cat_tpo_sta_tipo_estadoModel::find($id);

        $tipoEstado->fill($request->all());
        $tipoEstado->save();
        Session::flash('message','Tipo Estado Modificado correctamente!');
        return Redirect::to('tipoEstado');

    }

    public function destroy($id)
    {
        $userLogin=Auth::user();

        if ($userLogin->can(['tipoEstado.destroy']))
            {
            try {
                cat_tpo_sta_tipo_estadoModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('tipoEstado');
            }
                Session::flash('message','Tipo Estado Eliminado Correctamente!');
                return Redirect::to('tipoEstado');
            }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }

    }
}
