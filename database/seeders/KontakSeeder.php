<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kontak;

class KontakSeeder extends Seeder
{
    public function run(): void
    {
        // Biar tidak dobel saat seed dijalankan berkali-kali
        Kontak::truncate();

        Kontak::create([
            'alamat'   => 'Jl. Raya Baruga, No 48 Antang, Kota Makassar',
            'nomor_hp' => '(0411) 490101',
            'email'    => 'latbang.puslatbangkmp@gmail.com',

            // isi kalau ada, kalau belum biarkan null
            'fb'       => null, // contoh: 'https://facebook.com/namapage'
            'ig'       => null, // contoh: 'https://instagram.com/namainstagram'
            'twitter'  => null, // contoh: 'https://twitter.com/namaakun'
            'linkedin' => null, // contoh: 'https://linkedin.com/in/namaakun'
        ]);
    }
}
