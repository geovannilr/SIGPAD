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

    public static function getLideres($anio){
        $lideres=DB::select("select distinct x.NumGrupo, x.Carnet, x.Lider 
            from (select gru.numero_pdg_gru as NumGrupo, 
            UPPER(est.carnet_gen_est) as Carnet,
            est.nombre_gen_est as Lider, 
            triR.nombre_tri_rol as TribunalRol,
            usr.name  as Docente
            from pdg_tri_gru_tribunal_grupo triG
             join pdg_gru_est_grupo_estudiante gruE on triG.id_pdg_gru=gruE.id_pdg_gru and gruE.eslider_pdg_gru_est=1
                 join pdg_tri_rol_tribunal_rol triR on triR.id_pdg_tri_rol=triG.id_pdg_tri_rol
                 join gen_est_estudiante est on est.id_gen_est=gruE.id_gen_est
                 left join pdg_dcn_docente dcn on dcn.id_pdg_dcn=triG.id_pdg_dcn
                 left join gen_usuario usr on usr.id=dcn.id_gen_usuario
                left join pdg_gru_grupo gru on gru.id_pdg_gru=gruE.id_pdg_gru
                where gru.anio_pdg_gru=:anio
                order by gru.numero_pdg_gru asc) x",
            array(
                $anio,
            )
        );
        return $lideres;
    }
    public static function getTribunales($anio){
        $trib = DB::select("select distinct x.NumGrupo,x.TribunalRol,x.Docente
            from (select gru.numero_pdg_gru as NumGrupo,
            est.carnet_gen_est as Carnet,
            est.nombre_gen_est as Lider,
            triR.nombre_tri_rol as TribunalRol,
            usr.name  as Docente
            from pdg_tri_gru_tribunal_grupo triG
             join pdg_gru_est_grupo_estudiante gruE on triG.id_pdg_gru=gruE.id_pdg_gru and gruE.eslider_pdg_gru_est=1
                 join pdg_tri_rol_tribunal_rol triR on triR.id_pdg_tri_rol=triG.id_pdg_tri_rol
                 join gen_est_estudiante est on est.id_gen_est=gruE.id_gen_est
                 left join pdg_dcn_docente dcn on dcn.id_pdg_dcn=triG.id_pdg_dcn
                 left join gen_usuario usr on usr.id=dcn.id_gen_usuario
                left join pdg_gru_grupo gru on gru.id_pdg_gru=gruE.id_pdg_gru
                where gru.anio_pdg_gru=:anio) x
                order by x.TribunalRol asc",
            array(
                $anio
            )
        );
        return $trib;
    }
    public static function getDocentes($anio){
        $docentes = DB::select("select distinct x.CarnetDoc,x.Docente
            from (select gru.numero_pdg_gru as NumGrupo,
            est.carnet_gen_est as Carnet,
            est.nombre_gen_est as Lider,
            triR.nombre_tri_rol as TribunalRol,
            usr.name  as Docente,
            usr.user as CarnetDoc
            from pdg_tri_gru_tribunal_grupo triG
             join pdg_gru_est_grupo_estudiante gruE on triG.id_pdg_gru=gruE.id_pdg_gru and gruE.eslider_pdg_gru_est=1
                 join pdg_tri_rol_tribunal_rol triR on triR.id_pdg_tri_rol=triG.id_pdg_tri_rol
                 join gen_est_estudiante est on est.id_gen_est=gruE.id_gen_est
                 left join pdg_dcn_docente dcn on dcn.id_pdg_dcn=triG.id_pdg_dcn
                 left join gen_usuario usr on usr.id=dcn.id_gen_usuario
                left join pdg_gru_grupo gru on gru.id_pdg_gru=gruE.id_pdg_gru
                where gru.anio_pdg_gru=:anio) x
                order by x.CarnetDoc asc",
            array(
                $anio
            )
        );
        return $docentes;
    }
    public static function getGrupos($anio){
        $docentes = DB::select("select distinct x.CarnetDoc,x.NumGrupo, x.TribunalRol
            from (select gru.numero_pdg_gru as NumGrupo,
            est.carnet_gen_est as Carnet,
            est.nombre_gen_est as Lider,
            triR.nombre_tri_rol as TribunalRol,
            usr.name  as Docente,
            usr.user as CarnetDoc
            from pdg_tri_gru_tribunal_grupo triG
             join pdg_gru_est_grupo_estudiante gruE on triG.id_pdg_gru=gruE.id_pdg_gru and gruE.eslider_pdg_gru_est=1
                 join pdg_tri_rol_tribunal_rol triR on triR.id_pdg_tri_rol=triG.id_pdg_tri_rol
                 join gen_est_estudiante est on est.id_gen_est=gruE.id_gen_est
                 left join pdg_dcn_docente dcn on dcn.id_pdg_dcn=triG.id_pdg_dcn
                 left join gen_usuario usr on usr.id=dcn.id_gen_usuario
                left join pdg_gru_grupo gru on gru.id_pdg_gru=gruE.id_pdg_gru
                where gru.anio_pdg_gru=:anio) x
                order by x.NumGrupo, x.TribunalRol asc",
            array(
                $anio
            )
        );
        return $docentes;
    }
    public static function reportDocentesAsignaciones($anio){
        $data =  DB::select("call sp_pdg_get_tribunalPorGrupos_byAnio(:anio)", array($anio));
        return $data;
    }
}