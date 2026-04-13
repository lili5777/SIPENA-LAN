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

    private function getRomawList(): array
    {
        $map = [
            1000=>'M',900=>'CM',500=>'D',400=>'CD',
            100=>'C',90=>'XC',50=>'L',40=>'XL',
            10=>'X',9=>'IX',5=>'V',4=>'IV',1=>'I',
        ];
        $result = [];
        for ($i = 1; $i <= 80; $i++) {
            $n=$i; $str='';
            foreach ($map as $val=>$rom) {
                while ($n>=$val) { $str.=$rom; $n-=$val; }
            }
            $result[] = $str;
        }
        return $result;
    }

    private function getTahunList(): array
    {
        $list = [];
        for ($y = 2020; $y <= (int) date('Y'); $y++) $list[] = $y;
        return $list;
    }

    private function getWilayahList(): array
    {
        return [
            'DKI Jakarta','Jawa Barat','Jawa Tengah','Jawa Timur',
            'Banten','Bali','Sumatera Utara','Sumatera Barat',
            'Sumatera Selatan','Kalimantan Timur','Kalimantan Selatan',
            'Sulawesi Selatan','Sulawesi Utara','Papua','Papua Barat',
            'Nusa Tenggara Barat','Nusa Tenggara Timur',
        ];
    }

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
                ->unique()->values();
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

        $pesertaPicIds = collect();
        if ($roleName === 'pic' && $angkatanIds->isNotEmpty()) {
            $pesertaPicIds = Pendaftaran::where('id_jenis_pelatihan', $jenisPelatihanId)
                ->whereIn('id_angkatan', $angkatanIds)
                ->whereNotNull('id_peserta')
                ->pluck('id_peserta')
                ->unique()->values();
        }

        return compact(
            'user','roleName','roleId',
            'kelompokIds','angkatanKelompokIds','angkatanIds',
            'kelompokPicIds','pesertaKelompokIds','pesertaPicIds'
        );
    }

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

    private function getRekapIzin($jenisNilaiList, string $roleName, int $roleId): array
{
    // Admin: semua tampil, total tampil
    if ($roleName === 'admin') {
        $izinPerJenis = $jenisNilaiList->mapWithKeys(fn($jn) => [
            $jn->id => $jn->indikatorNilai->pluck('id')->toArray()
        ])->toArray();
        return [
            'jenisNilaiTerfilter'   => $jenisNilaiList,
            'izinIndikatorPerJenis' => $izinPerJenis,
            'showTotal'             => true,
        ];
    }

    // PIC, Coach, Penguji: hanya indikator yang memiliki akses role tersebut
    $izinPerJenis = [];
    $jenisNilaiTerfilter = $jenisNilaiList->filter(function ($jn) use ($roleId, &$izinPerJenis) {
        $diizinkan = $jn->indikatorNilai->filter(
            fn($ind) => $ind->roles->isNotEmpty() && $ind->roles->contains('id', $roleId)
        );
        if ($diizinkan->isEmpty()) return false;
        $izinPerJenis[$jn->id] = $diizinkan->pluck('id')->toArray();
        return true;
    })->values();

    return [
        'jenisNilaiTerfilter'   => $jenisNilaiTerfilter,
        'izinIndikatorPerJenis' => $izinPerJenis,
        'showTotal'             => false, // total disembunyikan untuk non-admin
    ];
}
    // =========================================================
