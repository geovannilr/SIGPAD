<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pdg_doc_documentoModel extends Model{

    protected $table='pdg_doc_documento';
	protected $primaryKey='id_pdg_doc';
	public $timestamps=false;
		protected $fillable=
		[
			'id_pdg_gru',
			'id_cat_tpo_doc',
			'fecha_creacion_pdg_doc'
            ,'id_cat_eta_eva_pdg_doc'
		];
}
