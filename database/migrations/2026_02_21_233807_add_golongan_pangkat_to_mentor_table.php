<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mentor', function (Blueprint $table) {
            $table->string('golongan', 50)->nullable()->after('jabatan_mentor');
            $table->string('pangkat', 100)->nullable()->after('golongan');
        });
    }

    public function down(): void
    {
        Schema::table('mentor', function (Blueprint $table) {
            $table->dropColumn(['golongan', 'pangkat']);
        });
    }
};