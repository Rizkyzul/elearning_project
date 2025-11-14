<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil profil dosen yang sedang login, lalu ambil relasi matkul()
        $dosenProfile = Auth::user()->dosenProfile;
        $mataKuliah = $dosenProfile->matkul()->orderBy('nama_matkul')->get();

        // Kirim data mata kuliah ke view
        return view('dosen.dashboard', [
            'mataKuliah' => $mataKuliah
        ]); 
    }
}