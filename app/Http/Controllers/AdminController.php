<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peserta;
use App\Models\KepegawaianPeserta;
use App\Models\Mentor;
use App\Models\Pendaftaran;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Validation\ValidationException;

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


    public function editData(Request $request)
    {
        try {
            $user = Auth::user();

            // Ambil data peserta berdasarkan user yang login
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
            $pendaftaranTerbaru = $peserta->pendaftaran->first(); // Mengambil dari collection yang sudah di-load

            // Debug untuk melihat struktur
            // dd($pendaftaranTerbaru);

            $provinsiList = Provinsi::orderBy('name')->get();
            $kabupatenList = [];
            

            if ($kepegawaian && $kepegawaian->id_provinsi) {
                $kabupatenList = Kabupaten::where('province_id', $kepegawaian->id_provinsi)
                    ->orderBy('name')
                    ->get();
                // dd($kabupatenList);
            }
            
            $mentorList = Mentor::where('status_aktif', true)->orderBy('nama_mentor')->get();
           
            return view('admin.edit', compact(
                'peserta',
                'kepegawaian',
                'pendaftaranTerbaru',
                'provinsiList',
                'kabupatenList',
                'mentorList'
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

            // Ambil data peserta
            $peserta = Peserta::where('user_id', $user->id)->first();
            dd($peserta);

            if (!$peserta) {
                throw ValidationException::withMessages([
                    'general' => ['Data peserta tidak ditemukan']
                ]);
            }

            // Ambil pendaftaran terbaru
            $pendaftaranTerbaru = $peserta->pendaftaran()->latest()->first();
            $kepegawaian = $peserta->kepegawaian;

            // 1. VALIDASI DASAR
            $validated = $request->validate([
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
                'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
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
            ]);

            // 2. SIMPAN FILE UPLOADS
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
                    try {
                        $folderPath = public_path('uploads/dashboard_edit');
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }

                        $file = $request->file($field);
                        $originalName = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $safeOriginalName = preg_replace('/[^A-Za-z0-9_-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                        $fileName = "{$field}_{$peserta->nip_nrp}_{$safeOriginalName}_" . time() . '.' . $extension;
                        $path = $file->move($folderPath, $fileName);
                        $relativePath = "uploads/dashboard_edit/{$fileName}";
                        $files[$field] = $relativePath;

                        // Hapus file lama jika ada
                        $this->deleteOldFile($peserta, $pendaftaranTerbaru, $kepegawaian, $field);
                    } catch (\Exception $e) {
                        throw ValidationException::withMessages([
                            $field => ['Gagal mengupload file: ' . $e->getMessage()]
                        ]);
                    }
                }
            }

            // 3. UPDATE DATA PESERTA
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

            if (isset($files['file_ktp'])) {
                $pesertaUpdateData['file_ktp'] = $files['file_ktp'];
            }

            if (isset($files['file_pas_foto'])) {
                $pesertaUpdateData['file_pas_foto'] = $files['file_pas_foto'];
            }

            $peserta->update($pesertaUpdateData);

            // 4. UPDATE KEPEGAWAIAN
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

            // 5. UPDATE DOKUMEN PENDAFTARAN
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

            // 6. SIMPAN MENTOR JIKA ADA
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

            // 7. RESPONSE
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

    private function deleteOldFile($peserta, $pendaftaran, $kepegawaian, $field)
    {
        try {
            $oldFilePath = null;

            // Cek di model Peserta
            if (in_array($field, ['file_ktp', 'file_pas_foto']) && $peserta->$field) {
                $oldFilePath = public_path($peserta->$field);
            }
            // Cek di model KepegawaianPeserta
            elseif (
                in_array($field, ['file_sk_jabatan', 'file_sk_pangkat', 'file_sk_cpns', 'file_spmt', 'file_skp'])
                && $kepegawaian && $kepegawaian->$field
            ) {
                $oldFilePath = public_path($kepegawaian->$field);
            }
            // Cek di model Pendaftaran
            elseif ($pendaftaran && $pendaftaran->$field) {
                $oldFilePath = public_path($pendaftaran->$field);
            }

            // Hapus file lama jika ada
            if ($oldFilePath && file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        } catch (\Exception $e) {
            // Log error jika diperlukan
        }
    }
}
