<?php

namespace App\Http\Controllers;

use App\Models\JenisPelatihan;
use App\Models\Angkatan;
use App\Models\Kabupaten;
use App\Models\Mentor;
use App\Models\Peserta;
use App\Models\KepegawaianPeserta;
use App\Models\Pendaftaran;
use App\Models\PesertaMentor;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan form pendaftaran (sekarang menjadi form verifikasi & update)
     */
    public function create()
    {
        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();

        return view('pendaftaran.create', compact('jenisPelatihan'));
    }

    /**
     * API untuk verifikasi NIP/NRP dan cek pendaftaran di pelatihan
     */
    public function verifyNip(Request $request)
    {
        try {
            $request->validate([
                'nip_nrp' => 'required|string|min:3',
                'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id'
            ]);

            // Cari peserta dengan semua relasi
            $peserta = Peserta::with(['kepegawaian.provinsi', 'kepegawaian.kabupaten'])
                ->where('nip_nrp', $request->nip_nrp)
                ->first();

            if (!$peserta) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIP/NRP tidak terdaftar sebagai peserta di sistem'
                ], 404);
            }

            if ($peserta->batasan == true) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIP/NRP ini sudah melakukan pengisian formulir'
                ], 403);
            }



            // Cari pendaftaran dengan relasi
            $existingPendaftaran = Pendaftaran::with('angkatan')
                ->where('id_peserta', $peserta->id)
                ->where('id_jenis_pelatihan', $request->id_jenis_pelatihan)
                ->first();

            if (!$existingPendaftaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta tidak terdaftar pada pelatihan yang dipilih. Hanya peserta yang sudah didaftarkan oleh admin yang dapat mengakses form ini.'
                ], 403);
            }

            // Format response dengan mengambil semua atribut
            $pesertaData = array_merge(
                $peserta->toArray(),
                ['kepegawaian' => $peserta->kepegawaian]
            );

            $pendaftaranData = array_merge(
                $existingPendaftaran->toArray(), // Mengambil semua atribut
                ['angkatan' => $existingPendaftaran->angkatan] // Menambahkan relasi
            );

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi berhasil. Peserta terdaftar pada pelatihan ini.',
                'peserta' => $pesertaData,
                'pendaftaran' => $pendaftaranData
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan partial form berdasarkan jenis pelatihan (dengan data yang sudah ada)
     */
    public function formPartial($type, Request $request)
    {
        try {
            // Decode peserta data dari request
            $pesertaData = json_decode($request->peserta_data, true);
            $pendaftaranData = json_decode($request->pendaftaran_data, true);

            if (!$pesertaData || !$pendaftaranData) {
                return response()->json(['error' => 'Data tidak valid'], 400);
            }

            if ($type === 'PKN_TK_II') {
                return view('partials.form-pkn-tk-ii', [
                    'peserta' => $pesertaData,
                    'pendaftaran' => $pendaftaranData
                ]);
            } elseif ($type === 'LATSAR') {
                return view('partials.form-latsar', [
                    'peserta' => $pesertaData,
                    'pendaftaran' => $pendaftaranData
                ]);
            } elseif ($type === 'PKA' || $type === 'PKP') {
                return view('partials.form-pka', [
                    'peserta' => $pesertaData,
                    'pendaftaran' => $pendaftaranData
                ]);
            }

            return response()->json(['error' => 'Form type not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menyimpan data pembaruan peserta (UPDATE data peserta yang sudah ada)
     */
    public function updateData(Request $request)
    {
        try {
            // 1. VALIDASI DASAR
            $validated = $request->validate([
                'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
                'peserta_id' => 'required|exists:peserta,id',
                'pendaftaran_id' => 'required|exists:pendaftaran,id',
                'nama_lengkap' => 'required|string|max:200',
                'nip_nrp' => 'required|string|max:50',
                'nama_panggilan' => 'nullable|string|max:100',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'alamat_rumah' => 'required|string',
                'email_pribadi' => 'required|email|max:100',
                'nomor_hp' => 'required|string|max:20',
                'pendidikan_terakhir' => 'required|in:SD,SMP,SMU,D3,D4,S1,S2,S3',
                'bidang_studi' => 'nullable|string|max:100',
                'bidang_keahlian' => 'nullable|string|max:100',
                'status_perkawinan' => 'nullable|in:Belum Menikah,Menikah,Duda,Janda',
                'nama_pasangan' => 'nullable|string|max:200',
                'olahraga_hobi' => 'nullable|string|max:100',
                'perokok' => 'required|in:Ya,Tidak',
                'ukuran_kaos' => 'nullable|in:S,M,L,XL,XXL,XXXL',
                'ukuran_celana' => 'nullable|in:S,M,L,XL,XXL,XXXL',
                'ukuran_training' => 'nullable|in:S,M,L,XL,XXL,XXXL',
                'kondisi_peserta' => 'nullable|string',
                'file_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
                'file_pas_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
                'asal_instansi' => 'required|string|max:200',
                'unit_kerja' => 'nullable|string|max:200',
                'id_provinsi' => 'required',
                'id_kabupaten_kota' => 'nullable',
                'alamat_kantor' => 'required|string',
                'nomor_telepon_kantor' => 'nullable|string|max:20',
                'email_kantor' => 'nullable|email|max:100',
                'jabatan' => 'required|string|max:200',
                'pangkat' => 'nullable|string|max:50',
                'golongan_ruang' => 'required|string|max:50',
                'eselon' => 'nullable|string|max:50',
                'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:1024',
                'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_tugas' => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_sehat' => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_bebas_narkoba' => 'nullable|file|mimes:pdf|max:1024',
                'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_pernyataan_administrasi' => 'nullable|file|mimes:pdf|max:1024',
                'file_sertifikat_penghargaan' => 'nullable|file|mimes:pdf|max:1024',
                'file_sk_cpns' => 'nullable|file|mimes:pdf|max:1024',
                'file_spmt' => 'nullable|file|mimes:pdf|max:1024',
                'file_skp' => 'nullable|file|mimes:pdf|max:1024',
                'file_persetujuan_mentor' => 'nullable|file|mimes:pdf|max:1024',
                'nomor_sk_cpns' => 'nullable|string|max:100',
                'nomor_sk_terakhir' => 'nullable|string|max:100',
                'tanggal_sk_cpns' => 'nullable|date',
                'tanggal_sk_jabatan' => 'nullable|date',
                'tahun_lulus_pkp_pim_iv' => 'nullable|integer',
                'nama_mentor' => 'nullable|string|max:200',
                'jabatan_mentor' => 'nullable|string|max:200',
                'nomor_rekening_mentor' => 'nullable|string|max:200',
                'npwp_mentor' => 'nullable|string|max:50',
                'has_mentor' => 'nullable|in:Ya,Tidak',
                'sudah_ada_mentor' => 'nullable|in:Ya,Tidak',
                'mentor_mode' => 'nullable|in:pilih,tambah',
                'id_mentor' => 'nullable|exists:mentor,id',
                'nama_mentor_baru' => 'nullable|string|max:200',
                'jabatan_mentor_baru' => 'nullable|string|max:200',
                'nomor_rekening_mentor_baru' => 'nullable|string|max:200',
                'npwp_mentor_baru' => 'nullable|string|max:50',
            ], [
                'nama_lengkap.required' => 'Nama lengkap wajib diisi',
                'nama_lengkap.max' => 'Nama lengkap maksimal 200 karakter',
                'nip_nrp.required' => 'NIP/NRP wajib diisi',
                'nip_nrp.max' => 'NIP/NRP maksimal 50 karakter',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
                'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
                'agama.required' => 'Agama wajib dipilih',
                'agama.in' => 'Agama yang dipilih tidak valid',
                'tempat_lahir.required' => 'Tempat lahir wajib diisi',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
                'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
                'alamat_rumah.required' => 'Alamat rumah wajib diisi',
                'email_pribadi.required' => 'Email pribadi wajib diisi',
                'email_pribadi.email' => 'Format email pribadi tidak valid',
                'nomor_hp.required' => 'Nomor HP wajib diisi',
                'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih',
                'pendidikan_terakhir.in' => 'Pendidikan terakhir tidak valid',
                'perokok.required' => 'Status perokok wajib dipilih',
                'perokok.in' => 'Status perokok tidak valid',
                'file_ktp.mimes' => 'File KTP harus berformat PDF, JPG, JPEG, atau PNG',
                'file_ktp.max' => 'Ukuran file KTP maksimal 1MB',
                'file_pas_foto.mimes' => 'File pas foto harus berformat JPG, JPEG, atau PNG',
                'file_pas_foto.max' => 'Ukuran file pas foto maksimal 1MB',
                'asal_instansi.required' => 'Asal instansi wajib diisi',
                'id_provinsi.required' => 'Provinsi wajib dipilih',
                'alamat_kantor.required' => 'Alamat kantor wajib diisi',
                'email_kantor.email' => 'Format email kantor tidak valid',
                'jabatan.required' => 'Jabatan wajib diisi',
                'golongan_ruang.required' => 'Golongan ruang wajib diisi',
                'file_sk_jabatan.mimes' => 'File SK Jabatan harus berformat PDF',
                'file_sk_jabatan.max' => 'Ukuran file SK Jabatan maksimal 1MB',
                'file_sk_pangkat.mimes' => 'File SK Pangkat harus berformat PDF',
                'file_sk_pangkat.max' => 'Ukuran file SK Pangkat maksimal 1MB',
                'file_surat_tugas.mimes' => 'File Surat Tugas harus berformat PDF',
                'file_surat_tugas.max' => 'Ukuran file Surat Tugas maksimal 1MB',
                'file_surat_sehat.mimes' => 'File Surat Sehat harus berformat PDF',
                'file_surat_sehat.max' => 'Ukuran file Surat Sehat maksimal 1MB',
                'file_surat_bebas_narkoba.mimes' => 'File Surat Bebas Narkoba harus berformat PDF',
                'file_surat_bebas_narkoba.max' => 'Ukuran file Surat Bebas Narkoba maksimal 1MB',
                'peserta_id.exists' => 'Data peserta tidak ditemukan',
                'pendaftaran_id.exists' => 'Data pendaftaran tidak ditemukan',
                'id_jenis_pelatihan.exists' => 'Jenis pelatihan tidak ditemukan',
                'id_mentor.exists' => 'Data mentor tidak ditemukan',
            ]);

            // 2. CEK KESESUAIAN DATA PESERTA DENGAN PENDAFTARAN
            $peserta = Peserta::find($request->peserta_id);
            if (!$peserta) {
                throw ValidationException::withMessages([
                    'peserta_id' => ['Data peserta tidak ditemukan di sistem']
                ]);
            }

            $pendaftaran = Pendaftaran::find($request->pendaftaran_id);
            if (!$pendaftaran) {
                throw ValidationException::withMessages([
                    'pendaftaran_id' => ['Data pendaftaran tidak ditemukan di sistem']
                ]);
            }

            // Verifikasi bahwa peserta benar-benar terdaftar pada pelatihan ini
            if (
                $pendaftaran->id_peserta != $peserta->id ||
                $pendaftaran->id_jenis_pelatihan != $request->id_jenis_pelatihan
            ) {
                throw ValidationException::withMessages([
                    'general' => ['Data tidak valid. Peserta tidak terdaftar pada pelatihan ini.']
                ]);
            }

            // 3. VALIDASI BERDASARKAN JENIS PELATIHAN
            $jenisPelatihan = JenisPelatihan::find($request->id_jenis_pelatihan);
            $kode = $jenisPelatihan->kode_pelatihan;
            $additionalRules = [];
            $additionalMessages = [];

            // Cek apakah file sudah ada di database
            $kepegawaian = $peserta->kepegawaian;

            if ($kode === 'PKN_TK_II') {
                $additionalRules = [
                    'eselon' => 'required|string|max:50',
                    'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:1024',
                    'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:1024',
                    'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_sehat' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_bebas_narkoba' => 'nullable|file|mimes:pdf|max:1024',
                ];

                if (!$kepegawaian->file_sk_jabatan) {
                    $additionalRules['file_sk_jabatan'] = 'required|file|mimes:pdf|max:1024';
                }

                if (!$kepegawaian->file_sk_pangkat) {
                    $additionalRules['file_sk_pangkat'] = 'required|file|mimes:pdf|max:1024';
                }

                if (!$pendaftaran->file_pakta_integritas) {
                    $additionalRules['file_pakta_integritas'] = 'required|file|mimes:pdf|max:1024';
                }

                if (!$pendaftaran->file_surat_sehat) {
                    $additionalRules['file_surat_sehat'] = 'required|file|mimes:pdf|max:1024';
                }

                if (!$pendaftaran->file_surat_bebas_narkoba) {
                    $additionalRules['file_surat_bebas_narkoba'] = 'required|file|mimes:pdf|max:1024';
                }

                $additionalMessages = [
                    'eselon.required' => 'Eselon wajib diisi untuk pelatihan PKN TK II',
                    'eselon.string' => 'Eselon harus berupa teks',
                    'eselon.max' => 'Eselon maksimal 50 karakter',
                    'file_pakta_integritas.required' => 'File Pakta Integritas wajib diunggah',
                    'file_pakta_integritas.file' => 'File Pakta Integritas harus berupa file',
                    'file_pakta_integritas.mimes' => 'File Pakta Integritas harus berformat PDF',
                    'file_pakta_integritas.max' => 'File Pakta Integritas maksimal 1MB',
                    'file_surat_kelulusan_seleksi.file' => 'File Surat Kelulusan Seleksi harus berupa file',
                    'file_surat_kelulusan_seleksi.mimes' => 'File Surat Kelulusan Seleksi harus berformat PDF',
                    'file_surat_kelulusan_seleksi.max' => 'File Surat Kelulusan Seleksi maksimal 1MB',
                    'file_sk_jabatan.required' => 'File SK Jabatan wajib diunggah',
                    'file_sk_jabatan.file' => 'File SK Jabatan harus berupa file',
                    'file_sk_jabatan.mimes' => 'File SK Jabatan harus berformat PDF',
                    'file_sk_jabatan.max' => 'File SK Jabatan maksimal 1MB',
                    'file_sk_pangkat.required' => 'File SK Pangkat wajib diunggah',
                    'file_sk_pangkat.file' => 'File SK Pangkat harus berupa file',
                    'file_sk_pangkat.mimes' => 'File SK Pangkat harus berformat PDF',
                    'file_sk_pangkat.max' => 'File SK Pangkat maksimal 1MB',
                    'file_surat_komitmen.file' => 'File Surat Komitmen harus berupa file',
                    'file_surat_komitmen.mimes' => 'File Surat Komitmen harus berformat PDF',
                    'file_surat_komitmen.max' => 'File Surat Komitmen maksimal 1MB',
                    'file_surat_sehat.required' => 'File Surat Sehat wajib diunggah',
                    'file_surat_sehat.file' => 'File Surat Sehat harus berupa file',
                    'file_surat_sehat.mimes' => 'File Surat Sehat harus berformat PDF',
                    'file_surat_sehat.max' => 'File Surat Sehat maksimal 1MB',
                    'file_surat_bebas_narkoba.required' => 'File Surat Bebas Narkoba wajib diunggah',
                    'file_surat_bebas_narkoba.file' => 'File Surat Bebas Narkoba harus berupa file',
                    'file_surat_bebas_narkoba.mimes' => 'File Surat Bebas Narkoba harus berformat PDF',
                    'file_surat_bebas_narkoba.max' => 'File Surat Bebas Narkoba maksimal 1MB',
                ];
            }

            if ($kode === 'LATSAR') {
                $additionalRules = [
                    'nomor_sk_cpns' => 'required|string|max:100',
                    'tanggal_sk_cpns' => 'required|date',
                    'pangkat' => 'required|string|max:50',
                    'sudah_ada_mentor' => 'required|in:Ya,Tidak',
                    'nomor_rekening_mentor' => 'nullable|string|max:200',
                    'npwp_mentor' => 'nullable|string|max:50',
                ];

                if (!$kepegawaian || !$kepegawaian->file_sk_cpns) {
                    $additionalRules['file_sk_cpns'] = 'required|file|mimes:pdf|max:1024';
                }

                if (!$kepegawaian || !$kepegawaian->file_spmt) {
                    $additionalRules['file_spmt'] = 'required|file|mimes:pdf|max:1024';
                }

                if (!$pendaftaran || !$pendaftaran->file_surat_kesediaan) {
                    $additionalRules['file_surat_kesediaan'] = 'required|file|mimes:pdf|max:1024';
                }

                if (!$peserta->file_ktp) {
                    $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:1024';
                }


                $additionalMessages = [
                    'nomor_sk_cpns.required' => 'Nomor SK CPNS wajib diisi untuk pelatihan LATSAR',
                    'nomor_sk_cpns.max' => 'Nomor SK CPNS maksimal 100 karakter',
                    'tanggal_sk_cpns.required' => 'Tanggal SK CPNS wajib diisi untuk pelatihan LATSAR',
                    'tanggal_sk_cpns.date' => 'Format tanggal SK CPNS tidak valid',
                    'file_sk_cpns.required' => 'File SK CPNS wajib diupload',
                    'file_sk_cpns.file' => 'File SK CPNS harus berupa file',
                    'file_sk_cpns.mimes' => 'File SK CPNS harus berformat PDF',
                    'file_sk_cpns.max' => 'File SK CPNS maksimal 1MB',
                    'file_spmt.required' => 'File SPMT wajib diupload',
                    'file_spmt.file' => 'File SPMT harus berupa file',
                    'file_spmt.mimes' => 'File SPMT harus berformat PDF',
                    'file_spmt.max' => 'File SPMT maksimal 1MB',
                    'file_surat_kesediaan.required' => 'File Surat Kesediaan wajib diupload',
                    'file_surat_kesediaan.file' => 'File Surat Kesediaan harus berupa file',
                    'file_surat_kesediaan.mimes' => 'File Surat Kesediaan harus berformat PDF',
                    'file_surat_kesediaan.max' => 'File Surat Kesediaan maksimal 1MB',
                    'pangkat.required' => 'Pangkat wajib diisi untuk pelatihan LATSAR',
                    'pangkat.max' => 'Pangkat maksimal 50 karakter',
                    'sudah_ada_mentor.required' => 'Status mentor wajib dipilih untuk pelatihan LATSAR',
                    'sudah_ada_mentor.in' => 'Status mentor tidak valid (Ya/Tidak)',
                    'nomor_rekening_mentor.max' => 'Nomor rekening mentor maksimal 200 karakter',
                    'npwp_mentor.max' => 'NPWP mentor maksimal 50 karakter',
                    'file_ktp.required' => 'File KTP wajib diupload',
                    'file_ktp.file' => 'File KTP harus berupa file',
                    'file_ktp.mimes' => 'File KTP harus berformat PDF, JPG, JPEG, atau PNG',
                    'file_ktp.max' => 'File KTP maksimal 1MB',
                ];
            }

            if ($kode === 'PKA' || $kode === 'PKP') {
                $additionalRules = [
                    'eselon' => 'required|string|max:50',
                    'tanggal_sk_jabatan' => 'required|date',
                    'tahun_lulus_pkp_pim_iv' => 'nullable|integer',
                    'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:1024',
                    'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_pernyataan_administrasi' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:1024',
                    'file_persetujuan_mentor' => 'nullable|file|mimes:pdf|max:1024',
                    'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
                    'sudah_ada_mentor' => 'required|in:Ya,Tidak',
                    'nomor_sk_terakhir' => 'required|string|max:100',
                    'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:1024',
                    'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_tugas' => 'nullable|file|mimes:pdf|max:1024',
                    'file_pas_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',

                ];

                if (!$pendaftaran->file_surat_kesediaan) {
                    $additionalRules['file_surat_kesediaan'] = 'required|file|mimes:pdf|max:1024';
                }
                if (!$peserta->file_ktp) {
                    $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:1024';
                }
                if (!$peserta->file_pas_foto) {
                    $additionalRules['file_pas_foto'] = 'required|file|mimes:jpg,jpeg,png|max:1024';
                }
                if (!$kepegawaian->file_sk_jabatan) {
                    $additionalRules['file_sk_jabatan'] = 'required|file|mimes:pdf|max:1024';
                }
                if (!$kepegawaian->file_sk_pangkat) {
                    $additionalRules['file_sk_pangkat'] = 'required|file|mimes:pdf|max:1024';
                }
                if (!$pendaftaran->file_pakta_integritas) {
                    $additionalRules['file_pakta_integritas'] = 'required|file|mimes:pdf|max:1024';
                }
                if (!$pendaftaran->file_surat_tugas) {
                    $additionalRules['file_surat_tugas'] = 'required|file|mimes:pdf|max:1024';
                }

                $additionalMessages = [
                    // Validasi untuk eselon
                    'eselon.required' => 'Eselon wajib diisi untuk pelatihan ' . $kode,
                    'eselon.string' => 'Eselon harus berupa teks',
                    'eselon.max' => 'Eselon maksimal 50 karakter',

                    // Validasi untuk tanggal SK jabatan
                    'tanggal_sk_jabatan.required' => 'Tanggal SK Jabatan wajib diisi untuk pelatihan ' . $kode,
                    'tanggal_sk_jabatan.date' => 'Format tanggal SK Jabatan tidak valid',

                    // Validasi untuk tahun lulus PKP/PIM IV
                    'tahun_lulus_pkp_pim_iv.integer' => 'Tahun lulus PKP/PIM IV harus berupa angka',

                    // Validasi untuk status mentor
                    'sudah_ada_mentor.required' => 'Status mentor wajib dipilih untuk pelatihan ' . $kode,
                    'sudah_ada_mentor.in' => 'Status mentor tidak valid (Ya/Tidak)',

                    // Validasi untuk nomor SK terakhir
                    'nomor_sk_terakhir.required' => 'Nomor SK Jabatan Terakhir wajib diisi untuk pelatihan ' . $kode,
                    'nomor_sk_terakhir.string' => 'Nomor SK Jabatan Terakhir harus berupa teks',
                    'nomor_sk_terakhir.max' => 'Nomor SK Jabatan Terakhir maksimal 100 karakter',

                    // Validasi untuk file SK jabatan
                    'file_sk_jabatan.required' => 'File SK Jabatan Terakhir wajib diunggah untuk pelatihan ' . $kode,
                    'file_sk_jabatan.file' => 'File SK Jabatan harus berupa file',
                    'file_sk_jabatan.mimes' => 'File SK Jabatan harus berformat PDF',
                    'file_sk_jabatan.max' => 'Ukuran file SK Jabatan maksimal 1MB',

                    // Validasi untuk file SK pangkat
                    'file_sk_pangkat.required' => 'File SK Pangkat/Golongan Ruang wajib diunggah untuk pelatihan ' . $kode,
                    'file_sk_pangkat.file' => 'File SK Pangkat harus berupa file',
                    'file_sk_pangkat.mimes' => 'File SK Pangkat harus berformat PDF',
                    'file_sk_pangkat.max' => 'Ukuran file SK Pangkat maksimal 1MB',

                    // Validasi untuk file KTP
                    'file_ktp.required' => 'Foto KTP wajib diunggah untuk pelatihan ' . $kode,
                    'file_ktp.file' => 'File KTP harus berupa file',
                    'file_ktp.mimes' => 'File KTP harus berformat PDF, JPG, JPEG, atau PNG',
                    'file_ktp.max' => 'Ukuran file KTP maksimal 1MB',

                    // Validasi untuk file surat kesediaan
                    'file_surat_kesediaan.required' => 'File Formulir Kesediaan wajib diunggah untuk pelatihan ' . $kode,
                    'file_surat_kesediaan.file' => 'File Formulir Kesediaan harus berupa file',
                    'file_surat_kesediaan.mimes' => 'File Formulir Kesediaan harus berformat PDF',
                    'file_surat_kesediaan.max' => 'Ukuran file Formulir Kesediaan maksimal 1MB',

                    // Validasi untuk file pakta integritas
                    'file_pakta_integritas.required' => 'File Pakta Integritas wajib diunggah untuk pelatihan ' . $kode,
                    'file_pakta_integritas.file' => 'File Pakta Integritas harus berupa file',
                    'file_pakta_integritas.mimes' => 'File Pakta Integritas harus berformat PDF',
                    'file_pakta_integritas.max' => 'Ukuran file Pakta Integritas maksimal 1MB',

                    // Validasi untuk file surat tugas
                    'file_surat_tugas.required' => 'File Surat Tugas wajib diunggah untuk pelatihan ' . $kode,
                    'file_surat_tugas.file' => 'File Surat Tugas harus berupa file',
                    'file_surat_tugas.mimes' => 'File Surat Tugas harus berformat PDF',
                    'file_surat_tugas.max' => 'Ukuran file Surat Tugas maksimal 1MB',

                    // Validasi untuk file surat pernyataan administrasi
                    'file_surat_pernyataan_administrasi.file' => 'File Surat Pernyataan Administrasi harus berupa file',
                    'file_surat_pernyataan_administrasi.mimes' => 'File Surat Pernyataan Administrasi harus berformat PDF',
                    'file_surat_pernyataan_administrasi.max' => 'Ukuran file Surat Pernyataan Administrasi maksimal 1MB',

                    // Validasi untuk file surat kelulusan seleksi
                    'file_surat_kelulusan_seleksi.file' => 'File Kelulusan Seleksi/Sertifikat harus berupa file',
                    'file_surat_kelulusan_seleksi.mimes' => 'File Kelulusan Seleksi/Sertifikat harus berformat PDF',
                    'file_surat_kelulusan_seleksi.max' => 'Ukuran file Kelulusan Seleksi/Sertifikat maksimal 1MB',

                    // Validasi untuk file persetujuan mentor
                    'file_persetujuan_mentor.file' => 'File Persetujuan Mentor harus berupa file',
                    'file_persetujuan_mentor.mimes' => 'File Persetujuan Mentor harus berformat PDF',
                    'file_persetujuan_mentor.max' => 'Ukuran file Persetujuan Mentor maksimal 1MB',

                    // Validasi untuk file pas foto
                    'file_pas_foto.required' => 'File Pas Foto wajib diunggah ',
                    'file_pas_foto.file' => 'File Pas Foto harus berupa file',
                    'file_pas_foto.mimes' => 'File Pas Foto harus berformat JPG, JPEG, atau PNG',
                    'file_pas_foto.max' => 'Ukuran file Pas Foto maksimal 1MB',
                ];
            }

            // Validasi mentor jika sudah ada mentor
            if ($request->sudah_ada_mentor === 'Ya') {
                $additionalRules['mentor_mode'] = 'required|in:pilih,tambah';
                $additionalMessages['mentor_mode.required'] = 'Pilih mode mentor (Pilih dari daftar atau Tambah baru)';
                $additionalMessages['mentor_mode.in'] = 'Mode mentor tidak valid';

                if ($request->mentor_mode === 'pilih') {
                    $additionalRules['id_mentor'] = 'required|exists:mentor,id';
                    $additionalMessages['id_mentor.required'] = 'Pilih mentor dari daftar';
                } elseif ($request->mentor_mode === 'tambah') {
                    $additionalRules['nama_mentor_baru'] = 'required|string|max:200';
                    $additionalRules['jabatan_mentor_baru'] = 'required|string|max:200';
                    $additionalMessages['nama_mentor_baru.required'] = 'Nama mentor baru wajib diisi';
                    $additionalMessages['jabatan_mentor_baru.required'] = 'Jabatan mentor baru wajib diisi';
                }
            }

            // Jalankan validasi tambahan
            if (!empty($additionalRules)) {
                $request->validate($additionalRules, $additionalMessages);
            }

            // 4. SIMPAN FILE UPLOADS DENGAN STRUKTUR FOLDER: Berkas/Tahun/JenisPelatihan/Angkatan/NIP
            $fileFields = [
                'file_ktp',
                'file_pas_foto',
                'file_sk_jabatan',
                'file_sk_pangkat',
                'file_surat_tugas',
                'file_surat_kesediaan',
                'file_pakta_integritas',
                'file_surat_komitmen',
                'file_surat_kelulusan_seleksi',
                'file_surat_sehat',
                'file_surat_bebas_narkoba',
                'file_surat_pernyataan_administrasi',
                'file_sertifikat_penghargaan',
                'file_sk_cpns',
                'file_spmt',
                'file_skp',
                'file_persetujuan_mentor'
            ];

            // Ambil data untuk struktur folder
            $tahun = date('Y');
            $folderPath = null;
            $fullPath = null;

            // Ambil data angkatan dari pendaftaran
            $angkatan = null;
            if ($pendaftaran && $pendaftaran->angkatan) {
                $angkatan = $pendaftaran->angkatan;
            }

            if ($jenisPelatihan && $angkatan) {
                $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
                $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan);
                $nip = $request->nip_nrp;

                // Buat struktur folder: Berkas/Tahun/JenisPelatihan/Angkatan/NIP
                $folderPath = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";
                // $fullPath = public_path($folderPath);

                // // Buat folder jika belum ada
                // if (!file_exists($fullPath)) {
                //     mkdir($fullPath, 0755, true);
                // }
            }

            $files = [];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    try {

                        if (!$folderPath) {
                            $folderPath = "Berkas/{$tahun}/default/{$request->nip_nrp}";
                        }

                        $extension = $request->file($field)->getClientOriginalExtension();
                        $fieldName = str_replace('file_', '', $field);
                        $fileName = $fieldName . '.' . $extension;

                        // PATH DI GOOGLE DRIVE
                        $drivePath = "{$folderPath}/{$fileName}";

                        // 🔥 HAPUS FILE LAMA DI DRIVE
                        $this->deleteOldFile($peserta, $pendaftaran, $kepegawaian, $field);

                        // 🔥 UPLOAD KE GOOGLE DRIVE
                        Storage::disk('google')->put(
                            $drivePath,
                            file_get_contents($request->file($field))
                        );

                        // SIMPAN PATH RELATIF KE DB
                        $files[$field] = $drivePath;
                    } catch (\Exception $e) {
                        throw ValidationException::withMessages([
                            $field => ['Gagal upload file ke Google Drive: ' . $e->getMessage()]
                        ]);
                    }
                }
            }


            // $files = [];
            // foreach ($fileFields as $field) {
            //     if ($request->hasFile($field)) {
            //         try {
            //             // Jika tidak ada data lengkap untuk struktur folder, gunakan folder default
            //             if (!$fullPath) {
            //                 $folderPath = "Berkas/{$tahun}/default/{$request->nip_nrp}";
            //                 $fullPath = public_path($folderPath);

            //                 if (!file_exists($fullPath)) {
            //                     mkdir($fullPath, 0755, true);
            //                 }
            //             }

            //             // Ambil ekstensi file
            //             $extension = $request->file($field)->getClientOriginalExtension();

            //             // Buat nama file yang lebih deskriptif (hilangkan prefix 'file_')
            //             $fieldName = str_replace('file_', '', $field);
            //             $fileName = $fieldName . '.' . $extension;

            //             // Pindahkan file ke folder yang sudah ditentukan
            //             $request->file($field)->move($fullPath, $fileName);

            //             // Simpan path relatif untuk database (DENGAN SLASH DI AWAL)
            //             $files[$field] = '/' . $folderPath . '/' . $fileName;

            //             // Hapus file lama jika ada
            //             $this->deleteOldFile($peserta, $pendaftaran, $kepegawaian, $field);
            //         } catch (\Exception $e) {
            //             throw ValidationException::withMessages([
            //                 $field => ['Gagal mengupload file: ' . $e->getMessage()]
            //             ]);
            //         }
            //     }
            // }

            // 5. UPDATE DATA PESERTA
            $pesertaUpdateData = [
                'nama_lengkap' => $request->nama_lengkap,
                'nip_nrp' => $request->nip_nrp,
                'nama_panggilan' => $request->nama_panggilan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'alamat_rumah' => $request->alamat_rumah,
                'email_pribadi' => $request->email_pribadi,
                'nomor_hp' => $request->nomor_hp,
                'bidang_studi' => $request->bidang_studi,
                'bidang_keahlian' => $request->bidang_keahlian,
                'status_perkawinan' => $request->status_perkawinan,
                'nama_pasangan' => $request->nama_pasangan,
                'olahraga_hobi' => $request->olahraga_hobi,
                'perokok' => $request->perokok,
                'ukuran_kaos' => $request->ukuran_kaos,
                'ukuran_celana' => $request->ukuran_celana,
                'ukuran_training' => $request->ukuran_training,
                'kondisi_peserta' => $request->kondisi_peserta,
            ];

            // Tambahkan file KTP dan pas foto jika ada
            if (isset($files['file_ktp'])) {
                $pesertaUpdateData['file_ktp'] = $files['file_ktp'];
            }

            if (isset($files['file_pas_foto'])) {
                $pesertaUpdateData['file_pas_foto'] = $files['file_pas_foto'];
            }

            $peserta->update($pesertaUpdateData);

            // 6. UPDATE KEPEGAWAIAN PESERTA
            $provinsi = Provinsi::where('id', $request->id_provinsi)->first();
            $kabupaten = $request->id_kabupaten_kota ?
                Kabupaten::where('id', $request->id_kabupaten_kota)->first() :
                null;

            if (!$provinsi) {
                throw ValidationException::withMessages([
                    'id_provinsi' => ['Provinsi yang dipilih tidak ditemukan di database']
                ]);
            }

            // Persiapkan data update untuk kepegawaian
            $kepegawaianUpdateData = [
                'asal_instansi' => $request->asal_instansi,
                'unit_kerja' => $request->unit_kerja,
                'id_provinsi' => $provinsi->id,
                'id_kabupaten_kota' => $kabupaten?->id,
                'alamat_kantor' => $request->alamat_kantor,
                'nomor_telepon_kantor' => $request->nomor_telepon_kantor,
                'email_kantor' => $request->email_kantor,
                'jabatan' => $request->jabatan,
                'pangkat' => $request->pangkat,
                'golongan_ruang' => $request->golongan_ruang,
                'eselon' => $request->eselon,
                'tanggal_sk_jabatan' => $request->tanggal_sk_jabatan,
                'nomor_sk_cpns' => $request->nomor_sk_cpns,
                'tanggal_sk_cpns' => $request->tanggal_sk_cpns,
                'tahun_lulus_pkp_pim_iv' => $request->tahun_lulus_pkp_pim_iv,
                'nomor_sk_terakhir' => $request->nomor_sk_terakhir,
            ];

            // Tambahkan file-field hanya jika ada file baru diupload
            $kepegawaianFileFields = [
                'file_sk_jabatan',
                'file_sk_pangkat',
                'file_sk_cpns',
                'file_spmt',
                'file_skp',
            ];

            foreach ($kepegawaianFileFields as $field) {
                if (isset($files[$field])) {
                    $kepegawaianUpdateData[$field] = $files[$field];
                }
            }

            KepegawaianPeserta::updateOrCreate(
                ['id_peserta' => $peserta->id],
                $kepegawaianUpdateData
            );

            // 7. UPDATE DOKUMEN PENDAFTARAN
            $pendaftaranUpdateData = [];

            $pendaftaranFileFields = [
                'file_surat_tugas',
                'file_surat_kesediaan',
                'file_pakta_integritas',
                'file_surat_komitmen',
                'file_surat_kelulusan_seleksi',
                'file_surat_sehat',
                'file_surat_bebas_narkoba',
                'file_surat_pernyataan_administrasi',
                'file_sertifikat_penghargaan',
                'file_persetujuan_mentor'
            ];

            foreach ($pendaftaranFileFields as $field) {
                if (isset($files[$field])) {
                    $pendaftaranUpdateData[$field] = $files[$field];
                }
            }

            // Update pendaftaran hanya jika ada data baru
            if (!empty($pendaftaranUpdateData)) {
                $pendaftaran->update($pendaftaranUpdateData);
            }

            // 8. SIMPAN MENTOR JIKA ADA
            if ($request->sudah_ada_mentor === 'Ya') {
                $mentor = null;

                if ($request->mentor_mode === 'pilih' && $request->id_mentor) {
                    $mentor = Mentor::find($request->id_mentor);
                } elseif ($request->mentor_mode === 'tambah') {
                    $mentor = Mentor::create([
                        'nama_mentor' => $request->nama_mentor_baru,
                        'jabatan_mentor' => $request->jabatan_mentor_baru,
                        'nomor_rekening' => $request->nomor_rekening_mentor_baru,
                        'npwp_mentor' => $request->npwp_mentor_baru,
                        'status_aktif' => true,
                    ]);
                }

                if ($mentor) {
                    PesertaMentor::updateOrCreate(
                        ['id_pendaftaran' => $pendaftaran->id],
                        [
                            'id_mentor' => $mentor->id,
                            'tanggal_penunjukan' => now(),
                            'status_mentoring' => 'Ditugaskan',
                        ]
                    );
                }
            }

            $peserta->update([
                'batasan' => true
            ]);

            // 9. RESPONSE
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data peserta berhasil diperbarui!',
                    'pendaftaran_id' => $pendaftaran->id,
                    'redirect_url' => route('pendaftaran.success')
                ], 200);
            }

            session(['pendaftaran_id' => $pendaftaran->id]);

            return redirect()->route('pendaftaran.success')
                ->with('success', 'Data peserta berhasil diperbarui!')
                ->with('pendaftaran_id', $pendaftaran->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal. Mohon periksa kembali data yang Anda masukkan.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Mohon periksa kembali data yang Anda masukkan.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.'
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.')
                ->withInput();
        }
    }

    // FUNGSI HELPER UNTUK MENGHAPUS FILE LAMA
    private function deleteOldFile($peserta, $pendaftaran, $kepegawaian, $field)
    {
        try {
            $oldFilePath = null;

            // Peserta
            if (in_array($field, ['file_ktp', 'file_pas_foto']) && $peserta->$field) {
                $oldFilePath = $peserta->$field;
            }
            // Kepegawaian
            elseif (
                in_array($field, ['file_sk_jabatan', 'file_sk_pangkat', 'file_sk_cpns', 'file_spmt', 'file_skp'])
                && $kepegawaian && $kepegawaian->$field
            ) {
                $oldFilePath = $kepegawaian->$field;
            }
            // Pendaftaran
            elseif ($pendaftaran && $pendaftaran->$field) {
                $oldFilePath = $pendaftaran->$field;
            }

            if ($oldFilePath && Storage::disk('google')->exists($oldFilePath)) {
                Storage::disk('google')->delete($oldFilePath);
            }
        } catch (\Exception $e) {
            // optional log
            // \Log::error('Gagal hapus file Drive: ' . $e->getMessage());
        }
    }

    
    /**
     * Success page setelah pembaruan data
     */
    public function success(Request $request)
    {
        $pendaftaran_id = $request->session()->get('pendaftaran_id') ?? $request->get('id');

        if ($pendaftaran_id) {
            $pendaftaran = Pendaftaran::with(['peserta', 'jenisPelatihan', 'angkatan'])
                ->where('id', $pendaftaran_id)
                ->firstOrFail();
        } else {
            return redirect()->route('home')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        return view('pendaftaran.success', compact('pendaftaran'));
    }

    /**
     * API untuk mendapatkan daftar mentor
     */
    public function getMentors()
    {
        $mentors = Mentor::where('status_aktif', true)->get();

        return response()->json($mentors);
    }

    /**
     * API untuk mendapatkan daftar provinsi
     */
    public function getProvinces()
    {
        try {
            // HAPUS kondisi where('active', true) karena kolom tidak ada
            $provinces = Provinsi::orderBy('name')
                ->get(['id', 'code', 'name']);

            return response()->json([
                'success' => true,
                'data' => $provinces,
                'message' => 'Data provinsi berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data provinsi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan daftar kabupaten/kota berdasarkan provinsi
     */
    public function getRegencies($provinceId)
    {
        try {
            // Cari provinsi berdasarkan ID (tanpa mencari by code karena id sudah pasti)
            $provinsi = Provinsi::find($provinceId);

            if (!$provinsi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provinsi tidak ditemukan'
                ], 404);
            }

            // CARA 1: Jika kabupaten menggunakan province_id (sesuai struktur tabel kabupatens)
            $regencies = Kabupaten::where('province_id', $provinsi->id)
                ->orderBy('name')
                ->get(['id', 'code', 'name', 'province_id']);

            return response()->json([
                'success' => true,
                'data' => $regencies,
                'message' => 'Data kabupaten/kota berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kabupaten/kota: ' . $e->getMessage()
            ], 500);
        }
    }
}
