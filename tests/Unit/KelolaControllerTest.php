<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KelolaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); // 🔥 Abaikan semua middleware selama testing
    }

    /** @test */
    public function it_returns_kelola_akun_view()
    {
        // Create a test user
        $user = \App\Models\User::factory()->create([
            'role' => 'admin',
            'profile_photo' => null,
            'nama_lengkap' => 'Test Admin'
        ]);

        // Act as the user
        $this->actingAs($user);

        $response = $this->get('/kelola-akun');

        $response->assertStatus(200);
        $response->assertViewIs('kelola.index');
    }
}
