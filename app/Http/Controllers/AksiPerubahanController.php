<?php

namespace App\Http\Controllers;

use App\Models\AksiPerubahan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AksiPerubahanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil pendaftaran terbaru user
        $pendaftaran = Pendaftaran::where('id_peserta', $user->peserta_id)
            ->with(['jenisPelatihan', 'angkatan', 'peserta'])
            ->latest('tanggal_daftar')
            ->first();

        if (!$pendaftaran) {
            return view('admin.aksiperubahan.index', [
                'aksiPerubahan' => null,
                'pendaftaran' => null,
                'message' => 'Anda belum terdaftar dalam pelatihan apapun.'
            ]);
        }

        // Ambil aksi perubahan untuk pendaftaran ini
        $aksiPerubahan = AksiPerubahan::where('id_pendaftar', $pendaftaran->id)->first();
        $kunci_judul = $pendaftaran->angkatan->kunci_judul ?? false;

        return view('admin.aksiperubahan.index', compact('aksiPerubahan', 'pendaftaran', 'kunci_judul'));
    }

    public function store(Request $request)
    {
        $kategoriOptions = [
            'Memperkokoh ideologi Pancasila, demokrasi, dan hak asasi manusia (HAM)',
            'Memantapkan sistem pertahanan keamanan negara dan mendorong kemandirian bangsa melalui swasembada pangan, energi, air, ekonomi kreatif, ekonomi hijau, dan ekonomi biru',
            'Meningkatkan lapangan kerja yang berkualitas, mendorong kewirausahaan, mengembangkan industri kreatif, dan melanjutkan pengembangan infrastruktur',
            'Memperkuat pembangunan sumber daya manusia (SDM), sains, teknologi, pendidikan, kesehatan, prestasi olahraga, kesetaraan gender, serta penguatan peran perempuan, pemuda, dan penyandang disabilitas',
            'Melanjutkan hilirisasi dan industrialisasi untuk meningkatkan nilai tambah di dalam negeri',
            'Membangun dari desa dan dari bawah untuk pemerataan ekonomi dan pemberantasan kemiskinan.',
            'Memperkuat reformasi politik, hukum, dan birokrasi, serta memperkuat pencegahan dan pemberantasan korupsi dan narkoba',
            'Memperkuat penyelarasan kehidupan yang harmonis dengan lingkungan, alam, dan budaya, serta peningkatan toleransi antarumat beragama untuk mencapai masyarakat yang adil dan makmur',
        ];

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'abstrak' => 'nullable|string',
            'kategori_aksatika' => ['nullable', Rule::in($kategoriOptions)],
            'file' => 'nullable|file|mimes:pdf|max:5120',
            'link_video' => 'nullable|max:500',
            'link_laporan_majalah' => 'nullable|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();
        $pendaftaran = Pendaftaran::where('id_peserta', $user->peserta_id)
            ->with(['jenisPelatihan', 'angkatan', 'peserta'])
            ->latest('tanggal_daftar')
            ->first();

        if (!$pendaftaran) {
            return back()->with('error', 'Anda belum terdaftar dalam pelatihan.');
        }

        // Cek apakah sudah ada aksi perubahan
        $existing = AksiPerubahan::where('id_pendaftar', $pendaftaran->id)->first();
        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki Aksi Perubahan. Silakan edit yang sudah ada.');
        }

        $data = [
            'id_pendaftar' => $pendaftaran->id,
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'kategori_aksatika' => $request->kategori_aksatika,
            'link_video' => $request->link_video,
            'link_laporan_majalah' => $request->link_laporan_majalah,
        ];

        // Upload file ke Google Drive
        if ($request->hasFile('file')) {
            $filePath = $this->uploadFileToDrive($request->file('file'), $pendaftaran, 'aksi_perubahan', 'pdf');
            if ($filePath) {
                $data['file'] = $filePath;
            }
        }

        AksiPerubahan::create($data);

        return back()->with('success', 'Aksi Perubahan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kategoriOptions = [
            'Memperkokoh ideologi Pancasila, demokrasi, dan hak asasi manusia (HAM)',
            'Memantapkan sistem pertahanan keamanan negara dan mendorong kemandirian bangsa melalui swasembada pangan, energi, air, ekonomi kreatif, ekonomi hijau, dan ekonomi biru',
            'Meningkatkan lapangan kerja yang berkualitas, mendorong kewirausahaan, mengembangkan industri kreatif, dan melanjutkan pengembangan infrastruktur',
            'Memperkuat pembangunan sumber daya manusia (SDM), sains, teknologi, pendidikan, kesehatan, prestasi olahraga, kesetaraan gender, serta penguatan peran perempuan, pemuda, dan penyandang disabilitas',
            'Melanjutkan hilirisasi dan industrialisasi untuk meningkatkan nilai tambah di dalam negeri',
            'Membangun dari desa dan dari bawah untuk pemerataan ekonomi dan pemberantasan kemiskinan.',
            'Memperkuat reformasi politik, hukum, dan birokrasi, serta memperkuat pencegahan dan pemberantasan korupsi dan narkoba',
            'Memperkuat penyelarasan kehidupan yang harmonis dengan lingkungan, alam, dan budaya, serta peningkatan toleransi antarumat beragama untuk mencapai masyarakat yang adil dan makmur',
        ];

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'abstrak' => 'nullable|string',
            'kategori_aksatika' => ['nullable', Rule::in($kategoriOptions)],
            'file' => 'nullable|file|mimes:pdf|max:5120',
            'link_video' => 'nullable|url|max:500',
            'link_laporan_majalah' => 'nullable|url|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $aksiPerubahan = AksiPerubahan::findOrFail($id);

        // Validasi kepemilikan
        $user = auth()->user();
        $pendaftaran = Pendaftaran::where('id_peserta', $user->peserta_id)
            ->with(['jenisPelatihan', 'angkatan', 'peserta'])
            ->first();

        if ($aksiPerubahan->id_pendaftar !== $pendaftaran->id) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
        }

        $data = [
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'kategori_aksatika' => $request->kategori_aksatika,
            'link_video' => $request->link_video,
            'link_laporan_majalah' => $request->link_laporan_majalah,
        ];

        // Upload file baru ke Google Drive jika ada
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($aksiPerubahan->file && Storage::disk('google')->exists($aksiPerubahan->file)) {
                Storage::disk('google')->delete($aksiPerubahan->file);
            }

            $filePath = $this->uploadFileToDrive($request->file('file'), $pendaftaran, 'aksi_perubahan', 'pdf');
            if ($filePath) {
                $data['file'] = $filePath;
            }
        }

        $aksiPerubahan->update($data);

        return back()->with('success', 'Aksi Perubahan berhasil diperbarui!');
    }

    public function uploadPengesahan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'lembar_pengesahan' => 'required|file|mimes:pdf|max:5120',
            'catatan' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $aksiPerubahan = AksiPerubahan::findOrFail($id);

        // Validasi kepemilikan
        $user = auth()->user();
        $pendaftaran = Pendaftaran::where('id_peserta', $user->peserta_id)
            ->with(['jenisPelatihan', 'angkatan', 'peserta'])
            ->first();


        // Hapus file lama jika ada
        if ($aksiPerubahan->lembar_pengesahan && Storage::disk('google')->exists($aksiPerubahan->lembar_pengesahan)) {
            Storage::disk('google')->delete($aksiPerubahan->lembar_pengesahan);
        }

        // Upload file pengesahan ke Google Drive
        $filePath = $this->uploadFileToDrive($request->file('lembar_pengesahan'), $pendaftaran, 'lembar_pengesahan', 'pdf');

        if ($filePath) {
            $aksiPerubahan->update([
                'lembar_pengesahan' => $filePath,
            ]);

            // Log atau simpan catatan jika diperlukan
            if ($request->catatan) {
                // Anda bisa menyimpan catatan di tabel terpisah atau di kolom jika tersedia
                // Contoh: Log::create([...])
            }

            return back()->with('success', 'Lembar pengesahan berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah lembar pengesahan.');
    }

    private function uploadFileToDrive($file, $pendaftaran, $fileName, $extension = null)
    {
        try {
            $tahun = date('Y');
            $jenisPelatihan = $pendaftaran->jenisPelatihan;
            $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan ?? 'pelatihan');
            $angkatan = $pendaftaran->angkatan;
            $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan ?? 'angkatan');
            $nip = $pendaftaran->peserta->nip_nrp ?? 'unknown';

            // Ambil kategori dan wilayah dari angkatan
            $kategori = $angkatan->kategori ?? 'PNBP';
            $wilayah = $angkatan->wilayah ?? null;
            $kategoriFolder = strtoupper($kategori);

            // Buat struktur folder berdasarkan kategori
            if (strtoupper($kategori) === 'FASILITASI') {
                // Struktur untuk Fasilitasi: Berkas/Fasilitasi/Tahun/JenisPelatihan/Angkatan/Wilayah/NIP
                $wilayahFolder = $wilayah ? str_replace(' ', '_', $wilayah) : 'Umum';
                $folderPath = "Berkas/{$kategoriFolder}/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$wilayahFolder}/{$nip}";
            } else {
                // Struktur untuk PNBP: Berkas/PNBP/Tahun/JenisPelatihan/Angkatan/NIP
                $folderPath = "Berkas/{$kategoriFolder}/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";
            }

            // Gunakan extension dari file jika tidak ditentukan
            if (!$extension) {
                $extension = $file->getClientOriginalExtension();
            }

            // Nama file: nama_file.extension
            $finalFileName = $fileName . '.' . $extension;

            // PATH di Google Drive
            $drivePath = "{$folderPath}/{$finalFileName}";

            // Upload ke Google Drive
            Storage::disk('google')->put(
                $drivePath,
                file_get_contents($file)
            );

            return $drivePath;
        } catch (\Exception $e) {
            \Log::error('Error uploading file to Google Drive: ' . $e->getMessage());
            return false;
        }
    }

    public function destroy($id)
    {
        $aksiPerubahan = AksiPerubahan::findOrFail($id);

        // Validasi kepemilikan
        $user = auth()->user();
        $pendaftaran = Pendaftaran::where('id_peserta', $user->peserta_id)->first();

        if ($aksiPerubahan->id_pendaftar !== $pendaftaran->id) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        // Hapus file dari Google Drive jika ada
        $filesToDelete = [
            'file' => $aksiPerubahan->file,
            'lembar_pengesahan' => $aksiPerubahan->lembar_pengesahan,
        ];

        foreach ($filesToDelete as $file) {
            if ($file && Storage::disk('google')->exists($file)) {
                Storage::disk('google')->delete($file);
            }
        }

        $aksiPerubahan->delete();

        return back()->with('success', 'Aksi Perubahan berhasil dihapus!');
    }
    
}
