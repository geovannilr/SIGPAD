<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Marquez
 * Date: 2/2/2019
 * Time: 10:52 PM
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class gen_tpo_par_tipo_parametroModel extends Model
{
    protected $table='gen_tpo_par_tipo_parametro';
    protected $primaryKey='id_gen_tpo_par';
    public $timestamps=false;
    protected $fillable=
        [
            'tipo_gen_tpo_par'
        ];
}

