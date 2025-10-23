<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class KelolaAkunControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_returns_kelola_akun_view()
    {
        // Simulasi akses halaman /kelola-akun
        $response = $this->get(route('user.index'));

        // Pastikan status 200 OK
        $response->assertStatus(200);

        // Pastikan view yang dipanggil benar
        $response->assertViewIs('kelola-akun');

        // Pastikan variabel 'users' dikirim ke view
        $response->assertViewHas('users');

        // Pastikan variabel 'activeTab' dikirim ke view
        $response->assertViewHas('activeTab');

        // Cek default tab aktif adalah 'daftar'
        $this->assertEquals('daftar', $response->viewData('activeTab'));
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_sets_active_tab_to_registrasi_after_store()
    {
        // Simulasi submit form registrasi akun baru
        $response = $this->post(route('kelola-akun.store'), [
            'nama' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'Administrator',
        ]);

        // Pastikan redirect ke route user.index dengan query tab=registrasi
        $response->assertRedirect(route('user.index', ['tab' => 'registrasi']));

        // Pastikan session menyimpan pesan sukses
        $response->assertSessionHas('success', 'Akun baru berhasil ditambahkan!');
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_redirects_to_index_when_switching_tab()
    {
        // Simulasi akses /kelola-akun/switch-tab/registrasi
        $response = $this->get(route('kelola-akun.switchTab', ['tab' => 'registrasi']));

        // Pastikan redirect ke user.index dengan tab=registrasi
        $response->assertRedirect(route('user.index', ['tab' => 'registrasi']));
    }
}
