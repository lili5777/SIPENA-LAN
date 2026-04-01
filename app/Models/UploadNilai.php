<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UploadNilai extends Model
{
    use HasFactory;

    protected $table = 'upload_nilai';

    protected $fillable = [
        'id_peserta',
        'id_indikator_nilai',
        'file',
        'nilai',
        'catatan_peserta',
        'catatan_verifikator',
        'status',           // pending | disetujui | ditolak
        'id_verifikator',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'nilai'       => 'float',
    ];

    // ── Relasi ────────────────────────────────────────────────

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta');
    }

    public function indikatorNilai()
    {
        return $this->belongsTo(IndikatorNilai::class, 'id_indikator_nilai');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'id_verifikator');
    }

    // ── Helper status ─────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDiSetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    // ── Label & warna status untuk blade ─────────────────────

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Verifikasi',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => 'Belum Disubmit',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending'   => 'bg-warning text-dark',
            'disetujui' => 'bg-success',
            'ditolak'   => 'bg-danger',
            default     => 'bg-secondary',
        };
    }
}