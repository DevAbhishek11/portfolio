<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'page',
        'url',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'device_type',
        'browser',
        'session_id',
        'viewable_type',
        'viewable_id',
    ];

    public function viewable()
    {
        return $this->morphTo();
    }
}
