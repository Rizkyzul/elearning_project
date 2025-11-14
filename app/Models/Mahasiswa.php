<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function matkul()
    {
        return $this->belongsToMany(Matkul::class, 'mahasiswa_matkul');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function jawabanTugas()
    {
        return $this->hasMany(JawabanTugas::class);
    }
}