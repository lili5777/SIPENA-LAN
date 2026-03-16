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
use App\Models\PicPeserta;
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

            $picPeserta = PicPeserta::with('user')
            ->where('jenispelatihan_id', $request->id_jenis_pelatihan)
            ->where('angkatan_id', $existingPendaftaran->id_angkatan)
            ->first();

            $picData = null;

            if ($picPeserta && $picPeserta->user) {
                $picData = [
                    'nama' => $picPeserta->user->name,
                    'no_telp' => $picPeserta->user->no_telp,
                    'email' => $picPeserta->user->email,
                ];
            }



            return response()->json([
                'success' => true,
                'message' => 'Verifikasi berhasil. Peserta terdaftar pada pelatihan ini.',
                'peserta' => $pesertaData,
                'pendaftaran' => $pendaftaranData,
                'pic' => $picData
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
 * API untuk mendapatkan NDH yang tersedia
 */
public function getAvailableNdh(Request $request)
{
    try {
        $request->validate([
            'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
            'id_angkatan' => 'required|exists:angkatan,id',
            'nip_nrp' => 'nullable|string', // tambahkan ini
        ]);

        // Ambil data angkatan untuk mendapatkan kuota
        $angkatan = Angkatan::findOrFail($request->id_angkatan);
        $kuota = $angkatan->kuota;

        // Ambil NDH yang sudah terpakai KECUALI milik peserta ini
        $ndhTerpakai = Peserta::whereHas('pendaftaran', function($query) use ($request) {
                $query->where('id_angkatan', $request->id_angkatan)
                      ->where('id_jenis_pelatihan', $request->id_jenis_pelatihan);
            })
            ->when($request->nip_nrp, function($query) use ($request) {
                // Exclude peserta dengan NIP yang sedang update
                $query->where('nip_nrp', '!=', $request->nip_nrp);
            })
            ->whereNotNull('ndh')
            ->pluck('ndh')
            ->toArray();

        // Generate list NDH yang tersedia (1 sampai kuota)
        $ndhTersedia = [];
        for ($i = 1; $i <= $kuota; $i++) {
            if (!in_array($i, $ndhTerpakai)) {
                $ndhTersedia[] = $i;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $ndhTersedia,
            'kuota' => $kuota,
            'terpakai' => count($ndhTerpakai),
            'tersedia' => count($ndhTersedia)
        ]);

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
        set_time_limit(600);
        ini_set('max_execution_time', 600);

        try {
            // ============================================
            // 1. VALIDASI DASAR
            // ============================================
            $request->validate([
                'id_jenis_pelatihan'  => 'required|exists:jenis_pelatihan,id',
                'peserta_id'          => 'required|exists:peserta,id',
                'pendaftaran_id'      => 'required|exists:pendaftaran,id',
                'nama_lengkap'        => 'required|string|max:200',
                'nip_nrp'             => 'required|string|max:50',
                'nama_panggilan'      => 'nullable|string|max:100',
                'jenis_kelamin'       => 'required|in:Laki-laki,Perempuan',
                'agama'               => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Kristen Protestan',
                'tempat_lahir'        => 'required|string|max:100',
                'tanggal_lahir'       => 'required|date',
                'alamat_rumah'        => 'required|string',
                'email_pribadi'       => 'required|email|max:100',
                'nomor_hp'            => 'required|string|max:20',
                'pendidikan_terakhir' => 'required|in:SD,SMP,SMU,D3,D4,S1,S2,S3',
                'bidang_studi'        => 'nullable|string|max:100',
                'bidang_keahlian'     => 'nullable|string|max:100',
                'status_perkawinan'   => 'nullable|in:Belum Menikah,Menikah,Duda,Janda',
                'nama_pasangan'       => 'nullable|string|max:200',
                'olahraga_hobi'       => 'nullable|string|max:100',
                'perokok'             => 'required|in:Ya,Tidak',
                'ukuran_kaos' => 'nullable|in:XS,S,M,L,XL,XXL,XXXL,XXXXL,XXXXXL,XXXXXXL,XXXXXXXL',
                'ukuran_celana' => 'nullable|in:XS,S,M,L,XL,XXL,XXXL,XXXXL,XXXXXL,XXXXXXL,XXXXXXXL',
                'ukuran_training' => 'nullable|in:XS,S,M,L,XL,XXL,XXXL,XXXXL,XXXXXL,XXXXXXL,XXXXXXXL',
                'kondisi_peserta'     => 'nullable|string',
                'file_ktp'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
                'file_pas_foto_cropped' => 'nullable|string',
                'crop_data'           => 'nullable|string',
                'asal_instansi'       => 'required|string|max:200',
                'unit_kerja'          => 'nullable|string|max:200',
                'id_provinsi'         => 'required',
                'id_kabupaten_kota'   => 'nullable',
                'alamat_kantor'       => 'nullable|string',
                'nomor_telepon_kantor'=> 'nullable|string|max:20',
                'email_kantor'        => 'nullable|email|max:100',
                'jabatan'             => 'required|string|max:200',
                'pangkat'             => 'nullable|string|max:50',
                'golongan_ruang'      => 'required|string|max:50',
                'eselon'              => 'nullable|string|max:50',
                'file_sk_jabatan'     => 'nullable|file|mimes:pdf|max:1024',
                'file_sk_pangkat'     => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_tugas'    => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_sehat'    => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_bebas_narkoba'          => 'nullable|file|mimes:pdf|max:1024',
                'file_pakta_integritas'             => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_kesediaan'              => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_komitmen'               => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_kelulusan_seleksi'      => 'nullable|file|mimes:pdf|max:1024',
                'file_surat_pernyataan_administrasi'=> 'nullable|file|mimes:pdf|max:1024',
                'file_sertifikat_penghargaan'       => 'nullable|file|mimes:pdf|max:1024',
                'file_sk_cpns'        => 'nullable|file|mimes:pdf|max:1024',
                'file_spmt'           => 'nullable|file|mimes:pdf|max:1024',
                'file_skp'            => 'nullable|file|mimes:pdf|max:1024',
                'file_toefl' => 'nullable|file|mimes:pdf|max:1024',
                'file_persetujuan_mentor' => 'nullable|file|mimes:pdf|max:1024',
                'nomor_sk_cpns'       => 'nullable|string|max:100',
                'nomor_sk_terakhir'   => 'nullable|string|max:100',
                'tanggal_sk_cpns'     => 'nullable|date',
                'tanggal_sk_jabatan'  => 'nullable|date',
                'tahun_lulus_pkp_pim_iv' => 'nullable|integer',
                'sudah_ada_mentor'    => 'nullable|in:Ya,Tidak',
                'mentor_mode'         => 'nullable|in:pilih,tambah',
                'id_mentor'           => 'nullable|exists:mentor,id',
                'nama_mentor_baru'    => 'nullable|string|max:200',
                'nip_mentor_baru'     => 'nullable|string|max:200',
                'jabatan_mentor_baru' => 'nullable|string|max:200',
                'golongan_mentor_baru'=> 'nullable|string|max:50',
                'pangkat_mentor_baru' => 'nullable|string|max:100',
                'nomor_rekening_mentor_baru' => 'nullable|string|max:255',
                'npwp_mentor_baru'    => 'nullable|string|max:50',
                'nomor_hp_mentor_baru'=> 'nullable|string|max:20',
                'nomor_rekening_mentor' => 'nullable|string|max:200',
                'npwp_mentor'         => 'nullable|string|max:50',
                'ndh'                 => 'required|max:50',
            ], [
                'nama_lengkap.required'        => 'Nama lengkap wajib diisi',
                'nama_lengkap.max'             => 'Nama lengkap maksimal 200 karakter',
                'nip_nrp.required'             => 'NIP/NRP wajib diisi',
                'jenis_kelamin.required'       => 'Jenis kelamin wajib dipilih',
                'agama.required'               => 'Agama wajib dipilih',
                'tempat_lahir.required'        => 'Tempat lahir wajib diisi',
                'tanggal_lahir.required'       => 'Tanggal lahir wajib diisi',
                'tanggal_lahir.date'           => 'Format tanggal lahir tidak valid',
                'alamat_rumah.required'        => 'Alamat rumah wajib diisi',
                'email_pribadi.required'       => 'Email pribadi wajib diisi',
                'email_pribadi.email'          => 'Format email pribadi tidak valid',
                'nomor_hp.required'            => 'Nomor HP wajib diisi',
                'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih',
                'perokok.required'             => 'Status perokok wajib dipilih',
                'asal_instansi.required'       => 'Asal instansi wajib diisi',
                'id_provinsi.required'         => 'Provinsi wajib dipilih',
                'jabatan.required'             => 'Jabatan wajib diisi',
                'golongan_ruang.required'      => 'Golongan ruang wajib diisi',
                'email_kantor.email'           => 'Format email kantor tidak valid',
                'peserta_id.exists'            => 'Data peserta tidak ditemukan',
                'pendaftaran_id.exists'        => 'Data pendaftaran tidak ditemukan',
                'id_jenis_pelatihan.exists'    => 'Jenis pelatihan tidak ditemukan',
                'id_mentor.exists'             => 'Data mentor tidak ditemukan',
            ]);

            // ============================================
            // 2. CEK KESESUAIAN DATA PESERTA & PENDAFTARAN
            // ============================================
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

            if (
                $pendaftaran->id_peserta != $peserta->id ||
                $pendaftaran->id_jenis_pelatihan != $request->id_jenis_pelatihan
            ) {
                throw ValidationException::withMessages([
                    'general' => ['Data tidak valid. Peserta tidak terdaftar pada pelatihan ini.']
                ]);
            }

            // Validasi ukuran base64 foto
            if ($request->filled('file_pas_foto_cropped')) {
                if (strlen($request->file_pas_foto_cropped) > 2_000_000) {
                    throw ValidationException::withMessages([
                        'file_pas_foto_cropped' => ['Ukuran foto hasil crop terlalu besar. Maksimal 1.5MB.']
                    ]);
                }
            }

            // ============================================
            // 3. VALIDASI TAMBAHAN BERDASARKAN JENIS PELATIHAN
            // ============================================
            $jenisPelatihan = JenisPelatihan::find($request->id_jenis_pelatihan);
            $kode           = $jenisPelatihan->kode_pelatihan;
            $kepegawaian    = $peserta->kepegawaian;
            $additionalRules    = [];
            $additionalMessages = [];

            // ---- PKN TK II ----
            if ($kode === 'PKN_TK_II') {
                $additionalRules['eselon'] = 'required|string|max:50';
                $additionalMessages['eselon.required'] = 'Eselon wajib diisi untuk pelatihan PKN TK II';

                if (!$kepegawaian || !$kepegawaian->file_sk_jabatan) {
                    $additionalRules['file_sk_jabatan'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_sk_jabatan.required'] = 'File SK Jabatan wajib diunggah';
                }
                if (!$kepegawaian || !$kepegawaian->file_sk_pangkat) {
                    $additionalRules['file_sk_pangkat'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_sk_pangkat.required'] = 'File SK Pangkat wajib diunggah';
                }
                if (!$pendaftaran->file_pakta_integritas) {
                    $additionalRules['file_pakta_integritas'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_pakta_integritas.required'] = 'File Pakta Integritas wajib diunggah';
                }
                if (!$pendaftaran->file_surat_sehat) {
                    $additionalRules['file_surat_sehat'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_surat_sehat.required'] = 'File Surat Sehat wajib diunggah';
                }
                if (!$pendaftaran->file_surat_bebas_narkoba) {
                    $additionalRules['file_surat_bebas_narkoba'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_surat_bebas_narkoba.required'] = 'File Surat Bebas Narkoba wajib diunggah';
                }
            }

            // ---- LATSAR ----
            if ($kode === 'LATSAR') {
                $additionalRules['nomor_sk_cpns']    = 'required|string|max:100';
                $additionalRules['tanggal_sk_cpns']  = 'required|date';
                $additionalRules['pangkat']           = 'required|string|max:50';
                $additionalRules['sudah_ada_mentor']  = 'required|in:Ya,Tidak';

                $additionalMessages['nomor_sk_cpns.required']   = 'Nomor SK CPNS wajib diisi';
                $additionalMessages['tanggal_sk_cpns.required'] = 'Tanggal SK CPNS wajib diisi';
                $additionalMessages['tanggal_sk_cpns.date']     = 'Format tanggal SK CPNS tidak valid';
                $additionalMessages['pangkat.required']         = 'Pangkat wajib diisi';
                $additionalMessages['sudah_ada_mentor.required']= 'Status mentor wajib dipilih';

                if (!$peserta->file_ktp) {
                    $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:1024';
                    $additionalMessages['file_ktp.required'] = 'File KTP wajib diunggah';
                }
                if (!$kepegawaian || !$kepegawaian->file_sk_cpns) {
                    $additionalRules['file_sk_cpns'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_sk_cpns.required'] = 'File SK CPNS wajib diunggah';
                }
                if (!$kepegawaian || !$kepegawaian->file_spmt) {
                    $additionalRules['file_spmt'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_spmt.required'] = 'File SPMT wajib diunggah';
                }
                if (!$pendaftaran->file_surat_kesediaan) {
                    $additionalRules['file_surat_kesediaan'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_surat_kesediaan.required'] = 'File Surat Kesediaan wajib diunggah';
                }
                if (!$peserta->file_pas_foto) {
                    $additionalRules['file_pas_foto_cropped'] = 'required|string';
                    $additionalMessages['file_pas_foto_cropped.required'] = 'Foto peserta wajib diunggah ';
                }
            }

            // ---- PKA / PKP ----
            if ($kode === 'PKA' || $kode === 'PKP') {
                $additionalRules['eselon']            = 'required|string|max:50';
                $additionalRules['tanggal_sk_jabatan']= 'required|date';
                $additionalRules['nomor_sk_terakhir'] = 'required|string|max:100';
                $additionalRules['sudah_ada_mentor']  = 'required|in:Ya,Tidak';

                $additionalMessages['eselon.required']             = 'Eselon wajib diisi untuk pelatihan ' . $kode;
                $additionalMessages['tanggal_sk_jabatan.required'] = 'Tanggal SK Jabatan wajib diisi';
                $additionalMessages['tanggal_sk_jabatan.date']     = 'Format tanggal SK Jabatan tidak valid';
                $additionalMessages['nomor_sk_terakhir.required']  = 'Nomor SK Jabatan Terakhir wajib diisi';
                $additionalMessages['sudah_ada_mentor.required']   = 'Status mentor wajib dipilih';

                if (!$peserta->file_ktp) {
                    $additionalRules['file_ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:1024';
                    $additionalMessages['file_ktp.required'] = 'File KTP wajib diunggah';
                }
                if (!$peserta->file_pas_foto) {
                    $additionalRules['file_pas_foto_cropped'] = 'required|string';
                    $additionalMessages['file_pas_foto_cropped.required'] = 'Foto peserta wajib diunggah';
                }
                if (!$kepegawaian || !$kepegawaian->file_sk_jabatan) {
                    $additionalRules['file_sk_jabatan'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_sk_jabatan.required'] = 'File SK Jabatan wajib diunggah';
                }
                if (!$kepegawaian || !$kepegawaian->file_sk_pangkat) {
                    $additionalRules['file_sk_pangkat'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_sk_pangkat.required'] = 'File SK Pangkat wajib diunggah';
                }
                if (!$pendaftaran->file_surat_kesediaan) {
                    $additionalRules['file_surat_kesediaan'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_surat_kesediaan.required'] = 'File Surat Kesediaan wajib diunggah';
                }
                if (!$pendaftaran->file_pakta_integritas) {
                    $additionalRules['file_pakta_integritas'] = 'required|file|mimes:pdf|max:1024';
                    $additionalMessages['file_pakta_integritas.required'] = 'File Pakta Integritas wajib diunggah';
                }
            }

            // ---- VALIDASI FOTO (semua jenis pelatihan) ----
            if (!$peserta->file_pas_foto && !isset($additionalRules['file_pas_foto_cropped'])) {
                $additionalRules['file_pas_foto_cropped'] = 'required|string';
                $additionalMessages['file_pas_foto_cropped.required'] = 'Foto pas peserta wajib diunggah ';
            }

            // ---- VALIDASI MENTOR ----
            if ($request->sudah_ada_mentor === 'Ya') {
                $additionalRules['mentor_mode'] = 'required|in:pilih,tambah';
                $additionalMessages['mentor_mode.required'] = 'Pilih mode mentor (dari daftar atau tambah baru)';
                $additionalMessages['mentor_mode.in']       = 'Mode mentor tidak valid';

                if ($request->mentor_mode === 'pilih') {
                    $additionalRules['id_mentor'] = 'required|exists:mentor,id';
                    $additionalMessages['id_mentor.required'] = 'Pilih mentor dari daftar';
                    $additionalMessages['id_mentor.exists']   = 'Mentor yang dipilih tidak ditemukan';

                } elseif ($request->mentor_mode === 'tambah') {
                    $additionalRules['nama_mentor_baru']           = 'required|string|max:200';
                    $additionalRules['nip_mentor_baru']            = [
                        'required',
                        'string',
                        'max:200',
                        function ($attribute, $value, $fail) {
                            $normalizedNip = preg_replace('/[\s\.]/', '', $value);
                            $exists = Mentor::whereRaw(
                                "REPLACE(REPLACE(nip_mentor, ' ', ''), '.', '') = ?",
                                [$normalizedNip]
                            )->exists();
                            if ($exists) {
                                $fail('NIP Mentor sudah terdaftar. Silakan pilih dari daftar mentor yang tersedia.');
                            }
                        }
                    ];
                    $additionalRules['jabatan_mentor_baru']        = 'required|string|max:200';
                    $additionalRules['golongan_mentor_baru']       = 'required|string|max:50';
                    $additionalRules['pangkat_mentor_baru']        = 'required|string|max:100';
                    $additionalRules['nomor_rekening_mentor_baru'] = 'required|string|max:255';
                    $additionalRules['npwp_mentor_baru']           = 'required|string|max:50';
                    $additionalRules['nomor_hp_mentor_baru']       = 'nullable|string|max:20|regex:/^[0-9\-\+]+$/';

                    $additionalMessages['nama_mentor_baru.required']           = 'Nama mentor baru wajib diisi';
                    $additionalMessages['nip_mentor_baru.required']            = 'NIP mentor baru wajib diisi';
                    $additionalMessages['jabatan_mentor_baru.required']        = 'Jabatan mentor baru wajib diisi';
                    $additionalMessages['golongan_mentor_baru.required']       = 'Golongan ruang mentor wajib dipilih';
                    $additionalMessages['pangkat_mentor_baru.required']        = 'Pangkat mentor wajib diisi';
                    $additionalMessages['nomor_rekening_mentor_baru.required'] = 'Nomor rekening mentor wajib diisi';
                    $additionalMessages['npwp_mentor_baru.required']           = 'NPWP mentor wajib diisi';
                    $additionalMessages['nomor_hp_mentor_baru.regex']          = 'Format nomor HP mentor tidak valid';
                }
            }

            // Jalankan semua validasi tambahan SEKARANG — sebelum proses simpan apapun
            if (!empty($additionalRules)) {
                $request->validate($additionalRules, $additionalMessages);
            }

            // ============================================
            // 4. CEK NDH — sebelum proses file & simpan
            // ============================================
            $ndhExists = Peserta::whereHas('pendaftaran', function ($query) use ($pendaftaran) {
                    $query->where('id_angkatan', $pendaftaran->id_angkatan)
                          ->where('id_jenis_pelatihan', $pendaftaran->id_jenis_pelatihan);
                })
                ->where('ndh', $request->ndh)
                ->where('id', '!=', $request->peserta_id)
                ->exists();

            if ($ndhExists) {
                throw ValidationException::withMessages([
                    'ndh' => ['Nomor NDH sudah digunakan oleh peserta lain di angkatan dan jenis pelatihan yang sama']
                ]);
            }

            // ============================================
            // 5. PROSES UPLOAD FILE
            // ============================================
            $fileFields = [
                'file_ktp', 'file_pas_foto', 'file_sk_jabatan', 'file_sk_pangkat',
                'file_surat_tugas', 'file_surat_kesediaan', 'file_pakta_integritas',
                'file_surat_komitmen', 'file_surat_kelulusan_seleksi',
                'file_surat_sehat', 'file_surat_bebas_narkoba',
                'file_surat_pernyataan_administrasi', 'file_sertifikat_penghargaan',
                'file_sk_cpns', 'file_spmt', 'file_skp', 'file_persetujuan_mentor','file_toefl'
            ];

            $tahun      = date('Y');
            $folderPath = null;
            $angkatan   = $pendaftaran->angkatan ?? null;
            $kategori   = $angkatan->kategori ?? 'PNBP';
            $wilayah    = $angkatan->wilayah  ?? null;

            if ($jenisPelatihan && $angkatan) {
                $kategoriFolder      = strtoupper($kategori);
                $kodeJenisPelatihan  = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
                $namaAngkatan        = str_replace(' ', '_', $angkatan->nama_angkatan);
                $nip                 = $request->nip_nrp;

                if (strtoupper($kategori) === 'FASILITASI') {
                    $wilayahFolder = $wilayah ? str_replace(' ', '_', $wilayah) : 'Umum';
                    $folderPath = "Berkas/{$kategoriFolder}/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$wilayahFolder}/{$nip}";
                } else {
                    $folderPath = "Berkas/{$kategoriFolder}/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";
                }
            }

            $files = [];

            // Proses foto pas cropped (base64)
            if ($request->filled('file_pas_foto_cropped')) {
                try {
                    $base64Image = $request->file_pas_foto_cropped;

                    if (!preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $base64Image)) {
                        throw ValidationException::withMessages([
                            'file_pas_foto_cropped' => ['Format gambar tidak didukung. Gunakan JPG, JPEG, atau PNG.']
                        ]);
                    }

                    $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));

                    if ($imageData === false) {
                        throw ValidationException::withMessages([
                            'file_pas_foto_cropped' => ['Gagal mendecode gambar base64.']
                        ]);
                    }

                    if (strlen($imageData) > 1_500_000) {
                        throw ValidationException::withMessages([
                            'file_pas_foto_cropped' => ['Ukuran foto terlalu besar. Maksimal 1.1MB setelah crop.']
                        ]);
                    }

                    if (!$folderPath) {
                        $folderPath = strtoupper($kategori) === 'FASILITASI'
                            ? "Berkas/FASILITASI/{$tahun}/default/" . ($wilayah ? str_replace(' ', '_', $wilayah) : 'Umum') . "/{$request->nip_nrp}"
                            : "Berkas/PNBP/{$tahun}/default/{$request->nip_nrp}";
                    }

                    $fileName  = 'pas_foto_3x4_' . time() . '.jpg';
                    $drivePath = "{$folderPath}/{$fileName}";

                    $this->deleteOldFile($peserta, $pendaftaran, $kepegawaian, 'file_pas_foto');
                    Storage::disk('google')->put($drivePath, $imageData);
                    $files['file_pas_foto'] = $drivePath;

                } catch (ValidationException $e) {
                    throw $e;
                } catch (\Exception $e) {
                    throw ValidationException::withMessages([
                        'file_pas_foto_cropped' => ['Gagal memproses foto: ' . $e->getMessage()]
                    ]);
                }
            }

            // Proses file-file lainnya
            foreach ($fileFields as $field) {
                if ($field === 'file_pas_foto') continue; // sudah diproses di atas

                if ($request->hasFile($field)) {
                    try {
                        if (!$folderPath) {
                            $folderPath = strtoupper($kategori) === 'FASILITASI'
                                ? "Berkas/FASILITASI/{$tahun}/default/" . ($wilayah ? str_replace(' ', '_', $wilayah) : 'Umum') . "/{$request->nip_nrp}"
                                : "Berkas/PNBP/{$tahun}/default/{$request->nip_nrp}";
                        }

                        $extension = $request->file($field)->getClientOriginalExtension();
                        $fieldName = str_replace('file_', '', $field);
                        $fileName  = $fieldName . '.' . $extension;
                        $drivePath = "{$folderPath}/{$fileName}";

                        $this->deleteOldFile($peserta, $pendaftaran, $kepegawaian, $field);
                        Storage::disk('google')->put($drivePath, file_get_contents($request->file($field)));
                        $files[$field] = $drivePath;

                    } catch (\Exception $e) {
                        throw ValidationException::withMessages([
                            $field => ['Gagal upload file ke Google Drive: ' . $e->getMessage()]
                        ]);
                    }
                }
            }

            // ============================================
            // 6. UPDATE DATA PESERTA
            // ============================================
            $pesertaUpdateData = [
                'nama_lengkap'      => $request->nama_lengkap,
                'nip_nrp'           => $request->nip_nrp,
                'nama_panggilan'    => $request->nama_panggilan,
                'jenis_kelamin'     => $request->jenis_kelamin,
                'agama'             => $request->agama,
                'tempat_lahir'      => $request->tempat_lahir,
                'tanggal_lahir'     => $request->tanggal_lahir,
                'pendidikan_terakhir'=> $request->pendidikan_terakhir,
                'alamat_rumah'      => $request->alamat_rumah,
                'email_pribadi'     => $request->email_pribadi,
                'nomor_hp'          => $request->nomor_hp,
                'bidang_studi'      => $request->bidang_studi,
                'bidang_keahlian'   => $request->bidang_keahlian,
                'status_perkawinan' => $request->status_perkawinan,
                'nama_pasangan'     => $request->nama_pasangan,
                'olahraga_hobi'     => $request->olahraga_hobi,
                'perokok'           => $request->perokok,
                'ukuran_kaos'       => $request->ukuran_kaos,
                'ukuran_celana'     => $request->ukuran_celana,
                'ukuran_training'   => $request->ukuran_training,
                'kondisi_peserta'   => $request->kondisi_peserta,
                'ndh'               => $request->ndh,
            ];

            if (isset($files['file_ktp']))      $pesertaUpdateData['file_ktp']      = $files['file_ktp'];
            if (isset($files['file_pas_foto'])) $pesertaUpdateData['file_pas_foto'] = $files['file_pas_foto'];

            $peserta->update($pesertaUpdateData);

            // ============================================
            // 7. UPDATE KEPEGAWAIAN PESERTA
            // ============================================
            $provinsi = Provinsi::where('id', $request->id_provinsi)->first();
            if (!$provinsi) {
                throw ValidationException::withMessages([
                    'id_provinsi' => ['Provinsi yang dipilih tidak ditemukan di database']
                ]);
            }

            $kabupaten = $request->id_kabupaten_kota
                ? Kabupaten::where('id', $request->id_kabupaten_kota)->first()
                : null;

            $kepegawaianUpdateData = [
                'asal_instansi'        => $request->asal_instansi,
                'unit_kerja'           => $request->unit_kerja,
                'id_provinsi'          => $provinsi->id,
                'id_kabupaten_kota'    => $kabupaten?->id,
                'alamat_kantor'        => $request->alamat_kantor,
                'nomor_telepon_kantor' => $request->nomor_telepon_kantor,
                'email_kantor'         => $request->email_kantor,
                'jabatan'              => $request->jabatan,
                'pangkat'              => $request->pangkat,
                'golongan_ruang'       => $request->golongan_ruang,
                'eselon'               => $request->eselon,
                'tanggal_sk_jabatan'   => $request->tanggal_sk_jabatan,
                'nomor_sk_cpns'        => $request->nomor_sk_cpns,
                'tanggal_sk_cpns'      => $request->tanggal_sk_cpns,
                'tahun_lulus_pkp_pim_iv' => $request->tahun_lulus_pkp_pim_iv,
                'nomor_sk_terakhir'    => $request->nomor_sk_terakhir,
            ];

            foreach (['file_sk_jabatan', 'file_sk_pangkat', 'file_sk_cpns', 'file_spmt', 'file_skp', 'file_toefl'] as $field) {
                if (isset($files[$field])) $kepegawaianUpdateData[$field] = $files[$field];
            }

            KepegawaianPeserta::updateOrCreate(
                ['id_peserta' => $peserta->id],
                $kepegawaianUpdateData
            );

            // ============================================
            // 8. UPDATE DOKUMEN PENDAFTARAN
            // ============================================
            $pendaftaranUpdateData = [];
            $pendaftaranFileFields = [
                'file_surat_tugas', 'file_surat_kesediaan', 'file_pakta_integritas',
                'file_surat_komitmen', 'file_surat_kelulusan_seleksi',
                'file_surat_sehat', 'file_surat_bebas_narkoba',
                'file_surat_pernyataan_administrasi', 'file_sertifikat_penghargaan',
                'file_persetujuan_mentor'
            ];

            foreach ($pendaftaranFileFields as $field) {
                if (isset($files[$field])) $pendaftaranUpdateData[$field] = $files[$field];
            }

            if (!empty($pendaftaranUpdateData)) {
                $pendaftaran->update($pendaftaranUpdateData);
            }

            // ============================================
            // 9. SIMPAN MENTOR
            // Validasi sudah dilakukan di step 3 — langsung proses simpan
            // ============================================
            if ($request->sudah_ada_mentor === 'Ya') {
                $mentor = null;

                if ($request->mentor_mode === 'pilih' && $request->id_mentor) {
                    $mentor = Mentor::find($request->id_mentor);

                } elseif ($request->mentor_mode === 'tambah') {
                    $nipMentorBersih = preg_replace('/[\s\.]/', '', $request->nip_mentor_baru);

                    // Cek dulu apakah NIP sudah ada di DB
                    $mentor = Mentor::whereRaw(
                        "REPLACE(REPLACE(nip_mentor, ' ', ''), '.', '') = ?",
                        [$nipMentorBersih]
                    )->first();

                    // Jika sudah ada, tolak dengan pesan error
                    if ($mentor) {
                        throw ValidationException::withMessages([
                            'nip_mentor_baru' => ['NIP Mentor sudah terdaftar. Silakan pilih dari daftar mentor yang tersedia.']
                        ]);
                    }

                    // Jika belum ada, baru buat
                    $mentor = Mentor::create([
                        'nama_mentor'     => $request->nama_mentor_baru,
                        'nip_mentor'      => $nipMentorBersih,
                        'jabatan_mentor'  => $request->jabatan_mentor_baru,
                        'golongan'        => $request->golongan_mentor_baru,
                        'pangkat'         => $request->pangkat_mentor_baru,
                        'nomor_rekening'  => $request->nomor_rekening_mentor_baru,
                        'npwp_mentor'     => $request->npwp_mentor_baru,
                        'nomor_hp_mentor' => $request->nomor_hp_mentor_baru,
                        'status_aktif'    => true,
                    ]);
                }

                if ($mentor) {
                    PesertaMentor::updateOrCreate(
                        ['id_pendaftaran' => $pendaftaran->id],
                        [
                            'id_mentor'          => $mentor->id,
                            'tanggal_penunjukan' => now(),
                            'status_mentoring'   => 'Ditugaskan',
                        ]
                    );
                }
            }

            // ============================================
            // 10. TANDAI PESERTA SUDAH MENGISI FORM
            // ============================================
            $peserta->update(['batasan' => true]);

            // ============================================
            // 11. RESPONSE
            // ============================================
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'         => true,
                    'message'         => 'Data peserta berhasil diperbarui!',
                    'pendaftaran_id'  => $pendaftaran->id,
                    'redirect_url'    => route('pendaftaran.success')
                ], 200);
            }

            session(['pendaftaran_id' => $pendaftaran->id]);

            return redirect()->route('pendaftaran.success')
                ->with('success', 'Data peserta berhasil diperbarui!')
                ->with('pendaftaran_id', $pendaftaran->id);

        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal. Mohon periksa kembali data yang Anda masukkan.',
                    'errors'  => $e->errors()
                ], 422);
            }

            return redirect()->back()
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

            return redirect()->back()
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
                in_array($field, ['file_sk_jabatan', 'file_sk_pangkat', 'file_sk_cpns', 'file_spmt', 'file_skp', 'file_toefl'])
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

        if (!$pendaftaran_id) {
            return redirect()->route('home')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $pendaftaran = Pendaftaran::with([
            'peserta',
            'jenisPelatihan',
            'angkatan'
        ])->findOrFail($pendaftaran_id);

        // 🔹 Ambil PIC berdasarkan angkatan + jenis pelatihan
        $pic = PicPeserta::with('user')
            ->where('angkatan_id', $pendaftaran->id_angkatan)
            ->where('jenispelatihan_id', $pendaftaran->id_jenis_pelatihan)
            ->first();

        return view('pendaftaran.success', compact('pendaftaran', 'pic'));
    }

    /**
     * API untuk mendapatkan daftar mentor dengan fitur pencarian
     */
    public function getMentors(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = Mentor::where('status_aktif', true);
        
        // Jika ada parameter pencarian
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                // Normalisasi input pencarian (hapus spasi dan titik)
                $normalizedSearch = preg_replace('/[\s\.]/', '', $search);
                
                // Cari berdasarkan nama mentor (case insensitive)
                $q->where('nama_mentor', 'LIKE', "%{$search}%")
                // Cari berdasarkan NIP mentor (normalisasi)
                ->orWhereRaw("REPLACE(REPLACE(nip_mentor, ' ', ''), '.', '') LIKE ?", ["%{$normalizedSearch}%"]);
            });
        }
        
        $mentors = $query->orderBy('nama_mentor', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $mentors,
            'total' => $mentors->count()
        ]);
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
