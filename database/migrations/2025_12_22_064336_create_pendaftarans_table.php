<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_peserta')->constrained('peserta')->onDelete('cascade');
            $table->foreignId('id_jenis_pelatihan')->constrained('jenis_pelatihan');
            $table->foreignId('id_angkatan')->constrained('angkatan');
            $table->string('file_surat_tugas', 255)->nullable();
            $table->string('file_surat_kesediaan', 255)->nullable();
            $table->string('file_pakta_integritas', 255)->nullable();
            $table->string('file_surat_komitmen', 255)->nullable();
            $table->string('file_surat_kelulusan_seleksi', 255)->nullable();
            $table->string('file_surat_sehat', 255)->nullable();
            $table->string('file_surat_bebas_narkoba', 255)->nullable();
            $table->string('file_surat_pernyataan_administrasi', 255)->nullable();
            $table->string('file_sertifikat_penghargaan', 255)->nullable();
            $table->string('file_persetujuan_mentor', 255)->nullable();
            $table->enum('status_pendaftaran', ['Menunggu Verifikasi', 'Diterima', 'Ditolak'])->default('Menunggu Verifikasi');
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->unique(['id_peserta', 'id_angkatan']);
            $table->index('id_jenis_pelatihan', 'idx_jenis_pelatihan');
            $table->index(['id_jenis_pelatihan', 'status_pendaftaran'], 'idx_status_jenis');
            $table->index('status_pendaftaran', 'idx_pendaftaran_status');
            $table->index('tanggal_daftar', 'idx_pendaftaran_tanggal');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
