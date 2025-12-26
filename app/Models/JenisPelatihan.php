<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPelatihan extends Model
{
    use HasFactory;

    protected $table = 'jenis_pelatihan';
    // protected $primaryKey = 'id_jenis_pelatihan';
    public $timestamps = false; // Karena hanya dibuat_pada, bukan updated_at

    protected $fillable = [
        'kode_pelatihan',
        'nama_pelatihan',
        'deskripsi',
        'aktif',
    ];

    // Relasi: hasMany ke Angkatan
    public function angkatan()
    {
        return $this->hasMany(Angkatan::class, 'id_jenis_pelatihan');
    }

    // Relasi: hasMany ke Pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_jenis_pelatihan');
    }
}