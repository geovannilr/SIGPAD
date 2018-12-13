<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use App\cat_eta_eva_etapa_evalutativaModel;

class cat_eta_eva_etapa_evalutativaController extends Controller
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
        if ($userLogin->can(['etapaEvaluativa.index'])) {
            $etapaEvaluativa =cat_eta_eva_etapa_evalutativaModel::all();
            return view('etapaEvaluativa.index',compact('etapaEvaluativa'));
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
        if ($userLogin->can(['etapaEvaluativa.create'])) {
            return view('etapaEvaluativa.create');
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
            'nombre_cat_eta_eva' => 'required|max:45',
            'ponderacion_cat_eta_eva' => 'required|max:5',
            'anio_cat_eta_eva' => 'required|max:4',
            'tiene_defensas_cat_eta_eva'=> 'required|max:11',
            'puede_observar_cat_eta_eva'=> 'required|max:11'
        ],
            [
                'nombre_cat_eta_eva.required' => 'El nombre de la etapa evaluativa es necesario',
                'nombre_cat_eta_eva.max' => 'El nombre de la etapa evaluativa debe contener como maximo 45 caracteres',
                'ponderacion_cat_eta_eva.required'=> 'La ponderacion de la etapa evaluativa es necesario',
                'ponderacion_cat_eta_eva.max'=> 'La ponderacion de la etapa evaluativa debe contener como maximo 5 caracteres',
                'anio_cat_eta_eva.required' => 'El anio de  la etapa evaluativa es necesario',
                'anio_cat_eta_eva.max' => 'El anio de la etapa evaluativa debe contener como maximo 4 caracteres',
                'tiene_defensas_cat_eta_eva.required' => 'Si tiene defensas es necesario saberlo',
                'tiene_defensas_cat_eta_eva.max' => 'La cantidad maxima de caracteres para saber si tiene defensas no debe ser mayor a 11',
                'puede_observar_cat_eta_eva.required' => 'Saber si puede observar etapa evaluativa es necesario',
                'puede_observar_cat_eta_eva.max' => 'La cantidad maxima de caracteres para poder observar la etapa evaluativa es de 11'

            ]
            );



        cat_eta_eva_etapa_evalutativaModel::create
        ([
            'nombre_cat_eta_eva'       	 => $request['nombre_cat_eta_eva'],
            'ponderacion_cat_eta_eva'       	 => $request['ponderacion_cat_eta_eva'],
            'anio_cat_eta_eva'       	 => $request['anio_cat_eta_eva'],
            'tiene_defensas_cat_eta_eva'       	 => $request['tiene_defensas_cat_eta_eva'],
            'puede_observar_cat_eta_eva'       	 => $request['puede_observar_cat_eta_eva'],

        ]);

        Return redirect('etapaEvaluativa')->with('message','Etapa Evaluativa Registrada correctamente!') ;

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
        if ($userLogin->can(['etapaEvaluativa.edit'])) {
            $etapaEvaluativa= cat_eta_eva_etapa_evalutativaModel::find($id);

            return view('etapaEvaluativa.edit',compact(['etapaEvaluativa']));
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
        $etapaEvaluativa=cat_eta_eva_etapa_evalutativaModel::find($id);

        $etapaEvaluativa->fill($request->all());
        $etapaEvaluativa->save();
        // Session::flash('message','Tipo Documento Modificado correctamente!');
        return Redirect::to('etapaEvaluativa');
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
        if ($userLogin->can(['etapaEvaluativa.destroy'])) {
            cat_eta_eva_etapa_evalutativaModel::destroy($id);
            Session::flash('message','Etapa Evaluativa Eliminado Correctamente!');
            return Redirect::to('etapaEvaluativa');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
