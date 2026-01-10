<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';
    public $timestamps = false;

    protected $fillable = [
        'nip_nrp',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'agama',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_rumah',
        'email_pribadi',
        'nomor_hp',
        'pendidikan_terakhir',
        'bidang_studi',
        'bidang_keahlian',
        'status_perkawinan',
        'nama_pasangan',
        'olahraga_hobi',
        'perokok',
        'ukuran_kaos',
        'kondisi_peserta',
        'file_ktp',
        'file_pas_foto',
        'status_aktif',
    ];

    protected $dates = ['tanggal_lahir'];

    // Relasi: hasOne ke KepegawaianPeserta
    public function kepegawaian()
    {
        return $this->hasOne(KepegawaianPeserta::class, 'id_peserta');
    }

    // Tetap ada method lama untuk kompatibilitas
    public function kepegawaianPeserta()
    {
        return $this->kepegawaian();
    }

    // Relasi: hasMany ke Pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_peserta');
    }

    // Relasi: hasMany ke LogAktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'id_peserta');
    }
}
