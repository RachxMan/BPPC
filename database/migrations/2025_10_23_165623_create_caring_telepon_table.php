<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaringTeleponTable extends Migration
{
    public function up(): void
    {
        Schema::create('caring_telepon', function (Blueprint $table) {
            $table->id();
            $table->string('witel')->nullable();
            $table->string('type')->nullable();
            $table->string('produk_bundling')->nullable();
            $table->string('fi_home')->nullable();
            $table->string('account_num')->nullable();
            $table->string('snd')->nullable();
            $table->string('snd_group')->nullable();
            $table->string('nama')->nullable();
            $table->string('cp')->nullable();
            $table->string('datel')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('status_bayar')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('nama_real')->nullable();
            $table->string('segmen_real')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('status_call')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caring_telepon');
    }
}
