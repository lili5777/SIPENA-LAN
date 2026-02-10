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
            'status_aktif' => 'required|boolean'
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
            'status_aktif.boolean' => 'Status mentor tidak valid.'
        ]);

        try {
            DB::beginTransaction();

            $mentor=Mentor::create([
                        'nama_mentor' => $request->nama_mentor,
                        'nip_mentor' => $request->nip_mentor,
                        'jabatan_mentor' => $request->jabatan_mentor,
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
            'status_aktif' => 'required|boolean'
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
            'status_aktif.boolean' => 'Status mentor tidak valid.'
        ]);

        try {
            DB::beginTransaction();

            $mentor->update([
                'nama_mentor' => $request->nama_mentor,
                'nip_mentor' => $request->nip_mentor,
                'jabatan_mentor' => $request->jabatan_mentor,
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
