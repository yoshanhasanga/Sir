<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayOff extends Model
{
    protected $table = 'days_off';

    protected $fillable = [
        'week',
        'day',
        'is_off',
    ];

    protected $casts = [
        'week' => 'integer',
        'day' => 'integer',
        'is_off' => 'boolean',
    ];
}

