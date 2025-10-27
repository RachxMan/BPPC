<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KelolaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); // matikan semua middleware supaya bisa akses route
    }

    /** @test */
    public function it_returns_kelola_index_view()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('kelola.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kelola.index');
        $response->assertViewHas('users');
    }

    /** @test */
    public function it_returns_kelola_create_view()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('kelola.create'));

        $response->assertStatus(200);
        $response->assertViewIs('kelola.create');
    }

    /** @test */
    public function it_can_store_new_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $data = [
            'nama_lengkap' => 'User Baru',
            'username' => 'userbaru',
            'email' => 'userbaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Administrator',
        ];

        $response = $this->post(route('kelola.store'), $data);

        $response->assertRedirect(route('kelola.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'userbaru',
            'email' => 'userbaru@example.com',
            'role' => 'admin',
        ]);
    }}