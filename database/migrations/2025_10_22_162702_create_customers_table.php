<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat');
            $table->string('no_telp');
            $table->enum('status_pembayaran', ['lunas', 'menunggak']);
            $table->text('catatan_follow_up');
            $table->foreignId('id_admin')->constrained('administrators')->onDelete('cascade'); // Many-to-One with Administrator
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

