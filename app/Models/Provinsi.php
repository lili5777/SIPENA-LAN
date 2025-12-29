<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsis';

    protected $fillable = [
        'id',
        'code',
        'name'
    ];

    public $timestamps = true;

    // Relasi: hasMany ke Kabupaten
    public function kabupatens()
    {
        return $this->hasMany(Kabupaten::class, 'id_provinsi');
    }

    // Relasi: hasMany ke KepegawaianPeserta
    public function kepegawaianPesertas()
    {
        return $this->hasMany(KepegawaianPeserta::class, 'id_provinsi');
    }

    // Helper method untuk sync data dari API
    public static function syncFromApi(array $data)
    {
        $provinsi = self::updateOrCreate(
            ['code' => $data['code']],
            ['name' => $data['name']]
        );

        return $provinsi;
    }
}
