<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;

/**
 * 动态生成 sitemap.xml（仅收录前台可见内容）
 */
class SitemapController extends Controller
{
    public function index()
    {
        $urls = collect();

        // 静态页面
        foreach ([
            ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('products.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('posts.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ] as $item) {
            $urls->push($item);
        }

        // 启用的单页（about/contact 已在上方静态收录）
        foreach (Page::where('is_active', true)->whereNotIn('slug', ['about', 'contact'])->get() as $page) {
            $urls->push([
                'loc' => route('page.show', $page->slug),
                'lastmod' => $page->updated_at?->toAtomString(),
                'priority' => '0.6',
                'changefreq' => 'monthly',
            ]);
        }

        // 上架产品
        foreach (Product::active()->get() as $product) {
            $urls->push([
                'loc' => route('products.show', $product->slug),
                'lastmod' => $product->updated_at?->toAtomString(),
                'priority' => '0.8',
                'changefreq' => 'weekly',
            ]);
        }

        // 已发布文章
        foreach (Post::published()->get() as $post) {
            $urls->push([
                'loc' => route('posts.show', $post->slug),
                'lastmod' => ($post->published_at ?? $post->updated_at)?->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ]);
        }

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
