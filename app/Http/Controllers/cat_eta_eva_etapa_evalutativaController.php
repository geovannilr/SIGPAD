<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use App\cat_eta_eva_etapa_evalutativaModel;
use App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
use App\rel_tpo_tra_eta_tipo_trabajo_etapaModel;



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
            $trabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::pluck('nombre_cat_tpo_tra_gra', 'id_cat_tpo_tra_gra')->toArray();
            return view('etapaEvaluativa.create',compact(['trabajos']));
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
            'ponderacion_cat_eta_eva' => 'required|max:6',
            'anio_cat_eta_eva' => 'required|max:4'
            ,'notagrupal_cat_eta_eva' => 'required',
            'tipoTrabajo'=>'required',
            'orden'=>'required'
        ],
            [
                'nombre_cat_eta_eva.required' => 'El nombre de la etapa evaluativa es necesario',
                'nombre_cat_eta_eva.max' => 'El nombre de la etapa evaluativa debe contener como maximo 45 caracteres',
                'ponderacion_cat_eta_eva.required'=> 'La ponderacion de la etapa evaluativa es necesario',
                'ponderacion_cat_eta_eva.max'=> 'La ponderacion de la etapa evaluativa debe contener como maximo 6 caracteres',
                'anio_cat_eta_eva.required' => 'El anio de  la etapa evaluativa es necesario',
                'anio_cat_eta_eva.max' => 'El anio de la etapa evaluativa debe contener como maximo 4 caracteres',
                'tiene_defensas_cat_eta_eva.required' => 'Si tiene defensas es necesario saberlo',
                'tiene_defensas_cat_eta_eva.max' => 'La cantidad maxima de caracteres para saber si tiene defensas no debe ser mayor a 11',
                'puede_observar_cat_eta_eva.required' => 'Saber si puede observar etapa evaluativa es necesario',
                'puede_observar_cat_eta_eva.max' => 'La cantidad maxima de caracteres para poder observar la etapa evaluativa es de 11'
                ,'notagrupal_cat_eta_eva.required' => 'Debe seleccionar si la etapa se evaluará de manera grupal o individual',
                'tipoTrabajo.required' => 'Debe seleccionar un tipo de Trabajo de Graduación.',
                'orden.required' => 'Debe ingresar un orden de la etapa evaluativa.'
            ]
            );



        $lastId = cat_eta_eva_etapa_evalutativaModel::create
        ([
            'nombre_cat_eta_eva'       	 => $request['nombre_cat_eta_eva'],
            'ponderacion_cat_eta_eva'       	 => $request['ponderacion_cat_eta_eva'],
            'anio_cat_eta_eva'       	 => $request['anio_cat_eta_eva'],
            'notagrupal_cat_eta_eva'    => $request['notagrupal_cat_eta_eva']

        ]);

        rel_tpo_tra_eta_tipo_trabajo_etapaModel::create
        ([
            'id_cat_tpo_tra_gra'        => $request['tipoTrabajo'],
            'id_cat_eta_eva'            => $lastId->id_cat_eta_eva,
            'orden_eta_eva'             => $request['orden']
            

        ]);
        
        return redirect('etapaEvaluativa')->with('message','Etapa Evaluativa Registrada correctamente!') ;

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
            $trabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::all();
            $relTrabajoEtapa = rel_tpo_tra_eta_tipo_trabajo_etapaModel::where('id_cat_eta_eva','=',$id)->first();
            $orden = $relTrabajoEtapa->orden_eta_eva;
            $select = '<select id="tipoTrabajo" name="tipoTrabajo" class="form-control">';
            foreach ($trabajos as $trabajo) {
                if ($trabajo->id_cat_tpo_tra_gra == $relTrabajoEtapa->id_cat_tpo_tra_gra) {
                    $select.='<option value="'.$trabajo->id_cat_tpo_tra_gra.'" selected="selected">'.$trabajo->nombre_cat_tpo_tra_gra.'</option>';
                }else{
                     $select.='<option value="'.$trabajo->id_cat_tpo_tra_gra.'">'.$trabajo->nombre_cat_tpo_tra_gra.'</option>';
                }
            }
            $select.='</select>';
            return view('etapaEvaluativa.edit',compact(['etapaEvaluativa','select','orden']));
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
        $relTrabajoEtapa = rel_tpo_tra_eta_tipo_trabajo_etapaModel::where('id_cat_eta_eva','=',$id)->first();
        $relTrabajoEtapa->id_cat_tpo_tra_gra =$request['tipoTrabajo'];
        $relTrabajoEtapa->orden_eta_eva = $request['orden'];
        $etapaEvaluativa->fill($request->all());
        $etapaEvaluativa->save();
        $relTrabajoEtapa->save();
        Session::flash('message','Etapa Evaluativa Modificada correctamente!');
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
        if ($userLogin->can(['etapaEvaluativa.destroy']))
            {
            try {
                $relTrabajoEtapa = rel_tpo_tra_eta_tipo_trabajo_etapaModel::where('id_cat_eta_eva','=',$id)->first();
                cat_eta_eva_etapa_evalutativaModel::destroy($id);
                rel_tpo_tra_eta_tipo_trabajo_etapaModel::destroy($relTrabajoEtapa->id_rel_tpo_tra_eta);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('etapaEvaluativa');
            }
                Session::flash('message','Etapa Evaluativa Eliminado Correctamente!');
                return Redirect::to('etapaEvaluativa');

            }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
