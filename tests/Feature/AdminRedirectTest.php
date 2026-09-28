<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_shows_login_page_for_guest(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_authenticated_user_visiting_admin_login_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/login')
            ->assertRedirect('/admin/dashboard');
    }

    public function test_authenticated_user_visiting_admin_root_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertRedirect('/admin/dashboard');
    }

    public function test_guest_is_redirected_to_login_when_visiting_admin_root(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }
}
