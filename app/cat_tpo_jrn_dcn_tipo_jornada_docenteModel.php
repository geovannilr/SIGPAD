<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class cat_tpo_jrn_dcn_tipo_jornada_docenteModel extends Model{
    protected $table='cat_tpo_jrn_dcn_tipo_jornada_docente';
	protected $primaryKey='id_cat_tpo_jrn_dcn';
	public $timestamps=false;
		protected $fillable=
		[
                'descripcion_cat_tpo_jrn_dcn',
                'orden_cat_tpo_jrn_dcn'
		];

    public static function bulkUpdateOrder($elements){
        $result = -1;
        $updatableElements = self::getUpdatableArray($elements);
        $templateQuery = "UPDATE cat_tpo_jrn_dcn_tipo_jornada_docente SET orden_cat_tpo_jrn_dcn = ordParam WHERE id_cat_tpo_jrn_dcn = idParam";
        $queries = self::builtUpdateQuery($templateQuery,$updatableElements);
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

    private static function builtUpdateQuery($template, $elements){
        $queries = [];
        $iniciales = array("idParam", "ordParam");
        foreach ($elements as $id => $element) {
            $valores = array($id, $element);
            $query = str_replace($iniciales, $valores, $template);
            $queries[] = $query;
            unset($valores);
        }
        return $queries;
    }

    private static function getUpdatableArray($elements){
        $toUpdate = [];
        foreach ($elements as $element){
            if($element['ordini']!=$element['ordfin']){
                $toUpdate[$element['id']]=$element['ordfin'];
            }
        }
        return $toUpdate;
    }
}
