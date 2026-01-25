<?php

namespace App\Http\Controllers;

use App\Models\JenisPelatihan;
use App\Models\Angkatan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataPeserta;
use App\Exports\DataPesertaSmartBangkom;
use App\Exports\KomposisiPeserta;
use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Exports\DataPesertaSmartBangkomTemplate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\HttpFoundation\StreamedResponse;
class ExportController extends Controller
{
    public function index()
    {
        return view('admin.export.datapeserta');
    }

    public function indexKomposisi()
    {
        return view('admin.export.komposisipeserta');
    }

    public function indexAbsen()
    {
        return view('admin.export.absenpeserta');
    }

    /**
     * Export data peserta dengan filter
     */
    public function exportPeserta()
    {
        // Ambil parameter dari request
        $template = request('template'); // wajib
        $jenisPelatihan = request('jenis_pelatihan');
        $angkatan = request('angkatan');
        $tahun = request('tahun');

        // Validasi template wajib dipilih
        if (empty($template)) {
            return redirect()->back()->with('error', 'Silakan pilih template export terlebih dahulu!');
        }

        // Validasi template harus valid
        if (!in_array($template, ['form_registrasi', 'smart_bangkom'])) {
            return redirect()->back()->with('error', 'Template yang dipilih tidak valid!');
        }

        // Buat nama file berdasarkan template dan filter
        $fileNameParts = [];

        // Tambahkan prefix berdasarkan template
        if ($template === 'smart_bangkom') {
            $fileNameParts[] = 'SMART_BANGKOM';
        } else {
            $fileNameParts[] = 'DATA_PESERTA';
        }

        // Tambahkan filter jika ada
        if ($jenisPelatihan) {
            $fileNameParts[] = str_replace(' ', '_', strtoupper($jenisPelatihan));
        }

        if ($angkatan) {
            $fileNameParts[] = str_replace(' ', '_', strtoupper($angkatan));
        }

        if ($tahun) {
            $fileNameParts[] = $tahun;
        }

        // Jika hanya ada prefix template, tambahkan tanggal
        if (count($fileNameParts) === 1) {
            $fileNameParts[] = date('Y_m_d');
        }

        $fileName = implode('_', $fileNameParts) . '.xlsx';

        // Pilih export class dan log aktifitas berdasarkan template
        if ($template === 'smart_bangkom') {
            aktifitas('Mengekspor Data Peserta - Template Smart Bangkom');

            // return Excel::download(
            //     new DataPesertaSmartBangkom($jenisPelatihan, $angkatan, $tahun),
            //     $fileName
            // );
            // return Excel::download(
            //    new DataPesertaSmartBangkomTemplate($jenisPelatihan, $angkatan, $tahun),
            //   $fileName
            // );
            $templatePath = public_path('smartbangkom.xlsx');


            if (!file_exists($templatePath)) {
                abort(404, 'Template Excel tidak ditemukan');
            }


            // 🔑 Load TEMPLATE ASLI
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();


            // 🔍 Ambil data peserta
            $data = Pendaftaran::with(['peserta.kepegawaianPeserta'])
                ->where('status_pendaftaran', 'Diterima')
                ->get();


            // ✅ Mulai isi dari BARIS KE-3
            $row = 3;


            foreach ($data as $item) {
                $p = $item->peserta;
                $k = $p->kepegawaianPeserta;


                // 🔁 Mapping gender sesuai dropdown template
                $gender = match (strtolower($p->jenis_kelamin)) {
                    'perempuan', 'wanita' => 'Wanita',
                    'laki-laki', 'laki laki', 'pria' => 'Pria',
                    default => '',
                };


                // ===== ISI CELL SESUAI TEMPLATE =====
                $sheet->setCellValue("A{$row}", $p->nama_lengkap);
                $sheet->setCellValueExplicit("B{$row}", $p->nip_nrp, DataType::TYPE_STRING);
                $sheet->setCellValue("C{$row}", $gender);
                $sheet->setCellValue("D{$row}", $p->agama);
                $sheet->setCellValue("E{$row}", $p->tempat_lahir);


                // 📅 TANGGAL LAHIR (dd-mm-yy)
                if ($p->tanggal_lahir) {
                    $sheet->setCellValue(
                        "F{$row}",
                        Date::PHPToExcel($p->tanggal_lahir)
                    );


                    $sheet->getStyle("F{$row}")
                        ->getNumberFormat()
                        ->setFormatCode('dd-mm-yyyy');
                } else {
                    $sheet->setCellValue("F{$row}", '');
                }


                $sheet->setCellValue("G{$row}", $p->email_pribadi);
                $sheet->setCellValueExplicit(
                    "H{$row}",
                    $p->nomor_hp ?? ($k->nomor_telepon_kantor ?? ''),
                    DataType::TYPE_STRING
                );


                $sheet->setCellValue("I{$row}", 'PNS');
                $sheet->setCellValue("J{$row}", $k->golongan_ruang ?? '');
                $sheet->setCellValue("K{$row}", $k->pangkat ?? '');
                $sheet->setCellValue("L{$row}", $k->jabatan ?? '');
                $sheet->setCellValue("M{$row}", 'PNBP');
                $sheet->setCellValue("N{$row}", 'APBD');
                $sheet->setCellValue("O{$row}", $k->asal_instansi ?? '');
                $sheet->setCellValue("P{$row}", $k->alamat_kantor ?? '');


                $row++;
            }


            // ⬇️ Download TANPA MERUSAK TEMPLATE
            return response()->streamDownload(function () use ($spreadsheet) {
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            }, 'SMART_BANGKOM.xlsx');
        } else {
            aktifitas('Mengekspor Data Peserta - Template Form Registrasi');

            return Excel::download(
                new DataPeserta($jenisPelatihan, $angkatan, $tahun),
                $fileName
            );
        }
    }

