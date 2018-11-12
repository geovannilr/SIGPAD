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


    public function updateEntregablesEtapaGrupo($cantidad,$idTraGra,$idEtapa){
     
            DB::statement('CALL sp_pdg_eta_eva_alter_EntregablesxEtapas_byGrypo(:cantidad, :idTrabajoGraduacion, :idEtapa, @result);',
                array(
                    $cantidad,
                    $idTraGra,
                    $idEtapa
                )
            );
        $errorCode = DB::select('select @result as resultado');
        return $errorCode[0]->resultado;
    }      

   public  static  function setEntregablesEtapaGrupo($idTraGra){
     
            DB::statement('CALL sp_pdg_eta_eva_set_EntregablesxEtapas_byGrypo( :idTrabajoGraduacion,@result);',
                array(
                    $idTraGra,
                )
            );
        $errorCode = DB::select('select @result as resultado');
        return $errorCode[0]->resultado;
    }      

}