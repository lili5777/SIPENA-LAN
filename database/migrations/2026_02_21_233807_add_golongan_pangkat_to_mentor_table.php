<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mentor', function (Blueprint $table) {
            if (!Schema::hasColumn('mentor', 'golongan')) {
                $table->string('golongan', 50)->nullable()->after('jabatan_mentor');
            }
            if (!Schema::hasColumn('mentor', 'pangkat')) {
                $table->string('pangkat', 50)->nullable()->after('golongan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mentor', function (Blueprint $table) {
            $table->dropColumn(['golongan', 'pangkat']);
        });
    }
};