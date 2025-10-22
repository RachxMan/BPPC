<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom role jika belum ada
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('email');
            }
            // Tambah kolom status jika belum ada
            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('Aktif')->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
