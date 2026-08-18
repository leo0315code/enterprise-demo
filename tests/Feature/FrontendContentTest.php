<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_post_is_visible(): void
    {
        Post::create([
            'title' => '测试文章', 'slug' => 'hello-post', 'content' => '<p>内容</p>',
            'is_active' => true, 'published_at' => now()->subDay(),
        ]);

        $this->get('/news/hello-post')->assertOk()->assertSee('测试文章');
    }

    public function test_inactive_post_returns_404(): void
    {
        Post::create([
            'title' => '已下架文章', 'slug' => 'hidden-post', 'content' => '<p>内容</p>',
            'is_active' => false, 'published_at' => now()->subDay(),
        ]);

        $this->get('/news/hidden-post')->assertNotFound();
    }

    public function test_future_post_returns_404(): void
    {
        Post::create([
            'title' => '定时文章', 'slug' => 'future-post', 'content' => '<p>内容</p>',
            'is_active' => true, 'published_at' => now()->addDay(),
        ]);

        $this->get('/news/future-post')->assertNotFound();
    }

    public function test_inactive_product_returns_404(): void
    {
        Product::create([
            'title' => '已下架产品', 'slug' => 'hidden-product',
            'content' => '<p>内容</p>', 'status' => 'inactive',
        ]);

        $this->get('/products/hidden-product')->assertNotFound();
    }

    public function test_active_product_is_visible(): void
    {
        Product::create([
            'title' => '在售产品', 'slug' => 'active-product',
            'content' => '<p>内容</p>', 'status' => 'active',
        ]);

        $this->get('/products/active-product')->assertOk()->assertSee('在售产品');
    }

    public function test_sitemap_only_contains_visible_content(): void
    {
        Post::create([
            'title' => '可见文章', 'slug' => 'visible-post', 'content' => '<p>内容</p>',
            'is_active' => true, 'published_at' => now()->subDay(),
        ]);
        Post::create([
            'title' => '不可见文章', 'slug' => 'invisible-post', 'content' => '<p>内容</p>',
            'is_active' => false, 'published_at' => now()->subDay(),
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('visible-post', false)
            ->assertDontSee('invisible-post', false);
    }

    public function test_homepage_renders(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_not_found_uses_custom_error_page(): void
    {
        $this->get('/news/no-such-post')
            ->assertNotFound()
            ->assertSee('页面走丢了');
    }

    public function test_search_filters_posts_by_keyword(): void
    {
        Post::create([
            'title' => '数字化转型实践', 'slug' => 'post-a', 'content' => '<p>a</p>',
            'is_active' => true, 'published_at' => now()->subDay(),
        ]);
        Post::create([
            'title' => '团队建设活动', 'slug' => 'post-b', 'content' => '<p>b</p>',
            'is_active' => true, 'published_at' => now()->subDay(),
        ]);

        $this->get('/news?'.http_build_query(['q' => '数字化']))
            ->assertOk()
            ->assertSee('数字化转型实践')
            ->assertDontSee('团队建设活动');
    }

    public function test_post_detail_shows_previous_and_next_links(): void
    {
        foreach ([['older', 3], ['middle', 2], ['newer', 1]] as [$slug, $daysAgo]) {
            Post::create([
                'title' => "文章 {$slug}", 'slug' => $slug, 'content' => '<p>x</p>',
                'is_active' => true, 'published_at' => now()->subDays($daysAgo),
            ]);
        }

        $this->get('/news/middle')
            ->assertOk()
            ->assertSee('文章 older')   // 上一篇（更早）
            ->assertSee('文章 newer');  // 下一篇（更新）
    }
}
