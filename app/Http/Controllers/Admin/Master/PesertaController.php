<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use App\Models\JenisPelatihan;
use App\Models\KepegawaianPeserta;
use App\Models\Angkatan;
use App\Models\PesertaMentor;
use App\Models\Mentor;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    /**
     * Display a listing of peserta PKN TK II.
     */
    public function index(Request $request)
    {
        // Ambil ID untuk PKN TK II (asumsi id = 1)
        $jenisPelatihanId = 1;

        // Query untuk mendapatkan peserta yang mendaftar PKN TK II
        $pendaftaran = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaianPeserta',
            'angkatan',
            'pesertaMentor.mentor'
        ])
            ->where('id_jenis_pelatihan', $jenisPelatihanId)
            ->latest('tanggal_daftar')
            ->get();

        // Jika ada filter angkatan
        if ($request->has('angkatan')) {
            $pendaftaran = Pendaftaran::with([
                'peserta',
                'peserta.kepegawaianPeserta',
                'angkatan',
                'pesertaMentor.mentor'
            ])
                ->where('id_jenis_pelatihan', $jenisPelatihanId)
                ->where('id_angkatan', $request->angkatan)
                ->latest('tanggal_daftar')
                ->get();
        }

        // Ambil data angkatan untuk filter dropdown
        $angkatanList = Angkatan::where('id_jenis_pelatihan', $jenisPelatihanId)
            ->where('status_angkatan', 'aktif')
            ->orderBy('tahun', 'desc')
            ->get();

        // Ambil jenis pelatihan untuk judul
        $jenisPelatihan = JenisPelatihan::find($jenisPelatihanId);

        return view('admin.peserta.pkn.index', compact('pendaftaran', 'angkatanList', 'jenisPelatihan'));
    }

    /**
     * Get peserta detail for modal.
     */
    public function getDetail($id)
    {
        $pendaftaran = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaianPeserta',
            'angkatan',
            'pesertaMentor.mentor',
            'jenisPelatihan'
        ])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'pendaftaran' => $pendaftaran,
                'peserta' => $pendaftaran->peserta,
                'kepegawaian' => $pendaftaran->peserta->kepegawaianPeserta,
                'angkatan' => $pendaftaran->angkatan,
                'mentor' => $pendaftaran->pesertaMentor->first()?->mentor ?? null
            ]
        ]);
    }

    /**
     * Update status pendaftaran.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pendaftaran' => 'required|in:pending,diterima,ditolak,lulus',
            'catatan_verifikasi' => 'nullable|string|max:500'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status_pendaftaran' => $request->status_pendaftaran,
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'tanggal_verifikasi' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pendaftaran berhasil diperbarui'
        ]);
    }
}
