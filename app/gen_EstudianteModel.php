<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class gen_EstudianteModel extends Model
{
    	protected $table='gen_estudiante';
		protected $primaryKey='idgen_estudiante';
		public $timestamps=true;
		protected $fillable=
		[
			'id',
			'carnet', 
			'carnet_estudiante'
		];

	public function conformarGrupoSp($xmlRequest){
		$valor=0;
	 	DB::statement('CALL sp_create_grupoTDG(:carnetsXML, @result);',
	    	array(
	        	$xmlRequest,
	    	)
		);
		$errorCode = DB::select('select @result as resultado');
		//DB::select("CALL sp_create_grupoTDG('".$xmlRequest."',@resultado)");
		//$errorCode = DB::select("@resultado)");
	 	return $errorCode;
	 }	
}
