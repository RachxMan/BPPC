<?php

namespace Tests\Unit;

use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    /** @test */
    public function it_can_render_profile_view()
    {
        $response = $this->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profil_pengaturan');
    }

    /** @test */
    public function it_can_update_profile_and_redirect_back()
    {
        $response = $this->post('/profile/update', [
            'first_name' => 'Aya',
            'last_name' => 'Test',
            'email' => 'aya@example.com',
            'mobile' => '08123456789',
            'gender' => 'Perempuan',
            'address' => 'Pekanbaru'
        ]);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui!');
    }
}
