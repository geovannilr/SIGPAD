<?php

namespace App;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class pdg_gru_est_grupo_estudianteModel extends Model
{
    protected $table='pdg_gru_est_grupo_estudiante';
	protected $primaryKey='id_pdg_gru_est';
	public $timestamps=true;
		protected $fillable=
		[
			'id_pdg_gru',
			'id_gen_est', 
			'id_cat_sta',
			'eslider_pdg_gru_est'
		];
	public function cambiarEstadoGrupo($idAlumno,$aceptar){ // si acepta o rechaza grupo de trabajo de graduación 0 si rechaza 1 si acepta
	 	/*$resultado=DB::table('pdg_gru_est_grupo_estudiante')
            ->where('id_gen_est', $idAlumno)
            ->update(['id_cat_sta' =>6]);*/
            DB::statement('CALL sp_pdg_gru_aceptarRechazarGrupo(:acepto,:idEST, @result);',
		    	array(
		        	$aceptar,
		        	$idAlumno,
		    	)
			);
		$errorCode = DB::select('select @result as resultado');
        return $errorCode[0]->resultado;
	 }		

	 public function enviarGrupo($idGrupo){ 
	 	$resultado=DB::table('pdg_gru_est_grupo_estudiante')
            ->where('id_pdg_gru', $idGrupo)
            ->update(['id_cat_sta' =>7]);
            
        return $resultado;
	 }		

}
