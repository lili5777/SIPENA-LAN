<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AksiPerubahan extends Model
{
    //
    protected $fillable = [
        'id_pendaftar',
        'judul',
        'abstrak',
        'kategori_aksatika',
        'file',
        'link_video',
        'link_laporan_majalah',
        'lembar_pengesahan',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Angkatan::class, 'id_pendaftar');
    }
}
