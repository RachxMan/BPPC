<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ProfilPengaturanController;

class ProfilPengaturanControllerTest extends TestCase
{
    /** @test */
    public function it_returns_profile_view()
    {
        $controller = new ProfilPengaturanController();
        $response = $controller->index();

        $this->assertEquals('profile.index', $response->name());
    }
}
