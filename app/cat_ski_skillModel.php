<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_ski_skillModel extends Model{
    protected $table='cat_ski_skill';
	protected $primaryKey='id_cat_ski';
	public $timestamps=false;
	protected $fillable=
		[
			'nombre_cat_ski',
			'id_tpo_ski'
		];
    const NIVEL = ['Básico','Intermedio','Avanzado'];
    public function tipoSki(){
        return $this->belongsTo('App\cat_tpo_ski_tipo_skillModel','id_tpo_ski','id_tpo_ski');
    }

    public static function getNivelesSkills(){
        $result = array();
        $niveles = self::NIVEL;
        $id = 0;
        foreach ($niveles as $nivel){
            $id++;
            $result[$id]=$nivel;
        }
        return $result;
    }
}
