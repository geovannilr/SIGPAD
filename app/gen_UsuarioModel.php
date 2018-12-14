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
			'user',
			'primer_nombre',
			'segundo_nombre',
			'primer_apellido',
			'segundo_apellido',
			'codigo_carnet'
		];
		protected $hidden = ['password', 'remember_token'];

		public function setPasswordAttribute($valor){
			if (!empty($valor)) {

				$this->attributes['password']=\Hash::make($valor);
			}

		}

		public static function genericPassword($length){
            $keyspace = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $pieces = [];
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $pieces []= $keyspace[random_int(0, $max)];
            }
            $password = implode('', $pieces);
		    return $password;
        }
		
}
