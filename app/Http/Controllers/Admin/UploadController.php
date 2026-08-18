<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * 富文本编辑器（wangEditor）专用本地图片上传。
 * 受后台 auth 中间件保护（定义在 routes/admin.php 的受保护 group 内）。
 * 返回 wangEditor v5 约定的 JSON 结构：{ errno: 0, data: { url } }
 */
class UploadController extends Controller
{
    public function image(Request $request)
    {
        $file = $request->file('wangeditor-uploaded-image')
            ?? $request->file('file')
            ?? $request->file('image');

        if (! $file) {
            return response()->json(['errno' => 1, 'message' => '未收到上传文件'], 422);
        }

        $request->validate([
            'wangeditor-uploaded-image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:10240'],
            'file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:10240'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:10240'],
        ]);

        // 上传磁盘由 config/filesystems.upload_disk 控制，默认 public（本地），
        // 改为 oss 即切换阿里云 OSS，仅需修改 .env 的 UPLOAD_DISK 与 OSS_* 配置。
        $disk = config('filesystems.upload_disk', 'public');

        // 扩展名基于 MIME 推断（extension()），不信任客户端传来的原始扩展名
        $name = 'uploads/' . date('Ym') . '/' . Str::uuid()->toString() . '.' . ($file->extension() ?: 'png');

        $path = $file->storeAs('', $name, $disk);
        $this->makeThumbnail($disk, $path);
        $url = Storage::disk($disk)->url($path);

        return response()->json([
            'errno' => 0,
            'data' => ['url' => $url],
        ]);
    }

    /**
     * 生成列表用缩略图（宽 600px，命名 uuid_thumb.ext，供 thumb_url() 推导）。
     * 失败不影响原图上传；gif 不处理（可能是动图）。
     */
    protected function makeThumbnail(string $disk, string $path): void
    {
        if (! preg_match('/\.(jpe?g|png|webp)$/i', $path, $m)) {
            return;
        }

        try {
            $storage = Storage::disk($disk);
            $image = (new ImageManager(new Driver()))->decodeBinary($storage->get($path));
            $image->scaleDown(width: 600);

            $thumbPath = preg_replace('/\.(jpe?g|png|webp)$/i', '_thumb.$1', $path);
            // png 编码器无 quality 参数，单独处理
            $encoded = strtolower($m[1]) === 'png'
                ? $image->encodeUsingPath($thumbPath)
                : $image->encodeUsingPath($thumbPath, quality: 82);
            $storage->put($thumbPath, (string) $encoded);
        } catch (\Throwable $e) {
            Log::warning('缩略图生成失败: '.$e->getMessage());
        }
    }
}
