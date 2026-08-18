<?php

namespace App\Http\Controllers;

use App\Models\Post;

/**
 * 新闻 RSS 2.0 订阅源（仅收录前台可见文章）
 */
class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::published()->latestPublished()->take(20)->get();

        return response()
            ->view('feed', compact('posts'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
