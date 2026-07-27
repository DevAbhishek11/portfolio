<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'title',
        'phone',
        'location',
        'website',
        'github_url',
        'linkedin_url',
        'twitter_url',
        'upwork_url',
        'resume_url',
        'resume',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_method',
        'two_factor_recovery_codes',
        'two_factor_verified_at',
        'is_admin',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];

    protected $casts = [
        'email_verified_at'         => 'datetime',
        'two_factor_verified_at'    => 'datetime',
        'last_login_at'             => 'datetime',
        'two_factor_enabled'        => 'boolean',
        'two_factor_recovery_codes' => 'array',
        'is_admin'                  => 'boolean',
        'password'                  => 'hashed',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function twoFactorTokens()
    {
        return $this->hasMany(TwoFactorToken::class);
    }

    public function loginAttempts()
    {
        return $this->hasMany(LoginAttempt::class, 'email', 'email');
    }
}
