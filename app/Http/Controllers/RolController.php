<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Permission;
use Caffeinated\Shinobi\Models\Role;

class RolController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
	/**
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles =Role::all();
        return view('rol.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$perfiles =tbl_perfil::lists('nombrePerfil', 'idPerfil');
       // return view('rol.create',compact('perfiles'));
    	return view('rol.create');
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
                'name' => 'required|max:50',
                'description' => 'required|max:250'
            ]);
            $slug=strtolower($request['name']);
            $newSlg=str_replace(' ','_',$slug);
            Role::create
            ([
                'name'       	 => $request['name'],
                'description'    => $request['description'],
                'slug'  		 => $newSlg
            ]);

        Return redirect('/rol')->with('message','Rol Registrado correctamente!') ;
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
        $rol=Role::find($id);
        return view('rol.edit',compact(['rol']));
       // $perfiles =tbl_perfil::lists('nombrePerfil', 'idPerfil');
    
        //return view('rol.edit',compact(['rol','perfiles']));
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
        $rol=Role::find($id);
        $rol->fill($request->all()); 
        $rol->save();
        Session::flash('message','Rol Modificado correctamente!');
        return Redirect::to('/rol');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);
        Session::flash('message','Rol Eliminado Correctamente!');
        return Redirect::to('/rol');
    }
}
