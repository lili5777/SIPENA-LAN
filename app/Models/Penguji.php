<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penguji extends Model
{
    use HasFactory;

    protected $table = 'pengujis';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'golongan',
        'pangkat',
        'nomor_rekening',
        'npwp',
        'email',
        'nomor_hp',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'dibuat_pada'  => 'datetime',
    ];

    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'id_penguji');
    }
}