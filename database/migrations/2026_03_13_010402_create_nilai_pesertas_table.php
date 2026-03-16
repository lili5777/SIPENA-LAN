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
        Schema::create('nilai_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_peserta')->constrained('peserta')->onDelete('cascade');
            $table->foreignId('id_indikator_nilai')->constrained('indikator_nilai')->onDelete('cascade');
            $table->float('nilai')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_pesertas');
    }
};
