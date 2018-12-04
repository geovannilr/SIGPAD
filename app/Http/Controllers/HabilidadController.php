<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\rel_ski_dcn_skill_docenteModel;
use Illuminate\Validation\Rule;
use \App\pdg_dcn_docenteModel;
use \App\cat_ski_skillModel;
use Session;
use Redirect;

class HabilidadController extends Controller
{
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $userLogin = Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        $idDocente = $docente->id_pdg_dcn; 
        $validatedData = $request->validate(
            [
                'id_cat_ski' => [

                'required', 

                Rule::unique('rel_ski_dcn_skill_docente')->where(function ($query) use ($request,$idDocente) {

                    return $query
                        ->where("id_cat_ski","=",$request->id_cat_ski)
                        ->where("id_pdg_dcn","=",$idDocente);
                })
            ],
                
                'nivel' => 'required'             
            ],
            [
                'id_cat_ski.required' => 'Debe seleccionar una habilidad',
                'nivel.required' => 'Debe seleccionar un nivel de habilidad..',
                'id_cat_ski.unique' => 'La habilidad seleccionada ya se encuentra registrada como una de sus habilidades.'
               
            ]
        );
        
        $lastId = rel_ski_dcn_skill_docenteModel::create
            ([
                'id_cat_ski' => $request["id_cat_ski"],
                'nivel_ski_dcn'   => $request["nivel"],
                'id_pdg_dcn'      => $idDocente
                
                        
            ]);
        Session::flash('message','Se agregó la habilidad correctamente!');
        Session::flash('apartado','5');
        
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){$userLogin = Auth::user();
        $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
        $idDocente = $docente->id_pdg_dcn; 
        $skillEliminar = rel_ski_dcn_skill_docenteModel::where("id_cat_ski","=",$id)->where("id_pdg_dcn","=",$idDocente)->first();
        rel_ski_dcn_skill_docenteModel::destroy($skillEliminar->id_rel_ski_dcn);
        Session::flash('message','Se eliminó la habilidad correctamente de su perfil!');
        Session::flash('apartado','5');
        return Redirect::to('DashboardPerfilDocente');      
                        
    }
}
