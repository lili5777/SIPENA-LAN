<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';
    public $timestamps = false;

    protected $fillable = [
        'id_jenis_pelatihan',
        'id_angkatan',
        'nama_kelompok',
        'tahun',
        'id_mentor',
        'id_coach',
        'id_penguji',
        'id_evaluator',
        'keterangan',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    public function jenisPelatihan()
    {
        return $this->belongsTo(JenisPelatihan::class, 'id_jenis_pelatihan');
    }

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'id_angkatan');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'id_mentor');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'id_coach');
    }

    public function penguji()
    {
        return $this->belongsTo(Penguji::class, 'id_penguji');
    }

    public function evaluator()
    {
        return $this->belongsTo(Evaluator::class, 'id_evaluator');
    }

    public function peserta()
    {
        return $this->belongsToMany(Peserta::class, 'kelompok_pesertas', 'id_kelompok', 'id_peserta')
                    ->withPivot('dibuat_pada');
    }

    public function kelompokPeserta()
    {
        return $this->hasMany(kelompok_peserta::class, 'id_kelompok');
    }
}