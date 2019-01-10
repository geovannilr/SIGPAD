<?php

namespace App\Http\Controllers;

use App\cat_mat_materiaModel;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;

class cat_mat_materiaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catMateria.index'])) {
            $catMateria =cat_mat_materiaModel::all();
            return view('catMateria.index',compact('catMateria'));
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
        if ($userLogin->can(['catMateria.create'])) {
            return view('catMateria.create');
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
            'codigo_mat' => 'required|max:45',
            'nombre_mat' => 'required|max:100',
            'anio_pensum'=> 'required|max:4',
            'ciclo' => 'required|max:2'
        ],
            [
                'codigo_mat.required' => 'El código de materia es necesario',
                'codigo_mat.max' => 'El código de materia contener como maximo 45 caracteres',
                'nombre_mat.required'=> 'El nombre de la materia es necesario',
                'nombre_mat.max'=> 'El nombre de la materia máximo 100 caracteres',
                'anio_pensum.required' => 'El año de pensum es necesario',
                'anio_pensum.max' => 'El año de pensum debe contener 4 carácteres como máximo',
                'ciclo.required' => 'Debe ingresar ciclo',
                'ciclo.max' => 'El ciclo debe contener como maximo 4 caractéres'
            ]

        );



        cat_mat_materiaModel::create
        ([
            'codigo_mat'       	 => $request['codigo_mat'],
            'nombre_mat'       	 => $request['nombre_mat'],
            'anio_pensum'       	 => $request['anio_pensum'],
            'ciclo'       	 => $request['ciclo']

        ]);

        Return redirect('catMateria')->with('message','Materia Registrada correctamente!') ;

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
        if ($userLogin->can(['catMateria.edit'])) {
            $catMateria= cat_mat_materiaModel::find($id);

            return view('catMateria.edit',compact(['catMateria']));
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
        $catMateria=cat_mat_materiaModel::find($id);

        $catMateria->fill($request->all());
        $catMateria->save();
        // Session::flash('message','Tipo Documento Modificado correctamente!');
        return Redirect::to('catMateria');

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
        if ($userLogin->can(['catMateria.destroy'])) {
            cat_mat_materiaModel::destroy($id);
            Session::flash('message','Materia Eliminada Correctamente!');
            return Redirect::to('catMateria');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }

    }
}
