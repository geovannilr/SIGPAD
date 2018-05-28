<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pdg_ppe_pre_perfilModel extends Model
{
    protected $table='pdg_ppe_pre_perfil';
	protected $primaryKey='id_pdg_ppe';
	public $timestamps=false;
		protected $fillable=
		[
			'tema_pdg_ppe',
			'nombre_archivo_pdg_ppe',
			'ubicacion_pdg_ppe',
			'fecha_creacion_pdg_ppe',
			'id_pdg_gru',
			'id_cat_sta',
			'id_cat_tpo_tra_gra',
			'id_gen_usuario'
		];
}
