<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * 列表缩略图生成（600px 宽，命名 uuid_thumb.ext，供 thumb_url() 推导）。
 * 上传接口与回填命令 media:generate-thumbs 共用。
 */
class Thumbnail
{
    /**
     * 为指定磁盘路径生成缩略图。
     * 非 jpg/png/webp、缩略图已存在（未指定 force）时跳过。
     *
     * @return bool 是否实际生成了缩略图
     */
    public static function generate(string $disk, string $path, bool $force = false): bool
    {
        if (! preg_match('/\.(jpe?g|png|webp)$/i', $path, $m)) {
            return false;
        }

        $storage = Storage::disk($disk);
        $thumbPath = static::thumbPath($path);
        if (! $force && $storage->exists($thumbPath)) {
            return false;
        }

        $image = (new ImageManager(new Driver()))->decodeBinary($storage->get($path));
        $image->scaleDown(width: 600);

        // png 编码器无 quality 参数，单独处理
        $encoded = strtolower($m[1]) === 'png'
            ? $image->encodeUsingPath($thumbPath)
            : $image->encodeUsingPath($thumbPath, quality: 82);

        return $storage->put($thumbPath, (string) $encoded);
    }

    /**
     * 缩略图存储路径：uuid.ext → uuid_thumb.ext
     */
    public static function thumbPath(string $path): string
    {
        return preg_replace('/\.(jpe?g|png|webp)$/i', '_thumb.$1', $path);
    }

    /**
     * 将前台图片 URL 换算为本地 public 磁盘路径。
     * 外链、gif、非本站 uploads/ 目录下的图片返回 null。
     */
    public static function pathFromUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (! $path || ! preg_match('/\.(jpe?g|png|webp)$/i', $path)) {
            return null;
        }

        $relative = ltrim(str_replace('/storage/', '', $path), '/');
        if (! str_starts_with($relative, 'uploads/') || ! Storage::disk('public')->exists($relative)) {
            return null;
        }

        return $relative;
    }
}
