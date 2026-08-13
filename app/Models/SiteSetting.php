<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'label', 'type', 'description', 'sort'];

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }

    /**
     * 获取指定 key 的配置值
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::getAll()->get($key, $default);
    }

    /**
     * 获取所有配置（带缓存）— 缓存数组避免序列化问题
     */
    public static function getAll(): \Illuminate\Support\Collection
    {
        return Cache::rememberForever('site_settings', function () {
            return static::query()->pluck('value', 'key');
        });
    }

    /**
     * 按分组获取配置
     */
    public static function getByGroup(string $group): \Illuminate\Support\Collection
    {
        return static::query()
            ->where('group', $group)
            ->orderBy('sort')
            ->get();
    }

    /**
     * 设置配置值
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function clearCache(): void
    {
        Cache::forget('site_settings');
    }
}
