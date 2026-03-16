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
        Schema::create('upload_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_peserta')->constrained('peserta')->onDelete('cascade');
            $table->foreignId('id_indikator_nilai')->constrained('indikator_nilai')->onDelete('cascade');
            $table->string('file')->nullable();
            $table->string('nilai')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_nilais');
    }
};
