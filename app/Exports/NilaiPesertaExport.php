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
    protected int    $jenisPelatihanId;
    protected ?string $angkatan;
    protected ?string $tahun;
    protected ?string $kelompok;
    protected ?string $search;

    // ── Warna header ──────────────────────────────────────────
    const COLOR_HEADER_JENIS  = '285496'; // biru utama  → baris 1 (jenis nilai)
    const COLOR_HEADER_IND    = '3A6BC7'; // biru muda   → baris 2 (indikator)
    const COLOR_HEADER_BASE   = '1B3A6B'; // biru gelap  → kolom identitas
    const COLOR_HEADER_TOTAL  = '10803B'; // hijau       → kolom total
    const COLOR_ROW_EVEN      = 'EEF3FB'; // zebra even
    const COLOR_ROW_ODD       = 'FFFFFF'; // zebra odd
    const COLOR_BELUM_DINILAI = 'FFF3CD'; // kuning muda → cell kosong

    // ── Warna kualifikasi ─────────────────────────────────────
    const COLOR_KUALIFIKASI_SANGAT = '1B6B3A'; // hijau tua  → Sangat Memuaskan (> 90)
    const COLOR_KUALIFIKASI_MEMU   = '28a745'; // hijau      → Memuaskan        (> 80)
    const COLOR_KUALIFIKASI_CUKUP  = '1A6EA8'; // biru       → Cukup Memuaskan  (> 70)
    const COLOR_KUALIFIKASI_KURANG = 'E07B00'; // oranye     → Kurang Memuaskan (> 60)
    const COLOR_KUALIFIKASI_TIDAK  = 'C0392B'; // merah tua  → Tidak Memuaskan  (≤ 60)
    const COLOR_KUALIFIKASI_ERROR  = '6c757d'; // abu        → nilai > 100 (error)

    public function __construct(
        int     $jenisPelatihanId,
        ?string $angkatan = null,
        ?string $tahun    = null,
        ?string $kelompok = null,
        ?string $search   = null
    ) {
        $this->jenisPelatihanId = $jenisPelatihanId;
        $this->angkatan         = $angkatan;
        $this->tahun            = $tahun;
        $this->kelompok         = $kelompok;
        $this->search           = $search;
    }

    public function title(): string
    {
        $jp = JenisPelatihan::find($this->jenisPelatihanId);
        return 'Rekap Nilai ' . ($jp->kode_pelatihan ?? 'Nilai');
    }

    // =========================================================
    // HELPER — Konversi total nilai → label & warna kualifikasi
    // =========================================================
    private function getKualifikasi(float $total): array
    {
        if ($total > 100) {
            return [
                'label' => 'Salah',
                'bg'    => self::COLOR_KUALIFIKASI_ERROR,
                'fg'    => 'FFFFFF',
            ];
        } elseif ($total > 90) {
            return [
                'label' => 'Sangat Memuaskan',
                'bg'    => self::COLOR_KUALIFIKASI_SANGAT,
                'fg'    => 'FFFFFF',
            ];
        } elseif ($total > 80) {
            return [
                'label' => 'Memuaskan',
                'bg'    => self::COLOR_KUALIFIKASI_MEMU,
                'fg'    => 'FFFFFF',
            ];
        } elseif ($total > 70) {
            return [
                'label' => 'Cukup Memuaskan',
                'bg'    => self::COLOR_KUALIFIKASI_CUKUP,
                'fg'    => 'FFFFFF',
            ];
        } elseif ($total > 60) {
            return [
                'label' => 'Kurang Memuaskan',
                'bg'    => self::COLOR_KUALIFIKASI_KURANG,
                'fg'    => 'FFFFFF',
            ];
        } else {
            return [
                'label' => 'Tidak Memuaskan',
                'bg'    => self::COLOR_KUALIFIKASI_TIDAK,
                'fg'    => 'FFFFFF',
            ];
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

        // Build query peserta
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
                $q->where('nama_angkatan', 'LIKE', "%{$namaAngkatan}%")
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

        $pesertaList = $query->orderBy('ndh')->get();

        $rows = [];
        foreach ($pesertaList as $p) {
            $kepegawaian = $p->kepegawaian;

            $kelompok = Kelompok::whereHas('peserta', fn($q) => $q->where('peserta.id', $p->id))
                ->where('id_jenis_pelatihan', $this->jenisPelatihanId)
                ->first();

            // Ambil semua nilai peserta ini untuk jenis pelatihan ini
            $nilaiList = NilaiPeserta::where('id_peserta', $p->id)
                ->with('indikatorNilai.jenisNilai')
                ->whereHas('indikatorNilai.jenisNilai', fn($q) =>
                    $q->where('id_jenis_pelatihan', $this->jenisPelatihanId)
                )
                ->get()
                ->keyBy('id_indikator_nilai');

            // Ambil catatan per jenis nilai
            $catatanList = CatatanNilai::where('id_peserta', $p->id)
                ->whereHas('jenisNilai', fn($q) =>
                    $q->where('id_jenis_pelatihan', $this->jenisPelatihanId)
                )
                ->with('jenisNilai')
                ->get()
                ->keyBy('id_jenis_nilai');

            // Hitung total nilai terbobot
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

            // Gabungkan semua catatan jadi 1 string
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

                // ── KOLOM IDENTITAS (tetap) ───────────────────────────
                $baseHeaders = [
                    'No',
                    'NDH',
                    'Nama Peserta',
                    'NIP / NRP',
                    'Jabatan',
                    'Instansi',
                    'Pangkat',
                    'Golongan',
                ];
                $baseCount = count($baseHeaders); // 8

                // ── HITUNG TOTAL KOLOM ────────────────────────────────
                // base + indikator per jenis nilai + Total + Kualifikasi + Catatan
                $totalCols = $baseCount;
                foreach ($jenisNilaiList as $jn) {
                    $totalCols += $jn->indikatorNilai->count();
                }
                $totalCols += 1; // kolom Total
                $totalCols += 1; // kolom Kualifikasi  ← BARU
                $totalCols += 1; // kolom Catatan

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

                // Header identitas — merge 2 baris vertikal
                foreach ($baseHeaders as $i => $label) {
                    $colLetter = Coordinate::stringFromColumnIndex($i + 1);
                    $sheet->mergeCells("{$colLetter}{$headerRow1}:{$colLetter}{$headerRow2}");
                    $sheet->setCellValue("{$colLetter}{$headerRow1}", $label);
                }

                // Header jenis nilai — merge horizontal sesuai jumlah indikator
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

                // Header Total — merge 2 baris
                $totalColIdx    = $currentCol;
                $totalColLetter = Coordinate::stringFromColumnIndex($totalColIdx);
                $sheet->mergeCells("{$totalColLetter}{$headerRow1}:{$totalColLetter}{$headerRow2}");
                $sheet->setCellValue("{$totalColLetter}{$headerRow1}", "TOTAL\nNILAI");

                // Header Kualifikasi — merge 2 baris  ← BARU
                $kualifikasiColIdx    = $currentCol + 1;
                $kualifikasiColLetter = Coordinate::stringFromColumnIndex($kualifikasiColIdx);
                $sheet->mergeCells("{$kualifikasiColLetter}{$headerRow1}:{$kualifikasiColLetter}{$headerRow2}");
                $sheet->setCellValue("{$kualifikasiColLetter}{$headerRow1}", "KUALIFIKASI");

                // Header Catatan — merge 2 baris
                $catatanColIdx    = $currentCol + 2;
                $catatanColLetter = Coordinate::stringFromColumnIndex($catatanColIdx);
                $sheet->mergeCells("{$catatanColLetter}{$headerRow1}:{$catatanColLetter}{$headerRow2}");
                $sheet->setCellValue("{$catatanColLetter}{$headerRow1}", "CATATAN");

                // ── STYLE HEADER ──────────────────────────────────────

                // Identitas — biru gelap
                $lastBaseCol = Coordinate::stringFromColumnIndex($baseCount);
                $sheet->getStyle("A{$headerRow1}:{$lastBaseCol}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_BASE]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                // Jenis nilai baris 1 — biru utama
                if ($totalCols > $baseCount + 3) { // +3 karena ada Total, Kualifikasi, Catatan
                    $jenisStartLetter = Coordinate::stringFromColumnIndex($baseCount + 1);
                    $jenisEndLetter   = Coordinate::stringFromColumnIndex($totalCols - 2); // -2 kecualikan Kualifikasi & Catatan
                    $sheet->getStyle("{$jenisStartLetter}{$headerRow1}:{$jenisEndLetter}{$headerRow1}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_JENIS]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    ]);

                    // Indikator baris 2 — biru muda
                    $sheet->getStyle("{$jenisStartLetter}{$headerRow2}:{$jenisEndLetter}{$headerRow2}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 9],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_IND]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    ]);
                }

                // Total header — hijau
                $sheet->getStyle("{$totalColLetter}{$headerRow1}:{$totalColLetter}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_TOTAL]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                // Kualifikasi header — ungu tua  ← BARU
                $sheet->getStyle("{$kualifikasiColLetter}{$headerRow1}:{$kualifikasiColLetter}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '5B2D8E']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                // Catatan header — abu-ungu
                $sheet->getStyle("{$catatanColLetter}{$headerRow1}:{$catatanColLetter}{$headerRow2}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6B5E8C']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);

                // Tinggi baris header
                $sheet->getRowDimension($headerRow1)->setRowHeight(40);
                $sheet->getRowDimension($headerRow2)->setRowHeight(50);

                // ── TULIS DATA ────────────────────────────────────────
                foreach ($rows as $idx => $row) {
                    $rowNum  = $dataStartRow + $idx;
                    $p       = $row['peserta'];
                    $kep     = $row['kepegawaian'];
                    $isEven  = ($idx % 2 === 0);
                    $bgColor = $isEven ? self::COLOR_ROW_EVEN : self::COLOR_ROW_ODD;

                    // Kolom identitas
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
                        $cell = $sheet->getCell("{$colLetter}{$rowNum}");

                        // NIP / NDH — force text
                        if (in_array($ci, [1, 3])) {
                            $cell->setValueExplicit((string)$val, DataType::TYPE_STRING);
                        } else {
                            $cell->setValue($val);
                        }
                    }

                    // Kolom nilai per indikator
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

                    // ① Zebra DULU — agar tidak menimpa warna total/kualifikasi/catatan
                    $lastDataCol = Coordinate::stringFromColumnIndex($currentCol + 2);
                    $sheet->getStyle("A{$rowNum}:{$lastDataCol}{$rowNum}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                    ]);

                    // ② Total — tulis setelah zebra
                    $totalColLetterData = Coordinate::stringFromColumnIndex($currentCol);
                    $total              = $row['total_nilai'];
                    $totalBg = $total >= 80 ? '28a745' : ($total >= 60 ? 'ffc107' : ($total > 0 ? 'dc3545' : 'adb5bd'));
                    $totalFg = ($total >= 60 && $total < 80) ? '212529' : 'FFFFFF';
                    $sheet->setCellValue("{$totalColLetterData}{$rowNum}", $total);
                    $sheet->getStyle("{$totalColLetterData}{$rowNum}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $totalFg]],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $totalBg]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    // ③ Kualifikasi — tulis setelah zebra  ← BARU
                    $kualifikasiColLetterData = Coordinate::stringFromColumnIndex($currentCol + 1);
                    $kualifikasi              = $this->getKualifikasi($total);
                    $sheet->setCellValue("{$kualifikasiColLetterData}{$rowNum}", $kualifikasi['label']);
                    $sheet->getStyle("{$kualifikasiColLetterData}{$rowNum}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => $kualifikasi['fg']]],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $kualifikasi['bg']]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    ]);

                    // ④ Catatan — tulis setelah zebra
                    $catatanColLetterData = Coordinate::stringFromColumnIndex($currentCol + 2);
                    $catatanText          = $row['catatan'] ?? '';
                    $sheet->setCellValue("{$catatanColLetterData}{$rowNum}", $catatanText);
                    if (!empty($catatanText)) {
                        $sheet->getStyle("{$catatanColLetterData}{$rowNum}")->applyFromArray([
                            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F0FA']],
                            'font'      => ['color' => ['rgb' => '4A3B6B'], 'size' => 9],
                            'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP],
                        ]);
                    }

                    // ⑤ Tinggi baris — auto berdasarkan panjang catatan
                    $lineCount = !empty($catatanText)
                        ? max(1, substr_count($catatanText, "\n") + 1,
                              (int) ceil(mb_strlen($catatanText) / 55))
                        : 1;
                    $rowHeight = max(18, $lineCount * 15);
                    $sheet->getRowDimension($rowNum)->setRowHeight($rowHeight);
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

                    // Rata-rata per indikator
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

                    // Rata-rata total
                    $avgTotalLetter = Coordinate::stringFromColumnIndex($currentCol);
                    $avgTotal       = count($rows) > 0
                        ? round(array_sum(array_column($rows, 'total_nilai')) / count($rows), 2)
                        : 0;
                    $sheet->setCellValue("{$avgTotalLetter}{$avgRow}", $avgTotal);
                    $sheet->getStyle("{$avgTotalLetter}{$avgRow}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_TOTAL]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    // Kualifikasi rata-rata  ← BARU
                    $kualifikasiAvgLetter = Coordinate::stringFromColumnIndex($currentCol + 1);
                    $kualifikasiAvg       = $this->getKualifikasi($avgTotal);
                    $sheet->setCellValue("{$kualifikasiAvgLetter}{$avgRow}", $kualifikasiAvg['label']);
                    $sheet->getStyle("{$kualifikasiAvgLetter}{$avgRow}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['rgb' => $kualifikasiAvg['fg']]],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $kualifikasiAvg['bg']]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    // Catatan di baris avg — kosong
                    $catatanAvgLetter = Coordinate::stringFromColumnIndex($currentCol + 2);
                    $sheet->setCellValue("{$catatanAvgLetter}{$avgRow}", '—');
                    $sheet->getStyle("{$catatanAvgLetter}{$avgRow}")->applyFromArray([
                        'font'      => ['color' => ['rgb' => 'AAAAAA']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EDE9F5']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->getRowDimension($avgRow)->setRowHeight(22);
                }

                // ── BORDER SEMUA DATA ─────────────────────────────────
                $lastRow = $avgRow;
                $sheet->getStyle("A{$headerRow1}:{$lastColLetter}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'CBD5E1'],
                        ],
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color'       => ['rgb' => self::COLOR_HEADER_BASE],
                        ],
                    ],
                ]);

                // ── ALIGNMENT DATA ────────────────────────────────────
                // No, NDH — center
                $sheet->getStyle("A{$dataStartRow}:B{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                // Nama, NIP, Jabatan, Instansi — kiri
                $sheet->getStyle("C{$dataStartRow}:F{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                // Pangkat, Golongan — center
                $sheet->getStyle("G{$dataStartRow}:H{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                // Kolom nilai indikator — center
                $jenisStartLetter2 = Coordinate::stringFromColumnIndex($baseCount + 1);
                $nilaiEndLetter    = Coordinate::stringFromColumnIndex($totalCols - 2); // -2 kecualikan Kualifikasi & Catatan
                $sheet->getStyle("{$jenisStartLetter2}{$dataStartRow}:{$nilaiEndLetter}{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                // Kolom Kualifikasi — center  ← BARU
                $sheet->getStyle("{$kualifikasiColLetter}{$dataStartRow}:{$kualifikasiColLetter}{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);
                // Kolom Catatan — kiri, wrap, top
                $catatanColFinal = Coordinate::stringFromColumnIndex($totalCols);
                $sheet->getStyle("{$catatanColFinal}{$dataStartRow}:{$catatanColFinal}{$lastRow}")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                ]);

                // ── FREEZE PANES ──────────────────────────────────────
                $sheet->freezePane('C5');

                // ── LEBAR KOLOM ───────────────────────────────────────
                $sheet->getColumnDimension('A')->setWidth(5);   // No
                $sheet->getColumnDimension('B')->setWidth(7);   // NDH
                $sheet->getColumnDimension('C')->setWidth(30);  // Nama
                $sheet->getColumnDimension('D')->setWidth(20);  // NIP
                $sheet->getColumnDimension('E')->setWidth(28);  // Jabatan
                $sheet->getColumnDimension('F')->setWidth(28);  // Instansi
                $sheet->getColumnDimension('G')->setWidth(18);  // Pangkat
                $sheet->getColumnDimension('H')->setWidth(12);  // Golongan

                // Kolom indikator + Total — lebar seragam
                for ($c = $baseCount + 1; $c <= $totalCols - 2; $c++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($c))->setWidth(14);
                }
                // Kolom Kualifikasi — lebih lebar  ← BARU
                $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($totalCols - 1))->setWidth(22);
                // Kolom Catatan — paling lebar
                $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($totalCols))->setWidth(45);

                // ── FORMAT NUMBER ─────────────────────────────────────
                // NDH & NIP — teks
                $sheet->getStyle("B{$dataStartRow}:B{$lastRow}")
                    ->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle("D{$dataStartRow}:D{$lastRow}")
                    ->getNumberFormat()->setFormatCode('@');

                // Nilai angka 2 desimal (indikator + total, bukan kualifikasi & catatan)
                $jenisStartLetter3 = Coordinate::stringFromColumnIndex($baseCount + 1);
                $nilaiEndLetter2   = Coordinate::stringFromColumnIndex($totalCols - 2);
                $sheet->getStyle("{$jenisStartLetter3}{$dataStartRow}:{$nilaiEndLetter2}{$lastRow}")
                    ->getNumberFormat()->setFormatCode('0.00');

                // Kualifikasi & Catatan — format teks
                $sheet->getStyle("{$kualifikasiColLetter}{$dataStartRow}:{$kualifikasiColLetter}{$lastRow}")
                    ->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle("{$catatanColFinal}{$dataStartRow}:{$catatanColFinal}{$lastRow}")
                    ->getNumberFormat()->setFormatCode('@');
            },
        ];
    }
}