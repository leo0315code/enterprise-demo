<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminUploadTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ]);
    }

    public function test_upload_generates_original_and_thumbnail(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->postJson('/manage/upload', [
            'wangeditor-uploaded-image' => UploadedFile::fake()->image('photo.jpg', 1200, 800),
        ]);

        $response->assertOk()->assertJson(['errno' => 0]);

        $files = Storage::disk('public')->allFiles();
        $this->assertCount(2, $files);

        $thumbs = array_filter($files, fn ($f) => str_contains($f, '_thumb.'));
        $this->assertCount(1, $thumbs);
    }

    public function test_upload_rejects_non_image_file(): void
    {
        Storage::fake('public');

        // wangEditor 实际以 XHR 提交，校验失败返回 422 JSON
        $response = $this->actingAs($this->admin())->postJson('/manage/upload', [
            'wangeditor-uploaded-image' => UploadedFile::fake()->create('evil.php', 10, 'application/x-php'),
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, Storage::disk('public')->allFiles());
    }

    public function test_guest_cannot_upload(): void
    {
        $this->post('/manage/upload', [
            'wangeditor-uploaded-image' => UploadedFile::fake()->image('photo.jpg'),
        ])->assertRedirect(route('admin.login'));
    }
}
