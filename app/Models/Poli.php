<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $fillable = [
        'nama_poli',
        'deskripsi',
        'icon_image',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
