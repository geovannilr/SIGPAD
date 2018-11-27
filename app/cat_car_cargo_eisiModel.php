<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_car_cargo_eisiModel extends Model{
    protected $table='cat_car_cargo_eisi';
	protected $primaryKey='id_cat_car';
	public $timestamps=false;
		protected $fillable=
		[
			'nombre_cargo'
		];
}
