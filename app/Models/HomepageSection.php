<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    /**
     * 获取前台首页展示的板块
     */
    public static function getForHomepage(): \Illuminate\Support\Collection
    {
        return static::active()->ordered()->get();
    }
}
