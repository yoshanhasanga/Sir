<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeSetting extends Model
{
    protected $fillable = [
        'start_date',
        'weeks',
        'include_saturday',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'weeks' => 'integer',
        'include_saturday' => 'boolean',
    ];
}

