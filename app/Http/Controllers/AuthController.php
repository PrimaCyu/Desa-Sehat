<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\LogAudit;
use App\Models\Pengguna;
use App\Models\Peran;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser();
        }
        return view('auth.login');
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUser();
        }
        return view('auth.register');
    }

    /**
     * Handle registration submission.
     */
    public function register(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|string|size:16|unique:pengguna,username',
            'head_of_family' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'no_kk.unique' => 'Nomor KK ini sudah terdaftar di sistem DesaSehat.',
            'no_kk.size' => 'Nomor KK harus tepat 16 digit.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $wargaRole = Peran::where('nama', 'warga')->firstOrFail();

        // Create family account (Pengguna)
        $pengguna = Pengguna::create([
            'name' => 'Keluarga ' . $request->head_of_family,
            'username' => $request->no_kk,
            'password' => $request->password,
            'peran_id' => $wargaRole->id,
            'kepala_keluarga' => $request->head_of_family,
            'alamat' => $request->address,
            'nomor_telepon' => $request->phone,
        ]);

        // Login user
        Auth::login($pengguna);

        // Log audit event
        LogAudit::log('registrasi', "Keluarga baru mendaftar mandiri. No. KK: {$pengguna->username}", $pengguna->id);

        return redirect()->route('warga.dashboard')->with('success', 'Pendaftaran berhasil! Akun keluarga Anda telah aktif.');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_input' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $credentials['login_input'];
        $password = $credentials['password'];

        // Determine if input is email or username/NIK/Kepala Keluarga/Name
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $fieldType = 'email';
        } else {
            // 1. Check if input is a NIK in anggota_keluarga
            $member = \App\Models\AnggotaKeluarga::where('nik', $loginInput)->first();
            if ($member && $member->pengguna) {
                $loginInput = $member->pengguna->username;
            } else {
                // 2. Check if input is kepala_keluarga, name, or Keluarga {name} (case-insensitive)
                $user = \App\Models\Pengguna::whereRaw('LOWER(kepala_keluarga) = ?', [strtolower($loginInput)])
                    ->orWhereRaw('LOWER(name) = ?', [strtolower($loginInput)])
                    ->orWhereRaw('LOWER(name) = ?', ['keluarga ' . strtolower($loginInput)])
                    ->first();
                if ($user) {
                    $loginInput = $user->username;
                }
            }
            $fieldType = 'username';
        }

        $attempt = Auth::attempt([
            $fieldType => $loginInput,
            'password' => $password
        ], $request->filled('remember'));

        if ($attempt) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            LogAudit::log('login', "User '{$user->name}' ({$user->peran->display_peran}) berhasil login.", $user->id);

            return $this->redirectUser()->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'login_input' => 'Kredensial yang Anda masukkan tidak cocok.',
        ])->onlyInput('login_input');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            LogAudit::log('logout', "User '{$user->name}' berhasil logout.", $user->id);
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Helper to redirect based on user role.
     */
    private function redirectUser()
    {
        $user = Auth::user();
        if ($user->isKader()) {
            return redirect()->intended(route('kader.dashboard'));
        } elseif ($user->isWarga()) {
            return redirect()->intended(route('warga.dashboard'));
        }

        Auth::logout();
        return redirect()->route('login')->with('error', 'Peran tidak valid.');
    }
}
