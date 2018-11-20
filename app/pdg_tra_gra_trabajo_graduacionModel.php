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
use PhpParser\Node\Stmt\TryCatch;

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

    public static function createOrUpdateTDG($perfil){
        $tragra = pdg_tra_gra_trabajo_graduacionModel::where('id_pdg_gru',$perfil->id_pdg_gru)->first();
        if(empty($tragra)){
            $tragra = new pdg_tra_gra_trabajo_graduacionModel();
            $tragra->id_pdg_gru = $perfil->id_pdg_gru;
            $tragra->id_cat_tpo_tra_gra = $perfil->id_cat_tpo_tra_gra;
            $tragra->tema_pdg_tra_gra = $perfil->tema_pdg_per;
            $tragra->id_cat_ctg_tra = $perfil->id_cat_ctg_tra;
            $tragra->id_cat_sta = $perfil->id_cat_sta;
            $tragra->save();
            try{
                $iniciar = self::avanzarProceso($tragra->id_pdg_tra_gra);
                if(!($iniciar->p_result == 0)) {
                    self::limpiarProceso($tragra);
                    $tragra = null;
                }
            }catch (\Exception $e){
                self::limpiarProceso($tragra);
                $tragra = null;
            }
        }else{
            $tragra = null; //Ya existía el TDG
        }
        return $tragra;
    }

    public static function avanzarProceso($idTraGra){
//        DB::statement(
//            'CALL sp_pdg_tdg_avanzarProceso(:idTraGra,@resultado,@info)',
//            array(
//                $idTraGra
//            )
//        );
//        $res = DB::select('select @resultado');
        $res = DB::select(
            'CALL sp_pdg_tdg_avanzarProceso('.$idTraGra.',@resultado,@info)'
        );
        return $res[0];
    }
    public static function limpiarProceso($tragra){
        try{
            DB::table('pdg_apr_eta_tra_aprobador_etapa_trabajo')->where('id_pdg_tra_gra', '=',$tragra->id_pdg_tra_gra)->delete();
            DB::table('pdg_not_cri_tra_nota_criterio_trabajo')->where('id_pdg_tra_gra', '=',$tragra->id_pdg_tra_gra)->delete();
            pdg_tra_gra_trabajo_graduacionModel::destroy($tragra->id_pdg_tra_gra);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}