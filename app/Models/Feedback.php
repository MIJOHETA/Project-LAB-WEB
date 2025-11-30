<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'appointment_id',
        'rating',
        'comment'
    ];

    // Relasi ke User (Pasien)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Dokter
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Relasi ke Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}