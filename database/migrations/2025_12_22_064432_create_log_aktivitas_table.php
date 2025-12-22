<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_peserta')->nullable()->constrained('peserta')->onDelete('set null');
            $table->foreignId('id_pendaftaran')->nullable()->constrained('pendaftaran')->onDelete('set null');
            $table->string('jenis_aktivitas', 100);
            $table->text('deskripsi')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->index('dibuat_pada', 'idx_aktivitas_tanggal');
            $table->index('id_peserta', 'idx_aktivitas_peserta');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
