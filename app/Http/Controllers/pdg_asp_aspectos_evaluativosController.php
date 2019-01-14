<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use App\pdg_asp_aspectos_evaluativosModel;
use App\cat_eta_eva_etapa_evalutativaModel;

class pdg_asp_aspectos_evaluativosController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['pdgAspectos.index'])) {
            $pdgAspectos=pdg_asp_aspectos_evaluativosModel::all();
            return view('pdgAspectos.index',compact('pdgAspectos'));
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
        if ($userLogin->can(['pdgAspectos.create'])) {
            $catEtaEva= cat_eta_eva_etapa_evalutativaModel::all()->pluck("full_name","id_cat_eta_eva");
            return view('pdgAspectos.create',compact('catEtaEva'));
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
            'nombre_pdg_asp' => 'required|max:45',
            'ponderacion_pdg_asp' => 'required|numeric'
        ],
            [
                'nombre_pdg_asp.required' => 'El nombre del aspecto es necesario',
                'nombre_pdg_asp.max' => 'El nombre del aspecto debe contener como maximo 45 caracteres',
                'ponderacion_pdg_asp.required'=> 'La ponderación del aspecto es necesario',
                'ponderacion_pdg_asp.max'=> 'La ponderación del aspecto debe contener como maximo 5 caracteres',
            ]
        );

//        return var_dump($request);
        pdg_asp_aspectos_evaluativosModel::create
        ([
            'nombre_pdg_asp'       	 => $request['nombre_pdg_asp'],
            'ponderacion_pdg_asp'       	 => $request['ponderacion_pdg_asp'],
            'id_cat_eta_eva'        => $request['id_cat_eta_eva']
        ]);

        Return redirect('pdgAspectos')->with('message','Estado Registrado correctamente!') ;
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
        if ($userLogin->can(['pdgAspectos.edit'])) {
            $pdgAspectos= pdg_asp_aspectos_evaluativosModel::find($id);
            $catEtaEva= cat_eta_eva_etapa_evalutativaModel::pluck("nombre_cat_eta_eva","id_cat_eta_eva");

            return view('pdgAspectos.edit',compact(['pdgAspectos'],'catEtaEva'));
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
        $pdgAspectos=pdg_asp_aspectos_evaluativosModel::find($id);

        $pdgAspectos->fill($request->all());
        $pdgAspectos->save();
        // Session::flash('message','Tipo Documento Modificado correctamente!');
        return Redirect::to('pdgAspectos');
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
        if ($userLogin->can(['pdgAspectos.destroy']))
        {
            try {
                pdg_asp_aspectos_evaluativosModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('pdgAspectos');
            }
            Session::flash('message','Aspecto Eliminado Correctamente!');
            return Redirect::to('pdgAspectos');

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }

    }
}
