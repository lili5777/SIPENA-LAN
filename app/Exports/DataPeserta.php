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
            'pesertaMentor.mentor',
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
            'NO',
            'JENIS PELATIHAN',
            'ANGKATAN',
            'TAHUN',
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
            'NOMOR SK JABATAN',
            'TANGGAL SK JABATAN',
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
            'JABATAN MENTOR',
            'NOMOR REKENING MENTOR',
            'NPWP MENTOR',
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
            $peserta->nip_nrp ?? '-', // Tanpa prefix '
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
            $peserta->perokok ? 'MEROKOK' : 'TIDAK MEROKOK',
            $mentor->nama_mentor ?? '-',
            $mentor->jabatan_mentor ?? '-',
            $mentor->nomor_rekening ?? '-',
            $mentor->npwp_mentor ?? '-',
            $mentor->email_mentor ?? '-',
            $mentor->nomor_hp_mentor ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT, // Kolom NIP/NRP (kolom E)
            'R' => NumberFormat::FORMAT_TEXT, // Kolom Nomor SK Jabatan
            'W' => NumberFormat::FORMAT_TEXT, // Kolom Nomor HP/WA Peserta (kolom W)
            'Y' => NumberFormat::FORMAT_TEXT, // Kolom Nomor Telepon Instansi
            'AL' => NumberFormat::FORMAT_TEXT, // Kolom Nomor Rekening Mentor
            'AM' => NumberFormat::FORMAT_TEXT, // Kolom NPWP Mentor
            'AO' => NumberFormat::FORMAT_TEXT, // Kolom Nomor HP Mentor
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
                $textColumns = ['E', 'R', 'W', 'Y', 'AL', 'AM', 'AO'];

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

                $sheet->getStyle('B1:D' . $highestRow)->applyFromArray([
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
