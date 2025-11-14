<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutpatientStat extends Model
{
    protected $fillable = [
        'daily_shift_id',

        'cat_medial',
        'cat_picc',
        'cat_umbilical',
        'cat_asepsia',
        'cat_periferico_corto',
        'cat_cvc',

        'her_curaciones',
        'her_interconsultas',
        'her_valoraciones',
        'her_cuidados_especiales',
        'her_vac',

        'lac_asesorias',
        'lac_autoclaves',
        'lac_fracciones',

        'end_endoscopias',
        'end_colonoscopias',
        'end_biopsias',
        'end_cepres',
    ];
}
