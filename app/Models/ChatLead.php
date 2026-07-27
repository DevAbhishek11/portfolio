<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatLead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'conversation_snippet',
        'ip_address',
        'emailed',
    ];

    protected $casts = [
        'emailed' => 'boolean',
    ];
}
