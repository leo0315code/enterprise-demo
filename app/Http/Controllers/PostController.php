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

        $categories = Category::active()->where('type', 'post')->ordered()->get();

        $posts = Post::active()->with('category')->latest();
        if ($categorySlug) {
            $cat = Category::where('slug', $categorySlug)->first();
            if ($cat) {
                $posts->where('category_id', $cat->id);
            }
        }
        $posts = $posts->paginate(9);

        return view('posts.index', compact('posts', 'categories', 'categorySlug'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('posts.show', compact('post'));
    }
}
