<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->hasMany(User::class);
    }


    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function indikatorNilai()
    {
        return $this->belongsToMany(
            IndikatorNilai::class,
            'akses_penilaian',
            'role_id',
            'id_indikator_nilai'
        )->withTimestamps();
    }
    
    public function aksesPenilaian()
    {
        return $this->hasMany(AksesPenilaian::class, 'role_id');
    }
}
