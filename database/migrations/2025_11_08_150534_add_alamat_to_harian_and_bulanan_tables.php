<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('harian', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('nama');
        });

        Schema::table('bulanan', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('harian', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });

        Schema::table('bulanan', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
};
