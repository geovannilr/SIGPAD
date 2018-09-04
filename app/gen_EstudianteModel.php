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
			'id_gen_usr', 
			'carnet_gen_est',
			'nombre_gen_est'
		];

//EJRG begin
    /**
     * Retornar la relacion-estudiante-grupo, relacion One to One
     */
    public function relacion_gru_est()
    {
        return $this->hasOne('App\pdg_gru_est_grupo_estudianteModel','id_gen_est','id_gen_est');
    }
//EJRG end

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
    public static function getEstudiantesSinGrupo($anio){
        $estudiantes = DB::table('gen_est_estudiante')
                            ->leftJoin('pdg_gru_est_grupo_estudiante','gen_est_estudiante.id_gen_est','=','pdg_gru_est_grupo_estudiante.id_gen_est')
                            ->whereNull('pdg_gru_est_grupo_estudiante.id_pdg_gru_est')
                            ->select('gen_est_estudiante.*')
                            ->get();
        return $estudiantes;
    }

	  public function getIdGrupo($carnet){
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
			$jsonEstudiantes =response()->json($estudiantes)->getData();
			$estudiantes= $jsonEstudiantes[0]->estudiantes;
			$decodeEstudiantes = json_decode($estudiantes);
			$idGrupo=$decodeEstudiantes[0]->idGrupo;
		}else{
			$idGrupo ="NA";
		}

	 	return $idGrupo;
	 }
}
