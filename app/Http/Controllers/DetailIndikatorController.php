<?php

namespace App\Http\Controllers;

use App\Models\DetailIndikator;
use App\Models\IndikatorNilai;
use App\Models\JenisNilai;
use App\Models\JenisPelatihan;
use Illuminate\Http\Request;

class DetailIndikatorController extends Controller
{
    public function index(JenisPelatihan $jenisPelatihan, JenisNilai $jenisNilai, IndikatorNilai $indikatorNilai)
    {
        $detailIndikator = DetailIndikator::where('id_indikator_nilai', $indikatorNilai->id)->orderBy('level')->get();
        return view('admin.indikator-penilaian.detail-indikator.index', compact('jenisPelatihan', 'jenisNilai', 'indikatorNilai', 'detailIndikator'));
    }

    public function store(Request $request, JenisPelatihan $jenisPelatihan, JenisNilai $jenisNilai, IndikatorNilai $indikatorNilai)
    {
        DetailIndikator::create([
            'id_indikator_nilai' => $indikatorNilai->id,
            'level'  => $request->level,
            'uraian' => $request->uraian,
            'range'  => $request->range,
        ]);
        return back()->with('success', 'Detail indikator berhasil ditambahkan.');
    }

    public function update(Request $request, JenisPelatihan $jenisPelatihan, JenisNilai $jenisNilai, IndikatorNilai $indikatorNilai, $id)
    {
        DetailIndikator::findOrFail($id)->update([
            'level'  => $request->level,
            'uraian' => $request->uraian,
            'range'  => $request->range,
        ]);
        return back()->with('success', 'Detail indikator berhasil diperbarui.');
    }

    public function destroy(JenisPelatihan $jenisPelatihan, JenisNilai $jenisNilai, IndikatorNilai $indikatorNilai, $id)
    {
        DetailIndikator::findOrFail($id)->delete();
        return back()->with('success', 'Detail indikator berhasil dihapus.');
    }
}
