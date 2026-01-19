<?php

namespace App\Http\Controllers;

use App\Models\JenisPelatihan;
use App\Models\Angkatan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataPeserta;
use App\Exports\KomposisiPeserta;
use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Ambil parameter filter dari request
        $jenisPelatihan = request('jenis_pelatihan');
        $angkatan = request('angkatan');
        $tahun = request('tahun');

        // Buat nama file berdasarkan filter yang dipilih
        $fileNameParts = [];

        if ($jenisPelatihan) {
            // Replace spasi dengan underscore dan hapus karakter khusus
            $fileNameParts[] = str_replace(' ', '_', strtoupper($jenisPelatihan));
        }

        if ($angkatan) {
            // Convert "Angkatan I" menjadi "ANGKATAN_I"
            $fileNameParts[] = str_replace(' ', '_', strtoupper($angkatan));
        }

        if ($tahun) {
            $fileNameParts[] = $tahun;
        }

        // Jika tidak ada filter, gunakan nama default
        if (empty($fileNameParts)) {
            $fileName = 'DATA_PESERTA_' . date('Y_m_d');
        } else {
            $fileName = implode('_', $fileNameParts);
        }

        $fileName .= '.xlsx';

        aktifitas('Mengekspor Data Peserta');
        // Kirim parameter filter ke export class
        return Excel::download(
            new DataPeserta($jenisPelatihan, $angkatan, $tahun),
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
}
