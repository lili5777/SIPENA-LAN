<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pejabat;

class PejabatSeeder extends Seeder
{
    public function run(): void
    {
        // Biar tidak dobel kalau seed dijalankan berkali-kali
        Pejabat::truncate();

        $data = [
            [
                'nama_pejabat' => 'Dr. Muhammad Aswad, M.Si',
                'jabatan_pejabat' => 'Kepala Pusat PUSJAR SKMP',
                'nip_pejabat' => '19670206 199303 1 001',
                'foto_pejabat' => 'kapus.png', // file ada di public/gambar/kapus.png
                'posisi' => 1,
            ],
            // [
            //     'nama_pejabat' => 'Pejabat 2',
            //     'jabatan_pejabat' => 'Wakil Kepala Bidang SDM',
            //     'nip_pejabat' => '1xxxxxxxxxx',
            //     'foto_pejabat' => null, // kalau belum ada fotonya
            //     'posisi' => 2,
            // ],
            // [
            //     'nama_pejabat' => 'Pejabat 3',
            //     'jabatan_pejabat' => 'Kepala Bidang Inovasi',
            //     'nip_pejabat' => '1xxxxxxxxxxxxx',
            //     'foto_pejabat' => null,
            //     'posisi' => 3,
            // ],
        ];

        foreach ($data as $row) {
            Pejabat::create($row);
        }
    }
}
