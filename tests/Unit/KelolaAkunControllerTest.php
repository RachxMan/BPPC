<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\KelolaAkunController;

class KelolaAkunControllerTest extends TestCase
{
    /** @test */
    public function it_returns_kelola_akun_view()
    {
        $controller = new KelolaAkunController();
        $response = $controller->index();

        $this->assertEquals('users.index', $response->name());
    }
}
