<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE peserta MODIFY COLUMN ukuran_training ENUM('XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL', 'XXXXXL', 'XXXXXXL', 'XXXXXXXL') NULL");
        DB::statement("ALTER TABLE peserta MODIFY COLUMN ukuran_kaos ENUM('XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL', 'XXXXXL', 'XXXXXXL', 'XXXXXXXL') NULL");
        DB::statement("ALTER TABLE peserta MODIFY COLUMN ukuran_celana ENUM('XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL', 'XXXXXL', 'XXXXXXL', 'XXXXXXXL') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE peserta MODIFY COLUMN ukuran_training ENUM('S', 'M', 'L', 'XL', 'XXL', 'XXXL') NULL");
        DB::statement("ALTER TABLE peserta MODIFY COLUMN ukuran_kaos ENUM('S', 'M', 'L', 'XL', 'XXL', 'XXXL') NULL");
        DB::statement("ALTER TABLE peserta MODIFY COLUMN ukuran_celana ENUM('S', 'M', 'L', 'XL', 'XXL', 'XXXL') NULL");
    }
};
