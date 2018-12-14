<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dcn_exp_experienciaModel extends Model{
    protected $table='dcn_exp_experiencia';
		protected $primaryKey='id_dcn_exp';
		public $timestamps=false;
		protected $fillable=
		[
			'lugar_trabajo_dcn_exp', 
			'anio_inicio_dcn_exp',
			'anio_fin_dcn_exp',
			'descripcion_dcn_exp',
			'id_cat_idi',
			'id_pdg_dcn'
		];
}
