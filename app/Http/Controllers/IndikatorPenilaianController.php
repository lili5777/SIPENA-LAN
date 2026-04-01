<?php

namespace App\Http\Controllers;

use App\Models\JenisPelatihan;
use Illuminate\Http\Request;

class IndikatorPenilaianController extends Controller
{
    //
     public function index()
    {
        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();
        return view('admin.indikator-penilaian.index', compact('jenisPelatihan'));
    }
}
