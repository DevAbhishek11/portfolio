<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category',
        'tags',
        'status',
        'is_featured',
        'view_count',
        'read_time',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'tags'         => 'array',
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
        'view_count'   => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class)
            ->where('is_approved', true)
            ->whereNull('parent_id');
    }

    public function pageViews()
    {
        return $this->morphMany(PageView::class, 'viewable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getReadTimeAttribute($value): string
    {
        return $value ? $value . ' min read' : '1 min read';
    }
}
