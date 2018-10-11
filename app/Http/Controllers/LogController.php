<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Redirect;
use App\Http\Requests;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
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
        //if (Auth::attempt(['user'=>$request->usuario,'password'=>$request->password])) {
       /* if (Adldap::auth()->attempt($request->usuario, $request->password)) {
            
        return Redirect::to('/');
        }
        Session::flash('message-error', 'Usuario o Contraseña Incorrecta');
        return Redirect::to('login');*/
       // $test =  Adldap::auth()->attempt($request->usuario, $request->password); //No tenia el 'TRUE', pero fue prueba
//       $test =  Adldap::auth()->bind($request->usuario, $request->password);
//       $test =  Adldap::auth()->bind('rg12001', '604');
       // var_dump($test);
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
           // $grupoUsuario = $userInfoFull["objectclass"][6]; //ESTUDIANTE UES, VACATION
           // var_dump($splitDn);
            var_dump($arregloGruposUsuarios);


        } catch (Exception $e) {
            echo "failed";
        }
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
