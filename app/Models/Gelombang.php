<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    use HasFactory;

    protected $table = 'gelombang';
    public $timestamps = true;

    protected $fillable = [
        'id_jenis_pelatihan',
        'nama_gelombang',
        'tahun',
        'kategori',
    ];

    // Relasi ke JenisPelatihan
    public function jenisPelatihan()
    {
        return $this->belongsTo(JenisPelatihan::class, 'id_jenis_pelatihan');
    }

    // Relasi ke Angkatan
    public function angkatan()
    {
        return $this->hasMany(Angkatan::class, 'id_gelombang');
    }
}