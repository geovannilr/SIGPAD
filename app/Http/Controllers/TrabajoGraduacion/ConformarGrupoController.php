<?php

namespace App\Http\Controllers\TrabajoGraduacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use \App\gen_UsuarioModel;
use \App\User;
use Caffeinated\Shinobi\Models\Permission;
use Caffeinated\Shinobi\Models\Role;

class ConformarGrupoController extends Controller
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
        $usuarios =gen_UsuarioModel::all();
        return view('usuario.index',compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    	return view('TrabajoGraduacion\ConformarGrupo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fecha=date('Y-m-d');
            $validatedData = $request->validate([
                'name' => 'required|max:50',
                'user' => 'required|unique:gen_usuario|max:30',
                'password' => 'required|confirmed|min:6',
                'email' => 'required|unique:gen_usuario|max:250|email',
                'rol' => 'required'
            ]);
           $lastId = gen_UsuarioModel::create
            ([
                'name'       	 => $request['name'],
                'user'       	 => $request['user'],
                'email'  		 => $request['email'],
                'password'       => $request['password']
            ]);
           
               $usuario= User::find($lastId->id);
               $usuario->assignRole($request['rol']);       
        Return redirect('/usuario')->with('message','Usuario Registrado correctamente!') ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $usuario = new gen_UsuarioModel();
       $test = $usuario->testSp();
       return var_dump($test);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario=gen_UsuarioModel::find($id);
        $user=User::find($id);
        $roles=$user->getRoles();
        return view('usuario.edit',compact(['usuario','roles']));
       // $perfiles =tbl_perfil::lists('nombrePerfil', 'idPerfil');
    
        //return view('usuario.edit',compact(['usuario','perfiles']));
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
        $usuario=gen_UsuarioModel::find($id);
        $usuario->fill($request->all()); 
        $usuario->save();
        Session::flash('message','Usuario Modificado correctamente!');
        return Redirect::to('/usuario');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        gen_UsuarioModel::destroy($id);
        Session::flash('message','Usuario Eliminado Correctamente!');
        return Redirect::to('/usuario');
    }

    public function getAlumno(Request $request) // FUNCION PARA COMBO DINAMICOS
    {
        if ($request->ajax()) {
           $usuario=gen_UsuarioModel::where('user', '=',$request['carnet'])->get();
           
           if (sizeof($usuario) == 0){
            return response()->json(['errorCode'=>1,'errorMessage'=>'No se encontró ningún alumno con ese Carnet','msg'=>""]);
           }else{
             return response()->json(['errorCode'=>0,'errorMessage'=>'Alumno agregado a grupo de Trabajo de Graduación','msg'=>$usuario]);
           }
           
        }
       
    }
}
