<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;
    protected $table = 'dosen';
    protected $guarded = []; // Izinkan mass assignment

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matkul()
    {
        return $this->belongsToMany(Matkul::class, 'dosen_matkul');
    }
}