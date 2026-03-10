<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;
use App\Models\Penguji;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        $all           = Penguji::all();
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
        $buatAkun = $request->has('buat_akun');

        $rules = [
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
        ];

        if ($buatAkun) {
            $rules['email']    = 'required|email|max:100|unique:pengujis,email|unique:users,email';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules, array_merge($this->messages('penguji'), [
            'nip.unique'       => 'NIP "' . $request->nip . '" sudah terdaftar pada penguji lain.',
            'email.unique'     => 'Email sudah digunakan, gunakan email lain.',
            'password.required'   => 'Password wajib diisi jika membuat akun.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]));

        try {
            DB::beginTransaction();

            $penguji = Penguji::create([
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

            if ($buatAkun) {
                $role = Role::where('name', 'penguji')->firstOrFail();
                User::create([
                    'name'     => $request->nama,
                    'email'    => $request->email,   // pakai email kontak
                    'no_telp'  => $request->nomor_hp,
                    'role_id'  => $role->id,
                    'penguji_id' => $penguji->id,
                    'password' => Hash::make($request->password),
                ]);
            }

            DB::commit();
            return redirect()->route('penguji.index')->with('success', 'Penguji berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan penguji: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $penguji = Penguji::with('user')->findOrFail($id);
        return view('admin.penguji.form', ['penguji' => $penguji, 'isEdit' => true]);
    }

    public function update(Request $request, $id)
    {
        $penguji    = Penguji::with('user')->findOrFail($id);
        $buatAkun = $request->has('buat_akun');

        $rules = [
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
        ];

        if ($buatAkun) {
            // Email harus unik di users, kecuali milik user yang sudah terhubung
            $ignoreUserId = optional($penguji->user)->id;
            $rules['email'] = [
                'required', 'email', 'max:100',
                'unique:pengujis,email,' . $id,
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($ignoreUserId),
            ];
            // Password opsional saat edit (hanya wajib jika diisi)
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        $request->validate($rules, array_merge($this->messages('penguji'), [
            'nip.unique'      => 'NIP "' . $request->nip . '" sudah terdaftar pada penguji lain.',
            'email.unique'    => 'Email sudah digunakan, gunakan email lain.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
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

            if ($buatAkun) {
                $role = Role::where('name', 'penguji')->firstOrFail();

                if ($penguji->user) {
                    // Update akun yang sudah ada
                    $updateData = [
                        'name'    => $request->nama,
                        'email'   => $request->email,
                        'no_telp' => $request->nomor_hp,
                    ];
                    if ($request->filled('password')) {
                        $updateData['password'] = Hash::make($request->password);
                    }
                    $penguji->user->update($updateData);
                } else {
                    // Buat akun baru — password wajib jika belum punya akun
                    if (!$request->filled('password')) {
                        throw new \Exception('Password wajib diisi saat membuat akun baru.');
                    }
                    User::create([
                        'name'     => $request->nama,
                        'email'    => $request->email,
                        'no_telp'  => $request->nomor_hp,
                        'role_id'  => $role->id,
                        'penguji_id' => $penguji->id,
                        'password' => Hash::make($request->password),
                    ]);
                }
            }

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
                return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus penguji yang masih terhubung ke kelompok.'], 400);
            }
            return redirect()->route('penguji.index')->with('error', 'Tidak dapat menghapus penguji yang masih terhubung ke kelompok.');
        }

        try {
            if ($penguji->user) {
                $penguji->user->delete();
            }
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