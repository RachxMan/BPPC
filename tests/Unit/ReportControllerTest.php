<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    /**
     * Pastikan halaman report harian dapat diakses
     *
     * @return void
     */
    public function test_harian_page_displays_correctly()
    {
        $response = $this->get('/report/harian');

        $response->assertStatus(200); // cek HTTP 200 OK
        $response->assertViewIs('report.harian'); // cek view yang dikembalikan
    }

    /**
     * Pastikan halaman report bulanan dapat diakses
     *
     * @return void
     */
    public function test_bulanan_page_displays_correctly()
    {
        $response = $this->get('/report/bulanan');

        $response->assertStatus(200); // cek HTTP 200 OK
        $response->assertViewIs('report.bulanan'); // cek view yang dikembalikan
    }
}
