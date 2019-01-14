<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cat_tpo_pub_tipo_publicacionModel;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;

class cat_tpo_pub_tipo_publicacionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catTpublicacion.index'])) {
            $catTpublicacion= cat_tpo_pub_tipo_publicacionModel::all();
            return view('catTpublicacion.index',compact('catTpublicacion'));

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
        if ($userLogin->can(['catTpublicacion.create'])) {
            return view('catTpublicacion.create');
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
            'nombre_cat_tpo_pub' => 'required|max:45',
            'descripcion_cat_tpo_pub' => 'max:100'
        ],
            [
                'nombre_cat_tpo_pub.required' => 'El nombre tipo publicación es requerido',
                'nombre_cat_tpo_pub.max' => 'El tipo de publicación debe contener como maximo 45 caracteres',
                'descripcion_cat_tpo_pub.max' => 'La descripción debe contener como maximo 100 caracteres'
            ]
        );

        cat_tpo_pub_tipo_publicacionModel::create
        ([
            'nombre_cat_tpo_pub'=> $request['nombre_cat_tpo_pub'],
            'descripcion_cat_tpo_pub'=> $request['descripcion_cat_tpo_pub']
        ]);

        Return redirect('catTpublicacion')->with('message','Tipo Publicación Registrada correctamente!') ;

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
        if ($userLogin->can(['catTpublicacion.edit'])) {
            $catTpublicacion=cat_tpo_pub_tipo_publicacionModel::find($id);

            return view('catTpublicacion.edit',compact(['catTpublicacion']));
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
        $catTpublicacion=cat_tpo_pub_tipo_publicacionModel::find($id);

        $catTpublicacion->fill($request->all());
        $catTpublicacion->save();
        Session::flash('message','Tipo Publicación Modificada correctamente!');
        return Redirect::to('catTpublicacion');

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
        if ($userLogin->can(['catTpublicacion.destroy']))
            {
            try {
                cat_tpo_pub_tipo_publicacionModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('catTpublicacion');
            }
                Session::flash('message','Tipo publicación Eliminada Correctamente!');
                return Redirect::to('catTpublicacion');
            }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
