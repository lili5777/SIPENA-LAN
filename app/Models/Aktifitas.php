<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktifikat extends Model
{
    use HasFactory;

    protected $fillable = ['deskripsi', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function humanTime()
    {
        $tz = 'Asia/Makassar';
        $dt = $this->created_at->copy()->setTimezone($tz);

        if ($dt->diffInDays(now($tz)) >= 1) {
            return $dt->translatedFormat('d M Y \p\u\k\u\l H:i');
        }

        return $dt->diffForHumans([
            'parts' => 1,
            'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
        ]);
    }
}
