<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class cat_ctg_tra_categoria_trabajo_graduacionModel extends Model{
    protected $table='cat_ctg_tra_categoria_trabajo_graduacion';
	protected $primaryKey='id_cat_ctg_tra';
	public $timestamps=false;
		protected $fillable=
		[
			'nombre_cat_ctg_tra',
		];

		public static function getDocentesCategoria(){
        $query = '
        		select  
				catTdg.id_cat_ctg_tra,
				dcn.id_pdg_dcn,
				catTdg.nombre_cat_ctg_tra,
				concat(usuario.primer_nombre," ",usuario.segundo_nombre," ",usuario.primer_apellido, " ",usuario.segundo_apellido) nombre
				from pdg_dcn_docente dcn
				inner join gen_usuario usuario on dcn.id_gen_usuario = usuario.id
				inner join rel_ski_dcn_skill_docente relSkillDcn on  relSkillDcn.id_pdg_dcn = dcn.id_pdg_dcn
				inner join cat_ski_skill skill on skill.id_cat_ski = relSkillDcn.id_cat_ski
				inner join cat_tpo_ski_tipo_skill tpoSKill on tpoSKill.id_tpo_ski = skill.id_tpo_ski 
				inner join rel_cat_tpo_ski_cat_ctg_tra rel_cat_tpo on rel_cat_tpo.id_tpo_ski = tpoSKill.id_tpo_ski
				inner join cat_ctg_tra_categoria_trabajo_graduacion catTdg on catTdg.id_cat_ctg_tra = rel_cat_tpo.id_cat_ctg_tra
				group by dcn.id_pdg_dcn,catTdg.id_cat_ctg_tra
				order by catTdg.id_cat_ctg_tra,nombre';

        $categoriasDocentes = DB::select($query);
        return $categoriasDocentes;
    }
}
