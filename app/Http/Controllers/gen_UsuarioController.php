<?php

namespace App\Http\Controllers;

use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Session;
use \App\gen_UsuarioModel;
use \App\User;
use File;

class gen_UsuarioController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$userLogin = Auth::user();
		if ($userLogin->can(['usuario.index'])) {
			$usuarios = gen_UsuarioModel::all();
			foreach ($usuarios as $usuario) {
				$user = User::find($usuario->id);
				$roles = $user->getRoles();
				$linea = "";
				foreach ($roles as $rol) {
					$linea .= $rol . "#";
				}
				$username = $usuario->user;
				$rolesView[$username] = $linea;
			}
			return view('usuario.index', compact('usuarios', 'rolesView'));
		} else {
			Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
			return view('template');
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$userLogin = Auth::user();
		if ($userLogin->can(['usuario.create'])) {
			$roles = Role::pluck('name', 'id')->toArray();
			return view('usuario.create', compact('roles'));
		} else {
			Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
			return view('template');
		}

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$fecha = date('Y-m-d');
		$validatedData = $request->validate([
			'name' => 'required|max:50',
			'user' => 'required|unique:gen_usuario|max:30',
			'password' => 'required|confirmed|min:6',
			'email' => 'required|unique:gen_usuario|max:250|email',
			'rol' => 'required',
		]);
		$lastId = gen_UsuarioModel::create
			([
			'name' => $request['name'],
			'user' => $request['user'],
			'email' => $request['email'],
			'password' => $request['password'],
		]);

		$usuario = User::find($lastId->id);
		foreach ($request['rol'] as $rol) {
			$usuario->assignRole($rol);
		}
		//$usuario->assignRole($request['rol']);
		Return redirect('/usuario')->with('message', 'Usuario Registrado correctamente!');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {

		$userLogin = Auth::user();
		if ($userLogin->can(['usuario.edit'])) {
			$usuario = gen_UsuarioModel::find($id);
			$user = User::find($id);
			$roles = $user->getRoles();
			$rolesBd = Role::all();
			$select = "<select name='rol[]' multiple ='multiple' class='form-control' id='roles'>";
			foreach ($rolesBd as $rolBd) {
				$flag = 0;
				foreach ($roles as $rol) {
					if ($rolBd->slug == $rol) {
						$flag = 1;
					}
				}
				if ($flag == 1) {
					$select .= "<option value='" . $rolBd->id . "' selected>" . $rolBd->name . "</option>";
				} else {
					$select .= "<option value='" . $rolBd->id . "'>" . $rolBd->name . "</option>";
				}
			}
			$select .= "</select>";
			return view('usuario.edit', compact(['usuario', 'select', 'roles']));
		} else {
			Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
			return view('template');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		if ($request['password'] != "") {
			$validatedData = $request->validate([
			'name' => 'required|max:50',
			'user' => 'required|max:30',
			'email' => 'required|max:250|email',
			'password' => 'required|confirmed|min:6',
			'rol' => 'required',
			]);
		}else{
			$validatedData = $request->validate([
			'name' => 'required|max:50',
			'user' => 'required|max:30',
			'email' => 'required|max:250|email',
			'rol' => 'required',
			]);
		}
		
		$usuario = gen_UsuarioModel::find($id);
		$user = User::find($id);
		$usuario->fill($request->all());
		$usuario->save();
		$user->syncRoles($request['rol']);
		Session::flash('message', 'Usuario Modificado correctamente!');
		return Redirect::to('/usuario');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$userLogin = Auth::user();
		if ($userLogin->can(['usuario.destroy'])) {
			$user = User::find($id);
			$user->revokeAllRoles();
			gen_UsuarioModel::destroy($id);
			Session::flash('message', 'Usuario Eliminado Correctamente!');
			return Redirect::to('/usuario');
		} else {
			Session::flash('message-error', 'No tiene permisos para acceder a esta opción');
			return view('template');
		}

	}
	public function createUsuarios() {
		return view('usuario.CargaUsuarios.create');
	}
	public function storeUsuarios(Request $request) {

		$validatedData = $request->validate([
			'documentoUsuarios' => 'required',
		]);
		$bodyHtml = '';
		$data = Excel::load($request->file('documentoUsuarios'), function ($reader) {
			$reader->setSelectedSheetIndices(array(0));
		})->get();
		$usuarios = $data->toArray();
		//return var_dump($usuarios);
		if (sizeof($usuarios) != 0) {
			foreach ($usuarios as $usuario) {
				//return var_dump($usuario);
				if (!is_null($usuario["usuario"]) && !is_null($usuario["nombres"]) && !is_null($usuario["apellidos"]) && !is_null($usuario["rol"])) {
					//Verificamos si esta repetido
					$user  = User::where('user','=',$usuario["usuario"])->first();
					//Verificamos si el rol ingresado se encuentra en el catalogo
					$rol = Role::find($usuario["rol"]);
					if (!empty($user->id)){
						//Usuario repetido
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usuario["usuario"] . '</td>';
						$bodyHtml .= '<td>' . $usuario["nombres"] . ' ' . $usuario["apellidos"] . '</td>';
						$bodyHtml .= '<td>' .  "N/A"  . '</td>';
						$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
						$bodyHtml .= '<td>El usuario que esta intentando ingresar ya se encuentra registrado.</td>';
						$bodyHtml .= '</tr>';
					} else if (empty($rol->id)) {
						//el rol ingresado es incorrecto y no esta registrado
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usuario["usuario"] . '</td>';
						$bodyHtml .= '<td>' . $usuario["nombres"] . ' ' . $usuario["apellidos"] . '</td>';
						$bodyHtml .= '<td>' . "N/A" . '</td>';
						$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
						$bodyHtml .= '<td>El rol ingresado es incorrecto o no existe</td>';
						$bodyHtml .= '</tr>';
					} else {
						//Insertamos el usuario
						$lastIdUser = gen_UsuarioModel::create
		                    ([
		                        'name' => $usuario["nombres"]." ".$usuario["apellidos"],
								'user' => $usuario["usuario"],
								'email' => $usuario["usuario"]."@ues.edu.sv",
								'password' => $usuario["usuario"]."*".date("Y"),
								'primer_nombre' => $usuario["nombres"],
								'segundo_nombre' => "",
								'primer_apellido' => $usuario["apellidos"],
								'segundo_apellido' => "",
								'codigo_carnet' => $usuario["usuario"]  
		                    ]);

		                //ASIGNAMOS EL ROL RESPECTIVO
		                $usuarioIngresado = User::find($lastIdUser->id);
						$usuarioIngresado->assignRole($rol->id);
						
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usuario["usuario"] . '</td>';
						$bodyHtml .= '<td>' . $usuario["nombres"] . ' ' . $usuario["apellidos"] . '</td>';
						$bodyHtml .= '<td>' . $rol->name . '</td>';
						$bodyHtml .= '<td><span class="badge badge-success">OK</span></td>';
						$bodyHtml .= '<td>Usuario ingresado exitosamente</td>';
						$bodyHtml .= '</tr>';
					}

				}

			}
	
		}

		return view('usuario.CargaUsuarios.index', compact('bodyHtml'));

	}
	public function storeUsuariosUesplay(Request $request) {

		$validatedData = $request->validate([
			'documentoUsuarios' => 'required',
		]);
		$bodyHtml = '';
		$data = Excel::load($request->file('documentoUsuarios'), function ($reader) {
			$reader->setSelectedSheetIndices(array(0));
		})->get();
		$usuarios = $data->toArray();
		if (sizeof($usuarios) != 0) {
			include app_path() . '/Exceptions/conexionMysqli.php';
			foreach ($usuarios as $usuario) {
				if (!is_null($usuario["usuario"]) && !is_null($usuario["nombres"]) && !is_null($usuario["apellidos"]) && !is_null($usuario["role"])) {
				    //variables actuales del loop
				    $usrAct = $usuario["usuario"];
				    $nomAct = $usuario["nombres"];
				    $apeAct = $usuario["apellidos"];
				    $rolAct = $usuario["role"];
					//Verificamos si esta repetido
					$query = 'SELECT alias FROM usuario where alias="' . $usrAct . '"';
					$resultado = $mysqli->query($query);
					$countUsuario = $resultado->num_rows;
					//Verificamos si el rol ingresado se encuentra en el catalogo
					$query = 'SELECT * FROM rol where idRol="' . $rolAct . '"';
					$resultado = $mysqli->query($query);
					$countRol = $resultado->num_rows;
					$rol = $resultado->fetch_assoc();
					if ($countUsuario > 0) {
						//Usuario repetido
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usrAct . '</td>';
						$bodyHtml .= '<td>' . $nomAct . ' ' . $apeAct . '</td>';
						$bodyHtml .= '<td>' . $rol["nombre"] . '</td>';
						$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
						$bodyHtml .= '<td>El usuario que esta intentando ingresar ya se encuentra registrado.</td>';
						$bodyHtml .= '</tr>';
					} else if ($countRol == 0) {
						//el rol ingresado es incorrecto y no esta registrado
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usrAct . '</td>';
						$bodyHtml .= '<td>' . $nomAct . ' ' . $apeAct . '</td>';
						$bodyHtml .= '<td>' . $rol["nombre"] . '</td>';
						$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
						$bodyHtml .= '<td>El rol ingresado es incorrecto o no existe</td>';
						$bodyHtml .= '</tr>';
					} else {
						//Insertamos el usuario
						$query = 'INSERT INTO usuario values(
							0,
							"' . $usrAct . '",
							"password",
							"' . $nomAct . '",
							"' . $apeAct . '"
						);';
						$mysqli->query($query);
						$idUsuario = $mysqli->insert_id; //último id de usuario ingresado

						//Le asociamos el rol corespondiente al usuario creado
						$query = 'INSERT INTO usuario_rol values(
							"' . $idUsuario . '",
							"' . $rolAct . '"
						);';
						$mysqli->query($query);

						$extraInfo="";
						if(!is_null($usuario["esautor"])){
						    $check = $usuario["esautor"];
						    if(strtolower($check)=='x'){
                                //Verificando que no exista como autor anteriormente
                                $query = 'SELECT * FROM autor WHERE carnet="'.$usrAct.'"';
                                $resultado = $mysqli->query($query);
                                $countAutor = $resultado->num_rows;
                                if($countAutor>0){
                                    //Ya existía como autor
                                    $extraInfo = ". <i>No pudo registrarse como autor</i>>";
                                }else{
                                    //Insertamos como autor
                                    $query = 'INSERT INTO autor VALUES(NULL,"'.$usrAct.'","'.$nomAct.' '.$apeAct.'");';
                                    $mysqli->query($query);
                                    $idAutor = $mysqli->insert_id;
                                    $extraInfo = ", también fue registrado como autor. Código generado: <b>".$idAutor."</b>";
                                }
                            }
                        }
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usrAct . '</td>';
						$bodyHtml .= '<td>' . $nomAct . ' ' . $apeAct . '</td>';
						$bodyHtml .= '<td>' . $rol["nombre"] . '</td>';
						$bodyHtml .= '<td><span class="badge badge-success">OK</span></td>';
						$bodyHtml .= '<td>Usuario ingresado exitosamente'.$extraInfo.'</td>';
						$bodyHtml .= '</tr>';
					}

				}

			}
			$mysqli->close();

		}

		return view('uesplay.index', compact('bodyHtml'));

	}
	public function createUsuariosUesPlay() {
		$userLogin = Auth::user();
		if ($userLogin->can(['uesplay.cargar'])) {
			return view('uesplay.create');
		}else{
			Session::flash('message-error','No tiene permisos para acceder a esta opción.');
            return redirect('/');
		}
		
	}
    function downloadPlantillaUesplay(Request $request){
        $path= public_path().$_ENV['PATH_RECURSOS'].'temp-usuarios-uesplay.xlsx';
        if (File::exists($path)){
            return response()->download($path);
        }else{
            Session::flash('error','El documento no se encuentra disponible , es posible que haya sido  borrado');
            return view('PerfilDocente.create');
        }
    }

    public function storeUsuariosCatUesplay(Request $request) {

		$validatedData = $request->validate([
			'documentoUsuarios' => 'required',
		]);
		$bodyHtml = '';
		$data = Excel::load($request->file('documentoUsuarios'), function ($reader) {
			$reader->setSelectedSheetIndices(array(0));
		})->get();
		$usuarios = $data->toArray();
		if (sizeof($usuarios) != 0) {
			include app_path() . '/Exceptions/conexionMysqli.php';
			foreach ($usuarios as $usuario) {
				if (!is_null($usuario["usuario"]) && !is_null($usuario["categoria"])) {
					//Verificamos si usuario existe
					$query = 'SELECT * FROM usuario where alias="' . $usuario["usuario"] . '"';
					$resultado = $mysqli->query($query);
					$countUsuario = $resultado->num_rows;
					$resultadoUsuario = $resultado->fetch_assoc();
					//Verificamos si la categoía ingresada se encuentra en el catalogo
					$query = 'SELECT * FROM categoria where nombre ="' . $usuario["categoria"] . '"';
					$resultado = $mysqli->query($query);
					$countCategoria = $resultado->num_rows;
					$categoria = $resultado->fetch_assoc();

					if ($countCategoria > 0 && $countUsuario > 0 ) {
						//Verificamos si ya se encuentra el usuario asociado a la categoria
						$query = 'SELECT * FROM usuario_categoria where idUsuario ="' . $resultadoUsuario["idUsuario"] . '" AND idCategoria ="' . $categoria["idCategoria"] . '" ';
						$resultado = $mysqli->query($query);
						$countUsuarioCategoria = $resultado->num_rows;
					}
					
					if ($countUsuario ==  0) {
						//Usuario no ha sido ingresado previamente
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usuario["usuario"] . '</td>';
						$bodyHtml .= '<td>N/A</td>';
						$bodyHtml .= '<td>' . $usuario["categoria"] . '</td>';
						$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
						$bodyHtml .= '<td>El usuario que esta intentando ingresar <b>NO</b> se encuentra registrado.</td>';
						$bodyHtml .= '</tr>';
					} else if ($countCategoria == 0) {
						//la categoría ingresada es incorrecta y no esta registrada
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usuario["usuario"] . '</td>';
						$bodyHtml .= '<td>' . $resultadoUsuario["nombre"] . ' ' . $resultadoUsuario["apellido"] . '</td>';
						$bodyHtml .= '<td>N/A</td>';
						$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
						$bodyHtml .= '<td>La Categoría  ingresada es incorrecta o no existe</td>';
						$bodyHtml .= '</tr>';
					}elseif ($countUsuarioCategoria > 0) {
						//registro repetido 
						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usuario["usuario"] . '</td>';
						$bodyHtml .= '<td>' . $resultadoUsuario["nombre"] . ' ' . $resultadoUsuario["apellido"] . '</td>';
						$bodyHtml .= '<td>' . $categoria["nombre"] . '</td>';
						$bodyHtml .= '<td><span class="badge badge-danger">Error</span></td>';
						$bodyHtml .= '<td>Este usuario ya se encuentra asociada a la categoría seleccionada.</td>';
						$bodyHtml .= '</tr>';
					} else {
						//Insertamos el usuario
						$query = 'INSERT INTO usuario_categoria values(
							0,
							"' . $resultadoUsuario["idUsuario"] . '",
							"' . $categoria["idCategoria"] . '"
						);';
						$mysqli->query($query);


						$bodyHtml .= '<tr>';
						$bodyHtml .= '<td>' . $usuario["usuario"] . '</td>';
						$bodyHtml .= '<td>' . $resultadoUsuario["nombre"] . ' ' . $resultadoUsuario["apellido"] . '</td>';
						$bodyHtml .= '<td>' . $categoria["nombre"] . '</td>';
						$bodyHtml .= '<td><span class="badge badge-success">OK</span></td>';
						$bodyHtml .= '<td>Usuario asociado a categoría exitosamente</td>';
						$bodyHtml .= '</tr>';
					}

				}

			}
			$mysqli->close();

		}

		return view('uesplay.indexUsuarioCategoria', compact('bodyHtml'));

	}
	public function createUsuariosCatUesPlay() {
		$userLogin = Auth::user();
		if ($userLogin->can(['uesplay.cargar'])) {
			return view('uesplay.createUsuarioCategoria');
		}else{
			Session::flash('message-error','No tiene permisos para acceder a esta opción.');
            return redirect('/');
		}
	}

    public function downloadPlantillaSigpad(){
        $path= public_path().$_ENV['PATH_RECURSOS'].'temp-usuarios-sigpad.xlsx';
        if (File::exists($path)){
            return response()->download($path);
        }else{
            Session::flash('error','El documento no se encuentra disponible , es posible que haya sido  borrado');
            return view('PerfilDocente.create');
        }
    }

    function downloadPlantillaUesplayCategoria(Request $request){
        $path = public_path() . $_ENV['PATH_RECURSOS'] . 'temp-usuarios-categorias-uesplay.xlsx';
        if (File::exists($path)) {
            return response()->download($path);
        } else {
            Session::flash('error', 'El documento no se encuentra disponible , es posible que haya sido  borrado');
            return view('uesplay.createUsuarioCategoria');
        }
    }
}
