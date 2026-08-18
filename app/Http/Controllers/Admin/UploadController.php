<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $url = Storage::disk($disk)->url($path);

        return response()->json([
            'errno' => 0,
            'data' => ['url' => $url],
        ]);
    }
}
