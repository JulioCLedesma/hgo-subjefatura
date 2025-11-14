<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefuncionStat extends Model
{
    protected $fillable = [
        'daily_shift_id',
        'total_defunciones',
    ];
}
