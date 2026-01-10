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

            // Cari peserta berdasarkan NIP/NRP
            $peserta = Peserta::with(['kepegawaian' => function ($query) {
                $query->with(['provinsi', 'kabupaten']);
            }])->where('nip_nrp', $request->nip_nrp)->first();

            if (!$peserta) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIP/NRP tidak terdaftar sebagai peserta di sistem'
                ], 404);
            }

            // Cek apakah peserta sudah terdaftar di pelatihan ini
            $existingPendaftaran = Pendaftaran::where('id_peserta', $peserta->id)
                ->where('id_jenis_pelatihan', $request->id_jenis_pelatihan)
                ->first();

            if (!$existingPendaftaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta tidak terdaftar pada pelatihan yang dipilih. Hanya peserta yang sudah didaftarkan oleh admin yang dapat mengakses form ini.'
                ], 403);
            }

            // Format data peserta untuk response
            $pesertaData = [
                'id' => $peserta->id,
                'nip_nrp' => $peserta->nip_nrp,
                'nama_lengkap' => $peserta->nama_lengkap,
                'nama_panggilan' => $peserta->nama_panggilan,
                'jenis_kelamin' => $peserta->jenis_kelamin,
                'agama' => $peserta->agama,
                'tempat_lahir' => $peserta->tempat_lahir,
                'tanggal_lahir' => $peserta->tanggal_lahir,
                'alamat_rumah' => $peserta->alamat_rumah,
                'email_pribadi' => $peserta->email_pribadi,
                'nomor_hp' => $peserta->nomor_hp,
                'pendidikan_terakhir' => $peserta->pendidikan_terakhir,
                'bidang_studi' => $peserta->bidang_studi,
                'bidang_keahlian' => $peserta->bidang_keahlian,
                'status_perkawinan' => $peserta->status_perkawinan,
                'nama_pasangan' => $peserta->nama_pasangan,
                'olahraga_hobi' => $peserta->olahraga_hobi,
                'perokok' => $peserta->perokok,
                'ukuran_kaos' => $peserta->ukuran_kaos,
                'kondisi_peserta' => $peserta->kondisi_peserta,
                'file_ktp' => $peserta->file_ktp,
                'file_pas_foto' => $peserta->file_pas_foto,
                'kepegawaian' => $peserta->kepegawaian ? [
                    'asal_instansi' => $peserta->kepegawaian->asal_instansi,
                    'unit_kerja' => $peserta->kepegawaian->unit_kerja,
                    'id_provinsi' => $peserta->kepegawaian->id_provinsi,
                    'id_kabupaten_kota' => $peserta->kepegawaian->id_kabupaten_kota,
                    'alamat_kantor' => $peserta->kepegawaian->alamat_kantor,
                    'nomor_telepon_kantor' => $peserta->kepegawaian->nomor_telepon_kantor,
                    'email_kantor' => $peserta->kepegawaian->email_kantor,
                    'jabatan' => $peserta->kepegawaian->jabatan,
                    'pangkat' => $peserta->kepegawaian->pangkat,
                    'golongan_ruang' => $peserta->kepegawaian->golongan_ruang,
                    'eselon' => $peserta->kepegawaian->eselon,
                    'tanggal_sk_jabatan' => $peserta->kepegawaian->tanggal_sk_jabatan,
                    'nomor_sk_cpns' => $peserta->kepegawaian->nomor_sk_cpns,
                    'tanggal_sk_cpns' => $peserta->kepegawaian->tanggal_sk_cpns,
                    'tahun_lulus_pkp_pim_iv' => $peserta->kepegawaian->tahun_lulus_pkp_pim_iv,
                ] : null
            ];

            // Ambil data pendaftaran yang sudah ada
            $pendaftaranData = [
                'id' => $existingPendaftaran->id,
                'id_angkatan' => $existingPendaftaran->id_angkatan,
                'status_pendaftaran' => $existingPendaftaran->status_pendaftaran,
                'tanggal_daftar' => $existingPendaftaran->tanggal_daftar,
                'angkatan' => $existingPendaftaran->angkatan ? [
                    'nama_angkatan' => $existingPendaftaran->angkatan->nama_angkatan,
                    'tahun' => $existingPendaftaran->angkatan->tahun
                ] : null
            ];

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
                'kondisi_peserta' => 'nullable|string',
                'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'file_pas_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
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
                'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:5120',
                'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_tugas' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_sehat' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_bebas_narkoba' => 'nullable|file|mimes:pdf|max:5120',
                'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:5120',
                'file_surat_pernyataan_administrasi' => 'nullable|file|mimes:pdf|max:5120',
                'file_sertifikat_penghargaan' => 'nullable|file|mimes:pdf|max:5120',
                'file_sk_cpns' => 'nullable|file|mimes:pdf|max:5120',
                'file_spmt' => 'nullable|file|mimes:pdf|max:5120',
                'file_skp' => 'nullable|file|mimes:pdf|max:5120',
                'file_persetujuan_mentor' => 'nullable|file|mimes:pdf|max:5120',
                'nomor_sk_cpns' => 'nullable|string|max:100',
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
            ]);

            // 2. CEK KESESUAIAN DATA PESERTA DENGAN PENDAFTARAN
            $peserta = Peserta::find($request->peserta_id);
            if (!$peserta) {
                throw ValidationException::withMessages([
                    'peserta_id' => ['Peserta tidak ditemukan']
                ]);
            }

            $pendaftaran = Pendaftaran::find($request->pendaftaran_id);
            if (!$pendaftaran) {
                throw ValidationException::withMessages([
                    'pendaftaran_id' => ['Data pendaftaran tidak ditemukan']
                ]);
            }

            // Verifikasi bahwa peserta benar-benar terdaftar pada pelatihan ini
            if (
                $pendaftaran->id_peserta != $peserta->id ||
                $pendaftaran->id_jenis_pelatihan != $request->id_jenis_pelatihan
            ) {
                throw ValidationException::withMessages([
                    'general' => ['Data tidak sesuai. Peserta tidak terdaftar pada pelatihan ini.']
                ]);
            }

            // 3. VALIDASI BERDASARKAN JENIS PELATIHAN
            $jenisPelatihan = JenisPelatihan::find($request->id_jenis_pelatihan);
            $kode = $jenisPelatihan->kode_pelatihan;
            $additionalRules = [];

            if ($kode === 'PKN_TK_II') {
                $additionalRules = [
                    'eselon' => 'required|string|max:50',
                    'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:5120',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:5120',
                    'file_sk_jabatan' => 'nullable|file|mimes:pdf|max:5120',
                    'file_sk_pangkat' => 'nullable|file|mimes:pdf|max:5120',
                    'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:5120',
                    'file_surat_sehat' => 'nullable|file|mimes:pdf|max:5120',
                    'file_surat_bebas_narkoba' => 'nullable|file|mimes:pdf|max:5120',
                ];
            }

            if ($kode === 'LATSAR') {
                $additionalRules = [
                    'nomor_sk_cpns' => 'required|string|max:100',
                    'tanggal_sk_cpns' => 'required|date',
                    'file_sk_cpns' => 'nullable|file|mimes:pdf|max:5120',
                    'file_spmt' => 'nullable|file|mimes:pdf|max:5120',
                    'file_skp' => 'nullable|file|mimes:pdf|max:5120',
                    'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:5120',
                    'pangkat' => 'required|string|max:50',
                    'sudah_ada_mentor' => 'required|in:Ya,Tidak',
                    'nomor_rekening_mentor' => 'nullable|string|max:200',
                    'npwp_mentor' => 'nullable|string|max:50',
                ];
            }

            if ($kode === 'PKA' || $kode === 'PKP') {
                $additionalRules = [
                    'eselon' => 'required|string|max:50',
                    'tanggal_sk_jabatan' => 'required|date',
                    'tahun_lulus_pkp_pim_iv' => 'nullable|integer',
                    'file_surat_kesediaan' => 'nullable|file|mimes:pdf|max:5120',
                    'file_pakta_integritas' => 'nullable|file|mimes:pdf|max:5120',
                    'file_surat_pernyataan_administrasi' => 'nullable|file|mimes:pdf|max:5120',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:5120',
                    'file_persetujuan_mentor' => 'nullable|file|mimes:pdf|max:5120',
                    'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'sudah_ada_mentor' => 'required|in:Ya,Tidak',
                ];
            }

            // Validasi mentor jika sudah ada mentor
            if ($request->sudah_ada_mentor === 'Ya') {
                $additionalRules['mentor_mode'] = 'required|in:pilih,tambah';

                if ($request->mentor_mode === 'pilih') {
                    $additionalRules['id_mentor'] = 'required|exists:mentor,id';
                } elseif ($request->mentor_mode === 'tambah') {
                    $additionalRules['nama_mentor_baru'] = 'required|string|max:200';
                    $additionalRules['jabatan_mentor_baru'] = 'required|string|max:200';
                }
            }

            // Jalankan validasi tambahan
            if (!empty($additionalRules)) {
                $request->validate($additionalRules);
            }

            // 4. SIMPAN FILE UPLOADS
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

            $files = [];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $fileName = time() . '_' . $field . '_' . $peserta->id . '.' . $request->file($field)->getClientOriginalExtension();
                    $path = $request->file($field)->move(public_path('uploads'), $fileName);
                    $files[$field] = 'uploads/' . $fileName;
                }
            }

            // 5. UPDATE DATA PESERTA
            $peserta->update([
                'nama_panggilan' => $request->nama_panggilan ?? $peserta->nama_panggilan,
                'alamat_rumah' => $request->alamat_rumah ?? $peserta->alamat_rumah,
                'email_pribadi' => $request->email_pribadi ?? $peserta->email_pribadi,
                'nomor_hp' => $request->nomor_hp ?? $peserta->nomor_hp,
                'bidang_studi' => $request->bidang_studi ?? $peserta->bidang_studi,
                'bidang_keahlian' => $request->bidang_keahlian ?? $peserta->bidang_keahlian,
                'status_perkawinan' => $request->status_perkawinan ?? $peserta->status_perkawinan,
                'nama_pasangan' => $request->nama_pasangan ?? $peserta->nama_pasangan,
                'olahraga_hobi' => $request->olahraga_hobi ?? $peserta->olahraga_hobi,
                'perokok' => $request->perokok ?? $peserta->perokok,
                'ukuran_kaos' => $request->ukuran_kaos ?? $peserta->ukuran_kaos,
                'kondisi_peserta' => $request->kondisi_peserta ?? $peserta->kondisi_peserta,
                'file_ktp' => $files['file_ktp'] ?? $peserta->file_ktp,
                'file_pas_foto' => $files['file_pas_foto'] ?? $peserta->file_pas_foto,
            ]);

            // 6. UPDATE KEPEGAWAIAN PESERTA
            $provinsi = Provinsi::where('code', $request->id_provinsi)->orWhere('id', $request->id_provinsi)->first();
            $kabupaten = $request->id_kabupaten_kota ?
                Kabupaten::where('code', $request->id_kabupaten_kota)->orWhere('id', $request->id_kabupaten_kota)->first() :
                null;

            if (!$provinsi) {
                throw ValidationException::withMessages([
                    'id_provinsi' => ['Provinsi tidak ditemukan di database']
                ]);
            }

            KepegawaianPeserta::updateOrCreate(
                ['id_peserta' => $peserta->id],
                [
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
                    'file_sk_jabatan' => $files['file_sk_jabatan'] ?? null,
                    'file_sk_pangkat' => $files['file_sk_pangkat'] ?? null,
                    'nomor_sk_cpns' => $request->nomor_sk_cpns,
                    'tanggal_sk_cpns' => $request->tanggal_sk_cpns,
                    'file_sk_cpns' => $files['file_sk_cpns'] ?? null,
                    'file_spmt' => $files['file_spmt'] ?? null,
                    'file_skp' => $files['file_skp'] ?? null,
                    'tahun_lulus_pkp_pim_iv' => $request->tahun_lulus_pkp_pim_iv,
                ]
            );

            // 7. UPDATE DOKUMEN PENDAFTARAN
            $pendaftaran->update([
                'file_surat_tugas' => $files['file_surat_tugas'] ?? $pendaftaran->file_surat_tugas,
                'file_surat_kesediaan' => $files['file_surat_kesediaan'] ?? $pendaftaran->file_surat_kesediaan,
                'file_pakta_integritas' => $files['file_pakta_integritas'] ?? $pendaftaran->file_pakta_integritas,
                'file_surat_komitmen' => $files['file_surat_komitmen'] ?? $pendaftaran->file_surat_komitmen,
                'file_surat_kelulusan_seleksi' => $files['file_surat_kelulusan_seleksi'] ?? $pendaftaran->file_surat_kelulusan_seleksi,
                'file_surat_sehat' => $files['file_surat_sehat'] ?? $pendaftaran->file_surat_sehat,
                'file_surat_bebas_narkoba' => $files['file_surat_bebas_narkoba'] ?? $pendaftaran->file_surat_bebas_narkoba,
                'file_surat_pernyataan_administrasi' => $files['file_surat_pernyataan_administrasi'] ?? $pendaftaran->file_surat_pernyataan_administrasi,
                'file_sertifikat_penghargaan' => $files['file_sertifikat_penghargaan'] ?? $pendaftaran->file_sertifikat_penghargaan,
                'file_persetujuan_mentor' => $files['file_persetujuan_mentor'] ?? $pendaftaran->file_persetujuan_mentor,
            ]);

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
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()
                ->route('pendaftaran.create')
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
                ->route('pendaftaran.create')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
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
}
