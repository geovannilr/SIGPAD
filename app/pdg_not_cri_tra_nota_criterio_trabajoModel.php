<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pdg_not_cri_tra_nota_criterio_trabajoModel extends Model
{
    protected $table='pdg_not_cri_tra_nota_criterio_trabajo';
		protected $primaryKey='id_pdg_not_cri_tra';
		public $timestamps=false;
		protected $fillable=
		[
			'nota_pdg_not_cri_tra', 
			'id_cat_cri_eva',
			'id_pdg_tra_gra',
			'id_pdg_gru_est',
		];
    public static function getCriteriosEtapa($idGrupo,$idEtapa){
        $criterios = DB::select(
            'SELECT DISTINCT vwn.idCriterio, UPPER(vwn.nombreCriterio) as nombreCriterio, vwn.ponderaCriterio, vwn.ponderaAspecto
              FROM view_pdg_notas vwn
              WHERE  vwn.idEtapa = :idEtapa
              AND vwn.idGru = :idGrupo',array($idEtapa,$idGrupo));
        return $criterios;
    }

    public static function getNotasEtapa($idGrupo,$idEtapa){
        $notas = DB::select(
            'SELECT 
                vwn.idNota, vwn.notaCriterio, vwn.idCriterio, vwn.carnetEstudiante, vwn.nombreEstudiante, vwn.ponderaCriterio, vwn.ponderaAspecto
              FROM view_pdg_notas vwn
              WHERE  vwn.idEtapa = :idEtapa
              AND vwn.idGru = :idGrupo',array($idEtapa,$idGrupo));
        return $notas;
    }

    public static function bulkUpdateNotas($idGrupo,$idEtapa,$notas){
        $result = -1;
        $templateQuery = "update view_pdg_notas set notaCriterio = notaParam where idNota = idParam and idGru = '".$idGrupo."' and idEtapa = '".$idEtapa."'";
        $queries = pdg_not_cri_tra_nota_criterio_trabajoModel::builtUpdateQuery($templateQuery,$notas);
        DB::beginTransaction();
        try {
            foreach($queries as $query){
                DB::update($query);
            }
            DB::commit();
            $result = 0;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        return $result;
    }

    public static function builtUpdateQuery($template,$notas){
        $queries = [];
        $iniciales = array("idParam","notaParam");
        foreach ($notas as $id => $nota){
            $valores = array($id,$nota);
            $query = str_replace($iniciales,$valores,$template);
            $queries[] = $query;
            unset($valores);
        }
        return $queries;
    }
}
