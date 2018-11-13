<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pub_col_colaboradorModel extends Model{
    protected $table='pub_col_colaborador';
	protected $primaryKey='id_pub_col';
	public $timestamps=false;
		protected $fillable=
		[
			'id_gen_int',
			'nombres_pub_col',
			'apellidos_pub_col'
		];
}
