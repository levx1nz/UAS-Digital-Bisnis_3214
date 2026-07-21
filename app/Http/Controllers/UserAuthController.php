<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.user-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return back()->with('error', 'Email atau password salah.');
    }

    private function redirectByRole(User $user)
    {
        if ($user->isPlatformStaff()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isOrganizer()) {
            if ($user->account_status === 'approved') {
                return redirect()->route('organizer.dashboard')->with('success', 'Selamat datang, ' . ($user->organizer_name ?? $user->name) . '!');
            }
            return redirect()->route('organizer.pending');
        }

        return redirect()->route('home')->with('success', 'Berhasil masuk!');
    }

    public function showRegister()
    {
        return view('auth.user-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|numeric',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Pendaftaran berhasil!');
    }

    public function showRegisterOrganizer()
    {
        return view('auth.organizer-register');
    }

    public function registerOrganizer(Request $request)
    {
        $request->validate([
            'organizer_name' => 'required|string|max:255',
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'no_hp'          => 'required|numeric',
            'password'       => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'organizer_name' => $request->organizer_name,
            'name'           => $request->name,
            'email'          => $request->email,
            'no_hp'          => $request->no_hp,
            'password'       => Hash::make($request->password),
            'role'           => 'organizer',
            'account_status' => 'pending',
        ]);

        Auth::login($user);
        return redirect()->route('organizer.pending');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'user',
                    'no_hp' => null
                ]);
            } else {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            Auth::login($user);
            return $this->redirectByRole($user);

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal masuk menggunakan Google.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah keluar.');
    }
}