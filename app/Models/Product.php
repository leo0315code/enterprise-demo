<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'thumbnail', 'summary', 'content',
        'status', 'sort', 'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id', 'desc');
    }

    /**
     * 相关产品：优先同类，不足时用其他上架产品补齐
     */
    public function relatedTo(int $limit = 3): \Illuminate\Support\Collection
    {
        $related = $this->category_id
            ? static::active()
                ->where('category_id', $this->category_id)
                ->where('id', '!=', $this->id)
                ->ordered()->take($limit)->get()
            : collect();

        if ($related->count() < $limit) {
            $related = $related->concat(
                static::active()
                    ->where('id', '!=', $this->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->ordered()->take($limit - $related->count())->get()
            );
        }

        return $related;
    }
}
