<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';
    // protected $primaryKey = 'id_provinsi';
    public $timestamps = false;

    protected $fillable = [
        'nama_provinsi',
    ];

    // Relasi: hasMany ke KabupatenKota
    public function kabupatenKota()
    {
        return $this->hasMany(KabupatenKota::class);
    }

    // Relasi: hasMany ke KepegawaianPeserta
    public function kepegawaianPeserta()
    {
        return $this->hasMany(KepegawaianPeserta::class);
    }
}
