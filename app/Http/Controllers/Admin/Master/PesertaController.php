<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use App\Models\JenisPelatihan;
use App\Models\KepegawaianPeserta;
use App\Models\Angkatan;
use App\Models\Kabupaten;
use App\Models\PesertaMentor;
use App\Models\Mentor;
use App\Models\Provinsi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Models\AksiPerubahan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class PesertaController extends Controller
{
    /**
     * Display a listing of peserta PKN TK II.
     */
    private $jenisMapping = [
        'pkn' => ['id' => 1, 'nama' => 'PKN TK II'],
        'latsar' => ['id' => 2, 'nama' => 'LATSAR'],
        'pka' => ['id' => 3, 'nama' => 'PKA'],
        'pkp' => ['id' => 4, 'nama' => 'PKP']
    ];


    private function getJenisData($jenis)
    {
        if (!array_key_exists($jenis, $this->jenisMapping)) {
            abort(404);
        }
        return $this->jenisMapping[$jenis];
    }


    public function index(Request $request, $jenis)
    {
        $jenisData = $this->getJenisData($jenis);
        $jenisPelatihanId = $jenisData['id'];
        $user = Auth::user();

        // Ambil angkatan yang bisa diakses PIC
        $picAngkatanIds = $user->picPesertas
            ->where('jenispelatihan_id', $jenisPelatihanId)
            ->pluck('angkatan_id')
            ->unique()
            ->toArray();

        // Filter angkatan
        $angkatanQuery = Angkatan::where('id_jenis_pelatihan', $jenisPelatihanId);

        if ($user->role->name === 'pic' && !empty($picAngkatanIds)) {
            $angkatanQuery->whereIn('id', $picAngkatanIds);
        }

        $angkatanList = $angkatanQuery->orderBy('tahun', 'desc')->get();

        // Query pendaftaran
        $pendaftaranQuery = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaianPeserta',
            'angkatan',
            'pesertaMentor.mentor'
        ])
            ->where('id_jenis_pelatihan', $jenisPelatihanId)
            ->whereNotNull('id_angkatan')
            ->where('id_angkatan', '!=', 0);

        // Filter berdasarkan akses PIC
        if ($user->role->name === 'pic' && !empty($picAngkatanIds)) {
            $pendaftaranQuery->whereIn('id_angkatan', $picAngkatanIds);
        }

        // Filter dropdown
        if ($request->filled('angkatan') && $request->angkatan != '' && $request->angkatan != 'semua') {
            $pendaftaranQuery->where('id_angkatan', $request->angkatan);
        }

        // $pendaftaran = $pendaftaranQuery->latest('tanggal_daftar')->get();
        $pendaftaran = $pendaftaranQuery->orderBy('id', 'asc')->get();
        $jenisPelatihan = JenisPelatihan::find($jenisPelatihanId);

        return view("admin.peserta.{$jenis}.index", compact(
            'pendaftaran',
            'angkatanList',
            'jenisPelatihan',
            'jenis'
        ));
    }

    /**
     * Get peserta detail for modal.
     */
    public function getDetail($id)
    {
        $pendaftaran = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaianPeserta',
            'peserta.kepegawaianPeserta.provinsi',
            'peserta.kepegawaianPeserta.kabupaten',
            'angkatan',
            'pesertaMentor.mentor',
            'jenisPelatihan',
            'aksiPerubahan'
        ])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'pendaftaran' => $pendaftaran,
                'peserta' => $pendaftaran->peserta,
                'kepegawaian' => $pendaftaran->peserta->kepegawaianPeserta,
                'provinsi' => $pendaftaran->peserta->kepegawaianPeserta->provinsi,
                'kabupaten' => $pendaftaran->peserta->kepegawaianPeserta->kabupaten,
                'angkatan' => $pendaftaran->angkatan,
                'mentor' => $pendaftaran->pesertaMentor->first()?->mentor ?? null,
                'aksi_perubahan' => $pendaftaran->aksiPerubahan ?? null,
            ]
        ]);
    }


    /**
     * Update status pendaftaran.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pendaftaran' => 'required|in:Menunggu Verifikasi,Diterima,Ditolak,Lulus',
            'catatan_verifikasi' => 'nullable|string|max:500'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $role = Role::where('name', 'user')->first();
        $peserta = $pendaftaran->peserta;

        // 🔒 VALIDASI LINK GB WA DI ANGKATAN
        if (empty($pendaftaran->angkatan->link_gb_wa)) {
            return response()->json([
                'success' => false,
                'message' => 'Link Grup WhatsApp angkatan belum diisi. Silakan lengkapi terlebih dahulu.'
            ], 422);
        }

        if ($request->status_pendaftaran == 'Diterima') {

            $lastNdh = Pendaftaran::where('id_jenis_pelatihan', $pendaftaran->id_jenis_pelatihan)
                ->where('id_angkatan', $pendaftaran->id_angkatan)
                ->whereHas('peserta', function ($q) {
                    $q->whereNotNull('ndh');
                })
                ->with('peserta')
                ->get()
                ->max('peserta.ndh');

            $ndhBaru = $lastNdh ? $lastNdh + 1 : 1;

            // update NDH peserta
            $peserta->update([
                'ndh' => $ndhBaru
            ]);


            // password asli (untuk email)
            $passwordAsli = Str::random(8);

            // simpan user
            $user = User::updateOrCreate(
                ['peserta_id' => $pendaftaran->peserta->id],
                [
                    'name'     => $pendaftaran->peserta->nama_lengkap,
                    'email'    => $pendaftaran->peserta->email_pribadi,
                    'password' => bcrypt($passwordAsli),
                    'role_id'  => $role->id,
                ]
            );

            // update pesrta

            // data email
            $data = [
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => $passwordAsli,
                'link_gb_wa' => $pendaftaran->angkatan->link_gb_wa,
            ];

            // kirim email ke peserta
            Mail::to($user->email)->send(new SendEmail($data));
        }

        $pendaftaran->update([
            'status_pendaftaran' => $request->status_pendaftaran,
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'tanggal_verifikasi' => now()
        ]);

        $jenisPelatihan = $pendaftaran->jenisPelatihan->nama_pelatihan;
        $angkatan = $pendaftaran->angkatan->nama_angkatan;
        aktifitas("Mengubah Status Pendaftaran {$jenisPelatihan} - {$angkatan}", $peserta);

        return response()->json([
            'success' => true,
            'message' => 'Status pendaftaran berhasil diperbarui'
        ]);
    }


    // Method untuk create form
    public function create(Request $request, $jenis = null)
    {
        if (!$jenis) {
            $jenis = $request->jenis ?? session('jenis_pelatihan');
        }

        $jenisData = $this->getJenisData($jenis);
        $jenisPelatihanId = $jenisData['id'];

        $user = Auth::user();

        // =====================================
        // AMBIL ANGKATAN YANG DIAKSES OLEH PIC
        // =====================================
        $picAngkatanIds = [];

        if ($user->role->name === 'pic') {
            $picAngkatanIds = $user->picPesertas
                ->where('jenispelatihan_id', $jenisPelatihanId)
                ->pluck('angkatan_id')
                ->unique()
                ->toArray();
        }

        // =====================
        // DATA MASTER
        // =====================
        $mentorList = Mentor::where('status_aktif', true)->get();

        // =====================
        // QUERY ANGKATAN
        // =====================
        $angkatanQuery = Angkatan::where('id_jenis_pelatihan', $jenisPelatihanId)
            ->where('status_angkatan', 'Dibuka');

        // Filter khusus PIC
        if ($user->role->name === 'pic' && !empty($picAngkatanIds)) {
            $angkatanQuery->whereIn('id', $picAngkatanIds);
        }

        $angkatanList = $angkatanQuery->get();

        $provinsiList = Provinsi::all();
        $kabupatenList = Kabupaten::all();

        // =====================
        // FLAG VIEW
        // =====================
        $isEdit = false;
        $kunci_judul = false;
        $aksiPerubahan = null;

        session(['jenis_pelatihan' => $jenis]);

        // =====================
        // RESPONSE AJAX
        // =====================
        if ($request->ajax()) {
            return response()->json([
                'jenis_pelatihan' => $jenisPelatihanId,
                'mentor' => $mentorList,
                'angkatanList' => $angkatanList,
                'provinsiList' => $provinsiList,
                'kabupatenList' => $kabupatenList,
            ]);
        }

        // =====================
        // VIEW
        // =====================
        return view("admin.peserta.{$jenis}.create", compact(
            'mentorList',
            'angkatanList',
            'provinsiList',
            'kabupatenList',
            'isEdit',
            'jenis',
            'kunci_judul',
            'aksiPerubahan'
        ));
    }

    // Method untuk edit form
    public function edit(Request $request, $jenis, $id)
    {
        $jenisData = $this->getJenisData($jenis);

        $pendaftaran = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaianPeserta',
            'peserta.kepegawaianPeserta.provinsi',
            'peserta.kepegawaianPeserta.kabupaten',
            'angkatan',
            'pesertaMentor.mentor',
            'aksiPerubahan'
        ])->findOrFail($id);

        // Verifikasi jenis pelatihan sesuai
        if ($pendaftaran->id_jenis_pelatihan != $jenisData['id']) {
            abort(404, 'Data tidak ditemukan untuk jenis pelatihan ini');
        }

        $mentorList = Mentor::where('status_aktif', true)->get();
        $angkatanList = Angkatan::where('id_jenis_pelatihan', $jenisData['id'])
            ->where('status_angkatan', 'Dibuka')->get();
        $provinsiList = Provinsi::all();
        $kabupatenList = Kabupaten::all();
        $kunci_judul = optional($pendaftaran->angkatan)->kunci_judul ?? false;
        $aksiPerubahan = $pendaftaran->aksiPerubahan->first();

        $isEdit = true;

        return view("admin.peserta.{$jenis}.create", compact(
            'pendaftaran',
            'mentorList',
            'angkatanList',
            'provinsiList',
            'kabupatenList',
            'isEdit',
            'jenis',
            'kunci_judul',
            'aksiPerubahan'
        ));
    }

    public function store(Request $request)
    {
        try {
            // Ambil jenis dari session atau request
            $jenis = $request->jenis ?? session('jenis_pelatihan');

            if (!$jenis) {
                throw ValidationException::withMessages([
                    'jenis_pelatihan' => ['Jenis pelatihan tidak ditemukan'],
                ]);
            }

            $jenisData = $this->getJenisData($jenis);
            $jenisPelatihanId = $jenisData['id'];

            // Set id_jenis_pelatihan dari mapping
            $request->merge(['id_jenis_pelatihan' => $jenisPelatihanId]);

            // 1. CEK PESERTA BERDASARKAN NIP/NRP
            $peserta = Peserta::where('nip_nrp', $request->nip_nrp)->first();

            // 2. CEK SUDAH DAFTAR DI JENIS PELATIHAN SAMA
            if ($peserta) {
                $exists = Pendaftaran::where('id_peserta', $peserta->id)
                    ->where('id_jenis_pelatihan', $request->id_jenis_pelatihan)
                    ->exists();

                if ($exists) {
                    throw ValidationException::withMessages([
                        'nip_nrp' => ['Peserta dengan NIP/NRP ini sudah terdaftar pada jenis pelatihan yang sama.'],
                    ]);
                }
            }

            // =========================
            // VALIDASI KUOTA ANGKATAN
            // =========================
            $angkatan = Angkatan::withCount('pendaftaran')
                ->find($request->id_angkatan);

            if (!$angkatan) {
                throw ValidationException::withMessages([
                    'id_angkatan' => ['Angkatan tidak ditemukan.'],
                ]);
            }

            if ($angkatan->pendaftaran_count >= $angkatan->kuota) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'id_angkatan' => [
                                'Kuota angkatan "' . $angkatan->nama_angkatan . '" sudah penuh.'
                            ]
                        ],
                        'message' => 'Kuota angkatan "' . $angkatan->nama_angkatan . '" sudah penuh.'
                    ], 422);
                }

                throw ValidationException::withMessages([
                    'id_angkatan' => [
                        'Kuota angkatan "' . $angkatan->nama_angkatan . '" sudah penuh.'
                    ],
                ]);
            }

            // 3. VALIDASI INPUT UMUM (HANYA angkatan, nip_nrp, nama_lengkap YANG REQUIRED)
            $validated = $request->validate(
                [
                    'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
                    'id_angkatan' => 'required|exists:angkatan,id',
                    'nip_nrp' => 'required|string|max:50',
                    'nama_lengkap' => 'required|string|max:200',

                    // Semua field berikut diubah dari required menjadi nullable
                    'nama_panggilan' => 'nullable|string|max:100',
                    'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
                    'agama' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Kristen Protestan',
                    'tempat_lahir' => 'nullable|string|max:100',
                    'tanggal_lahir' => 'nullable|date',
                    'alamat_rumah' => 'nullable|string',
                    'email_pribadi' => 'nullable|email|max:100',
                    'nomor_hp' => 'nullable|string|max:20',
                    'pendidikan_terakhir' => 'nullable|in:SD,SMP,SMU,D3,D4,S1,S2,S3',
                    'bidang_studi' => 'nullable|string|max:100',
                    'bidang_keahlian' => 'nullable|string|max:100',
                    'status_perkawinan' => 'nullable|in:Belum Menikah,Menikah,Duda,Janda',
                    'nama_pasangan' => 'nullable|string|max:200',
                    'olahraga_hobi' => 'nullable|string|max:100',
                    'perokok' => 'nullable|in:Ya,Tidak',
                    'ukuran_kaos' => 'nullable|in:S,M,L,XL,XXL,XXXL',
                    'ukuran_celana' => 'nullable|in:S,M,L,XL,XXL,XXXL',
                    'ukuran_training' => 'nullable|in:S,M,L,XL,XXL,XXXL',
                    'kondisi_peserta' => 'nullable|string',
                    'file_ktp' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
                    'file_pas_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
                    'asal_instansi' => 'nullable|string|max:200',
                    'unit_kerja' => 'nullable|string|max:200',
                    'id_provinsi' => 'nullable',
                    'id_kabupaten_kota' => 'nullable',
                    'alamat_kantor' => 'nullable|string',
                    'nomor_telepon_kantor' => 'nullable|string|max:20',
                    'email_kantor' => 'nullable|email|max:100',
                    'jabatan' => 'nullable|string|max:200',
                    'pangkat' => 'nullable|string|max:50',
                    'golongan_ruang' => 'nullable|string|max:50',
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
                    'judul' => 'nullable'
                ],
                [
                    'id_jenis_pelatihan.required' => 'Jenis pelatihan wajib dipilih.',
                    'id_jenis_pelatihan.exists'   => 'Jenis pelatihan tidak valid.',
                    'id_angkatan.required' => 'Angkatan wajib dipilih.',
                    'id_angkatan.exists'   => 'Angkatan tidak valid.',
                    'nip_nrp.required' => 'NIP/NRP wajib diisi.',
                    'nip_nrp.string'   => 'NIP/NRP harus berupa teks.',
                    'nip_nrp.max'      => 'NIP/NRP maksimal 50 karakter.',
                    'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                    'nama_lengkap.string'   => 'Nama lengkap harus berupa teks.',
                    'nama_lengkap.max'      => 'Nama lengkap maksimal 200 karakter.',

                    // Pesan error untuk field lainnya (opsional)
                    'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
                    'agama.in' => 'Agama yang dipilih tidak valid.',
                    'email_pribadi.email' => 'Format email pribadi tidak valid.',
                    'status_perkawinan.in' => 'Status perkawinan yang dipilih tidak valid.',
                    'perokok.in' => 'Status perokok harus Ya atau Tidak.',
                    'ukuran_kaos.in' => 'Ukuran kaos yang dipilih tidak valid.',
                    'ukuran_celana.in' => 'Ukuran celana yang dipilih tidak valid.',
                    'ukuran_training.in' => 'Ukuran training yang dipilih tidak valid.',
                    'has_mentor.in' => 'Pilihan memiliki mentor harus Ya atau Tidak.',
                    'sudah_ada_mentor.in' => 'Pilihan sudah ada mentor harus Ya atau Tidak.',
                    'file_pas_foto.mimes' => 'Pas foto harus berupa file JPG, JPEG, atau PNG.',
                    'file_pas_foto.max' => 'Pas foto maksimal 1MB.',
                ]
            );

            // 4. AMBIL JENIS PELATIHAN UNTUK VALIDASI TAMBAHAN
            $jenisPelatihan = JenisPelatihan::find($jenisPelatihanId);
            $kode = $jenisPelatihan->kode_pelatihan;
            $additionalRules = [];

            // Semua field menjadi nullable untuk semua jenis pelatihan
            if ($kode === 'PKN_TK_II') {
                $additionalRules = [
                    'eselon' => 'nullable|string|max:50',
                    'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:1024',
                    'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:1024',
                    'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:1024',
                ];
            }

            if ($kode === 'LATSAR') {
                $additionalRules = [
                    'nomor_sk_cpns' => 'nullable|string|max:100',
                    'tanggal_sk_cpns' => 'nullable|date',
                    'file_sk_cpns' => 'nullable|file|mimes:pdf|max:1024',
                    'file_spmt' => 'nullable|file|mimes:pdf|max:1024',
                    'file_skp' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:1024',
                    'pangkat' => 'nullable|string|max:50',
                    'sudah_ada_mentor' => 'nullable|in:Ya,Tidak',
                    'nomor_rekening_mentor' => 'nullable|string|max:200',
                    'npwp_mentor' => 'nullable|string|max:50',
                ];
            }

            if ($kode === 'PKA' || $kode === 'PKP') {
                $additionalRules = [
                    'eselon' => 'nullable|string|max:50',
                    'tanggal_sk_jabatan' => 'nullable|date',
                    'tahun_lulus_pkp_pim_iv' => 'nullable|integer',
                    'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:1024',
                    'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_pernyataan_administrasi' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:1024',
                    'file_persetujuan_mentor' => 'nullable|file|mimes:pdf|max:1024',
                    'file_ktp' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
                    'sudah_ada_mentor' => 'nullable|in:Ya,Tidak',
                ];
            }

            // Jika sudah ada mentor dan mode pilih
            if ($request->sudah_ada_mentor === 'Ya') {
                $additionalRules['mentor_mode'] = 'nullable|in:pilih,tambah';

                if ($request->mentor_mode === 'pilih') {
                    $additionalRules['id_mentor'] = 'nullable|exists:mentor,id';
                } elseif ($request->mentor_mode === 'tambah') {
                    $additionalRules['nama_mentor_baru'] = 'nullable|string|max:200';
                    $additionalRules['jabatan_mentor_baru'] = 'nullable|string|max:200';
                    $additionalRules['nomor_rekening_mentor_baru'] = 'nullable|string|max:200';
                    $additionalRules['npwp_mentor_baru'] = 'nullable|string|max:50';
                }
            }

            // Jalankan validasi tambahan
            if (!empty($additionalRules)) {
                $request->validate($additionalRules);
            }

            // 5. SIMPAN FILE UPLOADS
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
            $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
            $angkatan = Angkatan::find($request->id_angkatan);
            $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan);
            $nip = $request->nip_nrp;

            // Buat struktur folder: Berkas/Tahun/JenisPelatihan/Angkatan/NIP
            $folderPath = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";

            $files = [];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);

                    // Untuk file_pas_foto yang sudah di-crop, gunakan nama yang konsisten
                    if ($field === 'file_pas_foto') {
                        $fileName = 'pas_foto.jpg'; // Nama konsisten untuk pas foto

                        // Validasi khusus untuk pas foto
                        if (!$file->isValid()) {
                            throw ValidationException::withMessages([
                                'file_pas_foto' => ['File pas foto tidak valid.']
                            ]);
                        }

                        // Validasi ukuran maksimal 2MB (lebih longgar karena cropper menghasilkan JPG)
                        if ($file->getSize() > 2048 * 1024) {
                            throw ValidationException::withMessages([
                                'file_pas_foto' => ['Pas foto maksimal 2MB.']
                            ]);
                        }

                        // Validasi tipe file
                        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!in_array($file->getMimeType(), $allowedMimes)) {
                            throw ValidationException::withMessages([
                                'file_pas_foto' => ['Pas foto harus berupa JPG, JPEG, atau PNG.']
                            ]);
                        }
                    } else {
                        // Untuk file lainnya, gunakan nama asli
                        $extension = $file->getClientOriginalExtension();
                        $fieldName = str_replace('file_', '', $field);
                        $fileName = $fieldName . '.' . $extension;
                    }

                    // PATH DI GOOGLE DRIVE
                    $drivePath = "{$folderPath}/{$fileName}";

                    try {
                        // UPLOAD KE GOOGLE DRIVE
                        Storage::disk('google')->put(
                            $drivePath,
                            file_get_contents($file)
                        );

                        $files[$field] = $drivePath;
                    } catch (\Exception $e) {
                        throw ValidationException::withMessages([
                            $field => ['Gagal mengunggah file: ' . $e->getMessage()]
                        ]);
                    }
                }
            }

            // 6. SIMPAN/UPDATE PESERTA (REUSE JIKA SUDAH ADA)
            if (!$peserta) {
                // Buat peserta baru
                $peserta = Peserta::create([
                    'nip_nrp' => $request->nip_nrp,
                    'nama_lengkap' => $request->nama_lengkap,
                    'nama_panggilan' => $request->nama_panggilan ?? null,
                    'jenis_kelamin' => $request->jenis_kelamin ?? null,
                    'agama' => $request->agama ?? null,
                    'tempat_lahir' => $request->tempat_lahir ?? null,
                    'tanggal_lahir' => $request->tanggal_lahir ?? null,
                    'alamat_rumah' => $request->alamat_rumah ?? null,
                    'email_pribadi' => $request->email_pribadi ?? null,
                    'nomor_hp' => $request->nomor_hp ?? null,
                    'pendidikan_terakhir' => $request->pendidikan_terakhir ?? null,
                    'bidang_studi' => $request->bidang_studi ?? null,
                    'bidang_keahlian' => $request->bidang_keahlian ?? null,
                    'status_perkawinan' => $request->status_perkawinan ?? null,
                    'nama_pasangan' => $request->nama_pasangan ?? null,
                    'olahraga_hobi' => $request->olahraga_hobi ?? null,
                    'perokok' => $request->perokok ?? null,
                    'ukuran_kaos' => $request->ukuran_kaos ?? null,
                    'ukuran_celana' => $request->ukuran_celana ?? null,
                    'ukuran_training' => $request->ukuran_training ?? null,
                    'kondisi_peserta' => $request->kondisi_peserta ?? null,
                    'file_ktp' => $files['file_ktp'] ?? null,
                    'file_pas_foto' => $files['file_pas_foto'] ?? null,
                    'status_aktif' => true,
                ]);
            } else {
                $peserta->update([
                    'nama_lengkap' => $request->nama_lengkap,
                    'nama_panggilan' => $request->nama_panggilan ?? $peserta->nama_panggilan,
                    'jenis_kelamin' => $request->jenis_kelamin ?? $peserta->jenis_kelamin,
                    'agama' => $request->agama ?? $peserta->agama,
                    'tempat_lahir' => $request->tempat_lahir ?? $peserta->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir ?? $peserta->tanggal_lahir,
                    'alamat_rumah' => $request->alamat_rumah ?? $peserta->alamat_rumah,
                    'email_pribadi' => $request->email_pribadi ?? $peserta->email_pribadi,
                    'nomor_hp' => $request->nomor_hp ?? $peserta->nomor_hp,
                    'pendidikan_terakhir' => $request->pendidikan_terakhir ?? $peserta->pendidikan_terakhir,
                    'bidang_studi' => $request->bidang_studi ?? $peserta->bidang_studi,
                    'bidang_keahlian' => $request->bidang_keahlian ?? $peserta->bidang_keahlian,
                    'status_perkawinan' => $request->status_perkawinan ?? $peserta->status_perkawinan,
                    'nama_pasangan' => $request->nama_pasangan ?? $peserta->nama_pasangan,
                    'olahraga_hobi' => $request->olahraga_hobi ?? $peserta->olahraga_hobi,
                    'perokok' => $request->perokok ?? $peserta->perokok,
                    'ukuran_kaos' => $request->ukuran_kaos ?? $peserta->ukuran_kaos,
                    'ukuran_celana' => $request->ukuran_celana ?? $peserta->ukuran_celana,
                    'ukuran_training' => $request->ukuran_training ?? $peserta->ukuran_training,
                    'kondisi_peserta' => $request->kondisi_peserta ?? $peserta->kondisi_peserta,
                    'file_ktp' => $files['file_ktp'] ?? $peserta->file_ktp,
                    'file_pas_foto' => $files['file_pas_foto'] ?? $peserta->file_pas_foto,
                ]);
            }

            // 7. SIMPAN/UPDATE KEPEGAWAIAN PESERTA (updateOrCreate)
            $provinsi = $request->id_provinsi ? Provinsi::where('id', $request->id_provinsi)->first() : null;
            $kabupaten = $request->id_kabupaten_kota ? Kabupaten::where('id', $request->id_kabupaten_kota)->first() : null;

            KepegawaianPeserta::updateOrCreate(
                ['id_peserta' => $peserta->id],
                [
                    'asal_instansi' => $request->asal_instansi ?? null,
                    'unit_kerja' => $request->unit_kerja ?? null,
                    'id_provinsi' => $provinsi?->id,
                    'id_kabupaten_kota' => $kabupaten?->id,
                    'alamat_kantor' => $request->alamat_kantor ?? null,
                    'nomor_telepon_kantor' => $request->nomor_telepon_kantor ?? null,
                    'email_kantor' => $request->email_kantor ?? null,
                    'jabatan' => $request->jabatan ?? null,
                    'pangkat' => $request->pangkat ?? null,
                    'golongan_ruang' => $request->golongan_ruang ?? null,
                    'eselon' => $request->eselon ?? null,
                    'tanggal_sk_jabatan' => $request->tanggal_sk_jabatan ?? null,
                    'file_sk_jabatan' => $files['file_sk_jabatan'] ?? null,
                    'file_sk_pangkat' => $files['file_sk_pangkat'] ?? null,
                    'nomor_sk_cpns' => $request->nomor_sk_cpns ?? null,
                    'nomor_sk_terakhir' => $request->nomor_sk_terakhir ?? null,
                    'tanggal_sk_cpns' => $request->tanggal_sk_cpns ?? null,
                    'file_sk_cpns' => $files['file_sk_cpns'] ?? null,
                    'file_spmt' => $files['file_spmt'] ?? null,
                    'file_skp' => $files['file_skp'] ?? null,
                    'tahun_lulus_pkp_pim_iv' => $request->tahun_lulus_pkp_pim_iv ?? null,
                ]
            );

            // 8. SIMPAN PENDAFTARAN BARU
            $pendaftaran = Pendaftaran::create([
                'id_peserta' => $peserta->id,
                'id_jenis_pelatihan' => $jenisPelatihanId,
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
                'file_persetujuan_mentor' => $files['file_persetujuan_mentor'] ?? null,
                'status_pendaftaran' => 'Menunggu Verifikasi',
                'tanggal_daftar' => now(),
            ]);

            // 9. SIMPAN MENTOR JIKA ADA
            if ($request->sudah_ada_mentor === 'Ya') {
                $mentor = null;

                if ($request->mentor_mode === 'pilih' && $request->id_mentor) {
                    // Gunakan mentor yang dipilih
                    $mentor = Mentor::find($request->id_mentor);
                } elseif ($request->mentor_mode === 'tambah') {
                    // Buat mentor baru hanya jika ada data
                    if ($request->nama_mentor_baru && $request->jabatan_mentor_baru) {
                        $mentor = Mentor::create([
                            'nama_mentor' => $request->nama_mentor_baru,
                            'jabatan_mentor' => $request->jabatan_mentor_baru,
                            'nomor_rekening' => $request->nomor_rekening_mentor_baru,
                            'npwp_mentor' => $request->npwp_mentor_baru,
                            'status_aktif' => true,
                        ]);
                    }
                }

                if ($mentor) {
                    // Simpan hubungan peserta-mentor
                    PesertaMentor::create([
                        'id_pendaftaran' => $pendaftaran->id,
                        'id_mentor' => $mentor->id,
                        'tanggal_penunjukan' => now(),
                        'status_mentoring' => 'Ditugaskan',
                    ]);
                }
            }

            $angkatanNama = $angkatan->nama_angkatan;
            aktifitas("Menambahkan Peserta {$jenisPelatihan->nama_pelatihan} - {$angkatanNama}", $peserta);

            // 10. RESPONSE
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil disimpan!',
                    'pendaftaran_id' => $pendaftaran->id,
                    'redirect_url' => route('peserta.index', ['jenis' => $jenis])
                ], 200);
            }

            // Simpan ke session untuk redirect biasa (non-AJAX)
            session(['pendaftaran_id' => $pendaftaran->id]);

            return redirect()->route('peserta.index', ['jenis' => $jenis])
                ->with('success', 'Pendaftaran berhasil disimpan!')
                ->with('pendaftaran_id', $pendaftaran->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Response error untuk AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('validation_failed', true);
        } catch (\Exception $e) {
            // Response error untuk AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Method untuk update
    public function update(Request $request, $jenis, $id)
    {
        try {
            $jenisData = $this->getJenisData($jenis);
            $jenisPelatihanId = $jenisData['id'];

            $pendaftaran = Pendaftaran::with(['peserta', 'peserta.kepegawaianPeserta'])
                ->findOrFail($id);

            // Verifikasi jenis pelatihan sesuai
            if ($pendaftaran->id_jenis_pelatihan != $jenisPelatihanId) {
                abort(404, 'Data tidak ditemukan untuk jenis pelatihan ini');
            }

            $peserta = $pendaftaran->peserta;
            $kepegawaian = $peserta->kepegawaianPeserta;

            // 1. VALIDASI INPUT UMUM (HANYA angkatan, nip_nrp, nama_lengkap YANG REQUIRED)
            $validated = $request->validate(
                [
                    'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
                    'id_angkatan' => 'required|exists:angkatan,id',
                    'nip_nrp' => 'required|string|max:50',
                    'nama_lengkap' => 'required|string|max:200',

                    // Semua field berikut nullable
                    'nama_panggilan' => 'nullable|string|max:100',
                    'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
                    'agama' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Kristen Protestan',
                    'tempat_lahir' => 'nullable|string|max:100',
                    'tanggal_lahir' => 'nullable|date',
                    'alamat_rumah' => 'nullable|string',
                    'email_pribadi' => 'nullable|email|max:100',
                    'nomor_hp' => 'nullable|string|max:20',
                    'pendidikan_terakhir' => 'nullable|in:SD,SMP,SMU,D3,D4,S1,S2,S3',
                    'bidang_studi' => 'nullable|string|max:100',
                    'bidang_keahlian' => 'nullable|string|max:100',
                    'status_perkawinan' => 'nullable|in:Belum Menikah,Menikah,Duda,Janda',
                    'nama_pasangan' => 'nullable|string|max:200',
                    'olahraga_hobi' => 'nullable|string|max:100',
                    'perokok' => 'nullable|in:Ya,Tidak',
                    'ukuran_kaos' => 'nullable|in:S,M,L,XL,XXL,XXXL',
                    'ukuran_celana' => 'nullable|in:S,M,L,XL,XXL,XXXL',
                    'ukuran_training' => 'nullable|in:S,M,L,XL,XXL,XXXL',
                    'kondisi_peserta' => 'nullable|string',
                    'file_ktp' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
                    'file_pas_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
                    'asal_instansi' => 'nullable|string|max:200',
                    'unit_kerja' => 'nullable|string|max:200',
                    'id_provinsi' => 'nullable',
                    'id_kabupaten_kota' => 'nullable',
                    'alamat_kantor' => 'nullable|string',
                    'nomor_telepon_kantor' => 'nullable|string|max:20',
                    'email_kantor' => 'nullable|email|max:100',
                    'jabatan' => 'nullable|string|max:200',
                    'pangkat' => 'nullable|string|max:50',
                    'golongan_ruang' => 'nullable|string|max:50',
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
                    'file' => 'nullable|file|mimes:pdf|max:1024',
                    'judul' => 'nullable'
                ],
                [
                    'id_jenis_pelatihan.required' => 'Jenis pelatihan wajib dipilih.',
                    'id_jenis_pelatihan.exists'   => 'Jenis pelatihan tidak valid.',
                    'id_angkatan.required' => 'Angkatan wajib dipilih.',
                    'id_angkatan.exists'   => 'Angkatan tidak valid.',
                    'nip_nrp.required' => 'NIP/NRP wajib diisi.',
                    'nip_nrp.string'   => 'NIP/NRP harus berupa teks.',
                    'nip_nrp.max'      => 'NIP/NRP maksimal 50 karakter.',
                    'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                    'nama_lengkap.string'   => 'Nama lengkap harus berupa teks.',
                    'nama_lengkap.max'      => 'Nama lengkap maksimal 200 karakter.',
                    'file_pas_foto.mimes' => 'Pas foto harus berupa file JPG, JPEG, atau PNG.',
                    'file_pas_foto.max' => 'Pas foto maksimal 1MB.',
                ]
            );

            // 2. AMBIL JENIS PELATIHAN UNTUK VALIDASI TAMBAHAN
            $jenisPelatihan = JenisPelatihan::find($jenisPelatihanId);
            $kode = $jenisPelatihan->kode_pelatihan;
            $additionalRules = [];

            // Semua field menjadi nullable untuk update mode
            if ($kode === 'PKN_TK_II') {
                $additionalRules = [
                    'eselon' => 'nullable|string|max:50',
                    'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:1024',
                    'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:1024',
                    'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:1024',
                ];
            }

            if ($kode === 'LATSAR') {
                $additionalRules = [
                    'nomor_sk_cpns' => 'nullable|string|max:100',
                    'tanggal_sk_cpns' => 'nullable|date',
                    'file_sk_cpns' => 'nullable|file|mimes:pdf|max:1024',
                    'file_spmt' => 'nullable|file|mimes:pdf|max:1024',
                    'file_skp' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:1024',
                    'pangkat' => 'nullable|string|max:50',
                    'sudah_ada_mentor' => 'nullable|in:Ya,Tidak',
                    'nomor_rekening_mentor' => 'nullable|string|max:200',
                    'npwp_mentor' => 'nullable|string|max:50',
                ];
            }

            if ($kode === 'PKA' || $kode === 'PKP') {
                $additionalRules = [
                    'eselon' => 'nullable|string|max:50',
                    'tanggal_sk_jabatan' => 'nullable|date',
                    'tahun_lulus_pkp_pim_iv' => 'nullable|integer',
                    'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:1024',
                    'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_pernyataan_administrasi' => 'nullable|file|mimes:pdf|max:1024',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:1024',
                    'file_persetujuan_mentor' => 'nullable|file|mimes:pdf|max:1024',
                    'file_ktp' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
                    'sudah_ada_mentor' => 'nullable|in:Ya,Tidak',
                ];
            }

            // Jika sudah ada mentor dan mode pilih
            if ($request->sudah_ada_mentor === 'Ya') {
                $additionalRules['mentor_mode'] = 'nullable|in:pilih,tambah';

                if ($request->mentor_mode === 'pilih') {
                    $additionalRules['id_mentor'] = 'nullable|exists:mentor,id';
                } elseif ($request->mentor_mode === 'tambah') {
                    $additionalRules['nama_mentor_baru'] = 'nullable|string|max:200';
                    $additionalRules['jabatan_mentor_baru'] = 'nullable|string|max:200';
                    $additionalRules['nomor_rekening_mentor_baru'] = 'nullable|string|max:200';
                    $additionalRules['npwp_mentor_baru'] = 'nullable|string|max:50';
                }
            }

            // Jalankan validasi tambahan
            if (!empty($additionalRules)) {
                $request->validate($additionalRules);
            }

            // 3. SIMPAN FILE UPLOADS DENGAN STRUKTUR FOLDER BARU
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
                'file_persetujuan_mentor',
                'file',
            ];

            // Ambil data untuk struktur folder
            $tahun = date('Y');
            $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
            $angkatan = Angkatan::find($request->id_angkatan);
            $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan);
            $nip = $request->nip_nrp;

            // Buat struktur folder: Berkas/Tahun/JenisPelatihan/Angkatan/NIP
            $folderPath = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";

            $files = [];

            foreach ($fileFields as $field) {
                // JIKA ADA FILE BARU
                if ($request->hasFile($field)) {
                    $file = $request->file($field);

                    // Untuk file_pas_foto yang sudah di-crop, gunakan nama yang konsisten
                    if ($field === 'file_pas_foto') {
                        $fileName = 'pas_foto.jpg'; // Nama konsisten untuk pas foto

                        // Validasi khusus untuk pas foto
                        if (!$file->isValid()) {
                            throw ValidationException::withMessages([
                                'file_pas_foto' => ['File pas foto tidak valid.']
                            ]);
                        }

                        // Validasi ukuran maksimal 2MB
                        if ($file->getSize() > 2048 * 1024) {
                            throw ValidationException::withMessages([
                                'file_pas_foto' => ['Pas foto maksimal 2MB.']
                            ]);
                        }

                        // Validasi tipe file
                        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!in_array($file->getMimeType(), $allowedMimes)) {
                            throw ValidationException::withMessages([
                                'file_pas_foto' => ['Pas foto harus berupa JPG, JPEG, atau PNG.']
                            ]);
                        }
                    } else {
                        $extension = $file->getClientOriginalExtension();
                        $fieldName = str_replace('file_', '', $field);
                        $fileName = $fieldName . '.' . $extension;
                    }

                    // PATH TETAP
                    $drivePath = "{$folderPath}/{$fileName}";

                    try {
                        // OPTIONAL: hapus file lama (rapi)
                        if ($field === 'file_pas_foto' && $peserta?->file_pas_foto) {
                            Storage::disk('google')->delete($peserta->file_pas_foto);
                        } elseif ($kepegawaian && isset($kepegawaian->$field)) {
                            Storage::disk('google')->delete($kepegawaian->$field);
                        } elseif ($pendaftaran->$field) {
                            Storage::disk('google')->delete($pendaftaran->$field);
                        }

                        // UPLOAD → AUTO OVERWRITE
                        Storage::disk('google')->put(
                            $drivePath,
                            file_get_contents($file)
                        );

                        $files[$field] = $drivePath;
                    } catch (\Exception $e) {
                        throw ValidationException::withMessages([
                            $field => ['Gagal mengunggah file: ' . $e->getMessage()]
                        ]);
                    }
                }
                // JIKA TIDAK ADA FILE BARU → PAKAI FILE LAMA
                else {
                    if ($field === 'file_ktp' && $peserta?->file_ktp) {
                        $files[$field] = $peserta->file_ktp;
                    } elseif ($field === 'file_pas_foto' && $peserta?->file_pas_foto) {
                        $files[$field] = $peserta->file_pas_foto;
                    } elseif ($kepegawaian && isset($kepegawaian->$field)) {
                        $files[$field] = $kepegawaian->$field;
                    } elseif ($pendaftaran->$field) {
                        $files[$field] = $pendaftaran->$field;
                    }
                }
            }

            // 4. UPDATE PESERTA
            $pesertaData = [
                'nip_nrp' => $request->nip_nrp,
                'nama_lengkap' => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan ?? $peserta->nama_panggilan,
                'jenis_kelamin' => $request->jenis_kelamin ?? $peserta->jenis_kelamin,
                'agama' => $request->agama ?? $peserta->agama,
                'tempat_lahir' => $request->tempat_lahir ?? $peserta->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir ?? $peserta->tanggal_lahir,
                'alamat_rumah' => $request->alamat_rumah ?? $peserta->alamat_rumah,
                'email_pribadi' => $request->email_pribadi ?? $peserta->email_pribadi,
                'nomor_hp' => $request->nomor_hp ?? $peserta->nomor_hp,
                'pendidikan_terakhir' => $request->pendidikan_terakhir ?? $peserta->pendidikan_terakhir,
                'bidang_studi' => $request->bidang_studi ?? $peserta->bidang_studi,
                'bidang_keahlian' => $request->bidang_keahlian ?? $peserta->bidang_keahlian,
                'status_perkawinan' => $request->status_perkawinan ?? $peserta->status_perkawinan,
                'nama_pasangan' => $request->nama_pasangan ?? $peserta->nama_pasangan,
                'olahraga_hobi' => $request->olahraga_hobi ?? $peserta->olahraga_hobi,
                'perokok' => $request->perokok ?? $peserta->perokok,
                'ukuran_kaos' => $request->ukuran_kaos ?? $peserta->ukuran_kaos,
                'ukuran_celana' => $request->ukuran_celana ?? $peserta->ukuran_celana,
                'ukuran_training' => $request->ukuran_training ?? $peserta->ukuran_training,
                'kondisi_peserta' => $request->kondisi_peserta ?? $peserta->kondisi_peserta,
                'status_aktif' => true,
            ];

            // Tambahkan file jika ada
            if (isset($files['file_ktp'])) {
                $pesertaData['file_ktp'] = $files['file_ktp'];
            }
            if (isset($files['file_pas_foto'])) {
                $pesertaData['file_pas_foto'] = $files['file_pas_foto'];
            }

            $peserta->update($pesertaData);

            // 5. UPDATE KEPEGAWAIAN PESERTA
            $provinsi = $request->id_provinsi ? Provinsi::where('id', $request->id_provinsi)->first() : null;
            $kabupaten = $request->id_kabupaten_kota ? Kabupaten::where('id', $request->id_kabupaten_kota)->first() : null;

            $kepegawaianData = [
                'asal_instansi' => $request->asal_instansi ?? $kepegawaian->asal_instansi,
                'unit_kerja' => $request->unit_kerja ?? $kepegawaian->unit_kerja,
                'id_provinsi' => $provinsi?->id ?? $kepegawaian->id_provinsi,
                'id_kabupaten_kota' => $kabupaten?->id ?? $kepegawaian->id_kabupaten_kota,
                'alamat_kantor' => $request->alamat_kantor ?? $kepegawaian->alamat_kantor,
                'nomor_telepon_kantor' => $request->nomor_telepon_kantor ?? $kepegawaian->nomor_telepon_kantor,
                'email_kantor' => $request->email_kantor ?? $kepegawaian->email_kantor,
                'jabatan' => $request->jabatan ?? $kepegawaian->jabatan,
                'pangkat' => $request->pangkat ?? $kepegawaian->pangkat,
                'golongan_ruang' => $request->golongan_ruang ?? $kepegawaian->golongan_ruang,
                'eselon' => $request->eselon ?? $kepegawaian->eselon,
                'tanggal_sk_jabatan' => $request->tanggal_sk_jabatan ?? $kepegawaian->tanggal_sk_jabatan,
                'nomor_sk_cpns' => $request->nomor_sk_cpns ?? $kepegawaian->nomor_sk_cpns,
                'nomor_sk_terakhir' => $request->nomor_sk_terakhir ?? $kepegawaian->nomor_sk_terakhir,
                'tanggal_sk_cpns' => $request->tanggal_sk_cpns ?? $kepegawaian->tanggal_sk_cpns,
                'tahun_lulus_pkp_pim_iv' => $request->tahun_lulus_pkp_pim_iv ?? $kepegawaian->tahun_lulus_pkp_pim_iv,
            ];

            // Tambahkan file jika ada
            if (isset($files['file_sk_jabatan'])) {
                $kepegawaianData['file_sk_jabatan'] = $files['file_sk_jabatan'];
            }
            if (isset($files['file_sk_pangkat'])) {
                $kepegawaianData['file_sk_pangkat'] = $files['file_sk_pangkat'];
            }
            if (isset($files['file_sk_cpns'])) {
                $kepegawaianData['file_sk_cpns'] = $files['file_sk_cpns'];
            }
            if (isset($files['file_spmt'])) {
                $kepegawaianData['file_spmt'] = $files['file_spmt'];
            }
            if (isset($files['file_skp'])) {
                $kepegawaianData['file_skp'] = $files['file_skp'];
            }

            KepegawaianPeserta::updateOrCreate(
                ['id_peserta' => $peserta->id],
                $kepegawaianData
            );

            // 6. UPDATE PENDAFTARAN
            $pendaftaranData = [
                'id_angkatan' => $request->id_angkatan,
            ];

            // Tambahkan file jika ada
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
                    $pendaftaranData[$field] = $files[$field];
                }
            }

            $pendaftaran->update($pendaftaranData);

            // 7. UPDATE MENTOR JIKA ADA
            $pesertaMentor = PesertaMentor::where('id_pendaftaran', $pendaftaran->id)->first();

            if ($request->sudah_ada_mentor === 'Ya') {
                $mentor = null;

                if ($request->mentor_mode === 'pilih' && $request->id_mentor) {
                    // Gunakan mentor yang dipilih
                    $mentor = Mentor::find($request->id_mentor);
                } elseif ($request->mentor_mode === 'tambah') {
                    // Buat mentor baru hanya jika ada data
                    if ($request->nama_mentor_baru && $request->jabatan_mentor_baru) {
                        $mentor = Mentor::create([
                            'nama_mentor' => $request->nama_mentor_baru,
                            'jabatan_mentor' => $request->jabatan_mentor_baru,
                            'nomor_rekening' => $request->nomor_rekening_mentor_baru,
                            'npwp_mentor' => $request->npwp_mentor_baru,
                            'status_aktif' => true,
                        ]);
                    }
                }

                if ($mentor) {
                    if ($pesertaMentor) {
                        // Update mentor yang sudah ada
                        $pesertaMentor->update([
                            'id_mentor' => $mentor->id,
                            'status_mentoring' => 'Ditugaskan',
                        ]);
                    } else {
                        // Buat baru
                        PesertaMentor::create([
                            'id_pendaftaran' => $pendaftaran->id,
                            'id_mentor' => $mentor->id,
                            'tanggal_penunjukan' => now(),
                            'status_mentoring' => 'Ditugaskan',
                        ]);
                    }
                }
            } else {
                // Hapus mentor jika ada
                if ($pesertaMentor) {
                    $pesertaMentor->delete();
                }
            }

            // 8. SIMPAN AKSI PERUBAHAN JIKA ADA
            if ($request->filled('judul')) {
                AksiPerubahan::updateOrCreate(
                    ['id_pendaftar' => $pendaftaran->id],
                    [
                        'judul' => $request->judul,
                        'file'  => $files['file'] ?? optional(
                            AksiPerubahan::where('id_pendaftar', $pendaftaran->id)->first()
                        )->file,
                    ]
                );
            }

            $angkatanNama = $angkatan->nama_angkatan;
            aktifitas("Memperbarui Data Peserta {$jenisPelatihan->nama_pelatihan} - {$angkatanNama}", $peserta);

            // 9. RESPONSE
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data peserta berhasil diperbarui!',
                    'redirect_url' => route('peserta.index', ['jenis' => $jenis])
                ], 200);
            }

            return redirect()->route('peserta.index', ['jenis' => $jenis])
                ->with('success', 'Data peserta berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('validation_failed', true);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($jenis, $id)
    {
        try {
            DB::transaction(function () use ($jenis, $id) {

                // =========================
                // 1. AMBIL DATA PENDAFTARAN
                // =========================
                $jenisData = $this->getJenisData($jenis);

                $pendaftaran = Pendaftaran::with([
                    'peserta',
                    'peserta.kepegawaianPeserta',
                    'pesertaMentor',
                    'angkatan',
                    'jenisPelatihan',
                ])->lockForUpdate()->findOrFail($id);

                if ($pendaftaran->id_jenis_pelatihan != $jenisData['id']) {
                    abort(404, 'Data tidak ditemukan');
                }

                $peserta = $pendaftaran->peserta;
                $angkatan = $pendaftaran->angkatan;
                $jenisPelatihan = $pendaftaran->jenisPelatihan;

                // =========================
                // 2. KUMPULKAN FILE
                // =========================
                $filesToDelete = [];

                if ($peserta) {
                    $filesToDelete[] = $peserta->file_ktp;
                    $filesToDelete[] = $peserta->file_pas_foto;
                }

                if ($peserta && $peserta->kepegawaianPeserta) {
                    $k = $peserta->kepegawaianPeserta;
                    $filesToDelete = array_merge($filesToDelete, [
                        $k->file_sk_jabatan,
                        $k->file_sk_pangkat,
                        $k->file_sk_cpns,
                        $k->file_spmt,
                        $k->file_skp,
                    ]);
                }

                $pendaftaranFiles = [
                    'file_surat_tugas',
                    'file_surat_kesediaan',
                    'file_pakta_integritas',
                    'file_surat_komitmen',
                    'file_surat_kelulusan_seleksi',
                    'file_surat_sehat',
                    'file_surat_bebas_narkoba',
                    'file_surat_pernyataan_administrasi',
                    'file_sertifikat_penghargaan',
                    'file_persetujuan_mentor',
                ];

                foreach ($pendaftaranFiles as $field) {
                    if (!empty($pendaftaran->$field)) {
                        $filesToDelete[] = $pendaftaran->$field;
                    }
                }

                // =========================
                // 3. HAPUS FILE (SETELAH COMMIT)
                // =========================
                DB::afterCommit(function () use ($filesToDelete, $peserta, $angkatan, $jenisPelatihan) {

                    foreach (array_filter($filesToDelete) as $file) {
                        Storage::disk('google')->delete($file);
                    }

                    if ($peserta) {
                        $folderPath = "Berkas/" . date('Y') . "/" .
                            str_replace(' ', '_', $jenisPelatihan->kode_pelatihan) . "/" .
                            str_replace(' ', '_', $angkatan->nama_angkatan) . "/" .
                            $peserta->nip_nrp;

                        Storage::disk('google')->deleteDirectory($folderPath);
                    }
                });

                // =========================
                // 4. HAPUS RELASI
                // =========================
                PesertaMentor::where('id_pendaftaran', $pendaftaran->id)->delete();

                if ($peserta && $peserta->kepegawaianPeserta) {
                    $peserta->kepegawaianPeserta->delete();
                }

                $pendaftaran->delete();

                // =========================
                // 5. RAPINKAN NDH (AMAN)
                // =========================
                $pesertaBerNDH = Peserta::whereHas('pendaftaran', function ($q) use ($angkatan) {
                    $q->where('id_angkatan', $angkatan->id);
                })
                    ->whereNotNull('ndh')
                    ->orderBy('ndh')
                    ->lockForUpdate()
                    ->get();

                $no = 1;
                foreach ($pesertaBerNDH as $p) {
                    $p->update(['ndh' => $no++]);
                }
                // =========================
                // 6. HAPUS PESERTA & USER
                // =========================
                if ($peserta) {
                    $jumlahPendaftaran = Pendaftaran::where('id_peserta', $peserta->id)->count();

                    if ($jumlahPendaftaran === 0) {
                        User::where('peserta_id', $peserta->id)->delete();
                        $peserta->delete();
                    }
                }

                // =========================
                // 7. LOG
                // =========================
                aktifitas(
                    "Menghapus Peserta {$jenisPelatihan->nama_pelatihan} - {$angkatan->nama_angkatan}",
                    $peserta
                );
            });

            // =========================
            // 8. RESPONSE
            // =========================
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data peserta berhasil dihapus & NDH dirapikan'
                ]);
            }

            return redirect()
                ->route('peserta.index', ['jenis' => $jenis])
                ->with('success', 'Data peserta berhasil dihapus & NDH dirapikan');
        } catch (\Throwable $e) {

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }


    public function showSwapForm(Request $request, $jenis, $id)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $jenisData = $this->getJenisData($jenis);
        $jenisPelatihanId = $jenisData['id'];

        // Get current peserta
        $pendaftaranAsal = Pendaftaran::with(['peserta', 'angkatan', 'jenisPelatihan'])
            ->findOrFail($id);

        // Verify jenis pelatihan
        if ($pendaftaranAsal->id_jenis_pelatihan != $jenisPelatihanId) {
            abort(404, 'Data tidak ditemukan untuk jenis pelatihan ini');
        }

        // Get all other angkatan (excluding current angkatan)
        $angkatanTujuanList = Angkatan::where('id_jenis_pelatihan', $jenisPelatihanId)
            ->where('id', '!=', $pendaftaranAsal->id_angkatan)
            ->where('status_angkatan', 'Dibuka')
            ->orderBy('tahun', 'desc')
            ->orderBy('nama_angkatan', 'asc')
            ->get();

        return view('admin.peserta.swap', compact(
            'pendaftaranAsal',
            'angkatanTujuanList',
            'jenis'
        ));
    }

    /**
     * Get peserta list for selected angkatan (for dropdown).
     */
    /**
     * Get peserta list for selected angkatan (for dropdown).
     */
    public function getPesertaAngkatan(Request $request, $jenis = null)
    {
        if (auth()->user()->role->name !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Debug: Log request
        \Log::info('getPesertaAngkatan request:', $request->all());

        $request->validate([
            'angkatan_id' => 'required|exists:angkatan,id',
            'exclude_peserta_id' => 'nullable|exists:peserta,id'
        ]);

        $angkatanId = $request->angkatan_id;
        $excludePesertaId = $request->exclude_peserta_id;

        try {
            // Debug: Check angkatan exists
            $angkatan = Angkatan::find($angkatanId);
            \Log::info('Angkatan found:', ['id' => $angkatanId, 'nama' => $angkatan->nama_angkatan ?? 'not found']);

            // Get all peserta in the selected angkatan
            $pesertaList = Pendaftaran::with(['peserta', 'peserta.kepegawaianPeserta'])
                ->where('id_angkatan', $angkatanId)
                ->whereHas('peserta', function ($query) use ($excludePesertaId) {
                    if ($excludePesertaId) {
                        $query->where('id', '!=', $excludePesertaId);
                    }
                })
                ->get()
                ->map(function ($pendaftaran) {
                    return [
                        'id' => $pendaftaran->id,
                        'peserta_id' => $pendaftaran->peserta->id,
                        'nama' => $pendaftaran->peserta->nama_lengkap ?? 'Nama tidak tersedia',
                        'nip_nrp' => $pendaftaran->peserta->nip_nrp ?? '-',
                        'ndh' => $pendaftaran->peserta->ndh,
                        'asal_instansi' => $pendaftaran->peserta->kepegawaianPeserta->asal_instansi ?? '-'
                    ];
                });

            \Log::info('Peserta list found:', ['count' => count($pesertaList)]);

            return response()->json([
                'success' => true,
                'data' => $pesertaList,
                'count' => count($pesertaList)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getPesertaAngkatan:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Process swapping peserta between angkatan (NDH ikut angkatan).
     */
    public function swapAngkatan(Request $request, $jenis, $id)
    {
        if (auth()->user()->role->name !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat melakukan tindakan ini.'
            ], 403);
        }

        $request->validate([
            'angkatan_tujuan_id' => 'required|exists:angkatan,id',
            'peserta_tujuan_id' => 'required|exists:pendaftaran,id',
            'catatan_swap' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            $jenisData = $this->getJenisData($jenis);
            $jenisPelatihanId = $jenisData['id'];

            // Get peserta asal (current peserta yang akan ditukar)
            $pendaftaranAsal = Pendaftaran::with([
                'peserta',
                'angkatan',
                'jenisPelatihan',
                'peserta.kepegawaianPeserta'
            ])->findOrFail($id);

            // Verify jenis pelatihan
            if ($pendaftaranAsal->id_jenis_pelatihan != $jenisPelatihanId) {
                throw new \Exception('Data tidak ditemukan untuk jenis pelatihan ini');
            }

            // Get peserta tujuan (peserta yang akan ditukar)
            $pendaftaranTujuan = Pendaftaran::with([
                'peserta',
                'angkatan',
                'jenisPelatihan',
                'peserta.kepegawaianPeserta'
            ])->findOrFail($request->peserta_tujuan_id);

            // Verify that both peserta are in the same jenis pelatihan
            if ($pendaftaranTujuan->id_jenis_pelatihan != $jenisPelatihanId) {
                throw new \Exception('Peserta tujuan tidak berada dalam jenis pelatihan yang sama');
            }

            // Verify that they are from different angkatan
            if ($pendaftaranAsal->id_angkatan == $pendaftaranTujuan->id_angkatan) {
                throw new \Exception('Peserta harus berasal dari angkatan yang berbeda');
            }

            $angkatanAsal = $pendaftaranAsal->angkatan;
            $angkatanTujuan = $pendaftaranTujuan->angkatan;

            $pesertaAsal = $pendaftaranAsal->peserta;
            $pesertaTujuan = $pendaftaranTujuan->peserta;

            // Backup NDH values
            $ndhAsal = $pesertaAsal->ndh;    // NDH Ali: 2
            $ndhTujuan = $pesertaTujuan->ndh; // NDH Rizal: 1

            // === STEP 1: Swap folder paths for both peserta ===

            // File paths for peserta asal
            $tahun = date('Y');
            $kodeJenisPelatihan = str_replace(' ', '_', $pendaftaranAsal->jenisPelatihan->kode_pelatihan);

            $namaAngkatanAsal = str_replace(' ', '_', $angkatanAsal->nama_angkatan);
            $namaAngkatanTujuan = str_replace(' ', '_', $angkatanTujuan->nama_angkatan);

            $folderPathAsal = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatanAsal}/{$pesertaAsal->nip_nrp}";
            $folderPathTujuan = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatanTujuan}/{$pesertaTujuan->nip_nrp}";

            $folderPathTemp = "Berkas/{$tahun}/{$kodeJenisPelatihan}/TEMP/{$pesertaAsal->nip_nrp}_{$pesertaTujuan->nip_nrp}_" . time();

            // === STEP 2: Create temporary backup of files ===
            try {
                // Copy peserta asal files to temp location
                if (Storage::disk('google')->exists($folderPathAsal)) {
                    $filesAsal = Storage::disk('google')->listContents($folderPathAsal);
                    foreach ($filesAsal as $file) {
                        if (isset($file['path'])) {
                            $sourcePath = $file['path'];
                            $filename = basename($sourcePath);
                            $tempPath = $folderPathTemp . "/asal/{$filename}";

                            try {
                                $fileContent = Storage::disk('google')->get($sourcePath);
                                Storage::disk('google')->put($tempPath, $fileContent);
                            } catch (\Exception $e) {
                                // Skip if file doesn't exist
                            }
                        }
                    }
                }

                // Copy peserta tujuan files to temp location
                if (Storage::disk('google')->exists($folderPathTujuan)) {
                    $filesTujuan = Storage::disk('google')->listContents($folderPathTujuan);
                    foreach ($filesTujuan as $file) {
                        if (isset($file['path'])) {
                            $sourcePath = $file['path'];
                            $filename = basename($sourcePath);
                            $tempPath = $folderPathTemp . "/tujuan/{$filename}";

                            try {
                                $fileContent = Storage::disk('google')->get($sourcePath);
                                Storage::disk('google')->put($tempPath, $fileContent);
                            } catch (\Exception $e) {
                                // Skip if file doesn't exist
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Error creating temp backup: ' . $e->getMessage());
                // Continue anyway
            }

            // === STEP 3: SWAP NDH VALUES (NDH ikut angkatan) ===
            // Inilah perbedaan utama: NDH di-swap (ikut angkatan)
            // Ali (NDH 2) pindah ke Angkatan 2, dapat NDH 1 (dari Rizal)
            // Rizal (NDH 1) pindah ke Angkatan 1, dapat NDH 2 (dari Ali)

            $pesertaAsal->ndh = $ndhTujuan; // Ali dapat NDH 1
            $pesertaTujuan->ndh = $ndhAsal; // Rizal dapat NDH 2

            // === STEP 4: Update file paths in database ===

            // Function to update file paths
            function updateFilePaths($pendaftaran, $oldFolder, $newFolder)
            {
                $peserta = $pendaftaran->peserta;
                $kepegawaian = $peserta->kepegawaianPeserta;

                // Update peserta files
                $pesertaFields = ['file_ktp', 'file_pas_foto'];
                foreach ($pesertaFields as $field) {
                    if ($peserta->$field && strpos($peserta->$field, $oldFolder) !== false) {
                        $peserta->$field = str_replace($oldFolder, $newFolder, $peserta->$field);
                    }
                }

                // Update kepegawaian files
                if ($kepegawaian) {
                    $kepegawaianFields = ['file_sk_jabatan', 'file_sk_pangkat', 'file_sk_cpns', 'file_spmt', 'file_skp'];
                    foreach ($kepegawaianFields as $field) {
                        if ($kepegawaian->$field && strpos($kepegawaian->$field, $oldFolder) !== false) {
                            $kepegawaian->$field = str_replace($oldFolder, $newFolder, $kepegawaian->$field);
                        }
                    }
                }

                // Update pendaftaran files
                $pendaftaranFields = [
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

                foreach ($pendaftaranFields as $field) {
                    if ($pendaftaran->$field && strpos($pendaftaran->$field, $oldFolder) !== false) {
                        $pendaftaran->$field = str_replace($oldFolder, $newFolder, $pendaftaran->$field);
                    }
                }

                // Update aksi perubahan file
                $aksiPerubahan = $pendaftaran->aksiPerubahan->first();
                if ($aksiPerubahan && $aksiPerubahan->file) {
                    if (strpos($aksiPerubahan->file, $oldFolder) !== false) {
                        $aksiPerubahan->file = str_replace($oldFolder, $newFolder, $aksiPerubahan->file);
                        $aksiPerubahan->save();
                    }
                }

                return [$peserta, $kepegawaian, $pendaftaran];
            }

            // Swap file paths for peserta asal (moving to tujuan folder)
            list($pesertaAsal, $kepegawaianAsal, $pendaftaranAsal) = updateFilePaths(
                $pendaftaranAsal,
                $folderPathAsal,
                $folderPathTujuan
            );

            // Swap file paths for peserta tujuan (moving to asal folder)
            list($pesertaTujuan, $kepegawaianTujuan, $pendaftaranTujuan) = updateFilePaths(
                $pendaftaranTujuan,
                $folderPathTujuan,
                $folderPathAsal
            );

            // === STEP 5: Swap angkatan IDs ===
            $tempAngkatanId = $pendaftaranAsal->id_angkatan;
            $pendaftaranAsal->id_angkatan = $pendaftaranTujuan->id_angkatan;
            $pendaftaranTujuan->id_angkatan = $tempAngkatanId;

            // === STEP 6: Save all changes ===
            $pesertaAsal->save();
            $pesertaTujuan->save();

            if ($kepegawaianAsal) $kepegawaianAsal->save();
            if ($kepegawaianTujuan) $kepegawaianTujuan->save();

            $pendaftaranAsal->save();
            $pendaftaranTujuan->save();

            // === STEP 7: Move actual files in Google Drive ===
            try {
                // Delete old folders
                Storage::disk('google')->deleteDirectory($folderPathAsal);
                Storage::disk('google')->deleteDirectory($folderPathTujuan);

                // Copy temp files to new locations
                // Copy asal files to tujuan folder
                $tempAsalPath = $folderPathTemp . "/asal";
                if (Storage::disk('google')->exists($tempAsalPath)) {
                    $tempFiles = Storage::disk('google')->listContents($tempAsalPath);
                    foreach ($tempFiles as $file) {
                        if (isset($file['path'])) {
                            $sourcePath = $file['path'];
                            $filename = basename($sourcePath);
                            $destPath = $folderPathTujuan . "/{$filename}";

                            try {
                                $fileContent = Storage::disk('google')->get($sourcePath);
                                Storage::disk('google')->put($destPath, $fileContent);
                            } catch (\Exception $e) {
                                // Skip if error
                            }
                        }
                    }
                }

                // Copy tujuan files to asal folder
                $tempTujuanPath = $folderPathTemp . "/tujuan";
                if (Storage::disk('google')->exists($tempTujuanPath)) {
                    $tempFiles = Storage::disk('google')->listContents($tempTujuanPath);
                    foreach ($tempFiles as $file) {
                        if (isset($file['path'])) {
                            $sourcePath = $file['path'];
                            $filename = basename($sourcePath);
                            $destPath = $folderPathAsal . "/{$filename}";

                            try {
                                $fileContent = Storage::disk('google')->get($sourcePath);
                                Storage::disk('google')->put($destPath, $fileContent);
                            } catch (\Exception $e) {
                                // Skip if error
                            }
                        }
                    }
                }

                // Clean up temp folder
                Storage::disk('google')->deleteDirectory($folderPathTemp);
            } catch (\Exception $e) {
                \Log::error('Error moving files: ' . $e->getMessage());
                // Don't rollback if file movement fails
            }

            // === STEP 8: Update NDH sequencing if needed ===
            // Pastikan tidak ada duplikasi NDH dalam angkatan yang sama

            // Check for duplicate NDH in Angkatan 1 (setelah Rizal pindah)
            $this->validateAndFixNDHSequence($angkatanAsal->id);

            // Check for duplicate NDH in Angkatan 2 (setelah Ali pindah)
            $this->validateAndFixNDHSequence($angkatanTujuan->id);

            // === STEP 9: Log activities ===
            $jenisPelatihan = $pendaftaranAsal->jenisPelatihan;

            // Log for peserta asal
            aktifitas(
                "Swap angkatan: " . $pesertaAsal->nama_lengkap .
                    " pindah dari {$angkatanAsal->nama_angkatan} (NDH {$ndhAsal}) " .
                    "ke {$angkatanTujuan->nama_angkatan} (NDH {$ndhTujuan})",
                $pesertaAsal,
                $request->catatan_swap
            );

            // Log for peserta tujuan
            aktifitas(
                "Swap angkatan: " . $pesertaTujuan->nama_lengkap .
                    " pindah dari {$angkatanTujuan->nama_angkatan} (NDH {$ndhTujuan}) " .
                    "ke {$angkatanAsal->nama_angkatan} (NDH {$ndhAsal})",
                $pesertaTujuan,
                $request->catatan_swap
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menukar tempat ' . $pesertaAsal->nama_lengkap .
                    ' (sekarang NDH ' . $ndhTujuan . ' di ' . $angkatanTujuan->nama_angkatan . ') dengan ' .
                    $pesertaTujuan->nama_lengkap . ' (sekarang NDH ' . $ndhAsal . ' di ' . $angkatanAsal->nama_angkatan . ')',
                'redirect_url' => route('peserta.index', ['jenis' => $jenis])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Swap angkatan error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate and fix NDH sequence in an angkatan.
     */
    private function validateAndFixNDHSequence($angkatanId)
    {
        // Get all peserta in this angkatan
        $pesertaList = Peserta::whereHas('pendaftaran', function ($query) use ($angkatanId) {
            $query->where('id_angkatan', $angkatanId);
        })
            ->whereNotNull('ndh')
            ->orderBy('ndh')
            ->get();

        // Check for duplicate NDH
        $ndhCounts = [];
        foreach ($pesertaList as $peserta) {
            $ndh = $peserta->ndh;
            if (!isset($ndhCounts[$ndh])) {
                $ndhCounts[$ndh] = 0;
            }
            $ndhCounts[$ndh]++;
        }

        // If there are duplicates, re-sequence
        $hasDuplicates = false;
        foreach ($ndhCounts as $count) {
            if ($count > 1) {
                $hasDuplicates = true;
                break;
            }
        }

        if ($hasDuplicates) {
            $counter = 1;
            foreach ($pesertaList as $peserta) {
                $peserta->ndh = $counter++;
                $peserta->save();
            }
        }
    }
}
