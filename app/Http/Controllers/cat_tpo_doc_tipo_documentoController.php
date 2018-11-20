<?php

namespace App\Http\Controllers;

use App\cat_tpo_doc_tipo_documentoModel;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;

class cat_tpo_doc_tipo_documentoController extends Controller
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
      if ($userLogin->can(['tipoDocumento.index'])) {
            $tipoDocumento =cat_tpo_doc_tipo_documentoModel::all();
            return view('tipoDocumento.index',compact('tipoDocumento'));
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
        if ($userLogin->can(['tipoDocumento.create'])) {
            return view('tipoDocumento.create');
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
            'nombre_pdg_tpo_doc' => 'required|max:45',
            'descripcion_pdg_tpo_doc' => 'required|max:100',
            'puede_observar_cat_pdg_tpo_doc'=> 'required|max:11',
            'anio_cat_pdg_tpo_doc' => 'required|max:4'
             ]);



        cat_tpo_doc_tipo_documentoModel::create
        ([
            'nombre_pdg_tpo_doc'       	 => $request['nombre_pdg_tpo_doc'],
            'descripcion_pdg_tpo_doc'       	 => $request['descripcion_pdg_tpo_doc'],
            'puede_observar_cat_pdg_tpo_doc'       	 => $request['puede_observar_cat_pdg_tpo_doc'],
            'anio_cat_pdg_tpo_doc'       	 => $request['anio_cat_pdg_tpo_doc']

        ]);

        Return redirect('tipoDocumento')->with('message','Tipo Documento Registrado correctamente!') ;

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
        if ($userLogin->can(['tipoDocumento.edit'])) {
            $tipoDocumento= cat_tpo_doc_tipo_documentoModel::find($id);

            return view('tipoDocumento.edit',compact(['tipoDocumento']));
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
        $tipoDocumento=cat_tpo_doc_tipo_documentoModel::find($id);

        $tipoDocumento->fill($request->all());
        $tipoDocumento->save();
       // Session::flash('message','Tipo Documento Modificado correctamente!');
        return Redirect::to('tipoDocumento');

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
        if ($userLogin->can(['tipoDocumento.destroy'])) {
            cat_tpo_doc_tipo_documentoModel::destroy($id);
            Session::flash('message','Tipo Documento Eliminado Correctamente!');
            return Redirect::to('tipoDocumento');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }

    }
}
