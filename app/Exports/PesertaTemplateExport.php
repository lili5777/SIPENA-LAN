<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class PesertaTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    public function array(): array
    {
        return [
            // ================= CONTOH 1 - PNBP (wilayah kosong) =================
            [
                'PKA',           // JENIS_PELATIHAN
                'Angkatan I',    // ANGKATAN
                '2026',          // TAHUN_ANGKATAN
                'PNBP',          // KATEGORI (BARU)
                '',              // WILAYAH (BARU) - kosong untuk PNBP
                '197905012005011001',  // NIP_NRP
                'Ahmad Fauzi, S.STP',  // NAMA_LENGKAP
                'Laki-laki',     // JENIS_KELAMIN
                'Ahmad',         // NAMA_PANGGILAN
                'Islam',         // AGAMA
                'Makassar',      // TEMPAT_LAHIR
                '01-05-2004',    // TANGGAL_LAHIR
                'Jl. Perintis Kemerdekaan No. 10', // ALAMAT_RUMAH
                'ahmad.fauzi@email.com', // EMAIL_PRIBADI
                '081111111111',  // NOMOR_HP
                'S1',            // PENDIDIKAN_TERAKHIR
                'Administrasi Publik', // BIDANG_STUDI
                'Manajemen Pemerintahan', // BIDANG_KEAHLIAN
                'Menikah',       // STATUS_PERKAWINAN
                'Nur Aisyah',    // NAMA_PASANGAN
                'Jogging',       // OLAHRAGA_HOBI
                'Tidak',         // PEROKOK
                'L',             // UKURAN_KAOS
                'L',             // UKURAN_CELANA
                'L',             // UKURAN_TRAINING
                'Sehat',         // KONDISI_PESERTA
                'Pemerintah Provinsi Sulsel', // ASAL_INSTANSI
                'Biro Pemerintahan', // UNIT_KERJA
                'Sulawesi Selatan', // PROVINSI
                'Kota Makassar', // KABUPATEN_KOTA
                'Jl. Urip Sumoharjo No. 5', // ALAMAT_KANTOR
                '0411-123456',   // NOMOR_TELEPON_KANTOR
                'ahmad.fauzi@go.id', // EMAIL_KANTOR
                'Kepala Bagian', // JABATAN
                'Pembina',       // PANGKAT
                'IV/a',          // GOLONGAN_RUANG
                'II.a',          // ESELON
                '01-05-2025',    // TANGGAL_SK_JABATAN
                '821.2/CPNS/2005', // NOMOR_SK_CPNS
                '821.2/SK/2024', // NOMOR_SK_TERAKHIR
                '2005-03-01',    // TANGGAL_SK_CPNS
                '2022',          // TAHUN_LULUS_PKP_PIM_IV
            ],

            // ================= CONTOH 2 - FASILITASI (dengan wilayah) =================
            [
                'LATSAR',
                'Angkatan I',
                '2026',
                'FASILITASI',    // KATEGORI (BARU)
                'Jawa Barat',    // WILAYAH (BARU) - wajib untuk FASILITASI
                '199001122020121001',
                'Siti Rahmawati, S.IP',
                'Perempuan',
                'Siti',
                'Islam',
                'Bandung',
                '01-05-2024',
                'Jl. Asia Afrika No. 20',
                'siti.rahmawati@email.com',
                '082222222222',
                'S1',
                'Ilmu Pemerintahan',
                'Administrasi Negara',
                'Belum Menikah',
                '',
                'Yoga',
                'Tidak',
                'M',
                'M',
                'M',
                'Sehat',
                'Pemerintah Kota Bandung',
                'Bagian Umum',
                'Jawa Barat',
                'Kabupaten Bandung',
                'Jl. Wastukencana No. 2',
                '022-7654321',
                'siti.rahmawati@bandung.go.id',
                'Analis Kebijakan',
                'Penata Muda',
                'III/a',
                '',
                '01-05-2024',
                '800/CPNS/2020',
                '800/SK/2024',
                '2020-12-01',
                '',
            ],

            // ================= CONTOH 3 - PNBP lagi =================
            [
                'PKP',
                'Angkatan I',
                '2026',
                'PNBP',          // KATEGORI (BARU)
                '',              // WILAYAH (BARU) - kosong untuk PNBP
                '198512312010011002',
                'Rizal Mahendra, S.Kom',
                'Laki-laki',
                'Rizal',
                'Islam',
                'Yogyakarta',
                '11-11-1979',
                'Jl. Kaliurang Km 5',
                'rizal@email.com',
                '083333333333',
                'S1',
                'Teknik Informatika',
                'Sistem Informasi',
                'Menikah',
                'Dewi Lestari',
                'Bersepeda',
                'Tidak',
                'L',
                'L',
                'L',
                'Sehat',
                'Kementerian Kominfo',
                'Pusat Data Nasional',
                'Bali',
                'Kota Denpasar',
                'Jl. Magelang Km 8',
                '0274-123456',
                'rizal@kominfo.go.id',
                'Pranata Komputer',
                'Penata',
                'III/c',
                '',
                '11-11-1978',
                '197/CPNS/2010',
                '197/SK/2023',
                '2010-02-01',
                '2021',
            ],

            // ================= CONTOH 4 - FASILITASI dengan wilayah =================
            [
                'PKN TK II',
                'Angkatan I',
                '2026',
                'FASILITASI',    // KATEGORI (BARU)
                'DKI Jakarta',   // WILAYAH (BARU) - wajib untuk FASILITASI
                '197811112003031004',
                'Dedi Kurniawan, M.AP',
                'Laki-laki',
                'Dedi',
                'Islam',
                'Surabaya',
                '11-11-1978',
                'Jl. Raya Darmo No. 15',
                'dedi.kurniawan@email.com',
                '084444444444',
                'S2',
                'Administrasi Negara',
                'Kepemimpinan',
                'Menikah',
                'Ratna Sari',
                'Renang',
                'Tidak',
                'XL',
                'XL',
                'XL',
                'Sehat',
                'Pemerintah Provinsi Jawa Timur',
                'Badan Kepegawaian Daerah',
                'Jawa Timur',
                'Kabupaten Jember',
                'Jl. Pahlawan No. 1',
                '031-987654',
                'dedi.kurniawan@jatimprov.go.id',
                'Kepala Bidang',
                'Pembina Utama Muda',
                'IV/c',
                'II',
                '15-09-2021',
                '821/CPNS/2003',
                '821/SK/2024',
                '2003-04-01',
                '2020',
            ],

            // ================= BARIS KOSONG =================
            array_fill(0, 42, ''), // 42 kolom total (dari 40 + 2 kolom baru)
        ];
    }

    public function headings(): array
    {
        return [
            'JENIS_PELATIHAN',           // Wajib
            'ANGKATAN',                  // Wajib
            'TAHUN_ANGKATAN',            // Wajib
            'KATEGORI',                  // Wajib (BARU) - PNBP atau FASILITASI
            'WILAYAH',                   // Opsional, wajib jika FASILITASI (BARU)
            'NIP_NRP',                   // Wajib
            'NAMA_LENGKAP',              // Wajib
            'JENIS_KELAMIN',             // Wajib
            'NAMA_PANGGILAN',            // Opsional
            'AGAMA',                     // Opsional
            'TEMPAT_LAHIR',              // Opsional
            'TANGGAL_LAHIR',             // Opsional
            'ALAMAT_RUMAH',              // Opsional
            'EMAIL_PRIBADI',             // Opsional
            'NOMOR_HP',                  // Opsional
            'PENDIDIKAN_TERAKHIR',       // Opsional
            'BIDANG_STUDI',              // Opsional
            'BIDANG_KEAHLIAN',           // Opsional
            'STATUS_PERKAWINAN',         // Opsional
            'NAMA_PASANGAN',             // Opsional
            'OLAHRAGA_HOBI',             // Opsional
            'PEROKOK',                   // Opsional
            'UKURAN_KAOS',               // Opsional
            'UKURAN_CELANA',             // Opsional
            'UKURAN_TRAINING',           // Opsional
            'KONDISI_PESERTA',           // Opsional
            'ASAL_INSTANSI',             // Opsional
            'UNIT_KERJA',                // Opsional
            'PROVINSI',                  // Opsional
            'KABUPATEN_KOTA',            // Opsional
            'ALAMAT_KANTOR',             // Opsional
            'NOMOR_TELEPON_KANTOR',      // Opsional
            'EMAIL_KANTOR',              // Opsional
            'JABATAN',                   // Opsional
            'PANGKAT',                   // Opsional
            'GOLONGAN_RUANG',            // Opsional
            'ESELON',                    // Opsional
            'TANGGAL_SK_JABATAN',        // Opsional
            'NOMOR_SK_CPNS',             // Opsional
            'NOMOR_SK_TERAKHIR',         // Opsional
            'TANGGAL_SK_CPNS',           // Opsional
            'TAHUN_LULUS_PKP_PIM_IV',    // Opsional
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:AQ1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1a3a6c'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Warna berbeda untuk kolom wajib (A-G) - sekarang 7 kolom wajib termasuk kategori
        $sheet->getStyle('A1:G1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ef4444'], // Merah untuk wajib
            ],
        ]);

        // Kolom E (WILAYAH) berwarna kuning karena conditional required
        $sheet->getStyle('E1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f59e0b'], // Kuning untuk conditional
            ],
        ]);

        // Format kolom NIP/NRP sebagai TEXT agar tidak jadi scientific notation
        $sheet->getStyle('F:F')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // Format TANGGAL_LAHIR
        $sheet->getStyle('L:L')->getNumberFormat()->setFormatCode('dd-mm-yyyy');

        // Format TANGGAL_SK_JABATAN
        $sheet->getStyle('AL:AL')->getNumberFormat()->setFormatCode('dd-mm-yyyy');

        // Format TANGGAL_SK_CPNS
        $sheet->getStyle('AO:AO')->getNumberFormat()->setFormatCode('dd-mm-yyyy');

        // Format kolom NOMOR_HP sebagai TEXT
        $sheet->getStyle('O:O')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // Format kolom NOMOR_TELEPON_KANTOR sebagai TEXT
        $sheet->getStyle('AF:AF')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // Auto height untuk header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Freeze pane di header
        $sheet->freezePane('A2');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,  // JENIS_PELATIHAN
            'B' => 15,  // ANGKATAN
            'C' => 15,  // TAHUN_ANGKATAN
            'D' => 15,  // KATEGORI (BARU)
            'E' => 20,  // WILAYAH (BARU)
            'F' => 22,  // NIP_NRP
            'G' => 30,  // NAMA_LENGKAP
            'H' => 15,  // JENIS_KELAMIN
            'I' => 20,  // NAMA_PANGGILAN
            'J' => 15,  // AGAMA
            'K' => 20,  // TEMPAT_LAHIR
            'L' => 15,  // TANGGAL_LAHIR
            'M' => 35,  // ALAMAT_RUMAH
            'N' => 25,  // EMAIL_PRIBADI
            'O' => 17,  // NOMOR_HP
            'P' => 20,  // PENDIDIKAN_TERAKHIR
            'Q' => 25,  // BIDANG_STUDI
            'R' => 25,  // BIDANG_KEAHLIAN
            'S' => 20,  // STATUS_PERKAWINAN
            'T' => 30,  // NAMA_PASANGAN
            'U' => 20,  // OLAHRAGA_HOBI
            'V' => 12,  // PEROKOK
            'W' => 15,  // UKURAN_KAOS
            'X' => 15,  // UKURAN_CELANA
            'Y' => 15,  // UKURAN_TRAINING
            'Z' => 20,  // KONDISI_PESERTA
            'AA' => 35, // ASAL_INSTANSI
            'AB' => 25, // UNIT_KERJA
            'AC' => 20, // PROVINSI
            'AD' => 20, // KABUPATEN_KOTA
            'AE' => 35, // ALAMAT_KANTOR
            'AF' => 20, // NOMOR_TELEPON_KANTOR
            'AG' => 30, // EMAIL_KANTOR
            'AH' => 25, // JABATAN
            'AI' => 20, // PANGKAT
            'AJ' => 15, // GOLONGAN_RUANG
            'AK' => 12, // ESELON
            'AL' => 20, // TANGGAL_SK_JABATAN
            'AM' => 20, // NOMOR_SK_CPNS
            'AN' => 20, // NOMOR_SK_TERAKHIR
            'AO' => 20, // TANGGAL_SK_CPNS
            'AP' => 25, // TAHUN_LULUS_PKP_PIM_IV
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set cell NIP sebagai string explicit untuk mencegah scientific notation
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    // Set NIP_NRP sebagai text
                    $nipCell = $sheet->getCell('F' . $row);
                    $nipValue = $nipCell->getValue();
                    if (!empty($nipValue)) {
                        $nipCell->setValueExplicit($nipValue, DataType::TYPE_STRING);
                    }

                    // Set NOMOR_HP sebagai text
                    $hpCell = $sheet->getCell('O' . $row);
                    $hpValue = $hpCell->getValue();
                    if (!empty($hpValue)) {
                        $hpCell->setValueExplicit($hpValue, DataType::TYPE_STRING);
                    }

                    // Set NOMOR_TELEPON_KANTOR sebagai text
                    $telpCell = $sheet->getCell('AF' . $row);
                    $telpValue = $telpCell->getValue();
                    if (!empty($telpValue)) {
                        $telpCell->setValueExplicit($telpValue, DataType::TYPE_STRING);
                    }
                }
            },
        ];
    }
}