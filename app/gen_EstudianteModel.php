<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class gen_EstudianteModel extends Model
{
    	protected $table='gen_est_estudiante';
		protected $primaryKey='id_gen_est';
		public $timestamps=true;
		protected $fillable=
		[
			'id',
			'carnet', 
			'carnet_estudiante'
		];

	public function conformarGrupoSp($xmlRequest){
		$valor=0;
	 	DB::statement('CALL sp_pdg_gru_create(:carnetsXML, @result);',
	    	array(
	        	$xmlRequest,
	    	)
		);
		$errorCode = DB::select('select @result as resultado');
		//DB::select("CALL sp_create_grupoTDG('".$xmlRequest."',@resultado)");
		//$errorCode = DB::select("@resultado)");
	 	return $errorCode;
	 }
	 public function getGrupoCarnet($carnet){
		$valor=0;
	 	DB::statement('call sp_pdg_gru_find_ByCarnet(:carnet,@resultado,@jsonR);',
	    	array(
	        	$carnet,
	    	)
		);
		$errorCode = DB::select('select @resultado as resultado');
		$resultado="";
		$estudiantes = '';
		if ($errorCode[0]->resultado == '0'){
			$estudiantes = DB::select('select @jsonR as estudiantes');
			$resultado=response()->json(["errorCode"=>$errorCode[0]->resultado,"errorMessage"=>"El alumno ya pertenece a un grupo de trabajo de graduación","msg"=>$estudiantes[0]]);
		}else if ($errorCode[0]->resultado == '1'){
			$resultado=response()->json(["errorCode"=>$errorCode[0]->resultado,"errorMessage"=>"El alumno no pertenece a un  grupo de trabajo de graduación","msg"=>$estudiantes]);
		}

		//DB::select("CALL sp_create_grupoTDG('".$xmlRequest."',@resultado)");
		//$errorCode = DB::select("@resultado)");
	 	return $resultado;
	 }	
}
