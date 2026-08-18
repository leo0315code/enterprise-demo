<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(): array
    {
        return [
            'name' => '张三',
            'email' => 'zhangsan@example.com',
            'phone' => '13800138000',
            'subject' => '产品咨询',
            'message' => '您好，想咨询贵司的产品。',
        ];
    }

    public function test_valid_message_is_stored_and_admin_notified(): void
    {
        Mail::fake();
        SiteSetting::set('contact_email', 'admin@example.com');

        $this->from('/contact')->post('/contact', $this->validPayload())
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', ['email' => 'zhangsan@example.com']);
        Mail::assertSent(ContactMessageReceived::class, fn ($mail) => $mail->hasTo('admin@example.com'));
    }

    public function test_honeypot_submission_is_silently_discarded(): void
    {
        $payload = $this->validPayload() + ['website' => 'http://spam.example.com'];

        $this->from('/contact')->post('/contact', $payload)
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_invalid_payload_fails_validation(): void
    {
        $payload = $this->validPayload();
        unset($payload['email']);

        $this->from('/contact')->post('/contact', $payload)
            ->assertRedirect('/contact')
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseCount('contact_messages', 0);
    }
}
