<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\UploadDataController;
use App\Models\User;

class UploadDataControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', ['--env' => 'testing']);

        $this->user = User::factory()->create([
            'role' => 'admin',
            'nama_lengkap' => 'Admin Test',
            'username' => 'admin_test',
            'no_telp' => '081234567890',
        ]);

        $this->actingAs($this->user);

        Storage::fake('public');
        DB::table('uploaded_files')->truncate();
    }

    /** @test */
    public function it_returns_harian_view(): void
    {
        $controller = new UploadDataController();
        $response = $controller->harian();

        $this->assertStringContainsString('harian', viewName($response));
    }

    /** @test */
    public function it_returns_bulanan_view(): void
    {
        $controller = new UploadDataController();
        $response = $controller->bulanan();

        $this->assertStringContainsString('bulanan', viewName($response));
    }

    /** @test */
    public function it_can_handle_harian_file_upload(): void
    {
        $file = UploadedFile::fake()->create('harian.xlsx', 10);

        $controller = new UploadDataController();

        // Simulasikan nama file sesuai controller
        $filename = now()->format('d_m_Y_His') . '_' . $file->getClientOriginalName();

        // Panggil method
        $request = Request::create('/upload-data/harian/import', 'POST', [], [], ['file' => $file]);
        $response = $controller->importHarian($request);

        // File harus ada di storage fake
        Storage::disk('public')->assertExists('upload/admin/excel_files/harian/' . $filename);

        $this->assertNotNull($response);

        // Data tersimpan di DB
        $this->assertDatabaseHas('uploaded_files', [
            'filename' => $filename,
            'type' => 'harian',
        ]);
    }

    /** @test */
    public function it_can_handle_bulanan_file_upload(): void
    {
        $file = UploadedFile::fake()->create('bulanan.xlsx', 10);

        $controller = new UploadDataController();

        $filename = now()->format('d_m_Y_His') . '_' . $file->getClientOriginalName();

        $request = Request::create('/upload-data/bulanan/import', 'POST', [], [], ['file' => $file]);
        $response = $controller->importBulanan($request);

        Storage::disk('public')->assertExists('upload/admin/excel_files/bulanan/' . $filename);
        $this->assertNotNull($response);

        $this->assertDatabaseHas('uploaded_files', [
            'filename' => $filename,
            'type' => 'bulanan',
        ]);
    }
}

/**
 * Helper untuk mengambil view name dari response
 */
function viewName($response)
{
    if (method_exists($response, 'getName')) {
        return $response->getName();
    }

    return '';
}
