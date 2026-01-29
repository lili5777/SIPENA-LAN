<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pejabat extends Model
{
    //
    protected $fillable = [
        'nama_pejabat',
        'jabatan_pejabat',
        'nip_pejabat',
        'posisi',
        'foto_pejabat',
    ];
}
