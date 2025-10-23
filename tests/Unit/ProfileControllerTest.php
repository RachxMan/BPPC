<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class ProfileControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_can_render_profile_view()
    {
        // Uji apakah halaman profil bisa diakses
        $response = $this->get('/profil');

        $response->assertStatus(200);
        $response->assertViewIs('profil_pengaturan');
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_can_update_profile_and_redirect_back()
    {
        // Uji apakah form update profil bisa dijalankan dan redirect kembali
        $response = $this->post('/profil/update', [
            'first_name' => 'Aya',
            'last_name' => 'Test',
            'email' => 'aya@example.com',
            'mobile' => '08123456789',
            'gender' => 'Perempuan',
            'address' => 'Pekanbaru',
        ]);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui!');
    }
}
