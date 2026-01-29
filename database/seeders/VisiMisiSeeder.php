<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visi;
use App\Models\Misi;

class VisiMisiSeeder extends Seeder
{
    public function run(): void
    {
        // --- Visi (1 record) ---
        Visi::create([
            'visi' => 'Sebagai Institusi Pembelajar Berkelas Dunia yang Mampu menjadi Penggerak Utama dalam mewujudkan World Class Government Untuk Mendukung Visi Indonesia Maju yang berdaulat, Mandiri, dan berkepribadian berlandaskan gotong royong.',
            'ctt'  => 'Membangun Indonesia yang Berdaulat dan Mandiri',
        ]);

        // --- Misi (4 record) ---
        $misis = [
            [
                'ctt'  => 'SDM Aparatur Unggul',
                'isi'  => 'Mewujudkan SDM Aparatur unggul melalui kebijakan, pembinaan, dan penyelenggaraan pengembangan kompetensi yang berstandar internasional.',
                'icon' => 'users',
            ],
            [
                'ctt'  => 'Kebijakan Berkualitas',
                'isi'  => 'Mewujudkan Kebijakan Administrasi Negara yang berkualitas melalui kajian kebijakan berbasis evidence dan penyediaan analis kebijakan yang kompeten.',
                'icon' => 'edit',
            ],
            [
                'ctt'  => 'Inovasi Administrasi',
                'isi'  => 'Mewujudkan Inovasi Administrasi Negara yang berkualitas melalui pengembangan model inovasi serta penguatan kapasitas dan budaya inovasi.',
                'icon' => 'zap',
            ],
            [
                'ctt'  => 'Organisasi Pembelajar',
                'isi'  => 'Mewujudkan organisasi pembelajar berkinerja tinggi melalui dukungan pelayanan yang berkualitas dan berbasis elektronik.',
                'icon' => 'book',
            ],
        ];

        foreach ($misis as $data) {
            Misi::create($data);
        }
    }
}
