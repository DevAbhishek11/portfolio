<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactReplies extends Model
{
    protected $fillable = [
        'contact_query_id',
        'user_id',
        'subject',
        'message',
        'sent_at',
    ];

    protected $casts = ['sent_at' => 'datetime'];

    public function contactQuery()
    {
        return $this->belongsTo(ContactQueries::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
