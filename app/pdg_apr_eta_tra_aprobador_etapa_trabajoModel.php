<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class pdg_apr_eta_tra_aprobador_etapa_trabajoModel extends Model
{
    const T_CONTEO_NO_INICIADAS = 1;
    const T_CONTEO_APROBADAS    = 2;
    const T_CONTEO_ESTABLECIDAS = 3;
    const T_CONTEO_ORIGINALES   = 4;
    const T_BUSQ_ACTUAL         = 1;
    const T_BUSQ_SIGUIENTE      = 2;

    protected $table='pdg_apr_eta_tra_aprobador_etapa_trabajo';
    protected $primaryKey='id_pdg_apr_eta_tra';
    public $timestamps=false;
    protected $fillable=
		[
			'id_cat_eta_eva',
			'id_pdg_tri_gru',
			'id_pdg_tra_gra',
			'fecha_inicio',
			'fecha_aprobacion',
			'aprobo',
			'inicio'
		];

    public static function getEtapa($idTraGra,$idTipoBusqueda){
        DB::statement(
            'CALL sp_pdg_eta_eva_get_Etapa(:idTraGra,:idTpoBusqueda,@idAprbxEta,@idCatEtaEva)',
            array(
                $idTraGra,
                $idTipoBusqueda
            )
        );
        $res = DB::select('select @idCatEtaEva as idCatEtaEva');
        if($res[0]->idCatEtaEva==""){
            return null;
        }else{
            return \App\cat_eta_eva_etapa_evalutativaModel::where('id_cat_eta_eva','=',$res[0]->idCatEtaEva)->first();
        }
    }
    public static function contarEtapas($idTraGra,$idTipoConteo){
        DB::statement(
            'CALL sp_pdg_eta_eva_get_CantEtapas(:idTraGra,:idTpoConteo,@total)',
            array($idTraGra, $idTipoConteo)
        );
        $total = DB::select('select @total as total');
        return $total[0]->total;
    }
}