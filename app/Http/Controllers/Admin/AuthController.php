<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak memiliki hak akses administrator.',
                ]);
            }

            ActivityLog::log('ADMIN_LOGIN', 'AUTH', 'Admin ' . Auth::user()->email . ' berhasil login ke control panel.');

            return redirect()->intended(route('admin.dashboard'));
        }

        ActivityLog::log('ADMIN_LOGIN_FAILED', 'AUTH', 'Percobaan login gagal untuk email: ' . $request->email);

        return back()->withErrors([
            'email' => 'Kombinasi email dan password tidak sesuai.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $userEmail = Auth::user()?->email;
        ActivityLog::log('ADMIN_LOGOUT', 'AUTH', 'Admin ' . $userEmail . ' logout.');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
    }
}
