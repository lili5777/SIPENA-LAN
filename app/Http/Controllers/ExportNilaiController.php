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
}