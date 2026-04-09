<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        $query = Coach::withCount('kelompok');

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
            $coach = $query->get();
            $coach = new \Illuminate\Pagination\LengthAwarePaginator(
                $coach, $coach->count(), $coach->count(), 1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $coach = $query->paginate($perPage)->appends($request->except('page'));
        }

        $all           = Coach::all();
        $totalCoach    = $all->count();
        $aktifCoach    = $all->where('status_aktif', true)->count();
        $nonaktifCoach = $totalCoach - $aktifCoach;

        return view('admin.coach.index', compact('coach', 'totalCoach', 'aktifCoach', 'nonaktifCoach'));
    }

    public function create()
    {
        return view('admin.coach.form', ['isEdit' => false]);
    }

    public function store(Request $request)
    {
        $buatAkun = $request->has('buat_akun');

        $rules = [
            'nama' => 'required|string|max:200|unique:coaches,nama',
            'nip'            => 'nullable|string|max:200|unique:coaches,nip',
            'jabatan'        => 'nullable|string|max:200',
            'nomor_rekening' => 'nullable|string|max:200',
            'npwp'           => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:100|unique:coaches,email',
            'nomor_hp'       => 'nullable|string|max:20',
            'status_aktif'   => 'required|boolean',
            'golongan'       => 'nullable|string|max:50',
            'pangkat'        => 'nullable|string|max:100',
        ];

        if ($buatAkun) {
            $rules['email']    = 'required|email|max:100|unique:coaches,email|unique:users,email';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules, array_merge($this->messages('coach'), [
            'nama.unique'      => 'Nama coach "' . $request->nama . '" sudah terdaftar.',
            'nip.unique'       => 'NIP "' . $request->nip . '" sudah terdaftar pada coach lain.',
            'email.unique'     => 'Email sudah digunakan, gunakan email lain.',
            'password.required'   => 'Password wajib diisi jika membuat akun.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]));

        try {
            DB::beginTransaction();

            $coach = Coach::create([
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
                $role = Role::where('name', 'coach')->firstOrFail();
                User::create([
                    'name'     => $request->nama,
                    'email'    => $request->email,   // pakai email kontak
                    'no_telp'  => $request->nomor_hp,
                    'role_id'  => $role->id,
                    'coach_id' => $coach->id,
                    'password' => Hash::make($request->password),
                ]);
            }

            DB::commit();
            return redirect()->route('coach.index')->with('success', 'Coach berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan coach: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $coach = Coach::with('user')->findOrFail($id);
        return view('admin.coach.form', ['coach' => $coach, 'isEdit' => true]);
    }

    public function update(Request $request, $id)
    {
        $coach    = Coach::with('user')->findOrFail($id);
        $buatAkun = $request->has('buat_akun');

        $rules = [
            'nama' => 'required|string|max:200|unique:coaches,nama,' . $id, 
            'nip'            => 'nullable|string|max:200|unique:coaches,nip,' . $id,
            'jabatan'        => 'nullable|string|max:200',
            'nomor_rekening' => 'nullable|string|max:200',
            'npwp'           => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:100|unique:coaches,email,' . $id,
            'nomor_hp'       => 'nullable|string|max:20',
            'status_aktif'   => 'required|boolean',
            'golongan'       => 'nullable|string|max:50',
            'pangkat'        => 'nullable|string|max:100',
        ];

        if ($buatAkun) {
            // Email harus unik di users, kecuali milik user yang sudah terhubung
            $ignoreUserId = optional($coach->user)->id;
            $rules['email'] = [
                'required', 'email', 'max:100',
                'unique:coaches,email,' . $id,
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($ignoreUserId),
            ];
            // Password opsional saat edit (hanya wajib jika diisi)
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        $request->validate($rules, array_merge($this->messages('coach'), [
            'nama.unique'      => 'Nama coach "' . $request->nama . '" sudah terdaftar.',
            'nip.unique'      => 'NIP "' . $request->nip . '" sudah terdaftar pada coach lain.',
            'email.unique'    => 'Email sudah digunakan, gunakan email lain.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]));

        try {
            DB::beginTransaction();

            $coach->update([
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
                $role = Role::where('name', 'coach')->firstOrFail();

                if ($coach->user) {
                    // Update akun yang sudah ada
                    $updateData = [
                        'name'    => $request->nama,
                        'email'   => $request->email,
                        'no_telp' => $request->nomor_hp,
                    ];
                    if ($request->filled('password')) {
                        $updateData['password'] = Hash::make($request->password);
                    }
                    $coach->user->update($updateData);
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
                        'coach_id' => $coach->id,
                        'password' => Hash::make($request->password),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('coach.index')->with('success', 'Coach berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui coach: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $coach = Coach::with('user')->findOrFail($id);

        if ($coach->kelompok()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus coach yang masih terhubung ke kelompok.'
                ], 400);
            }

            return redirect()->route('coach.index')
                ->with('error', 'Tidak dapat menghapus coach yang masih terhubung ke kelompok.');
        }

        try {
            DB::beginTransaction();

            // ✅ hapus akun user jika ada
            if ($coach->user) {
                $coach->user->delete();
            }

            // ✅ hapus coach
            $coach->delete();

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coach dan akun berhasil dihapus'
                ]);
            }

            return redirect()->route('coach.index')
                ->with('success', 'Coach dan akun berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('coach.index')
                ->with('error', 'Gagal menghapus coach: ' . $e->getMessage());
        }
    }

    public function checkNama(Request $request)
{
    $query = Coach::where('nama', $request->nama);
    
    if ($request->filled('ignore_id')) {
        $query->where('id', '!=', $request->ignore_id);
    }

    return response()->json(['exists' => $query->exists()]);
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