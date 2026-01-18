<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    use HasFactory;

    protected $table = 'angkatan';
    public $timestamps = false;

    protected $fillable = [
        'id_jenis_pelatihan',
        'nama_angkatan',
        'tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'status_angkatan',
        'dibuat_pada', // tambahkan ini
    ];

    // Casting untuk properti tanggal
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'dibuat_pada' => 'datetime', // casting ke datetime
    ];

    // Relasi: belongsTo ke JenisPelatihan
    public function jenisPelatihan()
    {
        return $this->belongsTo(JenisPelatihan::class, 'id_jenis_pelatihan');
    }

    // Relasi: hasMany ke Pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_angkatan');
    }

    // Relasi: hasMany ke PicPeserta
    public function picPesertas()
    {
        return $this->hasMany(PicPeserta::class, 'angkatan_id');
    }
}
