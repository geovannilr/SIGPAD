<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rel_ski_dcn_skill_docenteModel extends Model{
    protected $table='rel_ski_dcn_skill_docente';
    protected $primaryKey='id_rel_ski_dcn';
    public $timestamps=false;
    protected $fillable=
        [
            'nivel_ski_dcn',
            'id_pdg_dcn',
            'id_cat_ski'
        ];
}
