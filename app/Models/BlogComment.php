<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'blog_id',
        'parent_id',
        'name',
        'email',
        'comment',
        'is_approved',
        'ip_address',
    ];

    protected $casts = ['is_approved' => 'boolean'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')
            ->where('is_approved', true);
    }
}
