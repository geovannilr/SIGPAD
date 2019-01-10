<?php

namespace App\Http\Controllers;
use App\cat_car_cargo_eisiModel;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class cat_car_cargo_eisiController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['cargoEisi.index'])) {
            $cargoEisi = cat_car_cargo_eisiModel::all();
            return view('cargoEisi.index',compact('cargoEisi'));

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
        if ($userLogin->can(['cargoEisi.create'])) {
            return view('cargoEisi.create');
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
            'nombre_cargo' => 'required|max:100'
        ],
            [
                'nombre_cargo.required' => 'El cargo es necesario',
                'nombre_cargo.max' => 'El cargo debe contener como maximo 100 caracteres'
            ]
        );

        cat_car_cargo_eisiModel::create
        ([
            'nombre_cargo'=> $request['nombre_cargo']
        ]);

        Return redirect('cargoEisi')->with('message','Cargo Registrado correctamente!') ;
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
        if ($userLogin->can(['cargoEisi.edit'])) {
            $cargoEisi=cat_car_cargo_eisiModel::find($id);

            return view('cargoEisi.edit',compact(['cargoEisi']));
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
        $cargoEisi=cat_car_cargo_eisiModel::find($id);

        $cargoEisi->fill($request->all());
        $cargoEisi->save();
        Session::flash('message','Cargo Modificado correctamente!');
        return Redirect::to('cargoEisi');
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
        if ($userLogin->can(['cargoEisi.destroy'])) {
            cat_car_cargo_eisiModel::destroy($id);
            Session::flash('message','Cargo Eliminado Correctamente!');
            return Redirect::to('cargoEisi');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
