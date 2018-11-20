<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_tpo_tra_gra_tipo_trabajo_graduacionModel extends Model
{
    protected $table='cat_tpo_tra_gra_tipo_trabajo_graduacion';
	protected $primaryKey='id_cat_tpo_tra_gra';
	public $timestamps=false;
		protected $fillable=
		[
			'nombre_cat_tpo_tra_gra',
            'anio_cat_tpo_tra_gra'
		];
}
