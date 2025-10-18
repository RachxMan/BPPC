<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\MailingListController;

class MailingListControllerTest extends TestCase
{
    /** @test */
    public function it_returns_mailing_list_view()
    {
        $controller = new MailingListController();
        $response = $controller->index();

        $this->assertEquals('mailing.index', $response->name());
    }
}
