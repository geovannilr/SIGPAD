<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use App\gen_par_parametrosModel;
use App\gen_tpo_par_tipo_parametroModel;

class gen_par_parametrosController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['parParametros.index'])) {
            $parParametros=gen_par_parametrosModel::all();
            return view('parParametros.index',compact('parParametros'));
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
        if ($userLogin->can(['parParametros.create'])) {
            $tpoParametro = gen_tpo_par_tipo_parametroModel::pluck("tipo_gen_tpo_par","id_gen_tpo_par");
            return view('parParametros.create',compact('tpoParametro'));
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
            'nombre_gen_par' => 'required|max:45',
            'valor_gen_par' => 'required',
            'id_gen_usuario' => 'max:11'

        ],
            [
                'nombre_gen_par.required' => 'El nombre del parámetro es necesario',
                'nombre_gen_par.max' => 'El nombre del parámetro debe contener como maximo 45 caracteres',
                'valor_gen_par.required'=> 'El valor es requerido',
                'id_gen_usuario.required'=> 'El usuario debe tener como máximo 11 caracteres',
            ]

        );
        $usuario=Auth::user()->id;

//        return var_dump($request);
        gen_par_parametrosModel::create
        ([
            'nombre_gen_par'       	 => $request['nombre_gen_par'],
            'valor_gen_par'       	 => $request['valor_gen_par'],
            'id_gen_usuario'        =>  $usuario,
            'id_gen_tpo_par'       	 => $request['id_gen_tpo_par']
        ]);

        Return redirect('parParametros')->with('message','Parametro Registrado correctamente!') ;
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
        if ($userLogin->can(['parParametros.edit'])) {
            $parParametros= gen_par_parametrosModel::find($id);
            $tpoParametro = gen_tpo_par_tipo_parametroModel::pluck("tipo_gen_tpo_par","id_gen_tpo_par");

            return view('parParametros.edit',compact(['parParametros'],'tpoParametro'));
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
        $parParametros=gen_par_parametrosModel::find($id);

        $parParametros->fill($request->all());
        $parParametros->save();
        // Session::flash('message','Tipo Documento Modificado correctamente!');
        return Redirect::to('parParametros');
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
        if ($userLogin->can(['parParametros.destroy']))
        {
            try {
                gen_par_parametrosModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('parParametros');
            }
            Session::flash('message','Parámetro Eliminado Correctamente!');
            return Redirect::to('parParametros');

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
