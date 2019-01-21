<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pub_publicacionModel extends Model{

	protected $table='pub_publicacion';
	protected $primaryKey='id_pub';
	public $timestamps=false;
		protected $fillable=
		[
			'id_cat_tpo_pub',
			'id_gen_int',
			'titulo_pub',
			'anio_pub',
			'correlativo_pub',
			'codigo_pub',
			'resumen_pub',
            'es_visible_pub'
		];
	public function getColaboradores($idPub){ 
	 	$resultado=DB::select('
					select 
					rel.id_pub,
					rel.id_pub_col,
					rel.id_cat_tpo_col_pub,
					col.nombres_pub_col,
					col.apellidos_pub_col,
					tipo.nombre_cat_tpo_col_pub
					from rel_col_pub_colaborador_publicacion rel
					inner join pub_col_colaborador col on rel.id_pub_col = col.id_pub_col
					inner join cat_tpo_col_pub_tipo_colaborador tipo on tipo.id_cat_tpo_col_pub = rel.id_cat_tpo_col_pub
					where id_pub ='.$idPub.';
					');
        return $resultado;
	 }

     public function getPubNombreAutor($nombre){
         $nombre = str_replace(' ','%',$nombre);
        $publicaciones = DB::select("select 
        pub.id_pub,
        pub.titulo_pub,
        pub.anio_pub,
        pub.codigo_pub,
        CONCAT(autor.nombres_pub_aut, '  ', autor.apellidos_pub_aut) As nombre_criterio,
        'X'	AS tipo_criterio
        from pub_publicacion pub
        inner join pub_aut_publicacion_autor autor on autor.id_pub=pub.id_pub
        WHERE CONCAT(autor.nombres_pub_aut,' ',autor.apellidos_pub_aut) like '%".$nombre."%' "
        );
        return $publicaciones;
     }
	 public function getPubNombreColaborador($nombre,$tipo){
	    $nombre = str_replace(' ','%',$nombre);
		$publicaciones = DB::select("select 
		pub.id_pub,
		pub.titulo_pub,
		pub.anio_pub,
		pub.codigo_pub,
		CONCAT(colaborador.nombres_pub_col, '  ', colaborador.apellidos_pub_col) As nombre_criterio,
		tipo.nombre_cat_tpo_col_pub	AS tipo_criterio
		from pub_publicacion pub
		inner join rel_col_pub_colaborador_publicacion relacion on relacion.id_pub=pub.id_pub
		inner join pub_col_colaborador colaborador on colaborador.id_pub_col=relacion.id_pub_col
		inner join cat_tpo_col_pub_tipo_colaborador tipo on tipo.id_cat_tpo_col_pub=relacion.id_cat_tpo_col_pub
		WHERE (CONCAT(colaborador.nombres_pub_col,' ',colaborador.apellidos_pub_col) like '%".$nombre."%' ) AND tipo.id_cat_tpo_col_pub = ".$tipo
        );
        return $publicaciones;
	 }
	 public static function getIdPublicacion($idGrupo){
	    $idpub = DB::select('   SELECT 
                                    pub.id_pub
                                FROM 
                                    gen_int_integracion integ
                                    INNER JOIN pub_publicacion pub ON (pub.id_gen_int=integ.id_gen_int)
                                WHERE
                                    integ.id_gen_tpo_int = 1
                                    AND integ.llave_gen_int = :idGrupo
                                    AND pub.id_cat_tpo_pub = 1
                                LIMIT 1 ', array($idGrupo));
	    return $idpub;
     }
    public static function getIdGrupo($idPublicacion){
        $idGrupo = DB::select('   SELECT 
                                    integ.llave_gen_int as id_pdg_gru
                                FROM 
                                    gen_int_integracion integ
                                    INNER JOIN pub_publicacion pub ON (pub.id_gen_int=integ.id_gen_int)
                                WHERE
                                    integ.id_gen_tpo_int = 1
                                    AND pub.id_pub = :idPublicacion
                                    AND pub.id_cat_tpo_pub = 1  ', array($idPublicacion));
        return $idGrupo;
    }

    public static function deletePublicacionAndRelations($idPublicacion){
        $result = true;
        try{
            $idsIntegracionAutores = self::getIdsIntegracionAutores($idPublicacion);
            DB::table('rel_col_pub_colaborador_publicacion')->where('id_pub','=',$idPublicacion)->delete();
            DB::table('pub_aut_publicacion_autor')->where('id_pub','=',$idPublicacion)->delete();
            DB::table('pub_arc_publicacion_archivo')->where('id_pub','=',$idPublicacion)->delete();
            DB::table('gen_int_integracion')->where('id_gen_tpo_int','=',3)->whereIn('id_gen_int',$idsIntegracionAutores)->delete();
            pub_publicacionModel::destroy($idPublicacion);
        } catch (\Exception $exception){
            $result = false;
        }
        return $result;
    }

    public static function getIdsIntegracionAutores($idPublicacion){
        $autores = pub_aut_publicacion_autorModel::where('id_pub','=',$idPublicacion)->get();
        $idsIntegracionAutores = array();
        foreach ($autores as $autor){
            $idsIntegracionAutores[] = $autor->id_gen_int;
        }
        return $idsIntegracionAutores;
    }
}
