<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pdg_not_cri_tra_nota_criterio_trabajoModel extends Model
{
    protected $table='pdg_not_cri_tra_nota_criterio_trabajo';
		protected $primaryKey='id_pdg_not_cri_tra';
		public $timestamps=false;
		protected $fillable=
		[
			'nota_pdg_not_cri_tra', 
			'id_cat_cri_eva',
			'id_pdg_tra_gra',
			'id_pdg_gru_est',
		];

}
