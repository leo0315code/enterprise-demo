<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThumbUrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_returns_thumb_when_exists(): void
    {
        Storage::disk('public')->put('uploads/202608/a.jpg', 'fake');
        Storage::disk('public')->put('uploads/202608/a_thumb.jpg', 'fake');

        $this->assertSame(
            '/storage/uploads/202608/a_thumb.jpg',
            thumb_url('/storage/uploads/202608/a.jpg')
        );
    }

    public function test_falls_back_to_original_when_thumb_missing(): void
    {
        Storage::disk('public')->put('uploads/202608/b.png', 'fake');

        $this->assertSame(
            '/storage/uploads/202608/b.png',
            thumb_url('/storage/uploads/202608/b.png')
        );
    }

    public function test_gif_and_external_urls_are_unchanged(): void
    {
        Storage::disk('public')->put('uploads/202608/c.gif', 'fake');

        $this->assertSame('/storage/uploads/202608/c.gif', thumb_url('/storage/uploads/202608/c.gif'));
        $this->assertSame('https://cdn.example.com/x.jpg', thumb_url('https://cdn.example.com/x.jpg'));
    }

    public function test_null_input_returns_null(): void
    {
        $this->assertNull(thumb_url(null));
    }
}
