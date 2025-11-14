<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;
    protected $table = 'tugas';
    protected $guarded = [];

    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }

    public function jawabanTugas()
    {
        return $this->hasMany(JawabanTugas::class);
    }
    public function kelas() // <-- TAMBAHKAN INI
    {
        return $this->belongsToMany(Kelas::class, 'tugas_kelas');
    }
    public function jawaban() 
    {
        return $this->hasMany(JawabanTugas::class);
    }
}