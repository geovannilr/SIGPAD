<?php

namespace App\Http\Controllers;

use App\cat_ctg_tra_categoria_trabajo_graduacionModel;
use Illuminate\Http\Request;
use Session;
use Redirect;

use Illuminate\Support\Facades\Auth;

class cat_ctg_tra_categoria_trabajo_graduacionController extends Controller
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
        if ($userLogin->can(['categoriaTDG.index'])) {
            $categoriasTDG = cat_ctg_tra_categoria_trabajo_graduacionModel::all();
            return view('categoriaTDG.index',compact('categoriasTDG'));

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
        if ($userLogin->can(['categoriaTDG.create'])) {
            return view('categoriaTDG.create');
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
            'nombre_cat_ctg_tra' => 'required|max:45'
        ],
            [
                'nombre_cat_ctg_tra.required' => 'El nombre de la categoria de trabajo de graduación es necesario',
                'nombre_cat_ctg_tra.max' => 'El nombre de la categoria de trabajo de graduación debe contener como maximo 45 caracteres'
            ]
            );

        cat_ctg_tra_categoria_trabajo_graduacionModel::create
        ([
            'nombre_cat_ctg_tra'=> $request['nombre_cat_ctg_tra']
        ]);

        Return redirect('categoriaTDG')->with('message','Categoría Registrada correctamente!') ;
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
        if ($userLogin->can(['categoriaTDG.edit'])) {
            $categoriaTDG=cat_ctg_tra_categoria_trabajo_graduacionModel::find($id);

            return view('categoriaTDG.edit',compact(['categoriaTDG']));
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }


    public function update(Request $request, $id)
    {

        $categoriaTDG=cat_ctg_tra_categoria_trabajo_graduacionModel::find($id);

        $categoriaTDG->fill($request->all());
        $categoriaTDG->save();
        Session::flash('message','Categoría de TDG Modificada correctamente!');
        return Redirect::to('categoriaTDG');
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
            if ($userLogin->can(['categoriaTDG.destroy'])) {
                cat_ctg_tra_categoria_trabajo_graduacionModel::destroy($id);
                Session::flash('message','Categoria Eliminada Correctamente!');
                return Redirect::to('categoriaTDG');
            }else{
                Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
                return  view('template');
                }


    }
}
