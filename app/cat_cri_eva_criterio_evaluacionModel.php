<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Marquez
 * Date: 15/1/2019
 * Time: 4:11 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;


class cat_cri_eva_criterio_evaluacionModel extends Model
{
    protected $table='cat_cri_eva_criterio_evaluacion';
    protected $primaryKey='id_cat_cri_eva';
    public $timestamps=false;
    protected $fillable=
        [
            'nombre_cat_cri_eva',
            'ponderacion_cat_cri_eva',
            'id_pdg_asp'

        ];
    public function catAspEva(){
        return $this->belongsTo('App\pdg_asp_aspectos_evaluativosModel','id_pdg_asp','id_pdg_asp');
    }
}
