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

    protected $table='pdg_apr_eta_tra_aprobador_etapa_trabajo';
    protected $primaryKey='id_pdg_apr_eta_tra_';
    public $timestamps=false;

    public static function getEtapa($idTragra,$idTipoBusqueda){
        DB::statement(
            'CALL sp_pdg_eta_eva_get_Etapa(:idTraGra,:idTpoBusqueda,@idCatEtaEva)',
            array(
                $idTragra,
                $idTipoBusqueda
            )
        );
        $etapa = DB::select('select @idCatEtaEva as idCatEtaEva');
        return $etapa;
    }
    public static function contarEtapas($idTragra,$idTipoConteo){
        DB::statement(
            'CALL sp_pdg_eta_eva_get_CantEtapas(:idTraGra,:idTpoConteo,@total)',
            array($idTragra, $idTipoConteo)
        );
        $total = DB::select('select @total as total');
        return $total[0]->total;
    }
}
