<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AksiPerubahan extends Model
{
    //
    protected $fillable = [
        'id_pendaftar',
        'judul',
        'file',
        'biodata'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Angkatan::class, 'id_pendaftar');
    }
}
