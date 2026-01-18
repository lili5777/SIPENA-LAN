<?php

namespace App\Http\Controllers;

use App\Models\JenisPelatihan;
use App\Models\Angkatan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataPeserta;
use App\Exports\KomposisiPeserta;

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

        // Kirim parameter filter ke export class
        return Excel::download(
            new KomposisiPeserta($jenisPelatihan, $angkatan, $tahun),
            $fileName
        );
    }
}
