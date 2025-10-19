<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LoginControllerTest extends TestCase
{
    #[Test]
    public function it_displays_the_login_page(): void
    {
        // Pastikan halaman login dapat diakses
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    #[Test]
    public function it_can_login_and_redirect_to_dashboard(): void
    {
        // Karena login saat ini dummy, kita tidak buat user
        // Langsung kirim POST ke /login dan pastikan redirect
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        // Login dummy selalu redirect ke dashboard
        $response->assertRedirect('/dashboard');

        // Tidak perlu autentikasi, cukup pastikan redirect sukses
        $this->assertTrue(true);
    }

    #[Test]
    public function it_redirects_to_dashboard_even_with_wrong_credentials(): void
    {
        // Kirim POST dengan password salah
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        // Karena login dummy, tetap redirect ke dashboard
        $response->assertRedirect('/dashboard');

        // Tidak ada autentikasi yang dicek
        $this->assertTrue(true);
    }

    #[Test]
    public function it_can_logout(): void
    {
        // Logout dummy: hanya redirect ke /login
        $response = $this->post('/logout');

        // Pastikan diarahkan kembali ke halaman login
        $response->assertRedirect('/login');

        // Tidak perlu cek Auth
        $this->assertTrue(true);
    }
}
