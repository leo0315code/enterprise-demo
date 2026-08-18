<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        return User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ]);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $this->get('/manage')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $this->createUser();

        $this->post('/manage/login', [
            'login' => 'admin',
            'password' => 'secret-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $this->createUser();

        $this->from('/manage/login')->post('/manage/login', [
            'login' => 'admin',
            'password' => 'wrong-password',
        ])->assertRedirect('/manage/login')->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $this->actingAs($this->createUser())
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }
}
