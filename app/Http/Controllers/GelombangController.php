<?php

namespace App\Http\Controllers;

use App\Models\Gelombang;
use App\Models\JenisPelatihan;
use App\Models\Angkatan;
use Illuminate\Http\Request;

class GelombangController extends Controller
{
    public function index(Request $request)
    {
        $query = Gelombang::with('jenisPelatihan');

        if ($request->filled('jenis_pelatihan')) {
            $query->where('id_jenis_pelatihan', $request->jenis_pelatihan);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama_gelombang', 'like', '%' . $request->search . '%');
        }

        $gelombang = $query->orderBy('tahun', 'desc')
                           ->orderBy('id_jenis_pelatihan')
                           ->paginate(10)
                           ->withQueryString();

        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();
        $tahunList = Gelombang::distinct()->pluck('tahun')->sortDesc();
        $kategoriList = ['PNBP', 'FASILITASI'];

        return view('admin.gelombang.index', compact('gelombang', 'jenisPelatihan', 'tahunList', 'kategoriList'));
    }

    public function create()
    {
        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();
        $kategoriList = ['PNBP', 'FASILITASI'];
        return view('admin.gelombang.create', compact('jenisPelatihan', 'kategoriList'));
    }

    public function store(Request $request)
    {
        // Cek duplikat: nama_gelombang + tahun + kategori yang sama
        $exists = Gelombang::where('nama_gelombang', $request->nama_gelombang)
                           ->where('tahun', $request->tahun)
                           ->where('kategori', $request->kategori)
                           ->exists();

        if ($exists) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gelombang dengan nama, tahun, dan kategori yang sama sudah ada.');
        }

        $request->validate([
            'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
            'nama_gelombang'     => 'required|string|max:100',
            'tahun'              => 'required|integer|min:2000|max:2099',
            'kategori'           => 'required|string|max:100',
        ], [
            'id_jenis_pelatihan.required' => 'Jenis pelatihan wajib dipilih.',
            'id_jenis_pelatihan.exists'   => 'Jenis pelatihan tidak ditemukan.',
            'nama_gelombang.required'     => 'Nama gelombang wajib diisi.',
            'nama_gelombang.max'          => 'Nama gelombang maksimal 100 karakter.',
            'tahun.required'              => 'Tahun wajib diisi.',
            'tahun.integer'               => 'Tahun harus berupa angka.',
            'tahun.min'                   => 'Tahun minimal 2000.',
            'tahun.max'                   => 'Tahun maksimal 2099.',
            'kategori.required'           => 'Kategori wajib dipilih.',
        ]);

        Gelombang::create([
            'id_jenis_pelatihan' => $request->id_jenis_pelatihan,
            'nama_gelombang'     => $request->nama_gelombang,
            'tahun'              => $request->tahun,
            'kategori'           => $request->kategori,
        ]);

