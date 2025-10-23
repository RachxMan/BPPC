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
        $response = $this->get('/kelola-akun');

        $response->assertStatus(200);
        $response->assertViewIs('kelola.index');
    }
}
