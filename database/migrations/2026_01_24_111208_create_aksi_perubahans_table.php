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
            $table->string('file')->nullable();
            $table->string('biodata')->nullable();
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
