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
}
