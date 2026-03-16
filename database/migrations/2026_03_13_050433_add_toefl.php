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
        //
        Schema::table('kepegawaian_peserta', function (Blueprint $table) {
            if (!Schema::hasColumn('kepegawaian_peserta', 'file_toefl')) {
                $table->string('file_toefl', 100)->nullable()->after('file_skp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('kepegawaian_peserta', function (Blueprint $table) {
            $table->dropColumn('file_toefl');
        });
    }
};
