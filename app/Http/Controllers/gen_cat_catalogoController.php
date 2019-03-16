<?php

namespace App\Http\Controllers;
use App\gen_cat_catalogoModel;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;

class gen_cat_catalogoController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catCatalogo.index'])) {
            $catCatalogo=gen_cat_catalogoModel::all();
            return view('catCatalogo.index',compact('catCatalogo'));
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
        if ($userLogin->can(['catCatalogo.create'])) {
            return view('catCatalogo.create');
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
            'nombre_gen_cat' => 'required|max:100',
            'descripcion_gen_cat' => 'required|max:200',
            'tipo_gen_cat'=> 'required|max:45',
            'ruta_gen_cat' => 'required|max:100'
        ],
            [
                'nombre_gen_cat.required' => 'El nombre necesario',
                'nombre_gen_cat.max' => 'El nombre debe contener como maximo 100 caracteres',
                'descripcion_gen_cat.required'=> 'La descripción es necesario',
                'descripcion_gen_cat.max'=> 'La descripción debe como máximo 200 caracteres',
                'tipo_gen_cat.required' => 'El tipo es necesario',
                'tipo_gen_cat.max' => 'El tipo debe contener 45 carácteres como máximo',
                'ruta_gen_cat.required' => 'Debe ingresar la Ruta',
                'ruta_gen_cat.max' => 'La ruta debe contener como maximo 100 caracteres'
            ]

        );



        gen_cat_catalogoModel::create
        ([
            'nombre_gen_cat'       	 => $request['nombre_gen_cat'],
            'descripcion_gen_cat'       	 => $request['descripcion_gen_cat'],
            'tipo_gen_cat'       	 => $request['tipo_gen_cat'],
            'ruta_gen_cat'       	 => $request['ruta_gen_cat']

        ]);

        Return redirect('catCatalogo')->with('message','Catálogo Registrado correctamente!') ;

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
        if ($userLogin->can(['catCatalogo.edit'])) {
            $catCatalogo= gen_cat_catalogoModel::find($id);

            return view('catCatalogo.edit',compact(['catCatalogo']));
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
        $catCatalogo=gen_cat_catalogoModel::find($id);

        $catCatalogo->fill($request->all());
        $catCatalogo->save();
        // Session::flash('message','Tipo Documento Modificado correctamente!');
        return Redirect::to('catCatalogo');

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
        if ($userLogin->can(['catCatalogo.destroy']))
        {
            try {
                gen_cat_catalogoModel::destroy($id);

            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('catCatalogo');
            }
            Session::flash('message','Catálogo Eliminado Correctamente!');
            return Redirect::to('catCatalogo');

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }

    }

}
