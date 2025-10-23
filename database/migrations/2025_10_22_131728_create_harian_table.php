<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harian', function (Blueprint $table) {
            $table->id();
            $table->string('witel');
            $table->string('type');
            $table->string('produk_bundling');
            $table->string('fi_home');
            $table->string('account_num');
            $table->string('snd');
            $table->string('snd_group');
            $table->string('nama');
            $table->string('cp')->nullable();
            $table->string('datel');
            $table->date('payment_date')->nullable();
            $table->string('status_bayar');
            $table->string('no_hp')->nullable();
            $table->string('nama_real');
            $table->string('segmen_real');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harian');
    }
};
