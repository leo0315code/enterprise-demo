<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HomepageSection extends Model
{
    protected $fillable = [
        'type', 'title', 'subtitle', 'content', 'image',
        'button_text', 'button_link', 'extra', 'sort', 'is_active',
    ];

    protected $casts = [
        'extra' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    /**
     * 获取前台首页展示的板块（带永久缓存，增删改时自动失效）
     */
    public static function getForHomepage(): \Illuminate\Support\Collection
    {
        return Cache::rememberForever('homepage_sections', function () {
            return static::active()->ordered()->get();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('homepage_sections');
    }
}