    /**
     * Export komposisi peserta dengan filter
     */
    public function exportKomposisi()
    {
        // Ambil parameter filter dari request
        $jenisPelatihan = request('jenis_pelatihan');
        $angkatan = request('angkatan');
        $tahun = request('tahun');

        // Buat nama file berdasarkan filter yang dipilih
        $fileNameParts = ['KOMPOSISI'];

        if ($jenisPelatihan) {
            $fileNameParts[] = str_replace(' ', '_', strtoupper($jenisPelatihan));
        }

        if ($angkatan) {
            $fileNameParts[] = str_replace(' ', '_', strtoupper($angkatan));
        }

        if ($tahun) {
            $fileNameParts[] = $tahun;
        }

        // Jika tidak ada filter, tambahkan tanggal
        if (count($fileNameParts) === 1) {
            $fileNameParts[] = 'PESERTA';
            $fileNameParts[] = date('Y_m_d');
        }

        $fileName = implode('_', $fileNameParts) . '.xlsx';

        aktifitas('Mengekspor Komposisi Peserta');

        // Kirim parameter filter ke export class
        return Excel::download(
            new KomposisiPeserta($jenisPelatihan, $angkatan, $tahun),
            $fileName
        );
    }

    /**
     * Export absen peserta dengan filter
     */
    public function exportAbsen(Request $request)
    {
        // Ambil filter dari request
        $jenisPelatihan = $request->jenis_pelatihan;
        $angkatan = $request->angkatan;
        $tahun = $request->tahun;

        // Query data peserta dengan filter
        $query = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaianPeserta',
            'angkatan',
            'jenisPelatihan'
        ])->where('status_pendaftaran', 'diterima');

        // Apply filters
        if ($jenisPelatihan) {
            $query->whereHas('jenisPelatihan', function ($q) use ($jenisPelatihan) {
                $q->where('nama_pelatihan', $jenisPelatihan);
            });
        }

        if ($angkatan) {
            $query->whereHas('angkatan', function ($q) use ($angkatan) {
                $q->where('nama_angkatan', $angkatan);
            });
        }

        if ($tahun) {
            $query->whereHas('angkatan', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }

        $pendaftaranList = $query->get();

        // Jika tidak ada data
        if ($pendaftaranList->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data peserta untuk filter yang dipilih.');
        }

        // Format data peserta untuk PDF
        $peserta = $pendaftaranList->map(function ($pendaftaran, $index) {
            $p = $pendaftaran->peserta;
            $kepeg = $p->kepegawaianPeserta;

            // Ambil NDH dari tabel peserta, atau gunakan nomor urut jika tidak ada
            $ndh = $p->ndh ?? ($index + 1);

            return [
                'nama' => $p->nama_lengkap ?? '-',
                'nip' => $p->nip_nrp ?? '-',
                'ndh' => str_pad($ndh, 2, '0', STR_PAD_LEFT), // Format NDH dengan 0 di depan
                'instansi' => $kepeg->asal_instansi ?? '-',
                'gender' => $p->jenis_kelamin == 'Laki-laki' ? 'L' : 'P'
            ];
        })->toArray();

        // Hitung jumlah laki-laki dan perempuan
        $jumlahLaki = collect($peserta)->where('gender', 'L')->count();
        $jumlahPerempuan = collect($peserta)->where('gender', 'P')->count();

        // Data untuk PDF
        $data = [
            'jenis_pelatihan' => $jenisPelatihan ?: 'SEMUA PELATIHAN',
            'angkatan' => $angkatan ? str_replace('Angkatan ', '', $angkatan) : 'SEMUA',
            'tahun' => $tahun ?: date('Y'),
            'hari_tanggal' => '',
            'waktu' => '',
            'materi' => '',
            'narasumber' => '',
            'sesi' => 'I',
            'tanggal_ttd' => Carbon::now()->locale('id')->isoFormat('D MMMM YYYY'),
            'peserta' => $peserta,
            'jumlah_laki' => $jumlahLaki,
            'jumlah_perempuan' => $jumlahPerempuan,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('admin.export.templateabsen', $data);
        $pdf->setPaper('A4', 'portrait');

        // Nama file
        $filename = 'Absensi_' . str_replace(' ', '_', $jenisPelatihan ?: 'Semua') . '_' .
            str_replace(' ', '_', $angkatan ?: 'Semua') . '_' .
            ($tahun ?: 'Semua') . '.pdf';

        aktifitas('Mengekspor Absen Peserta');

        return $pdf->stream($filename);
    }

    // Method untuk menampilkan halaman form export foto
    public function foto()
    {
        return view('admin.export.export-foto');
    }

    // Method untuk memproses export foto
    public function exportFoto(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'jenis_pelatihan' => 'nullable|string',
                'angkatan' => 'nullable|string',
                'tahun' => 'nullable|integer',
            ]);

            // Query untuk mendapatkan peserta berdasarkan filter
            $query = Pendaftaran::with(['peserta', 'angkatan', 'jenisPelatihan']);

            if (!empty($validated['jenis_pelatihan'])) {
                $query->whereHas('jenisPelatihan', function ($q) use ($validated) {
                    $q->where('nama_pelatihan', $validated['jenis_pelatihan']);
                });
            }

            if (!empty($validated['angkatan'])) {
                $query->whereHas('angkatan', function ($q) use ($validated) {
                    $q->where('nama_angkatan', $validated['angkatan']);
                });
            }

            if (!empty($validated['tahun'])) {
                $query->whereHas('angkatan', function ($q) use ($validated) {
                    $q->where('tahun', $validated['tahun']);
                });
            }

            // Ambil data peserta
            $pendaftaran = $query->get();

            if ($pendaftaran->isEmpty()) {
                return response()->json(['error' => 'Tidak ada data peserta ditemukan'], 404);
            }

            // Hitung jumlah peserta dengan foto
            $totalWithFoto = $pendaftaran->filter(function ($item) {
                return !empty($item->peserta->file_pas_foto);
            })->count();

            if ($totalWithFoto === 0) {
                return response()->json(['error' => 'Tidak ada foto yang ditemukan'], 404);
            }

            // Buat file ZIP temporary
            $zipFileName = "foto_peserta_" . now()->format('Ymd_His') . ".zip";
            $tempZipPath = storage_path('app/temp/' . $zipFileName);

            // Pastikan folder temp ada
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $zip = new \ZipArchive();

            if ($zip->open($tempZipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                return response()->json(['error' => 'Gagal membuat file ZIP'], 500);
            }

            $fotoCount = 0;
            $counter = 1; // Inisialisasi counter

            foreach ($pendaftaran as $daftar) {
                $peserta = $daftar->peserta;

                if (!empty($peserta->file_pas_foto)) {
                    try {
                        // Cek apakah file ada di Google Drive
                        if (Storage::disk('google')->exists($peserta->file_pas_foto)) {
                            // Dapatkan konten file dari Google Drive
                            $fileContent = Storage::disk('google')->get($peserta->file_pas_foto);

                            // Generate nama file dengan counter
                            $fileName = $this->generateFileName($peserta, $daftar, $counter);

                            // Tambahkan file ke ZIP
                            $zip->addFromString($fileName, $fileContent);
                            $fotoCount++;
                            $counter++; // Increment counter setiap file berhasil ditambahkan
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error adding file to ZIP: ' . $e->getMessage());
                        continue;
                    }
                }
            }

            // Tambahkan file info
            if ($fotoCount > 0) {
                $infoContent = "EXPORT FOTO PESERTA\n";
                $infoContent .= "========================\n";
                $infoContent .= "Tanggal Export: " . now()->format('d-m-Y H:i:s') . "\n";
                $infoContent .= "Total Foto: " . $fotoCount . " dari " . $pendaftaran->count() . " peserta\n";
                $infoContent .= "Filter:\n";
                $infoContent .= "- Jenis Pelatihan: " . ($validated['jenis_pelatihan'] ?? 'Semua') . "\n";
                $infoContent .= "- Angkatan: " . ($validated['angkatan'] ?? 'Semua') . "\n";
                $infoContent .= "- Tahun: " . ($validated['tahun'] ?? 'Semua') . "\n";
                $zip->addFromString('INFO_EXPORT.txt', $infoContent);
            }

            // Tutup ZIP
            $zip->close();

            // Download file dan hapus setelah selesai
            return response()->download($tempZipPath, $zipFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Export Foto Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Helper function untuk generate nama file
    private function generateFileName($peserta, $pendaftaran, $counter)
    {
        // Ambil ekstensi asli file
        $originalFileName = basename($peserta->file_pas_foto);
        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        if (empty($extension)) {
            // Coba deteksi dari MIME type atau gunakan default
            $extension = 'jpg';
        }

        // Bersihkan karakter khusus untuk nama file
        $cleanString = function ($str) {
            $str = preg_replace('/[^\p{L}\p{N}\s]/u', '', $str); // Hapus simbol
            $str = trim($str);
            $str = str_replace(' ', '_', $str);
            return strtolower($str); // Tambahkan lowercase untuk konsistensi
        };

        // Gunakan nama lengkap peserta
        $namaPeserta = $cleanString($peserta->nama_lengkap ?? 'peserta_' . $peserta->id);

        // Format: counter.namapeserta.extension
        return $counter . '.' . $namaPeserta . '.' . strtolower($extension);
    }


    public function fotoStats(Request $request)
    {
        try {
            $query = Pendaftaran::with(['peserta', 'angkatan', 'jenisPelatihan']);

            if ($request->filled('jenis_pelatihan')) {
                $query->whereHas('jenisPelatihan', function ($q) use ($request) {
                    $q->where('nama_pelatihan', $request->jenis_pelatihan);
                });
            }

            if ($request->filled('angkatan')) {
                $query->whereHas('angkatan', function ($q) use ($request) {
                    $q->where('nama_angkatan', $request->angkatan);
                });
            }

            if ($request->filled('tahun')) {
                $query->whereHas('angkatan', function ($q) use ($request) {
                    $q->where('tahun', $request->tahun);
                });
            }

            $totalPeserta = $query->count();

            $totalWithFoto = $query->whereHas('peserta', function ($q) {
                $q->whereNotNull('file_pas_foto')
                    ->where('file_pas_foto', '!=', '');
            })->count();

            $persentase = $totalPeserta > 0 ? round(($totalWithFoto / $totalPeserta) * 100, 1) : 0;

            return response()->json([
                'success' => true,
                'total_peserta' => $totalPeserta,
                'total_dengan_foto' => $totalWithFoto,
                'persentase' => $persentase
            ]);
        } catch (\Exception $e) {
            \Log::error('Stats Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman view untuk export sertifikat
     */
    public function viewExportSertifikat()
    {
        return view('admin.export.sertifikat');
    }

    /**
     * Export Sertifikat Peserta ke PDF
     * Setiap peserta mendapat 1 halaman landscape
     */
    public function exportSertifikat(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenis_pelatihan' => 'required',
            'angkatan' => 'required',
            'tahun' => 'required'
        ]);

        // Ambil filter dari request
        $jenisPelatihan = $request->jenis_pelatihan;
        $angkatan = $request->angkatan;
        $tahun = $request->tahun;

        // Query data peserta dengan filter dan relasi lengkap
        $query = Pendaftaran::with([
            'peserta',
            'peserta.kepegawaian',
            'peserta.kepegawaian.kabupaten',
            'peserta.kepegawaian.provinsi',
            'angkatan',
            'angkatan.jenisPelatihan',
            'jenisPelatihan'
        ])->where('status_pendaftaran','Diterima');

        // Apply filters
        if ($jenisPelatihan) {
            $query->whereHas('jenisPelatihan', function ($q) use ($jenisPelatihan) {
                $q->where('nama_pelatihan', $jenisPelatihan);
            });
        }

        if ($angkatan) {
            $query->whereHas('angkatan', function ($q) use ($angkatan) {
                $q->where('nama_angkatan', $angkatan);
            });
        }

        if ($tahun) {
            $query->whereHas('angkatan', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }

        // Ambil data dan urutkan berdasarkan NDH atau ID
        $pendaftaranList = $query->get()->sortBy(function ($pendaftaran) {
            return $pendaftaran->peserta->ndh ?? 999;
        });

        // Jika tidak ada data
        if ($pendaftaranList->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data peserta untuk filter yang dipilih.');
        }

        // Format data peserta untuk PDF
        $peserta = $pendaftaranList->map(function ($pendaftaran, $index) {
            $p = $pendaftaran->peserta;
            $kepeg = $p->kepegawaian;

            // Ambil NDH dari tabel peserta, atau gunakan nomor urut jika tidak ada
            $ndh = $p->ndh ?? str_pad($index + 1, 2, '0', STR_PAD_LEFT);

            // Ambil nama kabupaten dari relasi
            $kabupaten = '';
            if ($kepeg && $kepeg->kabupaten) {
                $kabupaten = $kepeg->kabupaten->name;
            } elseif ($kepeg && $kepeg->asal_instansi) {
                // Fallback: ekstrak dari nama instansi
                $kabupaten = $this->extractKabupaten($kepeg->asal_instansi);
            }

            // Ambil foto dari Google Drive dan convert ke base64
            $fotoBase64 = null;
            if ($p->file_pas_foto) {
                $fotoBase64 = $this->getImageFromGoogleDrive($p->file_pas_foto);
            }

            return [
                'nama' => $p->nama_lengkap ?? '-',
                'nip' => $p->nip_nrp ?? '-',
                'ndh' => str_pad($ndh, 2, '0', STR_PAD_LEFT),
                'kabupaten' => $kabupaten ?: '-',
                'foto' => $fotoBase64,
                'instansi' => $kepeg->asal_instansi ?? '-'
            ];
        })->values()->toArray();

        // Ekstrak angkatan tanpa kata "Angkatan"
        $angkatanLabel = str_replace('Angkatan ', '', $angkatan);

        // Convert logo dan badges ke base64
        $logoBase64 = $this->getImageFromLocalFile('gambar/pusjar.png');
        $badge1Base64 = $this->getImageFromLocalFile('gambar/wbbm.png');
        $badge2Base64 = $this->getImageFromLocalFile('gambar/hut.png');
        $bgBannerBase64 = $this->getImageFromLocalFile('gambar/bg_banner.png');

        // Data untuk PDF
        $data = [
            'jenis_pelatihan' => $jenisPelatihan,
            'angkatan' => $angkatanLabel,
            'tahun' => $tahun,
            'peserta' => $peserta,
            'logo' => $logoBase64,
            'badge1' => $badge1Base64,
            'badge2' => $badge2Base64,
            'bg_banner' => $bgBannerBase64,
        ];

        // Generate PDF dengan orientasi landscape
        $pdf = Pdf::loadView('admin.export.templatesertifikat', $data);
        $pdf->setPaper('A4', 'landscape');

        // Set options untuk performance
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        // Nama file
        $filename = 'Sertifikat_' . str_replace(' ', '_', $jenisPelatihan) . '_' .
            str_replace(' ', '_', $angkatan) . '_' .
            $tahun . '.pdf';

        // Log aktivitas
        aktifitas('Mengekspor Sertifikat Peserta ' . $jenisPelatihan . ' ' . $angkatan . ' ' . $tahun);

        return $pdf->stream($filename);
    }

    /**
     * Ambil gambar dari Google Drive dan convert ke base64
     * 
     * @param string $path Path file di Google Drive
     * @return string|null Base64 encoded image dengan data URI
     */
    private function getImageFromGoogleDrive($path)
    {
        try {
            // Cek apakah file ada di Google Drive
            if (!\Storage::disk('google')->exists($path)) {
                return null;
            }

            // Ambil content file dari Google Drive
            $content = \Storage::disk('google')->get($path);

            // Ambil MIME type
            $mimeType = \Storage::disk('google')->mimeType($path);

            // Convert ke base64
            $base64 = base64_encode($content);

            // Return sebagai data URI untuk digunakan di img src
            return 'data:' . $mimeType . ';base64,' . $base64;
        } catch (\Exception $e) {
            // Log error jika perlu
            \Log::error('Error getting image from Google Drive: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ambil gambar dari file lokal dan convert ke base64
     * 
     * @param string $relativePath Path relatif dari public folder
     * @return string|null Base64 encoded image dengan data URI
     */
    private function getImageFromLocalFile($relativePath)
    {
        try {
            $fullPath = public_path($relativePath);

            if (!file_exists($fullPath)) {
                \Log::error('File not found: ' . $fullPath);
                return null;
            }

            $content = file_get_contents($fullPath);
            $mimeType = mime_content_type($fullPath);
            $base64 = base64_encode($content);

            return 'data:' . $mimeType . ';base64,' . $base64;
        } catch (\Exception $e) {
            \Log::error('Error loading local image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Helper function untuk ekstrak nama kabupaten dari instansi
     * Contoh: "Pemerintah Kabupaten Barru" -> "Kabupaten Barru"
     */
    private function extractKabupaten($instansi)
    {
        // Pattern untuk mencari nama kabupaten/kota
        $patterns = [
            '/Kabupaten\s+([A-Za-z\s]+)/i',
            '/Kota\s+([A-Za-z\s]+)/i',
            '/Kab\.\s+([A-Za-z\s]+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $instansi, $matches)) {
                // Return "Kabupaten/Kota Nama"
                if (stripos($matches[0], 'Kab.') !== false) {
                    return 'Kabupaten ' . trim($matches[1]);
                }
                return trim($matches[0]);
            }
        }

        // Jika tidak ketemu pattern, ambil 2-3 kata terakhir
        $words = explode(' ', $instansi);
        if (count($words) >= 2) {
            return implode(' ', array_slice($words, -2));
        }

        return $instansi;
    }

}
