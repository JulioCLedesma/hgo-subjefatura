<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoclaveStat extends Model
{
    protected $fillable = [
        'daily_shift_id',
        'ceye',
        'subceye',
    ];
}