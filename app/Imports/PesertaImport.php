<?php

namespace App\Imports;

use App\Models\Peserta;
use App\Models\KepegawaianPeserta;
use App\Models\Pendaftaran;
use App\Models\JenisPelatihan;
use App\Models\Angkatan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\PicPeserta;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PesertaImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    protected int $successCount = 0;
    protected int $failedCount = 0;
    protected int $duplicateCount = 0;
    protected array $importErrors = [];
    protected $user;
    protected array $allowedAngkatanIds = [];

    public function __construct($user = null)
    {
        $this->user = $user ?? Auth::user();
        
        // TAMBAHKAN LOG DETAIL UNTUK DEBUG
        Log::info('User constructor details', [
            'user_id' => $this->user?->id,
            'user_role' => $this->user?->role,
            'user_role_model' => $this->user?->role?->name ?? 'no role',
            'is_pic_method' => $this->user?->isPic() ? 'true' : 'false'
        ]);
        
        // Jika user adalah PIC, batasi hanya angkatan yang dia akses
        if ($this->user && $this->user->isPic()) {
            $this->allowedAngkatanIds = PicPeserta::where('user_id', $this->user->id)
                ->pluck('angkatan_id')
                ->toArray();
                
            Log::info('PIC Import Access Details', [
                'user_id' => $this->user->id,
                'user_name' => $this->user->name,
                'allowed_angkatan' => $this->allowedAngkatanIds,
                'total_allowed' => count($this->allowedAngkatanIds)
            ]);
            
            if (empty($this->allowedAngkatanIds)) {
                Log::warning('PIC has no angkatan access!', ['user_id' => $this->user->id]);
            }
        } elseif ($this->user) {
            Log::info('Admin user importing', [
                'user_id' => $this->user->id,
                'role' => $this->user->role?->name
            ]);
        }
    }

    public function collection(Collection $rows)
    {
        Log::info('Starting import process', [
            'total_rows' => $rows->count(),
            'user_id' => $this->user?->id,
            'user_role' => $this->user?->role?->name,
            'is_pic' => $this->user?->isPic() ? 'true' : 'false',
            'allowed_angkatan_ids' => $this->allowedAngkatanIds
        ]);

        foreach ($rows->toArray() as $index => $rawRow) {
            $rowNumber = $index + 2; // +2 karena header di row 1
            $row = collect($this->normalizeData($rawRow));

            // ⛔ Skip baris kosong total
            if ($row->filter(fn($v) => $v !== null && trim($v) !== '')->isEmpty()) {
                Log::debug("Skipping empty row {$rowNumber}");
                continue;
            }

            DB::beginTransaction();
            try {
                Log::info("Processing row {$rowNumber}", [
                    'data' => $row->toArray(),
                    'user_role' => $this->user?->role?->name
                ]);

                // ================= VALIDASI WAJIB =================
                $validationResult = $this->validateRequiredFields($row, $rowNumber);
                if (!$validationResult['valid']) {
                    $this->importErrors[] = "Baris {$rowNumber}: " . $validationResult['message'];
                    $this->failedCount++;
                    DB::rollBack();
                    Log::warning("Row {$rowNumber} failed validation", ['error' => $validationResult['message']]);
                    continue;
                }

                // ================= VALIDASI KATEGORI =================
                $kategori = strtoupper(trim($row['kategori']));
                $validKategori = ['PNBP', 'FASILITASI'];
                
                if (!in_array($kategori, $validKategori)) {
                    $this->importErrors[] = "Baris {$rowNumber}: Kategori '{$row['kategori']}' tidak valid. Harus PNBP atau FASILITASI";
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= VALIDASI WILAYAH =================
                if ($kategori === 'FASILITASI' && empty($row['wilayah'])) {
                    $this->importErrors[] = "Baris {$rowNumber}: Wilayah wajib diisi jika kategori FASILITASI";
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= CEK JENIS PELATIHAN =================
                $jenisPelatihan = JenisPelatihan::where('nama_pelatihan', trim($row['jenis_pelatihan']))
                    ->orWhere('kode_pelatihan', trim($row['jenis_pelatihan']))
                    ->first();

                if (!$jenisPelatihan) {
                    $this->importErrors[] = "Baris {$rowNumber}: Jenis pelatihan '{$row['jenis_pelatihan']}' tidak ditemukan dalam sistem";
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= CEK ANGKATAN =================
                $angkatanData = [
                    'id_jenis_pelatihan' => $jenisPelatihan->id,
                    'tahun' => trim($row['tahun_angkatan']),
                    'nama_angkatan' => trim($row['angkatan']),
                    'kategori' => $kategori,
                    'wilayah' => !empty($row['wilayah']) ? trim($row['wilayah']) : null,
                ];

                // Cek apakah angkatan sudah ada
                $angkatan = Angkatan::where($angkatanData)->first();

                if (!$angkatan) {
                    $wilayahInfo = $angkatanData['wilayah'] ? " Wilayah: {$angkatanData['wilayah']}" : "";
                    $this->importErrors[] = "Baris {$rowNumber}: Angkatan '{$angkatanData['nama_angkatan']}' Tahun {$angkatanData['tahun']} Kategori {$kategori}{$wilayahInfo} tidak ditemukan";
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= VALIDASI AKSES PIC - PERBAIKAN DI SINI =================
                if ($this->user && $this->user->isPic()) {
                    Log::info('Checking PIC access for angkatan', [
                        'user_id' => $this->user->id,
                        'angkatan_id' => $angkatan->id,
                        'angkatan_name' => $angkatan->nama_angkatan,
                        'allowed_angkatan_ids' => $this->allowedAngkatanIds,
                        'is_allowed' => in_array($angkatan->id, $this->allowedAngkatanIds) ? 'YES' : 'NO'
                    ]);
                    
                    // CEK APAKAH ANGKATAN INI ADA DI DAFTAR AKSES PIC
                    if (!in_array($angkatan->id, $this->allowedAngkatanIds)) {
                        // TAMBAHKAN VALIDASI TAMBAHAN: CEK LEWAT RELASI
                        $hasAccess = PicPeserta::where('user_id', $this->user->id)
                            ->where('angkatan_id', $angkatan->id)
                            ->exists();
                            
                        if (!$hasAccess) {
                            $this->importErrors[] = "Baris {$rowNumber}: Anda (PIC) tidak memiliki akses untuk mengimport ke angkatan '{$angkatan->nama_angkatan}' (ID: {$angkatan->id})";
                            $this->failedCount++;
                            DB::rollBack();
                            Log::warning("PIC unauthorized access attempt", [
                                'user_id' => $this->user->id,
                                'angkatan_id' => $angkatan->id,
                                'angkatan_name' => $angkatan->nama_angkatan
                            ]);
                            continue;
                        } else {
                            // Jika ditemukan melalui relasi, update allowedAngkatanIds
                            $this->allowedAngkatanIds[] = $angkatan->id;
                            Log::info('Added angkatan to allowed list via relation check', [
                                'angkatan_id' => $angkatan->id
                            ]);
                        }
                    }
                }

                // ================= CEK KUOTA =================
                $totalPendaftar = Pendaftaran::where('id_angkatan', $angkatan->id)->count();
                if ($totalPendaftar >= $angkatan->kuota) {
                    $this->importErrors[] = "Baris {$rowNumber}: Kuota angkatan '{$angkatan->nama_angkatan}' penuh ({$totalPendaftar}/{$angkatan->kuota})";
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= VALIDASI NIP =================
                $nip_nrp = $row['nip_nrp'];
                
                // CEK 1: Apakah NIP sudah terdaftar sebagai peserta (TOLAK jika sudah ada)
                $pesertaExist = Peserta::where('nip_nrp', $nip_nrp)->first();
                
                if ($pesertaExist) {
                    // TOLAK: NIP sudah terdaftar, tidak boleh update data yang sudah ada
                    $this->importErrors[] = "Baris {$rowNumber}: NIP '{$nip_nrp}' sudah terdaftar dalam sistem. Data peserta yang sudah ada tidak dapat diupdate melalui import";
                    $this->duplicateCount++;
                    DB::rollBack();
                    Log::warning("Duplicate NIP rejected", [
                        'nip' => $nip_nrp,
                        'existing_peserta_id' => $pesertaExist->id
                    ]);
                    continue;
                }

                // CEK 2: Validasi format NIP
                if (!empty($nip_nrp)) {
                    $cleanNip = preg_replace('/\D/', '', $nip_nrp);
                    if (strlen($cleanNip) < 8) {
                        $this->importErrors[] = "Baris {$rowNumber}: NIP/NRP '{$nip_nrp}' tidak valid (minimal 8 digit angka)";
                        $this->failedCount++;
                        DB::rollBack();
                        continue;
                    }
                    $row['nip_nrp'] = $cleanNip; // Simpan NIP yang sudah dibersihkan
                }

                // ================= VALIDASI JENIS KELAMIN (OPSIONAL) =================
                if (!empty($row['jenis_kelamin'])) {
                    $jk = strtolower(trim($row['jenis_kelamin']));
                    $mapping = [
                        'l' => 'Laki-laki',
                        'laki-laki' => 'Laki-laki',
                        'laki laki' => 'Laki-laki',
                        'pria' => 'Laki-laki',
                        'lk' => 'Laki-laki',
                        'p' => 'Perempuan',
                        'perempuan' => 'Perempuan',
                        'wanita' => 'Perempuan',
                        'pr' => 'Perempuan',
                    ];

                    if (!array_key_exists($jk, $mapping)) {
                        $this->importErrors[] = "Baris {$rowNumber}: Jenis kelamin '{$row['jenis_kelamin']}' tidak valid. Gunakan: Laki-laki atau Perempuan";
                        $this->failedCount++;
                        DB::rollBack();
                        continue;
                    }

                    $row['jenis_kelamin'] = $mapping[$jk];
                } else {
                    $row['jenis_kelamin'] = null; // Boleh kosong
                }

                // ================= VALIDASI AGAMA =================
                if (!empty($row['agama'])) {
                    $agama = strtolower(trim($row['agama']));
                    $agamaMap = [
                        'islam' => 'Islam',
                        'kristen' => 'Kristen',
                        'kristen protestan' => 'Kristen Protestan',
                        'protestan' => 'Kristen',
                        'katolik' => 'Katolik',
                        'kristen katolik' => 'Katolik',
                        'hindu' => 'Hindu',
                        'budha' => 'Buddha',
                        'buddha' => 'Buddha',
                        'konghucu' => 'Konghucu',
                        'khonghucu' => 'Konghucu',
                    ];

                    if (!array_key_exists($agama, $agamaMap)) {
                        $this->importErrors[] = "Baris {$rowNumber}: Agama '{$row['agama']}' tidak valid. Gunakan: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu";
                        $this->failedCount++;
                        DB::rollBack();
                        continue;
                    }

                    $row['agama'] = $agamaMap[$agama];
                }

                // ================= BUAT PESERTA BARU =================
                // Hanya buat baru jika NIP belum ada di sistem
                $peserta = Peserta::create([
                    'nip_nrp'            => $row['nip_nrp'],
                    'nama_lengkap'       => $row['nama_lengkap'],
                    'nama_panggilan'     => $row['nama_panggilan'] ?? null,
                    'jenis_kelamin'      => $row['jenis_kelamin'],
                    'agama'              => $row['agama'] ?? null,
                    'tempat_lahir'       => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir'      => $this->parseDate($row['tanggal_lahir'] ?? null),
                    'alamat_rumah'       => $row['alamat_rumah'] ?? null,
                    'status_perkawinan'  => $row['status_perkawinan'] ?? null,
                    'nama_pasangan'      => $row['nama_pasangan'] ?? null,
                    'olahraga_hobi'      => $row['olahraga_hobi'] ?? null,
                    'perokok'            => $this->normalizeValue($row['perokok'] ?? null, 'perokok'),
                    'email_pribadi'      => $row['email_pribadi'] ?? null,
                    'nomor_hp'           => $row['nomor_hp'] ?? null,
                    'pendidikan_terakhir' => $this->normalizeValue($row['pendidikan_terakhir'] ?? null, 'pendidikan'),
                    'bidang_studi'       => $row['bidang_studi'] ?? null,
                    'bidang_keahlian'    => $row['bidang_keahlian'] ?? null,
                    'ukuran_kaos'        => $this->normalizeValue($row['ukuran_kaos'] ?? null, 'ukuran'),
                    'ukuran_celana'      => $this->normalizeValue($row['ukuran_celana'] ?? null, 'ukuran'),
                    'ukuran_training'    => $this->normalizeValue($row['ukuran_training'] ?? null, 'ukuran'),
                    'status_aktif'       => true,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                Log::info("Created new peserta", [
                    'id' => $peserta->id,
                    'nip' => $peserta->nip_nrp,
                    'name' => $peserta->nama_lengkap,
                    'user_role' => $this->user?->role?->name
                ]);

                // ================= BUAT DATA KEPEGAWAIAN =================
                $provinsi = !empty($row['provinsi'])
                    ? Provinsi::where('name', 'like', '%' . trim($row['provinsi']) . '%')->first()
                    : null;

                $kabupaten = null;
                if ($provinsi && !empty($row['kabupaten_kota'])) {
                    $kabupaten = Kabupaten::where('province_id', $provinsi->id)
                        ->where('name', 'like', '%' . trim($row['kabupaten_kota']) . '%')
                        ->first();
                }

                KepegawaianPeserta::create([
                    'id_peserta' => $peserta->id,
                    'asal_instansi' => $row['asal_instansi'] ?? null,
                    'unit_kerja' => $row['unit_kerja'] ?? null,
                    'jabatan' => $row['jabatan'] ?? null,
                    'pangkat' => $row['pangkat'] ?? null,
                    'golongan_ruang' => $row['golongan_ruang'] ?? null,
                    'eselon' => $row['eselon'] ?? null,
                    'alamat_kantor' => $row['alamat_kantor'] ?? null,
                    'nomor_telepon_kantor' => $row['nomor_telepon_kantor'] ?? null,
                    'email_kantor' => $row['email_kantor'] ?? null,
                    'id_provinsi' => $provinsi?->id,
                    'id_kabupaten_kota' => $kabupaten?->id,
                    'nomor_sk_cpns' => $row['nomor_sk_cpns'] ?? null,
                    'nomor_sk_terakhir' => $row['nomor_sk_terakhir'] ?? null,
                    'tanggal_sk_jabatan' => $this->parseDate($row['tanggal_sk_jabatan'] ?? null),
                    'tahun_lulus_pkp_pim_iv' => $row['tahun_lulus_pkp_pim_iv'] ?? null,
                    'tanggal_sk_cpns' => $this->parseDate($row['tanggal_sk_cpns'] ?? null),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // ================= BUAT PENDAFTARAN =================
                Pendaftaran::create([
                    'id_peserta' => $peserta->id,
                    'id_jenis_pelatihan' => $jenisPelatihan->id,
                    'id_angkatan' => $angkatan->id,
                    'status_pendaftaran' => 'Menunggu Verifikasi',
                    'tanggal_daftar' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit();
                $this->successCount++;
                
                Log::info("Row {$rowNumber} imported successfully", [
                    'peserta_id' => $peserta->id,
                    'angkatan_id' => $angkatan->id,
                    'angkatan_name' => $angkatan->nama_angkatan,
                    'user_role' => $this->user?->role?->name
                ]);
                
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->importErrors[] = "Baris {$rowNumber}: Error sistem - " . $e->getMessage();
                $this->failedCount++;
                Log::error("IMPORT ERROR ROW {$rowNumber}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $row->toArray(),
                    'user_role' => $this->user?->role?->name
                ]);
            }
        }

        Log::info('Import process completed', [
            'success' => $this->successCount,
            'failed' => $this->failedCount,
            'duplicate' => $this->duplicateCount,
            'errors' => count($this->importErrors),
            'user_id' => $this->user?->id,
            'user_role' => $this->user?->role?->name
        ]);
    }

    // ================= VALIDASI TEMPLATE =================
    public function validateTemplate($file)
    {
        $errors = [];
        
        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            
            // Cek minimal ada data (header + minimal 1 data)
            if ($highestRow < 2) {
                return [
                    'valid' => false,
                    'message' => 'File hanya berisi header, tidak ada data peserta'
                ];
            }
            
            // Validasi kolom wajib di header (7 kolom pertama)
            $headerRow = 1;
            $requiredHeaders = [
                'JENIS_PELATIHAN',
                'ANGKATAN', 
                'TAHUN_ANGKATAN',
                'KATEGORI',
                'WILAYAH',
                'NIP_NRP',
                'NAMA_LENGKAP'
            ];
            
            foreach ($requiredHeaders as $index => $expectedHeader) {
                $columnLetter = $this->getColumnLetter($index + 1);
                $cellValue = $worksheet->getCell($columnLetter . $headerRow)->getValue();
                
                if (trim($cellValue) !== $expectedHeader) {
                    $errors[] = "Kolom {$columnLetter} harus '<strong>{$expectedHeader}</strong>', ditemukan '{$cellValue}'";
                }
            }
            
            // Validasi data contoh untuk 5 baris pertama
            $sampleRows = min(5, $highestRow - 1); // minus header
            $validRows = 0;
            
            for ($row = 2; $row <= $sampleRows + 1; $row++) {
                $hasData = false;
                
                // Cek apakah baris memiliki data minimal NIP dan Nama
                $nip = $worksheet->getCell('F' . $row)->getValue();
                $nama = $worksheet->getCell('G' . $row)->getValue();
                
                if (!empty($nip) && !empty($nama)) {
                    $hasData = true;
                    
                    // Validasi format tanggal
                    $tanggalLahir = $worksheet->getCell('L' . $row)->getValue();
                    if (!empty($tanggalLahir) && !$this->isValidDate($tanggalLahir)) {
                        $errors[] = "Baris {$row}: Format tanggal lahir '{$tanggalLahir}' tidak valid (harus dd-mm-yyyy)";
                    }
                    
                    // Validasi kategori
                    $kategori = $worksheet->getCell('D' . $row)->getValue();
                    if (!empty($kategori) && !in_array(strtoupper($kategori), ['PNBP', 'FASILITASI'])) {
                        $errors[] = "Baris {$row}: Kategori '{$kategori}' harus PNBP atau FASILITASI";
                    }
                }
                
                if ($hasData) {
                    $validRows++;
                }
            }
            
            if ($validRows === 0) {
                $errors[] = "Tidak ada data valid dalam 5 baris pertama. Pastikan mengisi NIP dan Nama";
            }
            
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'File Excel tidak dapat dibaca atau rusak',
                'errors' => [$e->getMessage()]
            ];
        }
        
        if (!empty($errors)) {
            return [
                'valid' => false,
                'message' => 'Format file tidak sesuai template',
                'errors' => $errors
            ];
        }
        
        return ['valid' => true];
    }

    // ================= VALIDASI FIELDS WAJIB =================
    protected function validateRequiredFields(Collection &$row, int $rowNumber): array
    {
        // Field wajib (tanpa jenis kelamin)
        $required = [
            'jenis_pelatihan', 
            'angkatan', 
            'tahun_angkatan', 
            'kategori',
            'nip_nrp', 
            'nama_lengkap'
        ];

        $missingFields = [];
        foreach ($required as $field) {
            if (empty($row[$field])) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return [
                'valid' => false,
                'message' => "Field wajib kosong: " . implode(', ', $missingFields)
            ];
        }

        // Validasi tahun angkatan (4 digit angka)
        if (!empty($row['tahun_angkatan'])) {
            $tahun = trim($row['tahun_angkatan']);
            if (!preg_match('/^\d{4}$/', $tahun)) {
                return [
                    'valid' => false,
                    'message' => "Tahun angkatan '{$tahun}' harus 4 digit angka"
                ];
            }
            
            // Validasi tahun tidak boleh di masa depan
            $currentYear = date('Y');
            if ((int)$tahun > $currentYear + 1) { // Maksimal 1 tahun ke depan
                return [
                    'valid' => false,
                    'message' => "Tahun angkatan '{$tahun}' tidak valid (maksimal " . ($currentYear + 1) . ")"
                ];
            }
        }

        // Validasi email jika diisi
        if (!empty($row['email_pribadi']) && !filter_var($row['email_pribadi'], FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'message' => "Email pribadi '{$row['email_pribadi']}' tidak valid"
            ];
        }

        if (!empty($row['email_kantor']) && !filter_var($row['email_kantor'], FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'message' => "Email kantor '{$row['email_kantor']}' tidak valid"
            ];
        }

        // Validasi nomor HP jika diisi
        if (!empty($row['nomor_hp'])) {
            $cleanPhone = preg_replace('/\D/', '', $row['nomor_hp']);
            if (strlen($cleanPhone) < 10 || strlen($cleanPhone) > 13) {
                return [
                    'valid' => false,
                    'message' => "Nomor HP '{$row['nomor_hp']}' tidak valid (10-13 digit)"
                ];
            }
            $row['nomor_hp'] = $cleanPhone;
        }

        return ['valid' => true];
    }

    // ================= NORMALISASI DATA =================
    protected function normalizeData(array $row): array
    {
        $result = [];
        
        // Mapping nama kolom yang mungkin berbeda
        $columnMapping = [
            'nip/nrp' => 'nip_nrp',
            'tahun angkatan' => 'tahun_angkatan',
            'jenis pelatihan' => 'jenis_pelatihan',
            'nama lengkap' => 'nama_lengkap',
            'nama panggilan' => 'nama_panggilan',
            'jenis kelamin' => 'jenis_kelamin',
            'tempat lahir' => 'tempat_lahir',
            'tanggal lahir' => 'tanggal_lahir',
            'alamat rumah' => 'alamat_rumah',
            'email pribadi' => 'email_pribadi',
            'nomor hp' => 'nomor_hp',
            'pendidikan terakhir' => 'pendidikan_terakhir',
            'bidang studi' => 'bidang_studi',
            'bidang keahlian' => 'bidang_keahlian',
            'status perkawinan' => 'status_perkawinan',
            'nama pasangan' => 'nama_pasangan',
            'olahraga hobi' => 'olahraga_hobi',
            'ukuran kaos' => 'ukuran_kaos',
            'ukuran celana' => 'ukuran_celana',
            'ukuran training' => 'ukuran_training',
            'kondisi peserta' => 'kondisi_peserta',
            'asal instansi' => 'asal_instansi',
            'unit kerja' => 'unit_kerja',
            'kabupaten/kota' => 'kabupaten_kota',
            'alamat kantor' => 'alamat_kantor',
            'nomor telepon kantor' => 'nomor_telepon_kantor',
            'email kantor' => 'email_kantor',
            'golongan ruang' => 'golongan_ruang',
            'tanggal sk jabatan' => 'tanggal_sk_jabatan',
            'nomor sk cpns' => 'nomor_sk_cpns',
            'nomor sk terakhir' => 'nomor_sk_terakhir',
            'tanggal sk cpns' => 'tanggal_sk_cpns',
            'tahun lulus pkp pim iv' => 'tahun_lulus_pkp_pim_iv',
        ];

        foreach ($row as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            
            $normalizedKey = strtolower(trim($key));
            
            // Gunakan mapping jika ada
            if (array_key_exists($normalizedKey, $columnMapping)) {
                $normalizedKey = $columnMapping[$normalizedKey];
            } else {
                $normalizedKey = str_replace(' ', '_', $normalizedKey);
            }

            // Normalisasi value
            if (is_string($value)) {
                $value = trim($value);
                if ($value === '' || strtolower($value) === 'null') {
                    $value = null;
                }
            }

            $result[$normalizedKey] = $value;
        }

        return $result;
    }

    // ================= NORMALISASI VALUE =================
    protected function normalizeValue(?string $value, string $type = 'general'): ?string
    {
        if (!$value) return null;

        $v = strtolower(trim($value));

        switch ($type) {
            case 'perokok':
                $perokokMap = [
                    'tidak' => 'Tidak',
                    'tdk' => 'Tidak',
                    'no' => 'Tidak',
                    'n' => 'Tidak',
                    'false' => 'Tidak',
                    '0' => 'Tidak',
                    'ya' => 'Ya',
                    'iya' => 'Ya',
                    'yes' => 'Ya',
                    'y' => 'Ya',
                    'true' => 'Ya',
                    '1' => 'Ya',
                    'tidak merokok' => 'Tidak',
                    'merokok' => 'Ya',
                ];
                return $perokokMap[$v] ?? ucfirst($v);

            case 'pendidikan':
                $pendidikanMap = [
                    's-1' => 'S1',
                    's1' => 'S1',
                    's-1 profesi' => 'S1',
                    's1 profesi' => 'S1',
                    'strata 1' => 'S1',
                    's-2' => 'S2',
                    's2' => 'S2',
                    'strata 2' => 'S2',
                    's-3' => 'S3',
                    's3' => 'S3',
                    'strata 3' => 'S3',
                    'd-3' => 'D3',
                    'd3' => 'D3',
                    'diploma 3' => 'D3',
                    'd-4' => 'D4',
                    'd4' => 'D4',
                    'diploma 4' => 'D4',
                    'sma' => 'SMA',
                    'smk' => 'SMK',
                    'ma' => 'MA',
                    'smp' => 'SMP',
                    'sd' => 'SD',
                ];
                return $pendidikanMap[$v] ?? ucfirst($v);

            case 'ukuran':
                $ukuranMap = [
                    'xs' => 'S',
                    'extra small' => 'S',
                    's panjang' => 'S',
                    's lengan panjang' => 'S',
                    'm panjang' => 'M',
                    'l panjang' => 'L',
                    'xl panjang' => 'XL',
                    'xxl panjang' => 'XXL',
                    'xxl (lengan panjang)' => 'XXL',
                    'xxxl' => 'XXXL',
                    '3xl' => 'XXXL',
                    'xxxxl' => 'XXXXL',
                    '4xl' => 'XXXXL',
                ];
                return $ukuranMap[$v] ?? strtoupper($v);

            default:
                return trim($value);
        }
    }

    // ================= PARSING DATE =================
    protected function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            // Jika numerik (Excel date)
            if (is_numeric($date)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
            }
            
            // Coba berbagai format
            $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d', 'Y/m/d', 'd F Y', 'j F Y'];
            
            foreach ($formats as $format) {
                $parsed = \Carbon\Carbon::createFromFormat($format, $date);
                if ($parsed !== false) {
                    return $parsed->format('Y-m-d');
                }
            }
            
            // Coba parse secara general
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
            
        } catch (\Exception $e) {
            Log::warning("Failed to parse date: {$date}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    // ================= HELPER FUNCTIONS =================
    private function getColumnLetter($columnNumber)
    {
        $letter = '';
        while ($columnNumber > 0) {
            $columnNumber--;
            $letter = chr(65 + ($columnNumber % 26)) . $letter;
            $columnNumber = intval($columnNumber / 26);
        }
        return $letter;
    }

    private function isValidDate($date)
    {
        if (is_numeric($date)) {
            return true; // Excel date serial number
        }
        
        try {
            $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d', 'Y/m/d'];
            foreach ($formats as $format) {
                $parsed = \DateTime::createFromFormat($format, $date);
                if ($parsed !== false) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function onError(\Throwable $e)
    {
        Log::error('Excel import error: ' . $e->getMessage());
    }

    public function getStats(): array
    {
        return [
            'success' => $this->successCount,
            'failed' => $this->failedCount,
            'duplicate' => $this->duplicateCount,
            'total' => $this->successCount + $this->failedCount + $this->duplicateCount,
        ];
    }

    public function getErrors(): array
    {
        return $this->importErrors;
    }

    public function headings(): array
    {
        return [
            'JENIS_PELATIHAN',
            'ANGKATAN',
            'TAHUN_ANGKATAN',
            'KATEGORI',
            'WILAYAH',
            'NIP_NRP',
            'NAMA_LENGKAP',
            'JENIS_KELAMIN',
            'NAMA_PANGGILAN',
            'AGAMA',
            'TEMPAT_LAHIR',
            'TANGGAL_LAHIR',
            'ALAMAT_RUMAH',
            'EMAIL_PRIBADI',
            'NOMOR_HP',
            'PENDIDIKAN_TERAKHIR',
            'BIDANG_STUDI',
            'BIDANG_KEAHLIAN',
            'STATUS_PERKAWINAN',
            'NAMA_PASANGAN',
            'OLAHRAGA_HOBI',
            'PEROKOK',
            'UKURAN_KAOS',
            'UKURAN_CELANA',
            'UKURAN_TRAINING',
            'KONDISI_PESERTA',
            'ASAL_INSTANSI',
            'UNIT_KERJA',
            'PROVINSI',
            'KABUPATEN_KOTA',
            'ALAMAT_KANTOR',
            'NOMOR_TELEPON_KANTOR',
            'EMAIL_KANTOR',
            'JABATAN',
            'PANGKAT',
            'GOLONGAN_RUANG',
            'ESELON',
            'TANGGAL_SK_JABATAN',
            'NOMOR_SK_CPNS',
            'NOMOR_SK_TERAKHIR',
            'TANGGAL_SK_CPNS',
            'TAHUN_LULUS_PKP_PIM_IV',
        ];
    }
}