<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\DashboardController;

class DashboardControllerTest extends TestCase
{
    /** @test */
    public function it_can_render_dashboard_view()
    {
        $controller = new DashboardController();
        $response = $controller->index();

        $this->assertEquals('dashboard', $response->name());
    }
}
