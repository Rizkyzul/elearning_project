<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }
    public function materi() // <-- TAMBAHKAN INI
    {
        return $this->belongsToMany(Materi::class, 'materi_kelas');
    }
    
    public function tugas() // <-- TAMBAHKAN INI
    {
        return $this->belongsToMany(Tugas::class, 'tugas_kelas');
    }
}