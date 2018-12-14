<?php

namespace App\Http\Controllers;
use App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class cat_tpo_tra_gra_tipo_trabajo_graduacionController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
                                }
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['tipoTrabajo.index'])) {

            $tipoTrabajo =cat_tpo_tra_gra_tipo_trabajo_graduacionModel::all();
            return view('tipoTrabajo.index',compact('tipoTrabajo'));
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
        if ($userLogin->can(['tipoTrabajo.create'])) {
            return view('tipoTrabajo.create');
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
            'nombre_cat_tpo_tra_gra' => 'required|max:45',
            'anio_cat_tpo_tra_gra' => 'required|max:4'

        ] ,[
                'nombre_cat_tpo_tra_gra.required' => 'El nombre del tipo de TDG es necesario',
                'nombre_cat_tpo_tra_gra.max' => 'El nombre del tipo de TDG debe contener como maximo 45 caracteres',
                'anio_cat_tpo_tra_gra.required' => 'Debe ingresar un año',
                'anio_cat_tpo_tra_gra.max' => 'El año del tipo TDG debe contener como maximo 4 caractéres'
                ]
);



        cat_tpo_tra_gra_tipo_trabajo_graduacionModel::create
        ([
            'nombre_cat_tpo_tra_gra'       	 => $request['nombre_cat_tpo_tra_gra'],
            'anio_cat_tpo_tra_gra'       	 => $request['anio_cat_tpo_tra_gra']

        ]);

        Return redirect('tipoTrabajo')->with('message','Tipo Trabajo Registrado correctamente!') ;

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
        if ($userLogin->can(['tipoTrabajo.edit'])) {
            $tipoTrabajo= cat_tpo_tra_gra_tipo_trabajo_graduacionModel::find($id);

            return view('tipoTrabajo.edit',compact(['tipoTrabajo']));
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
        $tipoTrabajo=cat_tpo_tra_gra_tipo_trabajo_graduacionModel::find($id);

        $tipoTrabajo->fill($request->all());
        $tipoTrabajo->save();
        Session::flash('message','Tipo Trabajo Modificado correctamente!');
        return Redirect::to('tipoTrabajo');

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
        if ($userLogin->can(['tipoTrabajo.destroy'])) {
            cat_tpo_tra_gra_tipo_trabajo_graduacionModel::destroy($id);
            Session::flash('message','Tipo Trabajo Eliminado Correctamente!');
            return Redirect::to('tipoTrabajo');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }

    }
}
