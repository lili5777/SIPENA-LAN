<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AksesPenilaian extends Model
{
    use HasFactory;

    protected $table = 'akses_penilaian';

    protected $fillable = [
        'id_indikator_nilai',
        'role_id',
    ];

    // Relasi ke IndikatorNilai
    public function indikatorNilai()
    {
        return $this->belongsTo(IndikatorNilai::class, 'id_indikator_nilai');
    }

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}