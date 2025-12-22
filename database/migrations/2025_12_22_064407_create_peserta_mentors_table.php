<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_mentor', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_pendaftaran')->constrained('pendaftaran')->onDelete('cascade');
            $table->foreignId('id_mentor')->nullable()->constrained('mentor');
            $table->date('tanggal_penunjukan')->nullable();
            $table->enum('status_mentoring', ['Ditugaskan', 'Aktif', 'Selesai', 'Dibatalkan'])->default('Ditugaskan');
            $table->text('catatan')->nullable();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_mentor');
    }
};
