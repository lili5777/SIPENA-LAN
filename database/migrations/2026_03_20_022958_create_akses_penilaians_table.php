<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akses_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_indikator_nilai')
                  ->constrained('indikator_nilai')
                  ->onDelete('cascade');
            $table->foreignId('role_id')
                  ->constrained('roles')
                  ->onDelete('cascade');
            $table->timestamps();

            // Satu indikator tidak boleh duplikat role
            $table->unique(['id_indikator_nilai', 'role_id'], 'unique_akses_indikator_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akses_penilaian');
    }
};