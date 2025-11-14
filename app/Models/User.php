<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Jika Anda menggunakan Sanctum (bawaan Breeze)

class User extends Authenticatable
{
    use  HasFactory, Notifiable,HasApiTokens; // Sesuaikan use jika berbeda

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',                 // Tambahkan ini
        'must_change_password', // Tambahkan ini
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'must_change_password' => 'boolean', // Tambahkan ini
    ];

    // --- RELASI ---

    /**
     * Relasi ke profil Dosen (One-to-One)
     */
    public function dosenProfile()
    {
        return $this->hasOne(Dosen::class);
    }

    /**
     * Relasi ke profil Mahasiswa (One-to-One)
     */
    public function mahasiswaProfile()
    {
        return $this->hasOne(Mahasiswa::class);
    }
}