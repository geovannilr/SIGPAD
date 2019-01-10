<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use App\cat_ski_skillModel;
use App\cat_tpo_ski_tipo_skillModel;
class cat_ski_skillController extends Controller
{
    public function __construct(){
    $this->middleware('auth');
}
    public function index()
    {
        $userLogin=Auth::user();
        if ($userLogin->can(['catSki.index'])) {
            $catSki=cat_ski_skillModel::all();
            return view('catSki.index',compact('catSki'));
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
        if ($userLogin->can(['catSki.create'])) {
            $tpoSki = cat_tpo_ski_tipo_skillModel::pluck("descripcion_tpo_ski","id_tpo_ski");
            return view('catSki.create',compact('tpoSki'));
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
            'nombre_cat_ski' => 'required|max:45',
           ],
            [
                'nombre_cat_ski.required' => 'El nombre del skill es necesario',
                'nombre_cat_ski.max' => 'El nombre del skill debe contener como maximo 45 caracteres',
            ]
        );

//        return var_dump($request);
        cat_ski_skillModel::create
        ([
            'nombre_cat_ski'       	 => $request['nombre_cat_ski'],
            'id_tpo_ski'        => $request['id_tpo_ski']
        ]);

        Return redirect('catSki')->with('message','Skill Registrado correctamente!') ;

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
        if ($userLogin->can(['catSki.edit'])) {
            $catSki= cat_ski_skillModel::find($id);
            $tpoSki = cat_tpo_ski_tipo_skillModel::pluck("descripcion_tpo_ski","id_tpo_ski");

            return view('catSki.edit',compact(['catSki'],'tpoSki'));
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
        $catSki=cat_ski_skillModel::find($id);

        $catSki->fill($request->all());
        $catSki->save();
        // Session::flash('message','Tipo Documento Modificado correctamente!');
        return Redirect::to('catSki');
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
        if ($userLogin->can(['catSki.destroy'])) {
            cat_ski_skillModel::destroy($id);
            Session::flash('message','Skill Eliminado Correctamente!');
            return Redirect::to('catSki');
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
