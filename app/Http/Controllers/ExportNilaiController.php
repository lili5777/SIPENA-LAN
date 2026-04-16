<?php

namespace App\Http\Controllers;

use App\Exports\NilaiPesertaExport;
use App\Models\JenisPelatihan;
use App\Models\JenisNilai;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportNilaiController extends Controller
{
    // =========================================================
    // HELPER — angka romawi I–LXXX
    // =========================================================
    private function getRomawList(): array
    {
        $map = [
            1000 => 'M', 900 => 'CM', 500 => 'D', 400 => 'CD',
            100  => 'C', 90  => 'XC', 50  => 'L', 40  => 'XL',
            10   => 'X', 9   => 'IX', 5   => 'V', 4   => 'IV', 1 => 'I',
        ];
        $result = [];
        for ($i = 1; $i <= 80; $i++) {
            $n   = $i;
            $str = '';
            foreach ($map as $val => $rom) {
                while ($n >= $val) {
                    $str .= $rom;
                    $n   -= $val;
                }
            }
            $result[] = $str;
        }
        return $result;
    }

    private function getTahunList(): array
    {
        $tahunList = [];
        for ($y = 2020; $y <= (int) date('Y'); $y++) {
            $tahunList[] = $y;
        }
        return $tahunList;
    }

    // =========================================================
    // INDEX — Halaman form export
    // =========================================================
    public function index(Request $request)
    {
        $angkatanRomawi = $this->getRomawList();
        $tahunList      = $this->getTahunList();
        $kelompokList   = range(1, 10);
        $jenisPelatihan = JenisPelatihan::orderBy('nama_pelatihan')->get();

        // Jika jenis_pelatihan sudah dipilih, ambil jenis nilai untuk preview
        $jenisNilaiList = collect();
        if ($request->filled('jenis_pelatihan')) {
            $jp = JenisPelatihan::find($request->jenis_pelatihan);
            if ($jp) {
                $jenisNilaiList = JenisNilai::where('id_jenis_pelatihan', $jp->id)
                    ->withCount('indikatorNilai')
                    ->with(['indikatorNilai' => fn($q) => $q->orderBy('id')])
                    ->orderBy('id')
                    ->get();
            }
        }

        return view('admin.export.nilai_peserta', compact(
            'angkatanRomawi',
            'tahunList',
            'kelompokList',
            'jenisPelatihan',
            'jenisNilaiList'
        ));
    }

    // =========================================================
    // PREVIEW — AJAX preview struktur kolom
    // =========================================================
    public function preview(Request $request)
    {
        $jp = JenisPelatihan::find($request->jenis_pelatihan);
        if (!$jp) return response()->json(['jenis_nilai_list' => []]);

        $list = JenisNilai::where('id_jenis_pelatihan', $jp->id)
            ->with(['indikatorNilai' => fn($q) => $q->orderBy('id')])
            ->orderBy('id')
            ->get();

        return response()->json(['jenis_nilai_list' => $list]);
    }

    // =========================================================
    // EXPORT — Download Excel
    // =========================================================
    public function export(Request $request)
    {
        $request->validate([
            'jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
        ], [
            'jenis_pelatihan.required' => 'Jenis pelatihan wajib dipilih.',
            'jenis_pelatihan.exists'   => 'Jenis pelatihan tidak valid.',
        ]);

        $jp = JenisPelatihan::findOrFail($request->jenis_pelatihan);

        $angkatan = $request->input('angkatan');
        $tahun    = $request->input('tahun');
        $kelompok = $request->input('kelompok');
        $search   = $request->input('search');

        // ── Filter baru ───────────────────────────────────────
        $kategori = $request->input('kategori');
        // wilayah hanya relevan saat kategori FASILITASI
        $wilayah  = ($kategori === 'FASILITASI') ? $request->input('wilayah') : null;

        $fileName = 'rekap-nilai-' . str($jp->nama_pelatihan)->slug() . '-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new NilaiPesertaExport(
                $jp->id,
                $angkatan,
                $tahun,
                $kelompok,
                $search,
                $kategori,   // ← baru
                $wilayah     // ← baru
            ),
            $fileName
        );
    }

    // =========================================================
    // PREVIEW DATA — AJAX, return JSON untuk tabel inline
    // =========================================================
    public function previewData(Request $request)
    {
        $request->validate([
            'jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
        ]);

        // Reuse logic getData() dari Export class
        $export = new \App\Exports\NilaiPesertaExport(
            (int) $request->jenis_pelatihan,
            $request->input('angkatan'),
            $request->input('tahun'),
            $request->input('kelompok'),
            $request->input('search'),
            $request->input('kategori'),
            ($request->input('kategori') === 'FASILITASI') ? $request->input('wilayah') : null,
        );

        // getData() perlu dijadikan public — lihat langkah 3
        $data           = $export->getDataPublic();
        $jenisNilaiList = $data['jenisNilaiList'];
        $rows           = $data['rows'];

        $result = [];
        foreach ($rows as $idx => $row) {
            $p   = $row['peserta'];
            $kep = $row['kepegawaian'];

            $indikatorValues = [];
            foreach ($jenisNilaiList as $jn) {
                foreach ($jn->indikatorNilai as $ind) {
                    $val = $row['nilai_per_jenis'][$jn->id][$ind->id] ?? null;
                    $indikatorValues[] = [
                        'jenis_id'   => $jn->id,
                        'jenis_nama' => $jn->name,
                        'jenis_bobot'=> $jn->bobot,
                        'ind_id'     => $ind->id,
                        'ind_nama'   => $ind->name,
                        'ind_bobot'  => $ind->bobot,
                        'nilai'      => $val,
                    ];
                }
            }

            $result[] = [
                'no'           => $idx + 1,
                'ndh'          => $p->ndh ?? '-',
                'nama'         => $p->nama_lengkap ?? '-',
                'nip'          => $p->nip_nrp ?? '-',
                'jabatan'      => $kep->jabatan ?? '-',
                'instansi'     => $kep->asal_instansi ?? '-',
                'pangkat'      => $kep->pangkat ?? '-',
                'golongan'     => $kep->golongan_ruang ?? '-',
                'indikator'    => $indikatorValues,
                'total'        => $row['total_nilai'],
                'kualifikasi'  => $this->getKualifikasiLabel($row['total_nilai']),
                'catatan'      => $row['catatan'] ?? '',
                'penguji'      => $row['nama_penguji'],
                'coach'        => $row['nama_coach'],
            ];
        }

        // Bangun struktur header jenis+indikator untuk frontend
        $headers = [];
        foreach ($jenisNilaiList as $jn) {
            $headers[] = [
                'id'       => $jn->id,
                'nama'     => $jn->name,
                'bobot'    => $jn->bobot,
                'indikator'=> $jn->indikatorNilai->map(fn($i) => [
                    'id'    => $i->id,
                    'nama'  => $i->name,
                    'bobot' => $i->bobot,
                ])->values(),
            ];
        }

        return response()->json([
            'success' => true,
            'total'   => count($result),
            'headers' => $headers,
            'rows'    => $result,
        ]);
    }

    // Helper kualifikasi label saja (tanpa warna, warna di JS)
    private function getKualifikasiLabel(float $total): string
    {
        if ($total > 100) return 'Salah';
        if ($total > 90)  return 'Sangat Memuaskan';
        if ($total > 80)  return 'Memuaskan';
        if ($total > 70)  return 'Cukup Memuaskan';
        if ($total > 60)  return 'Kurang Memuaskan';
        return 'Tidak Memuaskan';
    }
}