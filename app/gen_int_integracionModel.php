<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gen_int_integracionModel extends Model{
    	protected $table='gen_int_integracion';
		protected $primaryKey='id_gen_int';
		public $timestamps=false;
		protected $fillable=
		[
			'id_gen_tpo_int', 
			'llave_gen_int'
		];

}
