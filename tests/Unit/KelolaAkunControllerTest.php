<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\KelolaController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\View\View;

class KelolaAkunControllerTest extends TestCase
{
    use RefreshDatabase; // <-- Tambahkan ini

    /** @test */
    public function it_returns_kelola_akun_view()
    {
        $controller = new KelolaController();
        $response = $controller->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('kelola.index', $response->name());
    }
}
