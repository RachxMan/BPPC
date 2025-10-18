<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\ProfileController;

class ProfileControllerTest extends TestCase
{
    /** @test */
    public function it_returns_profile_view()
    {
        $controller = new ProfileController();
        $response = $controller->index();

        $this->assertEquals('profile.index', $response->name());
    }
}
