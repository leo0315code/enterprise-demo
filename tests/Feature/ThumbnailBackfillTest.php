<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThumbnailBackfillTest extends TestCase
{
    use RefreshDatabase;

    private function seedLocalImage(string $path): string
    {
        $binary = UploadedFile::fake()->image('source.jpg', 1200, 800)->get();
        Storage::disk('public')->put($path, $binary);

        return '/storage/'.$path;
    }

    public function test_backfill_generates_thumbs_for_legacy_content(): void
    {
        $disk = Storage::fake('public');

        $postUrl = $this->seedLocalImage('uploads/202608/post-cover.jpg');
        $productUrl = $this->seedLocalImage('uploads/202608/product-shot.png');

        Post::create([
            'title' => '旧文章', 'slug' => 'legacy-post', 'content' => '<p>x</p>',
            'cover' => $postUrl, 'is_active' => true, 'published_at' => now()->subDay(),
        ]);
        Product::create([
            'title' => '旧产品', 'slug' => 'legacy-product', 'content' => '<p>x</p>',
            'thumbnail' => $productUrl, 'status' => 'active',
        ]);

        $this->artisan('media:generate-thumbs')->assertSuccessful();

        $disk->assertExists('uploads/202608/post-cover_thumb.jpg');
        $disk->assertExists('uploads/202608/product-shot_thumb.png');
    }

    public function test_backfill_skips_external_and_gif_images(): void
    {
        Storage::fake('public');

        Post::create([
            'title' => '外链封面', 'slug' => 'external-post', 'content' => '<p>x</p>',
            'cover' => 'https://cdn.example.com/a.jpg', 'is_active' => true, 'published_at' => now()->subDay(),
        ]);
        Product::create([
            'title' => '动图产品', 'slug' => 'gif-product', 'content' => '<p>x</p>',
            'thumbnail' => '/storage/uploads/202608/anim.gif', 'status' => 'active',
        ]);

        $this->artisan('media:generate-thumbs')
            ->expectsOutputToContain('跳过 2')
            ->assertSuccessful();
    }

    public function test_backfill_does_not_regenerate_without_force(): void
    {
        Storage::fake('public');

        $url = $this->seedLocalImage('uploads/202608/once.jpg');
        Post::create([
            'title' => '文章', 'slug' => 'once-post', 'content' => '<p>x</p>',
            'cover' => $url, 'is_active' => true, 'published_at' => now()->subDay(),
        ]);

        $this->artisan('media:generate-thumbs')->assertSuccessful();
        // 第二次运行：缩略图已存在，应全部跳过
        $this->artisan('media:generate-thumbs')
            ->expectsOutputToContain('新生成 0')
            ->assertSuccessful();
    }
}
