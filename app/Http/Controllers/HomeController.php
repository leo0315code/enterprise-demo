<?php

namespace App\Http\Controllers;

use App\Models\HomepageSection;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 首页板块（可后台配置）
        $sections = HomepageSection::getForHomepage();

        // 最新新闻（首页新闻板块使用）
        $latestPosts = Post::active()->latest()->take(3)->get();
        // 推荐产品
        $featuredProducts = Product::active()->take(3)->get();

        return view('home', compact('sections', 'latestPosts', 'featuredProducts'));
    }
}
