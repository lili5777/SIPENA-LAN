<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\PesertaImport;
use App\Exports\PesertaTemplateExport;
use App\Models\PicPeserta;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    /**
     * Tampilkan form import peserta
     */
    public function showImportForm()
    {
        $user = auth()->user();
        
        // Log untuk debug
        Log::info('Show import form accessed', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role?->name,
            'is_pic' => $user->isPic() ? 'true' : 'false'
        ]);
        
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
            $user = auth()->user();
            
            // LOG DETAIL USER
            Log::info('Import attempt started', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role?->name,
                'is_pic' => $user->isPic() ? 'true' : 'false',
                'file_name' => $file->getClientOriginalName()
            ]);
            
            // VALIDASI TAMBAHAN UNTUK PIC
            if ($user && $user->isPic()) {
                Log::info('PIC user validation', [
                    'user_id' => $user->id,
                    'pic_access_count' => PicPeserta::where('user_id', $user->id)->count()
                ]);
                
                // Cek apakah PIC memiliki akses angkatan
                $hasAccess = PicPeserta::where('user_id', $user->id)->exists();
                if (!$hasAccess) {
                    Log::warning('PIC has no angkatan access', ['user_id' => $user->id]);
                    return redirect()
                        ->back()
                        ->with('error', 'Anda (PIC) tidak memiliki akses untuk mengimport data. Silakan hubungi administrator untuk mendapatkan akses angkatan.');
                }
            }
            
            // Buat instance import dengan user
            $import = new PesertaImport($user);
            
            // Validasi template sebelum import
            $templateValidation = $import->validateTemplate($file);
            if (!$templateValidation['valid']) {
                Log::warning('Template validation failed', [
                    'errors' => $templateValidation['errors'] ?? []
                ]);
                
                return redirect()
                    ->back()
                    ->with('error', 'Format file tidak sesuai template: ' . $templateValidation['message'])
                    ->with('error_messages', $templateValidation['errors'] ?? []);
            }

            Log::info('Template validation passed, starting import...');
            
            // Proses import
            Excel::import($import, $file);

            // Ambil statistik
            $stats = $import->getStats();
            $errors = $import->getErrors();

            // LOG HASIL IMPORT
            Log::info('Import completed', [
                'success' => $stats['success'],
                'failed' => $stats['failed'],
                'duplicate' => $stats['duplicate'],
                'total_errors' => count($errors),
                'user_id' => $user->id
            ]);

            // Jika ada error detail, tampilkan
            if (!empty($errors)) {
                $message = "Import selesai dengan beberapa masalah:";
                
                return redirect()
                    ->back()
                    ->with('warning', $message)
                    ->with('stats', $stats)
                    ->with('error_messages', array_slice($errors, 0, 50)); // Batasi tampilan error
            }

            // Buat pesan sukses dengan detail
            $message = "Import selesai! ";
            $message .= "✅ Berhasil: {$stats['success']} ";
            
            if ($stats['duplicate'] > 0) {
                $message .= "⚠️ Duplikat (ditolak): {$stats['duplicate']} ";
            }

            if ($stats['failed'] > 0) {
                $message .= "❌ Gagal: {$stats['failed']} ";
            }

            // Tentukan tipe pesan berdasarkan hasil
            if ($stats['success'] > 0 && ($stats['duplicate'] > 0 || $stats['failed'] > 0)) {
                return redirect()
                    ->back()
                    ->with('warning', $message)
                    ->with('stats', $stats);
            } elseif ($stats['success'] > 0) {
                return redirect()
                    ->back()
                    ->with('success', $message)
                    ->with('stats', $stats);
            } elseif ($stats['duplicate'] > 0 && $stats['success'] == 0) {
                $errorMsg = 'Semua data ditolak karena:';
                if ($stats['duplicate'] > 0) {
                    $errorMsg .= ' NIP sudah terdaftar';
                }
                
                return redirect()
                    ->back()
                    ->with('error', $errorMsg)
                    ->with('stats', $stats);
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak ada data yang berhasil diimport. Periksa format dan data Anda.')
                    ->with('stats', $stats);
            }
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $rowNumber = $failure->row();
                $column = $failure->attribute();
                $value = $failure->values()[$failure->attribute()] ?? '';
                
                $errors[] = "Baris {$rowNumber} (Kolom {$column} = '{$value}'): " . implode(', ', $failure->errors());
            }

            Log::error('Excel validation exception', [
                'error_count' => count($errors),
                'first_error' => $errors[0] ?? 'none'
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan validasi pada file Excel')
                ->with('error_messages', array_slice($errors, 0, 20)); // Batasi tampilan error
                
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during import', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
                
        } catch (\Exception $e) {
            Log::error('Import peserta error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $request->file('file')?->getClientOriginalName(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel untuk import
     */
    public function downloadTemplate()
    {
        try {
            $user = auth()->user();
            $fileName = 'Template_Import_Peserta_' . date('Y-m-d_His') . '.xlsx';
            
            Log::info('Template download requested', [
                'user_id' => $user->id,
                'user_role' => $user->role?->name,
                'file_name' => $fileName
            ]);

            return Excel::download(
                new PesertaTemplateExport(),
                $fileName,
                \Maatwebsite\Excel\Excel::XLSX
            );
        } catch (\Exception $e) {
            Log::error('Template download failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }
    
    /**
     * Get import statistics for current user (API endpoint for debugging)
     */
    public function getImportStats()
    {
        $user = auth()->user();
        
        $stats = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role?->name,
                'is_pic' => $user->isPic()
            ],
            'pic_access' => []
        ];
        
        if ($user->isPic()) {
            $picAccess = PicPeserta::where('user_id', $user->id)
                ->with(['angkatan', 'jenisPelatihan'])
                ->get();
                
            $stats['pic_access'] = $picAccess->map(function ($access) {
                return [
                    'angkatan_id' => $access->angkatan_id,
                    'angkatan_name' => $access->angkatan->nama_angkatan ?? 'N/A',
                    'jenis_pelatihan_id' => $access->jenispelatihan_id,
                    'jenis_pelatihan_name' => $access->jenisPelatihan->nama_pelatihan ?? 'N/A'
                ];
            })->toArray();
        }
        
        return response()->json($stats);
    }
}