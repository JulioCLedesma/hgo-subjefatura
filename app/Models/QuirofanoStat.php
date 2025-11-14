<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuirofanoStat extends Model
{
    protected $fillable = [
        'daily_shift_id',
        'programadas',
        'realizadas',
        'suspendidas',
        'urgencias',
        'pendientes',
        'contaminadas',
        'salas_trabajando',
    ];
}