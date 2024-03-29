<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\pub_arc_publicacion_archivoModel;

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

    public static function nuevaPublicacion($tema, $autores, $colaboradores){
	    $success = true;
	    $msg = 'Publicación creada con éxito,';
        try{
            DB::transaction(function() use ($tema, $autores, $colaboradores, &$msg) {
                $id = self::formatId($tema['grupo']);
                $integracionPub = self::getValidIntegracionPub($id);
                $codigoPub = self::getCodigoPubAvailable($id);
                $newPublicacion = new pub_publicacionModel([
                    'id_cat_tpo_pub' => 1,
                    'id_gen_int' => empty($integracionPub) ? null : $integracionPub->id_gen_int,
                    'titulo_pub' => $tema['tema'],
                    'anio_pub' => substr($codigoPub,0,4),
                    'correlativo_pub' => substr($codigoPub,4,2),
                    'codigo_pub' => $codigoPub,
                    'resumen_pub' => $tema['resumen'],
                    'es_visible_pub' => 1
                ]);
                $newPublicacion->save();
                foreach ($colaboradores as $colaborador){
                    $integracionColab = self::getValidIntegracionColab($colaborador['carnet']);
                    $newColaborador = self::getValidColaborador($integracionColab, $colaborador['nombre']);
                    $tipoColaborador = self::getValidTipoColabId($colaborador['role']);
                    $newRelationColPub = new rel_col_pub_colaborador_publicacionModel([
                        'id_pub' => $newPublicacion->id_pub,
                        'id_pub_col' => $newColaborador->id_pub_col,
                        'id_cat_tpo_col_pub' => $tipoColaborador
                    ]);
                    $newRelationColPub->save();
                }

                foreach ($autores as $autor){
                    $integracionAut =  self::getValidIntegracionAut($autor['carnet']);
                    $newAutor = new pub_aut_publicacion_autorModel([
                        'id_pub' => $newPublicacion->id_pub,
                        'id_gen_int' => empty($integracionAut) ? null : $integracionAut->id_gen_int,
                        'nombres_pub_aut' => $autor['nombre'],
                        'apellidos_pub_aut' => ""
                    ]);
                    $newAutor->save();
                }
                $msg .= ' código generado: '.$codigoPub;
            });
        }catch(\Exception $e){
            $success = false;
            $msg = $e->getCode() == 9 ? $e->getMessage() : 'Ocurrió un error de escritura al ingresar los datos.'.$e->getMessage();
        }
        return array($success,$msg);
    }

    private static function formatId($idStr){
        $texto = trim(strval($idStr));
        $largo = strlen($texto);
        if ($largo == 6) {
            $anio = substr($texto,0,4);
            $correlativo = substr($texto,4,2);
            $validId = $correlativo.'-'.$anio;
        }else{
            throw new \Exception("Formato del identificador del grupo no es válido.:",9);
        }
        return $validId;
    }
    private static function getValidIntegracionPub($identifier){
	    $integracion = array();
        $grupo = pdg_gru_grupoModel::where('numero_pdg_gru','=',$identifier)->first();
        if(!empty($grupo)){
            $integracion = gen_int_integracionModel::where("llave_gen_int","=",$grupo->id_pdg_gru)->where('id_gen_tpo_int','=',1)->first();
            if(empty($integracion)){
                $integracion = new gen_int_integracionModel();
                $integracion->llave_gen_int = $grupo->id_pdg_gru;
                $integracion->id_gen_tpo_int = 1;
                $integracion->save();
            }else{
                $existingPub = pub_publicacionModel::where("id_gen_int","=",$identifier)->first();
                if(empty($existingPub))
                    throw new \Exception("Grupo ya posee Publicación de Trabajo de Graduación",9);
            }
        }
        return $integracion;
    }

    private static function getCodigoPubAvailable($identifier){
	    try{
            $texto = strval($identifier);
            $anio = substr($texto,3,4);
            $correlativo = pub_publicacionModel::where("anio_pub","=",$anio)->max('correlativo_pub');
            if($correlativo==null)
                $correlativo = 1;
            $correlativo++;
            if($correlativo>=1&&$correlativo<=9)
                $correlativoStr = '0'.$correlativo;
            else
                $correlativoStr = strval($correlativo);
            $codPub = $anio.trim($correlativoStr);
        }catch (\Exception $e){
            throw new \Exception("El formato del código de grupo no es válido",9);
        }
        return $codPub;
    }

    private static function getValidIntegracionColab($identifier){
        $integracion = array();
        $docente = pdg_dcn_docenteModel::where('carnet_pdg_dcn','=',$identifier)->first();
        if(!empty($docente)){
            $integracion = gen_int_integracionModel::where("llave_gen_int","=",$docente->id_pdg_dcn)->where('id_gen_tpo_int','=',2)->first();
            if(empty($integracion)){
                $integracion = new gen_int_integracionModel();
                $integracion->llave_gen_int = $docente->id_pdg_dcn;
                $integracion->id_gen_tpo_int = 2;
                $integracion->save();
            }
        }
        return $integracion;
    }

    private static function getValidColaborador($integracion, $colaborador){
	    if(!empty($integracion))
	        $validColaborador = pub_col_colaboradorModel::where("id_gen_int","=",$integracion->id_gen_int)->first();
        if(empty($validColaborador)){
            $validColaborador = new pub_col_colaboradorModel([
                'id_gen_int' => empty($integracion) ? null : $integracion->id_gen_int,
                'nombres_pub_col' => $colaborador,
                'apellidos_pub_col' => ""
            ]);
            $validColaborador->save();
        }
        return $validColaborador;
    }

    private static function getValidTipoColabId($role){
	    $tipoColab = cat_tpo_col_pub_tipo_colaboradorModel::where('id_cat_tpo_col_pub','=',$role)->first();
	    if(empty($tipoColab))
            throw new \Exception("Rol para el jurado no es válido.",9);
	    return $role;
    }

    private static function getValidIntegracionAut($identifier){
        $integracion = array();
        $estudiante = gen_EstudianteModel::where('carnet_gen_est','=',$identifier)->first();
        if(!empty($estudiante)){
            $integracion = gen_int_integracionModel::where("llave_gen_int","=",$estudiante->id_gen_est)->where("id_gen_tpo_int","=",3)->first();
            if(empty($integracion)){
                $integracion = new gen_int_integracionModel();
                $integracion->llave_gen_int = $estudiante->id_gen_est;
                $integracion->id_gen_tpo_int = 3;
                $integracion->save();
            }
        }
        return $integracion;
    }

    public static function eliminarPublicacion($idpub){
        $archivo = pub_arc_publicacion_archivoModel::where('id_pub','=',$idpub)->get();
        if(!empty($name)){
            $name = $archivo[0]->ubicacion_pub_arc;
            if( Storage::disk('publicaciones')->exists($name))
                Storage::disk('publicaciones')->delete($name);
        }
        $exito = pub_publicacionModel::deletePublicacionAndRelations($idpub);
        return $exito;
    }
}
