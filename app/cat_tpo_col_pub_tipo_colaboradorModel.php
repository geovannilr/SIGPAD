<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Marquez
 * Date: 8/1/2019
 * Time: 9:51 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_tpo_col_pub_tipo_colaboradorModel extends Model{
    protected $table='cat_tpo_col_pub_tipo_colaborador';
    protected $primaryKey='id_cat_tpo_col_pub';
    public $timestamps=false;
    protected $fillable=
        [
            'nombre_cat_tpo_col_pub',
            'descripcion_cat_tpo_col_pub'

        ];
}
