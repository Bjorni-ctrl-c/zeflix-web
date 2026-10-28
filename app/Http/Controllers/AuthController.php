<?php

namespace App\Http\Controllers;

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
        $c = $r->validate(['email' => 'required|email', 'password' => 'required']);
        if (! Auth::attempt($c, $r->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }
        $r->session()->regenerate();

        return redirect()->intended($r->user()->hasActiveSubscription() || $r->user()->isAdmin()
            ? route('home')
            : route('checkout.page'));
    }

    public function register(Request $r)
    {
        $d = $r->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        Auth::login(User::create($d));
        $r->session()->regenerate();

        return redirect()->intended(route('checkout.page'));
    }

    public function updateProfile(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:100',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);
        if (blank($data['password'])) {
            unset($data['password']);
        }
        $r->user()->update($data);

        return back()->with('ok', 'Profil berhasil diperbarui.');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('home');
    }
}
