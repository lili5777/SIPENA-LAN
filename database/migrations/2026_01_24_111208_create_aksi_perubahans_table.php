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
        Schema::create('aksi_perubahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pendaftar')->constrained('pendaftaran')->onDelete('cascade');
            $table->string('judul');
            $table->text('abstrak')->nullable();
            $table->enum('kategori_aksatika', ['pilihan1', 'pilihan2'])->nullable();
            $table->string('file')->nullable();
            $table->string('link_video')->nullable();
            $table->string('link_laporan_majalah')->nullable();
            $table->string('lembar_pengesahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aksi_perubahans');
    }
};
