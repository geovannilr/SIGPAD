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
            ->select('pdg_tri_rol_tribunal_rol.id_pdg_tri_rol','pdg_tri_rol_tribunal_rol.nombre_tri_rol',
                DB::raw('count(if(pdg_tri_gru_tribunal_grupo.id_pdg_gru>0,1,null)) as cantidad')
            )
            ->groupBy('pdg_tri_rol_tribunal_rol.id_pdg_tri_rol','pdg_tri_rol_tribunal_rol.nombre_tri_rol')
            ->get();
        $array = array();
        $params=self::getParamsTribunal($id);
        foreach($roles as $rol){
            $rolId = $rol->id_pdg_tri_rol;
            $rolCant = $rol->cantidad;
            switch ($rolId){
                case 1:
                    if ($rolCant >= $params->first()->max_a)
                        $array[] = $rolId;
                    break;
                case 3:
                    if ($rolCant >= $params->first()->max_j)
                        $array[] = $rolId;
                    break;
                default:
                    break;
            }
        }
        $roles = $roles->filter(function ($value,$key) use($array){
            return !in_array($value->id_pdg_tri_rol,$array);
        });
        return $roles->toArray();
    }
    public static function getParamsTribunal($idGrupo){
        $anio = pdg_gru_grupoModel::find($idGrupo)->anio_pdg_gru;
//
        // validar
        //  el uso del
        //   $anio EN EL SIGUIENTE QUERY DE LOS PARAMETROS
//
        $paramsTribunal = DB::table('gen_par_parametros')
            ->where('nombre_gen_par','like','CANTMAXASESOR')
            ->orWhere('nombre_gen_par','like','CANTMINASESOR')
            ->orWhere('nombre_gen_par','like','CANTMAXJURADO')
            ->orWhere('nombre_gen_par','like','CANTMINJURADO')
            ->select(DB::raw("1 as 'rownum',
                                sum(CASE when nombre_gen_par = 'CANTMAXASESOR' THEN valor_gen_par else 0 END) AS 'max_a',
                                sum(CASE when nombre_gen_par = 'CANTMINASESOR' THEN valor_gen_par else 0 END) AS 'min_a',
                                sum(CASE when nombre_gen_par = 'CANTMAXJURADO' THEN valor_gen_par else 0 END) AS 'max_j',
                                sum(CASE when nombre_gen_par = 'CANTMINJURADO' THEN valor_gen_par else 0 END) AS 'min_j' ")
            )
            ->groupBy('rownum')
            ->get();
        return $paramsTribunal;
    }
}