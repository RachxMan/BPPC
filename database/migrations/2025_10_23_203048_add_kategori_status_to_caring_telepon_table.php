<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKategoriStatusToCaringTeleponTable extends Migration
{
    public function up(): void
    {
        Schema::table('caring_telepon', function (Blueprint $table) {
            $table->string('kategori_status')->nullable()->after('status_call');
        });
    }

    public function down(): void
    {
        Schema::table('caring_telepon', function (Blueprint $table) {
            $table->dropColumn('kategori_status');
        });
    }
};
