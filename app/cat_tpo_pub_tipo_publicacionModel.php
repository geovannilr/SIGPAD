<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_tpo_pub_tipo_publicacionModel extends Model{
    protected $table='cat_tpo_pub_tipo_publicacion';
    protected $primaryKey='id_cat_tpo_pub';
    public $timestamps=false;
    protected $fillable=
        [
            'nombre_cat_tpo_pub',
            'descripcion_cat_tpo_pub'

        ];
}
