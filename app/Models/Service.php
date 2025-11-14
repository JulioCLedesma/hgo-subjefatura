<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'installed_capacity',
        'area',
        'is_active',
    ];
}
