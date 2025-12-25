<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';
    
    // protected $primaryKey = 'id_pendaftaran';
    public $timestamps = false;

    protected $fillable = [
        'id_peserta',
        'id_jenis_pelatihan',
        'id_angkatan',
        'file_surat_tugas',
        'file_surat_kesediaan',
        'file_pakta_integritas',
        'file_surat_komitmen',
        'file_surat_kelulusan_seleksi',
        'file_surat_sehat',
        'file_surat_bebas_narkoba',
        'file_surat_pernyataan_administrasi',
        'file_sertifikat_penghargaan',
        'status_pendaftaran',
        'tanggal_daftar',
        'tanggal_verifikasi',
        'catatan_verifikasi',
    ];

    protected $dates = ['tanggal_daftar', 'tanggal_verifikasi'];

    // Relasi: belongsTo ke Peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    // Relasi: belongsTo ke JenisPelatihan
    public function jenisPelatihan()
    {
        return $this->belongsTo(JenisPelatihan::class);
    }

    // Relasi: belongsTo ke Angkatan
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }

    // Relasi: hasMany ke PesertaMentor
    public function pesertaMentor()
    {
        return $this->hasMany(PesertaMentor::class);
    }

    // Relasi: hasMany ke LogAktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }
}
