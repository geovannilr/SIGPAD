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
        $docentes = DB::table('pdg_dcn_docente')
            ->join('gen_usuario','gen_usuario.id','=','pdg_dcn_docente.id_gen_usuario')
            ->leftJoin('pdg_tri_gru_tribunal_grupo', function ($join) use($id){
                $join->on('pdg_tri_gru_tribunal_grupo.id_pdg_dcn','=','pdg_dcn_docente.id_pdg_dcn')
                    ->where('pdg_tri_gru_tribunal_grupo.id_pdg_gru', '=', $id);
            })
            ->whereNull('pdg_tri_gru_tribunal_grupo.id_pdg_gru')
            ->select('gen_usuario.name','gen_usuario.email','pdg_dcn_docente.id_pdg_dcn')
            ->get();
        return $docentes;
    }
}