        return redirect()->route('gelombang.index')
                         ->with('success', 'Gelombang berhasil ditambahkan.');
    }

    public function show(Gelombang $gelombang)
    {
        $gelombang->load(['jenisPelatihan', 'angkatan' => function ($q) {
            $q->orderBy('nama_angkatan');
        }]);

        return view('admin.gelombang.show', compact('gelombang'));
    }

    public function edit(Gelombang $gelombang)
    {
        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();
        $kategoriList = ['PNBP', 'FASILITASI'];
        return view('admin.gelombang.edit', compact('gelombang', 'jenisPelatihan', 'kategoriList'));
    }

    public function update(Request $request, Gelombang $gelombang)
    {
        // Cek duplikat, kecuali diri sendiri
        $exists = Gelombang::where('nama_gelombang', $request->nama_gelombang)
                           ->where('tahun', $request->tahun)
                           ->where('kategori', $request->kategori)
                           ->where('id', '!=', $gelombang->id)
                           ->exists();

        if ($exists) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gelombang dengan nama, tahun, dan kategori yang sama sudah ada.');
        }

        $request->validate([
            'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
            'nama_gelombang'     => 'required|string|max:100',
            'tahun'              => 'required|integer|min:2000|max:2099',
            'kategori'           => 'required|string|max:100',
        ], [
            'id_jenis_pelatihan.required' => 'Jenis pelatihan wajib dipilih.',
            'id_jenis_pelatihan.exists'   => 'Jenis pelatihan tidak ditemukan.',
            'nama_gelombang.required'     => 'Nama gelombang wajib diisi.',
            'nama_gelombang.max'          => 'Nama gelombang maksimal 100 karakter.',
            'tahun.required'              => 'Tahun wajib diisi.',
            'tahun.integer'               => 'Tahun harus berupa angka.',
            'kategori.required'           => 'Kategori wajib dipilih.',
        ]);

        $gelombang->update([
            'id_jenis_pelatihan' => $request->id_jenis_pelatihan,
            'nama_gelombang'     => $request->nama_gelombang,
            'tahun'              => $request->tahun,
            'kategori'           => $request->kategori,
        ]);

        return redirect()->route('gelombang.index')
                         ->with('success', 'Gelombang berhasil diperbarui.');
    }

    public function destroy(Gelombang $gelombang)
    {
        if ($gelombang->angkatan()->count() > 0) {
            return redirect()->route('gelombang.index')
                             ->with('error', 'Gelombang tidak dapat dihapus karena masih memiliki angkatan terhubung.');
        }

        $gelombang->delete();

        return redirect()->route('gelombang.index')
                         ->with('success', 'Gelombang berhasil dihapus.');
    }

    // -------------------------------------------------------
    // Kelola relasi angkatan dalam gelombang
    // -------------------------------------------------------

    public function kelolaAngkatan(Gelombang $gelombang)
    {
        $gelombang->load('jenisPelatihan');

        $angkatanTerhubung = $this->sortAngkatan(
            Angkatan::where('id_gelombang', $gelombang->id)
        );

        $angkatanTersedia = $this->sortAngkatan(
            Angkatan::where('id_jenis_pelatihan', $gelombang->id_jenis_pelatihan)
                    ->whereNull('id_gelombang')
        );

        return view('admin.gelombang.kelola-angkatan', compact(
            'gelombang',
            'angkatanTerhubung',
            'angkatanTersedia'
        ));
    }

    public function tambahAngkatan(Request $request, Gelombang $gelombang)
    {
        $request->validate([
            'angkatan_ids'   => 'required|array|min:1',
            'angkatan_ids.*' => 'exists:angkatan,id',
        ], [
            'angkatan_ids.required' => 'Pilih minimal satu angkatan.',
            'angkatan_ids.min'      => 'Pilih minimal satu angkatan.',
        ]);

        Angkatan::whereIn('id', $request->angkatan_ids)
                ->where('id_jenis_pelatihan', $gelombang->id_jenis_pelatihan)
                ->whereNull('id_gelombang')
                ->update(['id_gelombang' => $gelombang->id]);

        return redirect()->route('gelombang.kelola-angkatan', $gelombang)
                         ->with('success', 'Angkatan berhasil ditambahkan ke gelombang.');
    }

    public function lepasAngkatan(Request $request, Gelombang $gelombang)
    {
        $request->validate([
            'angkatan_id' => 'required|exists:angkatan,id',
        ]);

        Angkatan::where('id', $request->angkatan_id)
                ->where('id_gelombang', $gelombang->id)
                ->update(['id_gelombang' => null]);

        return redirect()->route('gelombang.kelola-angkatan', $gelombang)
                         ->with('success', 'Angkatan berhasil dilepas dari gelombang.');
    }

    private function sortAngkatan($query)
    {
        return $query->get()->sortBy([
            fn($a, $b) => $b->tahun <=> $a->tahun,
            fn($a, $b) => $this->romanToInt($a->nama_angkatan) <=> $this->romanToInt($b->nama_angkatan),
        ])->values();
    }

    private function romanToInt(string $namaAngkatan): int
    {
        preg_match('/([IVXLCDM]+)$/i', $namaAngkatan, $matches);
        $roman = strtoupper($matches[1] ?? '');

        $map = ['I'=>1,'V'=>5,'X'=>10,'L'=>50,'C'=>100,'D'=>500,'M'=>1000];
        $result = 0;
        $prev = 0;

        foreach (array_reverse(str_split($roman)) as $char) {
            $val = $map[$char] ?? 0;
            $result += $val < $prev ? -$val : $val;
            $prev = $val;
        }

        return $result ?: PHP_INT_MAX;
    }
}