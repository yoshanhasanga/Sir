<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopicAssignment extends Model
{
    protected $fillable = [
        'centre_id',
        'topic_id',
        'lecturer_id',
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

