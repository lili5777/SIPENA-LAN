<?php

namespace App\Http\Controllers;

use App\Models\Aktifikat;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::with('role')->get();
        return view('admin.users.index', compact('users'));
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
            'role_id' => 'exists:roles,id',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => $data['role_id'],
        ]);

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
            'role_id' => 'exists:roles,id',
        ]);

        // Update user
        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->role_id = $data['role_id'];
        $user->save();


        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Hapus user
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    
}
