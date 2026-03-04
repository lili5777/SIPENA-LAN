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
        Schema::create('kelompok_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kelompok')->constrained('kelompoks')->onDelete('cascade');
            $table->foreignId('id_peserta')->constrained('peserta')->onDelete('cascade');
            $table->unique(['id_kelompok', 'id_peserta']);
            $table->timestamp('dibuat_pada')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_pesertas');
    }
};
