<?php

namespace App\Http\Controllers;
use Session;
use Redirect;
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
        $permisos =  Permission::pluck('name', 'id')->toArray();
    	return view('rol.create',compact('permisos'));
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
            $lastId =Role::create
            ([
                'name'       	 => $request['name'],
                'description'    => $request['description'],
                'slug'  		 => $newSlg
            ]);
            $rol= Role::find($lastId->id);
               foreach ($request['permiso'] as $permiso) {
                   $rol->assignPermission($permiso);
               }

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
        $permisos=$rol->getPermissions();
        $permisosBd = Permission::all();
        $select = "<select name='permiso[]' multiple ='multiple' class='form-control' id='permisos'>"; 
        foreach ($permisosBd as $permisoBd ) {
           $flag=0;
           foreach ($permisos as $permiso ) {
               if ($permisoBd->slug == $permiso ) {
                   $flag=1;
               }
           }
           if ($flag == 1) {
               $select .= "<option value='".$permisoBd->id."' selected>".$permisoBd->name."</option>"; 
           }else{
                $select .= "<option value='".$permisoBd->id."'>".$permisoBd->name."</option>"; 
           }
        }
        $select .= "</select>";
        return view('rol.edit',compact(['rol','select']));
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
        $rol->syncPermissions($request['permiso']);
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
        
        $rol=Role::find($id);
        $rol->revokeAllPermissions();
        Role::destroy($id);
        Session::flash('message','Rol Eliminado Correctamente!');
        return Redirect::to('/rol');
    }
}
