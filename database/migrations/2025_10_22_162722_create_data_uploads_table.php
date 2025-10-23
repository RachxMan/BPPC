<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('periode');
            $table->string('file_name');
            $table->date('upload_date');
            $table->foreignId('id_admin')->constrained('administrators')->onDelete('cascade'); // Many-to-One with Administrator
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_uploads');
    }
};

