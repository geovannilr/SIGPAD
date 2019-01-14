<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cat_tpo_col_pub_tipo_colaboradorModel;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
class cat_tpo_col_pub_tipo_colaboradorController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catTcolaborador.index'])) {
            $catTcolaborador= cat_tpo_col_pub_tipo_colaboradorModel::all();
            return view('catTcolaborador.index',compact('catTcolaborador'));

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
        if ($userLogin->can(['catTcolaborador.create'])) {
            return view('catTcolaborador.create');
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
            'nombre_cat_tpo_col_pub' => 'required|max:45',
            'descripcion_cat_tpo_col_pub' => 'max:100'
        ],
            [
                'nombre_cat_tpo_col_pub.required' => 'El nombre de colaborador es requerido',
                'nombre_cat_tpo_col_pub.max' => 'El nombre de colaborador debe contener como máximo 45 caracteres',
                'descripcion_cat_tpo_col_pub.max' => 'La descripción debe contener como máximo 100 carácteres'
            ]
        );

        cat_tpo_col_pub_tipo_colaboradorModel::create
        ([
            'nombre_cat_tpo_col_pub'=> $request['nombre_cat_tpo_col_pub'],
            'descripcion_cat_tpo_col_pub'=> $request['descripcion_cat_tpo_col_pub']

        ]);

        Return redirect('catTcolaborador')->with('message','Tipo colaborador Registrado correctamente!') ;
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
        if ($userLogin->can(['catTcolaborador.edit'])) {
            $catTcolaborador=cat_tpo_col_pub_tipo_colaboradorModel::find($id);

            return view('catTcolaborador.edit',compact(['catTcolaborador']));
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
        $catTcolaborador=cat_tpo_col_pub_tipo_colaboradorModel::find($id);

        $catTcolaborador->fill($request->all());
        $catTcolaborador->save();
        Session::flash('message','Tipo colaborador Modificado correctamente!');
        return Redirect::to('catTcolaborador');
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
        if ($userLogin->can(['catTcolaborador.destroy']))
            {
            try {
                cat_tpo_col_pub_tipo_colaboradorModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('catTcolaborador');
            }
                Session::flash('message','Tipo Colaborador Eliminado Correctamente!');
                return Redirect::to('catTcolaborador');

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
