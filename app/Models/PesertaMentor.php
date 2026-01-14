<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaMentor extends Model
{
    use HasFactory;

    protected $table = 'peserta_mentor';
    // protected $primaryKey = 'id_peserta_mentor';
    public $timestamps = false;

    protected $fillable = [
        'id_pendaftaran',
        'id_mentor',
        'tanggal_penunjukan',
        'status_mentoring',
        'catatan',
    ];

    protected $dates = ['tanggal_penunjukan'];
    protected $casts = [
        'tanggal_penunjukan' => 'datetime',
    ];

    // Relasi: belongsTo ke Pendaftaran
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran');
    }

    // Relasi: belongsTo ke Mentor
    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'id_mentor');
    }
}
