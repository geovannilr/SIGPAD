<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_mat_materiaModel extends Model{
    protected $table='cat_mat_materia';
		protected $primaryKey='id_cat_mat';
		public $timestamps=false;
		protected $fillable=
		[
			'codigo_mat', 
			'nombre_mat',
			'es_electiva',
			'anio_pensum',
			'orden_mat',
			'ciclo'
		];
}
