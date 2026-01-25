<?php

namespace App\Http\Controllers;

use App\Models\AksiPerubahan;
use App\Models\Pendaftaran;
use App\Models\JenisPelatihan;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            return view('user.aksi-perubahan.index', [
                'aksiPerubahan' => null,
                'pendaftaran' => null,
                'message' => 'Anda belum terdaftar dalam pelatihan apapun.'
            ]);
        }
        
        // Ambil aksi perubahan untuk pendaftaran ini (seharusnya cuma 1)
        $aksiPerubahan = AksiPerubahan::where('id_pendaftar', $pendaftaran->id)->first();
        $kunci_judul = $pendaftaran->angkatan->kunci_judul ?? false;
        // dd($kunci_judul);
        
        return view('admin.aksiperubahan.index', compact('aksiPerubahan', 'pendaftaran','kunci_judul'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'biodata' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);
        
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
            'biodata' => $request->biodata,
        ];
        
        // Upload file ke Google Drive dengan struktur folder yang sama
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            // Ambil data untuk struktur folder
            $tahun = date('Y');
            $jenisPelatihan = $pendaftaran->jenisPelatihan;
            $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
            $angkatan = $pendaftaran->angkatan;
            $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan);
            $nip = $pendaftaran->peserta->nip_nrp;
            
            // Buat struktur folder: Berkas/Tahun/JenisPelatihan/Angkatan/NIP
            $folderPath = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";
            
            // Nama file: aksi_perubahan.extension
            $fileName = 'aksi_perubahan.' . $extension;
            
            // PATH di Google Drive
            $drivePath = "{$folderPath}/{$fileName}";
            
            // Upload ke Google Drive
            Storage::disk('google')->put(
                $drivePath,
                file_get_contents($file)
            );
            
            $data['file'] = $drivePath;
        }
        
        AksiPerubahan::create($data);
        
        return back()->with('success', 'Aksi Perubahan berhasil ditambahkan!');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'biodata' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);
        
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
            'biodata' => $request->biodata,
        ];
        
        // Upload file baru ke Google Drive (akan overwrite file lama)
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            // Ambil data untuk struktur folder
            $tahun = date('Y');
            $jenisPelatihan = $pendaftaran->jenisPelatihan;
            $kodeJenisPelatihan = str_replace(' ', '_', $jenisPelatihan->kode_pelatihan);
            $angkatan = $pendaftaran->angkatan;
            $namaAngkatan = str_replace(' ', '_', $angkatan->nama_angkatan);
            $nip = $pendaftaran->peserta->nip_nrp;
            
            // Buat struktur folder: Berkas/Tahun/JenisPelatihan/Angkatan/NIP
            $folderPath = "Berkas/{$tahun}/{$kodeJenisPelatihan}/{$namaAngkatan}/{$nip}";
            
            // Nama file: aksi_perubahan.extension
            $fileName = 'aksi_perubahan.' . $extension;
            
            // PATH di Google Drive (tetap sama untuk overwrite)
            $drivePath = "{$folderPath}/{$fileName}";
            
            // Hapus file lama jika ada (optional, untuk kebersihan)
            if ($aksiPerubahan->file && Storage::disk('google')->exists($aksiPerubahan->file)) {
                Storage::disk('google')->delete($aksiPerubahan->file);
            }
            
            // Upload file baru ke Google Drive
            Storage::disk('google')->put(
                $drivePath,
                file_get_contents($file)
            );
            
            $data['file'] = $drivePath;
        }
        
        $aksiPerubahan->update($data);
        
        return back()->with('success', 'Aksi Perubahan berhasil diperbarui!');
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
        if ($aksiPerubahan->file && Storage::disk('google')->exists($aksiPerubahan->file)) {
            Storage::disk('google')->delete($aksiPerubahan->file);
        }
        
        $aksiPerubahan->delete();
        
        return back()->with('success', 'Aksi Perubahan berhasil dihapus!');
    }
}
