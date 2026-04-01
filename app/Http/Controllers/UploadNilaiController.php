<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UploadNilai;
use App\Models\IndikatorNilai;
use App\Models\NilaiPeserta;
use App\Models\Pendaftaran;
use App\Models\JenisPelatihan;
use App\Models\Kelompok;
use App\Models\Angkatan;
use App\Models\PicPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Peserta;

class UploadNilaiController extends Controller
{
    // =========================================================
    // HELPER: ambil jenis pelatihan peserta yang login
    // =========================================================
    private function getJenisPelatihanPeserta(): ?int
    {
        $user = Auth::user();
        if (!$user->peserta_id) return null;

        $pendaftaran = Pendaftaran::where('id_peserta', $user->peserta_id)
            ->whereNotNull('id_angkatan')
            ->latest('id')
            ->first();

        return $pendaftaran?->id_jenis_pelatihan;
    }

    // =========================================================
    // PESERTA — Halaman penilaian mandiri
    // =========================================================
    public function index()
    {
        $user     = Auth::user();
        $pesertaId = $user->peserta_id;

        if (!$pesertaId) {
            abort(403, 'Akun ini tidak terhubung ke data peserta.');
        }

        $jenisPelatihanId = $this->getJenisPelatihanPeserta();

        if (!$jenisPelatihanId) {
            abort(403, 'Anda belum terdaftar di angkatan manapun.');
        }

        $jenisPelatihan = JenisPelatihan::findOrFail($jenisPelatihanId);

        // Ambil indikator yang bisa diisi oleh peserta (role user)
        // Cek tabel akses_penilaian: role_id = role user
        $roleId = $user->role_id;

        $indikatorList = IndikatorNilai::with([
            'jenisNilai',
            'roles' => fn($q) => $q->select('roles.id', 'roles.name'),
        ])
        ->whereHas('jenisNilai', fn($q) =>
            $q->where('id_jenis_pelatihan', $jenisPelatihanId)
        )
        ->whereHas('roles', fn($q) =>
            $q->where('roles.id', $roleId)
        )
        ->orderBy('id')
        ->get();

        // Ambil upload yang sudah ada per indikator
        $uploadList = UploadNilai::where('id_peserta', $pesertaId)
            ->whereIn('id_indikator_nilai', $indikatorList->pluck('id'))
            ->get()
            ->keyBy('id_indikator_nilai');

        // Ambil nilai yang sudah disetujui (di nilai_peserta)
        $nilaiDisetujui = NilaiPeserta::where('id_peserta', $pesertaId)
            ->whereIn('id_indikator_nilai', $indikatorList->pluck('id'))
            ->get()
            ->keyBy('id_indikator_nilai');

        return view('admin.penilaian-mandiri.index', compact(
            'jenisPelatihan',
            'indikatorList',
            'uploadList',
            'nilaiDisetujui'
        ));
    }

