<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'cover', 'summary', 'content',
        'author', 'is_active', 'is_featured', 'sort', 'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 前台可见：启用且已到发布时间
     */
    public function scopePublished($query)
    {
        return $query->active()
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    /**
     * 按发布时间倒序（避免覆盖框架内置 latest()）
     */
    public function scopeLatestPublished($query)
    {
        return $query->orderBy('published_at', 'desc')->orderBy('id', 'desc');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('published_at', 'desc');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
