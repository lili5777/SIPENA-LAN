<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id('id');
            $table->string('nip_nrp', 50);
            $table->string('nama_lengkap', 200);
            $table->string('nama_panggilan', 100)->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat_rumah')->nullable();
            $table->string('email_pribadi', 100)->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->enum('pendidikan_terakhir', ['SD', 'SMP', 'SMU', 'D3', 'D4', 'S1', 'S2', 'S3'])->nullable();
            $table->string('bidang_studi', 100)->nullable();
            $table->string('bidang_keahlian', 100)->nullable();
            $table->enum('status_perkawinan', ['Belum Menikah', 'Menikah', 'Duda', 'Janda'])->nullable();
            $table->string('nama_pasangan', 200)->nullable();
            $table->string('olahraga_hobi', 100)->nullable();
            $table->enum('perokok', ['Ya', 'Tidak'])->nullable();
            $table->enum('ukuran_training', ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'])->nullable();
            $table->enum('ukuran_kaos', ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'])->nullable();
            $table->enum('ukuran_celana', ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'])->nullable();
            $table->text('kondisi_peserta')->nullable();
            $table->string('file_ktp', 255)->nullable();
            $table->string('file_pas_foto', 255)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            $table->index('nip_nrp', 'idx_peserta_nip');
            $table->index('nama_lengkap', 'idx_peserta_nama');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};
