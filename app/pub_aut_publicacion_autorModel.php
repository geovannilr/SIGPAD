<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pub_aut_publicacion_autorModel extends Model{
    protected $table='pub_aut_publicacion_autor';
	protected $primaryKey='id_pub_aut';
	public $timestamps=false;
		protected $fillable=
		[
			'id_pub',
			'id_gen_int',
			'nombres_pub_aut',
			'apellidos_pub_aut'
		];
}
