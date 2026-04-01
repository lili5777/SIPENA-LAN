<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel sudah ada, tambah kolom baru
        Schema::table('upload_nilai', function (Blueprint $table) {
            $table->text('catatan_peserta')->nullable()->after('nilai');
            $table->text('catatan_verifikator')->nullable()->after('catatan_peserta');
            $table->foreignId('id_verifikator')->nullable()->constrained('users')->nullOnDelete()->after('catatan_verifikator');
            $table->timestamp('verified_at')->nullable()->after('id_verifikator');
        });
    }

    public function down(): void
    {
        Schema::table('upload_nilai', function (Blueprint $table) {
            $table->dropColumn(['catatan_peserta', 'catatan_verifikator', 'id_verifikator', 'verified_at']);
        });
    }
};
