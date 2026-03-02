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
    Schema::create('gelombang', function (Blueprint $table) { // ganti gelombangS → gelombang
        $table->id();
        $table->unsignedBigInteger('id_jenis_pelatihan');
        $table->string('nama_gelombang');
        $table->integer('tahun');
        $table->string('kategori')->nullable();
        $table->timestamps();

        $table->foreign('id_jenis_pelatihan')
            ->references('id')
            ->on('jenis_pelatihan')
            ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('gelombang'); // ganti gelombangS → gelombang
}
};
