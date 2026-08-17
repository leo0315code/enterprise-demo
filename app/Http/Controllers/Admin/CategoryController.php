<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = Category::where('type', 'product')->ordered()->get();

        return inertia('Categories', [
            'categories' => $categories,
        ]);
    }

    /**
     * 仅返回表格局部，供弹窗保存后无刷新刷新列表（Blade 端遗留兼容）。
     */
    public function rows()
    {
        $categories = Category::where('type', 'product')->ordered()->get();
        return view('admin.categories._table', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'sort' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
        $data['type'] = 'product';
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']) ?: 'cat-' . time();
        $data['is_active'] = $request->boolean('is_active', true);

        Category::create($data);

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->route('admin.categories.index')->with('success', '分类已创建');
    }

    public function edit(Category $category)
    {
        if (request()->ajax()) {
            return view('admin.categories._fields', compact('category'));
        }
        return redirect()->route('admin.categories.index');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'sort' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']) ?: $category->slug;
        $data['is_active'] = $request->boolean('is_active', true);

        $category->update($data);

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->route('admin.categories.index')->with('success', '分类已更新');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        if (request()->ajax() || request()->inertia()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->route('admin.categories.index')->with('success', '分类已删除');
    }
}
