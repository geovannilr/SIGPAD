<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_tpo_doc_tipo_documentoModel extends Model{
    protected $table='cat_tpo_doc_tipo_documento';
	protected $primaryKey='id_cat_tpo_doc';
	public $timestamps=false;
		protected $fillable=
		[
                'nombre_pdg_tpo_doc',
                'descripcion_pdg_tpo_doc',
                'puede_observar_cat_pdg_tpo_doc',
                'anio_cat_pdg_tpo_doc',
		];
}
