<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepegawaianPeserta extends Model
{
    use HasFactory;

    protected $table = 'kepegawaian_peserta';
    // protected $primaryKey = 'id_kepegawaian';
    public $timestamps = false;

    protected $fillable = [
        'id_peserta',
        'asal_instansi',
        'unit_kerja',
        'id_provinsi',
        'id_kabupaten_kota',
        'alamat_kantor',
        'nomor_telepon_kantor',
        'email_kantor',
        'jabatan',
        'eselon',
        'tanggal_sk_jabatan',
        'file_sk_jabatan',
        'pangkat',
        'golongan_ruang',
        'file_sk_pangkat',
        'nomor_sk_cpns',
        'nomor_sk_terakhir',
        'tanggal_sk_cpns',
        'file_sk_cpns',
        'file_spmt',
        'file_skp',
        'tahun_lulus_pkp_pim_iv',
    ];

    protected $dates = ['tanggal_sk_jabatan', 'tanggal_sk_cpns'];

    // Relasi: belongsTo ke Peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta');
    }

}
