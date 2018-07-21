<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pub_publicacionModel extends Model{
 
	protected $table='pub_publicacion';
	protected $primaryKey='id_pub';
	public $timestamps=false;
		protected $fillable=
		[
			'id_cat_tpo_pub',
			'id_gen_int',
			'titulo_pub',
			'anio_pub',
			'correlativo_pub',
			'codigo_pub'
		];
}
