<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pages = Page::ordered()->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.form', ['page' => null]);
    }

    /**
     * 仅返回表格局部，供弹窗保存后无刷新刷新列表。
     */
    public function rows()
    {
        $pages = Page::ordered()->get();
        return view('admin.pages._table', compact('pages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200', 'unique:pages'],
            'seo_title' => ['nullable', 'string', 'max:200'],
            'seo_description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort' => ['integer', 'min:0'],
        ]);

        Page::create($data);

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.pages.index')->with('success', '页面已创建');
    }

    public function edit(Page $page)
    {
        if (request()->ajax()) {
            return view('admin.pages._fields', compact('page'));
        }
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200', 'unique:pages,slug,' . $page->id],
            'seo_title' => ['nullable', 'string', 'max:200'],
            'seo_description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort' => ['integer', 'min:0'],
        ]);

        $page->update($data);

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.pages.index')->with('success', '页面已更新');
    }

    public function destroy(Page $page)
    {
        // 保护关键页面（about/contact）不被误删
        if (in_array($page->slug, ['about', 'contact'])) {
            return redirect()->route('admin.pages.index')->with('error', '系统页面不可删除');
        }
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', '页面已删除');
    }
}
