<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        # Ambil semua data role beserta relasi permissions-nya
        # ->with('permissions') artinya sekaligus mengambil data permission yang dimiliki role
        $roles = Role::with('permissions')->get();

        # Kirim data roles ke view admin.roles.index untuk ditampilkan
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        # Ambil semua data permission dari tabel permissions
        # orderBy('name') agar data permission diurutkan berdasarkan nama
        $permissions = Permission::orderBy('name')->get();

        # Kelompokkan berdasarkan prefix sebelum tanda titik
        // $groupedPermissions = $permissions->groupBy(function ($permission) {
        //     return explode('.', $permission->name)[0];
        // });

        # Kirim data permissions ke view admin.roles.create
        # agar bisa ditampilkan sebagai checkbox untuk memilih permission saat membuat role baru
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        # Validasi input dari form
        $data = $request->validate([
            'name' => 'required|unique:roles,name',   # name wajib, tidak boleh sama dengan role lain
            'description' => 'nullable|string',      # description boleh kosong, tapi jika ada harus string
            'permissions' => 'array|min:1',          # permissions harus berupa array dan minimal pilih 1
            'permissions.*' => 'exists:permissions,id', # setiap item dalam array harus ada di tabel permissions
        ]);

        # Buat role baru ke database
        $role = Role::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null, # pakai null jika kosong
        ]);

        # Jika ada permission yang dipilih
        if (!empty($data['permissions'])) {
            # Sinkronisasi relasi role_permission (pivot table)
            # sync akan menambahkan data ke tabel role_permission
            $role->permissions()->sync($data['permissions']);
        }

        

        # Redirect ke halaman daftar role dengan pesan sukses
        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function delete($id)
    {
        # Cari role berdasarkan ID, kalau tidak ada langsung error 404
        $role = Role::findOrFail($id);

        # Hapus relasi role dengan permission di tabel pivot (role_permission)
        $role->permissions()->detach();

        # Hapus data role dari tabel roles
        $role->delete();

        

        # Kembali ke halaman daftar role dengan pesan sukses
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }


    public function edit(Role $role)
    {
        # Ambil semua permission yang ada
        $permissions = Permission::orderBy('name')->get();

        # Ambil daftar id permission yang dimiliki oleh role tertentu
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        # Kirim data role, semua permissions, dan permission yang dimiliki role
        # ke view edit untuk ditampilkan dalam form (misalnya checkbox sudah tercentang)
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }


    public function update(Request $request, Role $role)
    {
        # Validasi input dari form
        $data = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id, # name harus unik kecuali untuk role ini sendiri
            'description' => 'nullable|string',
            'permissions' => 'array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        # Update role (name & description) ke tabel roles
        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        # Jika ada permission yang dipilih
        if (!empty($data['permissions'])) {
            # Sinkronisasi permission baru ke role
            $role->permissions()->sync($data['permissions']);
        } else {
            # Jika tidak ada permission yang dipilih, hapus semua relasi
            $role->permissions()->detach();
        }

      

        # Redirect ke daftar role dengan pesan sukses
        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }


   
}
