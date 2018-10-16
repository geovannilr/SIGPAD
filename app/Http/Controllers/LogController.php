<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Redirect;
use App\Http\Requests;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use \App\gen_UsuarioModel;
use \App\gen_EstudianteModel;
use \App\User;
use Illuminate\Support\Facades\Hash;
use Caffeinated\Shinobi\Models\Role;
use Zend\Ldap\Ldap;

class LogController extends Controller
{

   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //CONEXION AL LDAP
        $options = array(
            'host' => 'ldap.ues.edu.sv',
            'bindRequiresDn' => true,
            'accountDomainName' => 'ues.edu.sv',
            'baseDn' => 'OU=usuarios,DC=ues,DC=edu,DC=sv',);
        try {
            $ldap = new Ldap($options);
            $ldap -> bind($request->usuario, $request->password);
            $info = $ldap->search('cn=*');
            $userInfo = $info->toArray();
            $userInfoFull = $userInfo[0]; //
            $nombreCompleto =  $userInfoFull["displayname"][0];
            $nombres = $userInfoFull["cn"][0];
            $apellidos = $userInfoFull["sn"][0];
            $cortarNombres = explode(" ",$nombres);
            $cortarApellidos = explode(" ",$apellidos);
            $mail = $userInfoFull["mail"][0];
            $carrera = $userInfoFull["svuescarrera"][0];
            $dn = $userInfoFull["dn"];
            $splitDn=explode(",",$dn);
            $arregloGruposUsuarios=[];
            foreach ($splitDn as  $value) {
                if (strpos($value,"ou")!==false) {
                    $cut = explode("ou=",$value);
                    $arregloGruposUsuarios[]=$cut[1];
                }
            }
            var_dump($userInfoFull);
            return;
        }catch (\Exception $e) {
            //VERIFICAR SI ES USUARIO DEL SISTEMA INTERNO (NO ESTUDIANTE, NO DOCENTE)
             if (Auth::attempt(['user'=>$request->usuario,'password'=>$request->password])) {
                    return Redirect::to('/');
            }
             Session::flash('message-error', 'Usuario o Contraseña Incorrecta');
            return Redirect::to('login');
        }
            //VERIFICAR SI EL USUARIO EXISTE EN LA BASE DE DATOS , SINO REGISTRARLO COMO ESTUDIANTE
            //$usuarioLogin = gen_UsuarioModel::where("user","=",$request->usuario)->first();
            if (!gen_UsuarioModel::where("user","=",$request->usuario)->exists()) {

                    $lastIdUsuario = gen_UsuarioModel::create
                ([
                    'name'                   => $nombreCompleto,
                    'user'                   => $request->usuario,
                    'email'                  => $mail,
                    'password'               => $request->password,
                    'primer_nombre'          => $cortarNombres[0],
                    'segundo_nombre'         => $cortarNombres[1],
                    'primer_apellido'        => $cortarApellidos[0],
                    'segundo_apellido'       => $cortarApellidos[1],
                    'codigo_carnet'          => $request->usuario

                ]);
                
                //VERIFICAR SI ES ESTUDIANTE O DOCENTE SEGUN PARAMETRO LDAP (CONSULTAR PARAMETRO)
                //VERIFICAR QUE SON DOCENTES O ALUMNOS DE LA FACULTAD
                //******************************************************************************
                 $lastIdEstudiante = gen_EstudianteModel::create
                ([
                    'id_gen_usr'                   => $lastIdUsuario->id,
                    'carnet_gen_est'               => $request->usuario,
                    'nombre_gen_est'               => $nombreCompleto

                ]); 

                 $usuario= User::find($lastIdUsuario->id);
                 //CREAR ROL GENERICO CON PERMISOS PARA ESTUDIANTE GENERAL
                 $usuario->assignRole('estudiante');
            }else{
                $usuarioLogin = gen_UsuarioModel::where("user","=",$request->usuario)->first();
                $usuarioLogin = gen_UsuarioModel::find($usuarioLogin->id);
                $usuarioLogin->password = $request->password;
                $usuarioLogin->save();

                //GUARDAMOS LA CONTRASEÑA CON LA QUE HIZO BIND AL LDAP
            }
             if (Auth::attempt(['user'=>$request->usuario,'password'=>$request->password])) {
                    return Redirect::to('/');
                }
                Session::flash('message-error', 'Usuario o Contraseña Incorrecta');
                return Redirect::to('login');
           
           // $grupoUsuario = $userInfoFull["objectclass"][6]; //ESTUDIANTE UES, VACATION
           // var_dump($splitDn);
           // var_dump($arregloGruposUsuarios);


       
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function logout()
    {
        Auth::logout();
        return Redirect::to('login');
    }
}
