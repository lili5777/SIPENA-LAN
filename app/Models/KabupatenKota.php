<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KabupatenKota extends Model
{
    use HasFactory;

    protected $table = 'kabupaten_kota';
    // protected $primaryKey = 'id_kabupaten_kota';
    public $timestamps = false;

    protected $fillable = [
        'id_provinsi',
        'nama_kabupaten_kota',
    ];

    // Relasi: belongsTo ke Provinsi
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    // Relasi: hasMany ke KepegawaianPeserta
    public function kepegawaianPeserta()
    {
        return $this->hasMany(KepegawaianPeserta::class);
    }
}
