<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_idi_idiomaModel extends Model{
    protected $table='cat_idi_idioma';
	protected $primaryKey='id_cat_idi';
	public $timestamps=false;
		protected $fillable=
		[
			'nombre_cat_idi'
		];
}
