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

        $page = Page::where('slug', $slug)->firstOrFail();

        // 联系我们页使用带表单的视图
        if ($slug === 'contact') {
            return view('contact.index', compact('page'));
        }

        return view('pages.show', compact('page'));
    }
}
