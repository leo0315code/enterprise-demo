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

    /**
     * 排序基准时间：未设发布时间时回退到创建时间
     */
    protected function sortTimestamp(): \Illuminate\Support\Carbon
    {
        return $this->published_at ?? $this->created_at;
    }

    /**
     * 上一篇（列表排序口径：发布时间倒序、同时间按 id 倒序）
     */
    public function previousOf(): ?self
    {
        $ts = $this->sortTimestamp();

        return static::published()
            ->where(function ($q) use ($ts) {
                $q->whereRaw('COALESCE(published_at, created_at) < ?', [$ts])
                    ->orWhere(function ($q2) use ($ts) {
                        $q2->whereRaw('COALESCE(published_at, created_at) = ?', [$ts])
                            ->where('id', '<', $this->id);
                    });
            })
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * 下一篇
     */
    public function nextOf(): ?self
    {
        $ts = $this->sortTimestamp();

        return static::published()
            ->where(function ($q) use ($ts) {
                $q->whereRaw('COALESCE(published_at, created_at) > ?', [$ts])
                    ->orWhere(function ($q2) use ($ts) {
                        $q2->whereRaw('COALESCE(published_at, created_at) = ?', [$ts])
                            ->where('id', '>', $this->id);
                    });
            })
            ->orderByRaw('COALESCE(published_at, created_at)')
            ->orderBy('id')
            ->first();
    }

    /**
     * 相关文章：优先同类，不足时用最新文章补齐
     */
    public function relatedTo(int $limit = 3): \Illuminate\Support\Collection
    {
        $related = $this->category_id
            ? static::published()
                ->where('category_id', $this->category_id)
                ->where('id', '!=', $this->id)
                ->latestPublished()->take($limit)->get()
            : collect();

        if ($related->count() < $limit) {
            $related = $related->concat(
                static::published()
                    ->where('id', '!=', $this->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->latestPublished()->take($limit - $related->count())->get()
            );
        }

        return $related;
    }
}
