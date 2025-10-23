<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_jatuh_tempo');
            $table->text('daftar_customer_prioritas');
            $table->foreignId('collection_agent_id')->constrained('collection_agents')->onDelete('cascade'); // Many-to-One with CollectionAgent
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};

