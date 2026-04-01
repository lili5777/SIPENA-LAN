<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndikatorNilai extends Model
{
    use HasFactory;

    protected $table = 'indikator_nilai';

    protected $fillable = [
        'id_jenis_nilai',
        'name',
        'deskripsi',
        'bobot',
    ];

    protected $casts = [
        'bobot' => 'float',
    ];

    // Relasi ke JenisNilai
    public function jenisNilai()
    {
        return $this->belongsTo(JenisNilai::class, 'id_jenis_nilai');
    }

    // Relasi ke DetailIndikator
    public function detailIndikator()
    {
        return $this->hasMany(DetailIndikator::class, 'id_indikator_nilai');
    }

    // Relasi ke NilaiPeserta
    public function nilaiPeserta()
    {
        return $this->hasMany(NilaiPeserta::class, 'id_indikator_nilai');
    }

    // ✅ Relasi ke AksesPenilaian
    public function aksesPenilaian()
    {
        return $this->hasMany(AksesPenilaian::class, 'id_indikator_nilai');
    }

    // ✅ Relasi ke Role melalui AksesPenilaian (many-to-many)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'akses_penilaian', 'id_indikator_nilai', 'role_id')
                    ->withTimestamps();
    }

    /**
     * Cek apakah role tertentu punya akses ke indikator ini.
     * Admin selalu punya akses.
     */
    public function bisaDinilaiOleh(int $roleId, string $roleName = ''): bool
    {
        // Admin bypass semua
        if ($roleName === 'admin') return true;

        // Cek di tabel akses_penilaian
        return $this->roles()->where('roles.id', $roleId)->exists();
    }
}