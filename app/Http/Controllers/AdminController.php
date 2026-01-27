<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Aktifitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peserta;
use App\Models\KepegawaianPeserta;
use App\Models\Mentor;
use App\Models\Pendaftaran;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;


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
        $kunci_judul = false;

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

                $kunci_judul = $pendaftaranTerbaru->angkatan->kunci_edit ?? false;
                

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
            'semuaPendaftaran',
            'kunci_judul'
        ));
    }


    public function editData(Request $request)
    {
        try {
            $user = Auth::user();

            // Ambil data peserta dengan relasi terkait
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
                    }
                ])
                ->first();

            if (!$peserta) {
                return redirect()->route('dashboard')
                    ->with('error', 'Data peserta tidak ditemukan.');
            }

            // Ambil data terkait
            $kepegawaian = $peserta->kepegawaian;
            $pendaftaranTerbaru = $peserta->pendaftaran->first();
            $jenisPelatihanData = $pendaftaranTerbaru->jenisPelatihan;

            // Dapatkan list provinsi dan SEMUA kabupaten
            $provinsiList = Provinsi::orderBy('name')->get();
            $kabupatenList = Kabupaten::orderBy('name')->get(); // Ambil semua kabupaten

            // Dapatkan list mentor
            $mentorList = Mentor::where('status_aktif', true)->orderBy('nama_mentor')->get();

            return view('admin.edit', compact(
                'peserta',
                'kepegawaian',
                'pendaftaranTerbaru',
                'provinsiList',
                'kabupatenList', // Kirim semua kabupaten
                'mentorList',
                'jenisPelatihanData'
            ));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateData(Request $request)
    {
        try {
            $user = Auth::user();
            $peserta = Peserta::where('id', $user->peserta_id)->first();

            if (!$peserta) {
                throw ValidationException::withMessages([
                    'general' => ['Data peserta tidak ditemukan']
                ]);
            }

            $pendaftaranTerbaru = $peserta->pendaftaran()->first();
            $kepegawaian = $peserta->kepegawaian;

            // 1. VALIDASI DASAR
            $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:200',
                'nip_nrp' => 'required|string|max:50',
                'nama_panggilan' => 'nullable|string|max:100',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Kristen Protestan',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'alamat_rumah' => 'required|string',
                'email_pribadi' => 'required|email|max:100',
                'nomor_hp' => 'required|string|max:20',
                'pendidikan_terakhir' => 'required|in:SD,SMP,SMU,D3,D4,S1,S2,S3',
                'bidang_studi' => 'required|string|max:100',
                'bidang_keahlian' => 'nullable|string|max:100',
                'status_perkawinan' => 'required|in:Belum Menikah,Menikah,Duda,Janda',
                'olahraga_hobi' => 'nullable|string|max:100',
                'perokok' => 'required|in:Ya,Tidak',
                'ukuran_kaos' => 'required|in:S,M,L,XL,XXL,XXXL',
                'ukuran_celana' => 'required|in:S,M,L,XL,XXL,XXXL',
                'ukuran_training' => 'required|in:S,M,L,XL,XXL,XXXL',
                'kondisi_peserta' => 'nullable|string',

                // FILE VALIDATION - ubah menjadi nullable karena bisa data URL
                'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'file_pas_foto' => 'nullable', // Bisa data URL atau file

                // Data Kepegawaian
                'asal_instansi' => 'required|string|max:200',
                'unit_kerja' => 'required|string|max:200',
                'id_provinsi' => 'required',
                'id_kabupaten_kota' => 'required',
                'alamat_kantor' => 'required|string',
                'nomor_telepon_kantor' => 'nullable|string|max:20',
                'email_kantor' => 'nullable|email|max:100',
                'jabatan' => 'required|string|max:200',
                'pangkat' => 'nullable|string|max:50',
                'golongan_ruang' => 'required|string|max:10',
                'eselon' => 'nullable|string|max:50',

                // File Kepegawaian
                'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:5120',
                'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:5120',
                'file_sk_cpns' => 'nullable|file|mimes:pdf|max:5120',
                'file_spmt' => 'nullable|file|mimes:pdf|max:5120',
                'file_skp' => 'nullable|file|mimes:pdf|max:5120',

                // File Pendaftaran
                'file_surat_tugas' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_sehat' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_bebas_narkoba' => 'nullable|file|mimes:pdf|max:5120',
                'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:5120',

                // Data SK
                'nomor_sk_cpns' => 'nullable|string|max:100',
                'nomor_sk_terakhir' => 'nullable|string|max:100',
                'tanggal_sk_cpns' => 'nullable|date',
                'tanggal_sk_jabatan' => 'nullable|date',
                'tahun_lulus_pkp_pim_iv' => 'nullable|integer',

                // Mentor
                'nama_mentor' => 'nullable|string|max:200',
                'jabatan_mentor' => 'nullable|string|max:200',
                'nomor_rekening_mentor' => 'nullable|string|max:200',
                'npwp_mentor' => 'nullable|string|max:50',
                'sudah_ada_mentor' => 'nullable|in:Ya,Tidak',
                'mentor_mode' => 'nullable|in:pilih,tambah',
                'id_mentor' => 'nullable|exists:mentor,id',
                'nama_mentor_baru' => 'nullable|string|max:200',
                'jabatan_mentor_baru' => 'nullable|string|max:200',
                'nomor_rekening_mentor_baru' => 'nullable|string|max:200',
                'npwp_mentor_baru' => 'nullable|string|max:50',
            ], [
                'nama_lengkap.required' => 'Nama lengkap wajib diisi',
                'nip_nrp.required' => 'NIP/NRP wajib diisi',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
                'agama.required' => 'Agama wajib dipilih',
                'tempat_lahir.required' => 'Tempat lahir wajib diisi',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
                'alamat_rumah.required' => 'Alamat rumah wajib diisi',
                'email_pribadi.required' => 'Email pribadi wajib diisi',
                'nomor_hp.required' => 'Nomor HP wajib diisi',
                'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih',
                'perokok.required' => 'Status perokok wajib dipilih',
                'asal_instansi.required' => 'Asal instansi wajib diisi',
                'id_provinsi.required' => 'Provinsi wajib dipilih',
                'alamat_kantor.required' => 'Alamat kantor wajib diisi',
                'jabatan.required' => 'Jabatan wajib diisi',
                'golongan_ruang.required' => 'Golongan ruang wajib diisi',
                'golongan_ruang.max' => 'Golongan ruang maksimal 10 karakter',
                'ukuran_kaos.required' => 'Ukuran kaos wajib dipilih',
                'ukuran_celana.required' => 'Ukuran celana wajib dipilih',
                'ukuran_training.required' => 'Ukuran training wajib dipilih',
                'bidang_studi.required' => 'Bidang studi wajib diisi',
                'status_perkawinan.required' => 'Status perkawinan wajib dipilih',
                'id_kabupaten_kota.required' => 'Kabupaten/Kota wajib dipilih',
                'unit_kerja.required' => 'Unit kerja wajib diisi',
            ]);

            // 2. VALIDASI KONDISIONAL BERDASARKAN STATUS FILE
            $additionalRules = [];
            $additionalMessages = [];

            if ($pendaftaranTerbaru && $pendaftaranTerbaru->jenisPelatihan) {
                $kode = $pendaftaranTerbaru->jenisPelatihan->kode_pelatihan;

                if ($kode === 'PKN_TK_II') {
                    $additionalRules['eselon'] = 'required|string|max:50';

                    if (!$kepegawaian || !$kepegawaian->file_sk_jabatan) {
                        $additionalRules['file_sk_jabatan'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$peserta || !$peserta->file_ktp) {
                        $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }
                    if (!$peserta || !$peserta->file_pas_foto) {
                        $additionalRules['file_pas_foto'] = 'required|max:5120'; // Ubah ini
                    }
                    if (!$pendaftaranTerbaru || !$pendaftaranTerbaru->file_surat_komitmen) {
                        $additionalRules['file_surat_komitmen'] = 'nullable|file|mimes:pdf|max:5120';
                    }
                    if (!$pendaftaranTerbaru || !$pendaftaranTerbaru->file_surat_tugas) {
                        $additionalRules['file_surat_tugas'] = 'nullable|file|mimes:pdf|max:5120';
                    }
                    if (!$kepegawaian || !$kepegawaian->file_sk_pangkat) {
                        $additionalRules['file_sk_pangkat'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$pendaftaranTerbaru->file_pakta_integritas) {
                        $additionalRules['file_pakta_integritas'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$pendaftaranTerbaru->file_surat_sehat) {
                        $additionalRules['file_surat_sehat'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$pendaftaranTerbaru->file_surat_bebas_narkoba) {
                        $additionalRules['file_surat_bebas_narkoba'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$pendaftaranTerbaru->file_surat_kelulusan_seleksi) {
                        $additionalRules['file_surat_kelulusan_seleksi'] = 'nullable|file|mimes:pdf|max:5120';
                    }

                    $additionalMessages = [
                        'eselon.required' => 'Eselon wajib diisi untuk pelatihan PKN TK II',
                        'file_sk_jabatan.required' => 'File SK Jabatan wajib diunggah untuk pelatihan PKN TK II',
                        'file_ktp.required' => 'File KTP wajib diunggah untuk pelatihan PKN TK II',
                        'file_pas_foto.required' => 'Pas Foto wajib diunggah untuk pelatihan PKN TK II',
                        'file_sk_pangkat.required' => 'File SK Pangkat wajib diunggah untuk pelatihan PKN TK II',
                        'file_pakta_integritas.required' => 'File Pakta Integritas wajib diunggah untuk pelatihan PKN TK II',
                        'file_surat_sehat.required' => 'File Surat Sehat wajib diunggah untuk pelatihan PKN TK II',
                        'file_surat_bebas_narkoba.required' => 'File Surat Bebas Narkoba wajib diunggah untuk pelatihan PKN TK II',
                    ];
                }

                if ($kode === 'LATSAR') {
                    $additionalRules = [
                        'nomor_sk_cpns' => 'required|string|max:100',
                        'tanggal_sk_cpns' => 'required|date',
                        'pangkat' => 'required|string|max:50',
                        'sudah_ada_mentor' => 'required|in:Ya,Tidak',
                    ];

                    if (!$kepegawaian || !$kepegawaian->file_sk_cpns) {
                        $additionalRules['file_sk_cpns'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$kepegawaian || !$kepegawaian->file_spmt) {
                        $additionalRules['file_spmt'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$pendaftaranTerbaru->file_surat_kesediaan) {
                        $additionalRules['file_surat_kesediaan'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$peserta->file_ktp) {
                        $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }
                    if (!$peserta || !$peserta->file_pas_foto) {
                        $additionalRules['file_pas_foto'] = 'required|max:5120'; // Ubah ini
                    }

                    $additionalMessages = [
                        'nomor_sk_cpns.required' => 'Nomor SK CPNS wajib diisi untuk pelatihan LATSAR',
                        'tanggal_sk_cpns.required' => 'Tanggal SK CPNS wajib diisi untuk pelatihan LATSAR',
                        'pangkat.required' => 'Pangkat wajib diisi untuk pelatihan LATSAR',
                        'sudah_ada_mentor.required' => 'Status mentor wajib dipilih untuk pelatihan LATSAR',
                        'file_ktp.required' => 'File KTP wajib diunggah untuk pelatihan LATSAR',
                        'file_sk_cpns.required' => 'File SK CPNS wajib diunggah untuk pelatihan LATSAR',
                        'file_spmt.required' => 'File SPMT wajib diunggah untuk pelatihan LATSAR',
                        'file_surat_kesediaan.required' => 'File Surat Kesediaan wajib diunggah untuk pelatihan LATSAR',
                        'file_pas_foto.required' => 'Pas Foto wajib diunggah untuk pelatihan LATSAR',
                    ];
                }

                if ($kode === 'PKA' || $kode === 'PKP') {
                    $additionalRules = [
                        'eselon' => 'required|string|max:50',
                        'tanggal_sk_jabatan' => 'required|date',
                        'sudah_ada_mentor' => 'required|in:Ya,Tidak',
                        'nomor_sk_terakhir' => 'required|string|max:100',
                    ];

                    if (!$pendaftaranTerbaru->file_surat_kesediaan) {
                        $additionalRules['file_surat_kesediaan'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$peserta->file_ktp) {
                        $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }
                    if (!$peserta->file_pas_foto) {
                        $additionalRules['file_pas_foto'] = 'required|max:5120'; // Ubah ini
                    }
                    if (!$kepegawaian || !$kepegawaian->file_sk_jabatan) {
                        $additionalRules['file_sk_jabatan'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$kepegawaian || !$kepegawaian->file_sk_pangkat) {
                        $additionalRules['file_sk_pangkat'] = 'required|file|mimes:pdf|max:5120';
                    }
                    if (!$pendaftaranTerbaru->file_pakta_integritas) {
                        $additionalRules['file_pakta_integritas'] = 'required|file|mimes:pdf|max:5120';
                    }

                    $additionalMessages = [
                        'eselon.required' => 'Eselon wajib diisi untuk pelatihan ' . $kode,
                        'tanggal_sk_jabatan.required' => 'Tanggal SK Jabatan wajib diisi untuk pelatihan ' . $kode,
                        'sudah_ada_mentor.required' => 'Status mentor wajib dipilih untuk pelatihan ' . $kode,
                        'nomor_sk_terakhir.required' => 'Nomor SK Jabatan Terakhir wajib diisi untuk pelatihan ' . $kode,
                        'file_surat_kesediaan.required' => 'File Surat Kesediaan wajib diunggah untuk pelatihan ' . $kode,
                        'file_ktp.required' => 'File KTP wajib diunggah untuk pelatihan ' . $kode,
                        'file_pas_foto.required' => 'Pas Foto wajib diunggah untuk pelatihan ' . $kode,
                        'file_sk_jabatan.required' => 'File SK Jabatan wajib diunggah untuk pelatihan ' . $kode,
                        'file_sk_pangkat.required' => 'File SK Pangkat wajib diunggah untuk pelatihan ' . $kode,
                        'file_pakta_integritas.required' => 'File Pakta Integritas wajib diunggah untuk pelatihan ' . $kode,
                    ];
                }

                if ($request->sudah_ada_mentor === 'Ya') {
                    $additionalRules['mentor_mode'] = 'required|in:pilih,tambah';
                    $additionalMessages['mentor_mode.required'] = 'Pilih mode mentor (Pilih dari daftar atau Tambah baru)';

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

                if (!empty($additionalRules)) {
                    $request->validate($additionalRules, $additionalMessages);
                }
            }

            // 3. PROSES FILE UPLOAD
            $files = [];

            // 3.1. Handle file pas_foto khusus karena bisa berupa data URL
            if ($request->has('file_pas_foto') && $request->file_pas_foto) {
                // Cek jika ini adalah data URL (dari crop)
                if (strpos($request->file_pas_foto, 'data:image') === 0) {
                    try {
                        $dataUrl = $request->file_pas_foto;

                        // Ekstrak data dari data URL
                        $image_parts = explode(";base64,", $dataUrl);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1] ?? 'jpeg';

                        // Decode base64
                        $image_base64 = base64_decode($image_parts[1]);

                        // Validasi ukuran (max 1MB)
                        if (strlen($image_base64) > 1024 * 1024) {
                            throw ValidationException::withMessages([
                                'file_pas_foto' => ['Ukuran file maksimal 1MB']
                            ]);
                        }

                        // Buat struktur folder
                        $tahun = date('Y');
                        $folderPath = $this->getFolderPath($pendaftaranTerbaru, $request->nip_nrp);

                        // Buat nama file
                        $timestamp = time();
                        $fileName = "pas_foto_{$timestamp}.jpg";
                        $drivePath = "{$folderPath}/{$fileName}";

                        // Hapus file lama jika ada
                        $this->deleteOldFile($peserta, $pendaftaranTerbaru, $kepegawaian, 'file_pas_foto');

                        // Simpan ke Google Drive
                        Storage::disk('google')->put($drivePath, $image_base64);

                        // Simpan path ke array files
                        $files['file_pas_foto'] = $drivePath;
                    } catch (\Exception $e) {
                        throw ValidationException::withMessages([
                            'file_pas_foto' => ['Gagal memproses foto: ' . $e->getMessage()]
                        ]);
                    }
                } elseif ($request->hasFile('file_pas_foto')) {
                    // Ini adalah file upload biasa
                    $file = $request->file('file_pas_foto');

                    // Validasi
                    if (!in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
                        throw ValidationException::withMessages([
                            'file_pas_foto' => ['Format file harus JPG, JPEG, atau PNG']
                        ]);
                    }

                    if ($file->getSize() > 1024 * 1024) {
                        throw ValidationException::withMessages([
                            'file_pas_foto' => ['Ukuran file maksimal 1MB']
                        ]);
                    }

                    // Proses upload file biasa
                    $folderPath = $this->getFolderPath($pendaftaranTerbaru, $request->nip_nrp);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'pas_foto.' . $extension;
                    $drivePath = "{$folderPath}/{$fileName}";

                    $this->deleteOldFile($peserta, $pendaftaranTerbaru, $kepegawaian, 'file_pas_foto');
                    Storage::disk('google')->put($drivePath, file_get_contents($file));
                    $files['file_pas_foto'] = $drivePath;
                }
            }

            // 3.2. PROSES FILE LAINNYA
            $fileFields = [
                'file_ktp',
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

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $folderPath = $this->getFolderPath($pendaftaranTerbaru, $request->nip_nrp);

                    $extension = $request->file($field)->getClientOriginalExtension();
                    $fieldName = str_replace('file_', '', $field);
                    $fileName = $fieldName . '.' . $extension;
                    $drivePath = "{$folderPath}/{$fileName}";

                    $this->deleteOldFile($peserta, $pendaftaranTerbaru, $kepegawaian, $field);
                    Storage::disk('google')->put($drivePath, file_get_contents($request->file($field)));
                    $files[$field] = $drivePath;
                }
            }

            // 4. UPDATE DATA PESERTA
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
                'nama_pasangan' => $request->status_perkawinan === 'Menikah' ? $request->nama_pasangan : null,
                'olahraga_hobi' => $request->olahraga_hobi,
                'perokok' => $request->perokok,
                'ukuran_kaos' => $request->ukuran_kaos,
                'ukuran_celana' => $request->ukuran_celana,
                'ukuran_training' => $request->ukuran_training,
                'kondisi_peserta' => $request->kondisi_peserta,
            ];

            if (isset($files['file_ktp'])) {
                $pesertaUpdateData['file_ktp'] = $files['file_ktp'];
            }

            if (isset($files['file_pas_foto'])) {
                $pesertaUpdateData['file_pas_foto'] = $files['file_pas_foto'];
            }

            $peserta->update($pesertaUpdateData);

            // 5. UPDATE KEPEGAWAIAN
            $provinsi = Provinsi::where('id', $request->id_provinsi)->first();
            $kabupaten = $request->id_kabupaten_kota ?
                Kabupaten::where('id', $request->id_kabupaten_kota)->first() : null;

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

            if ($kepegawaian) {
                $kepegawaian->update($kepegawaianUpdateData);
            } else {
                $kepegawaianUpdateData['id_peserta'] = $peserta->id;
                KepegawaianPeserta::create($kepegawaianUpdateData);
            }

            // 6. UPDATE DOKUMEN PENDAFTARAN
            if ($pendaftaranTerbaru) {
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

                if (!empty($pendaftaranUpdateData)) {
                    $pendaftaranTerbaru->update($pendaftaranUpdateData);
                }
            }

            // 7. SIMPAN MENTOR JIKA ADA
            if ($request->sudah_ada_mentor === 'Ya' && $pendaftaranTerbaru) {
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
                    \App\Models\PesertaMentor::updateOrCreate(
                        ['id_pendaftaran' => $pendaftaranTerbaru->id],
                        [
                            'id_mentor' => $mentor->id,
                            'tanggal_penunjukan' => now(),
                            'status_mentoring' => 'Ditugaskan',
                        ]
                    );
                }
            }

            aktifitas('Perbarui Data Peserta', $peserta);

            return redirect()->route('dashboard')
                ->with('success', 'Data berhasil diperbarui!')
                ->with('scroll_to', 'data-peserta-section');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Mohon periksa kembali data yang Anda masukkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Helper method untuk mendapatkan folder path
    private function getFolderPath($pendaftaranTerbaru, $nip)
    {
        $tahun = date('Y');

        if ($pendaftaranTerbaru && $pendaftaranTerbaru->jenisPelatihan && $pendaftaranTerbaru->angkatan) {
            $jenisPelatihan = $pendaftaranTerbaru->jenisPelatihan;
            $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
            $angkatan = $pendaftaranTerbaru->angkatan;
            $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan);

            return "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";
        }

        return "Berkas/{$tahun}/default/{$nip}";
    }

    // public function updateData(Request $request)
    // {
    //     try {
    //         $user = Auth::user();
    //         $peserta = Peserta::where('id', $user->peserta_id)->first();

    //         if (!$peserta) {
    //             throw ValidationException::withMessages([
    //                 'general' => ['Data peserta tidak ditemukan']
    //             ]);
    //         }

    //         $pendaftaranTerbaru = $peserta->pendaftaran()->first();
    //         $kepegawaian = $peserta->kepegawaian;

    //         // 1. VALIDASI DASAR (tanpa validasi file required di sini)
    //         $validated = $request->validate([
    //             'nama_lengkap' => 'required|string|max:200',
    //             'nip_nrp' => 'required|string|max:50',
    //             'nama_panggilan' => 'nullable|string|max:100',
    //             'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
    //             'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Kristen Protestan',
    //             'tempat_lahir' => 'required|string|max:100',
    //             'tanggal_lahir' => 'required|date',
    //             'alamat_rumah' => 'required|string',
    //             'email_pribadi' => 'required|email|max:100',
    //             'nomor_hp' => 'required|string|max:20',
    //             'pendidikan_terakhir' => 'required|in:SD,SMP,SMU,D3,D4,S1,S2,S3',
    //             'bidang_studi' => 'required|string|max:100',
    //             'bidang_keahlian' => 'nullable|string|max:100',
    //             'status_perkawinan' => 'required|in:Belum Menikah,Menikah,Duda,Janda',
    //             'olahraga_hobi' => 'nullable|string|max:100',
    //             'perokok' => 'required|in:Ya,Tidak',
    //             'ukuran_kaos' => 'required|in:S,M,L,XL,XXL,XXXL',
    //             'ukuran_celana' => 'required|in:S,M,L,XL,XXL,XXXL',
    //             'ukuran_training' => 'required|in:S,M,L,XL,XXL,XXXL',
    //             'kondisi_peserta' => 'nullable|string',

    //             // FILE VALIDATION HANYA JIKA ADA UPLOAD BARU
    //             'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //             'file_pas_foto' => 'nullable',

    //             // Data Kepegawaian
    //             'asal_instansi' => 'required|string|max:200',
    //             'unit_kerja' => 'required|string|max:200',
    //             'id_provinsi' => 'required',
    //             'id_kabupaten_kota' => 'required',
    //             'alamat_kantor' => 'required|string',
    //             'nomor_telepon_kantor' => 'nullable|string|max:20',
    //             'email_kantor' => 'nullable|email|max:100',
    //             'jabatan' => 'required|string|max:200',
    //             'pangkat' => 'nullable|string|max:50',
    //             'golongan_ruang' => 'required|string|max:10',
    //             'eselon' => 'nullable|string|max:50',

    //             // File Kepegawaian
    //             'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_sk_cpns' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_spmt' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_skp' => 'nullable|file|mimes:pdf|max:5120',

    //             // File Pendaftaran
    //             'file_surat_tugas' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_surat_sehat' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_surat_bebas_narkoba' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:5120',
    //             'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:5120',

    //             // Data SK
    //             'nomor_sk_cpns' => 'nullable|string|max:100',
    //             'nomor_sk_terakhir' => 'nullable|string|max:100',
    //             'tanggal_sk_cpns' => 'nullable|date',
    //             'tanggal_sk_jabatan' => 'nullable|date',
    //             'tahun_lulus_pkp_pim_iv' => 'nullable|integer',

    //             // Mentor
    //             'nama_mentor' => 'nullable|string|max:200',
    //             'jabatan_mentor' => 'nullable|string|max:200',
    //             'nomor_rekening_mentor' => 'nullable|string|max:200',
    //             'npwp_mentor' => 'nullable|string|max:50',
    //             'sudah_ada_mentor' => 'nullable|in:Ya,Tidak',
    //             'mentor_mode' => 'nullable|in:pilih,tambah',
    //             'id_mentor' => 'nullable|exists:mentor,id',
    //             'nama_mentor_baru' => 'nullable|string|max:200',
    //             'jabatan_mentor_baru' => 'nullable|string|max:200',
    //             'nomor_rekening_mentor_baru' => 'nullable|string|max:200',
    //             'npwp_mentor_baru' => 'nullable|string|max:50',
    //         ], [
    //             'nama_lengkap.required' => 'Nama lengkap wajib diisi',
    //             'nip_nrp.required' => 'NIP/NRP wajib diisi',
    //             'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
    //             'agama.required' => 'Agama wajib dipilih',
    //             'tempat_lahir.required' => 'Tempat lahir wajib diisi',
    //             'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
    //             'alamat_rumah.required' => 'Alamat rumah wajib diisi',
    //             'email_pribadi.required' => 'Email pribadi wajib diisi',
    //             'nomor_hp.required' => 'Nomor HP wajib diisi',
    //             'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih',
    //             'perokok.required' => 'Status perokok wajib dipilih',
    //             'asal_instansi.required' => 'Asal instansi wajib diisi',
    //             'id_provinsi.required' => 'Provinsi wajib dipilih',
    //             'alamat_kantor.required' => 'Alamat kantor wajib diisi',
    //             'jabatan.required' => 'Jabatan wajib diisi',
    //             'golongan_ruang.required' => 'Golongan ruang wajib diisi',
    //             'golongan_ruang.max' => 'Golongan ruang maksimal 10 karakter',
    //             'ukuran_kaos.required' => 'Ukuran kaos wajib dipilih',
    //             'ukuran_celana.required' => 'Ukuran celana wajib dipilih',
    //             'ukuran_training.required' => 'Ukuran training wajib dipilih',
    //             'bidang_studi.required' => 'Bidang studi wajib diisi',
    //             'status_perkawinan.required' => 'Status perkawinan wajib dipilih',
    //             'id_kabupaten_kota.required' => 'Kabupaten/Kota wajib dipilih',
    //             'unit_kerja.required' => 'Unit kerja wajib diisi',
    //         ]);

    //         // 2. VALIDASI KONDISIONAL BERDASARKAN STATUS FILE
    //         $additionalRules = [];
    //         $additionalMessages = [];

    //         if ($pendaftaranTerbaru && $pendaftaranTerbaru->jenisPelatihan) {
    //             $kode = $pendaftaranTerbaru->jenisPelatihan->kode_pelatihan;

    //             if ($kode === 'PKN_TK_II') {
    //                 $additionalRules['eselon'] = 'required|string|max:50';

    //                 if (!$kepegawaian || !$kepegawaian->file_sk_jabatan) {
    //                     $additionalRules['file_sk_jabatan'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$peserta || !$peserta->file_ktp) {
    //                     $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
    //                 }
    //                 if (!$peserta || !$peserta->file_pas_foto) {
    //                     $additionalRules['file_pas_foto'] = 'required|file|mimes:jpg,jpeg,png|max:5120';
    //                 }
    //                 if (!$pendaftaranTerbaru || !$pendaftaranTerbaru->file_surat_komitmen) {
    //                     $additionalRules['file_surat_komitmen'] = 'nullable|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$pendaftaranTerbaru || !$pendaftaranTerbaru->file_surat_tugas) {
    //                     $additionalRules['file_surat_tugas'] = 'nullable|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$kepegawaian || !$kepegawaian->file_sk_pangkat) {
    //                     $additionalRules['file_sk_pangkat'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$pendaftaranTerbaru->file_pakta_integritas) {
    //                     $additionalRules['file_pakta_integritas'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$pendaftaranTerbaru->file_surat_sehat) {
    //                     $additionalRules['file_surat_sehat'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$pendaftaranTerbaru->file_surat_bebas_narkoba) {
    //                     $additionalRules['file_surat_bebas_narkoba'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$pendaftaranTerbaru->file_surat_kelulusan_seleksi) {
    //                     $additionalRules['file_surat_kelulusan_seleksi'] = 'nullable|file|mimes:pdf|max:5120';
    //                 }

    //                 $additionalMessages = [
    //                     'eselon.required' => 'Eselon wajib diisi untuk pelatihan PKN TK II',
    //                     'file_sk_jabatan.required' => 'File SK Jabatan wajib diunggah untuk pelatihan PKN TK II',
    //                     'file_ktp.required' => 'File KTP wajib diunggah untuk pelatihan PKN TK II',
    //                     'file_pas_foto.required' => 'File Pas Foto wajib diunggah untuk pelatihan PKN TK II',
    //                     'file_sk_pangkat.required' => 'File SK Pangkat wajib diunggah untuk pelatihan PKN TK II',
    //                     'file_pakta_integritas.required' => 'File Pakta Integritas wajib diunggah untuk pelatihan PKN TK II',
    //                     'file_surat_sehat.required' => 'File Surat Sehat wajib diunggah untuk pelatihan PKN TK II',
    //                     'file_surat_bebas_narkoba.required' => 'File Surat Bebas Narkoba wajib diunggah untuk pelatihan PKN TK II',
    //                 ];
    //             }

    //             if ($kode === 'LATSAR') {
    //                 $additionalRules = [
    //                     'nomor_sk_cpns' => 'required|string|max:100',
    //                     'tanggal_sk_cpns' => 'required|date',
    //                     'pangkat' => 'required|string|max:50',
    //                     'sudah_ada_mentor' => 'required|in:Ya,Tidak',
    //                 ];

    //                 if (!$kepegawaian || !$kepegawaian->file_sk_cpns) {
    //                     $additionalRules['file_sk_cpns'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$kepegawaian || !$kepegawaian->file_spmt) {
    //                     $additionalRules['file_spmt'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$pendaftaranTerbaru->file_surat_kesediaan) {
    //                     $additionalRules['file_surat_kesediaan'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$peserta->file_ktp) {
    //                     $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
    //                 }
    //                 if (!$peserta || !$peserta->file_pas_foto) {
    //                     $additionalRules['file_pas_foto'] = 'required|file|max:5120';
    //                 }

    //                 $additionalMessages = [
    //                     'nomor_sk_cpns.required' => 'Nomor SK CPNS wajib diisi untuk pelatihan LATSAR',
    //                     'tanggal_sk_cpns.required' => 'Tanggal SK CPNS wajib diisi untuk pelatihan LATSAR',
    //                     'pangkat.required' => 'Pangkat wajib diisi untuk pelatihan LATSAR',
    //                     'sudah_ada_mentor.required' => 'Status mentor wajib dipilih untuk pelatihan LATSAR',
    //                     'file_ktp.required' => 'File KTP wajib diunggah untuk pelatihan LATSAR',
    //                     'file_sk_cpns.required' => 'File SK CPNS wajib diunggah untuk pelatihan LATSAR',
    //                     'file_spmt.required' => 'File SPMT wajib diunggah untuk pelatihan LATSAR',
    //                     'file_surat_kesediaan.required' => 'File Surat Kesediaan wajib diunggah untuk pelatihan LATSAR',
    //                     'file_pas_foto.required' => 'File Pas Foto wajib diunggah untuk pelatihan LATSAR',
    //                 ];
    //             }

    //             if ($kode === 'PKA' || $kode === 'PKP') {
    //                 $additionalRules = [
    //                     'eselon' => 'required|string|max:50',
    //                     'tanggal_sk_jabatan' => 'required|date',
    //                     'sudah_ada_mentor' => 'required|in:Ya,Tidak',
    //                     'nomor_sk_terakhir' => 'required|string|max:100',
    //                 ];

    //                 if (!$pendaftaranTerbaru->file_surat_kesediaan) {
    //                     $additionalRules['file_surat_kesediaan'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$peserta->file_ktp) {
    //                     $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
    //                 }
    //                 if (!$peserta->file_pas_foto) {
    //                     $additionalRules['file_pas_foto'] = 'required|file|mimes:jpg,jpeg,png|max:5120';
    //                 }
    //                 if (!$kepegawaian || !$kepegawaian->file_sk_jabatan) {
    //                     $additionalRules['file_sk_jabatan'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$kepegawaian || !$kepegawaian->file_sk_pangkat) {
    //                     $additionalRules['file_sk_pangkat'] = 'required|file|mimes:pdf|max:5120';
    //                 }
    //                 if (!$pendaftaranTerbaru->file_pakta_integritas) {
    //                     $additionalRules['file_pakta_integritas'] = 'required|file|mimes:pdf|max:5120';
    //                 }

    //                 $additionalMessages = [
    //                     'eselon.required' => 'Eselon wajib diisi untuk pelatihan ' ,
    //                     'tanggal_sk_jabatan.required' => 'Tanggal SK Jabatan wajib diisi untuk pelatihan ' ,
    //                     'sudah_ada_mentor.required' => 'Status mentor wajib dipilih untuk pelatihan ',
    //                     'nomor_sk_terakhir.required' => 'Nomor SK Jabatan Terakhir wajib diisi untuk pelatihan ',
    //                     'file_surat_kesediaan.required' => 'File Surat Kesediaan wajib diunggah untuk pelatihan ',
    //                     'file_ktp.required' => 'File KTP wajib diunggah untuk pelatihan ',
    //                     'file_pas_foto.required' => 'File Pas Foto wajib diunggah untuk pelatihan ',
    //                     'file_sk_jabatan.required' => 'File SK Jabatan wajib diunggah untuk pelatihan ',
    //                     'file_sk_pangkat.required' => 'File SK Pangkat wajib diunggah untuk pelatihan ',
    //                     'file_pakta_integritas.required' => 'File Pakta Integritas wajib diunggah untuk pelatihan ' ,
    //                 ];
    //             }

    //             if ($request->sudah_ada_mentor === 'Ya') {
    //                 $additionalRules['mentor_mode'] = 'required|in:pilih,tambah';
    //                 $additionalMessages['mentor_mode.required'] = 'Pilih mode mentor (Pilih dari daftar atau Tambah baru)';

    //                 if ($request->mentor_mode === 'pilih') {
    //                     $additionalRules['id_mentor'] = 'required|exists:mentor,id';
    //                     $additionalMessages['id_mentor.required'] = 'Pilih mentor dari daftar';
    //                 } elseif ($request->mentor_mode === 'tambah') {
    //                     $additionalRules['nama_mentor_baru'] = 'required|string|max:200';
    //                     $additionalRules['jabatan_mentor_baru'] = 'required|string|max:200';
    //                     $additionalMessages['nama_mentor_baru.required'] = 'Nama mentor baru wajib diisi';
    //                     $additionalMessages['jabatan_mentor_baru.required'] = 'Jabatan mentor baru wajib diisi';
    //                 }
    //             }

    //             if (!empty($additionalRules)) {
    //                 $request->validate($additionalRules, $additionalMessages);
    //             }
    //         }

    //         // 3. PROSES FILE PAS FOTO KHUSUS (handle data URL)
    //         $files = [];

    //         // Handle file pas_foto khusus karena bisa berupa data URL
    //         if ($request->has('file_pas_foto') && $request->file_pas_foto) {
    //             // Cek jika ini adalah data URL (dari crop)
    //             if (strpos($request->file_pas_foto, 'data:image') === 0) {
    //                 // Ini adalah data URL, konversi ke file
    //                 try {
    //                     $dataUrl = $request->file_pas_foto;

    //                     // Ekstrak data dari data URL
    //                     $image_parts = explode(";base64,", $dataUrl);
    //                     $image_type_aux = explode("image/", $image_parts[0]);
    //                     $image_type = $image_type_aux[1] ?? 'jpeg';

    //                     // Decode base64
    //                     $image_base64 = base64_decode($image_parts[1]);

    //                     // Validasi ukuran (max 1MB)
    //                     if (strlen($image_base64) > 1024 * 1024) {
    //                         throw ValidationException::withMessages([
    //                             'file_pas_foto' => ['Ukuran file maksimal 1MB']
    //                         ]);
    //                     }

    //                     // Buat struktur folder
    //                     $tahun = date('Y');
    //                     $folderPath = null;

    //                     if ($pendaftaranTerbaru && $pendaftaranTerbaru->jenisPelatihan && $pendaftaranTerbaru->angkatan) {
    //                         $jenisPelatihan = $pendaftaranTerbaru->jenisPelatihan;
    //                         $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
    //                         $angkatan = $pendaftaranTerbaru->angkatan;
    //                         $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan);
    //                         $nip = $request->nip_nrp;

    //                         $folderPath = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";
    //                     } else {
    //                         $folderPath = "Berkas/{$tahun}/default/{$request->nip_nrp}";
    //                     }

    //                     // Buat nama file
    //                     $timestamp = time();
    //                     $fileName = "pas_foto_{$timestamp}.jpg";
    //                     $drivePath = "{$folderPath}/{$fileName}";

    //                     // Hapus file lama jika ada
    //                     $this->deleteOldFile($peserta, $pendaftaranTerbaru, $kepegawaian, 'file_pas_foto');

    //                     // Simpan ke Google Drive
    //                     Storage::disk('google')->put($drivePath, $image_base64);

    //                     // Simpan path ke array files
    //                     $files['file_pas_foto'] = $drivePath;
    //                 } catch (\Exception $e) {
    //                     throw ValidationException::withMessages([
    //                         'file_pas_foto' => ['Gagal memproses foto: ' . $e->getMessage()]
    //                     ]);
    //                 }
    //             } elseif ($request->hasFile('file_pas_foto')) {
    //                 // Ini adalah file upload biasa
    //                 $file = $request->file('file_pas_foto');

    //                 // Validasi
    //                 if (!in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
    //                     throw ValidationException::withMessages([
    //                         'file_pas_foto' => ['Format file harus JPG, JPEG, atau PNG']
    //                     ]);
    //                 }

    //                 if ($file->getSize() > 1024 * 1024) {
    //                     throw ValidationException::withMessages([
    //                         'file_pas_foto' => ['Ukuran file maksimal 1MB']
    //                     ]);
    //                 }

    //                 // Buat struktur folder (sama seperti di atas)
    //                 $tahun = date('Y');
    //                 $folderPath = null;

    //                 if ($pendaftaranTerbaru && $pendaftaranTerbaru->jenisPelatihan && $pendaftaranTerbaru->angkatan) {
    //                     $jenisPelatihan = $pendaftaranTerbaru->jenisPelatihan;
    //                     $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
    //                     $angkatan = $pendaftaranTerbaru->angkatan;
    //                     $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan);
    //                     $nip = $request->nip_nrp;

    //                     $folderPath = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";
    //                 } else {
    //                     $folderPath = "Berkas/{$tahun}/default/{$request->nip_nrp}";
    //                 }

    //                 // Buat nama file
    //                 $extension = $file->getClientOriginalExtension();
    //                 $fileName = 'pas_foto.' . $extension;
    //                 $drivePath = "{$folderPath}/{$fileName}";

    //                 // Hapus file lama jika ada
    //                 $this->deleteOldFile($peserta, $pendaftaranTerbaru, $kepegawaian, 'file_pas_foto');

    //                 // Simpan ke Google Drive
    //                 Storage::disk('google')->put($drivePath, file_get_contents($file));

    //                 // Simpan path ke array files
    //                 $files['file_pas_foto'] = $drivePath;
    //             }
    //         }

    //         // 3. SIMPAN FILE UPLOADS DENGAN STRUKTUR FOLDER: Berkas/Tahun/JenisPelatihan/Angkatan/NIP
    //         $fileFields = [
    //             'file_ktp',
    //             'file_sk_jabatan',
    //             'file_sk_pangkat',
    //             'file_surat_tugas',
    //             'file_surat_kesediaan',
    //             'file_pakta_integritas',
    //             'file_surat_komitmen',
    //             'file_surat_kelulusan_seleksi',
    //             'file_surat_sehat',
    //             'file_surat_bebas_narkoba',
    //             'file_surat_pernyataan_administrasi',
    //             'file_sertifikat_penghargaan',
    //             'file_sk_cpns',
    //             'file_spmt',
    //             'file_skp',
    //             'file_persetujuan_mentor'
    //         ];

    //         // Ambil data untuk struktur folder
    //         $tahun = date('Y');
    //         $folderPath = null;
    //         $fullPath = null;

    //         if ($pendaftaranTerbaru && $pendaftaranTerbaru->jenisPelatihan && $pendaftaranTerbaru->angkatan) {
    //             $jenisPelatihan = $pendaftaranTerbaru->jenisPelatihan;
    //             $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
    //             $angkatan = $pendaftaranTerbaru->angkatan;
    //             $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan);
    //             $nip = $request->nip_nrp;

    //             // Buat struktur folder: Berkas/Tahun/JenisPelatihan/Angkatan/NIP
    //             $folderPath = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";
    //             // $fullPath = public_path($folderPath);

    //             // // Buat folder jika belum ada
    //             // if (!file_exists($fullPath)) {
    //             //     mkdir($fullPath, 0755, true);
    //             // }
    //         }

    //         // $files = [];
    //         // foreach ($fileFields as $field) {
    //         //     if ($request->hasFile($field)) {
    //         //         try {
    //         //             // Jika tidak ada pendaftaran, gunakan folder default
    //         //             if (!$fullPath) {
    //         //                 $folderPath = "Berkas/{$tahun}/default/{$request->nip_nrp}";
    //         //                 $fullPath = public_path($folderPath);

    //         //                 if (!file_exists($fullPath)) {
    //         //                     mkdir($fullPath, 0755, true);
    //         //                 }
    //         //             }

    //         //             // Ambil ekstensi file
    //         //             $extension = $request->file($field)->getClientOriginalExtension();

    //         //             // Buat nama file yang lebih deskriptif (hilangkan prefix 'file_')
    //         //             $fieldName = str_replace('file_', '', $field);
    //         //             $fileName = $fieldName . '.' . $extension;

    //         //             // Pindahkan file ke folder yang sudah ditentukan
    //         //             $request->file($field)->move($fullPath, $fileName);

    //         //             // Simpan path relatif untuk database (DENGAN SLASH DI AWAL)
    //         //             $files[$field] = '/' . $folderPath . '/' . $fileName;

    //         //             // Hapus file lama jika ada
    //         //             $this->deleteOldFile($peserta, $pendaftaranTerbaru, $kepegawaian, $field);
    //         //         } catch (\Exception $e) {
    //         //             throw ValidationException::withMessages([
    //         //                 $field => ['Gagal mengupload file: ' . $e->getMessage()]
    //         //             ]);
    //         //         }
    //         //     }
    //         // }

    //         $files = [];

    //         foreach ($fileFields as $field) {
    //             if ($request->hasFile($field)) {

    //                 // Pastikan folder path ada
    //                 if (!$folderPath) {
    //                     $folderPath = "Berkas/{$tahun}/default/{$request->nip_nrp}";
    //                 }

    //                 // Ambil ekstensi
    //                 $extension = $request->file($field)->getClientOriginalExtension();
    //                 $fieldName = str_replace('file_', '', $field);
    //                 $fileName = $fieldName . '.' . $extension;

    //                 // FULL PATH GOOGLE DRIVE
    //                 $drivePath = "{$folderPath}/{$fileName}";

    //                 // 🔥 HAPUS FILE LAMA DI DRIVE
    //                 $this->deleteOldFile($peserta, $pendaftaranTerbaru, $kepegawaian, $field);

    //                 // 🔥 SIMPAN KE GOOGLE DRIVE
    //                 Storage::disk('google')->put(
    //                     $drivePath,
    //                     file_get_contents($request->file($field))
    //                 );

    //                 // SIMPAN PATH RELATIF KE DB
    //                 $files[$field] = $drivePath;
    //             }
    //         }


    //         // 4. UPDATE DATA PESERTA
    //         $pesertaUpdateData = [
    //             'nama_lengkap' => $request->nama_lengkap,
    //             'nip_nrp' => $request->nip_nrp,
    //             'nama_panggilan' => $request->nama_panggilan,
    //             'jenis_kelamin' => $request->jenis_kelamin,
    //             'agama' => $request->agama,
    //             'tempat_lahir' => $request->tempat_lahir,
    //             'tanggal_lahir' => $request->tanggal_lahir,
    //             'pendidikan_terakhir' => $request->pendidikan_terakhir,
    //             'alamat_rumah' => $request->alamat_rumah,
    //             'email_pribadi' => $request->email_pribadi,
    //             'nomor_hp' => $request->nomor_hp,
    //             'bidang_studi' => $request->bidang_studi,
    //             'bidang_keahlian' => $request->bidang_keahlian,
    //             'status_perkawinan' => $request->status_perkawinan,
    //             'nama_pasangan' => $request->status_perkawinan === 'Menikah' ? $request->nama_pasangan : null,
    //             'olahraga_hobi' => $request->olahraga_hobi,
    //             'perokok' => $request->perokok,
    //             'ukuran_kaos' => $request->ukuran_kaos,
    //             'ukuran_celana' => $request->ukuran_celana,
    //             'ukuran_training' => $request->ukuran_training,
    //             'kondisi_peserta' => $request->kondisi_peserta,
    //         ];

    //         // Tambahkan file KTP dan pas foto jika ada
    //         if (isset($files['file_ktp'])) {
    //             $pesertaUpdateData['file_ktp'] = $files['file_ktp'];
    //         }

    //         if (isset($files['file_pas_foto'])) {
    //             $pesertaUpdateData['file_pas_foto'] = $files['file_pas_foto'];
    //         }

    //         $peserta->update($pesertaUpdateData);

    //         // 5. UPDATE KEPEGAWAIAN
    //         $provinsi = Provinsi::where('id', $request->id_provinsi)->first();
    //         $kabupaten = $request->id_kabupaten_kota ?
    //             Kabupaten::where('id', $request->id_kabupaten_kota)->first() : null;

    //         $kepegawaianUpdateData = [
    //             'asal_instansi' => $request->asal_instansi,
    //             'unit_kerja' => $request->unit_kerja,
    //             'id_provinsi' => $provinsi->id,
    //             'id_kabupaten_kota' => $kabupaten?->id,
    //             'alamat_kantor' => $request->alamat_kantor,
    //             'nomor_telepon_kantor' => $request->nomor_telepon_kantor,
    //             'email_kantor' => $request->email_kantor,
    //             'jabatan' => $request->jabatan,
    //             'pangkat' => $request->pangkat,
    //             'golongan_ruang' => $request->golongan_ruang,
    //             'eselon' => $request->eselon,
    //             'tanggal_sk_jabatan' => $request->tanggal_sk_jabatan,
    //             'nomor_sk_cpns' => $request->nomor_sk_cpns,
    //             'tanggal_sk_cpns' => $request->tanggal_sk_cpns,
    //             'tahun_lulus_pkp_pim_iv' => $request->tahun_lulus_pkp_pim_iv,
    //             'nomor_sk_terakhir' => $request->nomor_sk_terakhir,
    //         ];

    //         $kepegawaianFileFields = [
    //             'file_sk_jabatan',
    //             'file_sk_pangkat',
    //             'file_sk_cpns',
    //             'file_spmt',
    //             'file_skp',
    //         ];

    //         foreach ($kepegawaianFileFields as $field) {
    //             if (isset($files[$field])) {
    //                 $kepegawaianUpdateData[$field] = $files[$field];
    //             }
    //         }

    //         if ($kepegawaian) {
    //             $kepegawaian->update($kepegawaianUpdateData);
    //         } else {
    //             $kepegawaianUpdateData['id_peserta'] = $peserta->id;
    //             KepegawaianPeserta::create($kepegawaianUpdateData);
    //         }

    //         // 6. UPDATE DOKUMEN PENDAFTARAN
    //         if ($pendaftaranTerbaru) {
    //             $pendaftaranUpdateData = [];

    //             $pendaftaranFileFields = [
    //                 'file_surat_tugas',
    //                 'file_surat_kesediaan',
    //                 'file_pakta_integritas',
    //                 'file_surat_komitmen',
    //                 'file_surat_kelulusan_seleksi',
    //                 'file_surat_sehat',
    //                 'file_surat_bebas_narkoba',
    //                 'file_surat_pernyataan_administrasi',
    //                 'file_sertifikat_penghargaan',
    //                 'file_persetujuan_mentor'
    //             ];

    //             foreach ($pendaftaranFileFields as $field) {
    //                 if (isset($files[$field])) {
    //                     $pendaftaranUpdateData[$field] = $files[$field];
    //                 }
    //             }

    //             if (!empty($pendaftaranUpdateData)) {
    //                 $pendaftaranTerbaru->update($pendaftaranUpdateData);
    //             }
    //         }

    //         // 7. SIMPAN MENTOR JIKA ADA
    //         if ($request->sudah_ada_mentor === 'Ya' && $pendaftaranTerbaru) {
    //             $mentor = null;

    //             if ($request->mentor_mode === 'pilih' && $request->id_mentor) {
    //                 $mentor = Mentor::find($request->id_mentor);
    //             } elseif ($request->mentor_mode === 'tambah') {
    //                 $mentor = Mentor::create([
    //                     'nama_mentor' => $request->nama_mentor_baru,
    //                     'jabatan_mentor' => $request->jabatan_mentor_baru,
    //                     'nomor_rekening' => $request->nomor_rekening_mentor_baru,
    //                     'npwp_mentor' => $request->npwp_mentor_baru,
    //                     'status_aktif' => true,
    //                 ]);
    //             }

    //             if ($mentor) {
    //                 \App\Models\PesertaMentor::updateOrCreate(
    //                     ['id_pendaftaran' => $pendaftaranTerbaru->id],
    //                     [
    //                         'id_mentor' => $mentor->id,
    //                         'tanggal_penunjukan' => now(),
    //                         'status_mentoring' => 'Ditugaskan',
    //                     ]
    //                 );
    //             }
    //         }

    //         aktifitas('Perbarui Data Peserta', $peserta);

    //         // 8. RESPONSE
    //         return redirect()->route('dashboard')
    //             ->with('success', 'Data berhasil diperbarui!')
    //             ->with('scroll_to', 'data-peserta-section');
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return redirect()
    //             ->back()
    //             ->withErrors($e->validator)
    //             ->withInput()
    //             ->with('error', 'Validasi gagal. Mohon periksa kembali data yang Anda masukkan.');
    //     } catch (\Exception $e) {
    //         return redirect()
    //             ->back()
    //             ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

    private function deleteOldFile($peserta, $pendaftaran, $kepegawaian, $field)
    {
        try {
            $oldFilePath = null;

            if (in_array($field, ['file_ktp', 'file_pas_foto']) && $peserta->$field) {
                $oldFilePath = $peserta->$field;
            } elseif (
                in_array($field, ['file_sk_jabatan', 'file_sk_pangkat', 'file_sk_cpns', 'file_spmt', 'file_skp'])
                && $kepegawaian && $kepegawaian->$field
            ) {
                $oldFilePath = $kepegawaian->$field;
            } elseif ($pendaftaran && $pendaftaran->$field) {
                $oldFilePath = $pendaftaran->$field;
            }

            if ($oldFilePath && Storage::disk('google')->exists($oldFilePath)) {
                Storage::disk('google')->delete($oldFilePath);
            }
        } catch (\Exception $e) {
            // optional log
        }
    }


    public function histori(Request $request)
    {
        // Query dengan eager loading
        $query = Aktifitas::with('user')->latest();

        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Pagination
        $perPage = $request->per_page ?? 10;
        $logs = $query->paginate($perPage);

        // Statistik
        $totalAktivitas = Aktifitas::count();
        $aktivitasHariIni = Aktifitas::whereDate('created_at', today())->count();
        $aktivitasMingguIni = Aktifitas::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        return view('admin.aktifitas.index', compact(
            'logs',
            'totalAktivitas',
            'aktivitasHariIni',
            'aktivitasMingguIni'
        ));
    }


    public function preview(Request $request)
    {
        $path = $request->query('path');
        abort_if(!$path, 404);

        // hardening sederhana: block path aneh
        abort_if(str_contains($path, '..'), 403);

        if (!Storage::disk('google')->exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $mime = Storage::disk('google')->mimeType($path) ?? 'application/octet-stream';
        $content = Storage::disk('google')->get($path);

        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    public function download(Request $request)
    {
        // 1. Validasi parameter
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->path;

        // 2. Cek apakah file ada di Google Drive
        if (!Storage::disk('google')->exists($path)) {
            abort(404, 'File tidak ditemukan di Google Drive');
        }

        // 3. Ambil nama file
        $fileName = basename($path);

        // 4. Download file
        return Storage::disk('google')->download($path, $fileName);
    }
}
