<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('periode');
            $table->string('jenis_report');
            $table->string('format_file');
            $table->foreignId('id_admin')->constrained('administrators')->onDelete('cascade'); // Many-to-One with Administrator
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

