<?php

namespace App\Http\Controllers;

use App\cat_ctg_tra_categoria_trabajo_graduacionModel;
use App\cat_tpo_ski_tipo_skillModel;
use App\rel_cat_tpo_ski_cat_ctg_traModel;
use Illuminate\Http\Request;
use Session;
use Redirect;

use Illuminate\Support\Facades\Auth;

class cat_ctg_tra_categoria_trabajo_graduacionController extends Controller
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
        if ($userLogin->can(['categoriaTDG.index'])) {
            $categoriasTDG = cat_ctg_tra_categoria_trabajo_graduacionModel::all();
            return view('categoriaTDG.index',compact('categoriasTDG'));

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
        if ($userLogin->can(['categoriaTDG.create'])) {
            $tiposSkill =  cat_tpo_ski_tipo_skillModel::pluck('descripcion_tpo_ski', 'id_tpo_ski')->toArray();
            return view('categoriaTDG.create',compact(['tiposSkill']));
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
            'nombre_cat_ctg_tra' => 'required|max:45',
            'tipoSkill'=>'required'
        ],
            [
                'nombre_cat_ctg_tra.required' => 'El nombre de la categoria de trabajo de graduación es necesario',
                'nombre_cat_ctg_tra.max' => 'El nombre de la categoria de trabajo de graduación debe contener como maximo 45 caracteres',
                'tipoSkill.required' => 'Debe seleccionar al menos un tipo de habilidad'
            ]
            );
        $tiposSkill = $request['tipoSkill'];

        $lastId = cat_ctg_tra_categoria_trabajo_graduacionModel::create
        ([
            'nombre_cat_ctg_tra'=> $request['nombre_cat_ctg_tra']
        ]);

        foreach ($tiposSkill as $tipoSkill ) {
        rel_cat_tpo_ski_cat_ctg_traModel::create
            ([
                'id_tpo_ski'=> $tipoSkill ,
                'id_cat_ctg_tra'=> $lastId->id_cat_ctg_tra
            ]);
        }


        Return redirect('categoriaTDG')->with('message','Categoría Registrada correctamente!') ;
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
        if ($userLogin->can(['categoriaTDG.edit'])) {
            $categoriaTDG=cat_ctg_tra_categoria_trabajo_graduacionModel::find($id);
            $tiposSkillBd =  cat_tpo_ski_tipo_skillModel::all();
            $tiposSkill=rel_cat_tpo_ski_cat_ctg_traModel::where('id_cat_ctg_tra','=',$categoriaTDG->id_cat_ctg_tra)->get();
            $select = "<select name='tiposSkill[]' multiple ='multiple' class='form-control' id='tiposSkill'>"; 
            foreach ($tiposSkillBd as $tipoSkillBd ) {
               $flag=0;
               foreach ($tiposSkill as $tipoSkill ) {
                   if ($tipoSkillBd->id_tpo_ski == $tipoSkill->id_tpo_ski ){
                       $flag=1;
                   }
               }
               if ($flag == 1) {
                   $select .= "<option value='".$tipoSkillBd->id_tpo_ski."' selected>".$tipoSkillBd->descripcion_tpo_ski."</option>"; 
               }else{
                    $select .= "<option value='".$tipoSkillBd->id_tpo_ski."'>".$tipoSkillBd->descripcion_tpo_ski."</option>"; 
               }
            }
            $select .= "</select>";
            return view('categoriaTDG.edit',compact(['categoriaTDG','select']));
        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }


    public function update(Request $request, $id)
    {

        $categoriaTDG=cat_ctg_tra_categoria_trabajo_graduacionModel::find($id);

        $categoriaTDG->fill($request->all());
        $categoriaTDG->save();
        $tiposSkill=$request['tiposSkill'];
        //BORRAMOS Y LUEGO VOLVEMOS A HACER LOS INSERT DE LOS TIPOS DE SKILL ASOCIADOS
        $tiposSkillCat = rel_cat_tpo_ski_cat_ctg_traModel::where('id_cat_ctg_tra','=',$id)->get();
        foreach ($tiposSkillCat as $tipo ) {
            rel_cat_tpo_ski_cat_ctg_traModel::destroy($tipo->id_rel_cat_tpo_ski_cat_ctg_tra);
        }
        foreach ($tiposSkill as $tipoSkill ) {
        rel_cat_tpo_ski_cat_ctg_traModel::create
            ([
                'id_tpo_ski'=> $tipoSkill ,
                'id_cat_ctg_tra'=> $id
            ]);
        }
        Session::flash('message','Categoría de TDG Modificada correctamente!');
        return Redirect::to('categoriaTDG');
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

        if ($userLogin->can(['categoriaTDG.destroy']))
        {
            try {
                cat_ctg_tra_categoria_trabajo_graduacionModel::destroy($id);
            } catch (\PDOException $e)
            {
                Session::flash('message-error', 'No es posible eliminar este registro, está siendo usado.');
                return Redirect::to('categoriaTDG');
            }
            Session::flash('message','Categoria Eliminada Correctamente!');
            return Redirect::to('categoriaTDG');

        }else{
            Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
            return  view('template');
        }
    }
}
