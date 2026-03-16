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
        Schema::create('jenis_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jenis_pelatihan')->constrained('jenis_pelatihan')->onDelete('cascade');
            $table->string('name');
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_nilais');
    }
};
