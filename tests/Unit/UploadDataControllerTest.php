<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UploadDataController;

class UploadDataControllerTest extends TestCase
{
    public function test_it_returns_upload_data_view(): void
    {
        $controller = new UploadDataController();
        $response = $controller->index();

        $this->assertEquals('upload.index', $response->name());
    }

    public function test_it_can_handle_file_upload(): void
    {
        // Fake storage
        Storage::fake('public');

        // Buat file dummy
        $file = UploadedFile::fake()->create('test.csv', 10);

        // Buat request dengan file
        $request = Request::create('/upload-data', 'POST', [], [], ['file' => $file]);

        $controller = new UploadDataController();
        $response = $controller->upload($request);

        // Pastikan file tersimpan di storage
        Storage::disk('public')->assertExists('uploads/' . $file->hashName());

        $this->assertNotNull($response);
    }
}
