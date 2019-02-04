<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pdg_ppe_pre_perfilModel extends Model
{
    protected $table='pdg_ppe_pre_perfil';
	protected $primaryKey='id_pdg_ppe';
	public $timestamps=false;
		protected $fillable=
		[
			'tema_pdg_ppe',
			'nombre_archivo_pdg_ppe',
			'ubicacion_pdg_ppe',
			'fecha_creacion_pdg_ppe',
			'id_pdg_gru',
			'id_cat_sta',
			'id_cat_tpo_tra_gra',
			'id_gen_usuario'
		];

	public function categoriaEstado(){
	 	Return $this->belongsTo('App\cat_sta_estadoModel','id_cat_sta');
	 }

	 public function tipoTrabajo(){
	 	Return $this->belongsTo('App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel','id_cat_tpo_tra_gra');
	 }
	 public function usuario(){
	 	Return $this->belongsTo('App\gen_UsuarioModel','id');
	 }
	  public function grupo(){
	 	Return $this->belongsTo('App\pdg_gru_grupoModel','id_pdg_gru');
	 }
	


	 public function getGruposPrePerfil(){
	 	/*$grupos = DB::table('pdg_ppe_pre_perfil')
                 ->select('id_pdg_gru', DB::raw('count(*) as cantidadPrePerfiles'))
                 ->groupBy('id_pdg_gru')
                 ->get();*/
       /* $grupos = pdg_ppe_pre_perfilModel::with('grupo')
        ->select('id_pdg_gru', DB::raw('count(*) as cantidadPrePerfiles'))
        ->join('pdg_apr_eta_tra_aprobador_etapa_trabajo','gen_usuario.id','=','pdg_dcn_docente.id_gen_usuario')
        ->orderBy('id_pdg_ppe','desc')
        ->groupBy('id_pdg_gru')
        ->get();*/
       /* ULTIMO FUNCIONAL ANTES DE LIDER$grupos = DB::select("
        				select 
						prep.id_pdg_gru,
						gru.numero_pdg_gru,
						count(*) as cantidadPrePerfiles,
						(select aprobo from pdg_apr_eta_tra_aprobador_etapa_trabajo where id_cat_eta_eva = 999 AND id_pdg_tra_gra = 
							(SELECT id_pdg_tra_gra from pdg_tra_gra_trabajo_graduacion where id_pdg_gru = gru.id_pdg_gru )) as finalizo
						 from pdg_ppe_pre_perfil prep
						inner join  pdg_gru_grupo  gru on prep.id_pdg_gru = gru.id_pdg_gru
						group by prep.id_pdg_gru,gru.numero_pdg_gru
						order by prep.id_pdg_ppe desc"
						);*/
						$grupos = DB::select("
        				select 
						prep.id_pdg_gru,
						gru.numero_pdg_gru,
						count(*) as cantidadPrePerfiles,
                        estu.carnet_gen_est as carnetLider,
                        estu.nombre_gen_est as nombreLider,
						(select aprobo from pdg_apr_eta_tra_aprobador_etapa_trabajo where id_cat_eta_eva = 999 AND id_pdg_tra_gra = 
							(SELECT id_pdg_tra_gra from pdg_tra_gra_trabajo_graduacion where id_pdg_gru = gru.id_pdg_gru )) as finalizo
						 from pdg_ppe_pre_perfil prep
						inner join  pdg_gru_grupo  gru on prep.id_pdg_gru = gru.id_pdg_gru
                        inner join pdg_gru_est_grupo_estudiante est on est.id_pdg_gru = gru.id_pdg_gru
                        inner join gen_est_estudiante estu on estu.id_gen_est = est.id_gen_est
                        where est.eslider_pdg_gru_est = 1
						group by prep.id_pdg_gru,gru.numero_pdg_gru
						order by prep.id_pdg_ppe desc"
						);
		
        return $grupos;

	 }

	 public function getGruposPrePerfilDocente($idUsuario){
	 	$perfil = new  pdg_per_perfilModel();
        $grupos = $perfil->getGruposPerfilDocente($idUsuario);
        $array=array();
        $ids = "";
        $contador = 0 ;
        if (sizeof($grupos) != 0) {
                foreach ($grupos as $grupo) {
                	if ($contador == 0) {
                		$ids.= "(".$grupo->id_pdg_gru;
                	}else{
                		$ids.= ",".$grupo->id_pdg_gru;
                	}
                $array [] = $grupo->id_pdg_gru;
                $contador++;
            }
            $ids.=")";
            /*$grupos = pdg_ppe_pre_perfilModel::with('grupo')
	        ->select('id_pdg_gru', DB::raw('count(*) as cantidadPrePerfiles'))
	        ->whereIn('id_pdg_gru',$array)
	        ->orderBy('id_pdg_ppe','desc')
	        ->groupBy('id_pdg_gru')
	        ->get(); */
	        $grupos = DB::select("
        				select 
						prep.id_pdg_gru,
						gru.numero_pdg_gru,
						count(*) as cantidadPrePerfiles,
						estu.carnet_gen_est as carnetLider,
                        estu.nombre_gen_est as nombreLider,
						(select aprobo from pdg_apr_eta_tra_aprobador_etapa_trabajo where id_cat_eta_eva = 999 AND id_pdg_tra_gra = 
							(SELECT id_pdg_tra_gra from pdg_tra_gra_trabajo_graduacion where id_pdg_gru = gru.id_pdg_gru )) as finalizo
						 from pdg_ppe_pre_perfil prep
						inner join  pdg_gru_grupo  gru on prep.id_pdg_gru = gru.id_pdg_gru
						inner join pdg_gru_est_grupo_estudiante est on est.id_pdg_gru = gru.id_pdg_gru
                        inner join gen_est_estudiante estu on estu.id_gen_est = est.id_gen_est
						where gru.id_pdg_gru IN ".$ids." AND est.eslider_pdg_gru_est = 1
						group by prep.id_pdg_gru,gru.numero_pdg_gru
						order by prep.id_pdg_ppe desc"
						);

        }else{
        	$grupos = "NA";
        }
 		
 		return $grupos;
	 }
	 
}
