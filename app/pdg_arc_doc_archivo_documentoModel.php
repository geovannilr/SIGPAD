<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pdg_arc_doc_archivo_documentoModel extends Model{
    
    protected $table='pdg_arc_doc_archivo_documento';
	protected $primaryKey='id_pdg_arc_doc';
	public $timestamps=false;
		protected $fillable=
		[
			'id_pdg_doc',
			'ubicacion_arc_doc',
			'fecha_subida_arc_doc',
			'nombre_arc_doc',
			'activo'
		];
}
