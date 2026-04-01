<?php

namespace App\Http\Controllers;

use App\Models\JenisNilai;
use App\Models\JenisPelatihan;
use Illuminate\Http\Request;

class JenisNilaiController extends Controller
{
    public function index(JenisPelatihan $jenisPelatihan)
    {
        $jenisNilai        = JenisNilai::where('id_jenis_pelatihan', $jenisPelatihan->id)->get();
        $totalBobot        = $jenisNilai->sum('bobot');
        $sisaBobot         = round(100 - $totalBobot, 2);
 
        return view('admin.indikator-penilaian.jenis-nilai.index', compact(
            'jenisPelatihan',
            'jenisNilai',
            'totalBobot',
            'sisaBobot'
        ));
    }
 
    public function store(Request $request, JenisPelatihan $jenisPelatihan)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0.01|max:100',
        ]);
 
        // Hitung total bobot yang sudah ada
        $totalSudahAda = JenisNilai::where('id_jenis_pelatihan', $jenisPelatihan->id)->sum('bobot');
        $sisaBobot     = round(100 - $totalSudahAda, 2);
 
        if ($request->bobot > $sisaBobot) {
            return back()
                ->withInput()
                ->with('error', "Bobot melebihi batas. Sisa bobot yang tersedia: {$sisaBobot}%");
        }
 
        JenisNilai::create([
            'id_jenis_pelatihan' => $jenisPelatihan->id,
            'name'               => $request->name,
            'deskripsi'          => $request->deskripsi,
            'bobot'              => $request->bobot,
        ]);
 
        return back()->with('success', "Jenis nilai \"{$request->name}\" berhasil ditambahkan.");
    }
 
    public function update(Request $request, JenisPelatihan $jenisPelatihan, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0.01|max:100',
        ]);
 
        $jenisNilai = JenisNilai::findOrFail($id);
 
        // Hitung total bobot SELAIN record yang sedang diedit
        $totalSelainIni = JenisNilai::where('id_jenis_pelatihan', $jenisPelatihan->id)
                            ->where('id', '!=', $id)
                            ->sum('bobot');
        $sisaBobot = round(100 - $totalSelainIni, 2);
 
        if ($request->bobot > $sisaBobot) {
            return back()
                ->withInput()
                ->with('error', "Bobot melebihi batas. Maksimal bobot yang bisa diisi: {$sisaBobot}%");
        }
 
        // Cek apakah bobot baru lebih kecil dari total bobot indikator di dalamnya
        $totalBobotIndikator = $jenisNilai->totalBobotIndikator();
        if ($request->bobot < $totalBobotIndikator) {
            return back()
                ->withInput()
                ->with('error', "Bobot tidak boleh lebih kecil dari total bobot indikator di dalamnya ({$totalBobotIndikator}%). Kurangi bobot indikator terlebih dahulu.");
        }
 
        $jenisNilai->update([
            'name'      => $request->name,
            'deskripsi' => $request->deskripsi,
            'bobot'     => $request->bobot,
        ]);
 
        return back()->with('success', "Jenis nilai \"{$request->name}\" berhasil diperbarui.");
    }
 
    public function destroy(JenisPelatihan $jenisPelatihan, $id)
    {
        $jenisNilai = JenisNilai::findOrFail($id);
        $nama       = $jenisNilai->name;
        $jenisNilai->delete();
 
        return back()->with('success', "Jenis nilai \"{$nama}\" berhasil dihapus.");
    }
}
