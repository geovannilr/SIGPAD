<?php

namespace App\Http\Controllers;

use App\cat_tpo_ski_tipo_skillModel;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;

class cat_tpo_ski_tipo_skillController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['tipoSki.index'])) {

            $tipoSki =cat_tpo_ski_tipo_skillModel::all();
            return view('tipoSki.index',compact('tipoSki'));
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
        if ($userLogin->can(['tipoSki.create'])) {
            return view('tipoSki.create');
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
            'descripcion_tpo_ski' => 'max:60',
            ] ,[
                'descripcion_tpo_ski.max' => 'La descripción del tipo Skill debe contener como máximo 45 carácteres',
                ]
        );



        cat_tpo_ski_tipo_skillModel::create
        ([
            'descripcion_tpo_ski'       	 => $request['descripcion_tpo_ski']
        ]);

        Return redirect('tipoSki')->with('message','Tipo Skill Registrado correctamente!') ;

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
        if ($userLogin->can(['tipoSki.edit'])) {
            $tipoSki= cat_tpo_ski_tipo_skillModel::find($id);

            return view('tipoSki.edit',compact(['tipoSki']));
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
        $tipoSki=cat_tpo_ski_tipo_skillModel::find($id);

        $tipoSki->fill($request->all());
        $tipoSki->save();
        Session::flash('message','Tipo Skill Modificado correctamente!');
        return Redirect::to('tipoSki');

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
        if ($userLogin->can(['tipoSki.destroy'])) {
            cat_tpo_ski_tipo_skillModel::destroy($id);
            Session::flash('message','Tipo Skill Eliminado Correctamente!');
            return Redirect::to('tipoSki');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }

    }
}
