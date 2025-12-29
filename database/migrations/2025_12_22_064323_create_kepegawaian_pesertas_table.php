<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kepegawaian_peserta', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_peserta')->constrained('peserta')->onDelete('cascade');
            $table->string('asal_instansi', 200);
            $table->string('unit_kerja', 200)->nullable();
            $table->foreignId('id_provinsi')->constrained('provinsis')->onDelete('cascade');
            $table->foreignId('id_kabupaten_kota')->nullable()->constrained('kabupatens')->onDelete('cascade');
            $table->text('alamat_kantor');
            $table->string('nomor_telepon_kantor', 20)->nullable();
            $table->string('email_kantor', 100)->nullable();
            $table->string('jabatan', 200);
            $table->string('eselon', 50)->nullable();
            $table->date('tanggal_sk_jabatan')->nullable();
            $table->string('file_sk_jabatan', 255)->nullable();
            $table->string('pangkat', 50)->nullable();
            $table->string('golongan_ruang', 50);
            $table->string('file_sk_pangkat', 255)->nullable();
            $table->string('nomor_sk_cpns', 100)->nullable();
            $table->string('nomor_sk_terakhir', 100)->nullable();
            $table->date('tanggal_sk_cpns')->nullable();
            $table->string('file_sk_cpns', 255)->nullable();
            $table->string('file_spmt', 255)->nullable();
            $table->string('file_skp', 255)->nullable();
            $table->integer('tahun_lulus_pkp_pim_iv')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            $table->index('asal_instansi', 'idx_kepegawaian_instansi');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kepegawaian_peserta');
    }
};
