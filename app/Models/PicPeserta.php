<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PicPeserta extends Model
{
    //
    protected $table = 'pic_pesertas';

    protected $fillable = [
        'user_id',
        'jenispelatihan_id',
        'angkatan_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Relasi ke JenisPelatihan
    public function jenisPelatihan()
    {
        return $this->belongsTo(JenisPelatihan::class, 'jenispelatihan_id');
    }
    // Relasi ke Angkatan
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }
}
