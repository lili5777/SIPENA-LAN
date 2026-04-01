<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JenisPelatihan;
use App\Models\JenisNilai;
use App\Models\IndikatorNilai;
use App\Models\NilaiPeserta;
use App\Models\CatatanNilai;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use App\Models\Angkatan;
use App\Models\Kelompok;
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

        return [
            'user'                => $user,
            'roleName'            => $roleName,
            'roleId'              => $roleId,
            'kelompokIds'         => $kelompokIds,
            'angkatanKelompokIds' => $angkatanKelompokIds,
            'angkatanIds'         => $angkatanIds,
            'kelompokPicIds'      => $kelompokPicIds,
            'pesertaKelompokIds'  => $pesertaKelompokIds,
        ];
    }

    // =========================================================
    // INDEX — Daftar peserta
    // =========================================================
    public function index(Request $request, $jenis)
    {
        $jenisData        = $this->getJenisData($jenis);
        $jenisPelatihanId = $jenisData['id'];
        $jenisPelatihan   = JenisPelatihan::findOrFail($jenisPelatihanId);

        $ctx      = $this->getUserContext($jenisPelatihanId);
        $roleName = $ctx['roleName'];

        // ── Filter statis ─────────────────────────────────────────
        $angkatanRomawi = $this->getRomawList();   // ['I','II',...]
        $tahunList      = $this->getTahunList();   // [2020, 2021, ...]
        $kelompokList   = range(1, 10);            // [1,2,...,10]

        $totalIndikatorJenis = IndikatorNilai::whereHas('jenisNilai', function ($q) use ($jenisPelatihanId) {
            $q->where('id_jenis_pelatihan', $jenisPelatihanId);
        })->count();

        // ── Base query ────────────────────────────────────────────
        $query = Peserta::query()
            ->whereHas('pendaftaran', function ($q) use ($jenisPelatihanId) {
                $q->where('id_jenis_pelatihan', $jenisPelatihanId)
                  ->whereNotNull('id_angkatan');
            })
            ->whereHas('kelompok', fn($q) =>
                $q->where('id_jenis_pelatihan', $jenisPelatihanId)
            )
            ->with(['pendaftaran' => fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId)]);

        // ── Filter role ───────────────────────────────────────────
        if (in_array($roleName, ['coach', 'penguji'])) {
            if ($ctx['kelompokIds']->isNotEmpty()) {
                $kelompokTarget = $ctx['kelompokIds'];
                // filter kelompok statis → LIKE nama_kelompok
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

            // filter angkatan → LIKE nama_angkatan
            if ($request->filled('angkatan')) {
                $namaAngkatan = 'Angkatan ' . $request->angkatan;
                $query->whereHas('pendaftaran.angkatan', fn($q) =>
                    $q->where('nama_angkatan', 'LIKE', "%{$namaAngkatan}%")
                );
            }
            // filter tahun
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
            // admin / evaluator
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

        // ── Search ────────────────────────────────────────────────
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_lengkap', 'LIKE', "%{$term}%")
                  ->orWhere('nip_nrp', 'LIKE', "%{$term}%")
            );
        }

        $query->orderBy('ndh');
        $pesertaRaw = $query->paginate(15)->withQueryString();

        // ── Kelompok filter aktif (untuk info bar link laporan) ───
        $kelompokFilter = null;
        if ($request->filled('kelompok')) {
            $namaKelompok   = 'Kelompok ' . $request->kelompok;
            $kelompokFilter = Kelompok::where('nama_kelompok', 'LIKE', "%{$namaKelompok}%")
                ->where('id_jenis_pelatihan', $jenisPelatihanId)
                ->first();
        }

        // ── Metadata per peserta ──────────────────────────────────
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
            'kelompokFilter'
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

        $jenisNilaiList = JenisNilai::where('id_jenis_pelatihan', $jenisPelatihanId)
            ->withCount('indikatorNilai')
            ->with(['indikatorNilai' => fn($q) => $q->orderBy('id')])
            ->orderBy('id')
            ->get();

        $indikatorPerJenis = $jenisNilaiList->mapWithKeys(fn($jn) => [
            $jn->id => $jn->indikator_nilai_count
        ]);

        // ── Query peserta ─────────────────────────────────────────
        $query = Peserta::query()
            ->whereHas('pendaftaran', fn($q) =>
                $q->where('id_jenis_pelatihan', $jenisPelatihanId)->whereNotNull('id_angkatan')
            )
            ->whereHas('kelompok', fn($q) =>
                $q->where('id_jenis_pelatihan', $jenisPelatihanId)
            );

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
                $query->whereHas('pendaftaran', fn($q) =>
                    $q->where('id_jenis_pelatihan', $jenisPelatihanId)
                      ->whereIn('id_angkatan', $ctx['angkatanIds'])
                );
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

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_lengkap', 'LIKE', "%{$term}%")
                  ->orWhere('nip_nrp', 'LIKE', "%{$term}%")
            );
        }

        $pesertaList = $query->orderBy('ndh')->get();

        $rekapData = $pesertaList->map(function ($p) use (
            $jenisPelatihanId, $jenisNilaiList, $indikatorPerJenis
        ) {
            $kelompok = Kelompok::whereHas('peserta', fn($q) => $q->where('peserta.id', $p->id))
                ->where('id_jenis_pelatihan', $jenisPelatihanId)->first();

            $nilaiList = NilaiPeserta::where('id_peserta', $p->id)
                ->with('indikatorNilai.jenisNilai')
                ->whereHas('indikatorNilai.jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
                ->get();

            $catatanList = CatatanNilai::where('id_peserta', $p->id)
                ->whereHas('jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
                ->get()->keyBy('id_jenis_nilai')->map(fn($c) => $c->catatan);

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

            return [
                'peserta_id'      => $p->id,
                'nama'            => $p->nama_lengkap,
                'nip'             => $p->nip_nrp,
                'ndh'             => $p->ndh,
                'kelompok'        => $kelompok?->nama_kelompok,
                'nilai_per_jenis' => $nilaiPerJenis,
                'catatan'         => $catatanList,
                'total_nilai'     => round($totalNilai, 2),
                'kelengkapan'     => $totalIndikator > 0
                    ? round(($totalTerisi / $totalIndikator) * 100) : 0,
            ];
        });

        return view('admin.nilai.rekap', compact(
            'jenis', 'jenisPelatihan', 'rekapData',
            'jenisNilaiList', 'angkatanRomawi', 'tahunList', 'kelompokList'
        ));
    }
}