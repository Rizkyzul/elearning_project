<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanTugas extends Model
{
    use HasFactory;
    protected $table = 'jawaban_tugas';
    protected $guarded = [];

    // --- TAMBAHKAN BLOK INI ---
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'submitted_at' => 'datetime', // Otomatis ubah 'submitted_at' jadi objek Carbon
    ];
    // --- BATAS TAMBAHAN ---

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}