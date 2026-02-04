<?php

namespace App\Imports;

use App\Models\Peserta;
use App\Models\KepegawaianPeserta;
use App\Models\Pendaftaran;
use App\Models\JenisPelatihan;
use App\Models\Angkatan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class PesertaImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    protected int $successCount = 0;
    protected int $failedCount = 0;
    protected int $duplicateCount = 0;
    protected array $importErrors = []; // GANTI NAMA DARI $errors MENJADI $importErrors

    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $index => $rawRow) {
            $rowNumber = $index + 2;
            $row = collect($this->normalizeData($rawRow));

            // ⛔ Skip baris kosong total
            if ($row->filter(fn($v) => $v !== null && trim($v) !== '')->isEmpty()) {
                continue;
            }

            DB::beginTransaction();
            try {
                // ================= VALIDASI WAJIB =================
                if (!$this->validateRequiredFields($row, $rowNumber)) {
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= VALIDASI KATEGORI =================
                $kategori = strtoupper(trim($row['kategori']));
                $validKategori = ['PNBP', 'FASILITASI'];
                
                if (!in_array($kategori, $validKategori)) {
                    $this->importErrors[] = "Baris {$rowNumber}: Kategori '{$row['kategori']}' tidak valid. Harus PNBP atau FASILITASI"; // GANTI
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= VALIDASI WILAYAH =================
                if ($kategori === 'FASILITASI' && empty($row['wilayah'])) {
                    $this->importErrors[] = "Baris {$rowNumber}: Wilayah wajib diisi jika kategori FASILITASI"; // GANTI
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= CEK JENIS PELATIHAN =================
                $jenisPelatihan = JenisPelatihan::where('nama_pelatihan', trim($row['jenis_pelatihan']))
                    ->orWhere('kode_pelatihan', trim($row['jenis_pelatihan']))
                    ->first();

                if (!$jenisPelatihan) {
                    $this->importErrors[] = "Baris {$rowNumber}: Jenis pelatihan '{$row['jenis_pelatihan']}' tidak ditemukan"; // GANTI
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= CEK/CREATE ANGKATAN DENGAN KATEGORI & WILAYAH =================
                $angkatanData = [
                    'id_jenis_pelatihan' => $jenisPelatihan->id,
                    'tahun' => trim($row['tahun_angkatan']),
                    'nama_angkatan' => trim($row['angkatan']),
                    'kategori' => $kategori,
                    'wilayah' => !empty($row['wilayah']) ? trim($row['wilayah']) : null,
                ];

                // Cek apakah angkatan sudah ada dengan data yang sama
                $angkatan = Angkatan::where($angkatanData)->first();

                // Jika belum ada, buat baru dengan kuota default
                if (!$angkatan) {
                    $wilayahInfo = $angkatanData['wilayah'] ? " Wilayah: {$angkatanData['wilayah']}" : "";
                    $this->importErrors[] = "Baris {$rowNumber}: Angkatan '{$angkatanData['nama_angkatan']}' Tahun {$angkatanData['tahun']} Kategori {$kategori}{$wilayahInfo} tidak terdaftar. Silakan buat angkatan terlebih dahulu.";
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= CEK KUOTA =================
                if (Pendaftaran::where('id_angkatan', $angkatan->id)->count() >= $angkatan->kuota) {
                    $this->importErrors[] = "Baris {$rowNumber}: Kuota angkatan '{$angkatan->nama_angkatan}' penuh"; // GANTI
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= PESERTA =================
                $peserta = Peserta::where('nip_nrp', $row['nip_nrp'])->first();

                if ($peserta) {
                    // Cek apakah sudah terdaftar di angkatan yang sama
                    $exists = Pendaftaran::where([
                        'id_peserta' => $peserta->id,
                        'id_angkatan' => $angkatan->id,
                    ])->exists();

                    if ($exists) {
                        $this->importErrors[] = "Baris {$rowNumber}: NIP '{$row['nip_nrp']}' sudah terdaftar di angkatan ini"; // GANTI
                        $this->duplicateCount++;
                        DB::rollBack();
                        continue;
                    }
                }

                // Buat atau update data peserta (TANPA kategori dan wilayah di peserta)
                $peserta = Peserta::updateOrCreate(
                    ['nip_nrp' => $row['nip_nrp']],
                    [
                        'nama_lengkap'        => $row['nama_lengkap'],
                        'nama_panggilan'      => $row['nama_panggilan'] ?? null,
                        'jenis_kelamin'       => $row['jenis_kelamin'],
                        'agama'               => $row['agama'] ?? null,
                        'tempat_lahir'        => $row['tempat_lahir'] ?? null,
                        'tanggal_lahir'       => $this->parseDate($row['tanggal_lahir'] ?? null),
                        'alamat_rumah'        => $row['alamat_rumah'] ?? null,
                        'status_perkawinan'   => $row['status_perkawinan'] ?? null,
                        'nama_pasangan'       => $row['nama_pasangan'] ?? null,
                        'olahraga_hobi'       => $row['olahraga_hobi'] ?? null,
                        'perokok'             => $this->normalizeValue($row['perokok'] ?? null),
                        'email_pribadi'       => $row['email_pribadi'] ?? null,
                        'nomor_hp'            => $row['nomor_hp'] ?? null,
                        'pendidikan_terakhir' => $this->normalizeValue($row['pendidikan_terakhir'] ?? null),
                        'bidang_studi'        => $row['bidang_studi'] ?? null,
                        'bidang_keahlian'     => $row['bidang_keahlian'] ?? null,
                        'ukuran_kaos'         => $this->normalizeValue($row['ukuran_kaos'] ?? null),
                        'ukuran_celana'       => $this->normalizeValue($row['ukuran_celana'] ?? null),
                        'ukuran_training'     => $this->normalizeValue($row['ukuran_training'] ?? null),
                        'status_aktif'        => true,
                    ]
                );

                // ================= KEPEGAWAIAN =================
                $provinsi = !empty($row['provinsi'])
                    ? Provinsi::where('name', trim($row['provinsi']))->first()
                    : null;

                $kabupaten = ($provinsi && !empty($row['kabupaten_kota']))
                    ? Kabupaten::where('province_id', $provinsi->id)
                    ->where('name', trim($row['kabupaten_kota']))
                    ->first()
                    : null;

                KepegawaianPeserta::updateOrCreate(
                    ['id_peserta' => $peserta->id],
                    [
                        'asal_instansi' => $row['asal_instansi'] ?? null,
                        'unit_kerja'    => $row['unit_kerja'] ?? null,
                        'jabatan'       => $row['jabatan'] ?? null,
                        'pangkat'       => $row['pangkat'] ?? null,
                        'golongan_ruang'      => $row['golongan_ruang'] ?? null,
                        'eselon'        => $row['eselon'] ?? null,
                        'alamat_kantor' => $row['alamat_kantor'] ?? null,
                        'nomor_telepon_kantor'   => $row['nomor_telepon_kantor'] ?? null,
                        'email_kantor'  => $row['email_kantor'] ?? null,
                        'id_provinsi'   => $provinsi?->id,
                        'id_kabupaten_kota'  => $kabupaten?->id,
                        'nomor_sk_cpns' => $row['nomor_sk_cpns'] ?? null,
                        'nomor_sk_terakhir' => $row['nomor_sk_terakhir'] ?? null,
                        'tanggal_sk_jabatan' => $this->parseDate($row['tanggal_sk_jabatan'] ?? null),
                        'tahun_lulus_pkp_pim_iv' => $row['tahun_lulus_pkp_pim_iv'] ?? null,
                        'tanggal_sk_cpns' => $this->parseDate($row['tanggal_sk_cpns'] ?? null),
                    ]
                );

                // ================= PENDAFTARAN =================
                Pendaftaran::create([
                    'id_peserta' => $peserta->id,
                    'id_jenis_pelatihan' => $jenisPelatihan->id,
                    'id_angkatan' => $angkatan->id,
                    'status_pendaftaran' => 'Menunggu Verifikasi',
                    'tanggal_daftar' => now(),
                ]);

                DB::commit();
                $this->successCount++;
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->importErrors[] = "Baris {$rowNumber}: {$e->getMessage()}"; // GANTI
                $this->failedCount++;
                Log::error("IMPORT ERROR ROW {$rowNumber}", ['error' => $e->getMessage()]);
            }
        }
    }

    // ================= NORMALISASI =================
    protected function normalizeData(array $row): array
    {
        $result = [];
        foreach ($row as $key => $value) {
            $key = strtolower(str_replace(' ', '_', trim($key)));

            if ($key === 'nip_nrp' && $value !== null) {
                $value = trim((string) $value);
                $value = preg_replace('/\D/', '', $value);
            }

            $result[$key] = is_string($value) ? trim($value) : $value;
        }
        return $result;
    }

    // ================= VALIDASI =================
    protected function normalizeValue(?string $value): ?string
    {
        if (!$value) return null;

        $v = strtolower(trim($value));

        // ================= PEROKOK =================
        $perokokMap = [
            'tidak' => 'Tidak',
            'tdk' => 'Tidak',
            'no' => 'Tidak',
            'n' => 'Tidak',
            'ya' => 'Ya',
            'iya' => 'Ya',
            'yes' => 'Ya',
            'y' => 'Ya',
            'Tidak Merokok' => 'Tidak',
            'tidak merokok' => 'Tidak',
            'Merokok' => 'Ya',
            'merokok' => 'Ya',
        ];

        if (array_key_exists($v, $perokokMap)) {
            return $perokokMap[$v];
        }

        // ================= PENDIDIKAN =================
        $pendidikanMap = [
            's-1' => 'S1',
            's1' => 'S1',
            's-1 profesi' => 'S1',
            's1 profesi' => 'S1',
            's-2' => 'S2',
            's2' => 'S2',
            's-3' => 'S3',
            's3' => 'S3',
            'd-3' => 'D3',
            'd3' => 'D3',
        ];

        if (array_key_exists($v, $pendidikanMap)) {
            return $pendidikanMap[$v];
        }


        // ================= UKURAN =================
        $ukuranMap = [
            'xs' => 'S',
            's panjang' => 'S',
            's lengan panjang' => 'S',
            'm panjang' => 'M',
            'xxl (lengan panjang)' => 'XXL',
        ];

        if (array_key_exists($v, $ukuranMap)) {
            return $ukuranMap[$v];
        }

        return trim($value);
    }

    protected function validateRequiredFields(Collection &$row, int $rowNumber): bool
    {
        $required = [
            'jenis_pelatihan', 
            'angkatan', 
            'tahun_angkatan', 
            'kategori', // WAJIB
            'nip_nrp', 
            'nama_lengkap', 
            // 'jenis_kelamin'
        ];

        foreach ($required as $field) {
            if (empty($row[$field])) {
                $this->importErrors[] = "Baris {$rowNumber}: Field {$field} wajib diisi"; // GANTI
                return false;
            }
        }

        // ================= VALIDASI JENIS KELAMIN =================
        $jk = strtolower(trim($row['jenis_kelamin']));

        $mapping = [
            'l'         => 'Laki-laki',
            'laki-laki' => 'Laki-laki',
            'laki laki' => 'Laki-laki',
            'pria'      => 'Laki-laki',
            'p'         => 'Perempuan',
            'perempuan' => 'Perempuan',
            'wanita'    => 'Perempuan',
        ];

        if (!array_key_exists($jk, $mapping)) {
            $this->importErrors[] = "Baris {$rowNumber}: Jenis kelamin '{$row['jenis_kelamin']}' tidak valid"; // GANTI
            return false;
        }

        $row['jenis_kelamin'] = $mapping[$jk];

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
                $this->importErrors[] = "Baris {$rowNumber}: Agama '{$row['agama']}' tidak dikenali"; // GANTI
                return false;
            }

            $row['agama'] = $agamaMap[$agama];
        }

        return true;
    }

    protected function parseDate($date)
    {
        try {
            if (is_numeric($date)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
            }
            return $date ? \Carbon\Carbon::parse($date)->format('Y-m-d') : null;
        } catch (\Exception) {
            return null;
        }
    }

    public function onError(\Throwable $e) {
        // Method dari SkipsOnError trait
        // Biarkan kosong atau tambahkan logging jika perlu
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
        return $this->importErrors; // GANTI
    }
}