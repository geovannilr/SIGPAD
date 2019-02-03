<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pdg_eta_eva_tra_etapa_trabajoModel extends Model
{
    protected $table='pdg_eta_eva_tra_etapa_trabajo';
    protected $primaryKey='id_pdg_eta_eva_tra';
    public $timestamps=false;
    protected $fillable=
        [
            'id_cat_eta_eva',
            'id_pdg_tra_gra',
            'id_tpo_doc',
            'fecha_inicio',
            'orden_eta_eva',
        ];
    public static function contarArchivos($idTraGra,$idEtapa){
        $cantidad = DB::select("
                    SELECT 
                        COUNT(*) AS cantidad
                    FROM 
                        rel_tpo_doc_eta_eva_tipo_documento_etapa_eva	rel_tpo_doc_eta_eva
                        INNER JOIN cat_tpo_doc_tipo_documento			tpo_doc				
                            ON (tpo_doc.id_cat_tpo_doc = rel_tpo_doc_eta_eva.id_cat_tpo_doc)
                        INNER JOIN pdg_doc_documento					doc
                            ON (doc.id_cat_tpo_doc = rel_tpo_doc_eta_eva.id_cat_tpo_doc)
                        INNER JOIN pdg_arc_doc_archivo_documento arc 
                            ON (arc.id_pdg_doc=doc.id_pdg_doc)
                        INNER JOIN pdg_tra_gra_trabajo_graduacion tragra
                            ON (tragra.id_pdg_gru = doc.id_pdg_gru)
                    WHERE
                        tragra.id_pdg_tra_gra = :idTraGra
                        AND	rel_tpo_doc_eta_eva.id_cat_eta_eva = :idEtapa",
            array(
                $idTraGra,$idEtapa
            )
        );
        return $cantidad[0]->cantidad;
    }

    public static function contarArchivosPorEtapa($idTraGra,$idEtapa){
        $query = "  select 
                        eta.id_tpo_doc, count(docs.id_pdg_doc) as total
                    from
                        pdg_eta_eva_tra_etapa_trabajo eta
                        inner join pdg_tra_gra_trabajo_graduacion tragra on tragra.id_pdg_tra_gra = eta.id_pdg_tra_gra
                        inner join cat_tpo_doc_tipo_documento tpd on eta.id_tpo_doc=tpd.id_cat_tpo_doc
                        left join pdg_doc_documento docs on docs.id_cat_tpo_doc=tpd.id_cat_tpo_doc 
                            and docs.id_pdg_gru = tragra.id_pdg_gru and docs.id_cat_eta_eva_pdg_doc = eta.id_cat_eta_eva
                    where
                        eta.id_cat_eta_eva = :idEtapa
                        and eta.id_pdg_tra_gra = :idTraGra
                    group by eta.id_tpo_doc";
        $archivos = DB::select($query,array($idEtapa,$idTraGra));
        return $archivos;
    }
}
