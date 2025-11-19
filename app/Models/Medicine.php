<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'nama_obat',
        'deskripsi',
        'tipe_obat',
        'stok',
        'gambar_obat',
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function isAvailable()
    {
        return $this->stok > 0;
    }
}