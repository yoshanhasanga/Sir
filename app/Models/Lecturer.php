<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    protected $fillable = [
        'name',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(TopicAssignment::class, 'lecturer_id');
    }

    public function timetableSlots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class, 'lecturer_id');
    }
}

