<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Caffeinated\Shinobi\Models\Permission;
use Caffeinated\Shinobi\Models\Role;

class PermissionController extends Controller
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
        $permisos =Permission::all();
        return view('permiso.index',compact('permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	return view('permiso.create');
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
            Permission::create
            ([
                'name'       	 => $request['name'],
                'description'    => $request['description'],
                'slug'  		 => $newSlg
            ]);

        Return redirect('permiso')->with('message','Permiso Registrado correctamente!') ;
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
        $permiso=Permission::find($id);
        return view('permiso.edit',compact(['permiso']));
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
        $permiso=Permission::find($id);
        $permiso->fill($request->all()); 
        $permiso->save();
        Session::flash('message','Permiso Modificado correctamente!');
        return Redirect::to('permiso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Permission::destroy($id);
        Session::flash('message','Permiso Eliminado Correctamente!');
        return Redirect::to('permiso');
    }
}
