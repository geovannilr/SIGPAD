<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dcn_his_historial_academicoModel extends Model{
    protected $table='dcn_his_historial_academico';
		protected $primaryKey='id_dcn_his';
		public $timestamps=false;
		protected $fillable=
		[
			'id_pdg_dcn', 
			'id_cat_mat',
			'id_cat_car',
			'anio',
			'descripcion_adicional'
		];
}
