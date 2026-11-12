<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'designation',
        'phone',
        'can_view',
        'can_edit',
        'can_delete',
        'can_approve',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'can_view' => 'boolean',
            'can_edit' => 'boolean',
            'can_delete' => 'boolean',
            'can_approve' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function approvalRequests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class, 'requester_id');
    }

    public function approvedDecisions(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class, 'approved_by');
    }

    public function securityLogs(): HasMany
    {
        return $this->hasMany(SecurityLog::class, 'user_id');
    }

    public function isCenterManager(): bool
    {
        return $this->role === 'center_manager';
    }

    public function isTopicLeader(): bool
    {
        return $this->role === 'topic_leader';
    }

    public function isMoeApprover(): bool
    {
        return $this->role === 'moe_approver' || $this->can_approve;
    }

    public function isMoeAdmin(): bool
    {
        return $this->role === 'moe_admin';
    }

    public function isReadOnly(): bool
    {
        return !$this->can_edit && !$this->can_approve;
    }
}
