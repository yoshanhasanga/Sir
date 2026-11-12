<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopicProgress extends Model
{
    protected $table = 'topic_progress';

    protected $fillable = [
        'centre_id',
        'topic_id',
        'manual_hours',
        'remarks',
    ];

    protected $casts = [
        'manual_hours' => 'float',
    ];

    public function centre(): BelongsTo
    {
        return $this->belongsTo(TrainingCentre::class, 'centre_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}

