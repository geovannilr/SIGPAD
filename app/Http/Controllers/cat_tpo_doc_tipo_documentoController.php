<?php

namespace App\Http\Controllers;

use App\cat_tpo_doc_tipo_documentoModel;
use App\cat_eta_eva_etapa_evalutativaModel;
use App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
use App\rel_tpo_tra_eta_tipo_trabajo_etapaModel;
use App\rel_tpo_doc_eta_eva_tipo_documento_etapa_evaModel;
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
            $trabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::pluck('nombre_cat_tpo_tra_gra', 'id_cat_tpo_tra_gra')->toArray();
            $trabajosGraduacion = cat_tpo_tra_gra_tipo_trabajo_graduacionModel::all();
            $etapasEvaluativas = cat_eta_eva_etapa_evalutativaModel::getEtapasEvaluativas();
            $idTrabajo = 0;
            $idEtapa = 0;
            return view('tipoDocumento.create',compact(['trabajosGraduacion','etapasEvaluativas','trabajos','idTrabajo','idEtapa']));
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
            'anio_cat_pdg_tpo_doc' => 'required|max:4',
            'etapa' => 'required',
            'tipoTrabajo' => 'required'
        ],
            [
                'nombre_pdg_tpo_doc.required' => 'El nombre del tipo de documento es necesario',
                'nombre_pdg_tpo_doc.max' => 'El nombre dedel tipo de documento debe contener como maximo 45 caracteres',
                'descripcion_pdg_tpo_doc.required'=> 'La descripcion del tipo de documento es necesario',
                'descripcion_pdg_tpo_doc.max'=> 'La descripción del tipo de documento debe contener como maximo 100 caracteres',
                'puede_observar_cat_pdg_tpo_doc.required' => 'Saber si el tipo de documento puede ser observado debe ser posible',
                'puede_observar_cat_pdg_tpo_doc.max' => 'valor entre 0 y 1',
                'anio_cat_pdg_tpo_doc.required' => 'Debe ingresar un año',
                'anio_cat_pdg_tpo_doc.max' => 'El anio del tipo documento debe contener como maximo 4 caractéres',
                'etapa.required' => 'Debe seleccionar un tipo de trabajo de graduación.',
                'tipoTrabajo.required' => 'Debe seleccionar una etapa evaluativa.'
                ]

        );



        $lastId=cat_tpo_doc_tipo_documentoModel::create
        ([
            'nombre_pdg_tpo_doc'       	 => $request['nombre_pdg_tpo_doc'],
            'descripcion_pdg_tpo_doc'    => $request['descripcion_pdg_tpo_doc'],
            'anio_cat_pdg_tpo_doc'       => $request['anio_cat_pdg_tpo_doc']

        ]);

        rel_tpo_doc_eta_eva_tipo_documento_etapa_evaModel::create
        ([
            'id_cat_tpo_doc'         => $lastId->id_cat_tpo_doc,
            'id_cat_eta_eva'         => $request['etapa']
            
        ]);
        
        return redirect('tipoDocumento')->with('message','Tipo Documento Registrado correctamente!') ;

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
            $idTrabajo = 0;
            $idEtapa = 0;
            $tipoDocumento= cat_tpo_doc_tipo_documentoModel::find($id);
            $relacionDocEtapa = rel_tpo_doc_eta_eva_tipo_documento_etapa_evaModel::where('id_cat_tpo_doc','=',$id)->first();
            $idEtapa = $relacionDocEtapa->id_cat_eta_eva;
            $relacionEtapaTrabajo = rel_tpo_tra_eta_tipo_trabajo_etapaModel::where('id_cat_eta_eva','=',$idEtapa)->first();
            $idTrabajo = $relacionEtapaTrabajo->id_cat_tpo_tra_gra;
            $trabajos =  cat_tpo_tra_gra_tipo_trabajo_graduacionModel::pluck('nombre_cat_tpo_tra_gra', 'id_cat_tpo_tra_gra')->toArray();
            $trabajosGraduacion = cat_tpo_tra_gra_tipo_trabajo_graduacionModel::all();
            $etapasEvaluativas = cat_eta_eva_etapa_evalutativaModel::getEtapasEvaluativas();
            
            return view('tipoDocumento.edit',compact(['tipoDocumento','trabajosGraduacion','etapasEvaluativas','trabajos','idTrabajo','idEtapa']));
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
        $relDocEtapa=rel_tpo_doc_eta_eva_tipo_documento_etapa_evaModel::where('id_cat_tpo_doc','=',$id)->first();
        $relDocEtapa->id_cat_eta_eva = $request['etapa'];
        $tipoDocumento->fill($request->all());
        $tipoDocumento->save();
        $relDocEtapa->save();
        Session::flash('message','Tipo Documento Modificado correctamente!');
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
                $relDocEtapa = rel_tpo_doc_eta_eva_tipo_documento_etapa_evaModel::where('id_cat_tpo_doc','=',$id)->first();
                rel_tpo_doc_eta_eva_tipo_documento_etapa_evaModel::destroy($relDocEtapa->id_rel_tpo_doc_eta_eva);
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