    // =========================================================
    // PESERTA — Submit / Re-submit nilai
    // =========================================================
    public function store(Request $request)
    {
        $request->validate([
            'id_indikator_nilai' => 'required|exists:indikator_nilai,id',
            'nilai'              => 'required|numeric|min:0|max:100',
            'catatan_peserta'    => 'nullable|string|max:500',
            'file'               => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'file.required'  => 'Screenshot wajib dilampirkan.',
            'file.image'     => 'File harus berupa gambar (JPG, PNG, WebP).',
            'file.max'       => 'Ukuran file maksimal 5MB.',
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.max'      => 'Nilai maksimal 100.',
        ]);

        $user      = Auth::user();
        $pesertaId = $user->peserta_id;

        if (!$pesertaId) abort(403);

        // Cek akses: indikator ini memang boleh diisi peserta
        $roleId    = $user->role_id;
        $indikator = IndikatorNilai::with('jenisNilai')
            ->whereHas('roles', fn($q) => $q->where('roles.id', $roleId))
            ->findOrFail($request->id_indikator_nilai);

        // Cek apakah sudah ada upload sebelumnya
        $existing = UploadNilai::where('id_peserta', $pesertaId)
            ->where('id_indikator_nilai', $request->id_indikator_nilai)
            ->first();

        if ($existing && $existing->status === 'pending') {
            return back()->with('error', 'Submission Anda sedang dalam proses verifikasi.');
        }
        if ($existing && $existing->status === 'disetujui') {
            return back()->with('error', 'Nilai ini sudah disetujui, tidak bisa diubah.');
        }

        // ── Bangun path folder Google Drive ────────────────────────────
        // Sama persis dengan PendaftaranController
        $peserta     = Peserta::findOrFail($pesertaId);
        $pendaftaran = Pendaftaran::where('id_peserta', $pesertaId)
            ->whereNotNull('id_angkatan')
            ->latest('id')
            ->first();

        $tahun          = date('Y');
        $nip            = $peserta->nip_nrp;
        $angkatan       = $pendaftaran?->angkatan;
        $jenisPelatihan = $indikator->jenisNilai->jenisPelatihan
                          ?? \App\Models\JenisPelatihan::find(
                              $indikator->jenisNilai->id_jenis_pelatihan
                          );

        $kategori          = $angkatan?->kategori ?? 'PNBP';
        $wilayah           = $angkatan?->wilayah;
        $kodeJenis         = $jenisPelatihan
                             ? str_replace(' ', '_', $jenisPelatihan->kode_pelatihan)
                             : 'UMUM';
        $namaAngkatan      = $angkatan
                             ? str_replace(' ', '_', $angkatan->nama_angkatan)
                             : 'default';
        $kategoriFolder    = strtoupper($kategori);

        if ($kategoriFolder === 'FASILITASI') {
            $wilayahFolder = $wilayah ? str_replace(' ', '_', $wilayah) : 'Umum';
            $folderPath = "Berkas/{$kategoriFolder}/{$tahun}/{$kodeJenis}/{$namaAngkatan}/{$wilayahFolder}/{$nip}";
        } else {
            $folderPath = "Berkas/{$kategoriFolder}/{$tahun}/{$kodeJenis}/{$namaAngkatan}/{$nip}";
        }

        // ── Nama file: upload_nilai_{slug_indikator}_{timestamp}.ext ───
        $slugIndikator = Str::slug($indikator->name, '_');
        $extension     = $request->file('file')->getClientOriginalExtension();
        $fileName      = "upload_nilai_{$slugIndikator}_" . uniqid() . ".{$extension}";
        $drivePath     = "{$folderPath}/{$fileName}";

        // ── Hapus file lama di GDrive jika re-submit ───────────────────
        if ($existing && $existing->file) {
            try {
                if (Storage::disk('google')->exists($existing->file)) {
                    Storage::disk('google')->delete($existing->file);
                }
            } catch (\Exception $e) {
                // lanjutkan meski gagal hapus file lama
            }
        }

        // ── Upload ke Google Drive ─────────────────────────────────────
        try {
            Storage::disk('google')->put(
                $drivePath,
                file_get_contents($request->file('file'))
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload file ke server: ' . $e->getMessage());
        }

        UploadNilai::updateOrCreate(
            [
                'id_peserta'         => $pesertaId,
                'id_indikator_nilai' => $request->id_indikator_nilai,
            ],
            [
                'file'                => $drivePath,
                'nilai'               => $request->nilai,
                'catatan_peserta'     => $request->catatan_peserta,
                'catatan_verifikator' => null,
                'status'              => 'pending',
                'id_verifikator'      => null,
                'verified_at'         => null,
            ]
        );

        return back()->with('success', 'Nilai berhasil disubmit, menunggu verifikasi.');
    }

    // =========================================================
    // VERIFIKATOR — Daftar submission peserta
    // =========================================================
    public function indexVerifikasi(Request $request)
    {
        $user     = Auth::user();
        $roleName = $user->role->name ?? '';

        // Validasi: hanya pic, evaluator, admin
        if (!in_array($roleName, ['admin', 'evaluator', 'pic'])) {
            abort(403);
        }

        // Dropdown filter
        $angkatanList = Angkatan::all();
        $statusList   = ['pending', 'disetujui', 'ditolak'];

        $query = UploadNilai::with([
            'peserta',
            'indikatorNilai.jenisNilai',
            'verifikator',
        ]);

        // PIC: batasi ke angkatan yang ditugaskan
        if ($roleName === 'pic') {
            $angkatanIds = PicPeserta::where('user_id', $user->id)->pluck('angkatan_id');
            $query->whereHas('peserta.pendaftaran', fn($q) =>
                $q->whereIn('id_angkatan', $angkatanIds)
            );
        }

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan pending dulu
            $query->orderByRaw("FIELD(status, 'pending', 'ditolak', 'disetujui')");
        }

        if ($request->filled('angkatan')) {
            $query->whereHas('peserta.pendaftaran', fn($q) =>
                $q->where('id_angkatan', $request->angkatan)
            );
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->whereHas('peserta', fn($q) =>
                $q->where('nama_lengkap', 'LIKE', "%{$term}%")
                  ->orWhere('nip_nrp', 'LIKE', "%{$term}%")
            );
        }

        $submissions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.verifikasi-nilai.index', compact(
            'submissions', 'angkatanList', 'statusList'
        ));
    }

    // =========================================================
    // VERIFIKATOR — Setujui submission
    // =========================================================
    public function approve(Request $request, $id)
    {
        $user     = Auth::user();
        $roleName = $user->role->name ?? '';

        if (!in_array($roleName, ['admin', 'evaluator', 'pic'])) {
            abort(403);
        }

        $upload = UploadNilai::with('indikatorNilai')->findOrFail($id);

        if (!$upload->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Submission ini sudah diverifikasi sebelumnya.',
            ], 422);
        }

        DB::transaction(function () use ($upload, $user) {
            // Update status upload
            $upload->update([
                'status'              => 'disetujui',
                'id_verifikator'      => $user->id,
                'catatan_verifikator' => null,
                'verified_at'         => now(),
            ]);

            // Masukkan nilai ke tabel nilai_peserta
            NilaiPeserta::updateOrCreate(
                [
                    'id_peserta'         => $upload->id_peserta,
                    'id_indikator_nilai' => $upload->id_indikator_nilai,
                ],
                ['nilai' => $upload->nilai]
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil disetujui dan masuk ke rekap penilaian.',
        ]);
    }

    // =========================================================
    // VERIFIKATOR — Tolak submission
    // =========================================================
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_verifikator' => 'required|string|max:500',
        ], [
            'catatan_verifikator.required' => 'Catatan alasan penolakan wajib diisi.',
        ]);

        $user     = Auth::user();
        $roleName = $user->role->name ?? '';

        if (!in_array($roleName, ['admin', 'evaluator', 'pic'])) {
            abort(403);
        }

        $upload = UploadNilai::findOrFail($id);

        if (!$upload->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Submission ini sudah diverifikasi sebelumnya.',
            ], 422);
        }

        // Jika sebelumnya disetujui dan ada di nilai_peserta, hapus
        NilaiPeserta::where('id_peserta', $upload->id_peserta)
            ->where('id_indikator_nilai', $upload->id_indikator_nilai)
            ->delete();

        $upload->update([
            'status'              => 'ditolak',
            'id_verifikator'      => $user->id,
            'catatan_verifikator' => $request->catatan_verifikator,
            'verified_at'         => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Submission ditolak. Peserta dapat submit ulang.',
        ]);
    }

    // =========================================================
    // VERIFIKATOR — Preview file (AJAX)
    // =========================================================
    public function previewFile($id)
    {
        $user     = Auth::user();
        $roleName = $user->role->name ?? '';

        $upload = UploadNilai::findOrFail($id);

        // Peserta hanya boleh lihat miliknya sendiri
        if ($roleName === 'user') {
            if ($upload->id_peserta !== $user->peserta_id) abort(403);
        } elseif (!in_array($roleName, ['admin', 'evaluator', 'pic'])) {
            abort(403);
        }

        return response()->json([
            'success'  => true,
            'file_url' => asset('storage/' . $upload->file),
            'nilai'    => $upload->nilai,
            'peserta'  => $upload->peserta->nama_lengkap ?? '-',
            'indikator'=> $upload->indikatorNilai->name ?? '-',
            'status'   => $upload->status,
        ]);
    }

    public function getFile($id)
    {
        $upload = UploadNilai::with('peserta')->findOrFail($id);
 
        $user     = Auth::user();
        $roleName = $user->role->name ?? '';
 
        // Peserta hanya boleh lihat miliknya sendiri
        if ($roleName === 'user') {
            if ($upload->id_peserta !== $user->peserta_id) abort(403);
        } elseif (!in_array($roleName, ['admin', 'evaluator', 'pic'])) {
            abort(403);
        }
 
        if (!$upload->file) abort(404);
 
        try {
            // Ambil file dari Google Drive
            $stream   = Storage::disk('google')->readStream($upload->file);
            $mimeType = Storage::disk('google')->mimeType($upload->file);
            $filename = basename($upload->file);
 
            return response()->stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                'Content-Type'        => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                // ✅ No-cache: paksa browser load ulang file setiap kali
                // Penting agar gambar baru tidak di-cache saat re-submit
                'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'              => 'no-cache',
                'Expires'             => '0',
            ]);
 
        } catch (\Exception $e) {
            abort(404, 'File tidak ditemukan di server.');
        }
    }
}