<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Marquez
 * Date: 27/12/2018
 * Time: 7:54 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_tpo_ski_tipo_skillModel extends Model
{
    protected $table='cat_tpo_ski_tipo_skill';
    protected $primaryKey='id_tpo_ski';
    public $timestamps=false;
    protected $fillable=
        [
            'descripcion_tpo_ski'
        ];
}
