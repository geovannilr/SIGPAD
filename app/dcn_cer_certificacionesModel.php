<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dcn_cer_certificacionesModel extends Model{
	protected $table='dcn_cer_certificaciones';
		protected $primaryKey='id_dcn_cer';
		public $timestamps=false;
		protected $fillable=
		[
			'nombre_dcn_cer', 
			'anio_expedicion_dcn_cer',
			'institucion_dcn_cer',
			'id_cat_idi',
			'id_dcn'
		];
}
