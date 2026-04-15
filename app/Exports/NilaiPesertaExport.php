<?php

namespace App\Exports;

use App\Models\JenisPelatihan;
use App\Models\JenisNilai;
use App\Models\IndikatorNilai;
use App\Models\NilaiPeserta;
use App\Models\CatatanNilai;
use App\Models\Peserta;
use App\Models\Kelompok;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class NilaiPesertaExport implements WithEvents, WithTitle
{
    protected int     $jenisPelatihanId;
    protected ?string $angkatan;
    protected ?string $tahun;
    protected ?string $kelompok;
    protected ?string $search;
    protected ?string $kategori;
    protected ?string $wilayah;

    // ── Warna header ──────────────────────────────────────────
    const COLOR_HEADER_JENIS  = '285496';
    const COLOR_HEADER_IND    = '3A6BC7';
    const COLOR_HEADER_BASE   = '1B3A6B';
    const COLOR_HEADER_TOTAL  = '10803B';
    const COLOR_ROW_EVEN      = 'EEF3FB';
    const COLOR_ROW_ODD       = 'FFFFFF';
    const COLOR_BELUM_DINILAI = 'FFF3CD';

    // ── Warna kualifikasi ─────────────────────────────────────
    const COLOR_KUALIFIKASI_SANGAT = '1B6B3A';
    const COLOR_KUALIFIKASI_MEMU   = '28a745';
    const COLOR_KUALIFIKASI_CUKUP  = '1A6EA8';
    const COLOR_KUALIFIKASI_KURANG = 'E07B00';
    const COLOR_KUALIFIKASI_TIDAK  = 'C0392B';
    const COLOR_KUALIFIKASI_ERROR  = '6c757d';

    public function __construct(
        int     $jenisPelatihanId,
        ?string $angkatan = null,
        ?string $tahun    = null,
        ?string $kelompok = null,
        ?string $search   = null,
        ?string $kategori = null,
        ?string $wilayah  = null
    ) {
        $this->jenisPelatihanId = $jenisPelatihanId;
        $this->angkatan         = $angkatan;
        $this->tahun            = $tahun;
        $this->kelompok         = $kelompok;
        $this->search           = $search;
        $this->kategori         = $kategori;
        $this->wilayah          = $wilayah;
    }

    public function title(): string
    {
        $jp = JenisPelatihan::find($this->jenisPelatihanId);
        return 'Rekap Nilai ' . ($jp->kode_pelatihan ?? 'Nilai');
    }

    // =========================================================
    // HELPER — kualifikasi & romawi
    // =========================================================
    private function romawToInt(string $str): int
    {
        preg_match('/\b([IVXLCDM]+)\b/i', $str, $matches);
        $roman = strtoupper($matches[1] ?? '');

        $map    = ['I'=>1,'V'=>5,'X'=>10,'L'=>50,'C'=>100,'D'=>500,'M'=>1000];
        $result = 0;
        $len    = strlen($roman);

        for ($i = 0; $i < $len; $i++) {
            $curr = $map[$roman[$i]] ?? 0;
            $next = $map[$roman[$i + 1] ?? ''] ?? 0;
            $result += $curr < $next ? -$curr : $curr;
        }

        return $result;
    }

    private function getKualifikasi(float $total): array
    {
        if ($total > 100) {
            return ['label' => 'Salah',             'bg' => self::COLOR_KUALIFIKASI_ERROR,  'fg' => 'FFFFFF'];
        } elseif ($total > 90) {
            return ['label' => 'Sangat Memuaskan',  'bg' => self::COLOR_KUALIFIKASI_SANGAT, 'fg' => 'FFFFFF'];
        } elseif ($total > 80) {
            return ['label' => 'Memuaskan',         'bg' => self::COLOR_KUALIFIKASI_MEMU,   'fg' => 'FFFFFF'];
        } elseif ($total > 70) {
            return ['label' => 'Cukup Memuaskan',   'bg' => self::COLOR_KUALIFIKASI_CUKUP,  'fg' => 'FFFFFF'];
        } elseif ($total > 60) {
            return ['label' => 'Kurang Memuaskan',  'bg' => self::COLOR_KUALIFIKASI_KURANG, 'fg' => 'FFFFFF'];
        } else {
            return ['label' => 'Tidak Memuaskan',   'bg' => self::COLOR_KUALIFIKASI_TIDAK,  'fg' => 'FFFFFF'];
        }
    }

    // =========================================================
    // AMBIL DATA PESERTA + NILAI
    // =========================================================
    private function getData(): array
    {
        $jenisNilaiList = JenisNilai::where('id_jenis_pelatihan', $this->jenisPelatihanId)
            ->with(['indikatorNilai' => fn($q) => $q->orderBy('id')])
            ->orderBy('id')
            ->get();

        // ── Query peserta ─────────────────────────────────────
        $query = Peserta::query()
            ->whereHas('pendaftaran', fn($q) =>
                $q->where('id_jenis_pelatihan', $this->jenisPelatihanId)
                  ->whereNotNull('id_angkatan')
            )
            ->whereHas('kelompok', fn($q) =>
                $q->where('id_jenis_pelatihan', $this->jenisPelatihanId)
            )
            ->with([
                'kepegawaian',
                'pendaftaran' => fn($q) => $q->where('id_jenis_pelatihan', $this->jenisPelatihanId),
            ]);

        // Filter angkatan
        if (!empty($this->angkatan)) {
            $namaAngkatan = 'Angkatan ' . $this->angkatan;
            $query->whereHas('pendaftaran.angkatan', fn($q) =>
                $q->where('nama_angkatan', $namaAngkatan)
            );
        }

        // Filter tahun
        if (!empty($this->tahun)) {
            $query->whereHas('pendaftaran.angkatan', fn($q) =>
                $q->where('tahun', 'LIKE', "%{$this->tahun}%")
            );
        }

        // Filter kelompok
        if (!empty($this->kelompok)) {
            $namaKelompok = 'Kelompok ' . $this->kelompok;
            $query->whereHas('kelompok', fn($q) =>
                $q->where('nama_kelompok', 'LIKE', "%{$namaKelompok}%")
                  ->where('id_jenis_pelatihan', $this->jenisPelatihanId)
            );
        }

        // Filter search
        if (!empty($this->search)) {
            $term = $this->search;
            $query->where(fn($q) =>
                $q->where('nama_lengkap', 'LIKE', "%{$term}%")
                  ->orWhere('nip_nrp', 'LIKE', "%{$term}%")
            );
        }

        // Filter kategori
        if (!empty($this->kategori)) {
            $kategori = $this->kategori;
            $query->whereHas('pendaftaran.angkatan', fn($q) =>
                $q->where('kategori', $kategori)
            );
        }

        // Filter wilayah
        if (!empty($this->wilayah)) {
            $wilayah = $this->wilayah;
            $query->whereHas('pendaftaran.angkatan', fn($q) =>
                $q->where('wilayah', 'LIKE', "%{$wilayah}%")
            );
        }

        $pesertaList = $query->with([
            'pendaftaran.angkatan',
            'kelompok' => fn($q) => $q->where('id_jenis_pelatihan', $this->jenisPelatihanId),
        ])->get()->sortBy([
            fn($a, $b) => $this->romawToInt(
                optional(optional($a->pendaftaran->first())->angkatan)->nama_angkatan ?? ''
            ) <=> $this->romawToInt(
                optional(optional($b->pendaftaran->first())->angkatan)->nama_angkatan ?? ''
            ),
            fn($a, $b) => (int) filter_var($a->ndh, FILTER_SANITIZE_NUMBER_INT)
                          <=> (int) filter_var($b->ndh, FILTER_SANITIZE_NUMBER_INT),
        ])->values();

        $rows = [];
        foreach ($pesertaList as $p) {
            $kepegawaian = $p->kepegawaian;

            $kelompok = Kelompok::whereHas('peserta', fn($q) => $q->where('peserta.id', $p->id))
                ->where('id_jenis_pelatihan', $this->jenisPelatihanId)
                ->first();

            // Ambil nama penguji dan coach dari relasi kelompok
            $namaPenguji = $kelompok?->penguji?->nama ?? '-';
            $namaCoach   = $kelompok?->coach?->nama ?? '-';

            $nilaiList = NilaiPeserta::where('id_peserta', $p->id)
                ->with('indikatorNilai.jenisNilai')
                ->whereHas('indikatorNilai.jenisNilai', fn($q) =>
                    $q->where('id_jenis_pelatihan', $this->jenisPelatihanId)
                )
                ->get()
                ->keyBy('id_indikator_nilai');

            $catatanList = CatatanNilai::where('id_peserta', $p->id)
                ->whereHas('jenisNilai', fn($q) =>
                    $q->where('id_jenis_pelatihan', $this->jenisPelatihanId)
                )
                ->with('jenisNilai')
                ->get()
                ->keyBy('id_jenis_nilai');

            $totalNilai    = 0;
            $nilaiPerJenis = [];
            foreach ($jenisNilaiList as $jn) {
                $nilaiJn = $nilaiList->filter(
                    fn($n) => $n->indikatorNilai?->jenisNilai?->id === $jn->id
                );
                $sumKonversi = round(
                    $nilaiJn->sum(fn($n) => ($n->nilai / 100) * ($n->indikatorNilai->bobot ?? 0)),
                    2
                );
                $totalNilai += $sumKonversi;

                $indikatorValues = [];
                foreach ($jn->indikatorNilai as $ind) {
                    $nilaiRecord = $nilaiList->get($ind->id);
                    $indikatorValues[$ind->id] = $nilaiRecord ? $nilaiRecord->nilai : null;
                }
                $nilaiPerJenis[$jn->id] = $indikatorValues;
            }

            $catatanGabung = $jenisNilaiList->map(function ($jn) use ($catatanList) {
                $catatan = $catatanList->get($jn->id);
                if ($catatan && !empty(trim($catatan->catatan))) {
                    return $jn->name . ': ' . trim($catatan->catatan);
                }
                return null;
            })->filter()->implode("\n");

            $rows[] = [
                'peserta'         => $p,
                'kepegawaian'     => $kepegawaian,
                'kelompok'        => $kelompok,
                'nilai_per_jenis' => $nilaiPerJenis,
                'total_nilai'     => round($totalNilai, 2),
                'catatan'         => $catatanGabung,
                'nama_penguji'    => $namaPenguji,
                'nama_coach'      => $namaCoach,
            ];
        }

        return [
            'jenisNilaiList' => $jenisNilaiList,
            'rows'           => $rows,
        ];
    }

    // =========================================================
    // REGISTER EVENTS — tulis langsung ke sheet
    // =========================================================
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet          = $event->sheet->getDelegate();
                $data           = $this->getData();
                $jenisNilaiList = $data['jenisNilaiList'];
                $rows           = $data['rows'];
                $jp             = JenisPelatihan::find($this->jenisPelatihanId);

                // ── KOLOM IDENTITAS ───────────────────────────────────
                $baseHeaders = [
                    'No', 'NDH', 'Nama Peserta', 'NIP / NRP',
                    'Jabatan', 'Instansi', 'Pangkat', 'Golongan',
                ];
                $baseCount = count($baseHeaders); // 8

                // ── TOTAL KOLOM ───────────────────────────────────────
                $totalCols = $baseCount;
                foreach ($jenisNilaiList as $jn) {
                    $totalCols += $jn->indikatorNilai->count();
                }
                $totalCols += 3; // Total + Kualifikasi + Catatan
                $totalCols += 2; // + Penguji + Coach (di paling akhir)

                $lastColLetter = Coordinate::stringFromColumnIndex($totalCols);

                // ── JUDUL SHEET (baris 1) ─────────────────────────────
                $sheet->mergeCells('A1:' . $lastColLetter . '1');
                $sheet->setCellValue('A1', 'REKAP NILAI PESERTA — ' . strtoupper($jp->nama_pelatihan ?? ''));
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_BASE]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // ── BARIS FILTER INFO (baris 2) ───────────────────────
                $filterInfo = [];
                if (!empty($this->kategori)) $filterInfo[] = 'Kategori: ' . $this->kategori;
                if (!empty($this->wilayah))  $filterInfo[] = 'Wilayah: ' . $this->wilayah;
                if (!empty($this->angkatan)) $filterInfo[] = 'Angkatan ' . $this->angkatan;
                if (!empty($this->tahun))    $filterInfo[] = 'Tahun ' . $this->tahun;
                if (!empty($this->kelompok)) $filterInfo[] = 'Kelompok ' . $this->kelompok;
                if (!empty($this->search))   $filterInfo[] = 'Cari: ' . $this->search;

                $sheet->mergeCells('A2:' . $lastColLetter . '2');
                $filterText = count($filterInfo) > 0
                    ? 'Filter: ' . implode(' | ', $filterInfo)
                    : 'Filter: Semua Data';
                $sheet->setCellValue('A2', $filterText);
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '475569']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // ── BARIS HEADER ──────────────────────────────────────
                $headerRow1   = 3;
                $headerRow2   = 4;
                $dataStartRow = 5;

                foreach ($baseHeaders as $i => $label) {
                    $colLetter = Coordinate::stringFromColumnIndex($i + 1);
                    $sheet->mergeCells("{$colLetter}{$headerRow1}:{$colLetter}{$headerRow2}");
                    $sheet->setCellValue("{$colLetter}{$headerRow1}", $label);
                }

                $currentCol = $baseCount + 1;
                foreach ($jenisNilaiList as $jn) {
                    $indCount    = $jn->indikatorNilai->count();
                    $startLetter = Coordinate::stringFromColumnIndex($currentCol);
                    $endLetter   = Coordinate::stringFromColumnIndex($currentCol + $indCount - 1);

                    if ($indCount > 1) {
                        $sheet->mergeCells("{$startLetter}{$headerRow1}:{$endLetter}{$headerRow1}");
                    }
                    $sheet->setCellValue(
                        "{$startLetter}{$headerRow1}",
                        $jn->name . "\n(Bobot " . $jn->bobot . "%)"
                    );

                    foreach ($jn->indikatorNilai as $k => $ind) {
                        $indColLetter = Coordinate::stringFromColumnIndex($currentCol + $k);
                        $sheet->setCellValue(
                            "{$indColLetter}{$headerRow2}",
                            $ind->name . "\n(Bobot " . $ind->bobot . "%)"
                        );
                    }
                    $currentCol += $indCount;
                }

                // Header Total
                $totalColIdx    = $currentCol;
                $totalColLetter = Coordinate::stringFromColumnIndex($totalColIdx);
                $sheet->mergeCells("{$totalColLetter}{$headerRow1}:{$totalColLetter}{$headerRow2}");
                $sheet->setCellValue("{$totalColLetter}{$headerRow1}", "TOTAL\nNILAI");

                // Header Kualifikasi
                $kualifikasiColIdx    = $currentCol + 1;
                $kualifikasiColLetter = Coordinate::stringFromColumnIndex($kualifikasiColIdx);
                $sheet->mergeCells("{$kualifikasiColLetter}{$headerRow1}:{$kualifikasiColLetter}{$headerRow2}");
                $sheet->setCellValue("{$kualifikasiColLetter}{$headerRow1}", "KUALIFIKASI");

                // Header Catatan
                $catatanColIdx    = $currentCol + 2;
                $catatanColLetter = Coordinate::stringFromColumnIndex($catatanColIdx);
                $sheet->mergeCells("{$catatanColLetter}{$headerRow1}:{$catatanColLetter}{$headerRow2}");
                $sheet->setCellValue("{$catatanColLetter}{$headerRow1}", "CATATAN");

                // ── HEADER PENGUJI & COACH (paling akhir) ─────────────
                $pengujiColIdx = $currentCol + 3;
                $coachColIdx   = $currentCol + 4;
                $pengujiColLetter = Coordinate::stringFromColumnIndex($pengujiColIdx);
                $coachColLetter   = Coordinate::stringFromColumnIndex($coachColIdx);

                $sheet->mergeCells("{$pengujiColLetter}{$headerRow1}:{$pengujiColLetter}{$headerRow2}");
                $sheet->setCellValue("{$pengujiColLetter}{$headerRow1}", "PENGUJI");

                $sheet->mergeCells("{$coachColLetter}{$headerRow1}:{$coachColLetter}{$headerRow2}");
                $sheet->setCellValue("{$coachColLetter}{$headerRow1}", "COACH");

                // ── STYLE HEADER ──────────────────────────────────────
                $lastBaseCol = Coordinate::stringFromColumnIndex($baseCount);
                $sheet->getStyle("A{$headerRow1}:{$lastBaseCol}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_BASE]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                if ($totalCols > $baseCount + 3) {
                    $jenisStartLetter = Coordinate::stringFromColumnIndex($baseCount + 1);
                    $jenisEndLetter   = Coordinate::stringFromColumnIndex($catatanColIdx);
                    $sheet->getStyle("{$jenisStartLetter}{$headerRow1}:{$jenisEndLetter}{$headerRow1}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_JENIS]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    ]);
                    $sheet->getStyle("{$jenisStartLetter}{$headerRow2}:{$jenisEndLetter}{$headerRow2}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 9],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_IND]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    ]);
                }

                $sheet->getStyle("{$totalColLetter}{$headerRow1}:{$totalColLetter}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_TOTAL]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                $sheet->getStyle("{$kualifikasiColLetter}{$headerRow1}:{$kualifikasiColLetter}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '5B2D8E']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                $sheet->getStyle("{$catatanColLetter}{$headerRow1}:{$catatanColLetter}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6B5E8C']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                $sheet->getStyle("{$pengujiColLetter}{$headerRow1}:{$pengujiColLetter}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8B5CF6']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                $sheet->getStyle("{$coachColLetter}{$headerRow1}:{$coachColLetter}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'A855F7']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                $sheet->getRowDimension($headerRow1)->setRowHeight(40);
                $sheet->getRowDimension($headerRow2)->setRowHeight(50);

                // ── TULIS DATA ────────────────────────────────────────
                foreach ($rows as $idx => $row) {
                    $rowNum  = $dataStartRow + $idx;
                    $p       = $row['peserta'];
                    $kep     = $row['kepegawaian'];
                    $isEven  = ($idx % 2 === 0);
                    $bgColor = $isEven ? self::COLOR_ROW_EVEN : self::COLOR_ROW_ODD;

                    $identitasData = [
                        $idx + 1,
                        $p->ndh ?? '-',
                        $p->nama_lengkap ?? '-',
                        $p->nip_nrp ?? '-',
                        $kep->jabatan ?? '-',
                        $kep->asal_instansi ?? '-',
                        $kep->pangkat ?? '-',
                        $kep->golongan_ruang ?? '-',
                    ];

                    foreach ($identitasData as $ci => $val) {
                        $colLetter = Coordinate::stringFromColumnIndex($ci + 1);
                        $cell      = $sheet->getCell("{$colLetter}{$rowNum}");
                        if (in_array($ci, [1, 3])) {
                            $cell->setValueExplicit((string) $val, DataType::TYPE_STRING);
                        } else {
                            $cell->setValue($val);
                        }
                    }

                    $currentCol = $baseCount + 1;
                    foreach ($jenisNilaiList as $jn) {
                        foreach ($jn->indikatorNilai as $ind) {
                            $colLetter  = Coordinate::stringFromColumnIndex($currentCol);
                            $nilaiInput = $row['nilai_per_jenis'][$jn->id][$ind->id] ?? null;

                            if ($nilaiInput !== null) {
                                $sheet->setCellValue("{$colLetter}{$rowNum}", (float) $nilaiInput);
                            } else {
                                $sheet->setCellValue("{$colLetter}{$rowNum}", '');
                                $sheet->getStyle("{$colLetter}{$rowNum}")->applyFromArray([
                                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_BELUM_DINILAI]],
                                ]);
                            }
                            $currentCol++;
                        }
                    }

                    // ① Zebra untuk semua kolom termasuk penguji & coach
                    $lastDataCol = $coachColLetter;
                    $sheet->getStyle("A{$rowNum}:{$lastDataCol}{$rowNum}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                    ]);

                    // ② Total
                    $totalColLetterData = Coordinate::stringFromColumnIndex($totalColIdx);
                    $total              = $row['total_nilai'];
                    $totalBg = $total >= 80 ? '28a745' : ($total >= 60 ? 'ffc107' : ($total > 0 ? 'dc3545' : 'adb5bd'));
                    $totalFg = ($total >= 60 && $total < 80) ? '212529' : 'FFFFFF';
                    $sheet->setCellValue("{$totalColLetterData}{$rowNum}", $total);
                    $sheet->getStyle("{$totalColLetterData}{$rowNum}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $totalFg]],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $totalBg]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    // ③ Kualifikasi
                    $kualifikasiColLetterData = Coordinate::stringFromColumnIndex($kualifikasiColIdx);
                    $kualifikasi              = $this->getKualifikasi($total);
                    $sheet->setCellValue("{$kualifikasiColLetterData}{$rowNum}", $kualifikasi['label']);
                    $sheet->getStyle("{$kualifikasiColLetterData}{$rowNum}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => $kualifikasi['fg']]],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $kualifikasi['bg']]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    ]);

                    // ④ Catatan
                    $catatanColLetterData = Coordinate::stringFromColumnIndex($catatanColIdx);
                    $catatanText          = $row['catatan'] ?? '';
                    $sheet->setCellValue("{$catatanColLetterData}{$rowNum}", $catatanText);
                    if (!empty($catatanText)) {
                        $sheet->getStyle("{$catatanColLetterData}{$rowNum}")->applyFromArray([
                            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F0FA']],
                            'font'      => ['color' => ['rgb' => '4A3B6B'], 'size' => 9],
                            'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP],
                        ]);
                    }

                    // ⑤ Penguji
                    $sheet->setCellValue("{$pengujiColLetter}{$rowNum}", $row['nama_penguji']);
                    $sheet->getStyle("{$pengujiColLetter}{$rowNum}")->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    // ⑥ Coach
                    $sheet->setCellValue("{$coachColLetter}{$rowNum}", $row['nama_coach']);
                    $sheet->getStyle("{$coachColLetter}{$rowNum}")->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    // ⑦ Tinggi baris
                    $lineCount = !empty($catatanText)
                        ? max(1, substr_count($catatanText, "\n") + 1,
                              (int) ceil(mb_strlen($catatanText) / 55))
                        : 1;
                    $sheet->getRowDimension($rowNum)->setRowHeight(max(18, $lineCount * 15));
                }

                // ── BARIS RATA-RATA ───────────────────────────────────
                $avgRow = $dataStartRow + count($rows);
                if (count($rows) > 0) {
                    $sheet->mergeCells("A{$avgRow}:" . Coordinate::stringFromColumnIndex($baseCount) . "{$avgRow}");
                    $sheet->setCellValue("A{$avgRow}", 'RATA-RATA');
                    $sheet->getStyle("A{$avgRow}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_BASE]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    $currentCol = $baseCount + 1;
                    foreach ($jenisNilaiList as $jn) {
                        foreach ($jn->indikatorNilai as $ind) {
                            $colLetter = Coordinate::stringFromColumnIndex($currentCol);
                            $vals      = array_filter(
                                array_map(fn($r) => $r['nilai_per_jenis'][$jn->id][$ind->id] ?? null, $rows),
                                fn($v) => $v !== null
                            );
                            $avg = count($vals) > 0 ? round(array_sum($vals) / count($vals), 2) : 0;
                            $sheet->setCellValue("{$colLetter}{$avgRow}", $avg);
                            $sheet->getStyle("{$colLetter}{$avgRow}")->applyFromArray([
                                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_IND]],
                                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                            ]);
                            $currentCol++;
                        }
                    }

                    $avgTotalLetter = Coordinate::stringFromColumnIndex($totalColIdx);
                    $avgTotal       = round(array_sum(array_column($rows, 'total_nilai')) / count($rows), 2);
                    $sheet->setCellValue("{$avgTotalLetter}{$avgRow}", $avgTotal);
                    $sheet->getStyle("{$avgTotalLetter}{$avgRow}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_TOTAL]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $kualifikasiAvgLetter = Coordinate::stringFromColumnIndex($kualifikasiColIdx);
                    $kualifikasiAvg       = $this->getKualifikasi($avgTotal);
                    $sheet->setCellValue("{$kualifikasiAvgLetter}{$avgRow}", $kualifikasiAvg['label']);
                    $sheet->getStyle("{$kualifikasiAvgLetter}{$avgRow}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['rgb' => $kualifikasiAvg['fg']]],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $kualifikasiAvg['bg']]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    $catatanAvgLetter = Coordinate::stringFromColumnIndex($catatanColIdx);
                    $sheet->setCellValue("{$catatanAvgLetter}{$avgRow}", '—');
                    $sheet->getStyle("{$catatanAvgLetter}{$avgRow}")->applyFromArray([
                        'font'      => ['color' => ['rgb' => 'AAAAAA']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EDE9F5']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    // Rata-rata untuk Penguji & Coach (kosong)
                    $sheet->setCellValue("{$pengujiColLetter}{$avgRow}", '—');
                    $sheet->getStyle("{$pengujiColLetter}{$avgRow}")->applyFromArray([
                        'font'      => ['color' => ['rgb' => 'AAAAAA']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EDE9F5']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->setCellValue("{$coachColLetter}{$avgRow}", '—');
                    $sheet->getStyle("{$coachColLetter}{$avgRow}")->applyFromArray([
                        'font'      => ['color' => ['rgb' => 'AAAAAA']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EDE9F5']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->getRowDimension($avgRow)->setRowHeight(22);
                }

                // ── BORDER ────────────────────────────────────────────
                $lastRow = $avgRow;
                $sheet->getStyle("A{$headerRow1}:{$lastColLetter}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN,   'color' => ['rgb' => 'CBD5E1']],
                        'outline'    => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => self::COLOR_HEADER_BASE]],
                    ],
                ]);

                // ── ALIGNMENT DATA ────────────────────────────────────
                $sheet->getStyle("A{$dataStartRow}:B{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle("C{$dataStartRow}:F{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle("G{$dataStartRow}:H{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $jenisStartLetter2 = Coordinate::stringFromColumnIndex($baseCount + 1);
                $nilaiEndLetter    = Coordinate::stringFromColumnIndex($catatanColIdx);
                $sheet->getStyle("{$jenisStartLetter2}{$dataStartRow}:{$nilaiEndLetter}{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle("{$kualifikasiColLetter}{$dataStartRow}:{$kualifikasiColLetter}{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);
                $sheet->getStyle("{$catatanColLetter}{$dataStartRow}:{$catatanColLetter}{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                ]);
                $sheet->getStyle("{$pengujiColLetter}{$dataStartRow}:{$coachColLetter}{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // ── FREEZE PANES ──────────────────────────────────────
                $sheet->freezePane('C5');

                // ── LEBAR KOLOM ───────────────────────────────────────
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(7);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(28);
                $sheet->getColumnDimension('F')->setWidth(28);
                $sheet->getColumnDimension('G')->setWidth(18);
                $sheet->getColumnDimension('H')->setWidth(12);

                for ($c = $baseCount + 1; $c <= $catatanColIdx; $c++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($c))->setWidth(14);
                }
                $sheet->getColumnDimension($kualifikasiColLetter)->setWidth(22);
                $sheet->getColumnDimension($catatanColLetter)->setWidth(45);
                $sheet->getColumnDimension($pengujiColLetter)->setWidth(25);
                $sheet->getColumnDimension($coachColLetter)->setWidth(25);

                // ── FORMAT NUMBER ─────────────────────────────────────
                $sheet->getStyle("B{$dataStartRow}:B{$lastRow}")->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle("D{$dataStartRow}:D{$lastRow}")->getNumberFormat()->setFormatCode('@');

                $jenisStartLetter3 = Coordinate::stringFromColumnIndex($baseCount + 1);
                $nilaiEndLetter2   = Coordinate::stringFromColumnIndex($catatanColIdx);
                $sheet->getStyle("{$jenisStartLetter3}{$dataStartRow}:{$nilaiEndLetter2}{$lastRow}")
                    ->getNumberFormat()->setFormatCode('0.00');

                $sheet->getStyle("{$kualifikasiColLetter}{$dataStartRow}:{$kualifikasiColLetter}{$lastRow}")
                    ->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle("{$catatanColLetter}{$dataStartRow}:{$catatanColLetter}{$lastRow}")
                    ->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle("{$pengujiColLetter}{$dataStartRow}:{$coachColLetter}{$lastRow}")
                    ->getNumberFormat()->setFormatCode('@');
            },
        ];
    }
}