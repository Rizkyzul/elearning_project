<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;
    protected $table = 'materi';
    protected $guarded = [];

    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }
    public function kelas() // <-- TAMBAHKAN INI
    {
        return $this->belongsToMany(Kelas::class, 'materi_kelas');
    }
}