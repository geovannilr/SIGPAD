<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rel_tpo_tra_eta_tipo_trabajo_etapaModel extends Model{
   protected $table='rel_tpo_tra_eta_tipo_trabajo_etapa';
	protected $primaryKey='id_rel_tpo_tra_eta';
	public $timestamps=false;
		protected $fillable=
		[
			'id_cat_tpo_tra_gra',
			'id_cat_eta_eva',
			'orden_eta_eva'
		];
}
