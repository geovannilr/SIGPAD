<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pub_arc_publicacion_archivoModel extends Model{
    protected $table='pub_arc_publicacion_archivo';
	protected $primaryKey='id_pub_arc';
	public $timestamps=false;
		protected $fillable=
		[
			'id_pub',
			'ubicacion_pub_arc',
			'fecha_subida_pub_arc',
			'nombre_pub_arc',
			'descripcion_pub_arc',
			'ubicacion_fisica_pub_arc',
			'activo_pub_arc'
		];
}
