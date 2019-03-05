<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rel_rol_tri_eta_eva_tribunal_etapaModel extends Model{

    protected $table='rel_rol_tri_eta_eva_tribunal_etapa';
		protected $primaryKey='id_rel_rol_tri_eta_eva';
		public $timestamps=false;
		protected $fillable=
		[
			'id_pdg_tri_rol', 
			'id_cat_eta_eva',
			'anio_rel_rol_tri_eta_eva'
		];
}
