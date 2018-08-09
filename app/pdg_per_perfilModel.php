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
	 public function getGruposPerfilDocente($idDocente){
        $grupos = DB::table('pdg_dcn_docente')
            ->join('pdg_tri_gru_tribunal_grupo', 'pdg_dcn_docente.id_pdg_dcn', '=', 'pdg_tri_gru_tribunal_grupo.id_pdg_dcn')
            ->select('pdg_tri_gru_tribunal_grupo.id_pdg_gru')
            ->where('pdg_dcn_docente.id_gen_usuario', $idDocente)
            ->where('pdg_tri_gru_tribunal_grupo.id_pdg_tri_rol', 1) //QUEMAZON ROL ASESOR
            ->get();
        return $grupos;
	 }
	 
}
