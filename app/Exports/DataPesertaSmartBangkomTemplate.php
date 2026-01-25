<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class DataPesertaSmartBangkomTemplate implements WithEvents
{
    protected $jenisPelatihan;
    protected $angkatan;
    protected $tahun;

    public function __construct($jenisPelatihan, $angkatan, $tahun)
    {
        $this->jenisPelatihan = $jenisPelatihan;
        $this->angkatan = $angkatan;
        $this->tahun = $tahun;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // 🔑 INI TEMPLATE ASLI
                $sheet = $event->sheet->getDelegate();

                $data = Pendaftaran::with(['peserta.kepegawaianPeserta'])
                    ->where('status_pendaftaran', 'Diterima')
                    ->get();

                // ✅ mulai BARIS 3
                $row = 3;

                foreach ($data as $item) {
                    $p = $item->peserta;
                    $k = $p->kepegawaianPeserta;

                    // 🔁 mapping gender sesuai dropdown
                    $gender = strtolower($p->jenis_kelamin) === 'perempuan'
                        ? 'Wanita'
                        : 'Pria';

                    $sheet->setCellValue("A$row", $p->nama_lengkap);
                    $sheet->setCellValueExplicit("B$row", $p->nip_nrp, DataType::TYPE_STRING);
                    $sheet->setCellValue("C$row", $gender);
                    $sheet->setCellValue("D$row", $p->agama);
                    $sheet->setCellValue("E$row", $p->tempat_lahir);
                    $sheet->setCellValue("F$row", $p->tanggal_lahir);
                    $sheet->setCellValue("G$row", $p->email_pribadi);
                    $sheet->setCellValueExplicit("H$row", $p->nomor_hp, DataType::TYPE_STRING);
                    $sheet->setCellValue("I$row", 'PNS');
                    $sheet->setCellValue("J$row", $k->golongan_ruang ?? '');
                    $sheet->setCellValue("K$row", $k->pangkat ?? '');
                    $sheet->setCellValue("L$row", $k->jabatan ?? '');
                    $sheet->setCellValue("M$row", 'PNBP');
                    $sheet->setCellValue("N$row", 'APBD');
                    $sheet->setCellValue("O$row", $k->asal_instansi ?? '');
                    $sheet->setCellValue("P$row", $k->alamat_kantor ?? '');

                    $row++;
                }
            },
        ];
    }
}
