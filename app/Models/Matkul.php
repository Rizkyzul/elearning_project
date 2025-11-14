<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;
    protected $table = 'matkul';
    protected $guarded = [];

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_matkul');
    }

    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_matkul');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function sesiPerkuliahan()
    {
        return $this->hasMany(SesiPerkuliahan::class);
    }
   public function nilai()
    {
        return $this->hasMany(Nilai::class, 'matkul_id');
    }

}