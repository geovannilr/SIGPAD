<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rel_col_pub_colaborador_publicacionModel extends Model{
   	 	protected $table='rel_col_pub_colaborador_publicacion';
		protected $primaryKey='id_rel_col_pub';
		public $timestamps=false;
		protected $fillable=
		[
			'id_pub', 
			'id_pub_col',
			'id_cat_tpo_col_pub'
		];

}
