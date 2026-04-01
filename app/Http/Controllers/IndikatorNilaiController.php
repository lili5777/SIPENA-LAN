<?php

namespace App\Http\Controllers;

use App\Models\IndikatorNilai;
use App\Models\JenisNilai;
use App\Models\JenisPelatihan;
use Illuminate\Http\Request;

class IndikatorNilaiController extends Controller
{
    public function index(JenisPelatihan $jenisPelatihan, JenisNilai $jenisNilai)
    {
        $indikatorNilai  = IndikatorNilai::where('id_jenis_nilai', $jenisNilai->id)->get();
        $totalBobot      = $indikatorNilai->sum('bobot');
        $sisaBobot       = round($jenisNilai->bobot - $totalBobot, 2);
 
        return view('admin.indikator-penilaian.indikator.index', compact(
            'jenisPelatihan',
            'jenisNilai',
            'indikatorNilai',
            'totalBobot',
            'sisaBobot'
        ));
    }
 
    public function store(Request $request, JenisPelatihan $jenisPelatihan, JenisNilai $jenisNilai)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0.01',
        ]);
 
        // Hitung sisa bobot yang tersedia di jenis nilai ini
        $totalSudahAda = IndikatorNilai::where('id_jenis_nilai', $jenisNilai->id)->sum('bobot');
        $sisaBobot     = round($jenisNilai->bobot - $totalSudahAda, 2);
 
        if ($request->bobot > $sisaBobot) {
            return back()
                ->withInput()
                ->with('error', "Bobot melebihi batas jenis nilai \"{$jenisNilai->name}\" ({$jenisNilai->bobot}%). Sisa bobot tersedia: {$sisaBobot}%");
        }
 
        IndikatorNilai::create([
            'id_jenis_nilai' => $jenisNilai->id,
            'name'           => $request->name,
            'deskripsi'      => $request->deskripsi,
            'bobot'          => $request->bobot,
        ]);
 
        return back()->with('success', "Indikator \"{$request->name}\" berhasil ditambahkan.");
    }
 
    public function update(Request $request, JenisPelatihan $jenisPelatihan, JenisNilai $jenisNilai, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0.01',
        ]);
 
        // Hitung total bobot SELAIN record yang sedang diedit
        $totalSelainIni = IndikatorNilai::where('id_jenis_nilai', $jenisNilai->id)
                            ->where('id', '!=', $id)
                            ->sum('bobot');
        $sisaBobot = round($jenisNilai->bobot - $totalSelainIni, 2);
 
        if ($request->bobot > $sisaBobot) {
            return back()
                ->withInput()
                ->with('error', "Bobot melebihi batas. Maksimal bobot yang bisa diisi: {$sisaBobot}%");
        }
 
        IndikatorNilai::findOrFail($id)->update([
            'name'      => $request->name,
            'deskripsi' => $request->deskripsi,
            'bobot'     => $request->bobot,
        ]);
 
        return back()->with('success', "Indikator \"{$request->name}\" berhasil diperbarui.");
    }
 
    public function destroy(JenisPelatihan $jenisPelatihan, JenisNilai $jenisNilai, $id)
    {
        $indikator = IndikatorNilai::findOrFail($id);
        $nama      = $indikator->name;
        $indikator->delete();
 
        return back()->with('success', "Indikator \"{$nama}\" berhasil dihapus.");
    }
}
 