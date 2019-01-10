<?php


namespace App;
use Illuminate\Database\Eloquent\Model;


class gen_cat_catalogoModel extends Model
{
    protected $table='gen_cat_catalogo';
    protected $primaryKey='id_gen_cat';
    public $timestamps=false;
    protected $fillable=
        [
            'nombre_gen_cat',
            'descripcion_gen_cat',
            'tipo_gen_cat'
        ];
}
