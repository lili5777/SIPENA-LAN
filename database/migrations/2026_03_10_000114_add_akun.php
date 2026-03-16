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
        Schema::table('users', function (Blueprint $table) {

    if (!Schema::hasColumn('users', 'evaluator_id')) {
        $table->unsignedBigInteger('evaluator_id')->nullable()->after('peserta_id');
    }

    if (!Schema::hasColumn('users', 'penguji_id')) {
        $table->foreignId('penguji_id')
              ->nullable()
              ->after('evaluator_id')
              ->constrained('pengujis')
              ->cascadeOnDelete();
    }

    if (!Schema::hasColumn('users', 'coach_id')) {
        $table->foreignId('coach_id')
              ->nullable()
              ->after('penguji_id')
              ->constrained('coaches')
              ->cascadeOnDelete();
    }

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['evaluator_id']);
            $table->dropColumn('evaluator_id');

            $table->dropForeign(['penguji_id']);
            $table->dropColumn('penguji_id');

            $table->dropForeign(['coach_id']);
            $table->dropColumn('coach_id');
        });
    }
};
