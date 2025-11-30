<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    // Pastikan 'photo' tetap ada di fillable
    protected $fillable = ['user_id', 'poli_id', 'sip', 'photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // 👇 TAMBAHKAN FUNGSI RELASI INI 👇
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}