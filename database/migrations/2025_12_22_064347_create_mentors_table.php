<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentor', function (Blueprint $table) {
            $table->id('id');
            $table->string('nama_mentor', 200);
            $table->string('jabatan_mentor', 200)->nullable();
            $table->string('nomor_rekening', 100)->nullable();
            $table->string('npwp_mentor', 50)->nullable();
            $table->string('email_mentor', 100)->nullable();
            $table->string('nomor_hp_mentor', 20)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentor');
    }
};
