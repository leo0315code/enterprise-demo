<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, $slug = null)
    {
        $slug = $slug ?? $request->route()->defaults['slug'] ?? null;

        if (!$slug) {
            abort(404);
        }

        // 仅展示启用中的页面（与产品/文章/板块的 active scope 保持一致）
        // 系统页 about/contact 为前端锚点，不受启用开关影响（与后台删除保护一致）
        $page = Page::where('slug', $slug)
            ->when(!in_array($slug, ['about', 'contact']), fn ($q) => $q->where('is_active', true))
            ->firstOrFail();

        // 联系我们页使用带表单的视图
        if ($slug === 'contact') {
            return view('contact.index', compact('page'));
        }

        return view('pages.show', compact('page'));
    }
}
