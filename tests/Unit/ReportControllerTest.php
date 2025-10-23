<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class ReportControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function harian_page_displays_correctly()
    {
        $response = $this->get('/laporan/harian');

        $response->assertStatus(200); // cek HTTP 200 OK
        $response->assertViewIs('report.harian'); // cek view yang dikembalikan
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function bulanan_page_displays_correctly()
    {
        $response = $this->get('/laporan/bulanan');

        $response->assertStatus(200); // cek HTTP 200 OK
        $response->assertViewIs('report.bulanan'); // cek view yang dikembalikan
    }
}
