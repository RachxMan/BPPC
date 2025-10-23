<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('harian', function (Blueprint $table) {
            $table->unique('snd');
        });
    }

    public function down(): void
    {
        Schema::table('harian', function (Blueprint $table) {
            $table->dropUnique(['snd']);
        });
    }
};
