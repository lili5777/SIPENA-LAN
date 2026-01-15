<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupatens';

    protected $fillable = [
        'id',
        'province_id',
        'code',
        'name'
    ];

    public $timestamps = true;

    // Relasi: belongsTo ke Provinsi
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'province_id', 'id'); // Perhatikan foreign key
    }

    // Relasi: hasMany ke KepegawaianPeserta
    public function kepegawaianPesertas()
    {
        return $this->hasMany(KepegawaianPeserta::class, 'id_kabupaten_kota');
    }

    // Helper method untuk sync data dari API
    public static function syncFromApi(array $data, $provinsiId)
    {
        $kabupaten = self::updateOrCreate(
            ['code' => $data['code']],
            [
                'id_provinsi' => $provinsiId,
                'name' => $data['name']
            ]
        );

        return $kabupaten;
    }
}
