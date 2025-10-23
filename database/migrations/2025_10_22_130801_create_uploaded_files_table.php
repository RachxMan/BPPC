<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path'); // kolom path untuk lokasi file
            $table->enum('type', ['harian','bulanan']);
            $table->unsignedBigInteger('uploaded_by')->nullable(); // bisa foreign key ke users
            $table->timestamps();

            // Optional: jika ingin pakai foreign key
            // $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('uploaded_files');
    }
};
