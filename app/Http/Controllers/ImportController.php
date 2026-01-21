<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\PesertaImport;
use App\Exports\PesertaTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    /**
     * Tampilkan form import peserta
     */
    public function showImportForm()
    {
        return view('admin.import.peserta');
    }

    /**
     * Proses import data peserta dari file Excel
     */
    public function importPeserta(Request $request)
    {
        // Validasi file upload
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ], [
            'file.required' => 'File Excel wajib dipilih',
            'file.mimes' => 'Format file harus .xlsx, .xls, atau .csv',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('file');

            // Buat instance import
            $import = new PesertaImport();

            // Proses import
            Excel::import($import, $file);

            // Ambil statistik
            $stats = $import->getStats();
            $errors = $import->getErrors();

            // Buat pesan sukses dengan detail
            $successMessage = "Import selesai!<br>";
            $successMessage .= "✅ Berhasil: {$stats['success']}<br>";

            if ($stats['duplicate'] > 0) {
                $successMessage .= "⚠️ Duplikat (dilewati): {$stats['duplicate']}<br>";
            }

            if ($stats['failed'] > 0) {
                $successMessage .= "❌ Gagal: {$stats['failed']}";
            }

            // Jika ada data yang berhasil diimport
            if ($stats['success'] > 0) {
                return redirect()
                    ->back()
                    ->with('success', $successMessage)
                    ->with('stats', $stats)
                    ->with('error_messages', $errors);
            }

            // Jika semua gagal
            if ($stats['success'] == 0 && $stats['failed'] > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Semua data gagal diimport. Silakan periksa format file Anda.')
                    ->with('stats', $stats)
                    ->with('error_messages', $errors);
            }

            // Jika semua duplikat
            if ($stats['success'] == 0 && $stats['duplicate'] > 0) {
                return redirect()
                    ->back()
                    ->with('warning', 'Semua data sudah terdaftar sebelumnya (duplikat).')
                    ->with('stats', $stats);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan validasi pada file Excel')
                ->with('error_messages', $errors);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel untuk import
     */
    public function downloadTemplate()
    {
        try {
            $fileName = 'Template_Import_Peserta_' . date('Y-m-d_His') . '.xlsx';

            return Excel::download(
                new PesertaTemplateExport(),
                $fileName,
                \Maatwebsite\Excel\Excel::XLSX
            );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }
}
