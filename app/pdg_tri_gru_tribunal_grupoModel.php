<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 2/8/2018
 * Time: 12:57 AM
 */

namespace App;

use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pdg_tri_gru_tribunal_grupoModel extends Model
{
    protected $table='pdg_tri_gru_tribunal_grupo';
    protected $primaryKey='id_pdg_tri_gru';
    public $timestamps=false;
    protected $fillable=
        [
            'id_pdg_tri_rol',
            'id_pdg_dcn',
            'id_pdg_gru'
        ];

    public static function getTribunalData($idGrupo){
        $tribunalData = DB::table('pdg_tri_gru_tribunal_grupo')
            ->join('pdg_dcn_docente','pdg_dcn_docente.id_pdg_dcn','=','pdg_tri_gru_tribunal_grupo.id_pdg_dcn')
            ->join('pdg_tri_rol_tribunal_rol','pdg_tri_rol_tribunal_rol.id_pdg_tri_rol','=','pdg_tri_gru_tribunal_grupo.id_pdg_tri_rol')
            ->join('gen_usuario','gen_usuario.id','=','pdg_dcn_docente.id_gen_usuario')
            ->where('pdg_tri_gru_tribunal_grupo.id_pdg_gru','=',$idGrupo)
            ->orderBy('pdg_tri_rol_tribunal_rol.nombre_tri_rol','asc')
            ->get();
        return $tribunalData;
    }
    public static function nextRolToFill($idGrupo){
//        $tribunal = pdg_tri_gru_tribunal_grupoModel::where('id_pdg_gru','=',$idGrupo)
//            ->where('id_pdg_tri_rol','=',1)
//            ->exists();
//        if ($tribunal){
//        }else{
//        }
//        $nextRol = DB::table('pdg_tri_gru_tribunal_grupo')
//            ->join('pdg_tri_rol_tribunal_rol','pdg_tri_rol_tribunal_rol.id_pdg_tri_rol','=','pdg_tri_gru_tribunal_grupo.id_pdg_tri_rol')
//            ->get();
//        return $nextRol;
        return null;
    }
    public static function getRolesDisponibles($id){
        $roles = DB::table('pdg_tri_rol_tribunal_rol')
            ->leftJoin('pdg_tri_gru_tribunal_grupo', function ($join) use ($id){
                $join->on('pdg_tri_rol_tribunal_rol.id_pdg_tri_rol','=','pdg_tri_gru_tribunal_grupo.id_pdg_tri_rol')
                    ->where('pdg_tri_gru_tribunal_grupo.id_pdg_gru','=',$id);
            })
            ->whereNotIn('pdg_tri_rol_tribunal_rol.id_pdg_tri_rol',[2])//Excluir Rol Coordinador EJRG->PENDIENTE DE CONSULTA
            ->where(function ($query){
                $query->whereNull('pdg_tri_gru_tribunal_grupo.id_pdg_tri_gru')
                    ->orWhere('pdg_tri_rol_tribunal_rol.id_pdg_tri_rol','=',3);//Incluir Rol Jurado siempre EJRG->CORREGIR CON PARAMETROS TRIBUNAL
            })
            ->select('pdg_tri_rol_tribunal_rol.*')
            ->get();
        return $roles;
    }
}