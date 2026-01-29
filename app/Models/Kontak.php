<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    //
    protected $fillable = [
        'alamat',
        'nomor_hp',
        'email',
        'fb',
        'ig',
        'twitter',
        'linkedin',
    ];
}
