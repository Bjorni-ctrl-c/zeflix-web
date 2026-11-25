<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function login(Request $r)
    {
        $c = $r->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:150'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email harus valid, contoh: nama@email.com.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $c['email'] = strtolower(trim((string) $c['email']));

        if (! Auth::attempt($c, $r->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password yang Anda masukkan tidak sesuai.'])->onlyInput('email');
        }

        $r->session()->regenerate();

        $user = $r->user();
        if ($user->isAdmin()) {
            AuditLog::record('ADMIN_LOGIN', "Administrator '{$user->name}' berhasil login ke sistem");

            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended($user->hasActiveSubscription() ? route('home') : route('checkout.page'));
    }

    public function register(Request $r)
    {
        $d = $r->validate([
            'name' => ['required', 'string', 'min:3', 'max:50', "regex:/^[\pL\s\-'.]+$/u"],
            'email' => ['required', 'string', 'email:rfc', 'max:150', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal terdiri dari 3 karakter.',
            'name.max' => 'Nama maksimal 50 karakter.',
            'name.regex' => 'Nama hanya boleh memuat huruf dan spasi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email harus valid, contoh: nama@email.com.',
            'email.regex' => 'Format email harus memiliki domain lengkap, contoh: nama@email.com.',
            'email.unique' => 'Email ini sudah terdaftar di Zeflix. Silakan gunakan email lain atau masuk.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
        ]);

        $d['name'] = trim((string) $d['name']);
        $d['email'] = strtolower(trim((string) $d['email']));

        $user = User::create($d);
        Auth::login($user);
        $r->session()->regenerate();

        return redirect()->intended(route('checkout.page'));
    }

    public function updateProfile(Request $r)
    {
        $data = $r->validate([
            'name' => ['required', 'string', 'min:3', 'max:50', "regex:/^[\pL\s\-'.]+$/u"],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'name.max' => 'Nama maksimal 50 karakter.',
            'name.regex' => 'Nama hanya boleh memuat huruf dan spasi.',
            'password.min' => 'Password baru minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $data['name'] = trim((string) $data['name']);
        if (blank($data['password'])) {
            unset($data['password']);
        }

        $r->user()->update($data);

        if ($r->user()->isAdmin()) {
            AuditLog::record('ADMIN_PROFILE_UPDATE', "Administrator '{$r->user()->name}' memperbarui data profil/password");
        }

        return back()->with('ok', 'Profil berhasil diperbarui.');
    }

    public function logout(Request $r)
    {
        if ($r->user() && $r->user()->isAdmin()) {
            AuditLog::record('ADMIN_LOGOUT', "Administrator '{$r->user()->name}' keluar dari sistem");
        }

        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('home');
    }
}
