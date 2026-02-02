<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class KomposisiPeserta implements FromCollection, WithHeadings, WithStyles, WithEvents, ShouldAutoSize
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

    public function collection()
    {
        $query = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaianPeserta',
            'jenisPelatihan',
            'angkatan'
        ])->where('status_pendaftaran','Diterima');

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

        // Kelompokkan data
        $jenisKelamin = $data->groupBy(function ($item) {
            return $item->peserta->jenis_kelamin ?? 'Tidak Diketahui';
        })->map->count();

        $pendidikan = $data->groupBy(function ($item) {
            return $item->peserta->pendidikan_terakhir ?? 'Tidak Diketahui';
        })->map->count();

        $pangkatGolongan = $data->groupBy(function ($item) {
            $kepegawaian = $item->peserta->kepegawaianPeserta;
            if ($kepegawaian) {
                return ($kepegawaian->pangkat ?? '-') . ' - ' . ($kepegawaian->golongan_ruang ?? '-');
            }
            return 'Tidak Diketahui';
        })->map->count();

        $asalInstansi = $data->groupBy(function ($item) {
            return $item->peserta->kepegawaianPeserta->asal_instansi ?? 'Tidak Diketahui';
        })->map->count();

        // Format data untuk export
        $result = new Collection();

        // A. Jenis Kelamin
        $result->push(['KOMPOSISI BERDASARKAN JENIS KELAMIN', '', '']);
        $result->push(['Jenis Kelamin', 'Jumlah', 'Persentase']);
        $totalJK = $jenisKelamin->sum();
        foreach ($jenisKelamin as $jk => $jumlah) {
            $persentase = $totalJK > 0 ? round(($jumlah / $totalJK) * 100, 2) : 0;
            $result->push([$jk, $jumlah, $persentase . '%']);
        }
        $result->push(['TOTAL', $totalJK, '100%']);
        $result->push(['', '', '']); // Spacing

        // B. Pendidikan
        $result->push(['KOMPOSISI BERDASARKAN PENDIDIKAN', '', '']);
        $result->push(['Pendidikan Terakhir', 'Jumlah', 'Persentase']);
        $totalPendidikan = $pendidikan->sum();
        foreach ($pendidikan as $pend => $jumlah) {
            $persentase = $totalPendidikan > 0 ? round(($jumlah / $totalPendidikan) * 100, 2) : 0;
            $result->push([$pend, $jumlah, $persentase . '%']);
        }
        $result->push(['TOTAL', $totalPendidikan, '100%']);
        $result->push(['', '', '']); // Spacing

        // C. Pangkat/Golongan
        $result->push(['KOMPOSISI BERDASARKAN PANGKAT/GOLONGAN', '', '']);
        $result->push(['Pangkat / Golongan', 'Jumlah', 'Persentase']);
        $totalPangkat = $pangkatGolongan->sum();
        foreach ($pangkatGolongan as $pangkat => $jumlah) {
            $persentase = $totalPangkat > 0 ? round(($jumlah / $totalPangkat) * 100, 2) : 0;
            $result->push([$pangkat, $jumlah, $persentase . '%']);
        }
        $result->push(['TOTAL', $totalPangkat, '100%']);
        $result->push(['', '', '']); // Spacing

        // D. Asal Instansi
        $result->push(['KOMPOSISI BERDASARKAN ASAL INSTANSI', '', '']);
        $result->push(['Asal Instansi', 'Jumlah', 'Persentase']);
        $totalInstansi = $asalInstansi->sum();
        foreach ($asalInstansi as $instansi => $jumlah) {
            $persentase = $totalInstansi > 0 ? round(($jumlah / $totalInstansi) * 100, 2) : 0;
            $result->push([$instansi, $jumlah, $persentase . '%']);
        }
        $result->push(['TOTAL', $totalInstansi, '100%']);

        return $result;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Style untuk header section
                $currentRow = 1;
                $sections = ['A', 'B', 'C', 'D'];

                foreach ($sections as $section) {
                    // Cari baris header section
                    for ($i = $currentRow; $i <= $highestRow; $i++) {
                        $cellValue = $sheet->getCell('A' . $i)->getValue();

                        if (strpos($cellValue, 'KOMPOSISI BERDASARKAN') !== false) {
                            // Style header section
                            $sheet->mergeCells('A' . $i . ':C' . $i);
                            $sheet->getStyle('A' . $i)->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'size' => 12,
                                    'color' => ['rgb' => 'FFFFFF'],
                                ],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => '1a3a6c'],
                                ],
                                'alignment' => [
                                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);
                            $sheet->getRowDimension($i)->setRowHeight(25);

                            // Style sub-header (baris berikutnya)
                            $sheet->getStyle('A' . ($i + 1) . ':C' . ($i + 1))->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'color' => ['rgb' => 'FFFFFF'],
                                ],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => '4472C4'],
                                ],
                                'alignment' => [
                                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);

                            $currentRow = $i + 2;
                            break;
                        }
                    }
                }

                // Style untuk baris TOTAL
                for ($i = 1; $i <= $highestRow; $i++) {
                    $cellValue = $sheet->getCell('A' . $i)->getValue();
                    if ($cellValue === 'TOTAL') {
                        $sheet->getStyle('A' . $i . ':C' . $i)->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF'],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '2c5282'],
                            ],
                        ]);
                    }
                }

                // Border untuk semua cell yang ada data
                $sheet->getStyle('A1:C' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Center alignment untuk kolom Jumlah dan Persentase
                $sheet->getStyle('B1:C' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Wrap text
                $sheet->getStyle('A1:C' . $highestRow)->getAlignment()->setWrapText(true);

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(40);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(15);
            },
        ];
    }
}
