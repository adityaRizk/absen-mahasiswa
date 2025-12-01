<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // List semua guards yang tersedia
    protected $guards = ['admin', 'dosen', 'mahasiswa'];

    public function showLoginForm()
    {
        return view('auth.login'); // View login tunggal
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'identity' => 'required|string', // Kolom input tunggal untuk NIP/NIM/Email
            'password' => 'required|min:6',
        ]);

        $identity = $request->input('identity');
        $password = $request->input('password');

        // 2. Iterasi dan Coba Login di Setiap Guard
        foreach ($this->guards as $guard) {

            // Tentukan kolom kredensial (identity/email) untuk guard ini
            $credentialField = $this->getCredentialField($guard);

            // Coba otentikasi
            if (Auth::guard($guard)->attempt([$credentialField => $identity, 'password' => $password], $request->remember)) {

                // Login berhasil! Redirect ke dashboard yang sesuai
                return redirect()->intended(route($guard . '.dashboard'));
            }
        }

        // 3. Jika Loop Selesai dan Gagal di semua Guard
        return redirect()->back()->withInput($request->only('identity', 'remember'))
            ->withErrors(['identity' => 'Ada yang salah, periksa kembali identitas anda.']);
    }

    /**
     * Menentukan kolom kredensial yang digunakan oleh setiap Guard.
     */
    protected function getCredentialField(string $guard): string
    {
        // Berdasarkan konfigurasi migration kita:
        if ($guard === 'admin') {
            return 'username'; // Admin menggunakan username
        } elseif ($guard === 'dosen') {
            return 'nip'; // Dosen menggunakan NIP
        } elseif ($guard === 'mahasiswa') {
            return 'nim'; // Mahasiswa menggunakan NIM
        }
        return 'username'; // Default atau fallback
    }
}
