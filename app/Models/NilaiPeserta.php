<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiPeserta extends Model
{
    use HasFactory;

    protected $table = 'nilai_peserta';

    protected $fillable = [
        'id_peserta',
        'id_indikator_nilai',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'float',
    ];

    // Relasi ke Peserta (belongsTo)
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta');
    }

    // Relasi ke IndikatorNilai (belongsTo)
    public function indikatorNilai()
    {
        return $this->belongsTo(IndikatorNilai::class, 'id_indikator_nilai');
    }
}