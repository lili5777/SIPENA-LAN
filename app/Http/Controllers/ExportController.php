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
use App\Exports\JadwalSeminar;
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
     * Halaman view untuk export jadwal seminar
     */
    public function indexJadwalSeminar()
    {
        return view('admin.export.jadwalseminar');
    }

    /**
     * Export jadwal seminar peserta
     */
    public function exportJadwalSeminar(Request $request)
    {
        $jenisPelatihan = $request->jenis_pelatihan;
        $angkatan = $request->angkatan;
        $tahun = $request->tahun;
        $kategori = $request->kategori;
        $wilayah = $request->wilayah;

        // Validasi data kosong
        $query = Pendaftaran::where('status_pendaftaran', 'Diterima');

        if ($jenisPelatihan) {
            $query->whereHas('jenisPelatihan', fn($q) => $q->where('nama_pelatihan', $jenisPelatihan));
        }

        if ($angkatan) {
            $query->whereHas('angkatan', fn($q) => $q->where('nama_angkatan', $angkatan));
        }

        if ($tahun) {
            $query->whereHas('angkatan', fn($q) => $q->where('tahun', $tahun));
        }

        if ($kategori === 'PNBP') {
            $query->whereHas('angkatan', fn($q) => $q->where('kategori', 'PNBP'));
        } elseif ($kategori === 'FASILITASI') {
            $query->whereHas('angkatan', function ($q) use ($wilayah) {
                $q->where('kategori', 'FASILITASI');
                if ($wilayah && trim($wilayah) !== '') {
                    $q->where('wilayah', 'like', '%' . trim($wilayah) . '%');
                }
            });
        } else if ($kategori === 'SEMUA') {
            if ($wilayah && trim($wilayah) !== '') {
                $query->whereHas('angkatan', fn($q) => $q->where('wilayah', 'like', '%' . trim($wilayah) . '%'));
            }
        }

        if (!$query->exists()) {
            return back()->with('error', 'Data peserta tidak ditemukan sesuai filter yang dipilih.');
        }

        // Buat nama file
        $fileNameParts = ['JADWAL_SEMINAR'];

        if ($jenisPelatihan) $fileNameParts[] = strtoupper(str_replace(' ', '_', $jenisPelatihan));
        if ($angkatan) $fileNameParts[] = strtoupper(str_replace(' ', '_', $angkatan));
        if ($tahun) $fileNameParts[] = $tahun;
        if ($kategori && $kategori !== 'SEMUA') $fileNameParts[] = $kategori;
        if ($wilayah) $fileNameParts[] = strtoupper(str_replace(' ', '_', $wilayah));

        $fileNameParts[] = now()->format('Ymd_His');
        $fileName = implode('_', $fileNameParts) . '.xlsx';

        aktifitas('Mengekspor Jadwal Seminar Peserta');

        return Excel::download(
            new JadwalSeminar($jenisPelatihan, $angkatan, $tahun, $kategori, $wilayah),
            $fileName
        );
    }

   
    public function exportPeserta(Request $request)
{
    // =========================
    // 1️⃣ Ambil parameter
    // =========================
    $template       = $request->template;
    $jenisPelatihan = $request->jenis_pelatihan;
    $angkatan       = $request->angkatan;
    $tahun          = $request->tahun;
    $kategori       = $request->kategori;
    $wilayah        = $request->wilayah;

    // =========================
    // 2️⃣ Validasi template
    // =========================
    if (!$template) {
        return back()->with('error', 'Silakan pilih template export terlebih dahulu.');
    }

    if (!in_array($template, ['form_registrasi', 'smart_bangkom'])) {
        return back()->with('error', 'Template export tidak valid.');
    }

    // =========================
    // 3️⃣ QUERY UTAMA
    // =========================
    $query = Pendaftaran::with([
        'peserta',
        'peserta.kepegawaianPeserta',
        'angkatan',
        'jenisPelatihan'
    ])->where('status_pendaftaran', 'Diterima');

    if ($jenisPelatihan) {
        $query->whereHas(
            'jenisPelatihan',
            fn($q) =>
            $q->where('nama_pelatihan', $jenisPelatihan)
        );
    }

    if ($angkatan) {
        $query->whereHas(
            'angkatan',
            fn($q) =>
            $q->where('nama_angkatan', $angkatan)
        );
    }

    if ($tahun) {
        $query->whereHas(
            'angkatan',
            fn($q) =>
            $q->where('tahun', $tahun)
        );
    }

    // =========================
    // 🔥 FILTER KATEGORI & WILAYAH (OPSIONAL)
    // =========================
    if ($kategori === 'PNBP') {
        $query->whereHas('angkatan', function ($q) {
            $q->where('kategori', 'PNBP');
        });
    } elseif ($kategori === 'FASILITASI') {
        $query->whereHas('angkatan', function ($q) use ($wilayah) {
            $q->where('kategori', 'FASILITASI');
            if ($wilayah && trim($wilayah) !== '') {
                // Gunakan LIKE untuk partial match
                $q->where('wilayah', 'like', '%' . trim($wilayah) . '%');
            }
        });
    } else if ($kategori === 'SEMUA') {
        // Jika kategori SEMUA, kita tetap bisa filter wilayah jika dipilih
        if ($wilayah && trim($wilayah) !== '') {
            $query->whereHas('angkatan', function ($q) use ($wilayah) {
                // Gunakan LIKE untuk partial match
                $q->where('wilayah', 'like', '%' . trim($wilayah) . '%');
            });
        }
    }

    // =========================
    // 🔥 VALIDASI DATA KOSONG (GLOBAL)
    // =========================
    if (!$query->exists()) {
        return back()->with(
            'error',
            'Data peserta tidak ditemukan sesuai filter yang dipilih.'
        );
    }

    // =========================
    // 4️⃣ NAMA FILE
    // =========================
    $fileNameParts = [
        $template === 'smart_bangkom' ? 'SMART_BANGKOM' : 'DATA_PESERTA'
    ];

    if ($jenisPelatihan) $fileNameParts[] = strtoupper(str_replace(' ', '_', $jenisPelatihan));
    if ($angkatan)       $fileNameParts[] = strtoupper(str_replace(' ', '_', $angkatan));
    if ($tahun)          $fileNameParts[] = $tahun;
    if ($kategori && $kategori !== 'SEMUA') $fileNameParts[] = $kategori;
    if ($wilayah)        $fileNameParts[] = strtoupper(str_replace(' ', '_', $wilayah));

    $fileNameParts[] = now()->format('Ymd_His');
    $fileName = implode('_', $fileNameParts) . '.xlsx';

    // =========================
    // 5️⃣ TEMPLATE SMART BANGKOM
    // =========================
    if ($template === 'smart_bangkom') {

        aktifitas('Mengekspor Data Peserta - Template Smart Bangkom');

        $templatePath = public_path('smartbangkom.xlsx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template Smart Bangkom tidak ditemukan.');
        }

        $data = $query->get();

        // =========================
        // 🔥 FUNGSI KONVERSI ANGKA ROMAWI KE DESIMAL
        // =========================
        $romanToDecimal = function ($roman) {
            $romanNumerals = [
                'I' => 1, 'V' => 5, 'X' => 10, 'L' => 50,
                'C' => 100, 'D' => 500, 'M' => 1000
            ];
            
            $result = 0;
            $prevValue = 0;
            
            // Hapus spasi dan uppercase
            $roman = strtoupper(trim($roman));
            
            for ($i = strlen($roman) - 1; $i >= 0; $i--) {
                $currentValue = $romanNumerals[$roman[$i]] ?? 0;
                
                if ($currentValue < $prevValue) {
                    $result -= $currentValue;
                } else {
                    $result += $currentValue;
                }
                
                $prevValue = $currentValue;
            }
            
            return $result;
        };

        // =========================
        // 🔥 SORTING DATA
        // =========================
        $data = $data->sort(function ($a, $b) use ($romanToDecimal) {
            // 1. Sorting berdasarkan angkatan (romawi terkecil)
            $angkatanA = $romanToDecimal($a->angkatan->nama_angkatan ?? '');
            $angkatanB = $romanToDecimal($b->angkatan->nama_angkatan ?? '');
            
            if ($angkatanA != $angkatanB) {
                return $angkatanA <=> $angkatanB;
            }
            
            // 2. Prioritaskan kategori PNBP
            $kategoriA = strtoupper($a->angkatan->kategori ?? '');
            $kategoriB = strtoupper($b->angkatan->kategori ?? '');
            
            if ($kategoriA === 'PNBP' && $kategoriB !== 'PNBP') {
                return -1;
            }
            if ($kategoriA !== 'PNBP' && $kategoriB === 'PNBP') {
                return 1;
            }
            
            // 3. Sorting berdasarkan NIP/NRP terkecil
            $nipA = $a->peserta->nip_nrp ?? '';
            $nipB = $b->peserta->nip_nrp ?? '';
            
            return strcmp($nipA, $nipB);
        });

        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // mulai baris ke-3
        $row = 3;

        foreach ($data as $item) {
            $p = $item->peserta;
            $k = $p->kepegawaianPeserta;

            $gender = match (strtolower($p->jenis_kelamin)) {
                'perempuan', 'wanita' => 'Wanita',
                'laki-laki', 'laki laki', 'pria' => 'Pria',
                default => '',
            };

            // 🔥 NORMALISASI AGAMA
            $agama = match (strtolower(trim($p->agama ?? ''))) {
                'kristen' => 'Kristen Protestan',
                'katolik' => 'Kristen Katolik',
                default => $p->agama,
            };

            // 🔥 GOLONGAN RUANG HURUF KAPITAL
            $golonganRuang = strtoupper($k->golongan_ruang ?? '');

            // 🔥 POLA PENYELENGGARAAN BERDASARKAN KATEGORI ANGKATAN
            $polaPenyelenggaraan = match (strtoupper($item->angkatan->kategori ?? '')) {
                'PNBP' => 'Pengiriman/PNBP',
                'FASILITASI' => 'Fasilitasi',
                default => 'Pengiriman/PNBP',
            };

            $sheet->setCellValue("A$row", $p->nama_lengkap);
            $sheet->setCellValueExplicit("B$row", $p->nip_nrp, DataType::TYPE_STRING);
            $sheet->setCellValue("C$row", $gender);
            $sheet->setCellValue("D$row", $agama);
            $sheet->setCellValue("E$row", $p->tempat_lahir);

            if ($p->tanggal_lahir) {
                $sheet->setCellValue("F$row", Date::PHPToExcel($p->tanggal_lahir));
                $sheet->getStyle("F$row")
                    ->getNumberFormat()
                    ->setFormatCode('dd-mm-yyyy');
            }

            $sheet->setCellValue("G$row", $p->email_pribadi);
            $sheet->setCellValueExplicit(
                "H$row",
                $p->nomor_hp ?? ($k->nomor_telepon_kantor ?? ''),
                DataType::TYPE_STRING
            );

            $sheet->setCellValue("I$row", 'PNS');
            $sheet->setCellValue("J$row", $golonganRuang);
            $sheet->setCellValue("K$row", $k->pangkat ?? '');
            $sheet->setCellValue("L$row", $k->jabatan ?? '');
            $sheet->setCellValue("M$row", $polaPenyelenggaraan);
            $sheet->setCellValue("N$row", 'APBD');
            $sheet->setCellValue("O$row", $k->asal_instansi ?? '');
            $sheet->setCellValue("P$row", $k->alamat_kantor ?? '');

            $row++;
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            IOFactory::createWriter($spreadsheet, 'Xlsx')->save('php://output');
        }, $fileName);
    }

    // =========================
    // 6️⃣ TEMPLATE FORM REGISTRASI
    // =========================
    aktifitas('Mengekspor Data Peserta - Template Form Registrasi');

    return Excel::download(
        new DataPeserta($jenisPelatihan, $angkatan, $tahun, $kategori, $wilayah),
        $fileName
    );
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
        $kategori = request('kategori');
        $wilayah = request('wilayah');

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
            new KomposisiPeserta($jenisPelatihan, $angkatan, $tahun, $kategori, $wilayah),
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
        $kategori = $request->kategori;
        $wilayah = $request->wilayah;

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

        if ($kategori === 'PNBP') {
            $query->whereHas('angkatan', function ($q) {
                $q->where('kategori', 'PNBP');
            });
        } elseif ($kategori === 'FASILITASI') {
            $query->whereHas('angkatan', function ($q) use ($wilayah) {
                $q->where('kategori', 'FASILITASI');
                if ($wilayah && trim($wilayah) !== '') {
                    $q->where('wilayah', 'like', '%' . trim($wilayah) . '%');
                }
            });
        } else if ($kategori === 'SEMUA') {
            if ($wilayah && trim($wilayah) !== '') {
                $query->whereHas('angkatan', function ($q) use ($wilayah) {
                    $q->where('wilayah', 'like', '%' . trim($wilayah) . '%');
                });
            }
        }

        // ✅ PERBAIKAN: Ambil data dan sorting berdasarkan NDH
        $pendaftaranList = $query->get()->sortBy(function ($pendaftaran) {
            // Konversi NDH ke integer untuk sorting numerik yang benar
            $ndh = $pendaftaran->peserta->ndh ?? 9999;
            return (int) $ndh;
        })->values(); // ⚠️ PENTING: values() untuk reset index array setelah sorting

        // Jika tidak ada data
        if ($pendaftaranList->isEmpty()) {
            return back()->with('error', 'Tidak ada data peserta ditemukan sesuai filter yang dipilih.');
        }

        // Format data peserta untuk PDF
        $peserta = $pendaftaranList->map(function ($pendaftaran, $index) {
            $p = $pendaftaran->peserta;
            $kepeg = $p->kepegawaianPeserta;

            // Ambil NDH dari tabel peserta
            $ndh = $p->ndh ?? 9999;

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
                'kategori' => 'nullable|string',
                'wilayah' => 'nullable|string',
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

            if (!empty($validated['kategori'])) {
                if ($validated['kategori'] === 'PNBP') {
                    $query->whereHas('angkatan', function ($q) {
                        $q->where('kategori', 'PNBP');
                    });
                } elseif ($validated['kategori'] === 'FASILITASI') {
                    $query->whereHas('angkatan', function ($q) use ($validated) {
                        $q->where('kategori', 'FASILITASI');
                        if (!empty($validated['wilayah'])) {
                            $q->where('wilayah', 'like', '%' . trim($validated['wilayah']) . '%');
                        }
                    });
                }
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
            // $counter = 1; 

            foreach ($pendaftaran as $daftar) {
                $peserta = $daftar->peserta;

                if (!empty($peserta->file_pas_foto)) {
                    try {
                        // Cek apakah file ada di Google Drive
                        if (Storage::disk('google')->exists($peserta->file_pas_foto)) {
                            // Dapatkan konten file dari Google Drive
                            $fileContent = Storage::disk('google')->get($peserta->file_pas_foto);


                            $fileName = $this->generateFileName($peserta, $daftar);

                            // Tambahkan file ke ZIP
                            $zip->addFromString($fileName, $fileContent);
                            $fotoCount++;
                            // $counter++; // Increment counter setiap file berhasil ditambahkan
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
    private function generateFileName($peserta, $pendaftaran)
    {
        // Ambil ekstensi asli file
        $originalFileName = basename($peserta->file_pas_foto);
        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        if (empty($extension)) {
            $extension = 'jpg';
        }

        // Helper untuk bersihkan string
        $cleanString = function ($str) {
            $str = preg_replace('/[^\p{L}\p{N}\s]/u', '', $str);
            $str = trim($str);
            $str = str_replace(' ', '_', $str);
            return strtolower($str);
        };

        // 🔢 Ambil NDH
        $ndh = $peserta->ndh ?? 'ndh_' . $peserta->id;

        // Nama peserta
        $namaPeserta = $cleanString($peserta->nama_lengkap ?? 'peserta');

        // 🧾 Format akhir: NDH_nama.extension
        return $ndh . '.' . $namaPeserta . '.' . strtolower($extension);
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

            if ($request->filled('kategori')) {
                if ($request->kategori === 'PNBP') {
                    $query->whereHas('angkatan', function ($q) {
                        $q->where('kategori', 'PNBP');
                    });
                } elseif ($request->kategori === 'FASILITASI') {
                    $query->whereHas('angkatan', function ($q) use ($request) {
                        $q->where('kategori', 'FASILITASI');
                        if ($request->filled('wilayah')) {
                            $q->where('wilayah', 'like', '%' . trim($request->wilayah) . '%');
                        }
                    });
                }
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
    // Set memory limit lebih tinggi
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', '300'); // 5 menit
    
    // Ambil filter dari request
    $jenisPelatihan = $request->jenis_pelatihan;
    $angkatan       = $request->angkatan;
    $tahun          = $request->tahun;
    $kategori       = $request->kategori;
    $wilayah        = $request->wilayah;

    // Query data peserta dengan filter dan relasi lengkap
    $query = Pendaftaran::with([
        'peserta',
        'peserta.kepegawaian',
        'peserta.kepegawaian.kabupaten',
        'peserta.kepegawaian.provinsi',
        'angkatan',
        'angkatan.jenisPelatihan',
        'jenisPelatihan'
    ])->where('status_pendaftaran', 'Diterima');

    // --- Filter: Jenis Pelatihan ---
    if ($jenisPelatihan) {
        $query->whereHas('jenisPelatihan', function ($q) use ($jenisPelatihan) {
            $q->where('nama_pelatihan', $jenisPelatihan);
        });
    }

    // --- Filter: Angkatan ---
    if ($angkatan) {
        $query->whereHas('angkatan', function ($q) use ($angkatan) {
            $q->where('nama_angkatan', $angkatan);
        });
    }

    // --- Filter: Tahun ---
    if ($tahun) {
        $query->whereHas('angkatan', function ($q) use ($tahun) {
            $q->where('tahun', $tahun);
        });
    }

    // --- Filter: Kategori & Wilayah ---
    if ($kategori === 'PNBP') {
        $query->whereHas('angkatan', function ($q) {
            $q->where('kategori', 'PNBP');
        });
    } elseif ($kategori === 'FASILITASI') {
        $query->whereHas('angkatan', function ($q) use ($wilayah) {
            $q->where('kategori', 'FASILITASI');
            if ($wilayah && trim($wilayah) !== '') {
                $q->where('wilayah', 'like', '%' . trim($wilayah) . '%');
            }
        });
    } else {
        if ($wilayah && trim($wilayah) !== '') {
            $query->whereHas('angkatan', function ($q) use ($wilayah) {
                $q->where('wilayah', 'like', '%' . trim($wilayah) . '%');
            });
        }
    }

    // Ambil data dan urutkan berdasarkan NDH
    $pendaftaranList = $query->get()->sortBy(function ($pendaftaran) {
        return $pendaftaran->peserta->ndh ?? 999;
    });

    // Jika tidak ada data
    if ($pendaftaranList->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada data peserta untuk filter yang dipilih.');
    }

    // OPTIMASI: Batasi jumlah peserta per batch jika terlalu banyak
    $totalPeserta = $pendaftaranList->count();
    if ($totalPeserta > 50) {
        return redirect()->back()->with('error', 
            "Terlalu banyak data ({$totalPeserta} peserta). Silakan filter lebih spesifik atau export per angkatan."
        );
    }

    // Format data peserta untuk PDF dengan optimasi
    $peserta = $pendaftaranList->map(function ($pendaftaran, $index) {
        $p     = $pendaftaran->peserta;
        $kepeg = $p->kepegawaian;

        // Ambil NDH dari tabel peserta
        $ndh = $p->ndh ?? str_pad($index + 1, 2, '0', STR_PAD_LEFT);

        // Ambil nama kabupaten dari relasi
        $kabupaten = '';
        if ($kepeg && $kepeg->kabupaten) {
            $kabupaten = $kepeg->kabupaten->name;
        } elseif ($kepeg && $kepeg->asal_instansi) {
            $kabupaten = $this->extractKabupaten($kepeg->asal_instansi);
        }

        // OPTIMASI: Ambil foto dengan resize
        $fotoBase64 = null;
        if ($p->file_pas_foto) {
            $fotoBase64 = $this->getOptimizedImageFromGoogleDrive($p->file_pas_foto);
        }

        return [
            'nama'      => $p->nama_lengkap ?? '-',
            'nip'       => $p->nip_nrp ?? '-',
            'ndh'       => str_pad($ndh, 2, '0', STR_PAD_LEFT),
            'kabupaten' => $kabupaten ?: '-',
            'foto'      => $fotoBase64,
            'instansi'  => $kepeg->asal_instansi ?? '-'
        ];
    })->values()->toArray();

    // Ekstrak angkatan tanpa kata "Angkatan"
    $angkatanLabel = $angkatan ? str_replace('Angkatan ', '', $angkatan) : 'SEMUA';

    // Convert logo dan badges ke base64
    $logoBase64     = $this->getImageFromLocalFile('gambar/pusjar.png');
    $badge1Base64   = $this->getImageFromLocalFile('gambar/wbbm.png');
    $badge2Base64   = $this->getImageFromLocalFile('gambar/hut.png');
    $bgBannerBase64 = $this->getImageFromLocalFile('gambar/bg_banner.png');

    // Data untuk PDF
    $data = [
        'jenis_pelatihan' => $jenisPelatihan ?: 'SEMUA PELATIHAN',
        'angkatan'        => $angkatanLabel,
        'tahun'           => $tahun ?: date('Y'),
        'peserta'         => $peserta,
        'logo'            => $logoBase64,
        'badge1'          => $badge1Base64,
        'badge2'          => $badge2Base64,
        'bg_banner'       => $bgBannerBase64,
    ];

    // Generate PDF dengan orientasi landscape
    $pdf = Pdf::loadView('admin.export.templatesertifikat', $data);
    $pdf->setPaper('A4', 'landscape');

    // Set options untuk performance
    $pdf->setOptions([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled'     => true,
        'defaultFont'         => 'Arial',
        'dpi'                 => 96, // Turunkan DPI untuk hemat memory
        'isPhpEnabled'        => false
    ]);

    // Nama file
    $filename = 'Sertifikat_'
        . str_replace(' ', '_', $jenisPelatihan ?: 'Semua')
        . '_' . str_replace(' ', '_', $angkatan ?: 'Semua')
        . '_' . ($tahun ?: 'Semua')
        . '.pdf';

    // Log aktivitas
    aktifitas('Mengekspor Sertifikat Peserta ' . ($jenisPelatihan ?: 'Semua') . ' ' . ($angkatan ?: 'Semua') . ' ' . ($tahun ?: 'Semua'));

    // Clear memory sebelum return
    gc_collect_cycles();

    return $pdf->stream($filename);
}

/**
 * Get optimized image from Google Drive (resized untuk hemat memory)
 */
private function getOptimizedImageFromGoogleDrive($filePath, $maxWidth = 300, $maxHeight = 400)
{
    try {
        $disk = Storage::disk('google');
        
        if (!$disk->exists($filePath)) {
            \Log::warning("File foto tidak ditemukan: {$filePath}");
            return $this->getPlaceholderImage();
        }

        // Ambil konten file
        $imageContent = $disk->get($filePath);
        
        // Buat image resource dari string
        $image = @imagecreatefromstring($imageContent);
        
        if ($image === false) {
            \Log::warning("Gagal membuat image dari file: {$filePath}");
            return $this->getPlaceholderImage();
        }

        // Dapatkan ukuran asli
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        // Hitung ukuran baru dengan maintain aspect ratio
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        $newWidth = (int)($originalWidth * $ratio);
        $newHeight = (int)($originalHeight * $ratio);

        // Buat image baru dengan ukuran yang sudah di-resize
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency untuk PNG
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        
        // Resize image
        imagecopyresampled(
            $resizedImage, 
            $image, 
            0, 0, 0, 0, 
            $newWidth, $newHeight, 
            $originalWidth, $originalHeight
        );

        // Convert ke base64
        ob_start();
        imagejpeg($resizedImage, null, 85); // Quality 85%
        $imageData = ob_get_clean();
        
        // Free memory
        imagedestroy($image);
        imagedestroy($resizedImage);
        
        return 'data:image/jpeg;base64,' . base64_encode($imageData);

    } catch (\Exception $e) {
        \Log::error("Error getting optimized image from Google Drive: " . $e->getMessage());
        return $this->getPlaceholderImage();
    }
}

/**
 * Get placeholder image jika foto tidak tersedia
 */
private function getPlaceholderImage()
{
    // Buat gambar placeholder sederhana
    $width = 300;
    $height = 400;
    
    $image = imagecreatetruecolor($width, $height);
    
    // Background abu-abu
    $bgColor = imagecolorallocate($image, 240, 240, 240);
    imagefill($image, 0, 0, $bgColor);
    
    // Text "No Photo"
    $textColor = imagecolorallocate($image, 150, 150, 150);
    $text = "No Photo";
    imagestring($image, 5, ($width/2)-40, ($height/2)-10, $text, $textColor);
    
    // Convert to base64
    ob_start();
    imagejpeg($image, null, 80);
    $imageData = ob_get_clean();
    
    imagedestroy($image);
    
    return 'data:image/jpeg;base64,' . base64_encode($imageData);
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
