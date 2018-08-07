<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pdg_per_perfilModel extends Model
{
    protected $table='pdg_per_perfil';
	protected $primaryKey='id_pdg_per';
	public $timestamps=false;
		protected $fillable=
		[
			'tema_pdg_per',
			'id_pdg_gru',
			'id_cat_sta',
			'id_cat_tpo_tra_gra',
			'id_cat_ctg_tra',
			'fecha_creacion_per',
		];

	public function categoriaEstado(){
	 	Return $this->belongsTo('App\cat_sta_estadoModel','id_cat_sta');
	 }

	 public function tipoTrabajo(){
	 	Return $this->belongsTo('App\cat_tpo_tra_gra_tipo_trabajo_graduacionModel','id_cat_tpo_tra_gra');
	 }
	 
	  public function grupo(){
	 	Return $this->belongsTo('App\pdg_gru_grupoModel','id_pdg_gru');
	 }


	 public function getGruposPerfil(){
	 	/*$grupos = DB::table('pdg_ppe_pre_perfil')
                 ->select('id_pdg_gru', DB::raw('count(*) as cantidadPrePerfiles'))
                 ->groupBy('id_pdg_gru')
                 ->get();*/
        $grupos = pdg_per_perfilModel::with('grupo')
        ->select('id_pdg_gru', DB::raw('count(*) as cantidadPerfiles'))
        ->orderBy('id_pdg_per','desc')
        ->groupBy('id_pdg_gru','id_pdg_per')
        ->get();
        return $grupos;

	 }
	 
}
