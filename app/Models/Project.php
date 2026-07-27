<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Project extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'short_description',
        'description',
        'thumbnail',
        'github_url',
        'live_url',
        'category',
        'status',
        'is_featured',
        'is_published',
        'start_date',
        'end_date',
        'sort_order',
        'view_count',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'is_published' => 'boolean',
        'start_date'   => 'date',
        'end_date'     => 'date',
        'view_count'   => 'integer',
        'sort_order'   => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function techStacks()
    {
        return $this->hasMany(ProjectTechStack::class);
    }

    public function pageViews()
    {
        return $this->morphMany(PageView::class, 'viewable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
            ->singleFile()
            ->useDisk('public');

        $this->addMediaCollection('images')
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)->height(300)
            ->format('webp')
            ->quality(80)
            ->nonQueued();

        $this->addMediaConversion('card')
            ->width(800)->height(500)
            ->format('webp')
            ->quality(82)
            ->nonQueued();

        $this->addMediaConversion('hero')
            ->width(1600)->height(900)
            ->format('webp')
            ->quality(85)
            ->nonQueued();
    }

    // Helper to get responsive image srcset
    public function getThumbnailSrcset(): string
    {
        $media = $this->getFirstMedia('thumbnail');
        if (!$media) return '';

        return implode(', ', [
            $media->getUrl('thumb') . ' 400w',
            $media->getUrl('card')  . ' 800w',
            $media->getUrl('hero')  . ' 1600w',
        ]);
    }
}
