<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    use HasFactory;

    protected $table = 'angkatan';
    // protected $primaryKey = 'id_angkatan';
    public $timestamps = false;

    protected $fillable = [
        'id_jenis_pelatihan',
        'nama_angkatan',
        'tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'status_angkatan',
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
}
