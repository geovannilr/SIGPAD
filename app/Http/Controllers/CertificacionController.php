<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Redirect;
use \App\dcn_cer_certificacionesModel;
use \App\cat_idi_idiomaModel;
use \App\pdg_dcn_docenteModel;


class CertificacionController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $idiomas = cat_idi_idiomaModel::pluck('nombre_cat_idi','id_cat_idi');
        return view('PerfilDocente.Catalogos.Certificaciones.create',compact('idiomas'));
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
                'nombre_dcn_cer' => 'required',
                'anio_expedicion_dcn_cer' => 'required',
                'institucion_dcn_cer' => 'required',
                'id_cat_idi' => 'required'
            ],
            [
                'nombre_dcn_cer.required' => 'Debe ingresar un nombre de certificacion',
                'anio_expedicion_dcn_cer.required' => 'Debe seleccionar un año de expedicion.',
                'institucion_dcn_cer.required' => 'Debe ingresar un nombre de institucion.',
                'id_cat_idi.required' => 'Debe seleccionar un idioma'
                
            ]
        );
        $userLogin = Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        $idDocente = $docente->id_pdg_dcn; 
        $lastId = dcn_cer_certificacionesModel::create
                    ([
                        'nombre_dcn_cer'                => $request["nombre_dcn_cer"],
                        'anio_expedicion_dcn_cer'       => $request["anio_expedicion_dcn_cer"],
                        'institucion_dcn_cer'           => $request["institucion_dcn_cer"],
                        'id_cat_idi'                    => $request["id_cat_idi"],
                        'id_dcn'                        => $idDocente               
                    ]);
        Session::flash('message','Registro de certificaciones realizado correctamente!');
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
    public function edit($id){
        $idiomas = cat_idi_idiomaModel::pluck('nombre_cat_idi','id_cat_idi');
        $certificacion = dcn_cer_certificacionesModel::find($id);
        return view('PerfilDocente.Catalogos.Certificaciones.edit',compact('idiomas','certificacion'));
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
                'nombre_dcn_cer' => 'required',
                'anio_expedicion_dcn_cer' => 'required',
                'institucion_dcn_cer' => 'required',
                'id_cat_idi' => 'required'
            ],
            [
                'nombre_dcn_cer.required' => 'Debe ingresar un nombre de certificacion',
                'anio_expedicion_dcn_cer.required' => 'Debe seleccionar un año de expedicion.',
                'institucion_dcn_cer.required' => 'Debe ingresar un nombre de institucion.',
                'id_cat_idi.required' => 'Debe seleccionar un idioma'
                
            ]
        );
        $userLogin = Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        $idDocente = $docente->id_pdg_dcn; 
        $certificacion = dcn_cer_certificacionesModel::find($id);
        $certificacion ->nombre_dcn_cer = $request["nombre_dcn_cer"];
        $certificacion ->anio_expedicion_dcn_cer = $request["anio_expedicion_dcn_cer"];
        $certificacion ->institucion_dcn_cer = $request["institucion_dcn_cer"];
        $certificacion ->id_cat_idi = $request["id_cat_idi"];
        $certificacion->save();
        
        Session::flash('message','Registro de certificaciones actulizado correctamente!');
        return Redirect::to('DashboardPerfilDocente');     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        //
    }
}
