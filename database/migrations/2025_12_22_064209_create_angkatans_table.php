<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('angkatan', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_jenis_pelatihan')->constrained('jenis_pelatihan');
            $table->string('nama_angkatan', 50);
            $table->integer('tahun');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('kuota')->nullable();
            $table->enum('status_angkatan', ['Dibuka', 'Ditutup', 'Berlangsung', 'Selesai'])->default('Dibuka');
            $table->boolean('kunci_edit')->default(false);
            $table->boolean('kunci_judul')->default(false);
            $table->string('link_gb_wa', 100)->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->index(['id_jenis_pelatihan', 'status_angkatan'], 'idx_jenis_status');
            $table->index('status_angkatan', 'idx_angkatan_status');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('angkatan');
    }
};
