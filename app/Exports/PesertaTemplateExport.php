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
            // ================= CONTOH 1 =================
            [
                'PKA',
                'Angkatan I',
                '2026',
                '197905012005011001',
                'Ahmad Fauzi, S.STP',
                'Laki-laki',
                'Ahmad',
                'Islam',
                'Makassar',
                '1979-05-01',
                'Jl. Perintis Kemerdekaan No. 10',
                'ahmad.fauzi@email.com',
                '081111111111',
                'S1',
                'Administrasi Publik',
                'Manajemen Pemerintahan',
                'Menikah',
                'Nur Aisyah',
                'Jogging',
                'Tidak',
                'L',
                'L',
                'L',
                'Sehat',
                'Pemerintah Provinsi Sulsel',
                'Biro Pemerintahan',
                'Sulawesi Selatan',
                'Kota Makassar',
                'Jl. Urip Sumoharjo No. 5',
                '0411-123456',
                'ahmad.fauzi@go.id',
                'Kepala Bagian',
                'Pembina',
                'IV/a',
                'II.a',
                '2023-02-10',
                '821.2/CPNS/2005',
                '821.2/SK/2024',
                '2005-03-01',
                '2022',
            ],

            // ================= CONTOH 2 =================
            [
                'LATSAR',
                'Angkatan I',
                '2026',
                '199001122020121001',
                'Siti Rahmawati, S.IP',
                'Perempuan',
                'Siti',
                'Islam',
                'Bandung',
                '1990-01-12',
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
                '2024-01-05',
                '800/CPNS/2020',
                '800/SK/2024',
                '2020-12-01',
                '',
            ],

            // ================= CONTOH 3 =================
            [
                'PKP',
                'Angkatan I',
                '2026',
                '198512312010011002',
                'Rizal Mahendra, S.Kom',
                'Laki-laki',
                'Rizal',
                'Islam',
                'Yogyakarta',
                '1985-12-31',
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
                '2022-06-20',
                '197/CPNS/2010',
                '197/SK/2023',
                '2010-02-01',
                '2021',
            ],

            // ================= CONTOH 4 =================
            [
                'PKN TK II',
                'Angkatan I',
                '2026',
                '197811112003031004',
                'Dedi Kurniawan, M.AP',
                'Laki-laki',
                'Dedi',
                'Islam',
                'Surabaya',
                '1978-11-11',
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
                '2021-09-15',
                '821/CPNS/2003',
                '821/SK/2024',
                '2003-04-01',
                '2020',
            ],

            // ================= BARIS KOSONG =================
            array_fill(0, 40, ''),
        ];
    }


    public function headings(): array
    {
        return [
            'JENIS_PELATIHAN',           // Wajib
            'ANGKATAN',                  // Wajib
            'TAHUN_ANGKATAN',            // Wajib
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
        $sheet->getStyle('A1:AN1')->applyFromArray([
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

        // Warna berbeda untuk kolom wajib (A-F)
        $sheet->getStyle('A1:F1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ef4444'], // Merah untuk wajib
            ],
        ]);

        // PERBAIKAN: Format kolom NIP/NRP sebagai TEXT agar tidak jadi scientific notation
        $sheet->getStyle('D:D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // Format kolom NOMOR_HP sebagai TEXT
        $sheet->getStyle('M:M')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // Format kolom NOMOR_TELEPON_KANTOR sebagai TEXT
        $sheet->getStyle('AD:AD')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

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
            'D' => 22,  // NIP_NRP (diperlebar untuk angka panjang)
            'E' => 30,  // NAMA_LENGKAP
            'F' => 15,  // JENIS_KELAMIN
            'G' => 20,  // NAMA_PANGGILAN
            'H' => 15,  // AGAMA
            'I' => 20,  // TEMPAT_LAHIR
            'J' => 15,  // TANGGAL_LAHIR
            'K' => 35,  // ALAMAT_RUMAH
            'L' => 25,  // EMAIL_PRIBADI
            'M' => 17,  // NOMOR_HP
            'N' => 20,  // PENDIDIKAN_TERAKHIR
            'O' => 25,  // BIDANG_STUDI
            'P' => 25,  // BIDANG_KEAHLIAN
            'Q' => 20,  // STATUS_PERKAWINAN
            'R' => 30,  // NAMA_PASANGAN
            'S' => 20,  // OLAHRAGA_HOBI
            'T' => 12,  // PEROKOK
            'U' => 15,  // UKURAN_KAOS
            'V' => 15,  // UKURAN_CELANA
            'W' => 15,  // UKURAN_TRAINING
            'X' => 20,  // KONDISI_PESERTA
            'Y' => 35,  // ASAL_INSTANSI
            'Z' => 25,  // UNIT_KERJA
            'AA' => 20, // PROVINSI
            'AB' => 20, // KABUPATEN_KOTA
            'AC' => 35, // ALAMAT_KANTOR
            'AD' => 20, // NOMOR_TELEPON_KANTOR
            'AE' => 30, // EMAIL_KANTOR
            'AF' => 25, // JABATAN
            'AG' => 20, // PANGKAT
            'AH' => 15, // GOLONGAN_RUANG
            'AI' => 12, // ESELON
            'AJ' => 20, // TANGGAL_SK_JABATAN
            'AK' => 20, // NOMOR_SK_CPNS
            'AL' => 20, // NOMOR_SK_TERAKHIR
            'AM' => 20, // TANGGAL_SK_CPNS
            'AN' => 25, // TAHUN_LULUS_PKP_PIM_IV
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // PERBAIKAN: Set cell NIP sebagai string explicit untuk mencegah scientific notation
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    // Set NIP_NRP sebagai text
                    $nipCell = $sheet->getCell('D' . $row);
                    $nipValue = $nipCell->getValue();
                    if (!empty($nipValue)) {
                        $nipCell->setValueExplicit($nipValue, DataType::TYPE_STRING);
                    }

                    // Set NOMOR_HP sebagai text
                    $hpCell = $sheet->getCell('M' . $row);
                    $hpValue = $hpCell->getValue();
                    if (!empty($hpValue)) {
                        $hpCell->setValueExplicit($hpValue, DataType::TYPE_STRING);
                    }

                    // Set NOMOR_TELEPON_KANTOR sebagai text
                    $telpCell = $sheet->getCell('AD' . $row);
                    $telpValue = $telpCell->getValue();
                    if (!empty($telpValue)) {
                        $telpCell->setValueExplicit($telpValue, DataType::TYPE_STRING);
                    }
                }
            },
        ];
    }
}


