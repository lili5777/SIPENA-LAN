<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JenisPelatihan;
use App\Models\JenisNilai;
use App\Models\IndikatorNilai;
use App\Models\AksesPenilaian;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AksesPenilaianController extends Controller
{
    // ✅ Di Laravel 12 tidak ada $this->middleware() di constructor
    // Pembatasan akses dilakukan di route (lihat routes-dan-sidebar.php)
    // atau via helper cek manual di setiap method

    private function cekAkses(): void
    {
        $role = Auth::user()->role->name ?? '';
        if (!in_array($role, ['admin', 'evaluator'])) {
            abort(403, 'Hanya admin dan evaluator yang dapat mengatur akses penilaian.');
        }
    }

    // =========================================================
    // INDEX — Pilih Jenis Pelatihan
    // =========================================================
    public function index()
    {
        $this->cekAkses();

        $jenisPelatihan = JenisPelatihan::where('aktif', true)
            ->withCount(['jenisNilai as total_indikator' => function ($q) {
                $q->join('indikator_nilai', 'jenis_nilai.id', '=', 'indikator_nilai.id_jenis_nilai');
            }])
            ->get();

        $jenisPelatihan->each(function ($jp) {
            $jp->sudah_ada_akses = AksesPenilaian::whereHas(
                'indikatorNilai.jenisNilai',
                fn($q) => $q->where('id_jenis_pelatihan', $jp->id)
            )->distinct('id_indikator_nilai')->count('id_indikator_nilai');
        });

        return view('admin.akses-penilaian.index', compact('jenisPelatihan'));
    }

    // =========================================================
    // KELOLA — Halaman atur akses per jenis pelatihan
    // =========================================================
    public function kelola(Request $request, $jenisPelatihanId)
    {
        $this->cekAkses();

        $jenisPelatihan = JenisPelatihan::findOrFail($jenisPelatihanId);

        $jenisNilaiList = JenisNilai::with([
            'indikatorNilai' => function ($q) {
                $q->orderBy('id')
                  ->with(['roles' => fn($q) => $q->select('roles.id', 'roles.name')]);
            },
        ])
        ->where('id_jenis_pelatihan', $jenisPelatihanId)
        ->orderBy('id')
        ->get();

        // Role yang bisa diassign (selain admin & user karena admin bypass otomatis)
        $roleList = Role::whereNotIn('name', ['admin'])
            ->orderBy('name')
            ->get();

        return view('admin.akses-penilaian.kelola', compact(
            'jenisPelatihan',
            'jenisNilaiList',
            'roleList'
        ));
    }

    // =========================================================
    // SIMPAN BULK — Form submit semua indikator sekaligus
    // =========================================================
    public function simpanBulk(Request $request, $jenisPelatihanId)
    {
        $this->cekAkses();

        $request->validate([
            'akses'   => 'nullable|array',
            'akses.*' => 'nullable|array',
        ]);

        try {
            DB::transaction(function () use ($request, $jenisPelatihanId) {
                $indikatorIds = IndikatorNilai::whereHas('jenisNilai', fn($q) =>
                    $q->where('id_jenis_pelatihan', $jenisPelatihanId)
                )->pluck('id');

                // Hapus semua akses lama
                AksesPenilaian::whereIn('id_indikator_nilai', $indikatorIds)->delete();

                // Insert akses baru
                $akses   = $request->akses ?? [];
                $inserts = [];

                foreach ($akses as $indikatorId => $roleIds) {
                    if (!is_array($roleIds)) continue;
                    foreach ($roleIds as $roleId) {
                        $inserts[] = [
                            'id_indikator_nilai' => (int) $indikatorId,
                            'role_id'            => (int) $roleId,
                            'created_at'         => now(),
                            'updated_at'         => now(),
                        ];
                    }
                }

                if (!empty($inserts)) {
                    AksesPenilaian::insert($inserts);
                }
            });

            return back()->with('success', 'Pengaturan akses penilaian berhasil disimpan.');

        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================================================
    // SIMPAN SATU INDIKATOR — AJAX
    // =========================================================
    public function simpan(Request $request)
    {
        $this->cekAkses();

        $request->validate([
            'indikator_nilai_id' => 'required|exists:indikator_nilai,id',
            'role_ids'           => 'nullable|array',
            'role_ids.*'         => 'exists:roles,id',
        ]);

        try {
            $indikator = IndikatorNilai::findOrFail($request->indikator_nilai_id);
            $roleIds   = $request->role_ids ?? [];

            $indikator->roles()->sync($roleIds);

            $roleAktif = Role::whereIn('id', $roleIds)->pluck('name', 'id');

            return response()->json([
                'success'    => true,
                'message'    => 'Akses berhasil disimpan.',
                'role_aktif' => $roleAktif,
                'jumlah'     => count($roleIds),
            ]);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =========================================================
    // RESET SATU INDIKATOR — AJAX
    // =========================================================
    public function reset(Request $request)
    {
        $this->cekAkses();

        $request->validate([
            'indikator_nilai_id' => 'required|exists:indikator_nilai,id',
        ]);

        try {
            AksesPenilaian::where('id_indikator_nilai', $request->indikator_nilai_id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Akses indikator berhasil direset.',
            ]);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}