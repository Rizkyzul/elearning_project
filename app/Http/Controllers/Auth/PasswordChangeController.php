<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    public function edit()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false
        ]);

        $redirectRoute = $user->role === 'dosen' ? 'dosen.dashboard' : 'mahasiswa.dashboard';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Password berhasil diperbarui!');
    }
}