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
            'anio_cat_pdg_tpo_doc' => 'required|max:4'
        ],
            [
                'nombre_pdg_tpo_doc.required' => 'El nombre del tipo de documento es necesario',
                'nombre_pdg_tpo_doc.max' => 'El nombre dedel tipo de documento debe contener como maximo 45 caracteres',
                'descripcion_pdg_tpo_doc.required'=> 'La descripcion del tipo de documento es necesario',
                'descripcion_pdg_tpo_doc.max'=> 'La descripción del tipo de documento debe contener como maximo 100 caracteres',
                'puede_observar_cat_pdg_tpo_doc.required' => 'Saber si el tipo de documento puede ser observado debe ser posible',
                'puede_observar_cat_pdg_tpo_doc.max' => 'valor entre 0 y 1',
                'anio_cat_pdg_tpo_doc.required' => 'Debe ingresar un año',
                'anio_cat_pdg_tpo_doc.max' => 'El anio del tipo documento debe contener como maximo 4 caractéres'
                ]

        );



        cat_tpo_doc_tipo_documentoModel::create
        ([
            'nombre_pdg_tpo_doc'       	 => $request['nombre_pdg_tpo_doc'],
            'descripcion_pdg_tpo_doc'       	 => $request['descripcion_pdg_tpo_doc'],
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
        if ($userLogin->can(['tipoDocumento.destroy']))
            {
            try {
                cat_tpo_doc_tipo_documentoModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('tipoDocumento');
            }
                Session::flash('message','Tipo Documento Eliminado Correctamente!');
                return Redirect::to('tipoDocumento');

            }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }

    }
}
