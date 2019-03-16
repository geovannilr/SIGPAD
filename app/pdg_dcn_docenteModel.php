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


    public function cargoPrincipal(){
        return $this->hasOne('App\cat_car_cargo_eisiModel','id_cat_car','id_cargo_actual');
    }
     public function cargoSecundario(){
        return $this->hasOne('App\cat_car_cargo_eisiModel','id_cat_car','id_segundo_cargo');
    }
    public function catTpoJornada(){
        return $this->hasOne('App\cat_tpo_jrn_dcn_tipo_jornada_docenteModel','id_cat_tpo_jrn_dcn','tipoJornada');
    }
    /***
     * Función: getDocentesDisponibles($id)
     * Params: $id-> Id del grupo de TDG
     * Retorna: Lista de docentes que no han sido asignados al tribunal de un grupo, excluyendo coordinadores de tdg y tribunales de grupos finalizados.
    */
    public static function getDocentesDisponibles($id){
        $coordinadores = self::getDocentesCoordinadores();
        $arrayCoordinadores = array();
        if(sizeof($coordinadores)!=0){
            foreach ($coordinadores as $coordinador){
                $arrayCoordinadores [] = $coordinador -> id_pdg_dcn;
            }
        }
        $tribunal = pdg_tri_gru_tribunal_grupoModel::getTribunalData($id);
        $array=array();
        if (sizeof($tribunal) != 0) {
            foreach ($tribunal as $tri) {
                $array [] = $tri->id_pdg_dcn;
            }
        }
        $idsGruposFinalizados = pdg_gru_grupoModel::getIdsGruposFinalizados();
        $finalizadosStr="(";
        if (sizeof($idsGruposFinalizados) != 0) {
            foreach ($idsGruposFinalizados as $idGrupo) {
                $finalizadosStr = $finalizadosStr.$idGrupo->id_pdg_gru.",";
            }
            $finalizadosStr = substr($finalizadosStr,0,strlen($finalizadosStr)-1);
            $finalizadosStr .= ")";
        }
        $docentes = DB::table('pdg_dcn_docente')
        ->join('gen_usuario','gen_usuario.id','=','pdg_dcn_docente.id_gen_usuario')
        ->leftJoin('pdg_tri_gru_tribunal_grupo','pdg_tri_gru_tribunal_grupo.id_pdg_dcn','=','pdg_dcn_docente.id_pdg_dcn')
        ->where('pdg_dcn_docente.activo','=',1)
        ->whereNotIn('pdg_dcn_docente.id_pdg_dcn',$array)
        ->whereNotIn('pdg_dcn_docente.id_pdg_dcn',$arrayCoordinadores)
        //->whereNotIn('pdg_tri_gru_tribunal_grupo.id_pdg_gru',)
        ->select('gen_usuario.name','gen_usuario.email','pdg_dcn_docente.id_pdg_dcn',
            DB::raw('count(
                        if(pdg_tri_gru_tribunal_grupo.id_pdg_tri_rol=1 
                            AND pdg_tri_gru_tribunal_grupo.id_pdg_gru NOT IN '.$finalizadosStr.' ,1,null)
                        ) as asigned_as_A, 
                    count(
                        if(pdg_tri_gru_tribunal_grupo.id_pdg_tri_rol=3
                            AND pdg_tri_gru_tribunal_grupo.id_pdg_gru NOT IN '.$finalizadosStr.' ,1,null)
                        ) as asigned_as_J ')
        )
        ->groupBy('gen_usuario.name','gen_usuario.email','pdg_dcn_docente.id_pdg_dcn')
        ->orderBy('asigned_as_A','DESC')
        ->orderBy('asigned_as_J','DESC')
        ->get();
        return $docentes;
    }

    public static function getLideres($anio,$estado){
        if ($estado == 0 || $estado == 1 ) {
            $where = " where ifnull(x.finalizo,0) =:estado ";
        }elseif ($estado == 2) {
            $where = " ";
        }
        $query = "select distinct x.NumGrupo, x.Carnet, x.Lider, x.finalizo
            from (select gru.numero_pdg_gru as NumGrupo, 
            UPPER(est.carnet_gen_est) as Carnet,
            est.nombre_gen_est as Lider, 
            triR.nombre_tri_rol as TribunalRol,
            usr.name  as Docente,
			IFNULL((select aprobo from pdg_apr_eta_tra_aprobador_etapa_trabajo where id_cat_eta_eva = 999 AND id_pdg_tra_gra = 
            (SELECT id_pdg_tra_gra from pdg_tra_gra_trabajo_graduacion where id_pdg_gru = gru.id_pdg_gru )),0) as finalizo
            from pdg_tri_gru_tribunal_grupo triG
             join pdg_gru_est_grupo_estudiante gruE on triG.id_pdg_gru=gruE.id_pdg_gru and gruE.eslider_pdg_gru_est=1
                 join pdg_tri_rol_tribunal_rol triR on triR.id_pdg_tri_rol=triG.id_pdg_tri_rol
                 join gen_est_estudiante est on est.id_gen_est=gruE.id_gen_est
                 left join pdg_dcn_docente dcn on dcn.id_pdg_dcn=triG.id_pdg_dcn
                 left join gen_usuario usr on usr.id=dcn.id_gen_usuario
                left join pdg_gru_grupo gru on gru.id_pdg_gru=gruE.id_pdg_gru
                where gru.anio_pdg_gru = :anio
                order by gru.numero_pdg_gru asc) x
                ".$where."
          order by x.finalizo DESC, x.NumGrupo ASC";
        if ($estado == 0 || $estado == 1 ) {
            $lideres=DB::select($query, array($anio,$estado));
        }elseif ($estado == 2) {
            $lideres=DB::select($query, array($anio));
        }
        return $lideres;
    }
    public static function getTribunales($anio,$estado){
        if ($estado == 0 || $estado == 1 ) {
            $where = " where ifnull(x.finalizo,0) =:estado "; 
        }elseif ($estado == 2) {
           $where = " ";
        }
        $select = "select distinct x.NumGrupo,x.TribunalRol,x.Docente
            from (select gru.numero_pdg_gru as NumGrupo,
            est.carnet_gen_est as Carnet,
            est.nombre_gen_est as Lider,
            triR.nombre_tri_rol as TribunalRol,
            usr.name  as Docente,
            (select aprobo from pdg_apr_eta_tra_aprobador_etapa_trabajo where id_cat_eta_eva = 999 AND id_pdg_tra_gra = 
            (SELECT id_pdg_tra_gra from pdg_tra_gra_trabajo_graduacion where id_pdg_gru = gru.id_pdg_gru )) as finalizo
            from pdg_tri_gru_tribunal_grupo triG
             join pdg_gru_est_grupo_estudiante gruE on triG.id_pdg_gru=gruE.id_pdg_gru and gruE.eslider_pdg_gru_est=1
                 join pdg_tri_rol_tribunal_rol triR on triR.id_pdg_tri_rol=triG.id_pdg_tri_rol
                 join gen_est_estudiante est on est.id_gen_est=gruE.id_gen_est
                 left join pdg_dcn_docente dcn on dcn.id_pdg_dcn=triG.id_pdg_dcn
                 left join gen_usuario usr on usr.id=dcn.id_gen_usuario
                left join pdg_gru_grupo gru on gru.id_pdg_gru=gruE.id_pdg_gru
                where gru.anio_pdg_gru=:anio AND gru.id_cat_sta not in(7,2,1)) x
                ".$where."
                order by x.TribunalRol asc";

        if ($estado == 0 || $estado == 1 ) {
            $trib = DB::select($select,array($anio,$estado));
        }elseif ($estado == 2) {
           $trib = DB::select($select,array($anio));
        }        
        
        return $trib;
    }
    public static function getDocentes($anio,$estado){
        if ($estado == 0 || $estado == 1 ) {
            $where = " where ifnull(x.finalizo,0) =:estado ";
        }elseif ($estado == 2) {
            $where = " ";
        }
        $select = "select distinct x.CarnetDoc,x.Docente
            from (select gru.numero_pdg_gru as NumGrupo,
            est.carnet_gen_est as Carnet,
            est.nombre_gen_est as Lider,
            triR.nombre_tri_rol as TribunalRol,
            usr.name  as Docente,
            usr.user as CarnetDoc,
            (select aprobo from pdg_apr_eta_tra_aprobador_etapa_trabajo where id_cat_eta_eva = 999 AND id_pdg_tra_gra = 
            (SELECT id_pdg_tra_gra from pdg_tra_gra_trabajo_graduacion where id_pdg_gru = gru.id_pdg_gru )) as finalizo
            from pdg_tri_gru_tribunal_grupo triG
             join pdg_gru_est_grupo_estudiante gruE on triG.id_pdg_gru=gruE.id_pdg_gru and gruE.eslider_pdg_gru_est=1
                 join pdg_tri_rol_tribunal_rol triR on triR.id_pdg_tri_rol=triG.id_pdg_tri_rol
                 join gen_est_estudiante est on est.id_gen_est=gruE.id_gen_est
                 left join pdg_dcn_docente dcn on dcn.id_pdg_dcn=triG.id_pdg_dcn
                 left join gen_usuario usr on usr.id=dcn.id_gen_usuario
                left join pdg_gru_grupo gru on gru.id_pdg_gru=gruE.id_pdg_gru
                where gru.anio_pdg_gru=:anio) x
                ".$where."
                order by x.CarnetDoc asc";
        if ($estado == 0 || $estado == 1 ) {
            $docentes = DB::select($select,array($anio,$estado));
        }elseif ($estado == 2) {
            $docentes = DB::select($select,array($anio));
        }
        return $docentes;
    }
    public static function getGrupos($anio,$estado){
        if ($estado == 0 || $estado == 1 ) {
            $where = " where ifnull(x.finalizo,0) =:estado ";
        }elseif ($estado == 2) {
            $where = " ";
        }
        $query = "select distinct x.CarnetDoc,x.NumGrupo, x.TribunalRol, x.finalizo
            from (select gru.numero_pdg_gru as NumGrupo,
            est.carnet_gen_est as Carnet,
            est.nombre_gen_est as Lider,
            triR.nombre_tri_rol as TribunalRol,
            usr.name  as Docente,
            usr.user as CarnetDoc,
            IFNULL((select aprobo from pdg_apr_eta_tra_aprobador_etapa_trabajo where id_cat_eta_eva = 999 AND id_pdg_tra_gra = 
            (SELECT id_pdg_tra_gra from pdg_tra_gra_trabajo_graduacion where id_pdg_gru = gru.id_pdg_gru )),0) as finalizo
            from pdg_tri_gru_tribunal_grupo triG
             join pdg_gru_est_grupo_estudiante gruE on triG.id_pdg_gru=gruE.id_pdg_gru and gruE.eslider_pdg_gru_est=1
                 join pdg_tri_rol_tribunal_rol triR on triR.id_pdg_tri_rol=triG.id_pdg_tri_rol
                 join gen_est_estudiante est on est.id_gen_est=gruE.id_gen_est
                 left join pdg_dcn_docente dcn on dcn.id_pdg_dcn=triG.id_pdg_dcn
                 left join gen_usuario usr on usr.id=dcn.id_gen_usuario
                left join pdg_gru_grupo gru on gru.id_pdg_gru=gruE.id_pdg_gru
                where gru.anio_pdg_gru=:anio) x
                ".$where."
                order by x.NumGrupo, x.TribunalRol asc";
        if ($estado == 0 || $estado == 1 ) {
            $docentes = DB::select($query, array($anio,$estado));
        }elseif ($estado == 2) {
            $docentes = DB::select($query, array($anio));
        }
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
                            IFNULL(id_dcn_exp,'') as id_dcn_exp,
                            IFNULL(lugar_trabajo_dcn_exp,'') as lugar_trabajo_dcn_exp,
                            IFNULL(anio_inicio_dcn_exp,'') as anio_inicio_dcn_exp,
                            IFNULL(anio_fin_dcn_exp,'') as anio_fin_dcn_exp,
                            IFNULL(idiomaExper,'') as idiomaExper,
                            IFNULL(descripcionExperiencia,'') as descripcionExperiencia
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
                            IFNULL(id_dcn_cer,'') as id_dcn_cer,
                            IFNULL(nombre_dcn_cer,'') as nombre_dcn_cer,
                            IFNULL(anio_expedicion_dcn_cer,'') as anio_expedicion_dcn_cer,
                            IFNULL(institucion_dcn_cer,'') as institucion_dcn_cer,
                            IFNULL(idiomaCert,'') as idiomaCert
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
                            IFNULL(Nivel,'') as Nivel,
                            IFNULL(id_cat_ski,'') as id_cat_ski,
                            IFNULL(nombre_cat_ski,'') as nombre_cat_ski
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
                            IFNULL(usuario.name,'') as name,
                            IFNULL(usuario.primer_nombre,'') as primer_nombre,
                            IFNULL(usuario.segundo_nombre,'') as segundo_nombre,
                            IFNULL(usuario.primer_apellido,'') as primer_apellido,
                            IFNULL(usuario.segundo_apellido,'') as segundo_apellido,
                            IFNULL(usuario.email,'') as email,
                            IFNULL(docente.tipoJornada,'') as tipoJornada,
                            IFNULL(docente.descripcionDocente,'') as descripcionDocente,
                            IFNULL(docente.display_name,'') as display_name,
                            IFNULL(docente.id_segundo_cargo,'') as id_segundo_cargo,
                            IFNULL(cargo.id_cat_car,'') as id_cat_car,
                            IFNULL(cargo.nombre_cargo,'') as nombre_cargo,
                            IFNULL(cargo2.nombre_cargo,'') as nombre_cargo2,
                            IFNULL(docente.dcn_profileFoto,'') as dcn_profileFoto,
                            IFNULL(docente.link_git,'') as link_git
                            ,IFNULL(docente.link_linke,'') as link_linke
                            ,IFNULL(docente.link_tw,'') as link_tw
                            ,IFNULL(docente.link_fb,'') as link_fb
                            ,IFNULL(docente.display_name,'') as display_name,
                            IFNULL(docente.perfilPrivado,'') as perfilPrivado,
                            IFNULL(docente.id_pdg_dcn,'') as id_pdg_dcn
                            
                            from  pdg_dcn_docente docente
                            inner join gen_usuario usuario on usuario.id = docente.id_gen_usuario 
                            left join cat_car_cargo_eisi cargo on cargo.id_cat_car=docente.id_cargo_actual
                            left join cat_car_cargo_eisi cargo2 on cargo2.id_cat_car=docente.id_segundo_cargo
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
            IFNULL(car2.nombre_cargo,'') AS nombre_cargo2,
            COALESCE(dcn.dcn_profileFoto ,'default.jpg') as dcn_profileFoto,
            dcn.tipoJornada,
            dcn.perfilPrivado
            ,COALESCE(dcn.display_name,usr.primer_nombre||' '||usr.primer_apellido) as display_name
            ,jrnd.descripcion_cat_tpo_jrn_dcn AS emp_clasif
            from pdg_dcn_docente dcn 
            inner join gen_usuario usr on usr.id=dcn.id_gen_usuario 
            left join cat_car_cargo_eisi car on car.id_cat_car=dcn.id_cargo_actual
            LEFT JOIN cat_car_cargo_eisi car2 ON car2.id_cat_car=dcn.id_segundo_cargo
            INNER JOIN cat_tpo_jrn_dcn_tipo_jornada_docente jrnd ON dcn.tipoJornada = jrnd.id_cat_tpo_jrn_dcn
            where dcn.es_visible_pdg_dcn=1
            ORDER BY dcn.tipoJornada ASC, dcn.pdg_dcn_prioridad DESC", // and dcn.tipoJornada=:jornada",
            array(
                $jornada
            )
        );
        return $docentes;
    }
  
    private static function getDocentesCoordinadores(){
        $query = "SELECT 
                        dcn.id_pdg_dcn 
                    FROM pdg_dcn_docente dcn
                        INNER JOIN role_user rlusr 	ON rlusr.user_id = dcn.id_gen_usuario
                        INNER JOIN roles	rl		ON rl.id = rlusr.role_id
                    WHERE
                        rl.slug like '%administrador_tdg%'";
        $coordinadores = DB::select($query);
        return $coordinadores;
    }
}