// INDEX — Spreadsheet full page
// =========================================================
// =========================================================
// INDEX — Spreadsheet full page
// =========================================================
public function index(Request $request, $jenis)
{
    $jenisData        = $this->getJenisData($jenis);
    $jenisPelatihanId = $jenisData['id'];
    $jenisPelatihan   = JenisPelatihan::findOrFail($jenisPelatihanId);

    $ctx      = $this->getUserContext($jenisPelatihanId);
    $roleName = $ctx['roleName'];
    $roleId   = $ctx['roleId'];

    $angkatanRomawi = $this->getRomawList();
    $tahunList      = $this->getTahunList();
    $kelompokList   = range(1, 10);
    $wilayahList    = $this->getWilayahList();

    $jnColors = ['#285496', '#2d7dd2', '#1b998b', '#e84855', '#ff9f1c', '#3d405b'];

    // Ambil semua jenis nilai beserta indikator
    $jenisNilaiAll = JenisNilai::where('id_jenis_pelatihan', $jenisPelatihanId)
        ->with([
            'indikatorNilai'                 => fn($q) => $q->orderBy('id'),
            'indikatorNilai.roles'           => fn($q) => $q->select('roles.id', 'roles.name'),
            'indikatorNilai.detailIndikator' => fn($q) => $q->orderBy('level'),
        ])
        ->orderBy('id')
        ->get();

    // ── FILTER BERDASARKAN ROLE ─────────────────────────────────────
    // Admin: semua tampil dan bisa diedit
    // PIC, Coach, Penguji: hanya indikator yang memiliki akses role tersebut yang tampil
    // ==================================================================
    
    if ($roleName === 'admin') {
        // Admin: semua indikator tampil dan bisa diedit
        $jenisNilaiList = $jenisNilaiAll;
        foreach ($jenisNilaiList as $jn) {
            foreach ($jn->indikatorNilai as $ind) {
                $ind->userDapatNilai = true;
            }
        }
    } 
    else {
        // PIC, Coach, Penguji: HANYA indikator yang diizinkan yang TAMPIL
        $jenisNilaiList = $jenisNilaiAll->filter(function ($jn) use ($roleId) {
            // Cek apakah jenis nilai ini memiliki setidaknya 1 indikator yang diizinkan role ini
            $hasAllowed = $jn->indikatorNilai->contains(function ($ind) use ($roleId) {
                return $ind->roles->isNotEmpty() && $ind->roles->contains('id', $roleId);
            });
            return $hasAllowed;
        })->values();
        
        // Filter indikator dalam setiap jenis nilai (hanya yang diizinkan)
        foreach ($jenisNilaiList as $jn) {
            $allowedIndicators = $jn->indikatorNilai->filter(function ($ind) use ($roleId) {
                return $ind->roles->isNotEmpty() && $ind->roles->contains('id', $roleId);
            })->values();
            
            $jn->setRelation('indikatorNilai', $allowedIndicators);
            
            foreach ($allowedIndicators as $ind) {
                $ind->userDapatNilai = true;
            }
        }
    }

    // ── QUERY PESERTA ────────────────────────────────────────────────
    $query = Peserta::query()
        ->whereHas('pendaftaran', fn($q) =>
            $q->where('id_jenis_pelatihan', $jenisPelatihanId)->whereNotNull('id_angkatan')
        )
        ->whereHas('kelompok', fn($q) =>
            $q->where('id_jenis_pelatihan', $jenisPelatihanId)
        )
        ->with(['pendaftaran' => fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId)]);

    // Filter berdasarkan role (akses ke peserta)
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
    } elseif ($roleName === 'pic') {
        if ($ctx['angkatanIds']->isNotEmpty()) {
            $query->whereHas('pendaftaran', fn($q) =>
                $q->where('id_jenis_pelatihan', $jenisPelatihanId)
                  ->whereIn('id_angkatan', $ctx['angkatanIds'])
            );
        } else {
            $query->whereRaw('1 = 0');
        }
    }

    // Filter lainnya (angkatan, tahun, kelompok, kategori, wilayah, search)
    if ($request->filled('angkatan')) {
        $query->whereHas('pendaftaran.angkatan', fn($q) =>
            $q->where('nama_angkatan', 'Angkatan ' . $request->angkatan)
        );
    }
    if ($request->filled('tahun')) {
        $query->whereHas('pendaftaran.angkatan', fn($q) =>
            $q->where('tahun', 'LIKE', "%{$request->tahun}%")
        );
    }
    if ($request->filled('kelompok') && !in_array($roleName, ['coach', 'penguji'])) {
        $query->whereHas('kelompok', fn($q) =>
            $q->where('nama_kelompok', 'LIKE', "%Kelompok {$request->kelompok}%")
        );
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
    $pesertaRaw = $query->paginate(20)->withQueryString();

    $pesertaIds = $pesertaRaw->pluck('id');

    // Kumpulkan ID indikator yang ditampilkan (untuk efisiensi query nilai)
    $allowedIndikatorIds = collect();
    foreach ($jenisNilaiList as $jn) {
        foreach ($jn->indikatorNilai as $ind) {
            $allowedIndikatorIds->push($ind->id);
        }
    }
    $allowedIndikatorIds = $allowedIndikatorIds->unique();

    // Ambil nilai hanya untuk indikator yang ditampilkan
    $semuaNilai = NilaiPeserta::whereIn('id_peserta', $pesertaIds)
        ->whereIn('id_indikator_nilai', $allowedIndikatorIds)
        ->get()
        ->groupBy('id_peserta');

    // Ambil kelompok peserta
    $kelompokRows = Kelompok::with([
            'angkatan',
            'peserta' => fn($q) => $q->whereIn('peserta.id', $pesertaIds)->select('peserta.id'),
        ])
        ->where('id_jenis_pelatihan', $jenisPelatihanId)
        ->whereHas('peserta', fn($q) => $q->whereIn('peserta.id', $pesertaIds))
        ->get();

    $semuaKelompok = collect();
    foreach ($kelompokRows as $kelompok) {
        foreach ($kelompok->peserta as $p) {
            if ($pesertaIds->contains($p->id) && !$semuaKelompok->has($p->id)) {
                $semuaKelompok->put($p->id, (object)[
                    'nama_kelompok' => $kelompok->nama_kelompok,
                    'nama_angkatan' => $kelompok->angkatan->nama_angkatan ?? '-',
                ]);
            }
        }
    }

    // Mapping nilai ke peserta
    $peserta = $pesertaRaw->through(function ($p) use (
        $semuaNilai, $semuaKelompok, $jenisNilaiList, $ctx, $roleName
    ) {
        $nilaiRows   = $semuaNilai->get($p->id, collect());
        $kelompokRow = $semuaKelompok->get($p->id);

        $nilaiMap = $nilaiRows->keyBy('id_indikator_nilai')
            ->map(fn($n) => $n->nilai !== null ? (float)$n->nilai : null);

        // Hitung total hanya dari indikator yang ditampilkan
        $totalNilai = 0;
        foreach ($jenisNilaiList as $jn) {
            foreach ($jn->indikatorNilai as $ind) {
                $v = $nilaiMap->get($ind->id);
                if ($v !== null) {
                    $totalNilai += ($v / 100) * $ind->bobot;
                }
            }
        }

        // Cek apakah user bisa menilai peserta ini
        $bisaDinilaiUser = true;
        if (in_array($roleName, ['coach', 'penguji'])) {
            $bisaDinilaiUser = $ctx['pesertaKelompokIds']->contains($p->id);
        } elseif ($roleName === 'pic') {
            $bisaDinilaiUser = $ctx['pesertaPicIds']->contains($p->id);
        }

        $p->kelompokInfo = $kelompokRow ? (object)[
            'nama_kelompok' => $kelompokRow->nama_kelompok,
            'angkatan'      => (object)['nama_angkatan' => $kelompokRow->nama_angkatan ?? '-'],
        ] : null;

        $p->nilaiMap        = $nilaiMap;
        $p->totalNilai      = round($totalNilai, 2);
        $p->bisaDinilaiUser = $bisaDinilaiUser;

        return $p;
    });

    return view('admin.nilai.index', compact(
        'jenis', 'jenisPelatihan', 'peserta',
        'angkatanRomawi', 'tahunList', 'kelompokList',
        'wilayahList', 'jenisNilaiList', 'jnColors'
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
                return response()->json(['success'=>false,'message'=>'Peserta belum terdaftar.'], 404);
            }

            $jenisPelatihanId = $pendaftaran->id_jenis_pelatihan;
            $aksiPerubahan    = AksiPerubahan::where('id_pendaftar', $pendaftaran->id)
                ->select('judul','kategori_aksatika')->first();

            $user     = Auth::user();
            $roleId   = $user->role_id;
            $roleName = $user->role->name ?? '';

            $pesertaMilikUser = true;
            if (in_array($roleName, ['coach','penguji'])) {
                $ctx = $this->getUserContext($jenisPelatihanId);
                $pesertaMilikUser = $ctx['pesertaKelompokIds']->contains((int)$pesertaId);
            }

            $jenisNilaiList = JenisNilai::with([
                'indikatorNilai'                 => fn($q) => $q->orderBy('id'),
                'indikatorNilai.detailIndikator' => fn($q) => $q->orderBy('level'),
                'indikatorNilai.roles'           => fn($q) => $q->select('roles.id','roles.name'),
            ])
            ->where('id_jenis_pelatihan', $jenisPelatihanId)
            ->orderBy('id')->get();

            $jenisNilaiList->each(function ($jn) use ($roleId, $roleName, $pesertaMilikUser) {
                $jn->indikatorNilai->each(function ($ind) use ($roleId, $roleName, $pesertaMilikUser) {
                    if ($roleName === 'admin') { $ind->user_dapat_nilai = true; return; }
                    if (!$pesertaMilikUser)    { $ind->user_dapat_nilai = false; return; }
                    $ind->user_dapat_nilai = $ind->roles->isNotEmpty() && $ind->roles->contains('id', $roleId);
                });
            });

            $existingNilai = NilaiPeserta::where('id_peserta', $pesertaId)
                ->whereHas('indikatorNilai.jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
                ->get()->keyBy('id_indikator_nilai')->map(fn($n) => $n->nilai);

            $existingCatatan = CatatanNilai::where('id_peserta', $pesertaId)
                ->whereHas('jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
                ->get()->keyBy('id_jenis_nilai')->map(fn($c) => $c->catatan);

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
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 500);
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
            'nilai_input'        => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $user      = Auth::user();
            $roleName  = $user->role->name ?? '';
            $roleId    = $user->role_id;
            $indikator = IndikatorNilai::with('roles','jenisNilai')
                ->findOrFail($request->indikator_nilai_id);
            $jenisPelatihanId = $indikator->jenisNilai->id_jenis_pelatihan;

            if (in_array($roleName, ['coach','penguji'])) {
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

            $nilaiInput = $request->input('nilai_input');

            // Jika kosong/null → hapus nilai (cell dikosongkan user)
            if ($nilaiInput === null || $nilaiInput === '') {
                NilaiPeserta::where('id_peserta', $request->peserta_id)
                    ->where('id_indikator_nilai', $request->indikator_nilai_id)
                    ->delete();

                return response()->json([
                    'success'     => true,
                    'message'     => 'Nilai berhasil dihapus.',
                    'nilai_input' => null,
                    'deleted'     => true,
                ]);
            }

            NilaiPeserta::updateOrCreate(
                [
                    'id_peserta'         => $request->peserta_id,
                    'id_indikator_nilai' => $request->indikator_nilai_id,
                ],
                ['nilai' => $nilaiInput]
            );

            $konversi = round(($nilaiInput / 100) * $indikator->bobot, 2);

            return response()->json([
                'success'        => true,
                'message'        => 'Nilai berhasil disimpan.',
                'nilai_input'    => $nilaiInput,
                'nilai_konversi' => $konversi,
                'deleted'        => false,
            ]);

        } catch (\Throwable $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 500);
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

            if (in_array($roleName, ['coach','penguji'])) {
                $jenisNilai = JenisNilai::findOrFail($request->jenis_nilai_id);
                $ctx = $this->getUserContext($jenisNilai->id_jenis_pelatihan);
                if (!$ctx['pesertaKelompokIds']->contains((int) $request->peserta_id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat memberi catatan untuk peserta dari kelompok Anda.',
                    ], 403);
                }
            }

            CatatanNilai::updateOrCreate(
                ['id_peserta'=>$request->peserta_id,'id_jenis_nilai'=>$request->jenis_nilai_id],
                ['id_user'=>Auth::id(),'catatan'=>$request->catatan]
            );

            return response()->json(['success'=>true,'message'=>'Catatan berhasil disimpan.']);

        } catch (\Throwable $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 500);
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
    $roleId   = $ctx['roleId'];

    $angkatanRomawi = $this->getRomawList();
    $tahunList      = $this->getTahunList();
    $kelompokList   = range(1, 10);
    $wilayahList    = $this->getWilayahList();

    $pengujiList = Penguji::whereHas('kelompok', fn($q) =>
        $q->where('id_jenis_pelatihan', $jenisPelatihanId)
    )->orderBy('nama')->get(['id','nama','nip']);

    // Ambil semua jenis nilai beserta indikator
    $jenisNilaiAll = JenisNilai::where('id_jenis_pelatihan', $jenisPelatihanId)
        ->withCount('indikatorNilai')
        ->with([
            'indikatorNilai'       => fn($q) => $q->orderBy('id'),
            'indikatorNilai.roles' => fn($q) => $q->select('roles.id', 'roles.name'),
        ])
        ->orderBy('id')->get();

    // Filter berdasarkan role
    $rekapIzin             = $this->getRekapIzin($jenisNilaiAll, $roleName, $roleId);
    $jenisNilaiList        = $rekapIzin['jenisNilaiTerfilter'];
    $izinIndikatorPerJenis = $rekapIzin['izinIndikatorPerJenis'];
    $showTotal             = $rekapIzin['showTotal'];

    // ── KRUSIAL: Kumpulkan ID indikator yang diizinkan ─────────────
    $allowedIndikatorIds = collect();
    foreach ($izinIndikatorPerJenis as $jnId => $indIds) {
        foreach ($indIds as $indId) {
            $allowedIndikatorIds->push($indId);
        }
    }
    $allowedIndikatorIds = $allowedIndikatorIds->unique()->values();

    $indikatorPerJenis = $jenisNilaiList->mapWithKeys(fn($jn) => [
        $jn->id => $jn->indikator_nilai_count
    ]);

    $query = Peserta::query()
        ->whereHas('pendaftaran', fn($q) =>
            $q->where('id_jenis_pelatihan', $jenisPelatihanId)->whereNotNull('id_angkatan')
        )
        ->whereHas('kelompok', fn($q) =>
            $q->where('id_jenis_pelatihan', $jenisPelatihanId)
        );

    if ($request->filled('angkatan')) {
        $query->whereHas('pendaftaran.angkatan', fn($q) =>
            $q->where('nama_angkatan', 'Angkatan ' . $request->angkatan)
        );
    }
    if ($request->filled('tahun')) {
        $query->whereHas('pendaftaran.angkatan', fn($q) =>
            $q->where('tahun', 'LIKE', "%{$request->tahun}%")
        );
    }
    if ($request->filled('kelompok')) {
        $query->whereHas('kelompok', fn($q) =>
            $q->where('nama_kelompok', 'LIKE', "%Kelompok {$request->kelompok}%")
              ->where('id_jenis_pelatihan', $jenisPelatihanId)
        );
    }
    if ($request->filled('penguji')) {
        $query->whereHas('kelompok', fn($q) =>
            $q->where('id_penguji', $request->penguji)
              ->where('id_jenis_pelatihan', $jenisPelatihanId)
        );
    }

    $this->applyKategoriWilayahFilter($query, $request);

    if ($request->filled('search')) {
        $term = $request->search;
        $query->where(fn($q) =>
            $q->where('nama_lengkap', 'LIKE', "%{$term}%")
              ->orWhere('nip_nrp', 'LIKE', "%{$term}%")
        );
    }

    if ($roleName === 'penguji' && $ctx['pesertaKelompokIds']->isNotEmpty()) {
        $ids = $ctx['pesertaKelompokIds']->toArray();
        $query->selectRaw('peserta.*, CASE WHEN peserta.id IN ('.implode(',',array_map('intval',$ids)).') THEN 0 ELSE 1 END AS prioritas_urut')
            ->orderBy('prioritas_urut')->orderBy('ndh');
    } elseif ($roleName === 'pic' && $ctx['pesertaPicIds']->isNotEmpty()) {
        $ids = $ctx['pesertaPicIds']->toArray();
        $query->selectRaw('peserta.*, CASE WHEN peserta.id IN ('.implode(',',array_map('intval',$ids)).') THEN 0 ELSE 1 END AS prioritas_urut')
            ->orderBy('prioritas_urut')->orderBy('ndh');
    } else {
        $query->orderBy('ndh');
    }

    $pesertaPaginated = $query->paginate(20)->withQueryString();
    $pesertaIds       = $pesertaPaginated->pluck('id');

    // ── FIX: Ambil nilai HANYA untuk indikator yang diizinkan ─────────
    $semuaNilai = NilaiPeserta::with([
            'indikatorNilai',
            'indikatorNilai.jenisNilai',
        ])
        ->whereIn('id_peserta', $pesertaIds)
        ->whereIn('id_indikator_nilai', $allowedIndikatorIds)  // ← KRUSIAL: hanya indikator yang diizinkan
        ->whereHas('indikatorNilai.jenisNilai', fn($q) =>
            $q->where('id_jenis_pelatihan', $jenisPelatihanId)
        )
        ->get()
        ->groupBy('id_peserta');

    $semuaCatatan = CatatanNilai::whereIn('id_peserta', $pesertaIds)
        ->whereHas('jenisNilai', fn($q) => $q->where('id_jenis_pelatihan', $jenisPelatihanId))
        ->get()->groupBy('id_peserta');

    $semuaKelompok = DB::table('kelompok_pesertas')
        ->join('kelompoks','kelompoks.id','=','kelompok_pesertas.id_kelompok')
        ->whereIn('kelompok_pesertas.id_peserta', $pesertaIds)
        ->where('kelompoks.id_jenis_pelatihan', $jenisPelatihanId)
        ->select('kelompok_pesertas.id_peserta','kelompoks.id','kelompoks.nama_kelompok','kelompoks.id_penguji')
        ->get()->keyBy('id_peserta');

    $rekapData = $pesertaPaginated->map(function ($p) use (
        $jenisPelatihanId, $jenisNilaiList, $indikatorPerJenis,
        $semuaNilai, $semuaCatatan, $semuaKelompok,
        $ctx, $roleName, $izinIndikatorPerJenis, $showTotal
    ) {
        $kelompokRow = $semuaKelompok->get($p->id);
        $nilaiList   = $semuaNilai->get($p->id, collect());
        $catatanList = $semuaCatatan->get($p->id, collect())
            ->keyBy('id_jenis_nilai')->map(fn($c) => $c->catatan);

        $nilaiPerJenis  = [];
        $totalNilai     = 0;
        $totalTerisi    = 0;
        $totalIndikator = 0;

        foreach ($jenisNilaiList as $jn) {
            $indikatorDiizinkan = $izinIndikatorPerJenis[$jn->id] ?? [];

            // ── Filter nilai hanya untuk indikator yang diizinkan ──
            $nilaiJn = $nilaiList->filter(function ($n) use ($jn, $indikatorDiizinkan) {
                // Cek apakah indikator ini termasuk dalam jenis nilai yang sesuai
                if ((int)($n->indikatorNilai?->jenisNilai?->id) !== (int)$jn->id) {
                    return false;
                }
                // Cek apakah indikator ini diizinkan untuk role ini
                return in_array($n->id_indikator_nilai, $indikatorDiizinkan);
            });

            $sumKonversi = round($nilaiJn->sum(fn($n) => ($n->nilai / 100) * ($n->indikatorNilai->bobot ?? 0)), 2);
            $avgInput    = $nilaiJn->count() > 0 ? round($nilaiJn->avg('nilai'), 2) : null;
            $terisi      = $nilaiJn->whereNotNull('nilai')->count();

            // Detail indikator hanya untuk yang diizinkan
            $detailIndikator = $jn->indikatorNilai
                ->filter(fn($ind) => in_array($ind->id, $indikatorDiizinkan))
                ->map(function ($ind) use ($nilaiJn) {
                    $nilaiRecord = $nilaiJn->first(fn($n) => (int)$n->id_indikator_nilai === (int)$ind->id);
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
            $totalIndikator += count($indikatorDiizinkan);
        }

        $isPrioritasUser = match($roleName) {
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
            'kelengkapan'       => $totalIndikator > 0 ? round(($totalTerisi / $totalIndikator) * 100) : 0,
            'is_prioritas_user' => $isPrioritasUser,
        ];
    });

    return view('admin.nilai.rekap', compact(
        'jenis','jenisPelatihan','rekapData',
        'jenisNilaiList','angkatanRomawi','tahunList','kelompokList',
        'wilayahList','pengujiList','pesertaPaginated','showTotal'
    ));
}
}