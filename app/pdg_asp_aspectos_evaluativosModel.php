<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Marquez
 * Date: 13/1/2019
 * Time: 5:32 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class pdg_asp_aspectos_evaluativosModel extends Model{
    protected $table='pdg_asp_aspectos_evaluativos';
    protected $primaryKey='id_pdg_asp';
    public $timestamps=false;
    protected $fillable=
        [
            'nombre_pdg_asp',
            'ponderacion_pdg_asp',
            'id_cat_eta_eva'

        ];
    public function catEtaEva(){
        return $this->belongsTo('App\cat_eta_eva_etapa_evalutativaModel','id_cat_eta_eva','id_cat_eta_eva');
    }

}
