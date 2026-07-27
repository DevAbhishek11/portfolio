<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Rendered on every frontend page (chat widget visibility) — must
     * degrade to $default rather than throw if the DB is unreachable,
     * so a database hiccup doesn't take the whole site down with it.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            return Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
                $row = static::where('key', $key)->first();
                return $row ? $row->value : $default;
            });
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting:{$key}");
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = static::get($key, $default ? '1' : '0');
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
