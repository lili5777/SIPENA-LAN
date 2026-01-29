<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visi;
use App\Models\Misi;

class VisiMisiController extends Controller
{
    // Index - Show all
    public function index()
    {
        $visi = Visi::first();
        $misi = Misi::all();

        return view('admin.visi-misi.index', compact('visi', 'misi'));
    }

    // Visi Create
    public function createVisi()
    {
        return view('admin.visi-misi.visi-create');
    }

    // Visi Store
    public function storeVisi(Request $request)
    {
        $request->validate([
            'visi' => 'required|string',
            'ctt' => 'nullable|string',
        ]);

        Visi::create($request->all());

        return redirect()->route('visi-misi.index')
            ->with('success', 'Visi berhasil ditambahkan');
    }

    // Visi Edit
    public function editVisi()
    {
        $visi = Visi::first();

        if (!$visi) {
            return redirect()->route('visi-misi.index')
                ->with('error', 'Data visi tidak ditemukan');
        }

        return view('admin.visi-misi.visi-edit', compact('visi'));
    }

    // Visi Update
    public function updateVisi(Request $request, Visi $visi)
    {
        $request->validate([
            'visi' => 'required|string',
            'ctt' => 'nullable|string',
        ]);

        $visi->update($request->all());

        return redirect()->route('visi-misi.index')
            ->with('success', 'Visi berhasil diperbarui');
    }

    // Misi Create
    public function createMisi()
    {
        $icons = [
            'users' => 'Users',
            'edit' => 'Edit',
            'zap' => 'Zap',
            'book' => 'Book'
        ];

        return view('admin.visi-misi.misi-create', compact('icons'));
    }

    // Misi Store
    public function storeMisi(Request $request)
    {
        $request->validate([
            'isi' => 'required|string',
            'ctt' => 'required|string|max:50',
            'icon' => 'required|in:users,edit,zap,book',
        ]);

        Misi::create($request->all());

        return redirect()->route('visi-misi.index')
            ->with('success', 'Misi berhasil ditambahkan');
    }

    // Misi Edit
    public function editMisi(Misi $misi)
    {
        $icons = [
            'users' => 'Users',
            'edit' => 'Edit',
            'zap' => 'Zap',
            'book' => 'Book'
        ];

        return view('admin.visi-misi.misi-edit', compact('misi', 'icons'));
    }

    // Misi Update
    public function updateMisi(Request $request, Misi $misi)
    {
        $request->validate([
            'isi' => 'required|string',
            'ctt' => 'required|string|max:50',
            'icon' => 'required|in:users,edit,zap,book',
        ]);

        $misi->update($request->all());

        return redirect()->route('visi-misi.index')
            ->with('success', 'Misi berhasil diperbarui');
    }

    // Misi Destroy
    public function destroyMisi(Misi $misi)
    {
        $misi->delete();

        return redirect()->route('visi-misi.index')
            ->with('success', 'Misi berhasil dihapus');
    }
}
