<?php

namespace App\Exports;

use App\Models\Penguji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PengujiExport extends DefaultValueBinder implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    WithCustomValueBinder
{
    protected array $filters;

    // Index kolom (0-based): 3=Password, 4=NIP, 8=NomorHP, 9=Norek, 10=NPWP
    protected array $stringColumnIndexes = [3, 4, 8, 9, 10];

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Paksa kolom tertentu ditulis sebagai TYPE_STRING
     * agar Excel tidak mengonversi angka panjang ke notasi ilmiah.
     */
    public function bindValue(Cell $cell, $value): bool
    {
        $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
            $cell->getColumn()
        ) - 1;

        if ($cell->getRow() > 1 && in_array($colIndex, $this->stringColumnIndexes)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function title(): string
    {
        return 'Data Penguji';
    }

    public function collection()
    {
        $query = Penguji::with('user')->withCount('kelompok');

        if (!empty($this->filters['status'])) {
            $query->where('status_aktif', $this->filters['status'] === 'Aktif');
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama',       'like', "%{$search}%")
                  ->orWhere('nip',      'like', "%{$search}%")
                  ->orWhere('email',    'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'Password',
            'NIP',
            'Jabatan',
            'Golongan',
            'Pangkat',
            'Nomor HP',
            'Nomor Rekening',
            'NPWP',
            'Status',
            'Jumlah Kelompok',
        ];
    }

    public function map($penguji): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $penguji->nama,
            $penguji->email ?? '-',
            $penguji->user?->password_plain ?? '-',
            $penguji->nip ?? '-',
            $penguji->jabatan ?? '-',
            $penguji->golongan ?? '-',
            $penguji->pangkat ?? '-',
            $penguji->nomor_hp ?? '-',
            $penguji->nomor_rekening ?? '-',
            $penguji->npwp ?? '-',
            $penguji->status_aktif ? 'Aktif' : 'Nonaktif',
            $penguji->kelompok_count,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
                'name'  => 'Arial',
            ],
            'fill' => [
                'fillType'   => 'solid',
                'startColor' => ['rgb' => '285496'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical'   => 'center',
                'wrapText'   => true,
            ],
        ]);

        $sheet->getStyle('A2:M1000')->applyFromArray([
            'font'      => ['name' => 'Arial', 'size' => 10],
            'alignment' => ['vertical' => 'center', 'wrapText' => true],
        ]);

        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:M{$row}")->applyFromArray([
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EEF3FB']],
                ]);
            }
        }

        $sheet->getStyle("A1:M{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'DEE2E6']],
            ],
        ]);

        $sheet->getStyle("D1:D{$highestRow}")->applyFromArray([
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFF3CD']],
        ]);
        $sheet->getStyle('D1')->applyFromArray([
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E6A817']],
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
        ]);

        // Format teks & rata kiri: D (Password), E (NIP), I (HP), J (Norek), K (NPWP)
        $textColumns = ['D', 'E', 'I', 'J', 'K'];
        foreach ($textColumns as $col) {
            $sheet->getStyle("{$col}2:{$col}{$highestRow}")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_TEXT);

            $sheet->getStyle("{$col}2:{$col}{$highestRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        $sheet->freezePane('A2');
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 30,
            'D' => 20,
            'E' => 20,
            'F' => 25,
            'G' => 12,
            'H' => 25,
            'I' => 18,
            'J' => 25,
            'K' => 22,
            'L' => 12,
            'M' => 16,
        ];
    }
}