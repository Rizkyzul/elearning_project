<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // <-- Pastikan ini ada

class ForceChangePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->must_change_password) {
            if (! $request->routeIs('password.change.*') && ! $request->routeIs('logout')) {
                return redirect()->route('password.change.edit')
                                 ->with('warning', 'Keamanan akun Anda penting. Silakan ganti password default Anda.');
            }
        }

        return $next($request);
    }
}