<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    // protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'id_peserta',
        'id_pendaftaran',
        'jenis_aktivitas',
        'deskripsi',
        'ip_address',
        'user_agent',
    ];

    // Relasi: belongsTo ke Peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    // Relasi: belongsTo ke Pendaftaran
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
