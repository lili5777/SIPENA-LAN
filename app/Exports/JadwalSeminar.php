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

class JadwalSeminar implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithEvents, 
    ShouldAutoSize, 
    WithColumnFormatting
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
            'angkatan',
            'jenisPelatihan',
            'pesertaMentor.mentor'
        ])->where('status_pendaftaran', 'Diterima');

        // Apply filters
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

        // Filter kategori & wilayah
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
            if ($this->wilayah && trim($this->wilayah) !== '') {
                $query->whereHas('angkatan', function ($q) {
                    $q->where('wilayah', 'like', '%' . trim($this->wilayah) . '%');
                });
            }
        }

        $data = $query->get();

        // Sorting: Angkatan (Romawi) → NDH
        $sorted = $data->sort(function ($a, $b) {
            // 1. Sort berdasarkan angka romawi angkatan
            $romanA = $this->extractRomanNumeral($a->angkatan->nama_angkatan ?? '');
            $romanB = $this->extractRomanNumeral($b->angkatan->nama_angkatan ?? '');
            
            $numA = $this->romanToInt($romanA);
            $numB = $this->romanToInt($romanB);
            
            if ($numA != $numB) {
                return $numA <=> $numB;
            }
            
            // 2. Sort berdasarkan NDH
            $ndhA = $a->peserta->ndh ?? 999;
            $ndhB = $b->peserta->ndh ?? 999;
            
            return $ndhA <=> $ndhB;
        });

        return $sorted->values();
    }

    public function headings(): array
    {
        return [
            'NO',
            'WAKTU',
            'NDH',
            'NAMA',
            'NIP',
            'JABATAN',
            'INSTANSI',
            'NAMA MENTOR',
            'JABATAN MENTOR',
            'COACH'
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
            '', // WAKTU - kosong
            $peserta->ndh ?? '-',
            $peserta->nama_lengkap ?? '-',
            $peserta->nip_nrp ?? '-',
            $kepegawaian->jabatan ?? '-',
            $kepegawaian->asal_instansi ?? '-',
            $mentor->nama_mentor ?? '-',
            $mentor->jabatan_mentor ?? '-',
            '' // COACH - kosong
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // NDH
            'E' => NumberFormat::FORMAT_TEXT, // NIP
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header kolom
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'BFC9D1'],
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

                // Format kolom NDH dan NIP sebagai TEXT
                $textColumns = ['C', 'E'];

                foreach ($textColumns as $col) {
                    $sheet->getStyle($col . '1:' . $col . $highestRow)
                        ->getNumberFormat()
                        ->setFormatCode('@');

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

                // Center alignment untuk kolom NO, WAKTU, NDH, COACH
                $centerColumns = ['A', 'B', 'C', 'J'];
                foreach ($centerColumns as $col) {
                    $sheet->getStyle($col . '1:' . $col . $highestRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }

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
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getAlignment()
                    ->setWrapText(true);
            },
        ];
    }
}