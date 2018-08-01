<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pub_publicacionModel extends Model{
 
	protected $table='pub_publicacion';
	protected $primaryKey='id_pub';
	public $timestamps=false;
		protected $fillable=
		[
			'id_cat_tpo_pub',
			'id_gen_int',
			'titulo_pub',
			'anio_pub',
			'correlativo_pub',
			'codigo_pub'
		];
	public function getColaboradores($idPub){ 
	 	$resultado=DB::select('
					select 
					rel.id_pub,
					rel.id_pub_col,
					rel.id_cat_tpo_col_pub,
					col.nombres_pub_col,
					col.apellidos_pub_col,
					tipo.nombre_cat_tpo_col_pub
					from rel_col_pub_colaborador_publicacion rel
					inner join pub_col_colaborador col on rel.id_pub_col = col.id_pub_col
					inner join cat_tpo_col_pub_tipo_colaborador tipo on tipo.id_cat_tpo_col_pub = rel.id_cat_tpo_col_pub
					where id_pub ='.$idPub.';
					');
        return $resultado;
	 }		
}
