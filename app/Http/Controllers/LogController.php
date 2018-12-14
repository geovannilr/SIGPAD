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
use \App\pdg_gru_grupoModel;
use \App\pdg_dcn_docenteModel;
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
        $usuario = $request->usuario;
        $contrasena = $request->password;

        $existe = gen_UsuarioModel::where("user","=",$usuario)->exists();
        if(!$existe){
            Session::flash('message-error', 'Usuario no se encuentra registrado.');
            return Redirect::to('login');
        }else{
            $intentoLogin = Auth::attempt(['user'=>$usuario,'password'=>$contrasena]);
            if ($intentoLogin) {
                self::configuracionInicial();
                return Redirect::to('/');
            }else{
                $opciones = config('services.ldapues');
                try{
                    //Bindeo() return TRUE/FALSE
                    $ldap = new Ldap($opciones);
                    $ldap -> bind($usuario, $contrasena);

                    //Borrar contrasenha original de memoria
                    unset($contrasena);

                    //UpdatePassword() return TRUE/FALSE
                    $usuarioLocal = gen_UsuarioModel::where("user","=",$usuario)->first();
                    $contrasenaGenerica = gen_UsuarioModel::genericPassword(12);
                    $usuarioLocal->password = $contrasenaGenerica;
                    $usuarioLocal->save();

                    //LoginAttempt() return redirect()
                    $intentoLogin = Auth::attempt(['user'=>$usuario,'password'=>$contrasenaGenerica]);
                    if ($intentoLogin) {
                        self::configuracionInicial();
                        return Redirect::to('/');
                    }else{
                        Session::flash('message-error', 'Problemas de conexión, intente nuevamente!');
                        return Redirect::to('login');
                    }
                }catch(\Exception $e){
                    Session::flash('message-error', 'Usuario o Contraseña Incorrecta, intente nuevamente.');
                    return Redirect::to('login');
                }
            }
        }
        //CONEXION AL LDAP
//        $options = array(
//            'host' => 'ldap.ues.edu.sv',
//            'bindRequiresDn' => true,
//            'accountDomainName' => 'ues.edu.sv',
//            'baseDn' => 'OU=usuarios,DC=ues,DC=edu,DC=sv',);
//        try {
//            $ldap = new Ldap($options);
//            $ldap -> bind($request->usuario, $request->password);
//            $info = $ldap->search('cn=*');
//            $userInfo = $info->toArray();
//            $userInfoFull = $userInfo[0]; //
//            $nombreCompleto =  $userInfoFull["displayname"][0];
//            $nombres = $userInfoFull["cn"][0];
//            $apellidos = $userInfoFull["sn"][0];
//            $cortarNombres = explode(" ",$nombres);
//            $cortarApellidos = explode(" ",$apellidos);
//            $mail = $userInfoFull["mail"][0];
////            $carrera = $userInfoFull["svuescarrera"][0]; //ESTE ATRIBUTO NO VIENE EN EL ADMINISTRADOR.EISI, CUIDADO! Primero debe verificarse el tipo de usuario
//            $dn = $userInfoFull["dn"];
//            $splitDn=explode(",",$dn);
//            $arregloGruposUsuarios=[];
//            foreach ($splitDn as  $value) {
//                if (strpos($value,"ou")!==false) {
//                    $cut = explode("ou=",$value);
//                    $arregloGruposUsuarios[]=$cut[1];
//                }
//            }
//            var_dump($userInfoFull);
//            return;
//        }catch (\Exception $e) {
//            //VERIFICAR SI ES USUARIO DEL SISTEMA INTERNO (NO ESTUDIANTE, NO DOCENTE)
//             if (Auth::attempt(['user'=>$request->usuario,'password'=>$request->password])) {
//                    return Redirect::to('/');
//            }
//                Session::flash('message-error', 'Usuario o Contraseña Incorrecta:'.$e->getMessage());
//                return Redirect::to('login');
//             Session::flash('message-error', 'Usuario o Contraseña Incorrecta');
//            return Redirect::to('login');
//        }
//            //VERIFICAR SI EL USUARIO EXISTE EN LA BASE DE DATOS , SINO REGISTRARLO COMO ESTUDIANTE
//            //$usuarioLogin = gen_UsuarioModel::where("user","=",$request->usuario)->first();
//            if (!gen_UsuarioModel::where("user","=",$request->usuario)->exists()) {
//
//                    $lastIdUsuario = gen_UsuarioModel::create
//                ([
//                    'name'                   => $nombreCompleto,
//                    'user'                   => $request->usuario,
//                    'email'                  => $mail,
//                    'password'               => $request->password,
//                    'primer_nombre'          => $cortarNombres[0],
//                    'segundo_nombre'         => $cortarNombres[1],
//                    'primer_apellido'        => $cortarApellidos[0],
//                    'segundo_apellido'       => $cortarApellidos[1],
//                    'codigo_carnet'          => $request->usuario
//
//                ]);
//
//                //VERIFICAR SI ES ESTUDIANTE O DOCENTE SEGUN PARAMETRO LDAP (CONSULTAR PARAMETRO)
//                //VERIFICAR QUE SON DOCENTES O ALUMNOS DE LA FACULTAD
//                //******************************************************************************
//                 $lastIdEstudiante = gen_EstudianteModel::create
//                ([
//                    'id_gen_usr'                   => $lastIdUsuario->id,
//                    'carnet_gen_est'               => $request->usuario,
//                    'nombre_gen_est'               => $nombreCompleto
//
//                ]);
//
//                 $usuarioX= User::find($lastIdUsuario->id);
//                 //CREAR ROL GENERICO CON PERMISOS PARA ESTUDIANTE GENERAL
//                 $usuarioX->assignRole('estudiante');
//            }else{
//                $usuarioLogin = gen_UsuarioModel::where("user","=",$request->usuario)->first();
//                $usuarioLogin = gen_UsuarioModel::find($usuarioLogin->id);
//                $usuarioLogin->password = $request->password;
//                $usuarioLogin->save();
//
//                //GUARDAMOS LA CONTRASEÑA CON LA QUE HIZO BIND AL LDAP
//            }
//             if (Auth::attempt(['user'=>$request->usuario,'password'=>$request->password])) {
//                    return Redirect::to('/');
//                }
//                Session::flash('message-error', 'Usuario o Contraseña Incorrecta');
//                return Redirect::to('login');
//
//           // $grupoUsuario = $userInfoFull["objectclass"][6]; //ESTUDIANTE UES, VACATION
//           // var_dump($splitDn);
//           // var_dump($arregloGruposUsuarios);

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

    public function configuracionInicial(){
        if (Auth::user()->isRole('docente_asesor')){
            //OBTENEMOS LOS GRUPOS QUE ESTA DIRIGIENDO COMO ASESOR
            $userLogin=Auth::user();
            $docente = pdg_dcn_docenteModel::where("id_gen_usuario","=",$userLogin->id)->first();
            $grupo = new pdg_gru_grupoModel();
            $grupos = $grupo->getGruposDocente($docente->id_pdg_dcn);
            $i=0;
            $misGrupos="";
            foreach ($grupos as $grupo) {
                if ($i==0) {
                    $misGrupos.=$grupo->ID;
                }else{
                    $misGrupos.=",".$grupo->ID;
                }
                $i=1;
            }
            if ($misGrupos=="") {
                session(['misGrupos' => "NA"]);
            }else{
                // GUARDAMOS EN UNA VARIABLE DE SESION LOS ID DE LOS GRUPOS QUE LE CORRESPONDEN COMO DOCENTE
                session(['misGrupos' => $misGrupos]);
            }
        }
    }
}
