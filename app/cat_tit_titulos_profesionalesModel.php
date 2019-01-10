<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_tit_titulos_profesionalesModel extends Model{
    protected $table='cat_tit_titulos_profesionales';
    protected $primaryKey='id_cat_tit';
    public $timestamps=false;
    protected $fillable=
        [
            'nombre_titulo_cat_tit'

        ];
                                                        }
