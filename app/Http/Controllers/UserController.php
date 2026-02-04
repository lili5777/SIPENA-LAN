<?php

namespace App\Http\Controllers;

use App\Models\Aktifikat;
use App\Models\Angkatan;
use App\Models\JenisPelatihan;
use App\Models\PicPeserta;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::with(['role', 'picPesertas.jenisPelatihan', 'picPesertas.angkatan'])->get();
        $allJenisPelatihan = JenisPelatihan::where('aktif', 1)->get();
        $allAngkatan = Angkatan::with('jenisPelatihan')->get();
        return view('admin.users.index', compact('users', 'allJenisPelatihan', 'allAngkatan'));
    }

    // Method baru untuk mengambil akses PIC
    public function getPicAccess($userId)
    {
        $user = User::with(['picPesertas.jenisPelatihan', 'picPesertas.angkatan'])->findOrFail($userId);

        $picAccess = [
            'jenis_pelatihan' => $user->picPesertas->pluck('jenispelatihan_id')->toArray(),
            'angkatan' => $user->picPesertas->pluck('angkatan_id')->toArray(),
        ];

        return response()->json($picAccess);
    }

    // Method baru untuk update akses PIC
    public function updatePicAccess(Request $request, $userId)
    {
        $request->validate([
            'jenis_pelatihan' => 'required|array',
            'jenis_pelatihan.*' => 'exists:jenis_pelatihan,id',
            'angkatan' => 'required|array',
            'angkatan.*' => 'exists:angkatan,id',
        ]);

        try {
            // Hapus akses lama
            PicPeserta::where('user_id', $userId)->delete();

            $jenisPelatihanIds = $request->jenis_pelatihan;
            $angkatanIds = $request->angkatan;

            // Buat kombinasi semua tanpa validasi hubungan
            foreach ($jenisPelatihanIds as $jenisId) {
                foreach ($angkatanIds as $angkatanId) {
                    PicPeserta::create([
                        'user_id' => $userId,
                        'jenispelatihan_id' => $jenisId,
                        'angkatan_id' => $angkatanId,
                    ]);
                }
            }
            aktifitas("Memperbaharui Akses PIC");

            return response()->json([
                'success' => true,
                'message' => 'Akses PIC berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui akses: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }
    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:3',
            'no_telp' => 'required|string|max:20',
            'role_id' => 'exists:roles,id',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'no_telp' => $data['no_telp'],
            'role_id' => $data['role_id'],
        ]);

        aktifitas("Membuat User Baru", $user);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Validasi input
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:3',
            'no_telp' => 'required|string|max:20',
            'role_id' => 'exists:roles,id',
        ]);

        // Update user
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->no_telp = $data['no_telp'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->role_id = $data['role_id'];
        $user->save();

        aktifitas("Memperbaharui User",$user);
        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Hapus user
        aktifitas("Menghapus User", $user);
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    
}
