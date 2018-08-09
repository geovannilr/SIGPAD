<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/8/2018
 * Time: 12:40 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pdg_tra_gra_trabajo_graduacionModel extends Model
{
    protected $table='pdg_tra_gra_trabajo_graduacion';
    protected $primaryKey='id_pdg_tra_gra';
    public $timestamps=false;
    protected $fillable=
        [
            'id_pdg_gru',
            'id_cat_tpo_tra_gra',
            'tema_pdg_tra_gra',
            'id_cat_ctg_tra',
            'id_cat_sta',
        ];
}