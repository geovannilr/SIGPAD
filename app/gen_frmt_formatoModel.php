<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class gen_frmt_formatoModel extends Model{
    protected $table='gen_frmt_formato';
	protected $primaryKey='id_gen_frmt';
	public $timestamps=false;
	protected $fillable=
        [
			'codigo_gen_frmt',
			'nombre_gen_frmt',
			'descripcion_gen_frmt'
        ];

	public static function findFormatosDisponibles($userId){
	    $query = "  SELECT 
                        frmt.nombre_gen_frmt		AS nombre,
                        frmt.descripcion_gen_frmt	AS descripcion,
                        frmt.codigo_gen_frmt		AS codigo
                    FROM 
                        gen_frmt_formato frmt
                        INNER JOIN rel_rol_gen_frmt rel ON (rel.codigo_gen_frmt = frmt.codigo_gen_frmt)
                        INNER JOIN roles rol 			ON (BINARY rol.slug = BINARY rel.slug_rol)
                        INNER JOIN role_user usr 		ON (usr.role_id = rol.id)
                    WHERE
                        usr.user_id = :userId
                    ";
	    $formatos = DB::select($query,array($userId));
        return $formatos;
    }

}
