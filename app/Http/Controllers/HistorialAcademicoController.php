<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Redirect;
use \App\cat_mat_materiaModel;
use \App\cat_car_cargo_eisiModel;
use \App\dcn_his_historial_academicoModel;
use \App\pdg_dcn_docenteModel;



class HistorialAcademicoController extends Controller
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
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){

        $materias = cat_mat_materiaModel::pluck('nombre_mat','id_cat_mat');
        $cargos = cat_car_cargo_eisiModel::pluck('nombre_cargo','id_cat_car');
        return view('PerfilDocente.Catalogos.Academico.create',compact('materias','cargos'));
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
                'id_cat_mat' => 'required',
                'id_cat_car' => 'required',
                'anio' => 'required|max:4',
                'descripcion_adicional' => 'max:500'                
            ],
            [
                'id_cat_mat.required' => 'Debe seleccionar una materia',
                'id_cat_car.required' => 'Debe seleccionar un cargo.',
                'anio.required' => 'Debe seleccionar un año.',
                'anio.max' => 'El año debe ser máximo de 4 digitos',
                'descripcion_adicional.max' => 'La descripcición debe ser máximo de 500 caracteres.'
            ]
        );
       $userLogin = Auth::user();
       $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
       $idDocente = $docente->id_pdg_dcn; 
        $lastId = dcn_his_historial_academicoModel::create
                    ([
                        'id_pdg_dcn'                => $idDocente,
                        'id_cat_mat'                => $request['id_cat_mat'],
                        'id_cat_car'                => $request['id_cat_car'],
                        'anio'                      => $request['anio'],
                        'descripcion_adicional'     => $request['descripcion_adicional']
                        
                    ]);

       Session::flash('message','Registro de historial académico realizado correctamente!');
       return Redirect::to('DashboardPerfilDocente/');                     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $materias = cat_mat_materiaModel::pluck('nombre_mat','id_cat_mat');
        $cargos = cat_car_cargo_eisiModel::pluck('nombre_cargo','id_cat_car');
        $academico = dcn_his_historial_academicoModel::find($id);
        return view('PerfilDocente.Catalogos.Academico.edit',compact('materias','cargos','academico'));
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
                'id_cat_mat' => 'required',
                'id_cat_car' => 'required',
                'anio' => 'required|max:4',
                'descripcion_adicional' => 'max:500'                
            ],
            [
                'id_cat_mat.required' => 'Debe seleccionar una materia',
                'id_cat_car.required' => 'Debe seleccionar un cargo.',
                'anio.required' => 'Debe seleccionar un año.',
                'anio.max' => 'El año debe ser máximo de 4 digitos',
                'descripcion_adicional.max' => 'La descripcición debe ser máximo de 500 caracteres.'
            ]
        );
        $academico = dcn_his_historial_academicoModel::find($id);
        $academico -> id_cat_mat = $request['id_cat_mat'];
        $academico -> id_cat_car = $request['id_cat_car'];
        $academico -> anio = $request['anio'];
        $academico -> descripcion_adicional = $request['descripcion_adicional'];
        $academico->save();
        Session::flash('message','Actualización  de historial académico realizado correctamente!');
       return Redirect::to('DashboardPerfilDocente'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        dcn_his_historial_academicoModel::destroy($id);
        Session::flash('message','Registro de Historial académico Eliminado Correctamente!');
        return Redirect::to('DashboardPerfilDocente');
    }
}
