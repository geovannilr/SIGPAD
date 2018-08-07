<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_ctg_tra_categoria_trabajo_graduacionModel extends Model{
    protected $table='cat_ctg_tra_categoria_trabajo_graduacion';
	protected $primaryKey='id_cat_ctg_tra';
	public $timestamps=false;
		protected $fillable=
		[
			'nombre_cat_ctg_tra',
		];
}
