<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class LoginControllerTest extends TestCase
{
    /** @test */
    public function it_returns_login_view()
    {
        $controller = new LoginController();
        $response = $controller->index();

        $this->assertEquals('auth.login', $response->name());
    }

    /** @test */
    public function it_can_validate_login_request()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertNotNull($response);
    }
}
