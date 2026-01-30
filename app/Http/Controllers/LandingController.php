<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Misi;
use App\Models\Pejabat;
use App\Models\Visi;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    //

    public function index()
    {
        $beritas = Berita::latest()
            ->paginate(9); // Tampilkan 9 berita per halaman

        return view('welcome', compact('beritas'));
    }

    public function showBerita($id)
    {
        $berita = Berita::findOrFail($id);

        return view('detail', compact('berita'));
    }

    public function profil()
    {
        $pejabat = Pejabat::orderBy('posisi', 'asc')->get();
        $visi = Visi::first();
        $misis = Misi::orderBy('id', 'asc')->get(); // atau orderBy('created_at')
        return view('profil', compact('visi', 'misis', 'pejabat'));
    }

    public function publikasi()
    {
        return view('publikasi');
    }
}
