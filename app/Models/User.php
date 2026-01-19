<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'no_telp',
        'peserta_id',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permissionName)
    {
        return $this->role
            ->permissions()
            ->where('name', $permissionName)
            ->exists();
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function picPesertas()
    {
        return $this->hasMany(PicPeserta::class, 'user_id');
    }

    public function isPic()
    {
        return $this->role && $this->role->name === 'pic';
    }

    public function aktifikats()
    {
        return $this->hasMany(Aktifitas::class);
    }
}
