<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Misi;
use App\Models\Pejabat;
use App\Models\Visi;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    //

    public function index()
    {
        return view('welcome');
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
