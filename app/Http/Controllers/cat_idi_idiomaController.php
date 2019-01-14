<?php

namespace App\Http\Controllers;
use App\cat_idi_idiomaModel;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class cat_idi_idiomaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catIdioma.index'])) {
            $catIdioma = cat_idi_idiomaModel::all();
            return view('catIdioma.index',compact('catIdioma'));

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
        if ($userLogin->can(['catIdioma.create'])) {
            return view('catIdioma.create');
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
            'nombre_cat_idi' => 'required|max:20'
        ],
            [
                'nombre_cat_idi.required' => 'El idioma es requerido',
                'nombre_cat_idi.max' => 'El idioma debe contener como maximo 20 caracteres'
            ]
        );

        cat_idi_idiomaModel::create
        ([
            'nombre_cat_idi'=> $request['nombre_cat_idi']
        ]);

        Return redirect('catIdioma')->with('message','Idioma Registrado correctamente!') ;
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
        if ($userLogin->can(['catIdioma.edit'])) {
            $catIdioma=cat_idi_idiomaModel::find($id);

            return view('catIdioma.edit',compact(['catIdioma']));
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
        $catIdioma=cat_idi_idiomaModel::find($id);

        $catIdioma->fill($request->all());
        $catIdioma->save();
        Session::flash('message','Idioma Modificado correctamente!');
        return Redirect::to('catIdioma');
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
        if ($userLogin->can(['catIdioma.destroy']))
            {
            try {
                cat_idi_idiomaModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('catIdioma');
            }
                Session::flash('message','Idioma Eliminado Correctamente!');
                return Redirect::to('catIdioma');

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
