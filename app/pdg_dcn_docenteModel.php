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
            'id_gen_usuario',
            'perfilPrivado'
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
    public function getDataGestionDocente($idDocente){

        $data = DB::select("SELECT * FROM sigpad_dev.view_dcn_perfildocente where id_pdg_dcn =:idDocente",
            array(
                $idDocente
            )
        );
        return $data;
    }
    public static function getHistorialAcademico($idDocente){
        $data =  DB::select("call sp_dcn_get_historial_academicoByDocente(:idDocente)", array($idDocente));
        return $data;
    }

    public function getDataExperienciaDocente($idDocente){

        $data = DB::select("select distinct
                            id_dcn_exp,
                            lugar_trabajo_dcn_exp,
                            anio_inicio_dcn_exp,
                            anio_fin_dcn_exp,
                            idiomaExper,
                            descripcionExperiencia
                            from view_dcn_perfildocente
                            where id_pdg_dcn =:idDocente",
            array(
                $idDocente
            )
        );
        return $data;
    }

    public function getDataCertificacionesDocente($idDocente){

        $data = DB::select("select distinct
                            id_dcn_cer,
                            nombre_dcn_cer,
                            anio_expedicion_dcn_cer,
                            institucion_dcn_cer,
                            idiomaCert
                            from view_dcn_perfildocente
                            where id_pdg_dcn = :idDocente",
            array(
                $idDocente
            )
        );
        return $data;
    }

    public function getDataSkillsDocente($idDocente){

        $data = DB::select("select distinct
                            Nivel,
                            nombre_cat_ski
                            from view_dcn_perfildocente
                            where id_pdg_dcn =  :idDocente",
            array(
                $idDocente
            )
        );
        return $data;
    }
     public function getGeneralInfo($idDocente){
         $data = DB::select("select 
                            usuario.primer_nombre,
                            usuario.segundo_nombre,
                            usuario.primer_apellido,
                            usuario.segundo_apellido,
                            usuario.email,
                            docente.tipoJornada,
                            docente.descripcionDocente,
                            cargo.id_cat_car,
                            cargo.nombre_cargo,
                            docente.dcn_profileFoto,
                            docente.link_git
                            ,docente.link_linke
                            ,docente.link_tw
                            ,docente.link_fb

                            from  pdg_dcn_docente docente
                            inner join gen_usuario usuario on usuario.id = docente.id_gen_usuario 
                            left join cat_car_cargo_eisi cargo on cargo.id_cat_car=docente.id_cargo_actual
                            where docente.id_pdg_dcn=:idDocente",
            array(
                $idDocente
            )
        );
        return $data;
      
    }

    public static function getListadoDocenteByJornada($jornada){
        $docentes = DB::select("select 
            dcn.id_pdg_dcn,
            usr.primer_nombre,
            COALESCE(usr.segundo_nombre, '') as segundo_nombre ,
            usr.primer_apellido,
            COALESCE(usr.segundo_apellido, '') as segundo_apellido ,            
            car.nombre_cargo,
            COALESCE(dcn.dcn_profileFoto ,'https://profile.actionsprout.com/default.jpeg') as dcn_profileFoto,
            dcn.tipoJornada,
            dcn.perfilPrivado
            from pdg_dcn_docente dcn 
            inner join gen_usuario usr on usr.id=dcn.id_gen_usuario 
            left join cat_car_cargo_eisi car on car.id_cat_car=dcn.id_cargo_actual
            where dcn.activo=1", // and dcn.tipoJornada=:jornada",
            array(
                $jornada
            )
        );
        return $docentes;
    }
  

}