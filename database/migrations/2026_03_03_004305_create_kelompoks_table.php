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
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jenis_pelatihan')->constrained('jenis_pelatihan')->onDelete('cascade');
            $table->foreignId('id_angkatan')->constrained('angkatan')->onDelete('cascade');
            $table->string('nama_kelompok');
            $table->integer('tahun');
            $table->foreignId('id_coach')->nullable()->constrained('coaches')->nullOnDelete();
            $table->foreignId('id_penguji')->nullable()->constrained('pengujis')->nullOnDelete();
            $table->foreignId('id_evaluator')->nullable()->constrained('evaluators')->nullOnDelete();
            $table->foreignId('id_mentor')->nullable()->constrained('mentor')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompoks');
    }
};
