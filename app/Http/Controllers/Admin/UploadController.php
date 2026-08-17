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
            'wangeditor-uploaded-image' => ['nullable', 'image', 'max:10240'],
            'file' => ['nullable', 'image', 'max:10240'],
            'image' => ['nullable', 'image', 'max:10240'],
        ]);

        $ext = $file->getClientOriginalExtension() ?: 'png';
        $name = date('Ym') . '/' . Str::uuid()->toString() . '.' . $ext;

        // 存入 storage/app/public/uploads，通过 public/storage 软链对外可访问
        $path = $file->storeAs('uploads', $name, 'public');
        $url = Storage::disk('public')->url($path);

        return response()->json([
            'errno' => 0,
            'data' => ['url' => $url],
        ]);
    }
}
