<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisNilai extends Model
{
    use HasFactory;

    protected $table = 'jenis_nilai';

    protected $fillable = [
        'id_jenis_pelatihan',
        'name',
        'deskripsi',
        'bobot',
    ];

    protected $casts = [
        'bobot' => 'float',
    ];

    // Relasi ke JenisPelatihan (belongsTo)
    public function jenisPelatihan()
    {
        return $this->belongsTo(JenisPelatihan::class, 'id_jenis_pelatihan');
    }

    // Relasi ke IndikatorNilai (hasMany)
    public function indikatorNilai()
    {
        return $this->hasMany(IndikatorNilai::class, 'id_jenis_nilai');
    }

    // Relasi ke CatatanNilai (hasMany)
    public function catatanNilai()
    {
        return $this->hasMany(CatatanNilai::class, 'id_jenis_nilai');
    }

    /**
     * Total bobot indikator yang sudah terpakai di dalam jenis nilai ini.
     */
    public function totalBobotIndikator(): float
    {
        return (float) $this->indikatorNilai()->sum('bobot');
    }

    /**
     * Sisa bobot indikator yang masih bisa dialokasikan.
     * Total indikator tidak boleh melebihi bobot jenis nilai ini.
     */
    public function sisaBobotIndikator(): float
    {
        return round($this->bobot - $this->totalBobotIndikator(), 2);
    }
}