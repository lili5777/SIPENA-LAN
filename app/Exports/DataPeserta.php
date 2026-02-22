<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class DataPeserta implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    protected $jenisPelatihan;
    protected $angkatan;
    protected $tahun;
    protected $kategori;
    protected $wilayah;

    public function __construct($jenisPelatihan = null, $angkatan = null, $tahun = null, $kategori = null, $wilayah = null)
    {
        $this->jenisPelatihan = $jenisPelatihan;
        $this->angkatan = $angkatan;
        $this->tahun = $tahun;
        $this->kategori = $kategori;
        $this->wilayah = $wilayah;
    }

    /**
     * Fungsi helper untuk mengkonversi angka romawi ke integer
     */
    private function romanToInt($roman)
    {
        if (empty($roman)) return 0;
        
        $romans = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];
        
        $result = 0;
        foreach ($romans as $key => $value) {
            while (strpos($roman, $key) === 0) {
                $result += $value;
                $roman = substr($roman, strlen($key));
            }
        }
        return $result;
    }

    /**
     * Fungsi helper untuk ekstrak angka romawi dari nama angkatan
     */
    private function extractRomanNumeral($namaAngkatan)
    {
        if (empty($namaAngkatan)) return '';
        
        // Cari pola angka romawi (contoh: "Angkatan I", "I", "IX", dll)
        if (preg_match('/\b([IVXLCDM]+)\b/', strtoupper($namaAngkatan), $matches)) {
            return $matches[1];
        }
        return '';
    }

    public function collection()
    {
        $query = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaianPeserta',
            'peserta.kepegawaianPeserta.provinsi',
            'peserta.kepegawaianPeserta.kabupaten',
            'angkatan',
            'pesertaMentor.mentor',
            'jenisPelatihan'
        ])->where('status_pendaftaran', 'Diterima');

        // Apply filters jika ada - berdasarkan nama, bukan ID
        if ($this->jenisPelatihan) {
            $query->whereHas('jenisPelatihan', function ($q) {
                $q->where('nama_pelatihan', $this->jenisPelatihan);
            });
        }

        if ($this->angkatan) {
            $query->whereHas('angkatan', function ($q) {
                $q->where('nama_angkatan', $this->angkatan);
            });
        }

        if ($this->tahun) {
            $query->whereHas('angkatan', function ($q) {
                $q->where('tahun', $this->tahun);
            });
        }

        // =========================
        // 🔥 FILTER KATEGORI & WILAYAH (OPSIONAL)
        // =========================
        if ($this->kategori && $this->kategori !== 'SEMUA') {
            if ($this->kategori === 'PNBP') {
                $query->whereHas('angkatan', function ($q) {
                    $q->where('kategori', 'PNBP');
                });
            } elseif ($this->kategori === 'FASILITASI') {
                $query->whereHas('angkatan', function ($q) {
                    $q->where('kategori', 'FASILITASI');
                    if ($this->wilayah && trim($this->wilayah) !== '') {
                        $q->where('wilayah', 'like', '%' . trim($this->wilayah) . '%');
                    }
                });
            }
        } else {
            // Jika kategori SEMUA atau kosong, filter wilayah saja jika dipilih
            if ($this->wilayah && trim($this->wilayah) !== '') {
                $query->whereHas('angkatan', function ($q) {
                    $q->where('wilayah', 'like', '%' . trim($this->wilayah) . '%');
                });
            }
        }

        $data = $query->get();

        // =========================
        // 🔥 SORTING: Angkatan (Romawi) → NDH
        // =========================
        $sorted = $data->sort(function ($a, $b) {
            // 1. Sort berdasarkan angka romawi angkatan (terkecil dulu)
            $romanA = $this->extractRomanNumeral($a->angkatan->nama_angkatan ?? '');
            $romanB = $this->extractRomanNumeral($b->angkatan->nama_angkatan ?? '');
            
            $numA = $this->romanToInt($romanA);
            $numB = $this->romanToInt($romanB);
            
            if ($numA != $numB) {
                return $numA <=> $numB;
            }
            
            // 2. Jika angkatan sama, sort berdasarkan NDH (terkecil dulu)
            $ndhA = $a->peserta->ndh ?? 0;
            $ndhB = $b->peserta->ndh ?? 0;
            
            return $ndhA <=> $ndhB;
        });

        return $sorted->values();
    }

    public function headings(): array
    {
        return [
            'NO',
            'JENIS PELATIHAN',
            'ANGKATAN',
            'TAHUN',
            'NDH', // Kolom baru ditambahkan di sini
            'NIP/NRP',
            'NAMA',
            'JENIS KELAMIN',
            'AGAMA',
            'TEMPAT LAHIR',
            'TGL LAHIR',
            'ALAMAT ASAL',
            'ALAMAT INSTANSI',
            'INSTANSI',
            'INSTANSI DETAIL',
            'PROVINSI',
            'KABUPATEN/KOTA',
            'JABATAN',
            'NOMOR SK CPNS',
            'TANGGAL SK CPNS',
            'NOMOR SK TERAKHIR',
            'TANGGAL SK JABATAN',
            'TAHUN LULUS PKP/PIM',
            'PANGKAT',
            'GOLONGAN',
            'ESELON',
            'NOMOR HP/WA PESERTA',
            'E-MAIL PESERTA',
            'NOMOR TELEPON INSTANSI',
            'E-MAIL INSTANSI',
            'STATUS PERKAWINAN',
            'NAMA SUAMI/ISTRI',
            'PENDIDIKAN TERAKHIR',
            'BIDANG PENDIDIKAN TERAKHIR',
            'HOBI / KESUKAAN',
            'UKURAN KAOS',
            'UKURAN BAJU TAKTIKAL',
            'UKURAN CELANA TAKTIKAL',
            'MEROKOK/TIDAK MEROKOK',
            'NAMA MENTOR',
            'NIP MENTOR',
            'JABATAN MENTOR',
            'NAMA BANK & NOMOR REKENING MENTOR',
            'NPWP MENTOR',
            'GOLONGAN MENTOR',
            'PANGKAT MENTOR',
            'EMAIL MENTOR',
            'NOMOR HP MENTOR',
        ];
    }

    public function map($pendaftaran): array
    {
        static $no = 0;
        $no++;

        $peserta = $pendaftaran->peserta;
        $kepegawaian = $peserta->kepegawaianPeserta;
        $pesertaMentor = $pendaftaran->pesertaMentor->first();
        $mentor = $pesertaMentor ? $pesertaMentor->mentor : null;

        return [
            $no,
            $pendaftaran->jenisPelatihan->nama_pelatihan ?? '-',
            $pendaftaran->angkatan->nama_angkatan ?? '-',
            $pendaftaran->angkatan->tahun ?? '-',
            $peserta->ndh ?? '-', // Kolom NDH ditambahkan di sini
            $peserta->nip_nrp ?? '-',
            $peserta->nama_lengkap ?? '-',
            $peserta->jenis_kelamin ?? '-',
            $peserta->agama ?? '-',
            $peserta->tempat_lahir ?? '-',
            $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d-m-Y') : '-',
            $peserta->alamat_rumah ?? '-',
            $kepegawaian->alamat_kantor ?? '-',
            $kepegawaian->asal_instansi ?? '-',
            $kepegawaian->unit_kerja ?? '-',
            $kepegawaian->provinsi->name ?? '-',
            $kepegawaian->kabupaten->name ?? '-',
            $kepegawaian->jabatan ?? '-',
            $kepegawaian->nomor_sk_cpns ?? '-',
            $kepegawaian->tanggal_sk_cpns ? \Carbon\Carbon::parse($kepegawaian->tanggal_sk_cpns)->format('d-m-Y') : '-',
            $kepegawaian->nomor_sk_terakhir ?? '-',
            $kepegawaian->tanggal_sk_jabatan ? \Carbon\Carbon::parse($kepegawaian->tanggal_sk_jabatan)->format('d-m-Y') : '-',
            $kepegawaian->tahun_lulus_pkp_pim_iv ?? '-',
            $kepegawaian->pangkat ?? '-',
            $kepegawaian->golongan_ruang ?? '-',
            $kepegawaian->eselon ?? '-',
            $peserta->nomor_hp ?? '-',
            $peserta->email_pribadi ?? '-',
            $kepegawaian->nomor_telepon_kantor ?? '-',
            $kepegawaian->email_kantor ?? '-',
            $peserta->status_perkawinan ?? '-',
            $peserta->nama_pasangan ?? '-',
            $peserta->pendidikan_terakhir ?? '-',
            $peserta->bidang_studi ?? '-',
            $peserta->olahraga_hobi ?? '-',
            $peserta->ukuran_kaos ?? '-',
            $peserta->ukuran_training ?? '-',
            $peserta->ukuran_celana ?? '-',
            $peserta->perokok ?? '-',
            $mentor->nama_mentor ?? '-',
            $mentor->nip_mentor ?? '-',
            $mentor->jabatan_mentor ?? '-',
            $mentor->nomor_rekening ?? '-',
            $mentor->npwp_mentor ?? '-',
            $mentor->golongan ?? '-',
            $mentor->pangkat ?? '-',
            $mentor->email_mentor ?? '-',
            $mentor->nomor_hp_mentor ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT, // Kolom NDH (sekarang kolom E)
            'F' => NumberFormat::FORMAT_TEXT, // Kolom NIP/NRP (bergeser jadi kolom F)
            'S' => NumberFormat::FORMAT_TEXT, // Kolom Nomor SK Jabatan (bergeser jadi S)
            'X' => NumberFormat::FORMAT_TEXT, // Kolom Nomor HP/WA Peserta (bergeser jadi X)
            'Z' => NumberFormat::FORMAT_TEXT, // Kolom Nomor Telepon Instansi (bergeser jadi Z)
            'AO' => NumberFormat::FORMAT_TEXT, // NIP Mentor
            'AM' => NumberFormat::FORMAT_TEXT, // Kolom Nomor Rekening Mentor
            'AN' => NumberFormat::FORMAT_TEXT, // NPWP Mentor
            'AP' => NumberFormat::FORMAT_TEXT, // Kolom Nomor HP Mentor
            'AR' => NumberFormat::FORMAT_TEXT, // Kolom Nomor HP Mentor
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header kolom
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set tinggi baris header
                $sheet->getRowDimension(1)->setRowHeight(25);

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // KUNCI UTAMA: Format kolom-kolom yang berisi angka panjang sebagai TEXT
                // Disesuaikan karena ada penambahan kolom NDH
                $textColumns = ['E', 'F', 'S', 'X', 'Z', 'AO', 'AM', 'AN', 'AP', 'AR'];

                foreach ($textColumns as $col) {
                    // Set format code '@' untuk TEXT
                    $sheet->getStyle($col . '1:' . $col . $highestRow)
                        ->getNumberFormat()
                        ->setFormatCode('@');

                    // Set explicit data type untuk setiap cell
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $cell = $sheet->getCell($col . $row);
                        $cell->setValueExplicit($cell->getValue(), DataType::TYPE_STRING);
                    }
                }

                // Border untuk semua data
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Center alignment untuk kolom tertentu
                $sheet->getStyle('A1:A' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Kolom B-E (JENIS PELATIHAN, ANGKATAN, TAHUN, NDH) di-center
                $sheet->getStyle('B1:E' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Zebra striping
                for ($i = 2; $i <= $highestRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle('A' . $i . ':' . $highestColumn . $i)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F2F2F2'],
                            ],
                        ]);
                    }
                }

                // Wrap text untuk semua cell
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}