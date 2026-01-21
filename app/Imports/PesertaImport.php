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
use Illuminate\Support\Facades\Schema;
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
    // protected array $errors = [];

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

                // ================= JENIS PELATIHAN =================
                $jenisPelatihan = JenisPelatihan::where('nama_pelatihan', trim($row['jenis_pelatihan']))
                    ->orWhere('kode_pelatihan', trim($row['jenis_pelatihan']))
                    ->first();

                if (!$jenisPelatihan) {
                    $this->errors[] = "Baris {$rowNumber}: Jenis pelatihan '{$row['jenis_pelatihan']}' tidak ditemukan";
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= ANGKATAN (STRICT) =================
                $angkatan = Angkatan::where([
                    'id_jenis_pelatihan' => $jenisPelatihan->id,
                    'tahun' => trim($row['tahun_angkatan']),
                    'nama_angkatan' => trim($row['angkatan']),
                ])->first();

                if (!$angkatan) {
                    $this->errors[] =
                        "Baris {$rowNumber}: Angkatan '{$row['angkatan']}' tahun {$row['tahun_angkatan']} untuk pelatihan '{$jenisPelatihan->nama_pelatihan}' TIDAK DITEMUKAN";
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= CEK KUOTA =================
                if (Pendaftaran::where('id_angkatan', $angkatan->id)->count() >= $angkatan->kuota) {
                    $this->errors[] = "Baris {$rowNumber}: Kuota angkatan '{$angkatan->nama_angkatan}' penuh";
                    $this->failedCount++;
                    DB::rollBack();
                    continue;
                }

                // ================= PESERTA =================
                $peserta = Peserta::where('nip_nrp', $row['nip_nrp'])->first();

                if ($peserta) {
                    $exists = Pendaftaran::where([
                        'id_peserta' => $peserta->id,
                        'id_jenis_pelatihan' => $jenisPelatihan->id,
                        'id_angkatan' => $angkatan->id,
                    ])->exists();

                    if ($exists) {
                        $this->errors[] = "Baris {$rowNumber}: NIP '{$row['nip_nrp']}' sudah terdaftar";
                        $this->duplicateCount++;
                        DB::rollBack();
                        continue;
                    }
                }

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
                        'perokok'             => $row['perokok'] ?? null,
                        'email_pribadi'       => $row['email_pribadi'] ?? null,
                        'nomor_hp'            => $row['nomor_hp'] ?? null,
                        'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
                        'bidang_studi'        => $row['bidang_studi'] ?? null,
                        'bidang_keahlian'     => $row['bidang_keahlian'] ?? null,
                        'ukuran_kaos'         => $row['ukuran_kaos'] ?? null,
                        'ukuran_celana'       => $row['ukuran_celana'] ?? null,
                        'ukuran_training'     => $row['ukuran_training'] ?? null,
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
                        'tanggal_sk_jabatan' => $row['tanggal_sk_jabatan'] ?? null,
                        'tahun_lulus_pkp_pim_iv' => $row['tahun_lulus_pkp_pim_iv'] ?? null,
                        'tanggal_sk_cpns' => $row['tanggal_sk_cpns'] ?? null,
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
                $this->errors[] = "Baris {$rowNumber}: {$e->getMessage()}";
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
    protected function validateRequiredFields(Collection &$row, int $rowNumber): bool
    {
        $required = ['jenis_pelatihan', 'angkatan', 'tahun_angkatan', 'nip_nrp', 'nama_lengkap', 'jenis_kelamin'];

        foreach ($required as $field) {
            if (empty($row[$field])) {
                $this->errors[] = "Baris {$rowNumber}: Field {$field} wajib diisi";
                return false;
            }
        }

        $jk = strtolower($row['jenis_kelamin']);
        if (!in_array($jk, ['laki-laki', 'perempuan', 'l', 'p'])) {
            $this->errors[] = "Baris {$rowNumber}: Jenis kelamin tidak valid";
            return false;
        }

        $row['jenis_kelamin'] = in_array($jk, ['l', 'laki-laki']) ? 'Laki-laki' : 'Perempuan';
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

    public function onError(\Throwable $e) {}

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
        return $this->errors;
    }
}
