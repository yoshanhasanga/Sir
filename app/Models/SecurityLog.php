<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends Model
{
    protected $fillable = [
        'user_id',
        'event',
        'ip_address',
        'user_agent',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function logEvent(?int $userId, string $event, ?string $ip, ?string $userAgent, array $details = []): self
    {
        return self::create([
            'user_id' => $userId,
            'event' => $event,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'details' => $details,
        ]);
    }
}

