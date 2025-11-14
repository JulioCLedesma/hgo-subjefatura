<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HumanResourceStat extends Model
{
    protected $fillable = [
        'daily_shift_id',

        // ASISTENCIA
        'subjefatura',
        'supervision',
        'jefes_servicio',
        'enfermeria_general',
        'enfermeria_auxiliar',
        'pasantes',

        // INCIDENCIAS
        'descansos_obligatorios',
        'incapacidades',
        'faltas',
        'vacaciones',
        'becas',
        'permisos_sindicales',
        'permiso_tiempo',
    ];
}
