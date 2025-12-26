<?php

namespace Database\Seeders;

use App\Models\Angkatan;
use App\Models\JenisPelatihan;
use App\Models\KabupatenKota;
use App\Models\Mentor;
use App\Models\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Dataawal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat data Jenis Pelatihan berdasarkan dokumen asli
        $jenisPelatihanData = [
            [
                'kode_pelatihan' => 'PKN_TK_II',
                'nama_pelatihan' => 'PKN TK II',
                'deskripsi' => 'Pelatihan Kepemimpinan Nasional Tingkat II',
                'aktif' => true,
            ],
            [
                'kode_pelatihan' => 'PD_CPNS',
                'nama_pelatihan' => 'PD CPNS',
                'deskripsi' => 'Pelatihan Dasar Calon Pegawai Negeri Sipil',
                'aktif' => true,
            ],
            [
                'kode_pelatihan' => 'PKA',
                'nama_pelatihan' => 'PKA',
                'deskripsi' => 'Pelatihan Kepemimpinan Administrator',
                'aktif' => true,
            ],
            [
                'kode_pelatihan' => 'PKP',
                'nama_pelatihan' => 'PKP',
                'deskripsi' => 'Pelatihan Kepemimpinan Pengawas',
                'aktif' => true,
            ],
        ];

        foreach ($jenisPelatihanData as $data) {
            JenisPelatihan::create($data);
        }

        // Buat data Angkatan untuk setiap Jenis Pelatihan
        // Misalnya, 2 angkatan per jenis pelatihan untuk tahun 2025 dan 2026
        $jenisPelatihan = JenisPelatihan::all();

        foreach ($jenisPelatihan as $jp) {
            Angkatan::create([
                'id_jenis_pelatihan' => $jp->id,
                'nama_angkatan' => 'Angkatan 1',
                'tahun' => 2025,
                'tanggal_mulai' => '2025-01-01',
                'tanggal_selesai' => '2025-03-31',
                'kuota' => 50,
                'status_angkatan' => 'Ditutup',
            ]);

            Angkatan::create([
                'id_jenis_pelatihan' => $jp->id,
                'nama_angkatan' => 'Angkatan 2',
                'tahun' => 2026,
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => '2026-03-31',
                'kuota' => 50,
                'status_angkatan' => 'Dibuka',
            ]);
        }

       
        // Data contoh mentor untuk mendukung pendaftaran
        // Buat beberapa mentor dengan detail lengkap berdasarkan field di form (e.g., nama, jabatan, rekening, NPWP)
        // Ini adalah data fiktif tapi realistis untuk testing dan pendaftaran awal
        $mentorData = [
            [
                'nama_mentor' => 'Dr. Ahmad Santoso, M.Si.',
                'jabatan_mentor' => 'Kepala Bidang Pengembangan SDM',
                'nomor_rekening' => 'Bank Mandiri,1234567890, an/Ahmad Santoso',
                'npwp_mentor' => '01.234.567.8-901.000',
                'email_mentor' => 'ahmad.santoso@example.com',
                'nomor_hp_mentor' => '081234567890',
                'status_aktif' => true,
            ],
            [
                'nama_mentor' => 'Prof. Budi Hartono, Ph.D.',
                'jabatan_mentor' => 'Direktur Pelatihan Kepemimpinan',
                'nomor_rekening' => 'Bank BCA,0987654321, an/Budi Hartono',
                'npwp_mentor' => '02.345.678.9-012.000',
                'email_mentor' => 'budi.hartono@example.com',
                'nomor_hp_mentor' => '082345678901',
                'status_aktif' => true,
            ],
            [
                'nama_mentor' => 'Siti Aminah, S.Pd., M.Pd.',
                'jabatan_mentor' => 'Koordinator Mentor CPNS',
                'nomor_rekening' => 'Bank BNI,1122334455, an/Siti Aminah',
                'npwp_mentor' => '03.456.789.0-123.000',
                'email_mentor' => 'siti.aminah@example.com',
                'nomor_hp_mentor' => '083456789012',
                'status_aktif' => true,
            ],
            [
                'nama_mentor' => 'Rudi Wijaya, S.E.',
                'jabatan_mentor' => 'Mentor Senior PKA',
                'nomor_rekening' => 'Bank BRI,6677889900, an/Rudi Wijaya',
                'npwp_mentor' => '04.567.890.1-234.000',
                'email_mentor' => 'rudi.wijaya@example.com',
                'nomor_hp_mentor' => '084567890123',
                'status_aktif' => true,
            ],
            [
                'nama_mentor' => 'Dewi Lestari, M.Hum.',
                'jabatan_mentor' => 'Spesialis Pengembangan Karir',
                'nomor_rekening' => 'Bank Mandiri,5544332211, an/Dewi Lestari',
                'npwp_mentor' => '05.678.901.2-345.000',
                'email_mentor' => 'dewi.lestari@example.com',
                'nomor_hp_mentor' => '085678901234',
                'status_aktif' => true,
            ],
        ];

        foreach ($mentorData as $data) {
            Mentor::create($data);
        }
    }

}
