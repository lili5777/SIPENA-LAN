<?php

namespace App\Http\Controllers;

use App\Models\JenisPelatihan;
use App\Models\Angkatan;
use App\Models\Provinsi;
use App\Models\KabupatenKota;
use App\Models\Mentor;
use App\Models\Peserta;
use App\Models\KepegawaianPeserta;
use App\Models\Pendaftaran;
use App\Models\PesertaMentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan form pendaftaran
     */
    public function create()
    {
        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();
        $provinsi = Provinsi::all();
        $mentor = Mentor::where('status_aktif', true)->get();

        if (request()->ajax()) {
            return response()->json([
                'jenis_pelatihan' => $jenisPelatihan,
                'provinsi' => $provinsi,
                'mentor' => $mentor
            ]);
        }

        return view('pendaftaran.create', compact('jenisPelatihan', 'provinsi', 'mentor'));
    }

    /**
     * Menyimpan data pendaftaran
     */
    public function store(Request $request)
    {
        dd($request->all());
        // Validasi input umum
        $validated = $request->validate([
            'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id_jenis_pelatihan',
            'id_angkatan' => 'required|exists:angkatan,id_angkatan',
            'nip_nrp' => 'required|string|max:50|unique:peserta,nip_nrp',
            'nama_lengkap' => 'required|string|max:200',
            'nama_panggilan' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat_rumah' => 'required|string',
            'email_pribadi' => 'required|email|max:100',
            'nomor_hp' => 'required|string|max:20',
            'pendidikan_terakhir' => 'required|in:SD,SMP,SMA,D3,D4,S1,S2,S3',
            'bidang_studi' => 'nullable|string|max:100',
            'bidang_keahlian' => 'nullable|string|max:100',
            'status_perkawinan' => 'nullable|in:Belum Menikah,Menikah,Cerai Hidup,Cerai Mati',
            'nama_pasangan' => 'nullable|string|max:200',
            'olahraga_hobi' => 'nullable|string|max:100',
            'perokok' => 'required|in:Ya,Tidak',
            'ukuran_kaos' => 'nullable|in:S,M,L,XL,XXL,XXXL',
            'kondisi_peserta' => 'nullable|string',
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'file_pas_foto' => 'nullable|file|mimes:jpg,png|max:2048',
            'asal_instansi' => 'required|string|max:200',
            'unit_kerja' => 'nullable|string|max:200',
            'id_provinsi' => 'required|exists:provinsi,id_provinsi',
            'id_kabupaten_kota' => 'nullable|exists:kabupaten_kota,id_kabupaten_kota',
            'alamat_kantor' => 'required|string',
            'nomor_telepon_kantor' => 'nullable|string|max:20',
            'email_kantor' => 'nullable|email|max:100',
            'jabatan' => 'required|string|max:200',
            'pangkat' => 'nullable|string|max:50',
            'golongan_ruang' => 'required|string|max:10',
            'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:2048',
            'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:2048',
            'file_surat_tugas' => 'nullable|file|mimes:pdf|max:2048',
            'file_surat_sehat' => 'nullable|file|mimes:pdf|max:2048',
            'file_surat_bebas_narkoba' => 'nullable|file|mimes:pdf|max:2048',
            'id_mentor' => 'nullable|exists:mentor,id_mentor',
            'nomor_rekening_mentor' => 'nullable|string|max:100',
            'npwp_mentor' => 'nullable|string|max:50',
            'has_mentor' => 'nullable|in:Ya,Tidak',
        ]);

        // Validasi tambahan berdasarkan jenis pelatihan
        $jenisPelatihan = JenisPelatihan::find($request->id_jenis_pelatihan);
        $kode = $jenisPelatihan->kode_pelatihan;

        if ($kode === 'PKN_TK_II' || $kode === 'PKA') {
            $validated += $request->validate([
                'eselon' => 'nullable|in:I.a,I.b,II.a,II.b,III.a,III.b,IV.a,IV.b,Non Eselon',
                'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:2048',
                'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:2048',
                'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:2048',
                'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:2048',
            ]);
        }

        if ($kode === 'PKN_TK_II') {
            $validated += $request->validate([
                'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:2048',
            ]);
        }

        if ($kode === 'PD_CPNS') {
            $validated += $request->validate([
                'nomor_sk_cpns' => 'nullable|string|max:100',
                'tanggal_sk_cpns' => 'nullable|date',
                'file_sk_cpns' => 'nullable|file|mimes:pdf|max:2048',
                'file_spmt' => 'nullable|file|mimes:pdf|max:2048',
                'file_skp' => 'nullable|file|mimes:pdf|max:2048',
                'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:2048',
                'pangkat' => 'required|string|max:50',
                'file_ktp' => 'nullable|file|mimes:pdf|max:2048',
                'has_mentor' => 'nullable|in:Ya,Tidak',
                'nomor_rekening_mentor' => 'nullable|string|max:100',
                'npwp_mentor' => 'nullable|string|max:50',
            ]);
        }

        if ($kode === 'PKA') {
            $validated += $request->validate([
                'tanggal_sk_jabatan' => 'nullable|date',
                'tahun_lulus_pkp_pim_iv' => 'nullable|integer',
                'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:2048',
                'file_surat_pernyataan_administrasi' => 'nullable|file|mimes:pdf|max:2048',
                'file_sertifikat_penghargaan' => 'nullable|file|mimes:pdf|max:2048',
                'file_persetujuan_mentor' => 'nullable|file|mimes:pdf|max:2048',
                'file_ktp' => 'nullable|file|mimes:pdf|max:2048',
                'nomor_rekening_mentor' => 'nullable|string|max:100',
                'npwp_mentor' => 'nullable|string|max:50',
            ]);
        }

        // Simpan file uploads (sama seperti sebelumnya)
        $fileFields = [
            'file_ktp', 'file_pas_foto', 'file_sk_jabatan', 'file_sk_pangkat', 'file_surat_tugas',
            'file_surat_kesediaan', 'file_pakta_integritas', 'file_surat_komitmen',
            'file_surat_kelulusan_seleksi', 'file_surat_sehat', 'file_surat_bebas_narkoba',
            'file_surat_pernyataan_administrasi', 'file_sertifikat_penghargaan',
            'file_sk_cpns', 'file_spmt', 'file_skp', 'file_persetujuan_mentor'
        ];

        $files = [];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $files[$field] = $request->file($field)->store('uploads/pendaftaran', 'public');
            }
        }

        // Simpan data Peserta (sama seperti sebelumnya)
        $peserta = Peserta::create([
            'nip_nrp' => $request->nip_nrp,
            'nama_lengkap' => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_rumah' => $request->alamat_rumah,
            'email_pribadi' => $request->email_pribadi,
            'nomor_hp' => $request->nomor_hp,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'bidang_studi' => $request->bidang_studi,
            'bidang_keahlian' => $request->bidang_keahlian,
            'status_perkawinan' => $request->status_perkawinan,
            'nama_pasangan' => $request->nama_pasangan,
            'olahraga_hobi' => $request->olahraga_hobi,
            'perokok' => $request->perokok,
            'ukuran_kaos' => $request->ukuran_kaos,
            'kondisi_peserta' => $request->kondisi_peserta,
            'file_ktp' => $files['file_ktp'] ?? null,
            'file_pas_foto' => $files['file_pas_foto'] ?? null,
            'status_aktif' => true,
        ]);

        // Simpan data KepegawaianPeserta (sama seperti sebelumnya)
        $kepegawaian = KepegawaianPeserta::create([
            'id_peserta' => $peserta->id_peserta,
            'asal_instansi' => $request->asal_instansi,
            'unit_kerja' => $request->unit_kerja,
            'id_provinsi' => $request->id_provinsi,
            'id_kabupaten_kota' => $request->id_kabupaten_kota,
            'alamat_kantor' => $request->alamat_kantor,
            'nomor_telepon_kantor' => $request->nomor_telepon_kantor,
            'email_kantor' => $request->email_kantor,
            'jabatan' => $request->jabatan,
            'eselon' => $request->eselon ?? null,
            'tanggal_sk_jabatan' => $request->tanggal_sk_jabatan ?? null,
            'file_sk_jabatan' => $files['file_sk_jabatan'] ?? null,
            'pangkat' => $request->pangkat ?? null,
            'golongan_ruang' => $request->golongan_ruang,
            'file_sk_pangkat' => $files['file_sk_pangkat'] ?? null,
            'nomor_sk_cpns' => $request->nomor_sk_cpns ?? null,
            'tanggal_sk_cpns' => $request->tanggal_sk_cpns ?? null,
            'file_sk_cpns' => $files['file_sk_cpns'] ?? null,
            'file_spmt' => $files['file_spmt'] ?? null,
            'file_skp' => $files['file_skp'] ?? null,
            'tahun_lulus_pkp_pim_iv' => $request->tahun_lulus_pkp_pim_iv ?? null,
        ]);

        // Simpan data Pendaftaran (sama seperti sebelumnya)
        $pendaftaran = Pendaftaran::create([
            'id_peserta' => $peserta->id_peserta,
            'id_jenis_pelatihan' => $request->id_jenis_pelatihan,
            'id_angkatan' => $request->id_angkatan,
            'file_surat_tugas' => $files['file_surat_tugas'] ?? null,
            'file_surat_kesediaan' => $files['file_surat_kesediaan'] ?? null,
            'file_pakta_integritas' => $files['file_pakta_integritas'] ?? null,
            'file_surat_komitmen' => $files['file_surat_komitmen'] ?? null,
            'file_surat_kelulusan_seleksi' => $files['file_surat_kelulusan_seleksi'] ?? null,
            'file_surat_sehat' => $files['file_surat_sehat'] ?? null,
            'file_surat_bebas_narkoba' => $files['file_surat_bebas_narkoba'] ?? null,
            'file_surat_pernyataan_administrasi' => $files['file_surat_pernyataan_administrasi'] ?? null,
            'file_sertifikat_penghargaan' => $files['file_sertifikat_penghargaan'] ?? null,
            'status_pendaftaran' => 'Draft',
            'tanggal_daftar' => now(),
        ]);

        // Simpan data PesertaMentor jika ada
        if ($request->id_mentor) {
            PesertaMentor::create([
                'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                'id_mentor' => $request->id_mentor,
                'tanggal_penunjukan' => now(),
                'status_mentoring' => 'Ditugaskan',
            ]);
        }

        return redirect()->route('pendaftaran.success')->with('success', 'Pendaftaran berhasil disimpan!');
    }

    /**
     * API untuk mendapatkan angkatan berdasarkan jenis pelatihan
     */
    public function apiAngkatan($id_jenis_pelatihan)
    {
        $angkatan = Angkatan::where('id_jenis_pelatihan', $id_jenis_pelatihan)
            ->where('status_angkatan', 'Dibuka')
            ->get();

        return response()->json($angkatan);
    }

    /**
     * API untuk mendapatkan kabupaten berdasarkan provinsi
     */
    public function apiKabupaten($id_provinsi)
    {
        $kabupaten = KabupatenKota::where('id_provinsi', $id_provinsi)->get();

        return response()->json($kabupaten);
    }

    public function apiProvinsi()
    {
        return response()->json(Provinsi::all());
    }
}