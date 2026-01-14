<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peserta;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Inisialisasi variabel
        $peserta = null;
        $kepegawaian = null;
        $pendaftaranTerbaru = null;
        $mentorData = null;
        $jenisPelatihanData = null;
        $angkatanData = null;
        $semuaPendaftaran = [];

        // Ambil data peserta jika role user adalah 'user'
        if ($user->role->name == 'user') {
            // Ambil peserta dengan SEMUA relasi yang dibutuhkan
            $peserta = Peserta::where('id', $user->peserta_id)
                ->with([
                    'kepegawaian' => function ($query) {
                        $query->with(['provinsi', 'kabupaten']);
                    },
                    'pendaftaran' => function ($query) {
                        $query->with([
                            'jenisPelatihan',
                            'angkatan',
                            'pesertaMentor' => function ($q) {
                                $q->with('mentor');
                            }
                        ])->orderBy('tanggal_daftar', 'desc');
                    },
                    'logAktivitas'
                ])
                ->first();

            // Jika peserta ditemukan
            if ($peserta) {
                // Ambil data kepegawaian
                $kepegawaian = $peserta->kepegawaian;

                // Ambil pendaftaran terbaru
                $pendaftaranTerbaru = $peserta->pendaftaran->first();

                // Ambil semua pendaftaran untuk ditampilkan
                $semuaPendaftaran = $peserta->pendaftaran;

                // Jika ada pendaftaran terbaru, ambil data mentor
                if ($pendaftaranTerbaru && $pendaftaranTerbaru->pesertaMentor->isNotEmpty()) {
                    $mentorData = $pendaftaranTerbaru->pesertaMentor->first()->mentor;
                }

                // Ambil data jenis pelatihan dari pendaftaran terbaru
                if ($pendaftaranTerbaru && $pendaftaranTerbaru->jenisPelatihan) {
                    $jenisPelatihanData = $pendaftaranTerbaru->jenisPelatihan;
                }

                // Ambil data angkatan dari pendaftaran terbaru
                if ($pendaftaranTerbaru && $pendaftaranTerbaru->angkatan) {
                    $angkatanData = $pendaftaranTerbaru->angkatan;
                }
            }
        }

        // Kirimkan data ke view
        return view('admin.dashboard', compact(
            'user',
            'peserta',
            'kepegawaian',
            'pendaftaranTerbaru',
            'mentorData',
            'jenisPelatihanData',
            'angkatanData',
            'semuaPendaftaran'
        ));
    }
}
