<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $keyword = trim((string) $request->query('q', ''));

        $categories = Category::active()->where('type', 'post')->ordered()->get();

        $posts = Post::published()->with('category')->latestPublished();
        if ($categorySlug) {
            $cat = Category::where('slug', $categorySlug)->first();
            if ($cat) {
                $posts->where('category_id', $cat->id);
            }
        }
        if ($keyword !== '') {
            $posts->where(fn ($q) => $q
                ->where('title', 'like', "%{$keyword}%")
                ->orWhere('summary', 'like', "%{$keyword}%"));
        }
        $posts = $posts->paginate(9)->withQueryString();

        return view('posts.index', compact('posts', 'categories', 'categorySlug', 'keyword'));
    }

    public function show($slug)
    {
        // 仅展示启用且已到发布时间的文章（与列表页口径一致）
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        $prevPost = $post->previousOf();
        $nextPost = $post->nextOf();
        $relatedPosts = $post->relatedTo(3);

        return view('posts.show', compact('post', 'prevPost', 'nextPost', 'relatedPosts'));
    }
}
