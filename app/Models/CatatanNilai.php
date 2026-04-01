<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatatanNilai extends Model
{
    use HasFactory;

    protected $table = 'catatan_nilai';

    protected $fillable = [
        'id_peserta',
        'id_jenis_nilai',
        'id_user',
        'catatan',
    ];

    // Relasi ke Peserta (belongsTo)
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta');
    }

    // Relasi ke JenisNilai (belongsTo)
    public function jenisNilai()
    {
        return $this->belongsTo(JenisNilai::class, 'id_jenis_nilai');
    }

    // Relasi ke User (belongsTo)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }
}