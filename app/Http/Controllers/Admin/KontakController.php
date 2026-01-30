<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kontak;

class KontakController extends Controller
{
    /**
     * Display the contact information.
     */
    public function index()
    {
        $kontak = Kontak::first();
        return view('admin.kontak.index', compact('kontak'));
    }

    /**
     * Show the form for creating/editing contact.
     */
    public function createOrEdit()
    {
        $kontak = Kontak::first();
        return view('admin.kontak.form', compact('kontak'));
    }

    /**
     * Store or update the contact information.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'alamat' => 'required|string|max:200',
            'nomor_hp' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'fb' => 'nullable|string|max:100',
            'ig' => 'nullable|string|max:100',
            'twitter' => 'nullable|string|max:100',
            'linkedin' => 'nullable|string|max:100',
        ]);

        $kontak = Kontak::first();

        if ($kontak) {
            $kontak->update($validated);
            $message = 'Informasi kontak berhasil diperbarui!';
        } else {
            Kontak::create($validated);
            $message = 'Informasi kontak berhasil ditambahkan!';
        }

        return redirect()->route('kontak.index')->with('success', $message);
    }
}
