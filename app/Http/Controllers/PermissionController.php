<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        # Ambil semua data permission beserta relasi roles-nya
        $permissions = Permission::with('roles')
            ->withCount('roles')
            ->orderBy('name')
            ->get();

        # Kelompokkan permission berdasarkan module (prefix sebelum titik)
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'general';
        });

        return view('admin.permissions.index', compact('permissions', 'groupedPermissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|unique:permissions,name|regex:/^[a-zA-Z0-9_\.]+$/',
            'description' => 'nullable|string|max:255',
        ], [
            'name.regex' => 'Nama permission hanya boleh mengandung huruf, angka, underscore, dan titik.',
        ]);

        $permission = Permission::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        aktifitas("Membuat Permission Baru: {$permission->name}");

        return redirect()->route('permissions.index')
            ->with('success', "Permission <strong>{$permission->name}</strong> berhasil dibuat.");
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name'        => 'required|unique:permissions,name,' . $permission->id . '|regex:/^[a-zA-Z0-9_\.]+$/',
            'description' => 'nullable|string|max:255',
        ], [
            'name.regex' => 'Nama permission hanya boleh mengandung huruf, angka, underscore, dan titik.',
        ]);

        $permission->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        aktifitas("Memperbarui Permission: {$permission->name}");

        return redirect()->route('permissions.index')
            ->with('success', "Permission <strong>{$permission->name}</strong> berhasil diperbarui.");
    }

    public function destroy(Permission $permission)
    {
        # Lepas semua relasi dengan role di pivot table
        $permission->roles()->detach();

        $name = $permission->name;
        $permission->delete();

        aktifitas("Menghapus Permission: {$name}");

        return redirect()->route('permissions.index')
            ->with('success', "Permission <strong>{$name}</strong> berhasil dihapus.");
    }
}