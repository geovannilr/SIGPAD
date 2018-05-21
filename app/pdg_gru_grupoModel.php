<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pdg_gru_grupoModel extends Model
{
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
}
