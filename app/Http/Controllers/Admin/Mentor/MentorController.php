<?php

namespace App\Http\Controllers\Admin\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mentor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MentorController extends Controller
{

    /**
 * Preview duplicate mentors before cleanup
 */
public function previewDuplicates()
{
    try {
        $duplicates = [];

        // ─── 1. Duplikat NIP ───
        $allMentors = DB::table('mentor')
            ->whereNotNull('nip_mentor')
            ->where('nip_mentor', '!=', '')
            ->get();

        $nipGroups = [];
        foreach ($allMentors as $mentor) {
            $cleanNip = preg_replace('/[^0-9]/', '', $mentor->nip_mentor);
            if ($cleanNip) {
                $nipGroups[$cleanNip][] = $mentor;
            }
        }

        foreach ($nipGroups as $nip => $mentors) {
            if (count($mentors) > 1) {

                // Ambil ID dari grup, query by ID agar tidak terpengaruh format NIP di DB
                $mentorIds = collect($mentors)->pluck('id')->toArray();

                $mentorsWithCount = DB::table('mentor')
                    ->select('mentor.*', DB::raw('(SELECT COUNT(*) FROM peserta_mentor WHERE peserta_mentor.id_mentor = mentor.id) as total_peserta'))
                    ->whereIn('mentor.id', $mentorIds)
                    ->orderByDesc('total_peserta')
                    ->orderBy('mentor.id')
                    ->get();

                if ($mentorsWithCount->isEmpty()) continue;

                $keepMentor    = $mentorsWithCount->first();
                $removeMentors = $mentorsWithCount->slice(1);

                $pesertaToMove = [];
                foreach ($removeMentors as $rm) {
                    $peserta = DB::table('peserta_mentor')
                        ->join('pendaftaran', 'peserta_mentor.id_pendaftaran', '=', 'pendaftaran.id')
                        ->join('peserta', 'pendaftaran.id_peserta', '=', 'peserta.id')
                        ->join('angkatan', 'pendaftaran.id_angkatan', '=', 'angkatan.id')
                        ->where('peserta_mentor.id_mentor', $rm->id)
                        ->select('peserta.nama_lengkap', 'peserta.nip_nrp', 'angkatan.nama_angkatan')
                        ->get();
                    foreach ($peserta as $p) $pesertaToMove[] = $p;
                }

                $duplicates[] = [
                    'type'       => 'nip',
                    'identifier' => $nip,
                    'label'      => 'NIP: ' . $nip,
                    'keep'       => [
                        'id'            => $keepMentor->id,
                        'nama'          => $keepMentor->nama_mentor,
                        'nip'           => $keepMentor->nip_mentor,
                        'jabatan'       => $keepMentor->jabatan_mentor,
                        'total_peserta' => $keepMentor->total_peserta,
                    ],
                    'remove' => $removeMentors->map(fn($m) => [
                        'id'            => $m->id,
                        'nama'          => $m->nama_mentor,
                        'nip'           => $m->nip_mentor,
                        'jabatan'       => $m->jabatan_mentor,
                        'total_peserta' => $m->total_peserta,
                    ])->values()->toArray(),
                    'peserta_dipindah' => $pesertaToMove,
                ];
            }
        }

        // ─── 2. Duplikat Nama (NIP kosong) ───
        $namaGroups = DB::table('mentor')
            ->select('nama_mentor', DB::raw('COUNT(*) as count'))
            ->where(function ($q) {
                $q->whereNull('nip_mentor')->orWhere('nip_mentor', '=', '');
            })
            ->groupBy('nama_mentor')
            ->having('count', '>', 1)
            ->get();

        foreach ($namaGroups as $group) {

            // Ambil semua ID mentor dengan nama ini
            $mentorIds = DB::table('mentor')
                ->where('nama_mentor', $group->nama_mentor)
                ->where(function ($q) {
                    $q->whereNull('nip_mentor')->orWhere('nip_mentor', '=', '');
                })
                ->pluck('id')
                ->toArray();

            $mentors = DB::table('mentor')
                ->select('mentor.*', DB::raw('(SELECT COUNT(*) FROM peserta_mentor WHERE peserta_mentor.id_mentor = mentor.id) as total_peserta'))
                ->whereIn('mentor.id', $mentorIds)
                ->orderByDesc('total_peserta')
                ->orderBy('mentor.id')
                ->get();

            if ($mentors->isEmpty()) continue;

            $keepMentor    = $mentors->first();
            $removeMentors = $mentors->slice(1);

            $pesertaToMove = [];
            foreach ($removeMentors as $rm) {
                $peserta = DB::table('peserta_mentor')
                    ->join('pendaftaran', 'peserta_mentor.id_pendaftaran', '=', 'pendaftaran.id')
                    ->join('peserta', 'pendaftaran.id_peserta', '=', 'peserta.id')
                    ->join('angkatan', 'pendaftaran.id_angkatan', '=', 'angkatan.id')
                    ->where('peserta_mentor.id_mentor', $rm->id)
                    ->select('peserta.nama_lengkap', 'peserta.nip_nrp', 'angkatan.nama_angkatan')
                    ->get();
                foreach ($peserta as $p) $pesertaToMove[] = $p;
            }

            $duplicates[] = [
                'type'       => 'nama',
                'identifier' => $group->nama_mentor,
                'label'      => 'Nama: ' . $group->nama_mentor,
                'keep'       => [
                    'id'            => $keepMentor->id,
                    'nama'          => $keepMentor->nama_mentor,
                    'nip'           => $keepMentor->nip_mentor ?? '-',
                    'jabatan'       => $keepMentor->jabatan_mentor,
                    'total_peserta' => $keepMentor->total_peserta,
                ],
                'remove' => $removeMentors->map(fn($m) => [
                    'id'            => $m->id,
                    'nama'          => $m->nama_mentor,
                    'nip'           => $m->nip_mentor ?? '-',
                    'jabatan'       => $m->jabatan_mentor,
                    'total_peserta' => $m->total_peserta,
                ])->values()->toArray(),
                'peserta_dipindah' => $pesertaToMove,
            ];
        }

        return response()->json([
            'success'        => true,
            'total_duplikat' => count($duplicates),
            'duplicates'     => $duplicates,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat preview duplikat: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Execute cleanup of duplicate mentors
 */
public function cleanupDuplicates(Request $request)
{
    try {
        DB::beginTransaction();

        $log         = [];
        $totalHapus  = 0;
        $totalPindah = 0;

        // ─── Step 1: Rapikan format NIP ───
        $allMentors = DB::table('mentor')
            ->whereNotNull('nip_mentor')
            ->where('nip_mentor', '!=', '')
            ->get();

        foreach ($allMentors as $mentor) {
            $cleanNip = preg_replace('/[^0-9]/', '', $mentor->nip_mentor);
            if ($cleanNip !== $mentor->nip_mentor) {
                DB::table('mentor')->where('id', $mentor->id)->update(['nip_mentor' => $cleanNip]);
                $log[] = "Format NIP dirapikan: ID {$mentor->id} '{$mentor->nip_mentor}' → '{$cleanNip}'";
            }
        }

        // ─── Step 2: Duplikat berdasarkan NIP ───
        // Ambil ulang setelah NIP dibersihkan
        $allMentorsClean = DB::table('mentor')
            ->whereNotNull('nip_mentor')
            ->where('nip_mentor', '!=', '')
            ->get();

        $nipGroups = [];
        foreach ($allMentorsClean as $mentor) {
            $nipGroups[$mentor->nip_mentor][] = $mentor->id;
        }

        foreach ($nipGroups as $nip => $ids) {
            if (count($ids) <= 1) continue;

            $mentors = DB::table('mentor')
                ->select('mentor.*', DB::raw('(SELECT COUNT(*) FROM peserta_mentor WHERE peserta_mentor.id_mentor = mentor.id) as total_peserta'))
                ->whereIn('mentor.id', $ids)
                ->orderByDesc('total_peserta')
                ->orderBy('mentor.id')
                ->get();

            if ($mentors->isEmpty()) continue;

            $keep    = $mentors->first();
            $removes = $mentors->slice(1);

            foreach ($removes as $remove) {
                $moved = DB::table('peserta_mentor')
                    ->where('id_mentor', $remove->id)
                    ->update(['id_mentor' => $keep->id]);

                $totalPindah += $moved;

                // Merge field kosong dari duplikat ke yang dipertahankan
                $keepFresh = DB::table('mentor')->where('id', $keep->id)->first();
                $updates   = [];
                foreach (['jabatan_mentor', 'nomor_rekening', 'npwp_mentor', 'email_mentor', 'nomor_hp_mentor', 'golongan', 'pangkat'] as $field) {
                    if (empty($keepFresh->$field) && !empty($remove->$field)) {
                        $updates[$field] = $remove->$field;
                    }
                }
                if ($updates) {
                    DB::table('mentor')->where('id', $keep->id)->update($updates);
                }

                DB::table('mentor')->where('id', $remove->id)->delete();
                $totalHapus++;
                $log[] = "Hapus duplikat NIP '{$nip}': ID {$remove->id} ({$remove->nama_mentor}), pertahankan ID {$keep->id} ({$keep->nama_mentor}, {$keep->total_peserta} peserta), pindah {$moved} peserta";
            }
        }

        // ─── Step 3: Duplikat berdasarkan Nama (NIP kosong) ───
        $namaGroups = DB::table('mentor')
            ->select('nama_mentor', DB::raw('COUNT(*) as count'))
            ->where(function ($q) {
                $q->whereNull('nip_mentor')->orWhere('nip_mentor', '=', '');
            })
            ->groupBy('nama_mentor')
            ->having('count', '>', 1)
            ->get();

        foreach ($namaGroups as $group) {

            $mentorIds = DB::table('mentor')
                ->where('nama_mentor', $group->nama_mentor)
                ->where(function ($q) {
                    $q->whereNull('nip_mentor')->orWhere('nip_mentor', '=', '');
                })
                ->pluck('id')
                ->toArray();

            $mentors = DB::table('mentor')
                ->select('mentor.*', DB::raw('(SELECT COUNT(*) FROM peserta_mentor WHERE peserta_mentor.id_mentor = mentor.id) as total_peserta'))
                ->whereIn('mentor.id', $mentorIds)
                ->orderByDesc('total_peserta')
                ->orderBy('mentor.id')
                ->get();

            if ($mentors->isEmpty()) continue;

            $keep    = $mentors->first();
            $removes = $mentors->slice(1);

            foreach ($removes as $remove) {
                $moved = DB::table('peserta_mentor')
                    ->where('id_mentor', $remove->id)
                    ->update(['id_mentor' => $keep->id]);

                $totalPindah += $moved;

                $keepFresh = DB::table('mentor')->where('id', $keep->id)->first();
                $updates   = [];
                foreach (['jabatan_mentor', 'nomor_rekening', 'npwp_mentor', 'email_mentor', 'nomor_hp_mentor', 'golongan', 'pangkat'] as $field) {
                    if (empty($keepFresh->$field) && !empty($remove->$field)) {
                        $updates[$field] = $remove->$field;
                    }
                }
                if ($updates) {
                    DB::table('mentor')->where('id', $keep->id)->update($updates);
                }

                DB::table('mentor')->where('id', $remove->id)->delete();
                $totalHapus++;
                $log[] = "Hapus duplikat Nama '{$group->nama_mentor}': ID {$remove->id}, pertahankan ID {$keep->id} ({$keep->total_peserta} peserta), pindah {$moved} peserta";
            }
        }

        DB::commit();
        aktifitas("Cleanup Mentor Duplikat", null);

        return response()->json([
            'success'      => true,
            'message'      => "Berhasil! {$totalHapus} mentor duplikat dihapus, {$totalPindah} peserta dipindahkan.",
            'total_hapus'  => $totalHapus,
            'total_pindah' => $totalPindah,
            'log'          => $log,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Gagal melakukan cleanup: ' . $e->getMessage()
        ], 500);
    }
}


    /**
 * Get peserta details for a specific mentor
 */
public function getPeserta($id)
{
    try {
        $mentor = Mentor::with([
            'pesertaMentor.pendaftaran.peserta.kepegawaianPeserta',
            'pesertaMentor.pendaftaran.angkatan'
        ])->findOrFail($id);
        
        $pesertaList = $mentor->pesertaMentor->map(function ($pesertaMentor) {
            // Ambil data peserta melalui pendaftaran
            $peserta = $pesertaMentor->pendaftaran->peserta ?? null;
            $angkatan = $pesertaMentor->pendaftaran->angkatan ?? null;
            $kepegawaian = $peserta ? $peserta->kepegawaianPeserta : null;
            
            if (!$peserta) {
                return null; // Skip jika peserta tidak ditemukan
            }
            
            return [
                'nama' => $peserta->nama_lengkap ?? '-',
                'nip' => $peserta->nip_nrp ?? '-',
                'ndh' => $peserta->ndh ?? '-',
                'email' => $peserta->email_pribadi ?? '-',
                'nomor_hp' => $peserta->nomor_hp ?? '-',
                'instansi' => $kepegawaian->asal_instansi ?? '-',
                'angkatan' => $angkatan->nama_angkatan ?? '-',
                'tahun' => $angkatan->tahun ?? '-',
                'status_mentoring' => $pesertaMentor->status_mentoring ?? '-',
                'tanggal_penunjukan' => $pesertaMentor->tanggal_penunjukan 
                    ? \Carbon\Carbon::parse($pesertaMentor->tanggal_penunjukan)->format('d/m/Y') 
                    : '-',
            ];
        })->filter()->values(); // Filter null values dan reset keys
        
        return response()->json([
            'success' => true,
            'mentor' => [
                'nama' => $mentor->nama_mentor,
                'nip' => $mentor->nip_mentor,
                'jabatan' => $mentor->jabatan_mentor,
            ],
            'peserta' => $pesertaList,
            'total' => $pesertaList->count()
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data peserta: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Display a listing of mentor.
     */
    public function index(Request $request)
    {
        $query = Mentor::withCount('pesertaMentor');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_aktif', $request->status === 'Aktif');
        }

        // Search functionality - mencakup semua data
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_mentor', 'like', "%{$search}%")
                    ->orWhere('nip_mentor', 'like', "%{$search}%")
                    ->orWhere('email_mentor', 'like', "%{$search}%")
                    ->orWhere('nomor_hp_mentor', 'like', "%{$search}%")
                    ->orWhere('jabatan_mentor', 'like', "%{$search}%");
            });
        }

        // Sort by jumlah peserta atau nama
        if ($request->filled('sort')) {
            if ($request->sort === 'peserta') {
                $query->orderBy('peserta_mentor_count', 'desc');
            } else if ($request->sort === 'nama') {
                $query->orderBy('nama_mentor', 'asc');
            }
        } else {
            $query->orderBy('nama_mentor', 'asc');
        }

        // Pagination - ambil parameter per_page dari request
        $perPage = $request->get('per_page', 10);
        if ($perPage == '-1') {
            $mentor = $query->get();
            $mentor = new \Illuminate\Pagination\LengthAwarePaginator(
                $mentor,
                $mentor->count(),
                $mentor->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $mentor = $query->paginate($perPage)->appends($request->except('page'));
        }

        // Stats for cards - hitung dari semua data (tanpa pagination)
        $allMentor = Mentor::all();
        $totalMentor = $allMentor->count();
        $aktifMentor = $allMentor->where('status_aktif', true)->count();
        $nonaktifMentor = $totalMentor - $aktifMentor;

        return view('admin.mentor.index', compact('mentor', 'totalMentor', 'aktifMentor', 'nonaktifMentor'));
    }

    /**
     * Show the form for creating a new mentor.
     */
    public function create()
    {
        return view('admin.mentor.create', [
            'isEdit' => false
        ]);
    }

    /**
     * Store a newly created mentor in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mentor' => 'required|string|max:200',
            'nip_mentor' => 'nullable|string|max:200',
            'jabatan_mentor' => 'nullable|string|max:200',
            'nomor_rekening' => 'nullable|string|max:200',
            'npwp_mentor' => 'nullable|string|max:50',
            'email_mentor' => 'nullable|email|max:100|unique:mentor,email_mentor',
            'nomor_hp_mentor' => 'nullable|string|max:20',
            'status_aktif' => 'required|boolean',
            'golongan' => 'nullable|string|max:50',
            'pangkat' => 'nullable|string|max:100',
        ], [
            'nama_mentor.required' => 'Nama mentor wajib diisi.',
            'nama_mentor.max' => 'Nama mentor maksimal 200 karakter.',
            'nip_mentor.max' => 'NIP mentor maksimal 200 karakter.',
            'jabatan_mentor.max' => 'Jabatan mentor maksimal 200 karakter.',
            'nomor_rekening.max' => 'Nomor rekening maksimal 200 karakter.',
            'npwp_mentor.max' => 'NPWP mentor maksimal 50 karakter.',
            'email_mentor.email' => 'Format email tidak valid.',
            'email_mentor.max' => 'Email mentor maksimal 100 karakter.',
            'email_mentor.unique' => 'Email mentor sudah terdaftar.',
            'nomor_hp_mentor.max' => 'Nomor HP mentor maksimal 20 karakter.',
            'status_aktif.required' => 'Status mentor wajib dipilih.',
            'status_aktif.boolean' => 'Status mentor tidak valid.',
            'golongan.max' => 'Golongan maksimal 50 karakter.',
            'pangkat.max' => 'Pangkat maksimal 100 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $mentor=Mentor::create([
                        'nama_mentor' => $request->nama_mentor,
                        'nip_mentor' => $request->nip_mentor,
                        'jabatan_mentor' => $request->jabatan_mentor,
                        'golongan' => $request->golongan,
                        'pangkat' => $request->pangkat,
                        'nomor_rekening' => $request->nomor_rekening,
                        'npwp_mentor' => $request->npwp_mentor,
                        'email_mentor' => $request->email_mentor,
                        'nomor_hp_mentor' => $request->nomor_hp_mentor,
                        'status_aktif' => $request->status_aktif,
                        'dibuat_pada' => now()
                    ]);

            DB::commit();

            aktifitas("Membuat Mentor Baru",$mentor);
            return redirect()->route('mentor.index')
                ->with('success', 'Mentor berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan mentor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified mentor.
     */
    public function edit($id)
    {
        $mentor = Mentor::findOrFail($id);

        return view('admin.mentor.create', [
            'mentor' => $mentor,
            'isEdit' => true
        ]);
    }

    /**
     * Update the specified mentor in storage.
     */
    public function update(Request $request, $id)
    {
        $mentor = Mentor::findOrFail($id);

        $request->validate([
            'nama_mentor' => 'required|string|max:200',
            'nip_mentor' => 'nullable|string|max:200',
            'jabatan_mentor' => 'nullable|string|max:200',
            'nomor_rekening' => 'nullable|string|max:200',
            'npwp_mentor' => 'nullable|string|max:50',
            'email_mentor' => 'nullable|email|max:100|unique:mentor,email_mentor,' . $id,
            'nomor_hp_mentor' => 'nullable|string|max:20',
            'status_aktif' => 'required|boolean',
            'golongan' => 'nullable|string|max:50',
            'pangkat' => 'nullable|string|max:100',
        ], [
            'nama_mentor.required' => 'Nama mentor wajib diisi.',
            'nama_mentor.max' => 'Nama mentor maksimal 200 karakter.',
            'nip_mentor.max' => 'NIP mentor maksimal 200 karakter.',
            'jabatan_mentor.max' => 'Jabatan mentor maksimal 200 karakter.',
            'nomor_rekening.max' => 'Nomor rekening maksimal 200 karakter.',
            'npwp_mentor.max' => 'NPWP mentor maksimal 50 karakter.',
            'email_mentor.email' => 'Format email tidak valid.',
            'email_mentor.max' => 'Email mentor maksimal 100 karakter.',
            'email_mentor.unique' => 'Email mentor sudah terdaftar.',
            'nomor_hp_mentor.max' => 'Nomor HP mentor maksimal 20 karakter.',
            'status_aktif.required' => 'Status mentor wajib dipilih.',
            'status_aktif.boolean' => 'Status mentor tidak valid.',
            'golongan.max' => 'Golongan maksimal 50 karakter.',
            'pangkat.max' => 'Pangkat maksimal 100 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $mentor->update([
                'nama_mentor' => $request->nama_mentor,
                'nip_mentor' => $request->nip_mentor,
                'jabatan_mentor' => $request->jabatan_mentor,
                'golongan' => $request->golongan,
                'pangkat' => $request->pangkat,
                'nomor_rekening' => $request->nomor_rekening,
                'npwp_mentor' => $request->npwp_mentor,
                'email_mentor' => $request->email_mentor,
                'nomor_hp_mentor' => $request->nomor_hp_mentor,
                'status_aktif' => $request->status_aktif
            ]);

            DB::commit();
            aktifitas("Memperbaharui Mentor Baru", $mentor);
            return redirect()->route('mentor.index')
                ->with('success', 'Mentor berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui mentor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified mentor from storage.
     */
    public function destroy($id)
    {
        $mentor = Mentor::findOrFail($id);

        // Check if mentor has related data in peserta_mentor
        if ($mentor->pesertaMentor()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus mentor yang sudah memiliki peserta. Hapus relasi peserta terlebih dahulu.'
                ], 400);
            }

            return redirect()->route('mentor.index')
                ->with('error', 'Tidak dapat menghapus mentor yang sudah memiliki peserta. Hapus relasi peserta terlebih dahulu.');
        }

        try {
            $mentor->delete();

            aktifitas("Menghapus Mentor Baru", $mentor);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mentor berhasil dihapus'
                ]);
            }

            return redirect()->route('mentor.index')
                ->with('success', 'Mentor berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus mentor: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('mentor.index')
                ->with('error', 'Gagal menghapus mentor: ' . $e->getMessage());
        }
    }
}
