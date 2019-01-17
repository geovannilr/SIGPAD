<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use App\cat_cri_eva_criterio_evaluacionModel;
use App\pdg_asp_aspectos_evaluativosModel;

class cat_cri_eva_criterio_evaluacionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catCriterios.index'])) {
            $catCriterios=cat_cri_eva_criterio_evaluacionModel::all();
            return view('catCriterios.index',compact('catCriterios'));
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
        if ($userLogin->can(['catCriterios.create'])) {
            $catAspEva= pdg_asp_aspectos_evaluativosModel::pluck("nombre_pdg_asp","id_pdg_asp");
            return view('catCriterios.create',compact('catAspEva'));
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
            'nombre_cat_cri_eva' => 'required|max:45',
            'ponderacion_cat_cri_eva' => 'required|max:6'
        ],
            [
                'nombre_cat_cri_eva.required' => 'El nombre del criterio es necesario',
                'nombre_cat_cri_eva.max' => 'El nombre del criterio debe contener como maximo 45 caracteres',
                'ponderacion_cat_cri_eva.required'=> 'La ponderación del criterio es necesario',
                'ponderacion_cat_cri_eva.max'=> 'La ponderación del criterio debe contener como maximo 6 caracteres',
            ]
        );

//        return var_dump($request);
        cat_cri_eva_criterio_evaluacionModel::create
        ([
            'nombre_cat_cri_eva'       	 => $request['nombre_cat_cri_eva'],
            'ponderacion_cat_cri_eva'       	 => $request['ponderacion_cat_cri_eva'],
            'id_pdg_asp'        => $request['id_pdg_asp']
        ]);

        Return redirect('catCriterios')->with('message','Criterio Registrado correctamente!') ;

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
        if ($userLogin->can(['catCriterios.edit'])) {
            $catCriterios= cat_cri_eva_criterio_evaluacionModel::find($id);
            $catAspEva= pdg_asp_aspectos_evaluativosModel::pluck("nombre_pdg_asp","id_pdg_asp");

            return view('catCriterios.edit',compact(['catCriterios'],'catAspEva'));
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
        $catCriterios=cat_cri_eva_criterio_evaluacionModel::find($id);

        $catCriterios->fill($request->all());
        $catCriterios->save();
        // Session::flash('message','Tipo Documento Modificado correctamente!');
        return Redirect::to('catCriterios');

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
        if ($userLogin->can(['catCriterios.destroy']))
        {
            try {
                cat_cri_eva_criterio_evaluacionModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('catCriterios');
            }
            Session::flash('message','Criterio Eliminado Correctamente!');
            return Redirect::to('catCriterios');

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
   }
}
