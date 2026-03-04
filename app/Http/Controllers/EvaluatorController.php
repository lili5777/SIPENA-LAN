<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evaluator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluatorController extends Controller
{
    public function index(Request $request)
    {
        $query = Evaluator::withCount('kelompok');

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
            $evaluator = $query->get();
            $evaluator = new \Illuminate\Pagination\LengthAwarePaginator(
                $evaluator, $evaluator->count(), $evaluator->count(), 1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $evaluator = $query->paginate($perPage)->appends($request->except('page'));
        }

        $all               = Evaluator::all();
        $totalEvaluator    = $all->count();
        $aktifEvaluator    = $all->where('status_aktif', true)->count();
        $nonaktifEvaluator = $totalEvaluator - $aktifEvaluator;

        return view('admin.evaluator.index', compact('evaluator', 'totalEvaluator', 'aktifEvaluator', 'nonaktifEvaluator'));
    }

    public function create()
    {
        return view('admin.evaluator.form', ['isEdit' => false]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:200',
            'nip'            => 'nullable|string|max:200|unique:evaluators,nip',
            'jabatan'        => 'nullable|string|max:200',
            'nomor_rekening' => 'nullable|string|max:200',
            'npwp'           => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:100|unique:evaluators,email',
            'nomor_hp'       => 'nullable|string|max:20',
            'status_aktif'   => 'required|boolean',
            'golongan'       => 'nullable|string|max:50',
            'pangkat'        => 'nullable|string|max:100',
        ], array_merge($this->messages('evaluator'), [
            'nip.unique' => 'NIP "' . $request->nip . '" sudah terdaftar pada evaluator lain.',
        ]));

        try {
            DB::beginTransaction();
            Evaluator::create([
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
            return redirect()->route('evaluator.index')->with('success', 'Evaluator berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan evaluator: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $evaluator = Evaluator::findOrFail($id);
        return view('admin.evaluator.form', ['evaluator' => $evaluator, 'isEdit' => true]);
    }

    public function update(Request $request, $id)
    {
        $evaluator = Evaluator::findOrFail($id);

        $request->validate([
            'nama'           => 'required|string|max:200',
            'nip'            => 'nullable|string|max:200|unique:evaluators,nip,' . $id,
            'jabatan'        => 'nullable|string|max:200',
            'nomor_rekening' => 'nullable|string|max:200',
            'npwp'           => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:100|unique:evaluators,email,' . $id,
            'nomor_hp'       => 'nullable|string|max:20',
            'status_aktif'   => 'required|boolean',
            'golongan'       => 'nullable|string|max:50',
            'pangkat'        => 'nullable|string|max:100',
        ], array_merge($this->messages('evaluator'), [
            'nip.unique' => 'NIP "' . $request->nip . '" sudah terdaftar pada evaluator lain.',
        ]));

        try {
            DB::beginTransaction();
            $evaluator->update([
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
            return redirect()->route('evaluator.index')->with('success', 'Evaluator berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui evaluator: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $evaluator = Evaluator::findOrFail($id);

        if ($evaluator->kelompok()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus evaluator yang masih terhubung ke kelompok.'
                ], 400);
            }
            return redirect()->route('evaluator.index')
                ->with('error', 'Tidak dapat menghapus evaluator yang masih terhubung ke kelompok.');
        }

        try {
            $evaluator->delete();
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Evaluator berhasil dihapus']);
            }
            return redirect()->route('evaluator.index')->with('success', 'Evaluator berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }
            return redirect()->route('evaluator.index')->with('error', 'Gagal menghapus evaluator: ' . $e->getMessage());
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