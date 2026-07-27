<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactQueries extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'ip_address',
        'user_agent',
    ];

    public function replies()
    {
        return $this->hasMany(ContactReplies::class, 'contact_query_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }
}
