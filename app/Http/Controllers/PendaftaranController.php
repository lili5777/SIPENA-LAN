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
     * Menampilkan form pendaftaran
     */
    public function create()
    {
        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();
        $mentor = Mentor::where('status_aktif', true)->get();
        if (request()->ajax()) {
            return response()->json([
                'jenis_pelatihan' => $jenisPelatihan,
                'mentor' => $mentor
            ]);
        }
        return view('pendaftaran.create', compact('jenisPelatihan', 'mentor'));
    }


    /**
     * Menampilkan partial form berdasarkan jenis pelatihan
     */

    public function formPartial($type)
    {
        if ($type === 'PKN_TK_II') {
            return view('partials.form-pkn-tk-ii');
        } elseif ($type === 'PD_CPNS') {
            return view('partials.form-pd-cpns');
        } elseif ($type === 'PKA') {
            return view('partials.form-pka');
        }
        return response()->json(['error' => 'Form type not found'], 404);
    }



    /**
     * Menyimpan data pendaftaran
     */
    public function store(Request $request)
    {
        try {
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

            // 3. VALIDASI INPUT UMUM (TANPA unique nip_nrp & email_pribadi)
            $validated = $request->validate(
                [
                    'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
                    'id_angkatan' => 'required|exists:angkatan,id',
                    'nip_nrp' => 'required|string|max:50',
                    'nama_lengkap' => 'required|string|max:200',
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
                    'file_ktp' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
                    'file_pas_foto' => 'nullable|file|mimes:jpg,png|max:5120',
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

                    'nama_panggilan.string' => 'Nama panggilan harus berupa teks.',
                    'nama_panggilan.max'    => 'Nama panggilan maksimal 100 karakter.',

                    'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                    'jenis_kelamin.in'       => 'Jenis kelamin harus Laki-laki atau Perempuan.',

                    'agama.required' => 'Agama wajib dipilih.',
                    'agama.in'       => 'Agama yang dipilih tidak valid.',

                    'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
                    'tempat_lahir.string'   => 'Tempat lahir harus berupa teks.',
                    'tempat_lahir.max'      => 'Tempat lahir maksimal 100 karakter.',

                    'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                    'tanggal_lahir.date'     => 'Tanggal lahir tidak valid.',

                    'alamat_rumah.required' => 'Alamat rumah wajib diisi.',
                    'alamat_rumah.string'   => 'Alamat rumah harus berupa teks.',

                    'email_pribadi.required' => 'Email pribadi wajib diisi.',
                    'email_pribadi.email'    => 'Format email pribadi tidak valid.',
                    'email_pribadi.max'      => 'Email pribadi maksimal 100 karakter.',

                    'nomor_hp.required' => 'Nomor HP wajib diisi.',
                    'nomor_hp.string'   => 'Nomor HP harus berupa teks.',
                    'nomor_hp.max'      => 'Nomor HP maksimal 20 karakter.',

                    'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih.',
                    'pendidikan_terakhir.in'       => 'Pendidikan terakhir yang dipilih tidak valid.',

                    'bidang_studi.string' => 'Bidang studi harus berupa teks.',
                    'bidang_studi.max'    => 'Bidang studi maksimal 100 karakter.',

                    'bidang_keahlian.string' => 'Bidang keahlian harus berupa teks.',
                    'bidang_keahlian.max'    => 'Bidang keahlian maksimal 100 karakter.',

                    'status_perkawinan.in' => 'Status perkawinan yang dipilih tidak valid.',

                    'nama_pasangan.string' => 'Nama pasangan harus berupa teks.',
                    'nama_pasangan.max'    => 'Nama pasangan maksimal 200 karakter.',

                    'olahraga_hobi.string' => 'Olahraga/hobi harus berupa teks.',
                    'olahraga_hobi.max'    => 'Olahraga/hobi maksimal 100 karakter.',

                    'perokok.required' => 'Status perokok wajib dipilih.',
                    'perokok.in'       => 'Status perokok harus Ya atau Tidak.',

                    'ukuran_kaos.in' => 'Ukuran kaos yang dipilih tidak valid.',

                    'kondisi_peserta.string' => 'Kondisi peserta harus berupa teks.',

                    'file_ktp.file'  => 'File KTP harus berupa berkas.',
                    'file_ktp.mimes' => 'File KTP harus berformat pdf, jpg, atau png.',
                    'file_ktp.max'   => 'File KTP maksimal 5 MB.',

                    'file_pas_foto.file'  => 'Pas foto harus berupa berkas.',
                    'file_pas_foto.mimes' => 'Pas foto harus berformat jpg atau png.',
                    'file_pas_foto.max'   => 'Pas foto maksimal 5 MB.',

                    'asal_instansi.required' => 'Asal instansi wajib diisi.',
                    'asal_instansi.string'   => 'Asal instansi harus berupa teks.',
                    'asal_instansi.max'      => 'Asal instansi maksimal 200 karakter.',

                    'unit_kerja.string' => 'Unit kerja harus berupa teks.',
                    'unit_kerja.max'    => 'Unit kerja maksimal 200 karakter.',

                    'id_provinsi.required' => 'Provinsi wajib dipilih.',

                    'alamat_kantor.required' => 'Alamat kantor wajib diisi.',
                    'alamat_kantor.string'   => 'Alamat kantor harus berupa teks.',

                    'nomor_telepon_kantor.string' => 'Nomor telepon kantor harus berupa teks.',
                    'nomor_telepon_kantor.max'    => 'Nomor telepon kantor maksimal 20 karakter.',

                    'email_kantor.email' => 'Format email kantor tidak valid.',
                    'email_kantor.max'   => 'Email kantor maksimal 100 karakter.',

                    'jabatan.required' => 'Jabatan wajib diisi.',
                    'jabatan.string'   => 'Jabatan harus berupa teks.',
                    'jabatan.max'      => 'Jabatan maksimal 200 karakter.',

                    'pangkat.string' => 'Pangkat harus berupa teks.',
                    'pangkat.max'    => 'Pangkat maksimal 50 karakter.',

                    'golongan_ruang.required' => 'Golongan ruang wajib diisi.',
                    'golongan_ruang.string'   => 'Golongan ruang harus berupa teks.',
                    'golongan_ruang.max'      => 'Golongan ruang maksimal 10 karakter.',

                    'eselon.string' => 'Eselon harus berupa teks.',

                    'file_sk_jabatan.file'  => 'SK jabatan harus berupa berkas.',
                    'file_sk_jabatan.mimes' => 'SK jabatan harus berformat pdf.',
                    'file_sk_jabatan.max'   => 'SK jabatan maksimal 5 MB.',

                    'file_sk_pangkat.file'  => 'SK pangkat harus berupa berkas.',
                    'file_sk_pangkat.mimes' => 'SK pangkat harus berformat pdf.',
                    'file_sk_pangkat.max'   => 'SK pangkat maksimal 5 MB.',

                    'file_surat_tugas.file'  => 'Surat tugas harus berupa berkas.',
                    'file_surat_tugas.mimes' => 'Surat tugas harus berformat pdf.',
                    'file_surat_tugas.max'   => 'Surat tugas maksimal 5 MB.',

                    'file_surat_sehat.file'  => 'Surat sehat harus berupa berkas.',
                    'file_surat_sehat.mimes' => 'Surat sehat harus berformat pdf.',
                    'file_surat_sehat.max'   => 'Surat sehat maksimal 5 MB.',

                    'file_surat_bebas_narkoba.file'  => 'Surat bebas narkoba harus berupa berkas.',
                    'file_surat_bebas_narkoba.mimes' => 'Surat bebas narkoba harus berformat pdf.',
                    'file_surat_bebas_narkoba.max'   => 'Surat bebas narkoba maksimal 5 MB.',

                    'file_pakta_integritas.file'  => 'Pakta integritas harus berupa berkas.',
                    'file_pakta_integritas.mimes' => 'Pakta integritas harus berformat pdf.',
                    'file_pakta_integritas.max'   => 'Pakta integritas maksimal 5 MB.',

                    'file_surat_kesediaan.file'  => 'Surat kesediaan harus berupa berkas.',
                    'file_surat_kesediaan.mimes' => 'Surat kesediaan harus berformat pdf.',
                    'file_surat_kesediaan.max'   => 'Surat kesediaan maksimal 5 MB.',

                    'file_surat_komitmen.file'  => 'Surat komitmen harus berupa berkas.',
                    'file_surat_komitmen.mimes' => 'Surat komitmen harus berformat pdf.',
                    'file_surat_komitmen.max'   => 'Surat komitmen maksimal 5 MB.',

                    'file_surat_kelulusan_seleksi.file'  => 'Surat kelulusan seleksi harus berupa berkas.',
                    'file_surat_kelulusan_seleksi.mimes' => 'Surat kelulusan seleksi harus berformat pdf.',
                    'file_surat_kelulusan_seleksi.max'   => 'Surat kelulusan seleksi maksimal 5 MB.',

                    'file_surat_pernyataan_administrasi.file'  => 'Surat pernyataan administrasi harus berupa berkas.',
                    'file_surat_pernyataan_administrasi.mimes' => 'Surat pernyataan administrasi harus berformat pdf.',
                    'file_surat_pernyataan_administrasi.max'   => 'Surat pernyataan administrasi maksimal 5 MB.',

                    'file_sertifikat_penghargaan.file'  => 'Sertifikat penghargaan harus berupa berkas.',
                    'file_sertifikat_penghargaan.mimes' => 'Sertifikat penghargaan harus berformat pdf.',
                    'file_sertifikat_penghargaan.max'   => 'Sertifikat penghargaan maksimal 5 MB.',

                    'file_sk_cpns.file'  => 'SK CPNS harus berupa berkas.',
                    'file_sk_cpns.mimes' => 'SK CPNS harus berformat pdf.',
                    'file_sk_cpns.max'   => 'SK CPNS maksimal 5 MB.',

                    'file_spmt.file'  => 'SPMT harus berupa berkas.',
                    'file_spmt.mimes' => 'SPMT harus berformat pdf.',
                    'file_spmt.max'   => 'SPMT maksimal 5 MB.',

                    'file_skp.file'  => 'SKP harus berupa berkas.',
                    'file_skp.mimes' => 'SKP harus berformat pdf.',
                    'file_skp.max'   => 'SKP maksimal 5 MB.',

                    'file_persetujuan_mentor.file'  => 'Persetujuan mentor harus berupa berkas.',
                    'file_persetujuan_mentor.mimes' => 'Persetujuan mentor harus berformat pdf.',
                    'file_persetujuan_mentor.max'   => 'Persetujuan mentor maksimal 5 MB.',

                    'nomor_sk_cpns.string' => 'Nomor SK CPNS harus berupa teks.',
                    'nomor_sk_cpns.max'    => 'Nomor SK CPNS maksimal 100 karakter.',

                    'tanggal_sk_cpns.date' => 'Tanggal SK CPNS tidak valid.',

                    'tanggal_sk_jabatan.date' => 'Tanggal SK jabatan tidak valid.',

                    'tahun_lulus_pkp_pim_iv.integer' => 'Tahun lulus PKP/PIM IV harus berupa angka.',

                    'nama_mentor.string' => 'Nama mentor harus berupa teks.',
                    'nama_mentor.max'    => 'Nama mentor maksimal 200 karakter.',

                    'jabatan_mentor.string' => 'Jabatan mentor harus berupa teks.',
                    'jabatan_mentor.max'    => 'Jabatan mentor maksimal 200 karakter.',

                    'nomor_rekening_mentor.string' => 'Nomor rekening mentor harus berupa teks.',
                    'nomor_rekening_mentor.max'    => 'Nomor rekening mentor maksimal 200 karakter.',

                    'npwp_mentor.string' => 'NPWP mentor harus berupa teks.',
                    'npwp_mentor.max'    => 'NPWP mentor maksimal 50 karakter.',

                    'has_mentor.in' => 'Pilihan memiliki mentor harus Ya atau Tidak.',
                    'sudah_ada_mentor.in' => 'Pilihan sudah ada mentor harus Ya atau Tidak.',
                ]
            );

            // 4. AMBIL JENIS PELATIHAN UNTUK VALIDASI TAMBAHAN
            $jenisPelatihan = JenisPelatihan::find($request->id_jenis_pelatihan);
            $kode = $jenisPelatihan->kode_pelatihan;
            $additionalRules = [];

            if ($kode === 'PKN_TK_II') {
                $additionalRules = [
                    'eselon' => 'required|string|max:50',
                    'file_pakta_integritas' => 'required|file|mimes:pdf|max:5120',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:5120',
                    'file_sk_jabatan' => 'required|file|mimes:pdf|max:5120',
                    'file_sk_pangkat' => 'required|file|mimes:pdf|max:5120',
                    'file_surat_komitmen' => 'nullable|file|mimes:pdf|max:5120',
                ];
            }

            if ($kode === 'PD_CPNS') {
                $additionalRules = [
                    'nomor_sk_cpns' => 'required|string|max:100',
                    'tanggal_sk_cpns' => 'required|date',
                    'file_sk_cpns' => 'required|file|mimes:pdf|max:5120',
                    'file_spmt' => 'required|file|mimes:pdf|max:5120',
                    'file_skp' => 'nullable|file|mimes:pdf|max:5120',
                    'file_surat_kesediaan' => 'required|file|mimes:pdf|max:5120',
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
                    'tahun_lulus_pkp_pim_iv' => 'required|integer',
                    'file_surat_kesediaan' => 'required|file|mimes:pdf|max:5120',
                    'file_pakta_integritas' => 'required|file|mimes:pdf|max:5120',
                    'file_surat_pernyataan_administrasi' => 'nullable|file|mimes:pdf|max:5120',
                    'file_surat_kelulusan_seleksi' => 'nullable|file|mimes:pdf|max:5120',
                    'file_persetujuan_mentor' => 'nullable|file|mimes:pdf|max:5120',
                    'file_ktp' => 'required|file|mimes:pdf,jpg,png|max:5120',
                    'sudah_ada_mentor' => 'required|in:Ya,Tidak',
                ];
            }

            // Jika sudah ada mentor dan mode pilih
            if ($request->sudah_ada_mentor === 'Ya') {
                $additionalRules['mentor_mode'] = 'required|in:pilih,tambah';

                if ($request->mentor_mode === 'pilih') {
                    $additionalRules['id_mentor'] = 'required|exists:mentor,id';
                } elseif ($request->mentor_mode === 'tambah') {
                    $additionalRules['nama_mentor_baru'] = 'required|string|max:200';
                    $additionalRules['jabatan_mentor_baru'] = 'required|string|max:200';
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

            $files = [];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $fileName = time() . '_' . $field . '.' . $request->file($field)->getClientOriginalExtension();
                    $path = $request->file($field)->storeAs('uploads/pendaftaran', $fileName, 'public');
                    $files[$field] = $path;
                }
            }


            // 6. SIMPAN/UPDATE PESERTA (REUSE JIKA SUDAH ADA)
            if (!$peserta) {
                // Buat peserta baru
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
            } else {
                $peserta->update([
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
                    'file_ktp' => $files['file_ktp'] ?? $peserta->file_ktp,
                    'file_pas_foto' => $files['file_pas_foto'] ?? $peserta->file_pas_foto,
                ]);
            }


            // 7. SIMPAN/UPDATE KEPEGAWAIAN PESERTA (updateOrCreate)
            $provinsi = Provinsi::where('code', $request->id_provinsi)->first();
            $kabupaten = $request->id_kabupaten_kota ? Kabupaten::where('code', $request->id_kabupaten_kota)->first() : null;

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
                    'pangkat' => $request->pangkat ?? null,
                    'golongan_ruang' => $request->golongan_ruang,
                    'eselon' => $request->eselon ?? null,
                    'tanggal_sk_jabatan' => $request->tanggal_sk_jabatan ?? null,
                    'file_sk_jabatan' => $files['file_sk_jabatan'] ?? null,
                    'file_sk_pangkat' => $files['file_sk_pangkat'] ?? null,
                    'nomor_sk_cpns' => $request->nomor_sk_cpns ?? null,
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
                    // Buat mentor baru
                    $mentor = Mentor::create([
                        'nama_mentor' => $request->nama_mentor_baru,
                        'jabatan_mentor' => $request->jabatan_mentor_baru,
                        'nomor_rekening' => $request->nomor_rekening_mentor_baru,
                        'npwp_mentor' => $request->npwp_mentor_baru,
                        'status_aktif' => true,
                    ]);
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



            // 10. RESPONSE
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil disimpan!',
                    'pendaftaran_id' => $pendaftaran->id,  // ← Real ID
                    'redirect_url' => route('pendaftaran.success')
                ], 200);
            }


            // Simpan ke session untuk redirect biasa (non-AJAX)
            session(['pendaftaran_id' => $pendaftaran->id]);

            return redirect()->route('pendaftaran.success')
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
                ->route('pendaftaran.create')
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
                ->route('pendaftaran.create')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }



    /**
     * Success page setelah pendaftaran
     */
    public function success(Request $request)
    {
        $pendaftaran_id = $request->session()->get('pendaftaran_id') ?? $request->get('id');;
        

        if ($pendaftaran_id) {
            $pendaftaran = Pendaftaran::with(['peserta', 'jenisPelatihan', 'angkatan'])
                ->where('id', $pendaftaran_id)  // id = PK Laravel
                ->firstOrFail();

        } else {
                return redirect()->route('home')->with('error', 'Data pendaftaran tidak ditemukan.');
            
        }

        return view('pendaftaran.success', compact('pendaftaran'));
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

    public function getMentors()
    {
        $mentors = Mentor::where('status_aktif', true)->get();

        return response()->json($mentors);
    }

    
}