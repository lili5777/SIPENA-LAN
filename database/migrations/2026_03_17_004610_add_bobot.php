<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah bobot ke tabel jenis_nilai
        Schema::table('jenis_nilai', function (Blueprint $table) {
            $table->decimal('bobot', 5, 2)->default(0)->after('deskripsi')
                  ->comment('Bobot dalam persen (%), total per jenis_pelatihan tidak boleh > 100');
        });

        // Tambah bobot ke tabel indikator_nilai
        Schema::table('indikator_nilai', function (Blueprint $table) {
            $table->decimal('bobot', 5, 2)->default(0)->after('deskripsi')
                  ->comment('Bobot dalam persen (%), total per jenis_nilai tidak boleh > bobot induknya');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_nilai', function (Blueprint $table) {
            $table->dropColumn('bobot');
        });

        Schema::table('indikator_nilai', function (Blueprint $table) {
            $table->dropColumn('bobot');
        });
    }
};