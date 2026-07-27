<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactorToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'type',
        'used',
        'expires_at',
    ];

    protected $casts = [
        'used'       => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return ! $this->used && ! $this->isExpired();
    }
}
