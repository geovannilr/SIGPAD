<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Marquez
 * Date: 2/2/2019
 * Time: 10:37 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class gen_par_parametrosModel extends Model
{
    protected $table='gen_par_parametros';
    protected $primaryKey='id_gen_par';
    public $timestamps=false;
    protected $fillable=
        [
            'id_gen_tpo_par',
            'nombre_gen_par',
            'valor_gen_par',
            'id_gen_usuario'
        ];
    public function tpoParametro(){
        return $this->belongsTo('App\gen_tpo_par_tipo_parametroModel','id_gen_tpo_par','id_gen_tpo_par');
    }
}



