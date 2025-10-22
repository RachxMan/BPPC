<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KelolaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_kelola_akun_view()
    {
        $response = $this->get('/kelola-akun');

        $response->assertStatus(200);
        $response->assertViewIs('kelola.index');
    }
}
