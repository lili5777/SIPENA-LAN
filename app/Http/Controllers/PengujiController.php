<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penguji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengujiController extends Controller
{
    public function index(Request $request)
    {
        $query = Penguji::withCount('kelompok');

        if ($request->filled('status')) {
            $query->where('status_aktif', $request->status === 'Aktif');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama',      'like', "%{$search}%")
                  ->orWhere('nip',     'like', "%{$search}%")
                  ->orWhere('email',   'like', "%{$search}%")
                  ->orWhere('nomor_hp','like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort') && $request->sort === 'kelompok') {
            $query->orderBy('kelompok_count', 'desc');
        } else {
            $query->orderBy('nama', 'asc');
        }

        $perPage = $request->get('per_page', 10);
        if ($perPage == '-1') {
            $penguji = $query->get();
            $penguji = new \Illuminate\Pagination\LengthAwarePaginator(
                $penguji, $penguji->count(), $penguji->count(), 1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $penguji = $query->paginate($perPage)->appends($request->except('page'));
        }

        $all             = Penguji::all();
        $totalPenguji    = $all->count();
        $aktifPenguji    = $all->where('status_aktif', true)->count();
        $nonaktifPenguji = $totalPenguji - $aktifPenguji;

        return view('admin.penguji.index', compact('penguji', 'totalPenguji', 'aktifPenguji', 'nonaktifPenguji'));
    }

    public function create()
    {
        return view('admin.penguji.form', ['isEdit' => false]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:200',
            'nip'            => 'nullable|string|max:200|unique:pengujis,nip',
            'jabatan'        => 'nullable|string|max:200',
            'nomor_rekening' => 'nullable|string|max:200',
            'npwp'           => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:100|unique:pengujis,email',
            'nomor_hp'       => 'nullable|string|max:20',
            'status_aktif'   => 'required|boolean',
            'golongan'       => 'nullable|string|max:50',
            'pangkat'        => 'nullable|string|max:100',
        ], array_merge($this->messages('penguji'), [
            'nip.unique' => 'NIP "' . $request->nip . '" sudah terdaftar pada penguji lain.',
        ]));

        try {
            DB::beginTransaction();
            Penguji::create([
                'nama'           => $request->nama,
                'nip'            => $request->nip,
                'jabatan'        => $request->jabatan,
                'golongan'       => $request->golongan,
                'pangkat'        => $request->pangkat,
                'nomor_rekening' => $request->nomor_rekening,
                'npwp'           => $request->npwp,
                'email'          => $request->email,
                'nomor_hp'       => $request->nomor_hp,
                'status_aktif'   => $request->status_aktif,
                'dibuat_pada'    => now(),
            ]);
            DB::commit();
            return redirect()->route('penguji.index')->with('success', 'Penguji berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan penguji: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $penguji = Penguji::findOrFail($id);
        return view('admin.penguji.form', ['penguji' => $penguji, 'isEdit' => true]);
    }

    public function update(Request $request, $id)
    {
        $penguji = Penguji::findOrFail($id);

        $request->validate([
            'nama'           => 'required|string|max:200',
            'nip'            => 'nullable|string|max:200|unique:pengujis,nip,' . $id,
            'jabatan'        => 'nullable|string|max:200',
            'nomor_rekening' => 'nullable|string|max:200',
            'npwp'           => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:100|unique:pengujis,email,' . $id,
            'nomor_hp'       => 'nullable|string|max:20',
            'status_aktif'   => 'required|boolean',
            'golongan'       => 'nullable|string|max:50',
            'pangkat'        => 'nullable|string|max:100',
        ], array_merge($this->messages('penguji'), [
            'nip.unique' => 'NIP "' . $request->nip . '" sudah terdaftar pada penguji lain.',
        ]));

        try {
            DB::beginTransaction();
            $penguji->update([
                'nama'           => $request->nama,
                'nip'            => $request->nip,
                'jabatan'        => $request->jabatan,
                'golongan'       => $request->golongan,
                'pangkat'        => $request->pangkat,
                'nomor_rekening' => $request->nomor_rekening,
                'npwp'           => $request->npwp,
                'email'          => $request->email,
                'nomor_hp'       => $request->nomor_hp,
                'status_aktif'   => $request->status_aktif,
            ]);
            DB::commit();
            return redirect()->route('penguji.index')->with('success', 'Penguji berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui penguji: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $penguji = Penguji::findOrFail($id);

        if ($penguji->kelompok()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus penguji yang masih terhubung ke kelompok.'
                ], 400);
            }
            return redirect()->route('penguji.index')
                ->with('error', 'Tidak dapat menghapus penguji yang masih terhubung ke kelompok.');
        }

        try {
            $penguji->delete();
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Penguji berhasil dihapus']);
            }
            return redirect()->route('penguji.index')->with('success', 'Penguji berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }
            return redirect()->route('penguji.index')->with('error', 'Gagal menghapus penguji: ' . $e->getMessage());
        }
    }

    private function messages(string $role): array
    {
        return [
            'nama.required'         => "Nama {$role} wajib diisi.",
            'nama.max'              => "Nama {$role} maksimal 200 karakter.",
            'nip.max'               => "NIP {$role} maksimal 200 karakter.",
            'jabatan.max'           => "Jabatan {$role} maksimal 200 karakter.",
            'nomor_rekening.max'    => "Nomor rekening maksimal 200 karakter.",
            'npwp.max'              => "NPWP {$role} maksimal 50 karakter.",
            'email.email'           => 'Format email tidak valid.',
            'email.max'             => "Email {$role} maksimal 100 karakter.",
            'email.unique'          => "Email {$role} sudah terdaftar.",
            'nomor_hp.max'          => "Nomor HP {$role} maksimal 20 karakter.",
            'status_aktif.required' => "Status {$role} wajib dipilih.",
            'status_aktif.boolean'  => "Status {$role} tidak valid.",
            'golongan.max'          => 'Golongan maksimal 50 karakter.',
            'pangkat.max'           => 'Pangkat maksimal 100 karakter.',
        ];
    }
}