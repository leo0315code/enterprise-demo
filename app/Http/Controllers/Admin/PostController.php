<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Post::with('category')->latest();
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        $posts = $query->paginate(15)->withQueryString();
        $categories = Category::active()->where('type', 'post')->ordered()->get();
        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->where('type', 'post')->ordered()->get();
        return view('admin.posts.form', ['post' => null, 'categories' => $categories]);
    }

    /**
     * 仅返回表格局部，供弹窗保存后无刷新刷新列表。
     */
    public function rows()
    {
        $posts = Post::with('category')->latest()->paginate(15)->withQueryString();
        $categories = Category::active()->where('type', 'post')->ordered()->get();
        return view('admin.posts._table', compact('posts', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:posts'],
            'cover' => ['nullable', 'string', 'max:500'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']) ?: 'post-' . time();
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['published_at'] = $data['published_at'] ?? now();

        Post::create($data);

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.posts.index')->with('success', '文章已发布');
    }

    public function edit(Post $post)
    {
        $categories = Category::active()->where('type', 'post')->ordered()->get();
        if (request()->ajax()) {
            return view('admin.posts._fields', compact('post', 'categories'));
        }
        return view('admin.posts.form', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:posts,slug,' . $post->id],
            'cover' => ['nullable', 'string', 'max:500'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']) ?: $post->slug;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);

        $post->update($data);

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.posts.index')->with('success', '文章已更新');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', '文章已删除');
    }
}
