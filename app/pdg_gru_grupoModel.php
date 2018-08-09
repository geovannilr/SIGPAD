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
//EJRG end

	function getGrupos (){
		$resultado = DB::select('CALL sp_pdg_gru_select_gruposPendientesDeAprobacion();');
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
}
