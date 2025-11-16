<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiPerkuliahan extends Model
{
    use HasFactory;
    
  
    protected $guarded = [];
    // -------------------------

    protected $table = 'sesi_perkuliahan';

    // Casts (Penting untuk timer)
    protected $casts = [
        'expires_at_masuk' => 'datetime',
        'expires_at_keluar' => 'datetime',
    ];

    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function kelas() // <-- Tambahkan relasi ini
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}