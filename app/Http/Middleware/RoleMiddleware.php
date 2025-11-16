<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role (ini adalah role yang DIZINKAN, misal: 'dosen')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // Jika tidak login, lempar
        if (!$user) {
            return redirect()->route('home');
        }

        // --- INI PERBAIKANNYA ---
        // Jika route ini butuh role 'dosen',
        // izinkan jika user-nya 'dosen' ATAU 'superadmin'.
        if ($role === 'dosen') {
            if ($user->role === 'dosen' || $user->role === 'superadmin') {
                return $next($request);
            }
        }
        
        // Jika route ini butuh role 'superadmin',
        // HANYA izinkan 'superadmin'.
        if ($role === 'superadmin') {
            if ($user->role === 'superadmin') {
                return $next($request);
            }
        }
        
        // Jika route ini butuh role 'mahasiswa',
        // HANYA izinkan 'mahasiswa'.
        if ($role === 'mahasiswa') {
            if ($user->role === 'mahasiswa') {
                return $next($request);
            }
        }
        // -------------------------

        // Jika semua gagal, tolak akses.
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses.');
    }
}