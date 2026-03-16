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
        Schema::create('detail_indikator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_indikator_nilai')->constrained('indikator_nilai')->onDelete('cascade');
            $table->integer('level')->nullable();
            $table->string('uraian')->nullable();
            $table->string('range')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_indikators');
    }
};
