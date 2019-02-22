<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rel_cat_tpo_ski_cat_ctg_traModel extends Model{
    protected $table='rel_cat_tpo_ski_cat_ctg_tra';
	protected $primaryKey='id_rel_cat_tpo_ski_cat_ctg_tra';
	public $timestamps=false;
		protected $fillable=
		[
			'id_tpo_ski', 
			'id_cat_ctg_tra'
		];
}
