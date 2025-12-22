<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $table = 'mentor';
    // protected $primaryKey = 'id_mentor';
    public $timestamps = false;

    protected $fillable = [
        'nama_mentor',
        'jabatan_mentor',
        'nomor_rekening',
        'nama_bank',
        'atas_nama_rekening',
        'npwp_mentor',
        'email_mentor',
        'nomor_hp_mentor',
        'file_persetujuan_mentor',
        'status_aktif',
    ];

    // Relasi: hasMany ke PesertaMentor
    public function pesertaMentor()
    {
        return $this->hasMany(PesertaMentor::class);
    }
}
