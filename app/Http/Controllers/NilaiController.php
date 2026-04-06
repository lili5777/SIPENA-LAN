<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AksiPerubahan;
use App\Models\JenisPelatihan;
use App\Models\JenisNilai;
use App\Models\IndikatorNilai;
use App\Models\NilaiPeserta;
use App\Models\CatatanNilai;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use App\Models\Angkatan;
use App\Models\Kelompok;
use App\Models\Penguji;
use App\Models\PicPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    private $jenisMapping = [
        'pkn'    => ['id' => 1, 'nama' => 'PKN TK II'],
        'latsar' => ['id' => 2, 'nama' => 'LATSAR'],
        'pka'    => ['id' => 3, 'nama' => 'PKA'],
        'pkp'    => ['id' => 4, 'nama' => 'PKP'],
    ];

    private function getJenisData($jenis)
    {
        if (!array_key_exists($jenis, $this->jenisMapping)) abort(404);
        return $this->jenisMapping[$jenis];
    }

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

    // =========================================================
    // HELPER — daftar tahun statis (2020 – tahun sekarang)
    // =========================================================
    private function getTahunList(): array
    {
        $tahunList = [];
        for ($y = 2020; $y <= (int) date('Y'); $y++) {
            $tahunList[] = $y;
        }
        return $tahunList;
    }

    // =========================================================
    // HELPER — daftar wilayah statis
    // =========================================================
    private function getWilayahList(): array
    {
        return [
            'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur',
            'Banten', 'Bali', 'Sumatera Utara', 'Sumatera Barat',
            'Sumatera Selatan', 'Kalimantan Timur', 'Kalimantan Selatan',
            'Sulawesi Selatan', 'Sulawesi Utara', 'Papua', 'Papua Barat',
            'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
        ];
    }

    // =========================================================
    // HELPER — konteks user (role, kelompok, angkatan)
    // =========================================================
    private function getUserContext(int $jenisPelatihanId): array
    {
        $user     = Auth::user();
        $roleName = $user->role->name ?? '';
        $roleId   = $user->role_id;

        $kelompokIds = collect();
        if ($roleName === 'coach' && $user->coach_id) {
            $kelompokIds = Kelompok::where('id_coach', $user->coach_id)
                ->where('id_jenis_pelatihan', $jenisPelatihanId)
                ->pluck('id');
        }

        if ($roleName === 'penguji' && $user->penguji_id) {
            $kelompokIds = Kelompok::where('id_penguji', $user->penguji_id)
                ->where('id_jenis_pelatihan', $jenisPelatihanId)
                ->pluck('id');
        }

        $angkatanKelompokIds = collect();
        if ($kelompokIds->isNotEmpty()) {
            $angkatanKelompokIds = Kelompok::whereIn('id', $kelompokIds)
                ->whereNotNull('id_angkatan')
                ->pluck('id_angkatan')
                ->unique()
                ->values();
        }

        $angkatanIds = collect();
        if ($roleName === 'pic') {
            $angkatanIds = PicPeserta::where('user_id', $user->id)
                ->where('jenispelatihan_id', $jenisPelatihanId)
                ->pluck('angkatan_id');
        }

        $kelompokPicIds = collect();
        if ($roleName === 'pic' && $angkatanIds->isNotEmpty()) {
            $kelompokPicIds = Kelompok::where('id_jenis_pelatihan', $jenisPelatihanId)
                ->whereIn('id_angkatan', $angkatanIds)
                ->pluck('id');
        }

        $pesertaKelompokIds = collect();
        if ($kelompokIds->isNotEmpty()) {
            $pesertaKelompokIds = DB::table('kelompok_pesertas')
                ->whereIn('id_kelompok', $kelompokIds)
                ->pluck('id_peserta');
        }

        // ── BARU: peserta dari angkatan yang dipegang PIC ─────────
        $pesertaPicIds = collect();
        if ($roleName === 'pic' && $angkatanIds->isNotEmpty()) {
            $pesertaPicIds = Pendaftaran::where('id_jenis_pelatihan', $jenisPelatihanId)
                ->whereIn('id_angkatan', $angkatanIds)
                ->whereNotNull('id_peserta')
                ->pluck('id_peserta')
                ->unique()
                ->values();
        }

        return [
            'user'                => $user,
            'roleName'            => $roleName,
            'roleId'              => $roleId,
            'kelompokIds'         => $kelompokIds,
            'angkatanKelompokIds' => $angkatanKelompokIds,
            'angkatanIds'         => $angkatanIds,
            'kelompokPicIds'      => $kelompokPicIds,
            'pesertaKelompokIds'  => $pesertaKelompokIds,
            'pesertaPicIds'       => $pesertaPicIds,   // ← BARU
        ];
    }

    // =========================================================
    // HELPER PRIVATE — apply filter kategori & wilayah ke query
    // =========================================================
    private function applyKategoriWilayahFilter($query, Request $request): void
    {
        if ($request->filled('kategori')) {
            $query->whereHas('pendaftaran.angkatan', fn($q) =>
                $q->where('kategori', $request->kategori)
            );
        }

        if ($request->filled('wilayah')) {
            $query->whereHas('pendaftaran.angkatan', fn($q) =>
                $q->where('wilayah', 'LIKE', "%{$request->wilayah}%")
            );
        }
    }

    // =========================================================
    // INDEX — Daftar peserta (tidak berubah)
    // =========================================================
    public function index(Request $request, $jenis)
    {
        $jenisData        = $this->getJenisData($jenis);
        $jenisPelatihanId = $jenisData['id'];
        $jenisPelatihan   = JenisPelatihan::findOrFail($jenisPelatihanId);

        $ctx      = $this->getUserContext($jenisPelatihanId);
        $roleName = $ctx['roleName'];

        $angkatanRomawi = $this->getRomawList();
        $tahunList      = $this->getTahunList();
        $kelompokList   = range(1, 10);
        $wilayahList    = $this->getWilayahList();

        $totalIndikatorJenis = IndikatorNilai::whereHas('jenisNilai', function ($q) use ($jenisPelatihanId) {
            $q->where('id_jenis_pelatihan', $jenisPelatihanId);
        })->count();

        $query = Peserta::query()
            ->whereHas('pendaftaran', function ($q) use ($jenisPelatihanId) {
                $q->where('id_jenis_pelatihan', $jenisPelatihanId)
                  ->whereNotNull('id_angkatan');
            })
            ->whereHas('kelompok', fn($q) =>
                $q->where('id_jenis_pelatihan', $jenisPelatihanId)
            )
            ->with(['pendaftaran' => fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId)]);

        if (in_array($roleName, ['coach', 'penguji'])) {
            if ($ctx['kelompokIds']->isNotEmpty()) {
                $kelompokTarget = $ctx['kelompokIds'];
                if ($request->filled('kelompok')) {
                    $namaKelompok = 'Kelompok ' . $request->kelompok;
                    $query->whereHas('kelompok', fn($q) =>
                        $q->where('nama_kelompok', 'LIKE', "%{$namaKelompok}%")
                          ->whereIn('kelompoks.id', $kelompokTarget)
                    );
                } else {
                    $query->whereHas('kelompok', fn($q) =>
                        $q->whereIn('kelompoks.id', $kelompokTarget)
                    );
                }
            } else {
                $query->whereRaw('1 = 0');
            }

            if ($request->filled('angkatan')) {
                $namaAngkatan = 'Angkatan ' . $request->angkatan;
                $query->whereHas('pendaftaran.angkatan', fn($q) =>
                    $q->where('nama_angkatan', 'LIKE', "%{$namaAngkatan}%")
                );
            }
            if ($request->filled('tahun')) {
                $query->whereHas('pendaftaran.angkatan', fn($q) =>
                    $q->where('tahun', 'LIKE', "%{$request->tahun}%")
                );
            }

        } elseif ($roleName === 'pic') {
            if ($ctx['angkatanIds']->isNotEmpty()) {
                $query->whereHas('pendaftaran', function ($q) use ($jenisPelatihanId, $ctx) {
                    $q->where('id_jenis_pelatihan', $jenisPelatihanId)
                      ->whereIn('id_angkatan', $ctx['angkatanIds']);
                });
            } else {
                $query->whereRaw('1 = 0');
            }

            if ($request->filled('angkatan')) {
                $namaAngkatan = 'Angkatan ' . $request->angkatan;
                $query->whereHas('pendaftaran.angkatan', fn($q) =>
                    $q->where('nama_angkatan', 'LIKE', "%{$namaAngkatan}%")
                );
            }
            if ($request->filled('tahun')) {
                $query->whereHas('pendaftaran.angkatan', fn($q) =>
                    $q->where('tahun', 'LIKE', "%{$request->tahun}%")
                );
            }
            if ($request->filled('kelompok')) {
                $namaKelompok = 'Kelompok ' . $request->kelompok;
                $query->whereHas('kelompok', fn($q) =>
                    $q->where('nama_kelompok', 'LIKE', "%{$namaKelompok}%")
                );
            }

        } else {
            if ($request->filled('angkatan')) {
                $namaAngkatan = 'Angkatan ' . $request->angkatan;
                $query->whereHas('pendaftaran.angkatan', fn($q) =>
                    $q->where('nama_angkatan', 'LIKE', "%{$namaAngkatan}%")
                );
            }
            if ($request->filled('tahun')) {
                $query->whereHas('pendaftaran.angkatan', fn($q) =>
                    $q->where('tahun', 'LIKE', "%{$request->tahun}%")
                );
            }
            if ($request->filled('kelompok')) {
                $namaKelompok = 'Kelompok ' . $request->kelompok;
                $query->whereHas('kelompok', fn($q) =>
                    $q->where('nama_kelompok', 'LIKE', "%{$namaKelompok}%")
                );
            }
        }

        $this->applyKategoriWilayahFilter($query, $request);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_lengkap', 'LIKE', "%{$term}%")
                  ->orWhere('nip_nrp', 'LIKE', "%{$term}%")
            );
        }

        $query->orderBy('ndh');
        $pesertaRaw = $query->paginate(15)->withQueryString();

        $kelompokFilter = null;
        if ($request->filled('kelompok')) {
            $namaKelompok   = 'Kelompok ' . $request->kelompok;
            $kelompokFilter = Kelompok::where('nama_kelompok', 'LIKE', "%{$namaKelompok}%")
                ->where('id_jenis_pelatihan', $jenisPelatihanId)
                ->first();
        }

        $peserta = $pesertaRaw->through(function ($p) use (
            $jenisPelatihanId, $totalIndikatorJenis, $ctx, $roleName
        ) {
            $kelompok = Kelompok::whereHas('peserta', fn($q) => $q->where('peserta.id', $p->id))
                ->where('id_jenis_pelatihan', $jenisPelatihanId)
                ->with('angkatan')
                ->first();

            $pendaftaran = $p->pendaftaran
                ->where('id_jenis_pelatihan', $jenisPelatihanId)
                ->first();

            $sudahDinilai = NilaiPeserta::where('id_peserta', $p->id)
                ->whereHas('indikatorNilai.jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
                ->whereNotNull('nilai')
                ->count();

            $bisaDinilaiUser = true;
            if (in_array($roleName, ['coach', 'penguji'])) {
                $bisaDinilaiUser = $ctx['pesertaKelompokIds']->contains($p->id);
            }

            $p->kelompokInfo    = $kelompok;
            $p->pendaftaranId   = $pendaftaran?->id;
            $p->totalIndikator  = $totalIndikatorJenis;
            $p->sudahDinilai    = $sudahDinilai;
            $p->bisaDinilaiUser = $bisaDinilaiUser;

            return $p;
        });

        return view('admin.nilai.index', compact(
            'jenis', 'jenisPelatihan', 'peserta',
            'angkatanRomawi', 'tahunList', 'kelompokList',
            'wilayahList', 'kelompokFilter'
        ));
    }

    // =========================================================
    // GET DATA — AJAX
    // =========================================================
    public function getData(Request $request, $pesertaId)
    {
        try {
            $peserta     = Peserta::findOrFail($pesertaId);
            $pendaftaran = Pendaftaran::where('id_peserta', $pesertaId)
                ->whereNotNull('id_angkatan')
                ->latest('id')
                ->first();

            if (!$pendaftaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta belum terdaftar di angkatan manapun.'
                ], 404);
            }

            $jenisPelatihanId = $pendaftaran->id_jenis_pelatihan;
            $aksiPerubahan = AksiPerubahan::where('id_pendaftar', $pendaftaran->id)
                ->select('judul', 'kategori_aksatika')
                ->first();
            $user             = Auth::user();
            $roleId           = $user->role_id;
            $roleName         = $user->role->name ?? '';

            $pesertaMilikUser = true;
            if (in_array($roleName, ['coach', 'penguji'])) {
                $ctx = $this->getUserContext($jenisPelatihanId);
                $pesertaMilikUser = $ctx['pesertaKelompokIds']->contains((int) $pesertaId);
            }

            $jenisNilaiList = JenisNilai::with([
                'indikatorNilai'                 => fn($q) => $q->orderBy('id'),
                'indikatorNilai.detailIndikator' => fn($q) => $q->orderBy('level'),
                'indikatorNilai.roles'           => fn($q) => $q->select('roles.id', 'roles.name'),
            ])
            ->where('id_jenis_pelatihan', $jenisPelatihanId)
            ->orderBy('id')
            ->get();

            $jenisNilaiList->each(function ($jn) use ($roleId, $roleName, $pesertaMilikUser) {
                $jn->indikatorNilai->each(function ($ind) use ($roleId, $roleName, $pesertaMilikUser) {
                    if ($roleName === 'admin') {
                        $ind->user_dapat_nilai = true;
                        return;
                    }
                    if (!$pesertaMilikUser) {
                        $ind->user_dapat_nilai = false;
                        return;
                    }
                    $ind->user_dapat_nilai = $ind->roles->isNotEmpty() &&
                        $ind->roles->contains('id', $roleId);
                });
            });

            $existingNilai = NilaiPeserta::where('id_peserta', $pesertaId)
                ->whereHas('indikatorNilai.jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
                ->get()
                ->keyBy('id_indikator_nilai')
                ->map(fn($n) => $n->nilai);

            $existingCatatan = CatatanNilai::where('id_peserta', $pesertaId)
                ->whereHas('jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
                ->get()
                ->keyBy('id_jenis_nilai')
                ->map(fn($c) => $c->catatan);

            return response()->json([
                'success'            => true,
                'jenis_nilai'        => $jenisNilaiList,
                'existing_nilai'     => $existingNilai,
                'existing_catatan'   => $existingCatatan,
                'role_name'          => $roleName,
                'peserta_milik_user' => $pesertaMilikUser,
                'aksi_perubahan'     => $aksiPerubahan,
            ]);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =========================================================
    // SIMPAN NILAI — AJAX
    // =========================================================
    public function simpanNilai(Request $request)
    {
        $request->validate([
            'peserta_id'         => 'required|exists:peserta,id',
            'indikator_nilai_id' => 'required|exists:indikator_nilai,id',
            'nilai_input'        => 'required|numeric|min:0|max:100',
        ]);

        try {
            $user      = Auth::user();
            $roleName  = $user->role->name ?? '';
            $roleId    = $user->role_id;
            $indikator = IndikatorNilai::with('roles', 'jenisNilai')->findOrFail($request->indikator_nilai_id);
            $jenisPelatihanId = $indikator->jenisNilai->id_jenis_pelatihan;

            if (in_array($roleName, ['coach', 'penguji'])) {
                $ctx = $this->getUserContext($jenisPelatihanId);
                if (!$ctx['pesertaKelompokIds']->contains((int) $request->peserta_id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat menilai peserta dari kelompok Anda.',
                    ], 403);
                }
            }

            if ($roleName !== 'admin') {
                if ($indikator->roles->isEmpty() || !$indikator->roles->contains('id', $roleId)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk mengisi nilai indikator ini.',
                    ], 403);
                }
            }

            NilaiPeserta::updateOrCreate(
                [
                    'id_peserta'         => $request->peserta_id,
                    'id_indikator_nilai' => $request->indikator_nilai_id,
                ],
                ['nilai' => $request->nilai_input]
            );

            $konversi = round(($request->nilai_input / 100) * $indikator->bobot, 2);

            return response()->json([
                'success'        => true,
                'message'        => 'Nilai berhasil disimpan.',
                'nilai_input'    => $request->nilai_input,
                'nilai_konversi' => $konversi,
                'keterangan'     => "{$request->nilai_input} / 100 × {$indikator->bobot}% = {$konversi}",
            ]);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =========================================================
    // SIMPAN CATATAN — AJAX
    // =========================================================
    public function simpanCatatan(Request $request)
    {
        $request->validate([
            'peserta_id'     => 'required|exists:peserta,id',
            'jenis_nilai_id' => 'required|exists:jenis_nilai,id',
            'catatan'        => 'nullable|string|max:2000',
        ]);

        try {
            $user     = Auth::user();
            $roleName = $user->role->name ?? '';

            if (in_array($roleName, ['coach', 'penguji'])) {
                $jenisNilai = JenisNilai::findOrFail($request->jenis_nilai_id);
                $ctx        = $this->getUserContext($jenisNilai->id_jenis_pelatihan);
                if (!$ctx['pesertaKelompokIds']->contains((int) $request->peserta_id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat memberi catatan untuk peserta dari kelompok Anda.',
                    ], 403);
                }
            }

            CatatanNilai::updateOrCreate(
                [
                    'id_peserta'     => $request->peserta_id,
                    'id_jenis_nilai' => $request->jenis_nilai_id,
                ],
                [
                    'id_user' => Auth::id(),
                    'catatan' => $request->catatan,
                ]
            );

            return response()->json(['success' => true, 'message' => 'Catatan berhasil disimpan.']);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =========================================================
    // REKAP
    // =========================================================
    public function rekap(Request $request, $jenis)
    {
        $jenisData        = $this->getJenisData($jenis);
        $jenisPelatihanId = $jenisData['id'];
        $jenisPelatihan   = JenisPelatihan::findOrFail($jenisPelatihanId);

        $ctx      = $this->getUserContext($jenisPelatihanId);
        $roleName = $ctx['roleName'];

        // ── Filter statis ─────────────────────────────────────────
        $angkatanRomawi = $this->getRomawList();
        $tahunList      = $this->getTahunList();
        $kelompokList   = range(1, 10);
        $wilayahList    = $this->getWilayahList();

        // ── Daftar penguji untuk filter dropdown ──────────────────
        $pengujiList = Penguji::whereHas('kelompok', fn($q) =>
            $q->where('id_jenis_pelatihan', $jenisPelatihanId)
        )->orderBy('nama')->get(['id', 'nama', 'nip']);

        $jenisNilaiList = JenisNilai::where('id_jenis_pelatihan', $jenisPelatihanId)
            ->withCount('indikatorNilai')
            ->with(['indikatorNilai' => fn($q) => $q->orderBy('id')])
            ->orderBy('id')
            ->get();

        $indikatorPerJenis = $jenisNilaiList->mapWithKeys(fn($jn) => [
            $jn->id => $jn->indikator_nilai_count
        ]);

        // ── Base query — SEMUA peserta ────────────────────────────
        $query = Peserta::query()
            ->whereHas('pendaftaran', fn($q) =>
                $q->where('id_jenis_pelatihan', $jenisPelatihanId)->whereNotNull('id_angkatan')
            )
            ->whereHas('kelompok', fn($q) =>
                $q->where('id_jenis_pelatihan', $jenisPelatihanId)
            );

        // ── Filter angkatan ───────────────────────────────────────
        if ($request->filled('angkatan')) {
            $namaAngkatan = 'Angkatan ' . $request->angkatan;
            $query->whereHas('pendaftaran.angkatan', fn($q) =>
                $q->where('nama_angkatan', 'LIKE', "%{$namaAngkatan}%")
            );
        }

        // ── Filter tahun ──────────────────────────────────────────
        if ($request->filled('tahun')) {
            $query->whereHas('pendaftaran.angkatan', fn($q) =>
                $q->where('tahun', 'LIKE', "%{$request->tahun}%")
            );
        }

        // ── Filter kelompok ───────────────────────────────────────
        if ($request->filled('kelompok')) {
            $namaKelompok = 'Kelompok ' . $request->kelompok;
            $query->whereHas('kelompok', fn($q) =>
                $q->where('nama_kelompok', 'LIKE', "%{$namaKelompok}%")
                  ->where('id_jenis_pelatihan', $jenisPelatihanId)
            );
        }

        // ── Filter penguji ────────────────────────────────────────
        if ($request->filled('penguji')) {
            $query->whereHas('kelompok', fn($q) =>
                $q->where('id_penguji', $request->penguji)
                  ->where('id_jenis_pelatihan', $jenisPelatihanId)
            );
        }

        // ── Filter kategori & wilayah ─────────────────────────────
        $this->applyKategoriWilayahFilter($query, $request);

        // ── Search ────────────────────────────────────────────────
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_lengkap', 'LIKE', "%{$term}%")
                  ->orWhere('nip_nrp', 'LIKE', "%{$term}%")
            );
        }

        // ── Sorting dengan prioritas per role ─────────────────────
        //
        // Penguji → peserta kelompoknya muncul duluan
        // PIC     → peserta angkatannya muncul duluan
        // Lainnya → urut NDH biasa
        //
        if ($roleName === 'penguji' && $ctx['pesertaKelompokIds']->isNotEmpty()) {

            $prioritasIds = $ctx['pesertaKelompokIds']->toArray();
            $query->selectRaw(
                'peserta.*, CASE WHEN peserta.id IN (' .
                implode(',', array_map('intval', $prioritasIds)) .
                ') THEN 0 ELSE 1 END AS prioritas_urut'
            )->orderBy('prioritas_urut')->orderBy('ndh');

        } elseif ($roleName === 'pic' && $ctx['pesertaPicIds']->isNotEmpty()) {

            $prioritasIds = $ctx['pesertaPicIds']->toArray();
            $query->selectRaw(
                'peserta.*, CASE WHEN peserta.id IN (' .
                implode(',', array_map('intval', $prioritasIds)) .
                ') THEN 0 ELSE 1 END AS prioritas_urut'
            )->orderBy('prioritas_urut')->orderBy('ndh');

        } else {
            $query->orderBy('ndh');
        }

        // ── Pagination (20 per halaman) ───────────────────────────
        $pesertaPaginated = $query->paginate(20)->withQueryString();

        // ── Kumpulkan ID peserta halaman ini saja ─────────────────
        $pesertaIds = $pesertaPaginated->pluck('id');

        // ── Ambil semua nilai & catatan sekaligus ─────────────────
        $semuaNilai = NilaiPeserta::with('indikatorNilai.jenisNilai')
            ->whereIn('id_peserta', $pesertaIds)
            ->whereHas('indikatorNilai.jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
            ->get()
            ->groupBy('id_peserta');

        $semuaCatatan = CatatanNilai::whereIn('id_peserta', $pesertaIds)
            ->whereHas('jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
            ->get()
            ->groupBy('id_peserta');

        $semuaKelompok = DB::table('kelompok_pesertas')
            ->join('kelompoks', 'kelompoks.id', '=', 'kelompok_pesertas.id_kelompok')
            ->whereIn('kelompok_pesertas.id_peserta', $pesertaIds)
            ->where('kelompoks.id_jenis_pelatihan', $jenisPelatihanId)
            ->select('kelompok_pesertas.id_peserta', 'kelompoks.id', 'kelompoks.nama_kelompok', 'kelompoks.id_penguji')
            ->get()
            ->keyBy('id_peserta');

        // ── Susun rekapData per halaman ───────────────────────────
        $rekapData = $pesertaPaginated->map(function ($p) use (
            $jenisPelatihanId, $jenisNilaiList, $indikatorPerJenis,
            $semuaNilai, $semuaCatatan, $semuaKelompok,
            $ctx, $roleName
        ) {
            $kelompokRow = $semuaKelompok->get($p->id);
            $nilaiList   = $semuaNilai->get($p->id, collect());
            $catatanList = $semuaCatatan->get($p->id, collect())
                ->keyBy('id_jenis_nilai')
                ->map(fn($c) => $c->catatan);

            $nilaiPerJenis  = [];
            $totalNilai     = 0;
            $totalTerisi    = 0;
            $totalIndikator = 0;

            foreach ($jenisNilaiList as $jn) {
                $nilaiJn = $nilaiList->filter(
                    fn($n) => $n->indikatorNilai?->jenisNilai?->id === $jn->id
                );

                $sumKonversi = round(
                    $nilaiJn->sum(fn($n) => ($n->nilai / 100) * ($n->indikatorNilai->bobot ?? 0)),
                    2
                );
                $avgInput = $nilaiJn->count() > 0 ? round($nilaiJn->avg('nilai'), 2) : null;
                $terisi   = $nilaiJn->whereNotNull('nilai')->count();

                $detailIndikator = $jn->indikatorNilai->map(function ($ind) use ($nilaiJn) {
                    $nilaiRecord = $nilaiJn->first(fn($n) => $n->id_indikator_nilai == $ind->id);
                    return [
                        'nama_indikator'  => $ind->name,
                        'bobot_indikator' => $ind->bobot,
                        'nilai_input'     => $nilaiRecord ? $nilaiRecord->nilai : null,
                    ];
                })->values()->toArray();

                $nilaiPerJenis[$jn->id] = [
                    'sum_konversi'     => $sumKonversi,
                    'avg_input'        => $avgInput,
                    'terisi'           => $terisi,
                    'max_jenis'        => $jn->bobot,
                    'detail_indikator' => $detailIndikator,
                ];

                $totalNilai     += $sumKonversi;
                $totalTerisi    += $terisi;
                $totalIndikator += ($indikatorPerJenis[$jn->id] ?? 0);
            }

            // ── Flag prioritas per role ───────────────────────────
            $isPrioritasUser = match ($roleName) {
                'penguji' => $ctx['pesertaKelompokIds']->contains($p->id),
                'pic'     => $ctx['pesertaPicIds']->contains($p->id),
                default   => false,
            };

            return [
                'peserta_id'        => $p->id,
                'nama'              => $p->nama_lengkap,
                'nip'               => $p->nip_nrp,
                'ndh'               => $p->ndh,
                'kelompok'          => $kelompokRow?->nama_kelompok,
                'nilai_per_jenis'   => $nilaiPerJenis,
                'catatan'           => $catatanList,
                'total_nilai'       => round($totalNilai, 2),
                'kelengkapan'       => $totalIndikator > 0
                    ? round(($totalTerisi / $totalIndikator) * 100) : 0,
                'is_prioritas_user' => $isPrioritasUser,
            ];
        });

        return view('admin.nilai.rekap', compact(
            'jenis', 'jenisPelatihan', 'rekapData',
            'jenisNilaiList', 'angkatanRomawi', 'tahunList', 'kelompokList',
            'wilayahList', 'pengujiList', 'pesertaPaginated'
        ));
    }
}