<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_tpo_sta_tipo_estadoModel extends Model
{
    protected $table='cat_tpo_sta_tipo_estado';
    protected $primaryKey='id_cat_tpo_sta';
    public $timestamps=false;
    protected $fillable=
        [
            'nombre_cat_tpo_sta',
            'descripcion_cat_tpo_sta'
        ];
}
