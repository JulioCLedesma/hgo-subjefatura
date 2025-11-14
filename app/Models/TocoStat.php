<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TocoStat extends Model
{
    protected $fillable = [
        'daily_shift_id',
        'partos',
        'cesareas',
        'rn_vivos',
        'piel_a_piel',
        'obitos',
        'legrados',
        'otb',
        'rev_cavidad',
        'histerectomia',
        'plastias',
        'analgesias',
        'emergencia_obstetrica',
        'consulta',
    ];
}
