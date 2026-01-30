<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Pejabat;

class PejabatController extends Controller
{
    /**
     * Display a listing of the pejabat.
     */
    public function index()
    {
        $pejabats = Pejabat::orderBy('posisi')->get();
        return view('admin.pejabat.index', compact('pejabats'));
    }

    /**
     * Show the form for creating a new pejabat.
     */
    public function create()
    {
        $maxPosisi = Pejabat::max('posisi') ?? 0;
        return view('admin.pejabat.form', [
            'pejabat' => null,
            'nextPosisi' => $maxPosisi + 1
        ]);
    }

    /**
     * Store a newly created pejabat in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pejabat' => 'required|string|max:200',
            'jabatan_pejabat' => 'required|string|max:200',
            'nip_pejabat' => 'nullable|string|max:50',
            'posisi' => 'required|integer|min:1',
            'foto_pejabat' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('foto_pejabat')) {
            $file = $request->file('foto_pejabat');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('gambar'), $filename);
            $validated['foto_pejabat'] = $filename;
        }

        Pejabat::create($validated);

        return redirect()->route('pejabat.index')->with('success', 'Data pejabat berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified pejabat.
     */
    public function edit(Pejabat $pejabat)
    {
        return view('admin.pejabat.form', [
            'pejabat' => $pejabat,
            'nextPosisi' => null
        ]);
    }

    /**
     * Update the specified pejabat in storage.
     */
    public function update(Request $request, Pejabat $pejabat)
    {
        $validated = $request->validate([
            'nama_pejabat' => 'required|string|max:200',
            'jabatan_pejabat' => 'required|string|max:200',
            'nip_pejabat' => 'nullable|string|max:50',
            'posisi' => 'required|integer|min:1',
            'foto_pejabat' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('foto_pejabat')) {
            // Delete old photo if exists
            if ($pejabat->foto_pejabat && file_exists(public_path('gambar/' . $pejabat->foto_pejabat))) {
                unlink(public_path('gambar/' . $pejabat->foto_pejabat));
            }

            $file = $request->file('foto_pejabat');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('gambar'), $filename);
            $validated['foto_pejabat'] = $filename;
        }

        $pejabat->update($validated);

        return redirect()->route('pejabat.index')->with('success', 'Data pejabat berhasil diperbarui!');
    }

    /**
     * Remove the specified pejabat from storage.
     */
    public function destroy(Pejabat $pejabat)
    {
        // Delete photo if exists
        if ($pejabat->foto_pejabat && file_exists(public_path('gambar/' . $pejabat->foto_pejabat))) {
            unlink(public_path('gambar/' . $pejabat->foto_pejabat));
        }

        $pejabat->delete();

        return redirect()->route('pejabat.index')->with('success', 'Data pejabat berhasil dihapus!');
    }

    /**
     * Update positions of pejabat.
     */
    public function updatePositions(Request $request)
    {
        $positions = $request->input('positions');

        foreach ($positions as $position) {
            Pejabat::where('id', $position['id'])->update(['posisi' => $position['position']]);
        }

        return response()->json(['success' => true, 'message' => 'Posisi berhasil diperbarui!']);
    }
}
