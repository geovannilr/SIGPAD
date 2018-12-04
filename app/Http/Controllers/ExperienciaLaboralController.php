<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Redirect;
use \App\cat_idi_idiomaModel;
use \App\pdg_dcn_docenteModel;
use \App\dcn_exp_experienciaModel;


class ExperienciaLaboralController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $idiomas = cat_idi_idiomaModel::pluck('nombre_cat_idi','id_cat_idi');
        return view('PerfilDocente.Catalogos.Laboral.create',compact('idiomas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validatedData = $request->validate(
            [
                'lugar_trabajo_dcn_exp' => 'required',
                'anio_inicio_dcn_exp' => 'required',
                'anio_fin_dcn_exp' => 'required|max:4',
                'id_cat_idi' => 'required',
                'descripcion_dcn_exp' => 'max:500'                 
            ],
            [
                'lugar_trabajo_dcn_exp.required' => 'Debe ingresar un lugar de trabajo',
                'anio_inicio_dcn_exp.required' => 'Debe seleccionar un año de inicio.',
                'anio_fin_dcn_exp.required' => 'Debe seleccionar un año de fin.',
                'id_cat_idi.required' => 'Debe seleccionar un idioma',
                'descripcion_dcn_exp.max' => 'La descripcición debe ser máximo de 500 caracteres.'
            ]
        );
        $userLogin = Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        $idDocente = $docente->id_pdg_dcn; 
        $lastId = dcn_exp_experienciaModel::create
            ([
                'lugar_trabajo_dcn_exp' => $request["lugar_trabajo_dcn_exp"],
                'anio_inicio_dcn_exp'   => $request["anio_inicio_dcn_exp"],
                'anio_fin_dcn_exp'      => $request["anio_fin_dcn_exp"],
                'descripcion_dcn_exp'   => $request["descripcion_dcn_exp"],
                'id_cat_idi'            => $request["id_cat_idi"],
                'id_pdg_dcn'            => $idDocente  
                        
            ]);
        Session::flash('message','Registro de experiencia laboral realizado correctamente!');
        Session::flash('apartado','3');
        return Redirect::to('DashboardPerfilDocente');    
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
        $idiomas = cat_idi_idiomaModel::pluck('nombre_cat_idi','id_cat_idi');
        $laboral = dcn_exp_experienciaModel::find($id);
        return view('PerfilDocente.Catalogos.Laboral.edit',compact('idiomas','laboral'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $validatedData = $request->validate(
            [
                'lugar_trabajo_dcn_exp' => 'required',
                'anio_inicio_dcn_exp' => 'required',
                'anio_fin_dcn_exp' => 'required|max:4',
                'id_cat_idi' => 'required',
                'descripcion_dcn_exp' => 'max:500'                 
            ],
            [
                'lugar_trabajo_dcn_exp.required' => 'Debe ingresar un lugar de trabajo',
                'anio_inicio_dcn_exp.required' => 'Debe seleccionar un año de inicio.',
                'anio_fin_dcn_exp.required' => 'Debe seleccionar un año de fin.',
                'id_cat_idi.required' => 'Debe seleccionar un idioma',
                'descripcion_dcn_exp.max' => 'La descripcición debe ser máximo de 500 caracteres.'
            ]
        );
         $laboral = dcn_exp_experienciaModel::find($id);
         $laboral->lugar_trabajo_dcn_exp = $request['lugar_trabajo_dcn_exp'];
         $laboral->anio_inicio_dcn_exp = $request['anio_inicio_dcn_exp'];
         $laboral->anio_inicio_dcn_exp = $request['anio_inicio_dcn_exp'];
         $laboral->id_cat_idi = $request['id_cat_idi'];
         $laboral->descripcion_dcn_exp = $request['descripcion_dcn_exp'];
         $laboral->save();
         Session::flash('message','Actualización  de registro de experiencia laboral realizado correctamente!');
         Session::flash('apartado','3');
         return Redirect::to('DashboardPerfilDocente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        dcn_exp_experienciaModel::destroy($id);
        Session::flash('message','Registro de Experienica laboral Eliminado Correctamente!');
        Session::flash('apartado','3');
        return Redirect::to('DashboardPerfilDocente');
    }
}
