<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class kelompok_peserta extends Model
{
     use HasFactory;

    protected $table = 'kelompok_pesertas';
    public $timestamps = false;

    protected $fillable = [
        'id_kelompok',
        'id_peserta',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta');
    }
}
