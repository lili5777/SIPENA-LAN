<?php

namespace App\Http\Controllers\Admin\Angkatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Angkatan;
use App\Models\JenisPelatihan;
use Illuminate\Support\Facades\DB;

class AngkatanController extends Controller
{
    /**
     * Display a listing of angkatan.
     */
    public function index(Request $request)
    {
        $query = Angkatan::with(['jenisPelatihan', 'pendaftaran']);

        // Filter berdasarkan role PIC
        if (auth()->user()->isPic()) {
            // Ambil ID angkatan yang boleh diakses PIC
            $allowedAngkatanIds = auth()->user()->picPesertas()->pluck('angkatan_id');
            $query->whereIn('id', $allowedAngkatanIds);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_angkatan', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $angkatan = $query
        ->orderBy('id', 'desc')
        ->get();



        // Get unique years for filter dropdown
        $years = Angkatan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin.angkatan.index', compact('angkatan', 'years'));
    }

    /**
     * Show the form for creating a new angkatan.
     */
    public function create()
    {
        $jenisPelatihan = JenisPelatihan::where('aktif', true)
            ->orderBy('nama_pelatihan')
            ->get();


        return view('admin.angkatan.create', [
            'jenisPelatihan' => $jenisPelatihan,
            'isEdit' => false
        ]);
    }

    /**
     * Store a newly created angkatan in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
            'nama_angkatan' => 'required|string|max:50',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'kuota' => 'nullable|integer|min:1',
            'status_angkatan' => 'required|in:Dibuka,Diutup,Berlangsung,Selesai',
            'link_gb_wa' => 'nullable|string|max:100',
            'kategori' => 'required|in:PNBP,FASILITASI',
            'wilayah'  => 'nullable|string|max:255',
        ], [
            'id_jenis_pelatihan.required' => 'Jenis pelatihan wajib dipilih.',
            'id_jenis_pelatihan.exists' => 'Jenis pelatihan tidak valid.',
            'nama_angkatan.required' => 'Nama angkatan wajib diisi.',
            'nama_angkatan.max' => 'Nama angkatan maksimal 50 karakter.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2000.',
            'tahun.max' => 'Tahun maksimal ' . (date('Y') + 5) . '.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'kuota.integer' => 'Kuota harus berupa angka.',
            'kuota.min' => 'Kuota minimal 1.',
            'status_angkatan.required' => 'Status angkatan wajib dipilih.',
            'status_angkatan.in' => 'Status angkatan tidak valid.',
            'link_gb_wa.max' => 'link GB WA Maksimal 100 karakter'
        ]);

        try {

            $query = Angkatan::where('nama_angkatan', $request->nama_angkatan)
                ->where('tahun', $request->tahun)
                ->where('id_jenis_pelatihan', $request->id_jenis_pelatihan)
                ->where('kategori', $request->kategori);

            if ($request->kategori === 'PNBP') {
                // PNBP: tidak boleh duplikat sama sekali
                $exists = $query->exists();
            } else {
                // FASILITASI: boleh asal wilayah beda
                $exists = $query
                    ->where('wilayah', $request->wilayah)
                    ->exists();
            }

            if ($exists) {
                return back()
                    ->withErrors([
                        'nama_angkatan' => 'Angkatan dengan nama, jenis pelatihan, dan tahun ini sudah ada.'
                    ])
                    ->withInput();
            }


            DB::beginTransaction();

            $angkatan = Angkatan::create([
                'id_jenis_pelatihan' => $request->id_jenis_pelatihan,
                'nama_angkatan' => $request->nama_angkatan,
                'tahun' => $request->tahun,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'kuota' => $request->kuota,
                'status_angkatan' => $request->status_angkatan,
                'kunci_edit' => $request->kunci_edit,
                'kunci_judul' => $request->kunci_judul,
                'link_gb_wa' => $request->link_gb_wa,
                'kategori' => $request->kategori,
                'wilayah'  => $request->kategori === 'FASILITASI' ? $request->wilayah : null,
                'dibuat_pada' => now()
            ]);

            DB::commit();

            aktifitas("Membuat Angkatan Baru",$angkatan);
            return redirect()->route('angkatan.index')
                ->with('success', 'Angkatan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan angkatan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified angkatan.
     */
    public function edit($id)
    {
        $angkatan = Angkatan::findOrFail($id);
        $jenisPelatihan = JenisPelatihan::where('aktif', true)
            ->orderBy('nama_pelatihan')
            ->get();

        return view('admin.angkatan.create', [
            'angkatan' => $angkatan,
            'jenisPelatihan' => $jenisPelatihan,
            'isEdit' => true
        ]);
    }

    /**
     * Update the specified angkatan in storage.
     */
    public function update(Request $request, $id)
    {
        $angkatan = Angkatan::findOrFail($id);

        $request->validate([
            'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
            'nama_angkatan' => 'required|string|max:50',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'kuota' => 'nullable|integer|min:1',
            'status_angkatan' => 'required|in:Dibuka,Diutup,Berlangsung,Selesai',
            'link_gb_wa' => 'nullable|string|max:100',
            'kategori' => 'required|in:PNBP,FASILITASI',
            'wilayah'  => 'nullable|string|max:255',
        ], [
            'id_jenis_pelatihan.required' => 'Jenis pelatihan wajib dipilih.',
            'id_jenis_pelatihan.exists' => 'Jenis pelatihan tidak valid.',
            'nama_angkatan.required' => 'Nama angkatan wajib diisi.',
            'nama_angkatan.max' => 'Nama angkatan maksimal 50 karakter.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2000.',
            'tahun.max' => 'Tahun maksimal ' . (date('Y') + 5) . '.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'kuota.integer' => 'Kuota harus berupa angka.',
            'kuota.min' => 'Kuota minimal 1.',
            'status_angkatan.required' => 'Status angkatan wajib dipilih.',
            'status_angkatan.in' => 'Status angkatan tidak valid.',
            'link_gb_wa.max' => 'link GB WA Maksimal 100 karakter'
        ]);

        try {
            $query = Angkatan::where('nama_angkatan', $request->nama_angkatan)
                ->where('tahun', $request->tahun)
                ->where('id_jenis_pelatihan', $request->id_jenis_pelatihan)
                ->where('kategori', $request->kategori)
                ->where('id', '!=', $angkatan->id);

            if ($request->kategori === 'PNBP') {
                $exists = $query->exists();
            } else {
                $exists = $query
                    ->where('wilayah', $request->wilayah)
                    ->exists();
            }

            if ($exists) {
                return back()
                    ->withErrors([
                        'nama_angkatan' => 'Data angkatan duplikat berdasarkan aturan kategori.'
                    ])
                    ->withInput();
            }


            DB::beginTransaction();

            $angkatan->update([
                'id_jenis_pelatihan' => $request->id_jenis_pelatihan,
                'nama_angkatan' => $request->nama_angkatan,
                'tahun' => $request->tahun,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'kuota' => $request->kuota,
                'status_angkatan' => $request->status_angkatan,
                'kunci_edit'=>$request->kunci_edit,
                'kunci_judul' => $request->kunci_judul,
                'link_gb_wa' => $request->link_gb_wa,
                'kategori' => $request->kategori,
                'wilayah'  => $request->kategori === 'FASILITASI' ? $request->wilayah : null,

            ]);

            DB::commit();
            aktifitas("Memperbaharui Angkatan Baru", $angkatan);
            return redirect()->route('angkatan.index')
                ->with('success', 'Angkatan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui angkatan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified angkatan from storage.
     */
    public function destroy($id)
    {
        $angkatan = Angkatan::findOrFail($id);

        // Check if angkatan has peserta
        if ($angkatan->pendaftaran()->count() > 0) {
            return redirect()->route('angkatan.index')
                ->with('error', 'Tidak dapat menghapus angkatan yang sudah memiliki peserta. Pindahkan peserta terlebih dahulu.');
        }

        try {
            $angkatan->delete();
            aktifitas("Menghapus Angkatan Baru", $angkatan);
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Angkatan berhasil dihapus'
                ]);
            }

            return redirect()->route('angkatan.index')
                ->with('success', 'Angkatan berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus angkatan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('angkatan.index')
                ->with('error', 'Gagal menghapus angkatan: ' . $e->getMessage());
        }
    }

}
