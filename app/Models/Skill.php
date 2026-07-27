<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'category',
        'level',
        'color',
        'icon',
        'sort_order',
        'is_featured',
    ];

    protected $casts = [
        'level'       => 'integer',
        'sort_order'  => 'integer',
        'is_featured' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public static function groupedByCategory(int $userId): array
    {
        return static::where('user_id', $userId)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category')
            ->toArray();
    }

    public static function radarData(int $userId): array
    {
        return static::where('user_id', $userId)
            ->featured()
            ->orderBy('sort_order')
            ->limit(8) // radar works best with 6-8 axes
            ->get(['name', 'level', 'color'])
            ->toArray();
    }
}
