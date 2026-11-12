<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimetableSlot extends Model
{
    protected $fillable = [
        'centre_id',
        'week',
        'day',
        'slot_index',
        'topic_id',
        'is_other_topic',
        'lecturer_id',
        'unit_note',
        'is_held',
    ];

    protected $casts = [
        'week' => 'integer',
        'day' => 'integer',
        'slot_index' => 'integer',
        'is_other_topic' => 'boolean',
        'is_held' => 'boolean',
    ];

    public function centre(): BelongsTo
    {
        return $this->belongsTo(TrainingCentre::class, 'centre_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id');
    }
}

