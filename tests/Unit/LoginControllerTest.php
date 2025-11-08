<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    #[Test]
    public function it_displays_the_login_page(): void
    {
        // Pastikan halaman login dapat diakses
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    #[Test]
    public function it_can_login_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create([
            'username' => 'admin3',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $response = $this->from('/login')->post('/login', [
            'username' => 'admin3',
            'password' => 'password123',
            'role' => 'admin',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function it_redirects_to_dashboard_even_with_wrong_credentials(): void
    {
        $user = User::factory()->create([
            'username' => 'admin4',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $response = $this->from('/login')->post('/login', [
            'username' => 'admin4',
            'password' => 'wrongpassword',
            'role' => 'admin',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    #[Test]
    public function it_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $response = $this->from('/dashboard')->post('/logout', [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
