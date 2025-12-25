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

        // Data provinsi Indonesia (berdasarkan data resmi terbaru hingga 2023, termasuk provinsi baru seperti Papua Barat Daya, dll.)
        $provinsiData = [
            ['nama_provinsi' => 'Aceh'],
            ['nama_provinsi' => 'Sumatera Utara'],
            ['nama_provinsi' => 'Sumatera Barat'],
            ['nama_provinsi' => 'Riau'],
            ['nama_provinsi' => 'Jambi'],
            ['nama_provinsi' => 'Sumatera Selatan'],
            ['nama_provinsi' => 'Bengkulu'],
            ['nama_provinsi' => 'Lampung'],
            ['nama_provinsi' => 'Kepulauan Bangka Belitung'],
            ['nama_provinsi' => 'Kepulauan Riau'],
            ['nama_provinsi' => 'DKI Jakarta'],
            ['nama_provinsi' => 'Jawa Barat'],
            ['nama_provinsi' => 'Jawa Tengah'],
            ['nama_provinsi' => 'DI Yogyakarta'],
            ['nama_provinsi' => 'Jawa Timur'],
            ['nama_provinsi' => 'Banten'],
            ['nama_provinsi' => 'Bali'],
            ['nama_provinsi' => 'Nusa Tenggara Barat'],
            ['nama_provinsi' => 'Nusa Tenggara Timur'],
            ['nama_provinsi' => 'Kalimantan Barat'],
            ['nama_provinsi' => 'Kalimantan Tengah'],
            ['nama_provinsi' => 'Kalimantan Selatan'],
            ['nama_provinsi' => 'Kalimantan Timur'],
            ['nama_provinsi' => 'Kalimantan Utara'],
            ['nama_provinsi' => 'Sulawesi Utara'],
            ['nama_provinsi' => 'Sulawesi Tengah'],
            ['nama_provinsi' => 'Sulawesi Selatan'],
            ['nama_provinsi' => 'Sulawesi Tenggara'],
            ['nama_provinsi' => 'Gorontalo'],
            ['nama_provinsi' => 'Sulawesi Barat'],
            ['nama_provinsi' => 'Maluku'],
            ['nama_provinsi' => 'Maluku Utara'],
            ['nama_provinsi' => 'Papua Barat Daya'],
            ['nama_provinsi' => 'Papua'],
            ['nama_provinsi' => 'Papua Selatan'],
            ['nama_provinsi' => 'Papua Tengah'],
            ['nama_provinsi' => 'Papua Pegunungan'],
            ['nama_provinsi' => 'Papua Barat'],
        ];

        foreach ($provinsiData as $data) {
            $provinsi = Provinsi::create($data);

            // Tambahkan contoh kabupaten/kota untuk setiap provinsi (minimal 2-3 per provinsi untuk mendukung pendaftaran)
            // Data ini berdasarkan kabupaten/kota nyata di Indonesia; bisa ditambahkan lebih banyak jika diperlukan
            $kabupatenData = [];
            switch ($provinsi->nama_provinsi) {
                case 'Aceh':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Banda Aceh'],
                        ['nama_kabupaten_kota' => 'Kabupaten Aceh Besar'],
                        ['nama_kabupaten_kota' => 'Kabupaten Pidie'],
                    ];
                    break;
                case 'Sumatera Utara':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Medan'],
                        ['nama_kabupaten_kota' => 'Kabupaten Deli Serdang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Simalungun'],
                    ];
                    break;
                case 'Sumatera Barat':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Padang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Agam'],
                        ['nama_kabupaten_kota' => 'Kabupaten Solok'],
                    ];
                    break;
                case 'Riau':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Pekanbaru'],
                        ['nama_kabupaten_kota' => 'Kabupaten Kampar'],
                        ['nama_kabupaten_kota' => 'Kabupaten Pelalawan'],
                    ];
                    break;
                case 'Jambi':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Jambi'],
                        ['nama_kabupaten_kota' => 'Kabupaten Muaro Jambi'],
                        ['nama_kabupaten_kota' => 'Kabupaten Kerinci'],
                    ];
                    break;
                case 'Sumatera Selatan':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Palembang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Ogan Komering Ilir'],
                        ['nama_kabupaten_kota' => 'Kabupaten Musi Banyuasin'],
                    ];
                    break;
                case 'Bengkulu':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Bengkulu'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bengkulu Utara'],
                        ['nama_kabupaten_kota' => 'Kabupaten Rejang Lebong'],
                    ];
                    break;
                case 'Lampung':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Bandar Lampung'],
                        ['nama_kabupaten_kota' => 'Kabupaten Lampung Selatan'],
                        ['nama_kabupaten_kota' => 'Kabupaten Tanggamus'],
                    ];
                    break;
                case 'Kepulauan Bangka Belitung':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Pangkal Pinang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bangka'],
                        ['nama_kabupaten_kota' => 'Kabupaten Belitung'],
                    ];
                    break;
                case 'Kepulauan Riau':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Batam'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bintan'],
                        ['nama_kabupaten_kota' => 'Kabupaten Karimun'],
                    ];
                    break;
                case 'DKI Jakarta':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Jakarta Pusat'],
                        ['nama_kabupaten_kota' => 'Jakarta Utara'],
                        ['nama_kabupaten_kota' => 'Jakarta Selatan'],
                    ];
                    break;
                case 'Jawa Barat':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Bandung'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bogor'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bekasi'],
                    ];
                    break;
                case 'Jawa Tengah':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Semarang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Magelang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Cilacap'],
                    ];
                    break;
                case 'DI Yogyakarta':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Yogyakarta'],
                        ['nama_kabupaten_kota' => 'Kabupaten Sleman'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bantul'],
                    ];
                    break;
                case 'Jawa Timur':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Surabaya'],
                        ['nama_kabupaten_kota' => 'Kabupaten Malang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Sidoarjo'],
                    ];
                    break;
                case 'Banten':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Tangerang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Serang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Pandeglang'],
                    ];
                    break;
                case 'Bali':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Denpasar'],
                        ['nama_kabupaten_kota' => 'Kabupaten Badung'],
                        ['nama_kabupaten_kota' => 'Kabupaten Gianyar'],
                    ];
                    break;
                case 'Nusa Tenggara Barat':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Mataram'],
                        ['nama_kabupaten_kota' => 'Kabupaten Lombok Barat'],
                        ['nama_kabupaten_kota' => 'Kabupaten Sumbawa'],
                    ];
                    break;
                case 'Nusa Tenggara Timur':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Kupang'],
                        ['nama_kabupaten_kota' => 'Kabupaten Timor Tengah Selatan'],
                        ['nama_kabupaten_kota' => 'Kabupaten Manggarai'],
                    ];
                    break;
                case 'Kalimantan Barat':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Pontianak'],
                        ['nama_kabupaten_kota' => 'Kabupaten Kubu Raya'],
                        ['nama_kabupaten_kota' => 'Kabupaten Sintang'],
                    ];
                    break;
                case 'Kalimantan Tengah':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Palangka Raya'],
                        ['nama_kabupaten_kota' => 'Kabupaten Kotawaringin Timur'],
                        ['nama_kabupaten_kota' => 'Kabupaten Barito Selatan'],
                    ];
                    break;
                case 'Kalimantan Selatan':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Banjarmasin'],
                        ['nama_kabupaten_kota' => 'Kabupaten Banjar'],
                        ['nama_kabupaten_kota' => 'Kabupaten Hulu Sungai Selatan'],
                    ];
                    break;
                case 'Kalimantan Timur':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Samarinda'],
                        ['nama_kabupaten_kota' => 'Kabupaten Kutai Kartanegara'],
                        ['nama_kabupaten_kota' => 'Kabupaten Berau'],
                    ];
                    break;
                case 'Kalimantan Utara':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Tarakan'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bulungan'],
                        ['nama_kabupaten_kota' => 'Kabupaten Nunukan'],
                    ];
                    break;
                case 'Sulawesi Utara':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Manado'],
                        ['nama_kabupaten_kota' => 'Kabupaten Minahasa'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bolaang Mongondow'],
                    ];
                    break;
                case 'Sulawesi Tengah':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Palu'],
                        ['nama_kabupaten_kota' => 'Kabupaten Donggala'],
                        ['nama_kabupaten_kota' => 'Kabupaten Poso'],
                    ];
                    break;
                case 'Sulawesi Selatan':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Makassar'],
                        ['nama_kabupaten_kota' => 'Kabupaten Gowa'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bone'],
                    ];
                    break;
                case 'Sulawesi Tenggara':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Kendari'],
                        ['nama_kabupaten_kota' => 'Kabupaten Konawe'],
                        ['nama_kabupaten_kota' => 'Kabupaten Kolaka'],
                    ];
                    break;
                case 'Gorontalo':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Gorontalo'],
                        ['nama_kabupaten_kota' => 'Kabupaten Gorontalo'],
                        ['nama_kabupaten_kota' => 'Kabupaten Bone Bolango'],
                    ];
                    break;
                case 'Sulawesi Barat':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Mamuju'],
                        ['nama_kabupaten_kota' => 'Kabupaten Mamuju'],
                        ['nama_kabupaten_kota' => 'Kabupaten Polewali Mandar'],
                    ];
                    break;
                case 'Maluku':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Ambon'],
                        ['nama_kabupaten_kota' => 'Kabupaten Maluku Tengah'],
                        ['nama_kabupaten_kota' => 'Kabupaten Buru'],
                    ];
                    break;
                case 'Maluku Utara':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Ternate'],
                        ['nama_kabupaten_kota' => 'Kabupaten Halmahera Selatan'],
                        ['nama_kabupaten_kota' => 'Kabupaten Pulau Morotai'],
                    ];
                    break;
                case 'Papua Barat Daya':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Sorong'],
                        ['nama_kabupaten_kota' => 'Kabupaten Sorong'],
                        ['nama_kabupaten_kota' => 'Kabupaten Raja Ampat'],
                    ];
                    break;
                case 'Papua':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Jayapura'],
                        ['nama_kabupaten_kota' => 'Kabupaten Jayapura'],
                        ['nama_kabupaten_kota' => 'Kabupaten Biak Numfor'],
                    ];
                    break;
                case 'Papua Selatan':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kabupaten Merauke'],
                        ['nama_kabupaten_kota' => 'Kabupaten Boven Digoel'],
                        ['nama_kabupaten_kota' => 'Kabupaten Asmat'],
                    ];
                    break;
                case 'Papua Tengah':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kabupaten Nabire'],
                        ['nama_kabupaten_kota' => 'Kabupaten Paniai'],
                        ['nama_kabupaten_kota' => 'Kabupaten Mimika'],
                    ];
                    break;
                case 'Papua Pegunungan':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kabupaten Jayawijaya'],
                        ['nama_kabupaten_kota' => 'Kabupaten Lanny Jaya'],
                        ['nama_kabupaten_kota' => 'Kabupaten Yahukimo'],
                    ];
                    break;
                case 'Papua Barat':
                    $kabupatenData = [
                        ['nama_kabupaten_kota' => 'Kota Manokwari'],
                        ['nama_kabupaten_kota' => 'Kabupaten Manokwari'],
                        ['nama_kabupaten_kota' => 'Kabupaten Fakfak'],
                    ];
                    break;
            }

            foreach ($kabupatenData as $kabData) {
                KabupatenKota::create(array_merge($kabData, ['id_provinsi' => $provinsi->id]));
            }
        }

        // Data contoh mentor untuk mendukung pendaftaran
        // Buat beberapa mentor dengan detail lengkap berdasarkan field di form (e.g., nama, jabatan, rekening, NPWP)
        // Ini adalah data fiktif tapi realistis untuk testing dan pendaftaran awal
        $mentorData = [
            [
                'nama_mentor' => 'Dr. Ahmad Santoso, M.Si.',
                'jabatan_mentor' => 'Kepala Bidang Pengembangan SDM',
                'nomor_rekening' => '1234567890',
                'nama_bank' => 'Bank Mandiri',
                'atas_nama_rekening' => 'Ahmad Santoso',
                'npwp_mentor' => '01.234.567.8-901.000',
                'email_mentor' => 'ahmad.santoso@example.com',
                'nomor_hp_mentor' => '081234567890',
                'file_persetujuan_mentor' => null, // Bisa diisi path jika ada file contoh
                'status_aktif' => true,
            ],
            [
                'nama_mentor' => 'Prof. Budi Hartono, Ph.D.',
                'jabatan_mentor' => 'Direktur Pelatihan Kepemimpinan',
                'nomor_rekening' => '0987654321',
                'nama_bank' => 'Bank BCA',
                'atas_nama_rekening' => 'Budi Hartono',
                'npwp_mentor' => '02.345.678.9-012.000',
                'email_mentor' => 'budi.hartono@example.com',
                'nomor_hp_mentor' => '082345678901',
                'file_persetujuan_mentor' => null,
                'status_aktif' => true,
            ],
            [
                'nama_mentor' => 'Siti Aminah, S.Pd., M.Pd.',
                'jabatan_mentor' => 'Koordinator Mentor CPNS',
                'nomor_rekening' => '1122334455',
                'nama_bank' => 'Bank BNI',
                'atas_nama_rekening' => 'Siti Aminah',
                'npwp_mentor' => '03.456.789.0-123.000',
                'email_mentor' => 'siti.aminah@example.com',
                'nomor_hp_mentor' => '083456789012',
                'file_persetujuan_mentor' => null,
                'status_aktif' => true,
            ],
            [
                'nama_mentor' => 'Rudi Wijaya, S.E.',
                'jabatan_mentor' => 'Mentor Senior PKA',
                'nomor_rekening' => '6677889900',
                'nama_bank' => 'Bank BRI',
                'atas_nama_rekening' => 'Rudi Wijaya',
                'npwp_mentor' => '04.567.890.1-234.000',
                'email_mentor' => 'rudi.wijaya@example.com',
                'nomor_hp_mentor' => '084567890123',
                'file_persetujuan_mentor' => null,
                'status_aktif' => true,
            ],
            [
                'nama_mentor' => 'Dewi Lestari, M.Hum.',
                'jabatan_mentor' => 'Spesialis Pengembangan Karir',
                'nomor_rekening' => '5544332211',
                'nama_bank' => 'Bank Mandiri',
                'atas_nama_rekening' => 'Dewi Lestari',
                'npwp_mentor' => '05.678.901.2-345.000',
                'email_mentor' => 'dewi.lestari@example.com',
                'nomor_hp_mentor' => '085678901234',
                'file_persetujuan_mentor' => null,
                'status_aktif' => true,
            ],
        ];

        foreach ($mentorData as $data) {
            Mentor::create($data);
        }
    }

}
