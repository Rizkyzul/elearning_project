<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $table = 'absensi';
    protected $guarded = [];

    // --- TAMBAHKAN BLOK INI ---
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scan_masuk' => 'datetime',
        'scan_keluar' => 'datetime',
    ];
    // --- BATAS TAMBAHAN ---

    public function sesiPerkuliahan()
    {
        return $this->belongsTo(SesiPerkuliahan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}