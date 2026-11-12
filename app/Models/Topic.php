<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'topic_number',
        'title',
        'required_hours',
    ];

    protected $casts = [
        'topic_number' => 'integer',
        'required_hours' => 'float',
    ];
}

