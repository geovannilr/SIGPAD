<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/8/2018
 * Time: 3:47 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pdg_dcn_docenteModel extends Model
{
    protected $table='pdg_dcn_docente';
    protected $primaryKey='id_pdg_dcn';
    public $timestamps=false;
    protected $fillable=
        [
            'carnet_pdg_dcn',
            'anio_titulacion_pdg_dcn',
            'activo',
            'id_gen_usuario'
        ];

    /***
     * Función: getDocentesDisponibles($id)
     * Params: $id-> Id del grupo de TDG
     * Retorna: Lista de docentes que no han sido asignados al tribunal de un grupo
    */
    public static function getDocentesDisponibles($id){
        $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($id);
        $array=array();
        if (sizeof($tribunal) != 0) {
            foreach ($tribunal as $tri) {
                $array [] = $tri->id_pdg_dcn;
            }
        }
        $docentes = DB::table('pdg_dcn_docente')
        ->join('gen_usuario','gen_usuario.id','=','pdg_dcn_docente.id_gen_usuario')
//            ->leftJoin('pdg_tri_gru_tribunal_grupo', function ($join) use($id){
//                $join->on('pdg_tri_gru_tribunal_grupo.id_pdg_dcn','=','pdg_dcn_docente.id_pdg_dcn')
//                    ->where('pdg_tri_gru_tribunal_grupo.id_pdg_gru', '=', $id);
//            })
        ->leftJoin('pdg_tri_gru_tribunal_grupo','pdg_tri_gru_tribunal_grupo.id_pdg_dcn','=','pdg_dcn_docente.id_pdg_dcn')
//        ->whereNull('pdg_tri_gru_tribunal_grupo.id_pdg_gru')
        ->whereNotIn('pdg_dcn_docente.id_pdg_dcn',$array)
        ->select('gen_usuario.name','gen_usuario.email','pdg_dcn_docente.id_pdg_dcn',
            DB::raw('count(if(pdg_tri_gru_tribunal_grupo.id_pdg_tri_rol=1,1,null)) as asigned_as_A, 
                    count(if(pdg_tri_gru_tribunal_grupo.id_pdg_tri_rol=3,1,null)) as asigned_as_J ')
        )
        ->groupBy('gen_usuario.name','gen_usuario.email','pdg_dcn_docente.id_pdg_dcn')
        ->orderBy('asigned_as_A','DESC')
        ->orderBy('asigned_as_J','DESC')
        ->get();
        return $docentes;
    }
}