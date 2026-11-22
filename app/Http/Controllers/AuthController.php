<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected GoogleSheetService $gs;

    public function __construct(GoogleSheetService $gs)
    {
        $this->gs = $gs;
    }

    public function showLogin()
    {
        // Sudah login -> langsung ke profil
        if (session()->has('user')) {
            return redirect()->route('profile.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi hanya Email dan Username
        $validated = $request->validate([
            'email'    => 'required|email',
            'username' => 'required|string',
        ]);

        // Cari user berdasarkan email
        $users = $this->gs->getWhere('users', 'email', $validated['email']);

        if (empty($users)) {
            return back()->withInput($request->only('email', 'username'))
                ->withErrors(['email' => 'Email belum terdaftar. Silakan daftar dulu.']);
        }

        $user = $users[0];

        // Cocokkan Username (abaikan tanda @ dan huruf besar/kecil)
        $inputUsername  = strtolower(ltrim($validated['username'], '@'));
        $storedUsername = strtolower(ltrim($user['username'] ?? '', '@'));

        if ($inputUsername !== $storedUsername) {
            return back()->withInput($request->only('email', 'username'))
                ->withErrors(['username' => 'Username tidak cocok dengan email ini.']);
        }

        // Update last_login di Google Sheets
        try {
            $this->gs->update('users', $user['id'], ['last_login' => now()->toIso8601String()]);
        } catch (\Throwable $e) {
            Log::warning('Gagal update last_login: ' . $e->getMessage());
        }

        // Simpan session (tanpa password)
        $sessionUser = $user;
        unset($sessionUser['password']);
        session(['user' => $sessionUser]);
        $request->session()->regenerate();

        // Setelah login -> halaman profil
        return redirect()->route('profile.index')
            ->with('success', 'Selamat datang kembali, ' . ($sessionUser['nama'] ?? 'User') . '! 💜');
    }

    public function showRegister()
    {
        if (session()->has('user')) {
            return redirect()->route('profile.index');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|min:2|max:100',
            'username' => 'required|string|min:3|max:30|regex:/^[a-zA-Z0-9_]+$/',
            'email'    => 'required|email|max:150',
        ]);

        $existing = $this->gs->getWhere('users', 'email', $validated['email']);
        if (!empty($existing)) {
            return back()->withInput($request->only('nama', 'username', 'email'))
                ->withErrors(['email' => 'Email sudah terdaftar. Coba login.']);
        }

        $newUserData = [
            'nama'       => $validated['nama'],
            'username'   => '@' . strtolower($validated['username']),
            'email'      => $validated['email'],
            'avatar'     => 'https://i.pravatar.cc/200?u=' . urlencode($validated['email']),
            'bio'        => 'Member baru K-PLAY',
            'bias_id'    => '',
            'followers'  => 0,
            'following'  => 0,
            'joined_at'  => now()->toIso8601String(),
            'last_login' => now()->toIso8601String(),
        ];

        $newId = $this->gs->insert('users', $newUserData);

        if (!$newId) {
            return back()->withInput()->withErrors(['email' => 'Gagal menyimpan akun. Coba lagi.']);
        }

        session(['user' => array_merge(['id' => $newId], $newUserData)]);
        $request->session()->regenerate();

        // Setelah daftar -> halaman profil
        return redirect()->route('profile.index')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $validated['nama'] . '! 🎉');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Kamu sudah logout. Sampai jumpa lagi! 💜');
    }
}
