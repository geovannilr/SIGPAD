<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_tpo_jrn_dcn_tipo_jornada_docenteModel extends Model{
    protected $table='cat_tpo_jrn_dcn_tipo_jornada_docente';
	protected $primaryKey='id_cat_tpo_jrn_dcn';
	public $timestamps=false;
		protected $fillable=
		[
                'descripcion_cat_tpo_jrn_dcn'
		];
}
