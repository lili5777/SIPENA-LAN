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

class DataPesertaSmartBangkom implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    protected $jenisPelatihan;
    protected $angkatan;
    protected $tahun;

    public function __construct($jenisPelatihan = null, $angkatan = null, $tahun = null)
    {
        $this->jenisPelatihan = $jenisPelatihan;
        $this->angkatan = $angkatan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaianPeserta',
            'peserta.kepegawaianPeserta.provinsi',
            'peserta.kepegawaianPeserta.kabupaten',
            'angkatan',
            'jenisPelatihan'
        ]);

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

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'nama',
            'no_identitas',
            'jenis_kelamin',
            'agama',
            'tempat_lahir',
            'tgl_lahir',
            'email',
            'no_hp / telp kantor',
            'jenis_peserta',
            'gol',
            'pangkat',
            'jabatan',
            'pola_penyelenggaraan',
            'sumber_anggaran',
            'Instansi',
            'Alamat Instansi',
        ];
    }

    public function map($pendaftaran): array
    {
        $peserta = $pendaftaran->peserta;
        $kepegawaian = $peserta->kepegawaianPeserta;

        return [
            $peserta->nama_lengkap ?? '',
            $peserta->nip_nrp ?? '', // Tanpa prefix '
            $peserta->jenis_kelamin ?? '',
            $peserta->agama ?? '',
            $peserta->tempat_lahir ?? '',
            $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d-m-Y') : '',
            $peserta->email_pribadi ?? '',
            $peserta->nomor_hp ?? $kepegawaian->nomor_telepon_kantor ?? '',
            'PNS',
            $kepegawaian->golongan_ruang ?? '',
            $kepegawaian->pangkat ?? '',
            $kepegawaian->jabatan ?? '',
            'PNBP',
            'APBD',
            $kepegawaian->asal_instansi ?? '',
            $kepegawaian->alamat_kantor ?? '',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // Kolom no_identitas
            'H' => NumberFormat::FORMAT_TEXT, // Kolom no_hp juga sebaiknya text
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
                    'startColor' => ['rgb' => 'FFFFFF'],
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

                // KUNCI UTAMA: Format kolom B (no_identitas) sebagai TEXT dengan format code '@'
                // Format '@' adalah format code Excel untuk TEXT
                $sheet->getStyle('B1:B' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('@');

                // Format kolom H (no_hp) juga sebagai TEXT
                $sheet->getStyle('H1:H' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('@');

                // Alternatif: Set explicit data type untuk setiap cell di kolom B
                for ($row = 2; $row <= $highestRow; $row++) {
                    $cell = $sheet->getCell('B' . $row);
                    $cell->setValueExplicit($cell->getValue(), DataType::TYPE_STRING);

                    $cellH = $sheet->getCell('H' . $row);
                    $cellH->setValueExplicit($cellH->getValue(), DataType::TYPE_STRING);
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
                $sheet->getStyle('C2:C' . $highestRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle('D2:D' . $highestRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle('I2:K' . $highestRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Fill semua baris data dengan warna #FBE4D5 (kecuali header)
                $sheet->getStyle('A2:' . $highestColumn . $highestRow)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FBE4D5'],
                    ],
                ]);

                // Wrap text untuk semua cell
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
