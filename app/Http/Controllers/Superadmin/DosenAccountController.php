<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen; // <-- PASTIKAN INI ADA
use Illuminate\Support\Facades\Hash; // <-- PASTIKAN INI ADA

class DosenAccountController extends Controller
{
    public function create()
    {
        $dosenList = Dosen::with('user')
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return view('superadmin.dosen.create', [
            'dosenList' => $dosenList
        ]);
    }

  public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|unique:dosen,nidn',
            'email' => 'required|email|unique:users,email',
            'prodi' => 'required|string|max:255',
        ], [
            'nidn.unique' => 'NIDN ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.'
        ]);

        try {
            // 1. Buat Akun User (Login)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'dosen',
                'password' => Hash::make('password'),
                'must_change_password' => true,
                'email_verified_at' => now(),
            ]);

            // 2. === INI PERBAIKANNYA ===
            // Buat Profil Dosen (Biodata)
            Dosen::create([
                'user_id' => $user->id,
                'nama' => $request->name, // <-- INI YANG HILANG
                'nidn' => $request->nidn,
                'prodi' => $request->prodi,
            ]);
            // ==========================

            return redirect()->route('superadmin.dosen.create')
                             ->with('success', 'Akun Dosen "' . $request->name . '" berhasil dibuat.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}