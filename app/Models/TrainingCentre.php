<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingCentre extends Model
{
    protected $fillable = [
        'code',
        'name',
        'manager_name',
        'contact',
        'sort_order',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(TopicAssignment::class, 'centre_id');
    }

    public function timetableSlots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class, 'centre_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(TopicProgress::class, 'centre_id');
    }
}

