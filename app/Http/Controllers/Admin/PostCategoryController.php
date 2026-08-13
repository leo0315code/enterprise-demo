<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = Category::where('type', 'post')->ordered()->get();
        return view('admin.posts.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);
        $data['type'] = 'post';
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']) ?: 'cat-' . time();
        $data['is_active'] = $request->boolean('is_active', true);

        Category::create($data);
        return redirect()->route('admin.post-categories.index')->with('success', '分类已创建');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']) ?: $category->slug;
        $data['is_active'] = $request->boolean('is_active', true);

        $category->update($data);
        return redirect()->route('admin.post-categories.index')->with('success', '分类已更新');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.post-categories.index')->with('success', '分类已删除');
    }
}
