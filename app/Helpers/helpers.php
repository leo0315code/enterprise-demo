<?php

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

if (!function_exists('setting')) {
    /**
     * 获取站点配置值
     *
     * @param string $key 配置键
     * @param mixed $default 默认值
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }
}

if (!function_exists('thumb_url')) {
    /**
     * 获取图片的列表缩略图 URL（上传时生成的 uuid_thumb.ext）。
     * 缩略图不存在（旧数据）、gif、外链或非本地 public 磁盘时回退原图。
     */
    function thumb_url(?string $url): ?string
    {
        if (! $url) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (! $path || ! preg_match('/\.(jpe?g|png|webp)$/i', $path)) {
            return $url;
        }

        // OSS 等外部磁盘无法廉价校验缩略图是否存在，直接用原图
        if (config('filesystems.upload_disk', 'public') !== 'public') {
            return $url;
        }

        // 外部域名的图片（非本站上传）回退原图
        $relative = ltrim(str_replace('/storage/', '', $path), '/');
        if (! str_starts_with($relative, 'uploads/') || ! Storage::disk('public')->exists($relative)) {
            return $url;
        }

        $thumbRelative = preg_replace('/\.(jpe?g|png|webp)$/i', '_thumb.$1', $relative);
        if (! Storage::disk('public')->exists($thumbRelative)) {
            return $url;
        }

        $thumbPath = preg_replace('/\.(jpe?g|png|webp)$/i', '_thumb.$1', $path);
        return str_replace($path, $thumbPath, $url);
    }
}
