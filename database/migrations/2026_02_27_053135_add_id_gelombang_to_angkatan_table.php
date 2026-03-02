<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Step 1: tambah kolom dulu
    Schema::table('angkatan', function (Blueprint $table) {
        $table->unsignedBigInteger('id_gelombang')->nullable()->after('id_jenis_pelatihan');
    });

    // Step 2: baru tambah foreign key terpisah
    Schema::table('angkatan', function (Blueprint $table) {
        $table->foreign('id_gelombang')
              ->references('id')
              ->on('gelombang')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('angkatan', function (Blueprint $table) {
        $table->dropForeign(['id_gelombang']);
        $table->dropColumn('id_gelombang');
    });
}
};
