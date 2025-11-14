<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WardStat extends Model
{
        protected $fillable = [
        'daily_shift_id',
        'service_id',
        'pacientes',
        'caidas',
        'tiras',
        'graves',
        'tubos',
    ];
}
