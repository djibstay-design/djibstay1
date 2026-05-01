<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        if (! Schema::hasTable('site_settings')) {
            return $default;
        }

        return Cache::remember("site_setting.{$key}", 3600, function () use ($key, $default) {
            return static::query()->where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrInsert(
            ['key' => $key],
            ['value' => $value]
        );
        Cache::forget("site_setting.{$key}");
    }
}
