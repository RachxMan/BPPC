<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\UploadDataController;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadDataControllerTest extends TestCase
{
    /** @test */
    public function it_returns_upload_data_view()
    {
        $controller = new UploadDataController();
        $response = $controller->index();

        $this->assertEquals('upload.index', $response->name());
    }

    /** @test */
    public function it_can_handle_file_upload()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('test.csv', 10);
        $request = Request::create('/upload-data', 'POST', ['file' => $file]);

        $controller = new UploadDataController();
        $response = $controller->upload($request);

        Storage::disk('public')->assertExists('uploads/' . $file->hashName());
        $this->assertNotNull($response);
    }
}
