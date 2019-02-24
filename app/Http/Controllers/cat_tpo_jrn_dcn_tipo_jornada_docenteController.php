<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cat_tpo_jrn_dcn_tipo_jornada_docenteModel;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;

class cat_tpo_jrn_dcn_tipo_jornada_docenteController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catJornada.index'])) {
            $catJornada= cat_tpo_jrn_dcn_tipo_jornada_docenteModel::orderBy('orden_cat_tpo_jrn_dcn','asc')->get();
            return view('catJornada.index',compact('catJornada'));

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
        if ($userLogin->can(['catJornada.create'])) {
            return view('catJornada.create');
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
            'descripcion_cat_tpo_jrn_dcn' => 'required|max:100'
        ],
            [
                'descripcion_cat_tpo_jrn_dcn.required' => 'El idioma es requerido',
                'descripcion_cat_tpo_jrn_dcn.max' => 'El idioma debe contener como máximo 100 caracteres'
            ]
        );

        cat_tpo_jrn_dcn_tipo_jornada_docenteModel::create
        ([
            'descripcion_cat_tpo_jrn_dcn'=> $request['descripcion_cat_tpo_jrn_dcn']
        ]);

        Return redirect('catJornada')->with('message','Jornada Registrada correctamente!') ;
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
        if ($userLogin->can(['catJornada.edit'])) {
            $catJornada=cat_tpo_jrn_dcn_tipo_jornada_docenteModel::find($id);

            return view('catJornada.edit',compact(['catJornada']));
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
        $catJornada=cat_tpo_jrn_dcn_tipo_jornada_docenteModel::find($id);

        $catJornada->fill($request->all());
        $catJornada->save();
        Session::flash('message','Jornada Modificada correctamente!');
        return Redirect::to('catJornada');
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
        if ($userLogin->can(['catJornada.destroy']))
        {
            try {
                cat_tpo_jrn_dcn_tipo_jornada_docenteModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('catIdioma');
            }
            Session::flash('message','Jornada Eliminada Correctamente!');
            return Redirect::to('catJornada');

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }

    public function sort(Request $request){
        $data = $request['data'];
        $errorCode = -1;
        $errorMessage = "No se procesaron los datos";
        try{
            $errorMessage = "¡Orden actualizado satisfactoriamente!";
            $errorCode = cat_tpo_jrn_dcn_tipo_jornada_docenteModel::bulkUpdateOrder($data);
            $info = "nothing to show";
        }catch (Exception $exception){
            $info = $exception->getMessage();
            $errorCode = 1;
            $errorMessage = "Su solicitud no pudo ser procesada, intente más tarde.";
        }
        return response()->json(['errorCode'=>$errorCode,'errorMessage'=>$errorMessage,'info'=>$info]);
    }
}
