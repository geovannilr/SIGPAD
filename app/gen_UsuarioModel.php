<?php

namespace App;
use Caffeinated\Shinobi\Traits\ShinobiTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class gen_UsuarioModel extends Model
{
	use Notifiable;
    use ShinobiTrait;
   protected $table='gen_usuario';
		protected $primaryKey='id';
		public $timestamps=true;
		protected $fillable=
		[
			'name',
			'email', 
			'password',
			'user'
		];
		protected $hidden = ['password', 'remember_token'];

		public function setPasswordAttribute($valor){
			if (!empty($valor)) {

				$this->attributes['password']=\Hash::make($valor);
			}

		}
		public function testSp(){

	 	//$vehiculosAlertas=DB::select("SELECT * FROM fn_get_olds(".$idUsuario.")");
		$test = DB::select("CALL test()");
	 	return $test;
	 }
}
