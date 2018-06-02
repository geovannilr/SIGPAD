<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_sta_estadoModel extends Model
{
    protected $table='cat_sta_estado';
	protected $primaryKey='id_cat_sta';
	public $timestamps=false;
		protected $fillable=
		[
			'id_cat_tpo_sta',
			'nombre_cat_sta',
			'descripcion_cat_sta'
		];
}
