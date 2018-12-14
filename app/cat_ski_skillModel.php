<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_ski_skillModel extends Model{
    protected $table='cat_ski_skill';
	protected $primaryKey='id_cat_ski';
	public $timestamps=false;
	protected $fillable=
		[
			'nombre_cat_ski',
			'id_tpo_ski'
		];
}
