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
        $grupos = pdg_ppe_pre_perfilModel::with('grupo')
        ->select('id_pdg_gru', DB::raw('count(*) as cantidadPrePerfiles'))
        ->orderBy('id_pdg_ppe','desc')
        ->groupBy('id_pdg_gru')
        ->get();
        return $grupos;

	 }

	 public function getGruposPrePerfilDocente($idUsuario){
	 	$perfil = new  pdg_per_perfilModel();
        $grupos = $perfil->getGruposPerfilDocente($idUsuario);
        $array=array();
        if (sizeof($grupos) != 0) {
                foreach ($grupos as $grupo) {
                $array [] = $grupo->id_pdg_gru;
            }
            $grupos = pdg_ppe_pre_perfilModel::with('grupo')
	        ->select('id_pdg_gru', DB::raw('count(*) as cantidadPrePerfiles'))
	        ->whereIn('id_pdg_gru',$array)
	        ->orderBy('id_pdg_ppe','desc')
	        ->groupBy('id_pdg_gru')
	        ->get();  

        }else{
        	$grupos = "NA";
        }
 		
 		return $grupos;
	 }
	 
}
