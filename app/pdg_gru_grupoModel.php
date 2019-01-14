<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pdg_gru_grupoModel extends Model{
    protected $table='pdg_gru_grupo';
	protected $primaryKey='id_pdg_gru';
	public $timestamps=false;
		protected $fillable=
		[
			'id_cat_sta',
			'numero_pdg_gru',
			'correlativo_pdg_gru',
			'anio_pdg_gru',
			'ciclo_pdg_gru'
		];

//EJRG begin
    /**
     * Retornar coleccion de relaciones-estudiantes-grupo, relacion One to Many
     */
    public function relaciones_gru_est()
    {
        return $this->hasMany('App\pdg_gru_est_grupo_estudianteModel','id_pdg_gru','id_pdg_gru');
    }
    /**
     * Retornar la relacion-grupo-tdg, relacion One to One
     */
    public function relacion_gru_tdg()
    {
        return $this->hasOne('App\pdg_tra_gra_trabajo_graduacionModel','id_pdg_gru','id_pdg_gru');
    }
//EJRG end

	function getGrupos (){
		$resultado = DB::select('CALL sp_pdg_gru_select_gruposPendientesDeAprobacion();');
		return  $resultado;
	}
	function getGruposDocente ($idDocente){
		$resultado = DB::select('CALL sp_pdg_gru_select_gruposByDocente('.$idDocente.');');
		return  $resultado;
	}
	function getDetalleGrupo ($idGrupo){
		$resultado =DB::select('call sp_pdg_gru_grupoDetalleByID(:idGrupo);',
	    	array(
	        	$idGrupo,
	    	)
		);;
		return  $resultado;
	}	
	function aprobarGrupo ($idGrupo){
		DB::statement('call sp_pdg_gru_aprobarGrupoTGCoordinador(:idGrupo,@result);',
	    	array(
	        	$idGrupo,
	    	)
		);
		$errorCode = DB::select('select @result as resultado');
		return  $errorCode;
	}
	function getEtapas($idGrupo){
		$etapas=DB::select('call sp_pdg_getEtapasEvaluativasByGrupo(:idGrupo);',
	    	array(
	        	$idGrupo,
	    	)
		);
		return $etapas;
	}
	function enviarParaAprobacionSp($idGrupo){
		DB::select('call sp_pdg_enviarParaAprobacion(:idGrupo,@result);',
	    	array(
	        	$idGrupo,
	    	)
		);
		$errorCode = DB::select('select @result as resultado');
		return  $errorCode;
	}
	public static function deleteGrupoAndRelations($idGrupo){
        $result = 0;
        try{
            DB::table('pdg_gru_est_grupo_estudiante')->where('id_pdg_gru','=',$idGrupo)->delete();
            pdg_gru_grupoModel::destroy($idGrupo);
        } catch (\Exception $exception){
            $result = 1;
        }
        return $result;
    }

    public static function getEstadoGrupos($anio,$estado){
        $condicionActivos = ($estado==1)?" AND apr.id_cat_eta_eva = 999 ":" ";
        $condicionAnio1 = ($anio==null)?"":"AND gru.anio_pdg_gru = :anio1";
        $condicionAnio2 = ($anio==null)?"":"AND gru.anio_pdg_gru = :anio2";
        $queryNoIniciados = "
            SELECT DISTINCT 
                x.id_pdg_gru, x.id_pdg_tra_gra, x.numero_pdg_gru, x.nombre_cat_eta_eva, 
                x.carnet_gen_est, x.nombre_gen_est, x.CantAprobadas, x.totalEtapas
            FROM
                (SELECT 
                    gru.id_pdg_gru,
                    gru.id_pdg_gru as id_pdg_tra_gra,
                    gru.numero_pdg_gru,
                    CASE 
                        WHEN prep.id_pdg_ppe IS NULL THEN ' ' 
                        WHEN prep.id_pdg_ppe IS NOT NULL AND perf.id_pdg_per IS NULL THEN 'Pre-Perfil'
                        WHEN prep.id_pdg_ppe IS NOT NULL AND perf.id_pdg_per IS NOT NULL THEN 'Perfil'
                        ELSE ' '
                    END as nombre_cat_eta_eva,
                    genEst.carnet_gen_est,
                    genEst.nombre_gen_est,
                    0	as CantAprobadas,
                    0	as totalEtapas
                FROM 
                    pdg_gru_grupo gru
                    INNER JOIN pdg_gru_est_grupo_estudiante est on est.id_pdg_gru = gru.id_pdg_gru
                    INNER JOIN gen_est_estudiante genEst on genEst.id_gen_est = est.id_gen_est
                    LEFT JOIN pdg_per_perfil perf ON (perf.id_pdg_gru = gru.id_pdg_gru)
                    LEFT JOIN pdg_ppe_pre_perfil prep ON (prep.id_pdg_gru = gru.id_pdg_gru)
                WHERE 
                    gru.id_cat_sta NOT IN (7,1,2)
                    AND est.eslider_pdg_gru_est = 1
                    ".$condicionAnio1."
                    AND gru.id_pdg_gru NOT IN (SELECT tragra.id_pdg_gru FROM pdg_tra_gra_trabajo_graduacion tragra)	
                ) x
            --
            UNION
            --";
        $queryIniciados = "
            SELECT 
                gru.id_pdg_gru,
                tra.id_pdg_tra_gra,
                gru.numero_pdg_gru,
                eta.nombre_cat_eta_eva,
                genEst.carnet_gen_est,
                genEst.nombre_gen_est,
                (SELECT count(*) from pdg_apr_eta_tra_aprobador_etapa_trabajo where aprobo = 1 AND id_pdg_tra_gra = tra.id_pdg_tra_gra ) as CantAprobadas,
                (SELECT count(*) from pdg_apr_eta_tra_aprobador_etapa_trabajo where  id_pdg_tra_gra = tra.id_pdg_tra_gra ) as totalEtapas
                from pdg_gru_grupo gru
                inner join pdg_tra_gra_trabajo_graduacion tra on tra.id_pdg_gru = gru.id_pdg_gru
                inner join pdg_apr_eta_tra_aprobador_etapa_trabajo apr on apr.id_pdg_tra_gra = tra.id_pdg_tra_gra
                inner join cat_eta_eva_etapa_evaluativa eta on eta.id_cat_eta_eva = apr.id_cat_eta_eva
                inner join pdg_gru_est_grupo_estudiante est on est.id_pdg_gru = gru.id_pdg_gru
                inner join gen_est_estudiante genEst on genEst.id_gen_est = est.id_gen_est
                where 
                  apr.inicio = 1 AND aprobo = 0 AND est.eslider_pdg_gru_est = 1
                  ".$condicionActivos." 
                  ".$condicionAnio2."
                group by gru.id_pdg_gru,tra.id_pdg_tra_gra,gru.numero_pdg_gru,eta.nombre_cat_eta_eva,est.id_gen_est,genEst.carnet_gen_est,
                genEst.nombre_gen_est";
        $fullQuery = ($estado!=1)?$queryNoIniciados.$queryIniciados:$queryIniciados;
        if($anio==null)
            $grupos = DB::select($fullQuery);
        else
            if($estado!=1)
                $grupos = DB::select($fullQuery, array('anio1'=>$anio,'anio2'=>$anio));
            else
                $grupos = DB::select($fullQuery, array('anio2'=>$anio));
        return $grupos;
    }
    public static function getDetalleGrupos($anio,$estado){
        if ($estado == 0 || $estado == 1 ) {
            $where = " where ifnull(x.finalizo,0) =:estado ";
        }elseif ($estado == 2) {
            $where = " ";
        }
        $query = "SELECT x.cantGru, x.idGru, x.numGrupo, x.nomSta, x.nomEst, x.bLider
                    FROM
                        (SELECT 
                                (SELECT COUNT(*) FROM pdg_gru_est_grupo_estudiante gru2 WHERE gru2.id_pdg_gru = gru_est.id_pdg_gru) AS cantGru,
                                gru_est.id_pdg_gru 	AS idGru,
                                gru.numero_pdg_gru 	AS numGrupo,
                                sta.nombre_cat_sta	AS nomSta,
                                est.nombre_gen_est	AS nomEst,
                                gru_est.eslider_pdg_gru_est AS bLider
                                ,(select aprobo from pdg_apr_eta_tra_aprobador_etapa_Trabajo where id_cat_eta_eva = 999 AND id_pdg_tra_gra = 
                                    (SELECT id_pdg_tra_gra from pdg_tra_gra_trabajo_graduacion where id_pdg_gru = gru.id_pdg_gru )) as finalizo
                            FROM
                                pdg_gru_grupo gru
                                INNER JOIN pdg_gru_est_grupo_estudiante gru_est ON (gru_est.id_pdg_gru=gru.id_pdg_gru)
                                INNER JOIN gen_est_estudiante est	ON (est.id_gen_est=gru_est.id_gen_est)
                                INNER JOIN cat_sta_estado sta	ON (sta.id_cat_sta=gru.id_cat_sta)
                            WHERE
                                gru.anio_pdg_gru = :anio
                        ) x
                    ".$where."
                    ORDER BY x.numGrupo ASC, x.bLider DESC";
        if ($estado == 0 || $estado == 1 ) {
            $grupos = DB::select($query, array($anio,$estado));
        }elseif ($estado == 2) {
            $grupos = DB::select($query, array($anio));
        }
        return $grupos;
    }
}
