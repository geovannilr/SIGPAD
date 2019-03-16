<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rel_tpo_doc_eta_eva_tipo_documento_etapa_evaModel extends Model
{
    protected $table='rel_tpo_doc_eta_eva_tipo_documento_etapa_eva';
	protected $primaryKey='id_rel_tpo_doc_eta_eva';
	public $timestamps=false;
		protected $fillable=
		[
			'id_cat_tpo_doc',
			'id_cat_eta_eva'
		];

		public function etapas(){
          return $this->belongsTo('App\cat_eta_eva_etapa_evalutativaModel','id_cat_eta_eva','id_cat_eta_eva');
       }
}
