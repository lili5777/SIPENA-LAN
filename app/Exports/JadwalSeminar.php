<?php

namespace App\Exports;

use App\Models\Kelompok;
use App\Models\PesertaMentor;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class JadwalSeminar implements WithEvents
{
    use Exportable;

    protected $jenisPelatihan;
    protected $angkatan;
    protected $tahun;
    protected $kategori;
    protected $wilayah;

    const START_TIME   = '08:30';
    const DURATION_MIN = 45;
    const BREAK_START  = '12:15';
    const BREAK_END    = '13:00';

    public function __construct($jenisPelatihan = null, $angkatan = null, $tahun = null, $kategori = null, $wilayah = null)
    {
        $this->jenisPelatihan = $jenisPelatihan;
        $this->angkatan       = $angkatan;
        $this->tahun          = $tahun;
        $this->kategori       = $kategori;
        $this->wilayah        = $wilayah;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->buildSheet($event->sheet->getDelegate());
            },
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────

    private function romanToInt(string $roman): int
    {
        $map    = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100, 'D' => 500, 'M' => 1000];
        $result = 0;
        $prev   = 0;
        foreach (array_reverse(str_split(strtoupper(trim($roman)))) as $char) {
            $val    = $map[$char] ?? 0;
            $result += $val < $prev ? -$val : $val;
            $prev   = $val;
        }
        return $result ?: 9999;
    }

    private function toMinutes(string $time): int
    {
        [$h, $m] = explode(':', $time);
        return (int)$h * 60 + (int)$m;
    }

    private function addMinutes(string $time, int $minutes): string
    {
        $total = $this->toMinutes($time) + $minutes;
        return sprintf('%02d:%02d', intdiv($total, 60), $total % 60);
    }

    private function willHitBreak(string $startTime): bool
    {
        $start = $this->toMinutes($startTime);
        $end   = $start + self::DURATION_MIN;
        $brkS  = $this->toMinutes(self::BREAK_START);
        return $start < $brkS && $end > $brkS;
    }

    private function isBreakTime(string $time): bool
    {
        $t    = $this->toMinutes($time);
        $brkS = $this->toMinutes(self::BREAK_START);
        $brkE = $this->toMinutes(self::BREAK_END);
        return $t >= $brkS && $t < $brkE;
    }

    // ── Build sheet ──────────────────────────────────────────────────

    private function buildSheet(Worksheet $sheet): void
    {
        // 1. Query kelompok
        $q = Kelompok::with([
            'jenisPelatihan', 'angkatan', 'coach', 'mentor',
            'peserta.kepegawaian',
        ]);

        if ($this->jenisPelatihan) {
            $q->whereHas('jenisPelatihan', fn($x) => $x->where('nama_pelatihan', $this->jenisPelatihan));
        }
        if ($this->angkatan) {
            $q->whereHas('angkatan', fn($x) => $x->where('nama_angkatan', $this->angkatan));
        }
        if ($this->tahun) {
            $q->where('tahun', $this->tahun);
        }
        if ($this->kategori === 'PNBP') {
            $q->whereHas('angkatan', fn($x) => $x->where('kategori', 'PNBP'));
        } elseif ($this->kategori === 'FASILITASI') {
            $q->whereHas('angkatan', function ($x) {
                $x->where('kategori', 'FASILITASI');
                if ($this->wilayah) {
                    $x->where('wilayah', 'like', '%' . trim($this->wilayah) . '%');
                }
            });
        }

        $kelompokList = $q->get()->sortBy(function ($k) {
            preg_match('/([IVXLCDM]+)$/i', trim($k->angkatan->nama_angkatan ?? ''), $m);
            $ord = $m ? $this->romanToInt($m[1]) : 9999;
            return sprintf('%05d_%s', $ord, $k->nama_kelompok);
        })->values();

        if ($kelompokList->isEmpty()) {
            $sheet->setCellValue('A1', 'Tidak ada data kelompok ditemukan.');
            return;
        }

        // 2. Info dari kelompok pertama
        $first        = $kelompokList->first();
        $namaAngkatan = strtoupper($first->angkatan->nama_angkatan ?? '');
        $namaJenis    = strtoupper($first->jenisPelatihan->nama_pelatihan ?? '');
        $tahunLabel   = $this->tahun ?? ($first->tahun ?? date('Y'));

        $sheet->getParent()->getDefaultStyle()->getFont()
            ->setName('Times New Roman')->setSize(10);

        // 3. Header dokumen
        $row = 1;
        foreach ([
            ['JADWAL SEMINAR AKTUALISASI', 12, true],
            ["PESERTA {$namaJenis} {$namaAngkatan}", 11, true],
            ['PUSAT PEMBELAJARAN DAN STRATEGI KEBIJAKAN MANAJEMEN PEMERINTAHAN', 10, true],
            [(string)$tahunLabel, 10, false],
        ] as [$text, $size, $bold]) {
            $sheet->mergeCells("A{$row}:J{$row}");
            $sheet->setCellValue("A{$row}", $text);
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font'      => ['bold' => $bold, 'size' => $size, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(22);
            $row++;
        }

        $row++; // 1 baris kosong

        // 4. Loop kelompok
        foreach ($kelompokList as $kelompok) {
            $namaCoach    = $kelompok->coach?->nama ?? '';
            $jabatanCoach = $kelompok->coach?->jabatan ?? '';
            $coachLabel   = $namaCoach;

            $namaMentorKelompok = $kelompok->mentor?->nama_mentor ?? '';

            $pesertaList = $kelompok->peserta
                ->sortBy(fn($p) => $p->ndh ?? 9999)
                ->values();

            if ($pesertaList->isEmpty()) {
                continue;
            }

            // ── 4a. Baris nama kelompok (A:I saja, J nanti di-merge dari header) ──
            $sheet->mergeCells("A{$row}:I{$row}");
            $sheet->setCellValue("A{$row}", strtoupper($kelompok->nama_kelompok) . ' : ' . strtoupper($namaCoach));
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
            // J di baris nama kelompok — biarkan kosong, akan masuk dalam merge coach
            $sheet->setCellValue("J{$row}", '');
            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;

            // ── 4b. Header tabel ──
            $headerRow = $row; // ← coach merge MULAI dari sini
            $headers   = ['NO', 'WAKTU', 'NDH', 'NAMA', 'NIP', 'JABATAN', 'INSTANSI', 'NAMA MENTOR', 'JABATAN MENTOR', 'COACH'];
            foreach ($headers as $col => $h) {
                $colLetter = chr(65 + $col);
                $sheet->setCellValue("{$colLetter}{$row}", $h);
            }
            $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;

            // ── 4c. Data peserta ──
            $currentTime = self::START_TIME;
            $no          = 1;
            $breakRows   = []; // catat baris istirahat agar tidak bentrok dengan merge coach

            foreach ($pesertaList as $peserta) {
                // Cek istirahat
                if ($this->willHitBreak($currentTime)) {
                    $breakRows[] = $row;
                    // Merge full A:J agar tidak bentrok dengan merge coach di J
                    $sheet->mergeCells("A{$row}:J{$row}");
                    $sheet->setCellValue("A{$row}", 'ISTIRAHAT');
                    $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
                    ]);
                    $sheet->getRowDimension($row)->setRowHeight(20);
                    $row++;
                    $currentTime = self::BREAK_END;
                }

                while ($this->isBreakTime($currentTime)) {
                    $currentTime = self::BREAK_END;
                }

                $endTime  = $this->addMinutes($currentTime, self::DURATION_MIN);
                $timeSlot = "{$currentTime} - {$endTime}";

                $pm = PesertaMentor::whereHas('pendaftaran', function ($q) use ($peserta, $kelompok) {
                    $q->where('id_peserta', $peserta->id)
                      ->where('id_angkatan', $kelompok->id_angkatan);
                })->with('mentor')->first();

                $namaMentor    = $pm?->mentor?->nama_mentor ?? '';
                $jabatanMentor = $pm?->mentor?->jabatan_mentor ?? '';
                $kepeg = $peserta->kepegawaian;

                $rowData = [
                    'A' => $no,
                    'B' => $timeSlot,
                    'C' => $peserta->ndh ?? '',
                    'D' => strtoupper($peserta->nama_lengkap ?? ''),
                    'E' => $peserta->nip_nrp ?? '',
                    'F' => $kepeg?->jabatan ?? '',
                    'G' => $kepeg?->asal_instansi ?? '',
                    'H' => $namaMentor,
                    'I' => $jabatanMentor,
                    'J' => '', // akan di-merge
                ];

                foreach ($rowData as $col => $val) {
                    if ($col === 'E') {
                        $sheet->getCell("{$col}{$row}")->setValueExplicit($val, DataType::TYPE_STRING);
                    } else {
                        $sheet->setCellValue("{$col}{$row}", $val);
                    }
                }

                $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                    'font'      => ['size' => 10, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                foreach (['D', 'F', 'G', 'H', 'I'] as $c) {
                    $sheet->getStyle("{$c}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }

                $sheet->getRowDimension($row)->setRowHeight(45);
                $currentTime = $endTime;
                $no++;
                $row++;
            }

            // ── 4d. Merge kolom J untuk coach — skip baris istirahat ──
            // Kumpulkan semua baris data (non-istirahat) dari headerRow sampai mergeEnd
            $mergeEnd    = $row - 1;
            $allRows     = range($headerRow, $mergeEnd);
            $dataRows    = array_values(array_diff($allRows, $breakRows));

            if (!empty($dataRows)) {
                // Merge per segmen berurutan yang tidak terputus baris istirahat
                $segments = [];
                $segStart = $dataRows[0];
                $segPrev  = $dataRows[0];

                for ($i = 1; $i < count($dataRows); $i++) {
                    if ($dataRows[$i] === $segPrev + 1) {
                        $segPrev = $dataRows[$i];
                    } else {
                        $segments[] = [$segStart, $segPrev];
                        $segStart = $dataRows[$i];
                        $segPrev  = $dataRows[$i];
                    }
                }
                $segments[] = [$segStart, $segPrev];

                // Merge tiap segmen di kolom J
                foreach ($segments as $idx => [$segS, $segE]) {
                    if ($segS === $segE) {
                        // Single row — tidak perlu merge
                        $sheet->setCellValue("J{$segS}", $idx === 0 ? $coachLabel : '');
                    } else {
                        $sheet->mergeCells("J{$segS}:J{$segE}");
                        $sheet->setCellValue("J{$segS}", $idx === 0 ? $coachLabel : '');
                    }
                    $sheet->getStyle("J{$segS}:J{$segE}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                            'wrapText'   => true,
                        ],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    ]);
                }
            }

            // ── 4e. Alokasi waktu — label di A:B, nilai di C agar : sejajar ──
            // Baris judul
            $sheet->mergeCells("A{$row}:E{$row}");
            $sheet->setCellValue("A{$row}", 'Alokasi Pembagian Waktu :');
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 9, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(14);
            $row++;

            // Baris label + nilai (label di A:B, nilai di C:E)
            // col A:B = label, col C:E = nilai
            $alokasiItems = [
                ['1. Penyajian',         ': 15 menit', false],
                ['2. Coach/NS/ Mentor',  ': 15 menit', false],
                ['3. Tanggapan Penyaji', ': 15 menit', true],
                ['',                     '  45 menit', false],
            ];

            foreach ($alokasiItems as [$label, $nilai, $underline]) {
                $sheet->mergeCells("A{$row}:B{$row}");
                $sheet->setCellValue("A{$row}", $label);
                $sheet->mergeCells("C{$row}:E{$row}");
                $sheet->setCellValue("C{$row}", $nilai);

                $styleLabel = $sheet->getStyle("A{$row}:B{$row}");
                $styleLabel->getFont()->setSize(9)->setName('Times New Roman');
                $styleLabel->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $styleNilai = $sheet->getStyle("C{$row}:E{$row}");
                $styleNilai->getFont()->setSize(9)->setName('Times New Roman');
                $styleNilai->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                if ($underline) {
                    $styleLabel->getFont()->setUnderline(true);
                    $styleNilai->getFont()->setUnderline(true);
                }

                $sheet->getRowDimension($row)->setRowHeight(14);
                $row++;
            }

            $row += 2;
        }

        // 5. Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(6);
        $sheet->getColumnDimension('D')->setWidth(22);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(22);
        $sheet->getColumnDimension('G')->setWidth(22);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(22);
        $sheet->getColumnDimension('J')->setWidth(16);
    }
}